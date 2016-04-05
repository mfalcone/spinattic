<?
	require("inc/conex.inc");
	session_start();
	$id = $_POST["id"];
	$admin = $_SESSION['admin'];

	if ($id !='' && $admin == 1){
		//elimino imagen si existe
		$ssqlp = "SELECT * FROM blog where id = ".$id;
		$result = mysql_query($ssqlp);
		$row = mysql_fetch_array($result);
		if($row["image"] != ''){
			if(is_file("images/blog/".$row["image"])){
				unlink("images/blog/".$row["image"]);
			}
		}
		
		$ssqlp = "DELETE FROM blog where id = ".$id;
		mysql_query($ssqlp);
		echo 'success';
	}else{
		echo 'error';
	}
		
?>