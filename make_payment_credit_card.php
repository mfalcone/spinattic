<?
//ini_set("display_errors", 1);
require_once(realpath($_SERVER["DOCUMENT_ROOT"])."/inc/conex.inc");
require_once (realpath($_SERVER["DOCUMENT_ROOT"])."/inc/functions.inc");
session_start();

	$level_text	= "ADVANCED";
	$nl = 1;
	$credit = 0;
	
	//if(isset($_POST["nl"]) && ($_POST["nl"] == '1' || $_POST["nl"] == '2')){  //PARA CUANDO SE HABILITE PRO *********************************************************************
	if(isset($_POST["nl"]) && ($_POST["nl"] == '1')){	
		$nl = $_POST["nl"];
		if($nl == 2){
			$level_text	= "PRO";
		}
	}else{
		header("Location: ".$http.$_SERVER[HTTP_HOST]."/account");
	}

	$q = $_POST["q"]; //Tomo cantidad de ciclos
	
	$c = '';
	$coupon_id_for_paypal = 'NONE';
	
	//Chequeo si aplicó cupón y si es válido (doble chequeo por hardcoding)
	//Estos valores los aplico al rate, si no se modifican, la fórmula resulta en el rate original
	$cporcentdiscount = 1;
	$cvaluediscount = 0;
	
	
	
	if(isset($_POST["c"]) && $_POST["c"] != ''){
		//Chequeo que no lo haya usado
		$ssqlp = "select * from payments where LCASE(coupon_id) = '".strtolower($_POST["c"])."' and user_id = '".$_SESSION['usr']."'";
		$result = mysql_query($ssqlp);
		if(!($row = mysql_fetch_array($result))){		
		
			$ssqlp = "select * from payments_coupons where LCASE(id) = '".strtolower($_POST["c"])."' and (level = '".$nl."' or level = '0') and now() BETWEEN valid_from AND valid_until";
			$result = mysql_query($ssqlp);
			if($row = mysql_fetch_array($result)){
				$c = $_POST["c"];
				$coupon_id_for_paypal = $c; 
				$ctype = $row["type"];
				$cvalue = $row["value"];
				if($ctype == 'porcent'){
					$cporcentdiscount = 1 - $cvalue / 100;
				}else{
					$cvaluediscount = $cvalue;
				}
			}
		}
		
	}
	
	//Tomo level actual
	$level = get_level($_SESSION['usr']);

	//$allow_renew = allow_renew($_SESSION['usr'], $level); //verificación si deja renovar el level que tengo (1 mes antes del vencimiento)
	//if(($level == 'PRO' || $level == 'ADVANCED' && $nl == 1) && $allow_renew == 0){
	if($level == 'PRO'  && $nl == 1){
		header("Location: ".$http.$_SERVER[HTTP_HOST]."/account");
	}	
	
	//Si soy PRO, no puedo aceptar otra cosa que nl = 2
	if($level == 'PRO' && $nl != 2){
		header("Location: ".$http.$_SERVER[HTTP_HOST]."/account");
	}

	$restrict = 1;
	$page_title = "Spinattic | CHECK OUT";
	require(realpath($_SERVER["DOCUMENT_ROOT"])."/inc/header.php");


	//Check if has credit
	$has_credit = 0;

	//$ssqlp = "select payments.*, DATE_ADD(cycle_end, INTERVAL ".$q." YEAR) as cycle_end_renew , DATEDIFF(cycle_end, now()) as remaining_days,amount/DATEDIFF(cycle_end, cycle_start) as amont_per_day, amount/DATEDIFF(cycle_end, cycle_start) * DATEDIFF(cycle_end, now()) as remaining_credit from payments where user_id = ".$_SESSION['usr']." and (cycle_start <= now() and cycle_end > now() or cycle_start > now()) and upgraded_to_level = '".$level."' order by cycle_start";

	$ssqlp = "select payments.*, DATE_ADD(cycle_end, INTERVAL ".$q." YEAR) as cycle_end_renew , DATEDIFF(cycle_end, now()) as remaining_days, amount/DATEDIFF(cycle_end, cycle_start) * DATEDIFF(cycle_end, now()) as remaining_credit from payments where user_id = ".$_SESSION['usr']." and cycle_start <= now() and cycle_end > now() and upgraded_to_level = '".$level."' and upgraded = 0 ";
	$ssqlp .= "UNION ";
	$ssqlp .= "select payments.*, DATE_ADD(cycle_end, INTERVAL ".$q." YEAR) as cycle_end_renew , DATEDIFF(cycle_end, cycle_start) as remaining_days, amount/DATEDIFF(cycle_end, cycle_start) * DATEDIFF(cycle_end, cycle_start) as remaining_credit from payments where user_id = ".$_SESSION['usr']." and cycle_start > now() and upgraded_to_level = '".$level."' and upgraded = 0 order by id ";	
	
	$result = mysql_query($ssqlp);
	
	
	if($row = mysql_fetch_array($result)){
		$has_credit = 1;
		$credit = $row["remaining_credit"];
		$credit_cycle_start = $row["cycle_start"];
		$credit_cycle_end = $row["cycle_end"];
		$credit_cycle_renew_end = $row["cycle_end_renew"];
	
		//Chequeo si más ciclos pagados
		while($row = mysql_fetch_array($result)){
			$credit = $credit + $row["remaining_credit"]; //Voy acumulando los créditos
			$credit_cycle_end = $row["cycle_end"];  //Voy actualizando la última fecha para el vencimiento
			$credit_cycle_renew_end = $row["cycle_end_renew"]; //Voy actualizando la última fecha para el vencimiento
		}
	}
	
	$credit_cycle_renew_start = $credit_cycle_end;	
	

	//Check if has credit balance
	$credit_b = 0;
	$ssqlp = "select * from payments_credits where user_id = ".$_SESSION['usr']." order by date desc";
	$result = mysql_query($ssqlp);
	
	if($row = mysql_fetch_array($result)){
		$credit_b = $row["credit"];
	}	
	
	//Get NL Rate
	$ssqlp = "select * from payments_rates where level = '".$level_text."'";
	$result = mysql_query($ssqlp);
	if($row = mysql_fetch_array($result)){
		
		if($has_credit == 1 && $level == $level_text){ //Si tengo crédito y el level actual es el mismo que el next estoy agregando un ciclo, sino, estoy haciendo upgrade (resto el crédito, que puede ser cero si soy free)
			$rate = round($row["yearly_rate"] * $q * $cporcentdiscount - $cvaluediscount - $credit_b, 2); //Estoy agregando un ciclo
		}else{
			$rate = round($row["yearly_rate"] * $q * $cporcentdiscount - $cvaluediscount - $credit_b - $credit, 2); //Estoy haciendo upgrade, aplica el posible credito por unused period
		}
		
	}
	
	
	//Tomo los datos del usuario del último pago, si los tiene
	$name = '';
	$address = '';
	$city = '';
	$state = '';
	$zip = '';
	$country = '';
	
	$ssqlp = "select * from payments where user_id = ".$_SESSION['usr']." order by date desc LIMIT 1";
	$result = mysql_query($ssqlp);
	if($row = mysql_fetch_array($result)){
		$name = $row['name'];
		$address = $row['address'];
		$city = $row['city'];
		$state = $row['state'];
		$zip = $row['zip'];
		$country = $row['country'];
	}
	
?>
		<script type="text/javascript" src="https://www.2checkout.com/checkout/api/2co.min.js"></script>

		<script type="text/javascript">

		  // Called when token created successfully.
		  var successCallback = function(data) {
			var myForm = document.getElementById('myCCForm');

			// Set the token as the value for the token input
			myForm.token.value = data.response.token.token;

			//alert(myForm.token.value);

			// IMPORTANT: Here we call `submit()` on the form element directly instead of using jQuery to prevent and infinite token request loop.
			document.getElementById("loading").style.display="none";
			confirmMessage('Attention','This action will charge your credit card with $<?php echo $rate;?>\nAre you sure?', function(){

				showModalMessage('Processing', '');
				//myForm.submit();  //Replaced with Ajax call
				
				var datastring = $("#myCCForm").serialize();
				
				$.ajax({
					url : '<?echo $http;?>'+window.location.hostname+'/ajax_payment.php',
					type: 'POST',
					data: datastring,
					cache : false,
					success : function(response){
						//respuesta = JSON.parse(response);
						if(response != 'success'){
							//document.getElementById("loading").style.display="none";
							showModalMessage('Error', response);
						}else{
							//document.getElementById("loading").style.display="none";
							showModalMessage('Spinattic', 'Thank you for your payment :)');
						}
					}
				});

			});			
			
			
		  };

		  // Called when token creation fails.
		  var errorCallback = function(data) {
			if (data.errorCode === 200) {
			  // This error code indicates that the ajax call failed. We recommend that you retry the token request.
			  showModalMessage("Error", "Please check all your information and try again, or contact site's admin including the Error Code: 200");
			} else {
			  showModalMessage("Error", "Please check all your information and try again, or contact site's admin including the Error Code: " + data.errorCode);
			}
		  };

		  var tokenRequest = function() {
			// Setup token request arguments
			
			var args = {
			  sellerId: "<?php echo $seller_id;?>",
			  publishableKey: "<?php echo $public_key;?>",
			  ccNo: $("#ccNo").val(),
			  cvv: $("#cvv").val(),
			  
			  expMonth: $("#expDate").val().substr(0, 2),
			  expYear: $("#expDate").val().substr(2, 4)
			  
			  
			};

			// Make the token request
			TCO.requestToken(successCallback, errorCallback, args);
		  };

		  $(function() {

			// Pull in the public encryption key for our environment
			TCO.loadPubKey('<?php echo $payment_env;?>');

			$("#myCCForm").submit(function(e) {
				
			  // Call our token request function
			  tokenRequest();

			  // Prevent form from submitting
			  return false;
			});
		  });
		
		
			$(document).ready(function(){

				$("#buttonCloseModal").click(
						function(e){
							e.preventDefault();
							$('.modal-wrapper').fadeOut(200);
							//$('.modal-wrapper').hide();
						});

				$('.onlynum').keyup(function(){
					if($(this).val()=='' || isNaN($(this).val())){
						$(this).addClass("input_error");
		            }else{
		           	 	$(this).removeClass("input_error");
		            }
				});
				
				
				$("#submitter").click(
						function(e){
							document.getElementById("loading").style.display="block";
							//e.preventDefault();

							//Form checking
							
							form_er = 0;

							if(form_er == 0){
								$("#myCCForm input:not([type=hidden])").each(function(){ 
						            if($(this).val() == ''){
						            	$(this).addClass("input_error");
						            	form_er = 1;
						            }else{
						           	 	$(this).removeClass("input_error");
						            }
								 });
								if(form_er == 1){showModalMessage("Error", "Please complete all fields");}
							}

							if(form_er == 0){	
								if($('#country').val() == ''){
									showModalMessage("Error", "Please select a country");
									form_er = 1;
								}
							}
														
							if(form_er == 0){	
								if(isNaN($('#expDate').val()) || $('#expDate').val().length != 6){
									showModalMessage("Error", "Please enter a valid expiration date (format 'MMYYYY')");
									$('#expDate').addClass("input_error");
									form_er = 1;
								}
							}

							if(form_er == 0){	
								if(isNaN($('#ccNo').val())){
									showModalMessage("Error", "Please enter a valid Credit Card number");
									$('#ccNo').addClass("input_error");
									form_er = 1;
								}
							}
							
							if(form_er == 0){	
								if(isNaN($('#cvv').val())){
									showModalMessage("Error", "Please enter a valid security code");
									$('#cvv').addClass("input_error");
									form_er = 1;
								}
							}
							
							if(form_er == 0){	
								if(!($('#cert').is(':checked'))){
								//if(!($('#cert:checked').hasClass('fa-check-square'))){
									showModalMessage("Error", "Please, check on the box to certify that you are an authorized user of this credit card");
									form_er = 1;
								}
							}
														
							//End form checking
							
							if(form_er == 0){
								$("#myCCForm").submit();
							}
							
						});



				$("dt.sec_dt .icon-left").click(function(){
					$("dd.sec_dt").slideToggle();
					$("dt.sec_dt .icon-left").toggleClass("fa-caret-down fa-caret-left")
				})

				$("dt.first_dt .icon-left").click(function(){
					$("dd.first_dt").slideToggle();
					$("dt.first_dt .icon-left").toggleClass("fa-caret-down fa-caret-left")
				})




			});
			
			
			function showModalMessage(type, text){
				document.getElementById("loading").style.display="none";
				if(type == 'Processing'){
					$('.error').hide();
					$('.thanks').hide();
					$('.processing').show();
					$('#buttonModal').hide();
					$('#buttonCloseModal').hide();					
				}else{
					if(type == 'Error'){
						$('.error').html(text);
						$('.error').show();
						$('#buttonModal').hide();
						$('#buttonCloseModal').show();
						$('.thanks').hide();
						$('.processing').hide();
					}else{
						$('.thanks').html(text);
						$('.thanks').show();
						$('#buttonModal').show();
						$('#buttonCloseModal').hide();
						$('.error').hide();
						$('.processing').hide();
					}
				}
				$('.modal-wrapper').fadeIn(200);
			};
	
			function maxLengthCheck(object) {
				if (object.value.length > object.maxLength)
				  object.value = object.value.slice(0, object.maxLength)
			}
		
			  function isNumeric (evt) {
				var theEvent = evt || window.event;
				var key = theEvent.keyCode || theEvent.which;
				key = String.fromCharCode (key);
				var regex = /[0-9]|\./;
				if ( !regex.test(key) ) {
				  theEvent.returnValue = false;
				  if(theEvent.preventDefault) theEvent.preventDefault();
				}
			}
	
			function isDate (evt) {
				var theEvent = evt || window.event;
				var key = theEvent.keyCode || theEvent.which;
				key = String.fromCharCode (key);
				var regex = /[0-9/]|\./;
				if ( !regex.test(key) ) {
				  theEvent.returnValue = false;
				  if(theEvent.preventDefault) theEvent.preventDefault();
				}
			}


						
		</script>
		
		
		<style type="text/css"></style>
		
	
		
		
		<div>
			<h1 class="manage_acount_header">Check Out</h1>
		</div>
		<hr class="space180px">
	   
		<div class="wrapper wrapper-user manage_account">
			<header>
				<h2>User ID: #<?php echo $_SESSION['usr'];?> - "<?php echo $_SESSION['username'];?>"</h2>
				<a href="<?php echo $http.$_SERVER[HTTP_HOST];?>/account/checkout?nl=<?php echo $nl;?>" class="back"><i class="fa fa-chevron-left"></i> back</a>
			</header>
			

			
			<dl>
			
			<form id="myCCForm" action="payment.php" method="post">
			
			   <dt class="first_dt"><i class="fa fa-user"></i>Billing information:<i class="fa fa-caret-down icon-left"></i></dt>
			   <dd class="first_dt">
			   
					<table style="margin-top:-20px">
						<tr>
							<td class="label">Full Name:</td>
							<td>
								<input type="text" name="fname" value="<?php echo $name;?>">
							</td>
						</tr>
						<tr>
							<td class="label">Billing address:</td>
							<td>
								<input name="address" type="text" value="<?php echo $address;?>" id="address">
							</td>
						</tr>
						<tr>
							<td class="label">City:</td>
							<td>
								<input name="city" type="text" value="<?php echo $city;?>" id="city">
							</td>
						</tr>
						<tr>
							<td class="label">State / Province:</td>
							<td>
								<input name="state" type="text" value="<?php echo $state;?>" id="state">
							</td>
						</tr>
						<tr>
							<td class="label">ZIP / Postal code:</td>
							<td>
								<input name="zip" type="text" value="<?php echo $zip;?>" id="zip">
							</td>
						</tr>
						<tr>
							<td class="label">Country:</td>
							<td>
								<select name="country" id="country">
									<option <?if($country == ''){echo 'selected';}?> value="">Select Country</option>
									<option <?if($country == 'Afganistan'){echo 'selected';}?> value="Afganistan">Afganistan</option>
									<option <?if($country == 'Albania'){echo 'selected';}?> value="Albania">Albania</option>
									<option <?if($country == 'Algeria'){echo 'selected';}?> value="Algeria">Algeria</option>
									<option <?if($country == 'American Samoa'){echo 'selected';}?> value="American Samoa">American Samoa</option>
									<option <?if($country == 'Andorra'){echo 'selected';}?> value="Andorra">Andorra</option>
									<option <?if($country == 'Angola'){echo 'selected';}?> value="Angola">Angola</option>
									<option <?if($country == 'Anguilla'){echo 'selected';}?> value="Anguilla">Anguilla</option>
									<option <?if($country == 'Antigua - Barbuda'){echo 'selected';}?> value="Antigua - Barbuda">Antigua - Barbuda</option>
									<option <?if($country == 'Argentina'){echo 'selected';}?> value="Argentina">Argentina</option>
									<option <?if($country == 'Armenia'){echo 'selected';}?> value="Armenia">Armenia</option>
									<option <?if($country == 'Aruba'){echo 'selected';}?> value="Aruba">Aruba</option>
									<option <?if($country == 'Australia'){echo 'selected';}?> value="Australia">Australia</option>
									<option <?if($country == 'Austria'){echo 'selected';}?> value="Austria">Austria</option>
									<option <?if($country == 'Azerbaijan'){echo 'selected';}?> value="Azerbaijan">Azerbaijan</option>
									<option <?if($country == 'Bahamas'){echo 'selected';}?> value="Bahamas">Bahamas</option>
									<option <?if($country == 'Bahrain'){echo 'selected';}?> value="Bahrain">Bahrain</option>
									<option <?if($country == 'Bangladesh'){echo 'selected';}?> value="Bangladesh">Bangladesh</option>
									<option <?if($country == 'Barbados'){echo 'selected';}?> value="Barbados">Barbados</option>
									<option <?if($country == 'Belarus'){echo 'selected';}?> value="Belarus">Belarus</option>
									<option <?if($country == 'Belgium'){echo 'selected';}?> value="Belgium">Belgium</option>
									<option <?if($country == 'Belize'){echo 'selected';}?> value="Belize">Belize</option>
									<option <?if($country == 'Benin'){echo 'selected';}?> value="Benin">Benin</option>
									<option <?if($country == 'Bermuda'){echo 'selected';}?> value="Bermuda">Bermuda</option>
									<option <?if($country == 'Bhutan'){echo 'selected';}?> value="Bhutan">Bhutan</option>
									<option <?if($country == 'Bolivia'){echo 'selected';}?> value="Bolivia">Bolivia</option>
									<option <?if($country == 'Bonaire'){echo 'selected';}?> value="Bonaire">Bonaire</option>
									<option <?if($country == 'Bosnia - Herzegovina'){echo 'selected';}?> value="Bosnia - Herzegovina">Bosnia - Herzegovina</option>
									<option <?if($country == 'Botswana'){echo 'selected';}?> value="Botswana">Botswana</option>
									<option <?if($country == 'Brazil'){echo 'selected';}?> value="Brazil">Brazil</option>
									<option <?if($country == 'British Indian Ocean Ter'){echo 'selected';}?> value="British Indian Ocean Ter">British Indian Ocean Ter</option>
									<option <?if($country == 'Brunei'){echo 'selected';}?> value="Brunei">Brunei</option>
									<option <?if($country == 'Bulgaria'){echo 'selected';}?> value="Bulgaria">Bulgaria</option>
									<option <?if($country == 'Burkina Faso'){echo 'selected';}?> value="Burkina Faso">Burkina Faso</option>
									<option <?if($country == 'Burundi'){echo 'selected';}?> value="Burundi">Burundi</option>
									<option <?if($country == 'Cambodia'){echo 'selected';}?> value="Cambodia">Cambodia</option>
									<option <?if($country == 'Cameroon'){echo 'selected';}?> value="Cameroon">Cameroon</option>
									<option <?if($country == 'Canada'){echo 'selected';}?> value="Canada">Canada</option>
									<option <?if($country == 'Canary Islands'){echo 'selected';}?> value="Canary Islands">Canary Islands</option>
									<option <?if($country == 'Cape Verde'){echo 'selected';}?> value="Cape Verde">Cape Verde</option>
									<option <?if($country == 'Cayman Islands'){echo 'selected';}?> value="Cayman Islands">Cayman Islands</option>
									<option <?if($country == 'Central African Republic'){echo 'selected';}?> value="Central African Republic">Central African Republic</option>
									<option <?if($country == 'Chad'){echo 'selected';}?> value="Chad">Chad</option>
									<option <?if($country == 'Channel Islands'){echo 'selected';}?> value="Channel Islands">Channel Islands</option>
									<option <?if($country == 'Chile'){echo 'selected';}?> value="Chile">Chile</option>
									<option <?if($country == 'China'){echo 'selected';}?> value="China">China</option>
									<option <?if($country == 'Christmas Island'){echo 'selected';}?> value="Christmas Island">Christmas Island</option>
									<option <?if($country == 'Cocos Island'){echo 'selected';}?> value="Cocos Island">Cocos Island</option>
									<option <?if($country == 'Colombia'){echo 'selected';}?> value="Colombia">Colombia</option>
									<option <?if($country == 'Comoros'){echo 'selected';}?> value="Comoros">Comoros</option>
									<option <?if($country == 'Congo'){echo 'selected';}?> value="Congo">Congo</option>
									<option <?if($country == 'Cook Islands'){echo 'selected';}?> value="Cook Islands">Cook Islands</option>
									<option <?if($country == 'Costa Rica'){echo 'selected';}?> value="Costa Rica">Costa Rica</option>
									<option <?if($country == 'Cote DIvoire'){echo 'selected';}?> value="Cote DIvoire">Cote D'Ivoire</option>
									<option <?if($country == 'Croatia'){echo 'selected';}?> value="Croatia">Croatia</option>
									<option <?if($country == 'Cuba'){echo 'selected';}?> value="Cuba">Cuba</option>
									<option <?if($country == 'Curaco'){echo 'selected';}?> value="Curaco">Curacao</option>
									<option <?if($country == 'Cyprus'){echo 'selected';}?> value="Cyprus">Cyprus</option>
									<option <?if($country == 'Czech Republic'){echo 'selected';}?> value="Czech Republic">Czech Republic</option>
									<option <?if($country == 'Denmark'){echo 'selected';}?> value="Denmark">Denmark</option>
									<option <?if($country == 'Djibouti'){echo 'selected';}?> value="Djibouti">Djibouti</option>
									<option <?if($country == 'Dominica'){echo 'selected';}?> value="Dominica">Dominica</option>
									<option <?if($country == 'Dominican Republic'){echo 'selected';}?> value="Dominican Republic">Dominican Republic</option>
									<option <?if($country == 'East Timor'){echo 'selected';}?> value="East Timor">East Timor</option>
									<option <?if($country == 'Ecuador'){echo 'selected';}?> value="Ecuador">Ecuador</option>
									<option <?if($country == 'Egypt'){echo 'selected';}?> value="Egypt">Egypt</option>
									<option <?if($country == 'El Salvador'){echo 'selected';}?> value="El Salvador">El Salvador</option>
									<option <?if($country == 'Equatorial Guinea'){echo 'selected';}?> value="Equatorial Guinea">Equatorial Guinea</option>
									<option <?if($country == 'Eritrea'){echo 'selected';}?> value="Eritrea">Eritrea</option>
									<option <?if($country == 'Estonia'){echo 'selected';}?> value="Estonia">Estonia</option>
									<option <?if($country == 'Ethiopia'){echo 'selected';}?> value="Ethiopia">Ethiopia</option>
									<option <?if($country == 'Falkland Islands'){echo 'selected';}?> value="Falkland Islands">Falkland Islands</option>
									<option <?if($country == 'Faroe Islands'){echo 'selected';}?> value="Faroe Islands">Faroe Islands</option>
									<option <?if($country == 'Fiji'){echo 'selected';}?> value="Fiji">Fiji</option>
									<option <?if($country == 'Finland'){echo 'selected';}?> value="Finland">Finland</option>
									<option <?if($country == 'France'){echo 'selected';}?> value="France">France</option>
									<option <?if($country == 'French Guiana'){echo 'selected';}?> value="French Guiana">French Guiana</option>
									<option <?if($country == 'French Polynesia'){echo 'selected';}?> value="French Polynesia">French Polynesia</option>
									<option <?if($country == 'French Southern Ter'){echo 'selected';}?> value="French Southern Ter">French Southern Ter</option>
									<option <?if($country == 'Gabon'){echo 'selected';}?> value="Gabon">Gabon</option>
									<option <?if($country == 'Gambia'){echo 'selected';}?> value="Gambia">Gambia</option>
									<option <?if($country == 'Georgia'){echo 'selected';}?> value="Georgia">Georgia</option>
									<option <?if($country == 'Germany'){echo 'selected';}?> value="Germany">Germany</option>
									<option <?if($country == 'Ghana'){echo 'selected';}?> value="Ghana">Ghana</option>
									<option <?if($country == 'Gibraltar'){echo 'selected';}?> value="Gibraltar">Gibraltar</option>
									<option <?if($country == 'Great Britain'){echo 'selected';}?> value="Great Britain">Great Britain</option>
									<option <?if($country == 'Greece'){echo 'selected';}?> value="Greece">Greece</option>
									<option <?if($country == 'Greenland'){echo 'selected';}?> value="Greenland">Greenland</option>
									<option <?if($country == 'Grenada'){echo 'selected';}?> value="Grenada">Grenada</option>
									<option <?if($country == 'Guadeloupe'){echo 'selected';}?> value="Guadeloupe">Guadeloupe</option>
									<option <?if($country == 'Guam'){echo 'selected';}?> value="Guam">Guam</option>
									<option <?if($country == 'Guatemala'){echo 'selected';}?> value="Guatemala">Guatemala</option>
									<option <?if($country == 'Guinea'){echo 'selected';}?> value="Guinea">Guinea</option>
									<option <?if($country == 'Guyana'){echo 'selected';}?> value="Guyana">Guyana</option>
									<option <?if($country == 'Haiti'){echo 'selected';}?> value="Haiti">Haiti</option>
									<option <?if($country == 'Hawaii'){echo 'selected';}?> value="Hawaii">Hawaii</option>
									<option <?if($country == 'Honduras'){echo 'selected';}?> value="Honduras">Honduras</option>
									<option <?if($country == 'Hong Kong'){echo 'selected';}?> value="Hong Kong">Hong Kong</option>
									<option <?if($country == 'Hungary'){echo 'selected';}?> value="Hungary">Hungary</option>
									<option <?if($country == 'Iceland'){echo 'selected';}?> value="Iceland">Iceland</option>
									<option <?if($country == 'India'){echo 'selected';}?> value="India">India</option>
									<option <?if($country == 'Indonesia'){echo 'selected';}?> value="Indonesia">Indonesia</option>
									<option <?if($country == 'Iran'){echo 'selected';}?> value="Iran">Iran</option>
									<option <?if($country == 'Iraq'){echo 'selected';}?> value="Iraq">Iraq</option>
									<option <?if($country == 'Ireland'){echo 'selected';}?> value="Ireland">Ireland</option>
									<option <?if($country == 'Isle of Man'){echo 'selected';}?> value="Isle of Man">Isle of Man</option>
									<option <?if($country == 'Israel'){echo 'selected';}?> value="Israel">Israel</option>
									<option <?if($country == 'Italy'){echo 'selected';}?> value="Italy">Italy</option>
									<option <?if($country == 'Jamaica'){echo 'selected';}?> value="Jamaica">Jamaica</option>
									<option <?if($country == 'Japan'){echo 'selected';}?> value="Japan">Japan</option>
									<option <?if($country == 'Jordan'){echo 'selected';}?> value="Jordan">Jordan</option>
									<option <?if($country == 'Kazakhstan'){echo 'selected';}?> value="Kazakhstan">Kazakhstan</option>
									<option <?if($country == 'Kenya'){echo 'selected';}?> value="Kenya">Kenya</option>
									<option <?if($country == 'Kiribati'){echo 'selected';}?> value="Kiribati">Kiribati</option>
									<option <?if($country == 'Korea North'){echo 'selected';}?> value="Korea North">Korea North</option>
									<option <?if($country == 'Korea Sout'){echo 'selected';}?> value="Korea Sout">Korea South</option>
									<option <?if($country == 'Kuwait'){echo 'selected';}?> value="Kuwait">Kuwait</option>
									<option <?if($country == 'Kyrgyzstan'){echo 'selected';}?> value="Kyrgyzstan">Kyrgyzstan</option>
									<option <?if($country == 'Laos'){echo 'selected';}?> value="Laos">Laos</option>
									<option <?if($country == 'Latvia'){echo 'selected';}?> value="Latvia">Latvia</option>
									<option <?if($country == 'Lebanon'){echo 'selected';}?> value="Lebanon">Lebanon</option>
									<option <?if($country == 'Lesotho'){echo 'selected';}?> value="Lesotho">Lesotho</option>
									<option <?if($country == 'Liberia'){echo 'selected';}?> value="Liberia">Liberia</option>
									<option <?if($country == 'Libya'){echo 'selected';}?> value="Libya">Libya</option>
									<option <?if($country == 'Liechtenstein'){echo 'selected';}?> value="Liechtenstein">Liechtenstein</option>
									<option <?if($country == 'Lithuania'){echo 'selected';}?> value="Lithuania">Lithuania</option>
									<option <?if($country == 'Luxembourg'){echo 'selected';}?> value="Luxembourg">Luxembourg</option>
									<option <?if($country == 'Macau'){echo 'selected';}?> value="Macau">Macau</option>
									<option <?if($country == 'Macedonia'){echo 'selected';}?> value="Macedonia">Macedonia</option>
									<option <?if($country == 'Madagascar'){echo 'selected';}?> value="Madagascar">Madagascar</option>
									<option <?if($country == 'Malaysia'){echo 'selected';}?> value="Malaysia">Malaysia</option>
									<option <?if($country == 'Malawi'){echo 'selected';}?> value="Malawi">Malawi</option>
									<option <?if($country == 'Maldives'){echo 'selected';}?> value="Maldives">Maldives</option>
									<option <?if($country == 'Mali'){echo 'selected';}?> value="Mali">Mali</option>
									<option <?if($country == 'Malta'){echo 'selected';}?> value="Malta">Malta</option>
									<option <?if($country == 'Marshall Islands'){echo 'selected';}?> value="Marshall Islands">Marshall Islands</option>
									<option <?if($country == 'Martinique'){echo 'selected';}?> value="Martinique">Martinique</option>
									<option <?if($country == 'Mauritania'){echo 'selected';}?> value="Mauritania">Mauritania</option>
									<option <?if($country == 'Mauritius'){echo 'selected';}?> value="Mauritius">Mauritius</option>
									<option <?if($country == 'Mayotte'){echo 'selected';}?> value="Mayotte">Mayotte</option>
									<option <?if($country == 'Mexico'){echo 'selected';}?> value="Mexico">Mexico</option>
									<option <?if($country == 'Midway Islands'){echo 'selected';}?> value="Midway Islands">Midway Islands</option>
									<option <?if($country == 'Moldova'){echo 'selected';}?> value="Moldova">Moldova</option>
									<option <?if($country == 'Monaco'){echo 'selected';}?> value="Monaco">Monaco</option>
									<option <?if($country == 'Mongolia'){echo 'selected';}?> value="Mongolia">Mongolia</option>
									<option <?if($country == 'Montserrat'){echo 'selected';}?> value="Montserrat">Montserrat</option>
									<option <?if($country == 'Morocco'){echo 'selected';}?> value="Morocco">Morocco</option>
									<option <?if($country == 'Mozambique'){echo 'selected';}?> value="Mozambique">Mozambique</option>
									<option <?if($country == 'Myanmar'){echo 'selected';}?> value="Myanmar">Myanmar</option>
									<option <?if($country == 'Nambia'){echo 'selected';}?> value="Nambia">Nambia</option>
									<option <?if($country == 'Nauru'){echo 'selected';}?> value="Nauru">Nauru</option>
									<option <?if($country == 'Nepal'){echo 'selected';}?> value="Nepal">Nepal</option>
									<option <?if($country == 'Netherland Antilles'){echo 'selected';}?> value="Netherland Antilles">Netherland Antilles</option>
									<option <?if($country == 'Netherlands'){echo 'selected';}?> value="Netherlands">Netherlands (Holland, Europe)</option>
									<option <?if($country == 'Nevis'){echo 'selected';}?> value="Nevis">Nevis</option>
									<option <?if($country == 'New Caledonia'){echo 'selected';}?> value="New Caledonia">New Caledonia</option>
									<option <?if($country == 'New Zealand'){echo 'selected';}?> value="New Zealand">New Zealand</option>
									<option <?if($country == 'Nicaragua'){echo 'selected';}?> value="Nicaragua">Nicaragua</option>
									<option <?if($country == 'Niger'){echo 'selected';}?> value="Niger">Niger</option>
									<option <?if($country == 'Nigeria'){echo 'selected';}?> value="Nigeria">Nigeria</option>
									<option <?if($country == 'Niue'){echo 'selected';}?> value="Niue">Niue</option>
									<option <?if($country == 'Norfolk Island'){echo 'selected';}?> value="Norfolk Island">Norfolk Island</option>
									<option <?if($country == 'Norway'){echo 'selected';}?> value="Norway">Norway</option>
									<option <?if($country == 'Oman'){echo 'selected';}?> value="Oman">Oman</option>
									<option <?if($country == 'Pakistan'){echo 'selected';}?> value="Pakistan">Pakistan</option>
									<option <?if($country == 'Palau Island'){echo 'selected';}?> value="Palau Island">Palau Island</option>
									<option <?if($country == 'Palestine'){echo 'selected';}?> value="Palestine">Palestine</option>
									<option <?if($country == 'Panama'){echo 'selected';}?> value="Panama">Panama</option>
									<option <?if($country == 'Papua New Guinea'){echo 'selected';}?> value="Papua New Guinea">Papua New Guinea</option>
									<option <?if($country == 'Paraguay'){echo 'selected';}?> value="Paraguay">Paraguay</option>
									<option <?if($country == 'Peru'){echo 'selected';}?> value="Peru">Peru</option>
									<option <?if($country == 'Phillipines'){echo 'selected';}?> value="Phillipines">Philippines</option>
									<option <?if($country == 'Pitcairn Island'){echo 'selected';}?> value="Pitcairn Island">Pitcairn Island</option>
									<option <?if($country == 'Poland'){echo 'selected';}?> value="Poland">Poland</option>
									<option <?if($country == 'Portugal'){echo 'selected';}?> value="Portugal">Portugal</option>
									<option <?if($country == 'Puerto Rico'){echo 'selected';}?> value="Puerto Rico">Puerto Rico</option>
									<option <?if($country == 'Qatar'){echo 'selected';}?> value="Qatar">Qatar</option>
									<option <?if($country == 'Republic of Montenegro'){echo 'selected';}?> value="Republic of Montenegro">Republic of Montenegro</option>
									<option <?if($country == 'Republic of Serbia'){echo 'selected';}?> value="Republic of Serbia">Republic of Serbia</option>
									<option <?if($country == 'Reunion'){echo 'selected';}?> value="Reunion">Reunion</option>
									<option <?if($country == 'Romania'){echo 'selected';}?> value="Romania">Romania</option>
									<option <?if($country == 'Russia'){echo 'selected';}?> value="Russia">Russia</option>
									<option <?if($country == 'Rwanda'){echo 'selected';}?> value="Rwanda">Rwanda</option>
									<option <?if($country == 'St Barthelemy'){echo 'selected';}?> value="St Barthelemy">St Barthelemy</option>
									<option <?if($country == 'St Eustatius'){echo 'selected';}?> value="St Eustatius">St Eustatius</option>
									<option <?if($country == 'St Helena'){echo 'selected';}?> value="St Helena">St Helena</option>
									<option <?if($country == 'St Kitts-Nevis'){echo 'selected';}?> value="St Kitts-Nevis">St Kitts-Nevis</option>
									<option <?if($country == 'St Lucia'){echo 'selected';}?> value="St Lucia">St Lucia</option>
									<option <?if($country == 'St Maarten'){echo 'selected';}?> value="St Maarten">St Maarten</option>
									<option <?if($country == 'St Pierre - Miquelon'){echo 'selected';}?> value="St Pierre - Miquelon">St Pierre - Miquelon</option>
									<option <?if($country == 'St Vincent - Grenadines'){echo 'selected';}?> value="St Vincent - Grenadines">St Vincent - Grenadines</option>
									<option <?if($country == 'Saipan'){echo 'selected';}?> value="Saipan">Saipan</option>
									<option <?if($country == 'Samoa'){echo 'selected';}?> value="Samoa">Samoa</option>
									<option <?if($country == 'Samoa American'){echo 'selected';}?> value="Samoa American">Samoa American</option>
									<option <?if($country == 'San Marino'){echo 'selected';}?> value="San Marino">San Marino</option>
									<option <?if($country == 'Sao Tome - Principe'){echo 'selected';}?> value="Sao Tome - Principe">Sao Tome - Principe</option>
									<option <?if($country == 'Saudi Arabia'){echo 'selected';}?> value="Saudi Arabia">Saudi Arabia</option>
									<option <?if($country == 'Senegal'){echo 'selected';}?> value="Senegal">Senegal</option>
									<option <?if($country == 'Serbia'){echo 'selected';}?> value="Serbia">Serbia</option>
									<option <?if($country == 'Seychelles'){echo 'selected';}?> value="Seychelles">Seychelles</option>
									<option <?if($country == 'Sierra Leone'){echo 'selected';}?> value="Sierra Leone">Sierra Leone</option>
									<option <?if($country == 'Singapore'){echo 'selected';}?> value="Singapore">Singapore</option>
									<option <?if($country == 'Slovakia'){echo 'selected';}?> value="Slovakia">Slovakia</option>
									<option <?if($country == 'Slovenia'){echo 'selected';}?> value="Slovenia">Slovenia</option>
									<option <?if($country == 'Solomon Islands'){echo 'selected';}?> value="Solomon Islands">Solomon Islands</option>
									<option <?if($country == 'Somalia'){echo 'selected';}?> value="Somalia">Somalia</option>
									<option <?if($country == 'South Africa'){echo 'selected';}?> value="South Africa">South Africa</option>
									<option <?if($country == 'Spain'){echo 'selected';}?> value="Spain">Spain</option>
									<option <?if($country == 'Sri Lanka'){echo 'selected';}?> value="Sri Lanka">Sri Lanka</option>
									<option <?if($country == 'Sudan'){echo 'selected';}?> value="Sudan">Sudan</option>
									<option <?if($country == 'Suriname'){echo 'selected';}?> value="Suriname">Suriname</option>
									<option <?if($country == 'Swaziland'){echo 'selected';}?> value="Swaziland">Swaziland</option>
									<option <?if($country == 'Sweden'){echo 'selected';}?> value="Sweden">Sweden</option>
									<option <?if($country == 'Switzerland'){echo 'selected';}?> value="Switzerland">Switzerland</option>
									<option <?if($country == 'Syria'){echo 'selected';}?> value="Syria">Syria</option>
									<option <?if($country == 'Tahiti'){echo 'selected';}?> value="Tahiti">Tahiti</option>
									<option <?if($country == 'Taiwan'){echo 'selected';}?> value="Taiwan">Taiwan</option>
									<option <?if($country == 'Tajikistan'){echo 'selected';}?> value="Tajikistan">Tajikistan</option>
									<option <?if($country == 'Tanzania'){echo 'selected';}?> value="Tanzania">Tanzania</option>
									<option <?if($country == 'Thailand'){echo 'selected';}?> value="Thailand">Thailand</option>
									<option <?if($country == 'Togo'){echo 'selected';}?> value="Togo">Togo</option>
									<option <?if($country == 'Tokelau'){echo 'selected';}?> value="Tokelau">Tokelau</option>
									<option <?if($country == 'Tonga'){echo 'selected';}?> value="Tonga">Tonga</option>
									<option <?if($country == 'Trinidad - Tobago'){echo 'selected';}?> value="Trinidad - Tobago">Trinidad - Tobago</option>
									<option <?if($country == 'Tunisia'){echo 'selected';}?> value="Tunisia">Tunisia</option>
									<option <?if($country == 'Turkey'){echo 'selected';}?> value="Turkey">Turkey</option>
									<option <?if($country == 'Turkmenistan'){echo 'selected';}?> value="Turkmenistan">Turkmenistan</option>
									<option <?if($country == 'Turks - Caicos Is'){echo 'selected';}?> value="Turks - Caicos Is">Turks - Caicos Is</option>
									<option <?if($country == 'Tuvalu'){echo 'selected';}?> value="Tuvalu">Tuvalu</option>
									<option <?if($country == 'Uganda'){echo 'selected';}?> value="Uganda">Uganda</option>
									<option <?if($country == 'Ukraine'){echo 'selected';}?> value="Ukraine">Ukraine</option>
									<option <?if($country == 'United Arab Erimates'){echo 'selected';}?> value="United Arab Erimates">United Arab Emirates</option>
									<option <?if($country == 'United Kingdom'){echo 'selected';}?> value="United Kingdom">United Kingdom</option>
									<option <?if($country == 'United States of America'){echo 'selected';}?> value="United States of America">United States of America</option>
									<option <?if($country == 'Uraguay'){echo 'selected';}?> value="Uraguay">Uruguay</option>
									<option <?if($country == 'Uzbekistan'){echo 'selected';}?> value="Uzbekistan">Uzbekistan</option>
									<option <?if($country == 'Vanuatu'){echo 'selected';}?> value="Vanuatu">Vanuatu</option>
									<option <?if($country == 'Vatican City State'){echo 'selected';}?> value="Vatican City State">Vatican City State</option>
									<option <?if($country == 'Venezuela'){echo 'selected';}?> value="Venezuela">Venezuela</option>
									<option <?if($country == 'Vietnam'){echo 'selected';}?> value="Vietnam">Vietnam</option>
									<option <?if($country == 'Virgin Islands (Brit)'){echo 'selected';}?> value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
									<option <?if($country == 'Virgin Islands (USA)'){echo 'selected';}?> value="Virgin Islands (USA)">Virgin Islands (USA)</option>
									<option <?if($country == 'Wake Island'){echo 'selected';}?> value="Wake Island">Wake Island</option>
									<option <?if($country == 'Wallis - Futana Is'){echo 'selected';}?> value="Wallis - Futana Is">Wallis - Futana Is</option>
									<option <?if($country == 'Yemen'){echo 'selected';}?> value="Yemen">Yemen</option>
									<option <?if($country == 'Zaire'){echo 'selected';}?> value="Zaire">Zaire</option>
									<option <?if($country == 'Zambia'){echo 'selected';}?> value="Zambia">Zambia</option>
									<option <?if($country == 'Zimbabwe'){echo 'selected';}?> value="Zimbabwe">Zimbabwe</option>								
								</select>
							</td>
						</tr>
				   </table>			   
			   
			   </dd>
			
			</form>
			
			
	<?php /*
			   <dt class="sec_dt"><i class="fa fa-credit-card"></i>Credit card:<i class="fa fa-caret-left icon-left"></i></dt>
				<dd class="sec_dt" style="display: none;"> 
					<input name="token" type="hidden" value="." />
					<input name="nl" type="hidden" value="<?php echo $nl;?>" />
					
					<input type="hidden" value="<?php echo $q;?>" name="q" id="q">
					<input type="hidden" value="<?php echo $c;?>" name="c" id="c">
					
					<table style="margin-top:-20px">
					   <tr>
							<td class="label">Credit Card Number:</td>
							<td>
								<input value="<?php if($environment == 'dev'){echo '4000000000000002';}?>" maxlength="16" oninput="maxLengthCheck(this)" id="ccNo" autocomplete="off" required class="onlynum">
							</td>
						</tr>
						<tr>
							<td class="label">Name on card:</td>
							<td>
								<input type="text" name="name" value="<?php if($environment == 'dev'){echo 'Hernan';}?>">
							</td>
						</tr>
						<tr>
							<td class="label">Expiration date: <span>MMYYYY</span></td>
							<td>
								<input id="expDate"  value="<?php if($environment == 'dev'){echo '072015';}?>" type="text" size="6" maxlength="6" oninput="maxLengthCheck(this)" autocomplete="off" class="onlynum">
								
								<!-- input id="expMonth" value="07" type="text" size="2" maxlength="2" required class="short-input" oninput="maxLengthCheck(this)">
								<span> / </span>
								<input id="expYear" value="2016" type="text" size="4" maxlength="4" required class="short-input" oninput="maxLengthCheck(this)"-->
							</td>
						</tr>
						<tr>
							<td class="label">Card Security code:</td>
							<td>
								<input id="cvv" value="<?php if($environment == 'dev'){echo '132';}?>"  maxlength="4" class="short-input onlynum" oninput="maxLengthCheck(this)" autocomplete="off">
							</td>
						</tr>
				   </table>
				   
				  
				   <div class="certificate">
					<input type="checkbox" id="cert">  I certify that I am an authorized user of the credit card specified above, and I authorize Spinattic LLC to automatically charge this credit card for any products purchased, recurring service charges, or fees assessed on my Spinattic account #<?php echo $_SESSION['usr'];?>.
				   </div>
						   <footer style="max-width:505px">
							   <div class="owe owe-large">
									Total: $<?php echo $rate;?>.-
								</div>
								<a href="#" class="makepay makepay-large" id="submitter">pay now</a>
						   </footer>

			   </dd>
			   */?>
			   
			   <dt class="sec_dt"><i class="fa fa-paypal"></i>Paypal:<!-- i class="fa fa-caret-down icon-left"></i--></dt>
			   <dd class="sec_dt pay-pal">
			   
			   <?php $pp_button_id = 'KD5YCG464YLZW';?>
			   
			   <footer style="max-width:505px">
				   <div class="owe owe-large">
						Total: $<?php echo $rate;?>.-
					</div>
					
						<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
						<input type="hidden" name="cmd" value="_s-xclick">
						<input type="hidden" name="hosted_button_id" value="<?php echo $pp_button_id;?>">
						<input type="hidden" name="on0" value="options">
						<input type="hidden" name="os0" value="<?php echo $coupon_id_for_paypal;?>">
						<input type="hidden" name="currency_code" value="USD">
						<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_paynow_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
						<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
						</form>
													
			   </footer>			   
			   
							   

			   </dd>
		   </dl>

	   		<div class="modal-wrapper" style="display:none;">
				<div class="modal">
					<h2 class="processing">Processing payment...</h2>
					<p class="processing">Please, don't leave or close this page.</p>
					<p class="error" style="display:none;">Error message. Mensaje de error</p>
					<p class="thanks" style="display:none;">Thank you for your payment. ;)</p>
					<a href="<?php echo $http.$_SERVER[HTTP_HOST];?>/account" id="buttonModal" class="buttonModal ok">ok</a>
					<a href="" id="buttonCloseModal" class="buttonModal ok">ok</a>
				</div>
			</div>
					
		</div>
	<?php require_once(realpath($_SERVER["DOCUMENT_ROOT"])."/inc/footer.php");?>

</html>



