<?
	require("../inc/conex.inc");
	session_start();
	$id = $_GET["id"];

	if ($id !=''){
		$ssqlp = "SELECT avatar FROM users where id = ".$id;
		$result = mysql_query($ssqlp);
		if($row = mysql_fetch_array($result)){
			echo 'http://'.$_SERVER[HTTP_HOST].'/images/users/'.$row["avatar"];			
		}
	}
?>