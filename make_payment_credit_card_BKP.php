<?
  
	$restrict = 1;
	$page_title = "Spinattic | CHECK OUT";
	require("inc/header.php");

?>
		<script type="text/javascript" src="https://www.2checkout.com/checkout/api/2co.min.js"></script>

		<script type="text/javascript">

		  // Called when token created successfully.
		  var successCallback = function(data) {
			var myForm = document.getElementById('myCCForm');

			// Set the token as the value for the token input
			myForm.token.value = data.response.token.token;

			alert(myForm.token.value);

			// IMPORTANT: Here we call `submit()` on the form element directly instead of using jQuery to prevent and infinite token request loop.
			myForm.submit();
		  };

		  // Called when token creation fails.
		  var errorCallback = function(data) {
			if (data.errorCode === 200) {
			  // This error code indicates that the ajax call failed. We recommend that you retry the token request.
			} else {
			  alert(data.errorMsg);
			}
		  };

		  var tokenRequest = function() {
			// Setup token request arguments
			var args = {
			  sellerId: "1817037",
			  publishableKey: "E9DC86CA-D9FE-4473-93D4-1586F411E979",
			  ccNo: $("#ccNo").val(),
			  cvv: $("#cvv").val(),
			  expMonth: $("#expMonth").val(),
			  expYear: $("#expYear").val()
			};

			// Make the token request
			TCO.requestToken(successCallback, errorCallback, args);
		  };

		  $(function() {
			// Pull in the public encryption key for our environment
			TCO.loadPubKey('sandbox');

			$("#myCCForm").submit(function(e) {
				
			  // Call our token request function
			  tokenRequest();

			  // Prevent form from submitting
			  return false;
			});
		  });

		</script>


		<style type="text/css"></style>
		<div>
			<h1 class="manage_acount_header">Check Out</h1>
		</div>
		<hr class="space180px">
	   
		<div class="wrapper wrapper-user manage_account">
			<header>
				<h2>User ID: #999 - "Ariel Micheletti"</h2>
				<a href="manager_account.php" class="back"><i class="fa fa-chevron-left"></i> back</a>
			</header>
			<dl>
			   <dt><i class="fa fa-credit-card"></i>Credit card:<i class="fa fa-caret-down icon-left"></i></dt>
				<dd>
					<form id="myCCForm" action="get_cc_token.php" method="post">
					<input name="token" type="hidden" value="" />
					<table style="margin-top:-20px">
					   <tr>
							<td class="label">Credit Card Number:</td>
							<td>
								<input type="number" value="123456789" maxlength="16" onkeypress="return isNumeric(event)" oninput="maxLengthCheck(this)" id="ccNo" autocomplete="off" required>
							</td>
						</tr>
						<tr>
							<td class="label">Name on card:</td>
							<td>
								<input type="text" value="Ariel Micheletti" class="input_error">
							</td>
						</tr>
						<tr>
							<td class="label">Expiration date: <span>MM/YYYY</span></td>
							<td>
								<input id="expMonth" type="text" size="2" maxlength="2" required class="short-input" onkeypress="return isDate(event)" oninput="maxLengthCheck(this)">
								<!--input type="text" id="expMonth" maxlength="2" class="short-input"  onkeypress="return isDate(event)" oninput="maxLengthCheck(this)"-->
								<span> / </span>
								<input id="expYear" type="text" size="4" maxlength="4" required class="short-input" onkeypress="return isDate(event)" oninput="maxLengthCheck(this)">
								<!--input id="expYear" type="text" size="4" required /-->
							</td>
						</tr>
						<tr>
							<td class="label">Card Security code:</td>
							<td>
								<input id="cvv" type="number" value="399"  maxlength="3" class="short-input" onkeypress="return isNumeric(event)" oninput="maxLengthCheck(this)" autocomplete="off" required>
							</td>
						</tr>
						<tr>
							<td class="label">Billing address:</td>
							<td>
								<input type="text" value="Av. Pellegrini 74 2º B">
							</td>
						</tr>
						<tr>
							<td class="label">&nbsp;</td>
							<td>
								<input type="text" value="">
							</td>
						</tr>
						<tr>
							<td class="label">City:</td>
							<td>
								<input type="text" value="Rosario">
							</td>
						</tr>
						<tr>
							<td class="label">State:</td>
							<td>
								<input type="text" value="Santa Fe">
							</td>
						</tr>
						<tr>
							<td class="label">ZIP/ Postal code:</td>
							<td>
								<input type="text" value="2000">
							</td>
						</tr>
						<tr>
							<td class="label">Country:</td>
							<td>
								<select name="country" id="country">
									<option value="">Argentina</option>
									<option value="">USA</option>
								</select>
								
								<input type="submit" value="Submit Payment" />
								
							</td>
						</tr>
				   </table>
				   
				   </form>
				   <div class="certificate">
					<i class="fa fa-square"></i> I certify that I am an authorized user of the credit card specified above, and I authorize Spinattic LLC to automatically charge this credit card for any products purchased, recurring service charges, or fees assessed on my Spinattic account #999.
				   </div>
						   <footer style="max-width:505px">
							   <div class="owe owe-large">
									Total: $0.-
								</div>
								<a href="#" class="makepay makepay-large">pay now</a>
						   </footer>

			   </dd>
			   <dt><i class="fa fa-paypal"></i>Paypal:<i class="fa fa-caret-left icon-left"></i></dt>
			   <dd style="display:none;"></dd>
		   </dl>
			<div class="modal-wrapper">
				<div class="modal">
					<h2 class="processing">Processing payment...</h2>
					<p class="processing">Please, don't leave or close this page.</p>
					<p class="error">Error message. Mensaje de error</p>
					<p class="thanks">Thank you for your payment. ;)</p>
					<a href="#" class="buttonModal">ok</a>
				</div>
			</div>
		</div>

	<?php require_once("inc/footer.php");?>
	<script type="text/javascript">
		$(document).ready(function(){

			$("dt .icon-left").click(function(){
				$("dd").slideToggle();
				$("dt .icon-left").toggleClass("fa-caret-down fa-caret-left")
			})

			$(".certificate i").click(function(){
				$(this).toggleClass("fa-square fa-check-square");
			})
		})

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
</html>



