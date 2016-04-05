<?
	require("inc/conex.inc");
	require("inc/auth.inc");
	require("inc/functions.inc");
	
	if($logged != 1){
		echo 'error';
	}else{
		session_start();
		$myid = $_SESSION['usr'];
		
		$ip = $_SERVER['REMOTE_ADDR'];
		$id = $_GET["id"];
	
		if ($id !=''){
			$ssqlp = "SELECT * FROM tours where id = ".$id;
			$result = mysql_query($ssqlp);
					
			$row = mysql_fetch_array($result);
	
			$likes = $row["likes"];
		
			//$ssqlp = "SELECT * FROM likes where idtour = ".$id." and ip like '%".$ip."%' and date > DATE_SUB(now(),INTERVAL 72 HOUR)";
			$ssqlp = "SELECT * FROM likes where idtour = ".$id." and iduser = ".$myid;
			
			$result = mysql_query($ssqlp);
				
			if(!($row = mysql_fetch_array($result))){
				mysql_query("update tours set likes = likes + 1 where id = ".$id);
				mysql_query("update tours_draft set likes = likes + 1 where id = ".$id);
				mysql_query("insert into likes values (".$myid.", ".$id.",' ".$ip."', now())");
				
				//$likes++;
				$likes = $likes+1;

				//Busco el valor maximo de likes
				$result_max = mysql_query("select max(amount) as max from likes_priority_steps where factor = 1");
				$row = mysql_fetch_array($result_max);
				$max = $row["max"];
				
				//Busco el valor del factor
				$result_factor = mysql_query("select amount from likes_priority_steps where factor = 0");
				$row = mysql_fetch_array($result_factor);
				$factor = $row["amount"];

				if ($likes > $max){
					//Si el valor de likes es $factor veces cambio el valor de priority en la tabla tours
					if (($likes-$max)/$factor == intval(($likes-$max)/$factor)){
						setMaxPriority($id);
					}
				}else{
					//Si el valor de likes es igual a alguno de los valores de likes_priority_steps cambio el valor de priority en la tabla tours
					$ssqlp_likes_steps = "select * from likes_priority_steps where amount = ".$likes." and factor = 1";
					$result_likes = mysql_query($ssqlp_likes_steps);
					
					if (mysql_num_rows($result_likes)){
						setMaxPriority($id);
					}
				}
				//echo $likes + 1;
				echo $likes;
			}else{
	
				echo $likes;
	
			}
		}
	}
?>