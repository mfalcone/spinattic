<?php

require("../inc/auth.inc");
require("../inc/conex.inc");
include("make_tour_files.php");

function rrmdir($dir) {
	if (is_dir($dir)) {
		$objects = scandir($dir);
		foreach ($objects as $object) {
			if ($object != "." && $object != "..") {
				if (filetype($dir."/".$object) == "dir")
					rrmdir($dir."/".$object);
				else unlink   ($dir."/".$object);
			}
		}
		reset($objects);
		rmdir($dir);
	}
}

if (isset($_GET['action']))
{
    switch($_GET['action'])
    {
        case 'modify':
            if (isset($_POST['scene-id']))
            {
               // cambiar nombre
				$ssqlp1 = "update panosxtour_draft set name = '".mysql_real_escape_string($_POST['scene-name'])."' where id = ".$_POST['scene-id'];
				mysql_query($ssqlp1);               
                echo 'name changed to: '.$_POST['scene-name'].' - id:'.$_POST['scene-id'];
            }
            break;
            
        case 'remove':
        	
        	//Para remover escenas:
        	
            if (isset($_POST['scene-id']))
            {
            	//tomo id del tour
            	$ssqlp1 = "SELECT idtour FROM panosxtour_draft WHERE id = ".$_POST['scene-id'];
            	$result = mysql_query($ssqlp1);
            	$row = mysql_fetch_array($result);
            	$id_tour = $row["idtour"];
	            
            	
            	
            	//Reasigno el thumb a los tours en draft que tengan esta pano
           		$ssqlthumb = "SELECT * FROM panosxtour_draft where idtour = ".$id_tour." and id <> ".$_POST['scene-id']." ORDER BY ord LIMIT 1";
           		$resultthumb = mysql_query($ssqlthumb);
           	
           		if(mysql_num_rows($resultthumb) > 0){ //Si queda alguna scene, asigno el thumb de esa (si no está customizado), sino, el general ("no thumb")
           	
           			$rowthumb = mysql_fetch_array($resultthumb);
           			mysql_query("update tours_draft set tour_thumb_path = '".$cdn.'/panos/'.$rowthumb["idpano"].'/pano.tiles/'."' where tour_thumb_custom = 0 and id = ".$id_tour);
           			
           		}else{
           	
           			mysql_query("update tours_draft set tour_thumb_path = 'http://".$_SERVER[HTTP_HOST]."/panos/' where tour_thumb_custom = 0 and id = ".$id_tour);

           		}
            	
            	
            	
            	//borrar escena
				$ssqlp1 = "delete from panosxtour_draft where id = ".$_POST['scene-id'];
				mysql_query($ssqlp1);
				
				//borrar hotspots de esta scene
				$ssqlp1 = "delete from hotspots_draft where scene_id = ".$_POST['scene-id'];
				mysql_query($ssqlp1);				
				
				//borrar hotspots arrow que apunten a esta scene
				$ssqlp1 = "delete from hotspots_draft where (type = 'link' or type = 'arrow') and extra_linkedscene = 'scene_".$_POST['scene-id']."'";
				//echo $ssqlp1;
				mysql_query($ssqlp1);				
				
				
				//si viene de manage_panoramas, borro en published
				if (isset($_POST['source']) && $_POST['source'] == 'manage_panoramas'){
					$ssqlp1 = "delete from panosxtour where id = ".$_POST['scene-id'];
					mysql_query($ssqlp1);
					
					//borrar hotspots de esta scene
					$ssqlp1 = "delete from hotspots where scene_id = ".$_POST['scene-id'];
					mysql_query($ssqlp1);
					
					//borrar hotspots arrow que apunten a esta scene
					$ssqlp1 = "delete from hotspots where (type = 'link' or type = 'arrow') and extra_linkedscene = 'scene_".$_POST['scene-id']."'";
					mysql_query($ssqlp1);					
					
					//verifico si queda sin escenas en published para borrarlo de tours (quedarìa en draft)
					$ssqlp1 = "SELECT * FROM panosxtour WHERE idtour = ".$id_tour;
					$result = mysql_query($ssqlp1);
					if(!($row = mysql_fetch_array($result))){
						$ssqlp1 = "delete from tours where id = ".$id_tour;
						mysql_query($ssqlp1);
						//borro local
						$tourfolder = '../tours/'.$id_tour.'/';
						if(is_dir($tourfolder)){
							$gestor = opendir($tourfolder);
							$count = 0;
							$retval = array();
							while ($elemento = readdir($gestor))
							{
								if($elemento != "." && $elemento != ".."){
									unlink ($tourfolder.$elemento);
								}
							}
						}						
					}
										
            	}
            	
          	
            	
                echo 'scene removed: '.$_POST['scene-id'];
            }
            
            
            //Para remover panos:
            
            if (isset($_POST['pano-id']) && $_POST['pano-id'] != '')
            {
            	
            	//Reasigno el thumb a los tours en draft que tengan esta pano
            	$ssql = "SELECT * FROM tours_draft where id in (select idtour from panosxtour_draft where idpano = ".$_POST['pano-id'].") ORDER BY id";
            	$result = mysql_query($ssql);
            	
           		while($row = mysql_fetch_array($result)){
           			$ssqlthumb = "SELECT * FROM panosxtour_draft where idtour = ".$row["id"]." and idpano <> ".$_POST['pano-id']." ORDER BY ord LIMIT 1";
           			$resultthumb = mysql_query($ssqlthumb);
           			
           			if(mysql_num_rows($resultthumb) > 0){ //Si queda alguna pano, asigno el thumb de esa (si no está customizado), sino, el general ("no thumb")
           			
	           			$rowthumb = mysql_fetch_array($resultthumb);
    	       			mysql_query("update tours_draft set tour_thumb_path = '".$cdn.'/panos/'.$rowthumb["idpano"].'/pano.tiles/'."' where tour_thumb_custom = 0 and id = ".$row["id"]);
    	       		
           			}else{
           				
           				mysql_query("update tours_draft set tour_thumb_path = 'http://".$_SERVER[HTTP_HOST]."/panos/' where tour_thumb_custom = 0 and id = ".$row["id"]);
    	       		}
           		
           		}
            	
            	
            	
            	$ssqlp1 = "delete from panosxtour_draft where idpano = ".$_POST['pano-id'];
            	mysql_query($ssqlp1);

            	$ssqlp1 = "delete from panosxtour where idpano = ".$_POST['pano-id'];
            	mysql_query($ssqlp1);
            	
            	$ssqlp1 = "delete from panos where id = ".$_POST['pano-id'];
            	mysql_query($ssqlp1);

            	//Elimino los archivos de la pano
            	$folderthumb = '../panos/'.$_POST['pano-id'].'/';
            	
            	//Borro cloud
            	$salida = shell_exec('s3cmd -c /var/www/'.$bucket_config_file.' del s3://'.$cdn_string.'/panos/'.$_POST['pano-id'].' --recursive 2>&1');
            	
            	//Borro local
            	rrmdir($folderthumb);
            	
            	
            	echo 'pano removed: '.$_POST['pano-id'];
            }
                        
            break;
        
        case 'getall':
            if (isset($_GET['user-id']) && isset($_GET['tour-id']) && isset($_GET['scene-id']))
            {
               // seleccionar todas las escenas del tour y usuario actual
                $ssqlp1 = "SELECT * FROM panosxtour_draft WHERE id <> ".$_GET['scene-id']." AND idtour = ".$_GET['tour-id']; // agregar luego filtro por user-id
                $result = mysql_query($ssqlp1);   
              
                $output = array();
                
                while($scene = mysql_fetch_array($result))
                { 
                    array_push($output, array(
                        'text'          => $scene['name'],
                        'value'         => $scene['id'],
                        'selected'      => ($_GET['selected-scene'] == $scene['id'])?true:false,
                        'description'   => "",
                        'imageSrc'      => $cdn."/panos/".$scene['idpano']."/pano.tiles/thumb100x50.jpg"
                    ));
                } 
                
                echo json_encode($output);
            }
            break;     
            
        default:
            break;
    }    
}

?>