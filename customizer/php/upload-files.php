<?php
/*
$restrict = 1;
require("../inc/auth.inc");
require("../inc/conex.inc");
*/

session_start();

require("functions.php");

//Si es un nuevo tour, el id lo tengo guardado en la SESSION, antes chequeo que no me venga por REQUEST, y luego veo si la tengo en SESSION
if(isset($_REQUEST['tour_id']) && $_REQUEST['tour_id'] != '' && $_REQUEST['tour_id'] != 'undefined'){
	$tour_id = $_REQUEST['tour_id'];	
}else{
	$tour_id = $_SESSION['tour_id'];
}


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
$allowed_ext = array('tif', 'tiff','jpg', 'jpeg', 'png');


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

    
    // Inserto la pano con state = 0 para reservar el ID 
    
    $ssqlp1 = "insert into panos (state, user, date, name) values (0, '".$_SESSION['usr']."', now(), '".mysql_real_escape_string($pic['name'])."')";
    mysql_query($ssqlp1);
    $ssqlp = "SELECT max(id) as elid FROM panos";
    $result = mysql_query($ssqlp);
	$row = mysql_fetch_array($result);
   	$elid = $row["elid"];

    
   	/* Inserto la scene con state = 0 para reservar el ID */
   	
   	$scene_name = str_replace('.'.$ext, '', $pic['name']);
   	
   	//Tomo el nro de orden 
   	$ssqlp = "SELECT max(ord) as ord FROM panosxtour_draft where idtour = ".$tour_id;
   	$result = mysql_query($ssqlp) or die(mysql_error());
   	$row = mysql_fetch_array($result);
   	$el_ord = $row["ord"] + 1;   	
   	
   	
   	$ssqlp1 = "insert into panosxtour_draft (idpano, ord, state, idtour, name) values (".$elid.", ".$el_ord.", 0, ".$tour_id.", '".  mysql_real_escape_string($scene_name)."')";
   	//echo $ssqlp1;
   	mysql_query($ssqlp1);   	
   	$ssqlp = "SELECT max(id) as elid FROM panosxtour_draft";
   	$result = mysql_query($ssqlp) or die(mysql_error());
	$row = mysql_fetch_array($result);
	$scene_id = $row["elid"];
   	
    
    @mkdir ($upload_dir.'/'.$elid, 0777);
    chmod($upload_dir.'/'.$elid, 0777);

    if(move_uploaded_file($pic['tmp_name'], $upload_dir.'/'.$elid.'/pano.'.$ext) && isset($tour_id))    
    {
        chmod($upload_dir.'/'.$elid.'/pano.'.$ext, 0777);
        
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

/*
mysql_query("delete from panos where id = ".$elid);
mysql_query("delete from panosxtour where id = ".$scene_id);

exit_status(array(
                'result' => 'ERROR', 
                'msg' => 'Something went wrong with your upload!'
));
*/

?>
