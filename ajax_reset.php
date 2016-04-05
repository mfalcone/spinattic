<?
require("inc/conex.inc");
require_once("inc/functions.inc");

//Check for existing users
		
$ssqlp = "SELECT * FROM users where email = '".$_GET["e"]."'";
$result = mysql_query($ssqlp);	
		
if($row = mysql_fetch_array($result)){

	$hashregistro = $row["hashregistro"];
	
	$ssqlp = "update users set reset_sol = 1 where email = '".$_GET["e"]."'";
	
	mysql_query($ssqlp);
	
	//send mail
	
	
	$to = $row["email"];
	$toName = $row["username"];
	
	$subject = "Reset your password in Spinatttic.com";
	$body = 'Please reset your password by clicking on the following link.<br><br> ATTENTION: If you did not request this change, discard this e-mail <br><br> Best regards, the Spinattic support team.<br><br><a href="http://'.$_SERVER['HTTP_HOST'].'/reset_pwd.php?h='.$hashregistro.'">http://'.$_SERVER['HTTP_HOST'].'/reset_pwd.php?h='.$hashregistro.'</a>';
	
	$result_mail =  send_mail($to, $toName, $subject, '', '', 'Forgot your password ?', '', $body, '', 1);
	
	if($result_mail == 'SUCCESS'){
		echo json_encode(array(
				'result' => "ok",
				'email' => $_GET["e"]));		
		//echo '<div class="message_box good_m in_pop"><p>We´ve sent you an email to '.$_GET["e"].' complete the password reset proccess</p></div>';		
	}
		
}else{
	echo json_encode(array(
			'result' => "nok"));
	//echo '<div class="message_box error_m in_pop"><p>Wrong E-Mail, please try again</p></div>';
	
}

?>