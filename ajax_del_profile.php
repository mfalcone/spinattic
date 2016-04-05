<?
require_once("inc/auth.inc");
require("inc/conex.inc");
require_once("inc/functions.inc");


//Check for existing users
		
$ssqlp = "SELECT * FROM users where id = '".$_SESSION["usr"]."'";

$result = mysql_query($ssqlp);
if($row = mysql_fetch_array($result)){
	$nick = $row["nickname"];
	$email = $row[email];

	$hashdelete = md5($nick.$email.rand());

	$ssqlp = "update users set hashdelete = '".$hashdelete."' where id = ".$_SESSION["usr"];

	mysql_query($ssqlp);
	
	//send mail
	
	$to = $row["email"];
	$toName = $row["username"];
	$subject = "Delete your account in Spinattic.com";
	$body = 'We'."'".'re sending you this email because you asked us to remove your Spinattic account. If it wasn'."'".'t you who initiated this action or you regret it, just ignore this email and your account will still remain active. Otherwise, to continue deleting your account, click in the following link:<br><br><a href="http://'.$_SERVER['HTTP_HOST'].'/confirm_del.php?h='.$hashdelete.'&f=0" style="color:#51ace5; text-decoration:none;">http://'.$_SERVER['HTTP_HOST'].'/confirm_del.php?h='.$hashdelete.'</a><br><br> Best regards, the Spinattic support team.';
	
	$result_mail = send_mail($to, $toName, $subject, '', '', 'Delete account Spinattic.com', '', $body, '', 1);

	if($result_mail == 'SUCCESS'){
		echo 'SUCCESS';
		//echo '<div class="message_box good_m"><p>We'."'".'ve sent you an email to complete your delete proccess</p><br><br><p>If you don'."'".'t see our email shortly, take a quick peak in your spam box just to be sure</p></div>';
	}
}
?>