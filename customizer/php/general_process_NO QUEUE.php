<?php
$restrict = 1;

ini_set("display_errors", 0);

ini_set('memory_limit', '-1');
//require("../inc/auth.inc");
//require("../inc/conex.inc");
//include('../php-stubs/simpleimage.php');

include_once('simpleimage.php');
require_once("functions.php");


session_write_close();

$proc_id = $_GET["proc_id"];
$cancel = $_GET["c"];
$tour_id = $_REQUEST['tour_id'];


$upload_dir = '../../panos/';

//Si no hay registros en la tabla de log, inserto el inicio
$ssql_check = "SELECT * FROM general_process_log where proc_id = '".$proc_id."' order by id desc";
$result_check = mysql_query($ssql_check);
if(!($row_check = mysql_fetch_array($result_check))){
	if(array_key_exists('pic',$_FILES) && $_FILES['pic']['error'] == 0 )
	{
		$pic = $_FILES['pic'];
	}

	mysql_query("insert into general_process_log (proc_id, user_id, tour_id, filename, step, step_desc, date, agent) values ('".$proc_id."', '".$_SESSION['usr']."', '".$tour_id."', '".$pic['name']."', 0, 'Start Upload', now(), '".$_SERVER['HTTP_USER_AGENT']."')");

}else{

	$pano_id = $row_check["pano_id"];
	$scene_id = $row_check["scene_id"];
	$filename = $row_check["filename"];
	$tour_id = $row_check["tour_id"];
}



if(isset($_GET["c"]) && $_GET["c"] == 1){

	//CANCEL PROCESS

	mysql_query("insert into general_process_log (proc_id, user_id, tour_id, pano_id, scene_id, filename, step, step_desc, date, agent) values ('".$proc_id."', '".$_SESSION['usr']."', '".$tour_id."', '".$pano_id."', '".$scene_id."', '".$filename."', -2, 'Cancel Requested', now(), '".$_SERVER['HTTP_USER_AGENT']."')");
	mysql_query("update general_process_log set cancel_requested = 1 where proc_id = '".$proc_id."'");


}else{

	// If you want to ignore the uploaded files, 
	// set $demo_mode to true;
	
	check_for_cancel($proc_id);
	
	$demo_mode = false;
	
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
	    $step = 0;

	    
	
	    if(!in_array($ext,$allowed_ext))
	    {
	    	
	    	mysql_query("insert into general_process_log (proc_id, user_id, tour_id, filename, step, step_desc, date, agent) values ('".$proc_id."', '".$_SESSION['usr']."', '".$_REQUEST['tour_id']."', '".$pic['name']."', -1, 'ERROR: File Ext not allowed', now(), '".$_SERVER['HTTP_USER_AGENT']."')");
	    	
	        exit_status(array(
	                'result' => 'ERROR', 
	                'msg' => 'Only '.implode(',',$allowed_ext).' files are allowed!'
	        ));   
	    }	
	    
	    $image_size = getimagesize($pic['tmp_name']);
	
	    if($image_size[0] !== $image_size[1]*2) 
	    {
	    	
		 mysql_query("insert into general_process_log (proc_id, user_id, tour_id, filename, step, step_desc, date, agent) values ('".$proc_id."', '".$_SESSION['usr']."', '".$_REQUEST['tour_id']."', '".$pic['name']."', -1, 'ERROR: Image Ratio incorrect (2x1)', now(), '".$_SERVER['HTTP_USER_AGENT']."')");
	   	
	      exit_status(array(
	                'result' => 'ERROR', 
	                'msg' => 'Can\'t upload this image. Aspect ratio required: 2x1.'
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
	    
	    
	    $ssqlp1 = "insert into panos (state, user, date, name) values (0, '".$_SESSION['usr']."', now(), '".mysql_real_escape_string($pic['name'])."')";
	    mysql_query($ssqlp1);
	    $ssqlp = "SELECT max(id) as elid FROM panos where user = '".$_SESSION['usr']."'";
	    $result = mysql_query($ssqlp);
		$row = mysql_fetch_array($result);
	   	$elid = $row["elid"];
	   	
	   	
	   	//Tomo el nro de orden
	   	$ssqlp_ord = "SELECT max(ord) as ord FROM panosxtour_draft where idtour = ".$tour_id;
	   	$result_ord = mysql_query($ssqlp_ord) or die(mysql_error());
	   	$row_ord = mysql_fetch_array($result_ord);
	   	$el_ord = $row_ord["ord"] + 1;   	
	   	
	   	
	   	$scene_name = str_replace('.'.$ext, '', $pic['name']);
	   	$ssqlp1 = "insert into panosxtour_draft (idpano, ord, state, idtour, name) values (".$elid.", ".$el_ord.", 0, ".$tour_id.", '".  mysql_real_escape_string($scene_name)."')";
	   	mysql_query($ssqlp1);   	
	   	$ssqlp = "SELECT max(id) as elid FROM panosxtour_draft where idpano = '".$elid."'";
	   	$result = mysql_query($ssqlp) or die(mysql_error());
		$row = mysql_fetch_array($result);
		$scene_id = $row["elid"];
	   	
	    
	    @mkdir ($upload_dir.$elid, 0777);
	    chmod($upload_dir.$elid, 0777);
	
	    if(move_uploaded_file($pic['tmp_name'], $upload_dir.$elid.'/pano.'.$ext) && isset($_REQUEST['tour_id']))    
	    {
	        chmod($upload_dir.$elid.'/pano.'.$ext, 0777);
	
	        check_for_cancel($proc_id);
	        
	        mysql_query("insert into general_process_log (proc_id, user_id, tour_id, scene_id, pano_id, filename, step, step_desc, date, agent) values ('".$proc_id."', '".$_SESSION['usr']."', '".$_REQUEST['tour_id']."', '".$scene_id."', '".$elid."', '".$pic['name']."', ".$step.", 'End Upload', now(), '".$_SERVER['HTTP_USER_AGENT']."')");
	        
	        image_processing($tour_id, $elid, $scene_id, $pic['name'], 1);

	    }
		
	}
	
	/*si hubo un error borro el registro de la pano y la scene*/
	mysql_query("delete from panos where id = ".$elid);
	mysql_query("delete from panosxtour where id = ".$scene_id);
	mysql_query("delete from panosxtour_draft where id = ".$scene_id);
	
	$salida = shell_exec('rm -r ' . $upload_dir . $elid);
	
	mysql_query("insert into general_process_log (proc_id, user_id, tour_id, scene_id, pano_id, filename, step, step_desc, date, agent) values ('".$proc_id."', '".$_SESSION['usr']."', '".$_REQUEST['tour_id']."', '".$scene_id."', '".$elid."', '".$pic['name']."', -1, 'ERROR: Something went wrong with your upload', now(), '".$_SERVER['HTTP_USER_AGENT']."')");
	
	exit_status(array(
			'result' => 'ERROR',
			'msg' => 'Something went wrong with your upload!'
	));

}

//IMAGE PROCESSING:

function image_processing($tour_id, $pano_id, $scene_id, $file_name, $step){
	global $upload_dir;
	global $bucket_config_file;
	global $environment;
	global $cdn;
	global $cdn_string;
	global $proc_id;
	
	check_for_cancel($proc_id);
	
	mysql_query("insert into general_process_log (proc_id, user_id, tour_id, scene_id, pano_id, filename, step, step_desc, date, agent) values ('".$proc_id."', '".$_SESSION['usr']."', '".$tour_id."', '".$scene_id."', '".$pano_id."', '".$file_name."', ".$step.", 'Start Image Processing - Step ".$step."', now(), '".$_SERVER['HTTP_USER_AGENT']."')");
	
	if($step == 1){
			
		/* Convertir imagen y Crear Thumbs
			Using KRPANO maketiles
		    ANTES USABAMOS: http://www.imagemagick.org/script/convert.php
		*/

		if(!(is_dir($upload_dir . $pano_id . '/pano.tiles/'))){
			mkdir($upload_dir . $pano_id . '/pano.tiles/', 0777);
			chmod($upload_dir . $pano_id . '/pano.tiles/', 0777);
		};

		//Si es JPEG, lo renombro a jpg
		if(is_file ( $upload_dir . $pano_id.'/pano.jpeg' )){
			rename ( $upload_dir . $pano_id.'/pano.jpeg', $upload_dir . $pano_id.'/pano.jpg' );
		}

		$salida = shell_exec('identify -format "%m|%w" '.$upload_dir . $pano_id.'/pano.' . get_extension($file_name));

		
		$properties = explode('|', $salida);
		$format = strtolower($properties[0]);
		$witdh = $properties[1];

		//$max_witdh = 7000;   														  no limitamos mas a 7000

		//if ($format != 'jpeg' && $format != 'jpg' || $witdh > $max_witdh){          no limitamos mas a 7000
		if ($format != 'jpg' && $format != 'jpeg' && $format != 'JPG' && $format != 'JPEG'){
			//Convertir;
			/*                                                                        no limitamos mas a 7000
			 $new_witdh = '';
			if ($witdh > $max_witdh){
			$new_witdh = '-resize '.$max_witdh;
			}
			*/
			//$str_exec = 'convert '.$new_witdh.' '.$upload_dir . $pano_id.'/pano.' . get_extension($file_name).' '.$upload_dir . $pano_id.'/pano.jpg';

			$str_exec = 'convert '.$upload_dir . $pano_id.'/pano.' . get_extension($file_name).' '.$upload_dir . $pano_id.'/pano.jpg';

			shell_exec($str_exec);
			if(is_file($upload_dir . $pano_id.'/pano-0.jpg')){
				rename($upload_dir . $pano_id.'/pano-0.jpg', $upload_dir . $pano_id.'/pano.jpg');
			}
		}
		
		shell_exec('../../material/krpanotools maketiles '.$upload_dir . $pano_id.'/pano.jpg '.$upload_dir.$pano_id.'/pano.tiles/thumb900x450.jpg 0 -resize=900x*');
		shell_exec('../../material/krpanotools maketiles '.$upload_dir . $pano_id.'/pano.jpg '.$upload_dir.$pano_id.'/pano.tiles/thumb500x250.jpg 0 -resize=500x*');
		shell_exec('../../material/krpanotools maketiles '.$upload_dir . $pano_id.'/pano.jpg '.$upload_dir.$pano_id.'/pano.tiles/thumb200x100.jpg 0 -resize=232x*');
		shell_exec('../../material/krpanotools maketiles '.$upload_dir . $pano_id.'/pano.jpg '.$upload_dir.$pano_id.'/pano.tiles/thumb100x50.jpg 0 -resize=100x*');
		
		
		/* APLICATIVO QUE USABAMOS ANTES (IMAGEMAGICK)
		shell_exec('convert -resize 900 '.$upload_dir . $pano_id.'/pano.jpg '.$upload_dir.$pano_id.'/pano.tiles/thumb900x450.jpg');
		shell_exec('convert -resize 500 '.$upload_dir . $pano_id.'/pano.jpg '.$upload_dir.$pano_id.'/pano.tiles/thumb500x250.jpg');
		shell_exec('convert -resize 232 '.$upload_dir . $pano_id.'/pano.jpg '.$upload_dir.$pano_id.'/pano.tiles/thumb200x100.jpg');
		shell_exec('convert -resize 100 '.$upload_dir . $pano_id.'/pano.jpg '.$upload_dir.$pano_id.'/pano.tiles/thumb100x50.jpg');
		*/			

		//Count number of files
		$num_of_files = 0;
		$dir = $upload_dir.$pano_id.'/pano.tiles/';
		if ($handle = opendir($dir)) {
			while (($file = readdir($handle)) !== false){
				if (!in_array($file, array('.', '..')) && !is_dir($dir.$file))
					$num_of_files++;
			}
		}

		
		
		//Go to Step 2
		check_for_cancel($proc_id);
		mysql_query("insert into general_process_log (proc_id, user_id, tour_id, scene_id, pano_id, filename, step, step_desc, date, agent) values ('".$proc_id."', '".$_SESSION['usr']."', '".$tour_id."', '".$scene_id."', '".$pano_id."', '".$file_name."', ".$step.", 'End Image Processing - Step ".$step."', now(), '".$_SERVER['HTTP_USER_AGENT']."')");
		image_processing($tour_id, $pano_id, $scene_id, $file_name, 2);

		
	}else{
		
		/*Corro KRPANO */
	
		//(fuerzo el nombre del archivo a pano.jpg)
	
	
		$salida = shell_exec('../../material/krpanotools makepano -config=../../material/templates/normal.config ' . $upload_dir . $pano_id . '/pano.jpg');
	
		$salida = shell_exec('s3cmd -c /var/www/'.$bucket_config_file.' put ' . $upload_dir . $pano_id . ' s3://'.$cdn_string.'/panos/ --recursive 2>&1');
		
		check_for_cancel($proc_id);
		
		mysql_query("insert into general_process_log (proc_id, user_id, tour_id, scene_id, pano_id, filename, step, step_desc, date, agent) values ('".$proc_id."', '".$_SESSION['usr']."', '".$tour_id."', '".$scene_id."', '".$pano_id."', '".$file_name."', ".$step.", 'End Image Processing - Step ".$step."', now(), '".$_SERVER['HTTP_USER_AGENT']."')");
		
		/* Si todo fue bien, Cambio los estados a 1 */
	
		$ssqlp1 = "update panosxtour set state = 1 where id = ".$scene_id;
		mysql_query($ssqlp1);
	
		$ssqlp1 = "update panos set state = 1 where id = ".$pano_id;
		mysql_query($ssqlp1);
	
		//Count number of files
		$num_of_files = 0;
		$dir = $upload_dir.$pano_id.'/pano.tiles/';
		if ($handle = opendir($dir)) {
			while (($file = readdir($handle)) !== false){
				if (!in_array($file, array('.', '..')) && !is_dir($dir.$file))
					$num_of_files++;
			}
		}
	
		$salida = shell_exec('rm -r ' . $upload_dir . $pano_id);
	
		$step = 3;

		
		//Update de thumb_path, si no esta customizado
		//Check if Customized:
		$ssqlthumb = "SELECT * FROM tours_draft where id = ".$tour_id;
		$resultthumb = mysql_query($ssqlthumb);
		$rowthumb = mysql_fetch_array($resultthumb);
		if($rowthumb["tour_thumb_custom"] == 0){
			//If not customized, replace with 1st pano thumb
			$ssqlthumb = "SELECT * FROM panosxtour_draft where idtour = ".$tour_id." ORDER BY ord LIMIT 1";
			$resultthumb = mysql_query($ssqlthumb);
			$rowthumb = mysql_fetch_array($resultthumb);
		
			mysql_query("update tours_draft set tour_thumb_path = '".$cdn.'/panos/'.$rowthumb["idpano"].'/pano.tiles/'."' where id = ".$tour_id);
		
		}		
		
		check_for_cancel($proc_id);
		
		mysql_query("insert into general_process_log (proc_id, user_id, tour_id, scene_id, pano_id, filename, step, step_desc, date, agent) values ('".$proc_id."', '".$_SESSION['usr']."', '".$tour_id."', '".$scene_id."', '".$pano_id."', '".$file_name."', ".$step.", 'End', now(), '".$_SERVER['HTTP_USER_AGENT']."')");				
		
		exit_status(array(
				'result' => 'SUCCESS',
				'msg' => 'Pano was created successfuly!',
				'num_of_files' => $num_of_files,
				'params' => array(
						'thumb_path' => $cdn. str_replace('..', '', $upload_dir) . $pano_id . '/pano.tiles/thumb200x100.jpg')
		));
	};
	
	
}

function check_for_cancel($proc_id){

	global $upload_dir;
	global $tour_id;

	$ssql_check = "SELECT * FROM general_process_log where proc_id = '".$proc_id."' and cancel_requested = 1 order by id desc";

	//echo $ssql_check;

	$result_check = mysql_query($ssql_check);
	if($row_check = mysql_fetch_array($result_check)){

		//echo "ENTRO A CANCELAR";

		//Si pidió cancelación, elimino archivos y registros de BD:
		$pano_id = $row_check["pano_id"];
		$scene_id = $row_check["scene_id"];
		$filename = $row_check["filename"];

		if($pano_id != 0){
			mysql_query("delete from panos where id = ".$pano_id);
		}
		if($scene_id != 0){
			mysql_query("delete from panosxtour where id = ".$scene_id);
		}
		if($scene_id != 0){
			mysql_query("delete from panosxtour_draft where id = ".$scene_id);
		}

		if($pano_id != 0){
			$salida = shell_exec('rm -r ' . $upload_dir . $pano_id);
			//Borro cloud
			$salida = shell_exec('s3cmd -c /var/www/'.$bucket_config_file.' del s3://'.$cdn_string.'/panos/'.$pano_id.' --recursive 2>&1');
		}

		mysql_query("insert into general_process_log (proc_id, user_id, tour_id, scene_id, pano_id, filename, step, step_desc, date, agent) values ('".$proc_id."', '".$_SESSION['usr']."', '".$tour_id."', '".$scene_id."', '".$pano_id."', '".$filename."', -3, 'Cancel process finished', now(), '".$_SERVER['HTTP_USER_AGENT']."')");

		die();
	}

}

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

?>
