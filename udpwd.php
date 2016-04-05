<?
	require("inc/auth.inc");
	//require("inc/restrict.inc");
	require("inc/conex.inc");
	
	$password = $_GET["ud_password"];	
	
	if($_GET["h"] == '' || $_GET["h"] == "undefined") {
		$hashregistro = $_SESSION["h"];
	}else{
		$hashregistro = $_GET["h"];	
	}
	
	if ($password != ''){
		$ssqlp1 = "update users set password = '".md5($password)."', reset_sol = 0 where hashregistro = '".$hashregistro."'";
		mysql_query($ssqlp1);
	}


	echo '<div class="message_box good_m"><p>Good! Password reset. Start using your new password from now on.</p></div>';
	

?>
