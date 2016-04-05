<?
error_reporting(E_ALL &~ E_DEPRECATED);

$link=mysql_connect("localhost","root","root");

$_SESSION['usr'] = 41;
mysql_select_db("spin_bd");
$cdn_string = 'cdn.dev.spinattic.com';
$cdn = 'http://'.$cdn_string;
$bucket_config_file = '.s3cfg-spinattic-dev';
$environment = 'dev';

//session_start();


function abbr_number($size) {
	$size = preg_replace('/[^0-9]/','',$size);
	$sizes = array("", "K", "M");
	if ($size == 0) {
		return('0');
	} else {
		return (round($size/pow(1000, ($i = floor(log($size, 1000)))), 0) . $sizes[$i]);
	}
}

function get_pro(){

	//definir condicion para imprimir PRO
	//echo '<span class="pro">PRO</span>';

}

//Función que establece el campo priority al máximo
function setMaxPriority($idTour){
	$ssqlp = "select max(priority) as max from tours";
	$result = mysql_query($ssqlp);
	$row = mysql_fetch_array($result);
	$max_priority = $row["max"] + 1;
	mysql_query("update tours set priority = ".$max_priority." where id = ". $idTour);
	mysql_query("update tours_draft set priority = ".$max_priority." where id = ". $idTour);
}


function send_mail($to, $toName, $subject, $avatar, $notif_info, $text_title, $text_title_link, $text_paragraph1, $text_paragraph2, $add_footer){


	require_once("../../php-stubs/class.phpmailer.php");
	require_once("../../php-stubs/mail_template.php");

	$body =  get_mail_body($avatar, $notif_info, $text_title, $text_title_link, $text_paragraph1, $text_paragraph2, $add_footer);

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


	$mail->From = "hello@spinattic.com";
	$mail->FromName = "Spinattic.com";

	if($toName != 'spinattic_mail_group'){
		$mail->AddAddress($to, $toName);
	}else{
		$destinatarios= explode(",",$to);

		foreach($destinatarios as $destinatario => $value){
			$mail->AddBCC($value);
		}
	}


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
		return "ERROR";
	}

	return "SUCCESS";
}



?>