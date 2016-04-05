<?
$restrict = 1;
require("inc/header.php");

if(!empty($_POST["ud_username"])){

	$username = $_POST["ud_username"];
	$email = $_POST["ud_email"];
	$fnac = $_POST["ud_fnac"];
	$country = $_POST["ud_country"];
	$state = $_POST["ud_state"];
	$city = $_POST["ud_city"];
	$website = str_replace('http://', '', $_POST["ud_website"]);
	$twitter = str_replace('@', '', $_POST["ud_twitter"]);
	$facebook = end(explode('/',$_POST["ud_facebook"]));
	$password = $_POST["ud_password"];
	$lo = $_POST["lo"];	
	
	if($lo == 1){ //Si cambió el email, chequeo que el mismo no exista, le mandamos mail y lo deslogueamos

		//Check for existing users
		
		$ssqlp = "SELECT * FROM users where email = '".$email."'";
		$result = mysql_query($ssqlp);
		
		if($row = mysql_fetch_array($result)){
		
			$resutado_cambio = 'exists';
		
		}else{
		
			$hashregistro = md5(strtolower($username).$email.$password);
		
			$ssqlp1 = "update users set username = '".$username."', email = '".$email."', fnac = '".$fnac."', country = '".$country."', state = '".$state."', city = '".$city."', website = '".$website."', twitter = '".$twitter."', facebook = '".$facebook."', hashregistro = '".$hashregistro."', sol_date = now(), status = 0 where id = '".$_SESSION["usr"]."'";	
			mysql_query($ssqlp1);
		
			//send mail
		
			$to = $email;
			$subject = "Re-Activate your account in Spinatttic.com";
		
			$message = 'Please Re-Activate your account by clicking on the following link.<br><br> Best regards, the Spinattic support team.<br><br><a href="http://'.$_SERVER['HTTP_HOST'].'/confirm_reg.php?h='.$hashregistro.'">http://'.$_SERVER['HTTP_HOST'].'/confirm_reg.php?h='.$hashregistro.'</a>';
		
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		
			// Additional headers
			$headers .= "From: Spinattic.com <info@spinattic.com>\r\n";
		
			// Mail it
			mail($to, $subject, $message, $headers);
			
			header('Location: lo.php');
			
		}
				
	}else{
	
		//Update User data
		$ssqlp1 = "update users set username = '".$username."', email = '".$email."', fnac = '".$fnac."', country = '".$country."', state = '".$state."', city = '".$city."', website = '".$website."', twitter = '".$twitter."', facebook = '".$facebook."' where id = '".$_SESSION["usr"]."'";	
		mysql_query($ssqlp1);
	
		//Update name in tours if changed
		
		if($_SESSION["username"] != $username){
		
			$ssqlp1 = "update tours set user = '".$username."' where iduser = '".$_SESSION["usr"]."'";	
			mysql_query($ssqlp1);
	
		}
	
		//update sessions	
			$_SESSION['username'] = $username;
			$_SESSION['email'] = $email;
	
	}
}



	$ssql_stats = "SELECT * FROM tours where iduser = ".$_SESSION["usr"];
	$result_stats = mysql_query($ssql_stats);	
	$cant_tours = mysql_num_rows($result_stats);

	$ssql_stats = "SELECT * FROM panos where user = ".$_SESSION["usr"];
	$result_stats = mysql_query($ssql_stats);	
	$cant_panos = mysql_num_rows($result_stats);

	$ssql_stats = "SELECT * FROM users where id = ".$_SESSION["usr"];
	$result_stats = mysql_query($ssql_stats);	
	$row_user = mysql_fetch_array($result_stats);
	$username = $row_user["username"];
	$email = $row_user["email"];
	$fnac = $row_user["fnac"];
	$wb = $row_user["website"];
	$fb = $row_user["facebook"];
	$tw = $row_user["twitter"];
	$city = $row_user["city"];
	$state = $row_user["state"];
	$country = $row_user["country"];
	$fb_name = $row_user["fb_name"];
	$gp_name = $row_user["gp_name"];
	$loc = $city.', '.$state.' - '.$country;
	if($loc==',  - '){$loc = '';}

?>

		<link rel="stylesheet" type="text/css" media="screen" href="css/tabs.css" />
        <script src="js/core.user.inc.js"></script>
          

		<div>
		    <h1 class="user-profile">User Profile</h1>
		</div>


	            <div id="elcover" class="front-profile user-front-profile" style="background: url(images/users/cover/<?php echo $_SESSION["cover"];?>) repeat-x left;">
	            <div class="content">
		            <div class="user">
	                    <a id="upload-avatar-btn" class="black-buttom border-radius-4">Change avatar</a>
                        <img id="elavatar" width="107" height="107" src="images/users/<?echo $_SESSION["avatar"];?>">
                    </div>
                    <div class="content-info">
	                    <h3><?echo $_SESSION["username"];?></h3>
	                    <?php get_pro();?>
	                    <h4><?echo $cant_tours?> Tours / <?echo $cant_panos?> Panos</h4>
	                    <?if($wb != ''){?><a href="http://<?echo $wb?>" target="_blank" class="url"><?echo $wb?></a><?}?>
	                    <?if($fb != ''){?><a href="http://www.facebook.com/<?echo $fb?>" target="_blank" class="facebook"><?echo $fb?></a><?}?>
	                    <?if($tw != ''){?><a href="http://www.twitter.com/<?echo $tw?>" target="_blank" class="twitter"><?echo $tw?></a><?}?>
	                    <?if($loc != ''){?><font class="marker"><?echo $loc?></font><?}?>
	                    
					</div>                    
                    <a id="upload-cover-btn" class="black-buttom border-radius-4">Change cover</a>
                </div>
            </div>	
                

        <div class="wrapper wrapper-user">
			
			<span id="mensajes">
				<?php
				if($resutado_cambio == 'exists'){
					echo '<div class="message_box error_m"><p>Sorry, the email address that you provided is already being used in other account</p></div>';
				}else{
					if(!empty($_POST["ud_username"])){
						echo '<div class="message_box good_m"><p>Your info has been updated</p></div>';
					}
				} 
					
				?>
			</span>
			
			<!--tabs-->	
			<div id="tabs">
				<ul>
					<li><a href="#tabs-3" class="tab-button">Edit Personal Details</a></li>
					<li><a href="#tabs-4" class="tab-button">Change Password</a></li>
				</ul>
				<div id="tabs-3" class="tabs-content">
				<form action="" method="post" id="update_form">
				<input type="hidden" name="fb_name" id="fb_name" value="<?echo $fb_name;?>">
				<input type="hidden" name="gp_name" id="gp_name" value="<?echo $gp_name;?>">
				<input type="hidden" name="orig_email" id="orig_email" value="<?echo $email;?>">
				<input type="hidden" name="lo" id="lo" value="">
		                <label>
		                    <p>Full Name</p>
		                    <input class="border-radius-4 form-user" value="<?echo $username;?>" name="ud_username" id="ud_username">
                                    <div class="validation">
                                        <div class="msg1">Minimum characters</div>
                                        <div class="msg2">Availability</div>
                                    </div>   
                                    <br clear="all"/>
		                </label>
		                <label>
		                    <p>E-mail <font>(Use it for login. Won't be published)</font></p>
		                    <input class="border-radius-4 form-user" value="<?echo $email;?>" name="ud_email" id="ud_email">
                                    <div class="validation">
                                        <div class="msg1">Invalid account</div>
                                        <div class="msg2">Availability</div>
                                    </div>
                                    <br clear="all"/>
		                </label>
		                <label>
		                    <p>Birth Date</p>
		                    <input class="border-radius-4 form-user" value="<?echo $fnac?>" placeholder="mm/dd/yyyy" name="ud_fnac" id="ud_fnac">
                                    <br clear="all"/>
		                </label>			                
		                <label class=" float">
		                    <p>Country</p>
		                    <select class="border-radius-4 form-user" name="ud_country" id="ud_country">
								<option <?if($country == ''){echo 'selected';}?> value="">Select Country</option>
								<option <?if($country == 'Afganistan'){echo 'selected';}?> value="Afganistan">Afghanistan</option>
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
		                </label>
		                <label class=" float">
		                    <p>State</p>
		                    <input class="border-radius-4 form-user" value="<?echo $state;?>" name="ud_state" id="ud_state">
		                </label>
		                <label class="float">
		                    <p>City</p>
							<input class="border-radius-4 form-user" value="<?echo $city;?>" name="ud_city" id="ud_city">
		                </label>                
		                <br clear="all">

		                
		                <label>
		                    <p>Website</p>
		                    <input class="border-radius-4 form-user" value="http://<?echo $wb?>" name="ud_website" id="ud_website">
                                    <br clear="all"/>
		                </label>
		                <label>
		                    <p>Twitter</p>
		                    <input class="border-radius-4 form-user" value="@<?echo $tw?>" name="ud_twitter" id="ud_twitter">
                                    <br clear="all"/>
		                </label>
		                <label>
		                    <p>Facebook</p>
		                    <input class="border-radius-4 form-user" value="/<?echo $fb?>" name="ud_facebook" id="ud_facebook">
                                    <br clear="all"/>
		                </label>
	                 
	                 <hr class="separator" />
                    <a href="#" class="uduserdata green-button border-radius-4">UPDATE PROFILE</a>
                    <br><br clear="all">
				
				</form>
				
				</div>
	            <div id="tabs-4" class="tabs-content">
		                <label>
		                    <p>New Password</p>
		                    <input class="border-radius-4 form-user" value=""  type="password" name="ud_password" id="ud_password">
		                    <!--
                                    <div class="validation">
                                        <div class="msg1">Minimum characters</div>                                        
                                    </div>
                            -->
                                    <br clear="all"/>
		                </label>
		                <label>
		                    <p>Repeat New Password</p>
		                    <input class="border-radius-4 form-user" value="" type="password" name="ud_repeat_password" id="ud_repeat_password">
                                    <div class="validation">
                                        <div class="msg1">Same password</div>                                        
                                    </div>
                                    <br clear="all"/>
		                </label>
                        
                        <hr class="separator" />
						<a href="#" class="uduser green-button border-radius-4">CHANGE PASSWORD</a>
						<br><br clear="all">
				</div>
			</div>	

        </div>

	</body>
</html>



