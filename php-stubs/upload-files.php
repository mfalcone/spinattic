<?php

require("../inc/auth.inc");
require("../inc/restrict.inc");
require("../inc/conex.inc");

// Helper functions

function exit_status($return)
{
    echo json_encode($return);
    exit;
}

function get_extension($file_name)
{
    $ext = explode('.', $file_name);
    $ext = array_pop($ext);
    return strtolower($ext);
}

// If you want to ignore the uploaded files, 
// set $demo_mode to true;

$demo_mode = false;
$upload_dir = '../panos/';
$allowed_ext = array('tif', 'tiff','jpg', 'jpeg');


if(strtolower($_SERVER['REQUEST_METHOD']) != 'post')
{
    exit_status(array(
                    'result' => 'ERROR', 
                    'msg' => 'Error! Wrong HTTP method!'
    ));         
}


if(array_key_exists('pic',$_FILES) && $_FILES['pic']['error'] == 0 )
{
    $pic = $_FILES['pic'];
    $ext = get_extension($pic['name']);

    if(!in_array($ext,$allowed_ext))
    {
        exit_status(array(
                'result' => 'ERROR', 
                'msg' => 'Only '.implode(',',$allowed_ext).' files are allowed!'
        ));   
    }	
    
    $image_size = getimagesize($pic['tmp_name']);

    if($image_size[0] !== $image_size[1]*2) 
    {
       exit_status(array(
                'result' => 'ERROR', 
                'msg' => 'Can\'t upload this image. Aspect ratio required: 2x1.'
        ));  
    }
    
    if(!in_array($ext,$allowed_ext))
    {
        exit_status(array(
                'result' => 'ERROR', 
                'msg' => 'Only '.implode(',',$allowed_ext).' files are allowed!'
        ));   
    }	


    if($demo_mode)
    {
        // File uploads are ignored. We only log them.

        $line = implode('		', array( date('r'), $_SERVER['REMOTE_ADDR'], $pic['size'], $pic['name']));
        file_put_contents('log.txt', $line.PHP_EOL, FILE_APPEND);

        exit_status(array(
                'result' => 'ERROR', 
                'msg' => 'Uploads are ignored in demo mode.'
        ));                        
    }


    // Move the uploaded file from the temporary 
    // directory to the uploads folder:

	
	
    
    /* Inserto la pano con state = 0 para reservar el ID */
    
	/*PASO A AUTOINCREMENTAL
	$ssqlp = "SELECT max(id) as elid FROM panos";
    $result = mysql_query($ssqlp);	
    
    if($row = mysql_fetch_array($result))
    {
        $elid = $row["elid"] + 1;
    }
    else
    {
        $elid = 1;
    }
	*/
    
    
    $ssqlp1 = "insert into panos (state, user, date, name) values (0, '".$_SESSION['usr']."', now(), '".$pic['name']."')";
    mysql_query($ssqlp1);
    $ssqlp = "SELECT max(id) as elid FROM panos";
    $result = mysql_query($ssqlp);
	$row = mysql_fetch_array($result);
   	$elid = $row["elid"];
   	
   	/* Inserto la scene con state = 0 para reservar el ID */
   	
   	/*PASO A AUTOINCREMENTAL
   	
   	$ssqlp = "SELECT max(id) as elid FROM panosxtour";
   	$result = mysql_query($ssqlp) or die(mysql_error());
   	
   	if($row = mysql_fetch_array($result))
   	{
   		$scene_id = $row["elid"] + 1;
   	}
   	else
   	{
   		$scene_id = 1;
   	}*/

   	$tour_id = $_REQUEST['tour_id'];
   	
   	$scene_name = str_replace('.'.$ext, '', $pic['name']);
   	$ssqlp1 = "insert into panosxtour_draft (idpano, state, idtour, name) values (".$elid.", 0, ".$tour_id.", '".  $scene_name."')";
   	mysql_query($ssqlp1);   	
   	$ssqlp = "SELECT max(id) as elid FROM panosxtour_draft";
   	$result = mysql_query($ssqlp) or die(mysql_error());
	$row = mysql_fetch_array($result);
	$scene_id = $row["elid"];
   	
    
    @mkdir ($upload_dir.'/'.$elid, 0777);
    chmod($upload_dir.'/'.$elid, 0777);

    if(move_uploaded_file($pic['tmp_name'], $upload_dir.'/'.$elid.'/index.'.$ext) && isset($_REQUEST['tour_id']))    
    {
        chmod($upload_dir.'/'.$elid.'/index.'.$ext, 0777);
        
        exit_status(array(
                'result' => 'SUCCESS', 
                'msg' => 'File was uploaded successfuly!', 
                'params' => array(
                                'tour_id' => $tour_id,
                                'pano_id' => $elid,
                                'scene_id' => $scene_id,
                                'file_name' => $pic['name']
                    
        )));
    }
	
}

/*si hubo un error borro el registro de la pano y la scene*/
mysql_query("delete from panos where id = ".$elid);
mysql_query("delete from panosxtour where id = ".$scene_id);

exit_status(array(
                'result' => 'ERROR', 
                'msg' => 'Something went wrong with your upload!'
));


?>
