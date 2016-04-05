<?
ini_set("display_errors", 1);

require("inc/conex.inc");

	$ssqlp = "select * from users where id >= 173 and password <> 'd8578edf8458ce06fbc5bb76a58c5ca4'";
	$result = mysql_query($ssqlp);
	while($row = mysql_fetch_array($result)){

	
$body = '<!DOCTYPE html>
<html>
<meta charset="UTF-8">

<head>

	
</head>
<body>
<p style="text-align:center;font-family:Helvetica, Arial, sans-serif; font-size:12px; color:#707070;">
if you can\'t see this message correctly, open it in <a href="http://www.spinattic.com/images/newsletters/customizer-beta-testers.html" target="_blank">this link</a>
</p>
<div style="width:100%;margin:0; padding:0;background-color:#f2f2f2; padding: 50px 0;   font-family:Helvetica, Arial, sans-serif; font-size:16px; color:#707070;">

	<div style="max-width:900px;background-color:#222222; height:50px; margin:0 auto; text-align:center; padding:20px 0; ">
		<a href="http://www.spinattic.com" target="_blank"  > <img src="http://www.spinattic.com/images/spinattic.png" border="0" /> </a>
	</div>
	<div style="max-width:900px; margin:0 auto; " >
		<img src="http://www.spinattic.com/images/newsletters/customizer1.jpg" width="100%" />
		<div style="padding:50px;background-color:#ffffff;" >
			<h1 align="center"  >You\'re Invited!</h1>
			<p style="padding-top:20px; line-height:2em;" ><b>Spinattic\'s Customizer is a web platform that allows 360° photographers to create and customize 360° virtual tour interfaces.</b></p>
			
			<img src="http://www.spinattic.com/images/newsletters/betatesters.png" align="left" style="margin:0 20px 20px 0;" height="150" /> 
			<h2 align="center"  >Beta Testers Wanted!</h2>
			
			<p style="padding-top:20px; line-height:2em;" >To start testing our customizer, please go to <a href="http://dev.spinattic.com" target="_blank">dev.spinattic.com</a> and login with your Spinattic username and password and create a new tour.<br><br>
			<i>Please note that you\'re logging in to <b>Spinattic\'s Private Development environment</b> and any tours created during the initial beta test session in this enviornment will not be viewable to the public and may be deleted when the test session is complete. The purpose of being one of the first beta testers is to give you first access to the customizer platform and for our development team to get your valuable feedback and fix any bugs, that may be found.</i></p>
			<p style="padding-top:20px; line-height:2em;" >Feel free to share your opinions and any details with your peers, but please do not share your account credentials with anyone. Our goal is to migrate and integrate the customizer with our existing product at <a href="http://www.spinattic.com" target="_blank" >www.spinattic.com</a> on October 30th and make it available to all Spinattic members and the public.</p>
			
			<div style="background-color:#3c789c; height:50px; text-align:center; ">
				<a href="http://dev.spinattic.com" target="_blank" style="font-size:18px; color:#ffffff; text-decoration:none; height:50px; display: block; line-height:50px;">START NOW!</a>
			</div>
		</div>
		
		<div style="padding:30px; background-color:#ffffff; margin-top:20px; " >
		
		<img src="http://www.spinattic.com/images/newsletters/Hangouts_Icon.png" align="right" style="margin:0 20px 20px 0;" height="200" /> 
		<h2 align="center"  >Let\'s  Hangout!</h2>
		<p style="line-height:2em;">Spinattic\'s founders will be available on a Google Hangout on October 22nd at 9am (New York Time UTC/GCM -4 hours) to answer any questions and get feedback if interested. We\'ll send you a reminder with the Hangout link the previous day.<br>
		Otherwise, you can email us at hello@spinattic.com or go to our new forum at <a href="http://www.spinattic.com/blog/forum" target="_blank">www.spinattic.com/blog/forum</a>.</p>
		</div>

		<div style="padding:30px; background-color:#ffffff; margin-top:20px; " >
		<p style="line-height:2em;" >
		You have been added to our invite list because you have shown support for Spinattic in the past year. We thank you for your support and look forward to creating products that empowers creativity for the 360° photo industry.
		</p>
		<p style="line-height:2em; text-align:center;">Best,<br>
		Ariel, Rebecca and Derrick <br><br>
		<a href="http://www.spinattic.com" target="_blank"><img src="http://dev.spinattic.com/images/footer-logo.png" align="center"  /></a></p>
		</div>
	</div>

</div>

</body>
</html>';

	require_once(realpath($_SERVER["DOCUMENT_ROOT"])."/php-stubs/class.phpmailer.php");
	
	$mail = new PHPMailer();

	
	//AMAZON SES
	$mail->IsSMTP();                      // set mailer to use SMTP
	//$mail->SMTPDebug = 2;    //COMENTAR, ES PARA DEBBUG !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	$mail->Host = "email-smtp.us-west-2.amazonaws.com";  // specify main and backup server
	$mail->Port = "587";  //
	$mail->SMTPSecure = "tls";  //
	$mail->SMTPAuth = true;     // turn on SMTP authentication
	$mail->Username = "AKIAJXNF3S4AUWZAQCZA";  // SMTP username
	$mail->Password = "Aqw6lnNykJHtYeNyGzwcE9rslYqXPmZEdJ72O4PBI8jD"; // SMTP password
	$mail->CharSet = "utf-8";	
	
	
	//TEST
	//$to = 'hbiancardi@hotmail.com';
	//$to = 'ariel@spinattic.com';
	//$toName = 'Hernan';
	
	$to = $row["email"];
	$toName = $row["Username"];	
	
	$subject = 'Spinattic Customizer - You are invited!';

	$mail->From = "hello@spinattic.com";
	$mail->FromName = "Spinattic.com";
	
	$mail->AddAddress($to, $toName);
	$mail->AddBCC('hbiancardi@hotmail.com');
	                  
	//$mail->AddReplyTo("orders@mytreadz.com", "TREADZ Orders");

	//$mail->WordWrap = 50;                                 // set word wrap to 50 characters
	//$mail->AddAttachment("pdf/001-0035.pdf");         // add attachments
	//$mail->AddAttachment("/tmp/image.jpg", "new.jpg");    // optional name
	$mail->IsHTML(true);                                  // set email format to HTML

	
	$mail->Subject = $subject;
	//$mail->Subject = htmlentities($subject);
	//$mail->Subject = mb_encode_mimeheader(utf8_decode($subject),"UTF-8");
	$mail->Body    = $body;
	//$mail->AltBody = "This is the body in plain text for non-HTML mail clients";

	if(!$mail->Send())
	{
		echo "ERROR";
	}

	echo "SUCCESS";
}
?>