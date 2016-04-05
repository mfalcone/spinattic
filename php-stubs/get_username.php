<?
ini_set('default_charset', 'utf-8');
require("../inc/conex.inc");
	session_start();
	$id = $_GET["id"];

	if ($id !=''){
		$ssqlp = "SELECT username FROM users where id = ".$id;
		$result = mysql_query($ssqlp);
		if($row = mysql_fetch_array($result)){
			echo $row["username"];			
		}
	}
?>