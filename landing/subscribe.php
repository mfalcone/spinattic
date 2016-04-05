<?php

$email_to = "mauricio.hidalgo@dotcreek.com";
$subject = 'Subscription from website Sppinattic';
$name = $_POST["name"];
$email_from = $_POST["email"];
$headers = "From: " . $email_from . "\n";
$headers .= "Reply-To: " . $email_from . "\n";

$message = "Name: " . $name . "\r\nEmail: " . $email_from; 

ini_set("sendmail_from", $email_from);
$sent = mail($email_to, $subject, $message, $headers, "-f".$email_from);
if ($sent)
{
header("Location: index-thanks.php");
} else {
header("Location: index-error.php");
} 

?>