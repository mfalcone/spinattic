<?php

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

function get_follow_btn($uid, $logged){
	
	session_start();
	$myid = $_SESSION['usr'];
	if($logged == 1 && $uid != $myid){
		
		$follow_class = 'follow';
		$follow_text1 = 'Follow';
		$follow_text2 = 'Follow';
		$ssql_stats = "SELECT * FROM follows where id_follower = ".$myid." and id_following = ".$uid;
		$result_stats = mysql_query($ssql_stats);
		if($row_stats = mysql_fetch_array($result_stats)){
			$follow_class = 'following';
			$follow_text1 = 'Following';
			$follow_text2 = 'Unfollow';
		}
		echo '<button id="btn_follow" rel="'.$uid.'" class="btn_follow '.$follow_class.'"><span class="following-text">'.$follow_text1.'</span><span class="unfollow-text">'.$follow_text2.'</span></button>';
	}
}


function send_mail($to, $toName, $subject, $avatar, $notif_info, $text_title, $text_title_link, $text_paragraph1, $text_paragraph2, $add_footer){
	
		
	require_once(realpath($_SERVER["DOCUMENT_ROOT"])."/php-stubs/class.phpmailer.php");
	require_once(realpath($_SERVER["DOCUMENT_ROOT"])."/php-stubs/mail_template.php");
	
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
	
	
/*	
	//DREAMHOST
	$mail->IsSMTP();                      // set mailer to use SMTP
	$mail->Host = "mail.spinattic.com";  // specify main and backup server
	$mail->Port = "587";  // 
	$mail->SMTPAuth = true;     // turn on SMTP authentication
	$mail->Username = "hello@spinattic.com";  // SMTP username
	$mail->Password = "BeerBeer12"; // SMTP password
	$mail->CharSet = "utf-8";
*/

/*	
	//TEST
	$to = 'hbiancardi@hotmail.com';
	$toName = 'Hernan';
	$subject = 'TEST MAIL';

	$body = '<u>Cuerpo del mail</u>';
*/

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