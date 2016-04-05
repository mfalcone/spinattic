<?

	require("inc/conex.inc");
	session_start();
	$id = $_POST["id"];
	$myid = $_SESSION['usr'];

	if ($id !='' && $myid != ''){
		$ssqlp = "update notifications set checked = 1 where target_id = ".$myid." and id = ".$id;
		
		mysql_query($ssqlp);
		echo 'success';

	}
	
?>