<?php
require_once("../php/functions.php");
session_start();
//$name = $_SESSION["usr"];
$user = 110;
 $_SESSION['usr'] == 110;
error_reporting(E_ALL ^ E_WARNING); 
$ssqlp = "SELECT id FROM users where id = '".$user."'";
$result = mysql_query($ssqlp);
if($row = mysql_fetch_array($result)){
	//session not expired and user exists
	echo "0";
}else{
	//session expired or user doesn't exist
	$_SESSION = array();
	session_destroy();
	setcookie('hashregistro','',1);	
	echo "1";
}

	
/*
if($name == '')
{
	//session expired
	echo "1";
} else {
	//session not expired
    echo "0";
}
*/
?>