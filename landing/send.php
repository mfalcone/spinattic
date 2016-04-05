<?php

$email_to = "mauricio.hidalgo@dotcreek.com";
$subject = $_POST["subject"];
$email_from = $_POST["email"];
$message = $_POST["message"];
$headers = "From: " . $email_from . "\n";
$headers .= "Reply-To: " . $email_from . "\n";

$message = "Subject: ". $subject . "\r\nE-mail: " . $email_from . "\r\nMessage: " . $message; 

ini_set("sendmail_from", $email_from);
$sent = mail($email_to, $subject, $message, $headers, "-f".$email_from);
if ($sent)
{
header("Location: thanks.php");
} else {
header("Location: error.php");
} 

?>