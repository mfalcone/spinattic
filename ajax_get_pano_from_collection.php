<?
	require("inc/conex.inc");
	session_start();
	$idtour = $_GET["idtour"];
	$idpano = $_GET["panoid"];
	$myid = $_SESSION['usr'];

	if ($idpano !='' && $idtour !='' && $myid != ''){

		//busco la pano y veo si es mia
		$ssqlp = "SELECT name FROM panos where id=".$idpano." and user = ".$myid;
		$result = mysql_query($ssqlp) or die(mysql_error());		
		if($row = mysql_fetch_array($result)){
			
			$file_name = $row["name"];
			$scene_name = str_replace(end(explode('.', $file_name)), '', $file_name);
			$scene_name = substr($scene_name, 0,strlen($scene_name)-1);

			
			$ssqlp1 = "insert into panosxtour_draft (idpano, state, idtour, name) values (".$idpano.", 1, ".$idtour.", '".  mysql_escape_string($scene_name)."')";
			mysql_query($ssqlp1);
			$ssqlp = "SELECT max(id) as elid FROM panosxtour_draft";
			$result = mysql_query($ssqlp) or die(mysql_error());
			$row = mysql_fetch_array($result);
			$scene_id = $row["elid"];			
			
			/*
			$ssqlp = "SELECT max(id) as elid FROM panosxtour";
			$result = mysql_query($ssqlp) or die(mysql_error());	
	    
			if($row = mysql_fetch_array($result))
			{
			    $scene_id = $row["elid"] + 1;
			}
			else
			{
			    $scene_id = 1;
			}
	    	
	        $ssqlp1 = "insert into panosxtour (idpano, state, idtour, name, id) values (".$idpano.", 1, ".$idtour.", '".  $scene_name."', ".$scene_id.")";
	        mysql_query($ssqlp1);
			*/
			
			
			echo json_encode(array(
				
				'scene_id' => $scene_id,
				'scene_name' => $scene_name,
				'file_name' => $file_name,
				'result' => 'SUCCESS'
					
			));
		}else{
			echo json_encode(array(
			
					'scene_id' => '',
					'scene_name' => '',
					'file_name' => '',
					'result' => 'ERROR'
			
			));
		}
	}
?>