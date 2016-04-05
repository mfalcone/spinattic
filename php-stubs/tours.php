<?php

	require("../inc/conex.inc");
	require("../inc/auth.inc");


//sleep(3);

function rrmdir($dir) {
	//Borro local por las dudas
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

function del_tour($id_tour){
	
	global $cdn_string;
	global $cdn;
	global $bucket_config_file;	
	
	mysql_query("delete from tours where id = '".$id_tour."'");
	mysql_query("delete from tours_draft where id = '".$id_tour."'");
	mysql_query("delete from comments where idtour = '".$id_tour."'");
	mysql_query("delete from likes where idtour = '".$id_tour."'");
	mysql_query("delete from customizer where idtour = '".$id_tour."'");
	mysql_query("delete from customizer_draft where idtour = '".$id_tour."'");
	
	mysql_query("delete from hotspots where scene_id in (select id from panosxtour where idtour = '".$id_tour."')");
	mysql_query("delete from hotspots_draft where scene_id in (select id from panosxtour_draft where idtour = '".$id_tour."')");
	
	//echo "delete from hotspots where scene_id in (select id from panosxtour where idtour = '".$id_tour."')";
	
	mysql_query("delete from panosxtour where idtour = '".$id_tour."'");
	mysql_query("delete from panosxtour_draft where idtour = '".$id_tour."'");
	
	//vacìo la carpeta y la elimino
	rrmdir('../tours/'.$id_tour);
	
	//Borro cloud
	//$salida = shell_exec('s3cmd -c /var/www/'.$bucket_config_file.' del s3://'.$cdn_string.'/tours/'.$id_tour.' --recursive 2>&1');
	
	
	if($_GET["action"] != 'batch'){echo 'The tour was deleted';}
}	
	
if (isset($_GET['action']))
{
    switch($_GET['action'])
    {
        case 'remove':
            if (isset($_POST['id'])){
				del_tour($_POST['id']);
			}
            break;
        
        case 'privacy':            
            if (isset($_POST['option']) && isset($_POST['id']))
            {                    
				mysql_query("update tours set privacy = '_".$_POST['option']."' where id = '".$_POST['id']."'");
				mysql_query("update tours_draft set privacy = '_".$_POST['option']."' where id = '".$_POST['id']."'");
                switch ($_POST['option'])
                {   
                    case 'public':
                        echo 'Privacy configuration changed to PUBLIC';     
                        break;
                    case 'private':   
                        echo 'Privacy configuration changed to PRIVATE';                        
                        break;
                    case 'notlisted':       
                        echo 'Privacy configuration changed to NOT-LISTED';                        
                        break;
                }
            }    
            
            break;
        
        case 'batch': 
                if (isset($_POST['option']) && isset($_POST['ids']) && $_POST['ids'])
                {                    
                    switch ($_POST['option'])
                    {
                        case 'remove':
                            echo 'Elements deleted';
                            foreach($_POST['ids'] as $id){
                            	del_tour($id);
							}
                            break;
                        /* privacy actions*/
                        case 'public':
                            echo 'Privacy configuration changed to PUBLIC';
                            foreach($_POST['ids'] as $id){
								mysql_query("update tours set privacy = '_".$_POST['option']."' where id = '".$id."'");
								mysql_query("update tours_draft set privacy = '_".$_POST['option']."' where id = '".$id."'");
                            };
                            break;
                        case 'private':   
                            echo 'Privacy configuration changed to PRIVATE';
                            foreach($_POST['ids'] as $id){
								mysql_query("update tours set privacy = '_".$_POST['option']."' where id = '".$id."'");
								mysql_query("update tours_draft set privacy = '_".$_POST['option']."' where id = '".$id."'");
                            };
                            break;
                        case 'notlisted':       
                            echo 'Privacy configuration changed to NOTLISTED';
                            foreach($_POST['ids'] as $id){
								mysql_query("update tours set privacy = '_".$_POST['option']."' where id = '".$id."'");
								mysql_query("update tours_draft set privacy = '_".$_POST['option']."' where id = '".$id."'");
                            };
                            break;
                    }
                }    
                break;
        
        default:
            break;
    }
}


?>
