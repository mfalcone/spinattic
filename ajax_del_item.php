<?
	require("inc/conex.inc");
	session_start();
	$id = $_GET["id"];
	$action = $_GET["a"];
	$myid = $_SESSION['usr'];

	if ($id !='' && $myid != ''){
		$ssqlp = "DELETE FROM ".$action." where target_id = ".$myid." and id = ".$id;
		mysql_query($ssqlp);
		echo 'success';

	}
?>