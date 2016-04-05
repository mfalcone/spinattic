<?php
//customizer

$restrict = 1;

ini_set("display_errors", 0);

ini_set('memory_limit', '-1');


include_once('simpleimage.php');
require_once("functions.php");
require_once("fineuploader_handler.php");


session_write_close();

$temp_folder = "../../temp_upload/files";
$temp_chunks_folder = "../../temp_upload/chunks";
//$temp_chunks_folder = "../../../../../chunks";
$upload_dir = '../../panos/';


$tour_id = $_REQUEST['tour_id'];
$proc_id = $_POST["proc_id"];
$qquuid = $_POST["qquuid"]; //El id de la carpeta que manda el fineuploader
$filename = $_POST["qqfilename"];
$qqtotalparts = $_POST["qqtotalparts"]; //La cantidad de chunks que se armaron, se usa para cuando manda el "done" disparar el combineChunks o no 

$file = $temp_folder.'/'.$qquuid.'/'.$filename;


//Si no hay registros en la tabla de log, inserto el inicio
$ssql_check = "SELECT * FROM general_process_log where proc_id = '".$proc_id."' order by id desc";
$result_check = mysql_query($ssql_check);
if(!($row_check = mysql_fetch_array($result_check))){

	/*
	 if(array_key_exists('pic',$_FILES) && $_FILES['pic']['error'] == 0 )
	 {
	$pic = $_FILES['pic'];
	}

	$filename = $pic['name'];
	*/

	mysql_query("insert into general_process_log (proc_id, user_id, tour_id, filename, step, step_desc, date, agent, qquuid) values ('".$proc_id."', '".$_SESSION['usr']."', '".$tour_id."', '".$filename."', 0, 'Start Upload', now(), '".$_SERVER['HTTP_USER_AGENT']."', '".$qquuid."')");

}else{

	$pano_id = $row_check["pano_id"];
	$scene_id = $row_check["scene_id"];
	$filename = $row_check["filename"];
	$tour_id = $row_check["tour_id"];

}

//CANCEL PROCESS FUNCTION -----------------------------------------------------------------------------------------------------

if(isset($_GET["c"]) && $_GET["c"] == 1){

	mysql_query("insert into general_process_log (proc_id, user_id, tour_id, pano_id, scene_id, filename, step, step_desc, date, agent, qquuid) values ('".$proc_id."', '".$_SESSION['usr']."', '".$tour_id."', '".$pano_id."', '".$scene_id."', '".$filename."', -2, 'Cancel Requested', now(), '".$_SERVER['HTTP_USER_AGENT']."', '".$qquuid."')");
	mysql_query("update general_process_log set cancel_requested = 1 where proc_id = '".$proc_id."'");

	//Si el que cancelo estaba en waiting, reordeno las colas, sino, le bajo un puesto a todos
	$ssqlp = "SELECT max(step) as step FROM general_process_log where proc_id = '".$proc_id."'";
	$result = mysql_query($ssqlp);
	$row = mysql_fetch_array($result);

	if($row["step"] == 1){
		$ssqlp = "SELECT * FROM general_process_log where tour_id = '".$tour_id."' and proc_id <> '".$proc_id."' and cancel_requested = 0 and process_finished = 0 and queue_pos > 0 order by queue_pos";
		$result = mysql_query($ssqlp);
		$i = 1;
		while($row = mysql_fetch_array($result)){
			mysql_query("update general_process_log set queue_pos = '".$i."' where proc_id = '".$row["proc_id"]."' and queue_pos > 0");
			$i++;
		}
	}else{
		mysql_query("update general_process_log set queue_pos = queue_pos - 1 where tour_id = '".$tour_id."' and queue_pos > 0");
	}
	
	check_for_cancel($proc_id);
	
	die();
}

//END CANCEL PROCESS FUNCTION -----------------------------------------------------------------------------------------------------


//LIMPIEZA DE CARPETAS DE CHUNKS Y FILE, LLAMADO CUANDO SE TERMINA DE CANCELAR (customizer/js/views/main/UploaderViewD.js linea 225 - if(newStatus =="canceled"))
if(isset($_GET["clean"]) && $_GET["clean"] != ''){
	sleep(10); //Por el borrado de chunks, que pueden estar aun subiendo
	clean_chunks($_GET["clean"]);
	die('ok - '.$_GET["clean"]);
}


//Para cuando le da a "Create tour" en el modal (cuando termina de subir las 1eras panos, con esto asigno el thumb y las lat y lon finales al tour) - También lo disparamos cuando termina de subir cada pano por si abandona el modal sin darle create tour
if(isset($_GET["f"]) && $_GET["f"] == "1"){

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


	//Checkeo si el tour no tiene asignado lat y lon y le asigno el de la 1er pano que lo tenga
	$lat = '';
	$lon = '';
	$assigned = '';
	$ssqlcheck = "SELECT * FROM panosxtour_draft where idtour = ".$tour_id." ORDER BY ord";
	$resultcheck = mysql_query($ssqlcheck);
	while($rowcheck = mysql_fetch_array($resultcheck)){
		//echo $rowcheck["idpano"].$rowcheck["lat"].$rowcheck["lng"]."<br>";

		$lat = $rowcheck["lat"];
		$lon = $rowcheck["lng"];

		if($lat != '' && $lon != '' && $assigned == ''){
			mysql_query("update tours_draft set lat = '".$lat."', lon = '".$lon."' where id = '".$tour_id."'");
			mysql_query("update customizer_draft set value = '".$lat."' where idtour = '".$tour_id."' and segment = 'GENERAL' and attr = 'lat'");
			mysql_query("update customizer_draft set value = '".$lon."' where idtour = '".$tour_id."' and segment = 'GENERAL' and attr = 'long'");
			$assigned = 1;
		}
	}
	die("ok");
}



//Función para resetear la queue de un tour (recibo el id del tour en $_GET["reset_queue"], se usa al entrar en la edición del tour para que no haya residuos de ediciones anteriores
if(isset($_GET["reset_queue"]) && $_GET["reset_queue"] != ""){
	$hma = '';

	if(isset($_GET['hma']) && $_GET['hma'] != ''){ //Por si viene del Mini Customizer, usado en la app

		$hma = $_GET['hma'];
		$result = mysql_query("select * from users where hash_mobile_api = '".$hma."'");
		$row = mysql_fetch_array($result);
		$user_id = $row["id"];

	}else{

		session_start();
		$user_id = $_SESSION['usr'];
	}
	//echo "update general_process_log set queue_pos = 0 where tour_id = '".$_GET["reset_queue"]."' and user_id = '".$user_id."'";
	mysql_query("update general_process_log set queue_pos = 0 where tour_id = '".$_GET["reset_queue"]."' and user_id = '".$user_id."'");
	die("ok");
}





//UPLOADER ------------------------------------------------------------------------------------------------------------------------------------------

check_for_cancel($proc_id);

$uploader = new UploadHandler();

// Specify the list of valid extensions, ex. array("jpeg", "xml", "bmp")
$uploader->allowedExtensions = array('tif', 'tiff','jpg', 'jpeg', 'png'); // all files types allowed by default

// Specify max file size in bytes.
$uploader->sizeLimit = 75000000;

// Specify the input name set in the javascript.
$uploader->inputName = "qqfile"; // matches Fine Uploader's default inputName value by default

// If you want to use the chunking/resume feature, specify the folder to temporarily save parts.
//../../../../temp_upload/
$uploader->chunksFolder = $temp_chunks_folder;

$method = $_SERVER["REQUEST_METHOD"];

if ($method == "POST") {
	header("Content-Type: text/plain");

	// Assumes you have a chunking.success.endpoint set to point here with a query parameter of "done".
	// For example: /myserver/handlers/endpoint.php?done
	if (isset($_GET["done"])) {
	
		if($qqtotalparts > 1){ //Si qqtotalparts > 1 hay chunks, por lo que tengo que construir el file antes de seguir
			$result = $uploader->combineChunks($temp_folder);
		}
		
		//FIN DE UPLOAD, EMPIEZAN VALIDACIONES Y PROCESOS POSTERIORES
		$ext = get_extension($file);
		$step = 0;
		
		$image_size = getimagesize($file);
		
		if($image_size[0] !== $image_size[1]*2){
		
			mysql_query("insert into general_process_log (proc_id, user_id, tour_id, filename, step, step_desc, date, agent, qquuid) values ('".$proc_id."', '".$_SESSION['usr']."', '".$tour_id."', '".$filename."', -1, 'ERROR: Image Ratio incorrect (2x1)', now(), '".$_SERVER['HTTP_USER_AGENT']."', '".$qquuid."')");
			mysql_query("update general_process_log set process_finished = 1 where proc_id = '".$proc_id."'");
		
			exit_status(array(
				'result' => 'ERROR',
				'msg' => 'Can\'t upload this image. Aspect ratio required: 2x1.'
			));
		}
		
		
		if($image_size[0] > 30000 || $image_size[1] > 30000){
		
			mysql_query("insert into general_process_log (proc_id, user_id, tour_id, filename, step, step_desc, date, agent, qquuid) values ('".$proc_id."', '".$_SESSION['usr']."', '".$tour_id."', '".$filename."', -1, 'ERROR: Max Image Size: 30000 px', now(), '".$_SERVER['HTTP_USER_AGENT']."', '".$qquuid."')");
			mysql_query("update general_process_log set process_finished = 1 where proc_id = '".$proc_id."'");
		
			exit_status(array(
				'result' => 'ERROR',
				'msg' => 'Can\'t upload this image. Max Image Size: 30000 px.'
			));
		}
		
		
		//Check if has lat and long
		$exif = exif_read_data($file);
		
		$lon = '';
		$lat = '';
		
		if(isset($exif["GPSLongitude"]) && isset($exif["GPSLongitudeRef"]) && isset($exif["GPSLatitude"]) && isset($exif["GPSLatitudeRef"])){
			$lon = getGps($exif["GPSLongitude"], $exif['GPSLongitudeRef']);
			$lat = getGps($exif["GPSLatitude"], $exif['GPSLatitudeRef']);
		}
		
		// Move the uploaded file from the temporary
		
		$ssqlp1 = "insert into panos (state, user, date, name, image_lat, image_lng) values (0, '".$_SESSION['usr']."', now(), '".mysql_real_escape_string($filename)."', '".$lat."', '".$lon."')";
		mysql_query($ssqlp1);
		$ssqlp = "SELECT max(id) as elid FROM panos where user = '".$_SESSION['usr']."'";
		$result = mysql_query($ssqlp);
		$row = mysql_fetch_array($result);
		$pano_id = $row["elid"];
		
		
		//Traigo el order
		$ssqlp = "SELECT max(ord) as elord FROM panosxtour_draft where idtour = '".$tour_id."'";
		$result = mysql_query($ssqlp) or die(mysql_error());
		$elord = 0;
		if($row = mysql_fetch_array($result)){
			$elord = $row["elord"] + 1;
		}
		
		
		$scene_name = str_replace('.'.$ext, '', $filename);
		$ssqlp1 = "insert into panosxtour_draft (idpano, ord, state, idtour, name, lat, lng, urlname) values (".$pano_id.", ".$elord.", 0, ".$tour_id.", '".  mysql_real_escape_string($scene_name)."', '".$lat."', '".$lon."', '".  mysql_real_escape_string($scene_name)."')";
		mysql_query($ssqlp1);
		$ssqlp = "SELECT max(id) as elid FROM panosxtour_draft where idpano = '".$pano_id."'";
		$result = mysql_query($ssqlp) or die(mysql_error());
		$row = mysql_fetch_array($result);
		$scene_id = $row["elid"];
		
		
		
		@mkdir ($upload_dir.$pano_id, 0777);
		chmod($upload_dir.$pano_id, 0777);
		
		
		//echo "DEB 1: ".$file." - ". $upload_dir.$pano_id.'/pano.'.$ext." - ".$tour_id." - ";
		
		if(rename($file, $upload_dir.$pano_id.'/pano.'.$ext) && isset($tour_id)){
			
			//Elimino carpeta de temp_upload/chunks
			if($qquuid != ''){$salida = shell_exec('rm -r ' . $temp_chunks_folder.'/'.$qquuid);}
			
			//Elimino carpeta de temp_upload/files
			if($qquuid != ''){$salida = shell_exec('rm -r ' . $temp_folder.'/'.$qquuid);}
			 
			
			//echo "DEB 2";
			
			chmod($upload_dir.$pano_id.'/pano.'.$ext, 0777);
		
			check_for_cancel($proc_id);
		
			mysql_query("insert into general_process_log (proc_id, user_id, tour_id, scene_id, pano_id, filename, step, step_desc, date, agent, qquuid) values ('".$proc_id."', '".$_SESSION['usr']."', '".$tour_id."', '".$scene_id."', '".$pano_id."', '".$filename."', ".$step.", 'End Upload', now(), '".$_SERVER['HTTP_USER_AGENT']."', '".$qquuid."')");
		
			//Check if avaiable slots, else wait
			check_for_slots();
		
			image_processing($tour_id, $pano_id, $scene_id, $filename, 2);
		
		}
		
		
		//si hubo un error borro el registro de la pano y la scene
		mysql_query("delete from panos where id = ".$pano_id);
		mysql_query("delete from panosxtour where id = ".$scene_id);
		mysql_query("delete from panosxtour_draft where id = ".$scene_id);
		
		if($pano_id != '' && $pano_id != 0){
			$salida = shell_exec('rm -r ' . $upload_dir . $pano_id);
			
			//Elimino archivos de carpeta temp_upload/chunks
			if($qquuid != ''){$salida = shell_exec('rm -r ' . $temp_chunks_folder.'/'.$qquuid);}
			
			//Elimino archivos de carpeta temp_upload/files
			if($qquuid != ''){$salida = shell_exec('rm -r ' . $temp_folder.'/'.$qquuid);}
			
			//DEBUG
			mysql_query("insert into general_process_log (proc_id, user_id, tour_id, scene_id, pano_id, filename, step, step_desc, date, agent, qquuid) values ('".$proc_id."', '".$_SESSION['usr']."', '".$tour_id."', '".$scene_id."', '".$pano_id."', '".$filename."', -1, 'DEBBUG ENTRY 1: rm -r ".$upload_dir.$pano_id."', now(), '".$_SERVER['HTTP_USER_AGENT']."', '".$qquuid."')");
		}
		
		
		
		mysql_query("insert into general_process_log (proc_id, user_id, tour_id, scene_id, pano_id, filename, step, step_desc, date, agent, qquuid) values ('".$proc_id."', '".$_SESSION['usr']."', '".$tour_id."', '".$scene_id."', '".$pano_id."', '".$filename."', -1, 'ERROR: Something went wrong with your upload', now(), '".$_SERVER['HTTP_USER_AGENT']."', '".$qquuid."')");
		
		exit_status(array(
				'result' => 'ERROR',
				'msg' => 'Something went wrong with your upload!'
		));
		
		
		
		
	}
	// Handles upload requests
	else {
		
		
		// Call handleUpload() with the name of the folder, relative to PHP's getcwd()
		$result = $uploader->handleUpload($temp_folder);
		check_for_cancel($proc_id);
		
	}

	echo json_encode($result);
	
	
}


// for delete file requests
else if ($method == "DELETE") {
	$result = $uploader->handleDelete($temp_folder);
	echo json_encode($result);
}
else {
	header("HTTP/1.0 405 Method Not Allowed");
}
//END UPLOADER ------------------------------------------------------------------------------------------------------------------------------------------



//IMAGE PROCESSING:

function image_processing($tour_id, $pano_id, $scene_id, $file_name, $step){
	global $upload_dir;
	global $bucket_config_file;
	global $environment;
	global $cdn;
	global $cdn_string;
	global $proc_id;
	global $lat;
	global $lon;
	global $qquuid;

	check_for_cancel($proc_id);

	mysql_query("insert into general_process_log (proc_id, user_id, tour_id, scene_id, pano_id, filename, step, step_desc, date, agent, qquuid) values ('".$proc_id."', '".$_SESSION['usr']."', '".$tour_id."', '".$scene_id."', '".$pano_id."', '".$file_name."', ".$step.", 'Start Image Processing - Step ".$step."', now(), '".$_SERVER['HTTP_USER_AGENT']."', '".$qquuid."')");

	if($step == 2){

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
		mysql_query("insert into general_process_log (proc_id, user_id, tour_id, scene_id, pano_id, filename, step, step_desc, date, agent, qquuid) values ('".$proc_id."', '".$_SESSION['usr']."', '".$tour_id."', '".$scene_id."', '".$pano_id."', '".$file_name."', ".$step.", 'End Image Processing - Step ".$step."', now(), '".$_SERVER['HTTP_USER_AGENT']."', '".$qquuid."')");
		image_processing($tour_id, $pano_id, $scene_id, $file_name, 3);


	}else{

		/*Corro KRPANO */

		//(fuerzo el nombre del archivo a pano.jpg)


		$salida = shell_exec('../../material/krpanotools makepano -config=../../material/templates/normal.config ' . $upload_dir . $pano_id . '/pano.jpg');

		$salida = shell_exec('s3cmd -c /var/www/'.$bucket_config_file.' put ' . $upload_dir . $pano_id . ' s3://'.$cdn_string.'/panos/ --recursive 2>&1');

		check_for_cancel($proc_id);

		mysql_query("insert into general_process_log (proc_id, user_id, tour_id, scene_id, pano_id, filename, step, step_desc, date, agent, qquuid) values ('".$proc_id."', '".$_SESSION['usr']."', '".$tour_id."', '".$scene_id."', '".$pano_id."', '".$file_name."', ".$step.", 'End Image Processing - Step ".$step."', now(), '".$_SERVER['HTTP_USER_AGENT']."', '".$qquuid."')");

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

		if($pano_id != '' && $pano_id != 0){

			//Elimino archivos de carpeta panos
			$salida = shell_exec('rm -r ' . $upload_dir . $pano_id);
			
			//Elimino archivos de carpeta temp_upload/files
			if($qquuid != ''){$salida = shell_exec('rm -r ' . $temp_folder.'/'.$qquuid);}			

			//DEBBUG
			mysql_query("insert into general_process_log (proc_id, user_id, tour_id, scene_id, pano_id, filename, step, step_desc, date, agent, qquuid) values ('".$proc_id."', '".$_SESSION['usr']."', '".$tour_id."', '".$scene_id."', '".$pano_id."', '".$file_name."', ".$step.", 'DEBBUG ENTRY 2: rm -r ".$upload_dir.$pano_id."', now(), '".$_SERVER['HTTP_USER_AGENT']."', '".$qquuid."')");
		}
		
		$step = 4;



	

		check_for_cancel($proc_id);
	
		

		mysql_query("insert into general_process_log (proc_id, user_id, tour_id, scene_id, pano_id, filename, step, step_desc, date, agent, qquuid) values ('".$proc_id."', '".$_SESSION['usr']."', '".$tour_id."', '".$scene_id."', '".$pano_id."', '".$file_name."', ".$step.", 'End', now(), '".$_SERVER['HTTP_USER_AGENT']."', '".$qquuid."')");
		mysql_query("update general_process_log set queue_pos = queue_pos - 1 where tour_id = '".$tour_id."' and queue_pos > 0");
		
		mysql_query("update general_process_log set process_finished = 1 where proc_id = '".$proc_id."'");

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
	
	//echo "IN";
	
	global $upload_dir;
	global $tour_id;
	global $temp_chunks_folder;
	global $temp_folder;
	global $qquuid;
	global $uploader;
	
	$ssql_check = "SELECT * FROM general_process_log where proc_id = '".$proc_id."' and cancel_requested = 1 order by id desc";
	
	//echo $ssql_check;
	
	$result_check = mysql_query($ssql_check);
	if($row_check = mysql_fetch_array($result_check)){
		
		//echo "ENTRO A CANCELAR";
		//echo $ssql_check;
		
		//Si pidió cancelación, elimino archivos y registros de BD:
		$pano_id = $row_check["pano_id"];
		$scene_id = $row_check["scene_id"];
		$filename = $row_check["filename"];
		
		if($pano_id != 0){mysql_query("delete from panos where id = ".$pano_id);}
		if($scene_id != 0){mysql_query("delete from panosxtour where id = ".$scene_id);}
		if($scene_id != 0){mysql_query("delete from panosxtour_draft where id = ".$scene_id);}
		
		if($pano_id != '' && $pano_id != 0){
			$salida = shell_exec('rm -r ' . $upload_dir . $pano_id);
			
			//DEBUG
			mysql_query("insert into general_process_log (proc_id, user_id, tour_id, scene_id, pano_id, filename, step, step_desc, date, agent, cancel_requested, qquuid) values ('".$proc_id."', '".$_SESSION['usr']."', '".$tour_id."', '".$scene_id."', '".$pano_id."', '".$filename."', -3, 'DEBBUG ENTRY 3: rm -r ".$upload_dir.$pano_id."', now(), '".$_SERVER['HTTP_USER_AGENT']."', 1, '".$qquuid."')");
			
			//Borro cloud
			$salida = shell_exec('s3cmd -c /var/www/'.$bucket_config_file.' del s3://'.$cdn_string.'/panos/'.$pano_id.'/ --recursive 2>&1');
		}

		//$result = $uploader->cancel($qquuid);
		//echo "RESULT:" . $result;
		
		//Limpio carpetas
		clean_chunks($qquuid);
		/*
		//Elimino archivos de carpeta temp_upload/files
		$salida = shell_exec('rm -r ' . $temp_folder.'/'.$qquuid);
		
		//Elimino archivos de carpeta temp_upload/chunks
		$salida = shell_exec('rm -r ' . $temp_chunks_folder.'/'.$qquuid);
		*/
		
		mysql_query("insert into general_process_log (proc_id, user_id, tour_id, scene_id, pano_id, filename, step, step_desc, date, agent, cancel_requested, qquuid) values ('".$proc_id."', '".$_SESSION['usr']."', '".$tour_id."', '".$scene_id."', '".$pano_id."', '".$filename."', -3, 'Cancel process finished', now(), '".$_SERVER['HTTP_USER_AGENT']."', 1, '".$qquuid."')");
		
		mysql_query("update general_process_log set process_finished = 1 where proc_id = '".$proc_id."'");
		
		die();
	}
	
}

function clean_chunks($qquuid){
	
	global $temp_folder;
	global $temp_chunks_folder;
	
	//Elimino archivos de carpeta temp_upload/files
	if($qquuid != ''){$salida = shell_exec('rm -r ' . $temp_folder.'/'.$qquuid);}
	
	//Elimino archivos de carpeta temp_upload/chunks
	if($qquuid != ''){$salida = shell_exec('rm -r ' . $temp_chunks_folder.'/'.$qquuid);}
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

function check_for_slots(){
	
	global $qquuid;
	global $pano_id;
	global $scene_id;
	global $filename;
	global $tour_id;
	global $proc_id;
	
	//Tomo cantidad de segundos maximo para esperar por slot disponible
	$ssql_check = "SELECT * FROM customizer_queue_limits where type = 'max_process_wait_for_slot'";
	$result_check = mysql_query($ssql_check);
	$row_check = mysql_fetch_array($result_check);
	
	$max_process_wait_for_slot = $row_check["limit"];

	//Tomo cantidad de procesos maximos que deben haber al mismo tiempo
	$ssql_check = "SELECT * FROM customizer_queue_limits where type = 'processes'";
	$result_check = mysql_query($ssql_check);
	$row_check = mysql_fetch_array($result_check);
	
	$processes = $row_check["limit"];	
	
	//Chequeo que el tour no tenga activos mas procesos de los que admito (procesos del tour que no esten finalizados o cancelados ni que esten en espera)
	$ssql_check = "SELECT proc_id FROM general_process_log where tour_id = ".$tour_id." and 
		proc_id not in 
			(select proc_id from general_process_log where tour_id = ".$tour_id." and (process_finished = 1 or cancel_requested = 1)) and 
		proc_id not in 
			(select general_process_log.proc_id from general_process_log,
			(SELECT max(id) as id FROM general_process_log group by proc_id) as filter
			where general_process_log.id = filter.id and tour_id = ".$tour_id." and (step = 1 or step = 0)) 
		and proc_id <> 0 group by proc_id";


	$result_check = mysql_query($ssql_check);
	$cant_procesos_activos = mysql_num_rows($result_check);

	//echo $ssql;
	$ssql = "insert into text values ('".$proc_id."',  1, '".$cant_procesos_activos."', '".$processes."')";
	mysql_query($ssql);
	
	if($cant_procesos_activos >= $processes){
		$habilitar = 0;
	}else{
		$habilitar = 1;
		mysql_query("insert into general_process_log (proc_id, user_id, tour_id, scene_id, pano_id, filename, step, step_desc, date, agent, qquuid) values ('".$proc_id."', '".$_SESSION['usr']."', '".$tour_id."', '".$scene_id."', '".$pano_id."', '".$filename."', 1, 'Slot Found, continuing with process', now(), '".$_SERVER['HTTP_USER_AGENT']."', '".$qquuid."')");
	}
	
	if ($habilitar == 0 && $max_process_wait_for_slot > 0){ //Lo pongo en cola
		//Tomo máximo de queue_pos
		$ssql_check = "SELECT max(queue_pos) as queue_pos FROM general_process_log where tour_id = ".$tour_id;
		$result_check = mysql_query($ssql_check);
		$row_check = mysql_fetch_array($result_check);
		$queue_pos = $row_check["queue_pos"] + 1;
		
		$max_process_wait_for_slot = $max_process_wait_for_slot * $queue_pos; //Multiplico el tiempo de timeout para que no se disparen todos al mismo tiempo
		
		mysql_query("insert into general_process_log (proc_id, user_id, tour_id, scene_id, pano_id, filename, step, step_desc, date, agent, queue_pos, qquuid) values ('".$proc_id."', '".$_SESSION['usr']."', '".$tour_id."', '".$scene_id."', '".$pano_id."', '".$filename."', 1, 'Waiting for Slot', now(), '".$_SERVER['HTTP_USER_AGENT']."', ".$queue_pos.", '".$qquuid."')");
	}	
	
	$canceled = 0;
	
	while (($habilitar == 0 && $max_process_wait_for_slot > 0) || $cancelled == 1){
		
		//Chequeo si se canceló este proc
		$ssql_check = "select * from general_process_log where proc_id = '".$proc_id."' and cancel_requested = 1";
		$result_check = mysql_query($ssql_check);
		if($row_check = mysql_fetch_array($result_check)){
			check_for_cancel($proc_id);
			$cancelled = 1;
		}else{
			//Chequeo que el tour no tenga activos mas procesos de los que admito (procesos del tour que no esten finalizados o cancelados ni que esten en espera)
			$ssql_check = "SELECT proc_id FROM general_process_log where tour_id = ".$tour_id." and
			proc_id not in
			(select proc_id from general_process_log where tour_id = ".$tour_id." and (process_finished = 1 or cancel_requested = 1)) and
			proc_id not in
			(select general_process_log.proc_id from general_process_log,
			(SELECT max(id) as id FROM general_process_log group by proc_id) as filter
			where general_process_log.id = filter.id and tour_id = ".$tour_id." and (step = 1 or step = 0))
			and proc_id <> 0 group by proc_id";
			$result_check = mysql_query($ssql_check);
			$cant_procesos_activos = mysql_num_rows($result_check);
			
			$ssql = "update text set text = '".$ssql_check."' permitidos = '".$processes."', donde = 2, cant = '".$cant_procesos_activos."' where text = '".$proc_id."'";
			mysql_query($ssql);
			
			if($cant_procesos_activos < $processes){
				//Chequeo si este proceso es el próximo en ser habilitado
				$ssql_check = "SELECT max(queue_pos) as queue_pos FROM general_process_log where proc_id = '".$proc_id."'";
				$result_check = mysql_query($ssql_check);
				$row_check = mysql_fetch_array($result_check);
				if($row_check["queue_pos"] <= 0){
					$habilitar = 1;
					mysql_query("insert into general_process_log (proc_id, user_id, tour_id, scene_id, pano_id, filename, step, step_desc, date, agent, qquuid) values ('".$proc_id."', '".$_SESSION['usr']."', '".$tour_id."', '".$scene_id."', '".$pano_id."', '".$filename."', 1, 'Slot Found, continuing with process', now(), '".$_SERVER['HTTP_USER_AGENT']."', '".$qquuid."')");
				}
			}
			
			$max_process_wait_for_slot--;
			
			if ($max_process_wait_for_slot <= 0){
				mysql_query("insert into general_process_log (proc_id, user_id, tour_id, scene_id, pano_id, filename, step, step_desc, date, agent, qquuid) values ('".$proc_id."', '".$_SESSION['usr']."', '".$tour_id."', '".$scene_id."', '".$pano_id."', '".$filename."', 1, 'Continuing for timeout on waiting for slots', now(), '".$_SERVER['HTTP_USER_AGENT']."', '".$qquuid."')");
			}
			
			sleep(1);
		}
	
	
		
		
	}	

}

//Funciones para obtener formato de GPS
function getGps($exifCoord, $hemi) {

	$degrees = count($exifCoord) > 0 ? gps2Num($exifCoord[0]) : 0;
	$minutes = count($exifCoord) > 1 ? gps2Num($exifCoord[1]) : 0;
	$seconds = count($exifCoord) > 2 ? gps2Num($exifCoord[2]) : 0;

	$flip = ($hemi == 'W' or $hemi == 'S') ? -1 : 1;

	return $flip * ($degrees + $minutes / 60 + $seconds / 3600);

}

function gps2Num($coordPart) {

	$parts = explode('/', $coordPart);

	if (count($parts) <= 0)
		return 0;

	if (count($parts) == 1)
		return $parts[0];

	return floatval($parts[0]) / floatval($parts[1]);
}







?>
