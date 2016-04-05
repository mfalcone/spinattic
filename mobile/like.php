<?
	require("../inc/conex.inc");
	
	$ip = $_SERVER['REMOTE_ADDR'];
	$id = $_GET["id"];

	if ($id !=''){
		$ssqlp = "SELECT * FROM tours where id = ".$id;
		$result = mysql_query($ssqlp);
				
		$row = mysql_fetch_array($result);

		$likes = $row["likes"];
	
		$ssqlp = "SELECT * FROM likes where idtour = ".$id." and ip like '%".$ip."%' and date > DATE_SUB(now(),INTERVAL 72 HOUR)";
	
		$result = mysql_query($ssqlp);
			
		if(!($row = mysql_fetch_array($result))){
			mysql_query("update tours set likes = likes + 1 where id = ".$id);
			mysql_query("insert into likes values (".$id.",' ".$ip."', now())");
				
			echo $likes + 1;
		}else{

			echo $likes;

		}
	}
?>