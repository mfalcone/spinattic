<?php

	require("../inc/conex.inc");
	require("../inc/auth.inc");


//sleep(3);

if (isset($_GET['action']))
{
    switch($_GET['action'])
    {
        case 'remove':
            if (isset($_POST['id'])){
				mysql_query("delete from tours where id = '".$_POST['id']."'");
				mysql_query("delete from comments where idtour = '".$_POST['id']."'");
				mysql_query("delete from likes where idtour = '".$_POST['id']."'");
				
				mysql_query("delete from hotspots where scene_id in (select id from panosxtour where idtour = '".$_POST['id']."'");
				
				mysql_query("delete from panosxtour where idtour = '".$_POST['id']."'");

				//vacìo la carpeta y la elimino
				
				$folderthumb = '../tours/'.$_POST['id'].'/';
				$gestor = opendir($folderthumb);
				$count = 0;
				$retval = array();
				while ($elemento = readdir($gestor))
				{
					if($elemento != "." && $elemento != ".."){
						echo $elemento."<br>";
						unlink ($elemento);
					}
				}	
				rmdir($folderthumb);

				echo 'The tour was deleted';
			}
            break;
        
        case 'privacy':            
            if (isset($_POST['option']) && isset($_POST['id']))
            {                    
				mysql_query("update tours set privacy = '_".$_POST['option']."' where id = '".$_POST['id']."'");
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
								mysql_query("delete from tours where id = '".$id."'");
								mysql_query("delete from comments where idtour = '".$id."'");
								mysql_query("delete from likes where idtour = '".$id."'");
                            	mysql_query("delete from hotspots where scene_id in (select id from panosxtour where idtour = '".$_POST['id']."'");
				
								mysql_query("delete from panosxtour where idtour = '".$_POST['id']."'");
				
								//vacìo la carpeta y la elimino
								
								$folderthumb = '../tours/'.$_POST['id'].'/';
								$gestor = opendir($folderthumb);
								$count = 0;
								$retval = array();
								while ($elemento = readdir($gestor))
								{
									if($elemento != "." && $elemento != ".."){
										echo $elemento."<br>";
										unlink ($elemento);
									}
								}	
								rmdir($folderthumb);
							}
                            break;
                        /* privacy actions*/
                        case 'public':
                            echo 'Privacy configuration changed to PUBLIC';
                            foreach($_POST['ids'] as $id){
								mysql_query("update tours set privacy = '_".$_POST['option']."' where id = '".$id."'");
                            };
                            break;
                        case 'private':   
                            echo 'Privacy configuration changed to PRIVATE';
                            foreach($_POST['ids'] as $id){
								mysql_query("update tours set privacy = '_".$_POST['option']."' where id = '".$id."'");
                            };
                            break;
                        case 'notlisted':       
                            echo 'Privacy configuration changed to NOTLISTED';
                            foreach($_POST['ids'] as $id){
								mysql_query("update tours set privacy = '_".$_POST['option']."' where id = '".$id."'");
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
