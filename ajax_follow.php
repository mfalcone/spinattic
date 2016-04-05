<?
	require("inc/conex.inc");
	session_start();
	$id = $_GET["id"];
	$action = $_GET["a"];
	$myid = $_SESSION['usr'];

	if ($id !='' && $myid != ''){
		if($action == 'f'){
			$ssqlp = "INSERT INTO follows (id_follower, id_following, date) values (".$myid.", ".$id.", now())";
		}else{
			$ssqlp = "DELETE FROM follows where id_follower = ".$myid." and id_following = ".$id;
		}
		
		mysql_query($ssqlp);
		echo 'success';

	}else{
		echo 'error';
	}
?>