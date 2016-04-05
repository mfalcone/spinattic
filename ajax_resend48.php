<?

require("inc/conex.inc");
require_once("inc/functions.inc");

//Check for existing users
		
$ssqlp = "SELECT * FROM users where hashregistro = '".$_GET["h"]."'";
$result = mysql_query($ssqlp);	
		
if($row = mysql_fetch_array($result)){

	$hashregistro = md5($_GET["h"]);

	$ssqlp = "update users set sol_date = now(), hashregistro = '".$hashregistro."', confirm_sol = 1 where hashregistro = '".$_GET["h"]."'";

	mysql_query($ssqlp);

	//send mail

	$to = $row["email"];
	$toName = $row["username"];

	$subject = "Activate your account in Spinatttic.com";
	$body = 'Welcome to Spinattic!<br><br>Thank you for signing up. <br><br>Please activate your account by clicking on the following link.<br><br> Best regards, the Spinattic support team.<br><br><a href="http://'.$_SERVER['HTTP_HOST'].'/confirm_reg.php?h='.$hashregistro.'">http://'.$_SERVER['HTTP_HOST'].'/confirm_reg.php?h='.$hashregistro.'</a>';
	
	$result_mail = send_mail($to, $toName, $subject, '', '', 'Activate your account', '', $body, '', 1);
	
	if($result_mail == 'SUCCESS'){
		echo '<div class="message_box good_m in_pop"><p>We´ve sent you an email to complete your registration proccess</p><br><br><p>If you don´t see our email shortly, take a quick peak in your spam box just to be sure</p></div>';
	}	
	

}else{

	echo '<div class="message_box error_m in_pop"><p>An error has occurred. Please try again later</p></div>';

}
	
?>