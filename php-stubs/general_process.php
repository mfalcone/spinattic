<?php

//DEV

$restrict = 1;

ini_set("display_errors", 0);

ini_set('memory_limit', '-1');
require("../inc/auth.inc");
require("../inc/conex.inc");
include('../php-stubs/simpleimage.php');


session_write_close();

$proc_id = $_GET["proc_id"];
$cancel = $_GET["c"];
$tour_id = $_REQUEST['tour_id'];


$upload_dir = '../panos/';


//Funcion para resetear la queue de un tour (recibo el id del tour en $_GET["reset_queue"], se usa al entrar en la edición del tour para que no haya residuos de ediciones anteriores
if(isset($_GET["reset_queue"]) && $_GET["reset_queue"] != ""){
	mysql_query("update general_process_log set queue_pos = 0 where tour_id = '".$_GET["reset_queue"]."' and user_id = '".$_SESSION['usr']."'");
	die("ok");
}


//Si no hay registros en la tabla de log, inserto el inicio
$ssql_check = "SELECT * FROM general_process_log where proc_id = '".$proc_id."' order by id desc";
$result_check = mysql_query($ssql_check);
if(!($row_check = mysql_fetch_array($result_check))){
	if(array_key_exists('pic',$_FILES) && $_FILES['pic']['error'] == 0 )
	{
		$pic = $_FILES['pic'];
	}
	
	$filename = $pic['name']; 
	
	mysql_query("insert into general_process_log (proc_id, user_id, tour_id, filename, step, step_desc, date, agent) values ('".$proc_id."', '".$_SESSION['usr']."', '".$tour_id."', '".$filename."', 0, 'Start Upload', now(), '".$_SERVER['HTTP_USER_AGENT']."')");

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
	
			mysql_query("insert into general_process_log (proc_id, user_id, tour_id, filename, step, step_desc, date, agent) values ('".$proc_id."', '".$_SESSION['usr']."', '".$tour_id."', '".$pic['name']."', -1, 'ERROR: File Ext not allowed', now(), '".$_SERVER['HTTP_USER_AGENT']."')");
	
			exit_status(array(
					'result' => 'ERROR',
					'msg' => 'Only '.implode(',',$allowed_ext).' files are allowed!'
			));
		}
	
		$image_size = getimagesize($pic['tmp_name']);
	
		if($image_size[0] !== $image_size[1]*2)
		{
	
			mysql_query("insert into general_process_log (proc_id, user_id, tour_id, filename, step, step_desc, date, agent) values ('".$proc_id."', '".$_SESSION['usr']."', '".$tour_id."', '".$pic['name']."', -1, 'ERROR: Image Ratio incorrect (2x1)', now(), '".$_SERVER['HTTP_USER_AGENT']."')");
	
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
		$pano_id = $row["elid"];
	

		
		
		$scene_name = str_replace('.'.$ext, '', $pic['name']);
		$ssqlp1 = "insert into panosxtour_draft (idpano, state, idtour, name) values (".$pano_id.", 0, ".$tour_id.", '".  mysql_real_escape_string($scene_name)."')";
		mysql_query($ssqlp1);
		$ssqlp = "SELECT max(id) as elid FROM panosxtour_draft where idpano = '".$pano_id."'";
		$result = mysql_query($ssqlp) or die(mysql_error());
		$row = mysql_fetch_array($result);
		$scene_id = $row["elid"];
	
	
		@mkdir ($upload_dir.$pano_id, 0777);
		chmod($upload_dir.$pano_id, 0777);
	
		if(move_uploaded_file($pic['tmp_name'], $upload_dir.$pano_id.'/pano.'.$ext) && isset($tour_id))
		{
			chmod($upload_dir.$pano_id.'/pano.'.$ext, 0777);
			
			check_for_cancel($proc_id);
			
			mysql_query("insert into general_process_log (proc_id, user_id, tour_id, scene_id, pano_id, filename, step, step_desc, date, agent) values ('".$proc_id."', '".$_SESSION['usr']."', '".$tour_id."', '".$scene_id."', '".$pano_id."', '".$pic['name']."', ".$step.", 'End Upload', now(), '".$_SERVER['HTTP_USER_AGENT']."')");
			
			//Check if avaiable slots, else wait
			check_for_slots();			
			
			image_processing($tour_id, $pano_id, $scene_id, $pic['name'], 2);

		}
	
	}
	
	/*si hubo un error borro el registro de la pano y la scene*/
	mysql_query("delete from panos where id = ".$pano_id);
	mysql_query("delete from panosxtour where id = ".$scene_id);
	mysql_query("delete from panosxtour_draft where id = ".$scene_id);
	
	$salida = shell_exec('rm -r ' . $upload_dir . $pano_id);
	
	mysql_query("insert into general_process_log (proc_id, user_id, tour_id, scene_id, pano_id, filename, step, step_desc, date, agent) values ('".$proc_id."', '".$_SESSION['usr']."', '".$tour_id."', '".$scene_id."', '".$pano_id."', '".$pic['name']."', -1, 'ERROR: Something went wrong with your upload', now(), '".$_SERVER['HTTP_USER_AGENT']."')");
	
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

		shell_exec('../material/krpanotools maketiles '.$upload_dir . $pano_id.'/pano.jpg '.$upload_dir.$pano_id.'/pano.tiles/thumb900x450.jpg 0 -resize=900x*');
		shell_exec('../material/krpanotools maketiles '.$upload_dir . $pano_id.'/pano.jpg '.$upload_dir.$pano_id.'/pano.tiles/thumb500x250.jpg 0 -resize=500x*');
		shell_exec('../material/krpanotools maketiles '.$upload_dir . $pano_id.'/pano.jpg '.$upload_dir.$pano_id.'/pano.tiles/thumb200x100.jpg 0 -resize=232x*');
		shell_exec('../material/krpanotools maketiles '.$upload_dir . $pano_id.'/pano.jpg '.$upload_dir.$pano_id.'/pano.tiles/thumb100x50.jpg 0 -resize=100x*');
		
		
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
		image_processing($tour_id, $pano_id, $scene_id, $file_name, 3);


	}else{

		/*Corro KRPANO */

		//(fuerzo el nombre del archivo a pano.jpg)


		$salida = shell_exec('../material/krpanotools makepano -config=../material/templates/normal.config ' . $upload_dir . $pano_id . '/pano.jpg');

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

		$step = 4;


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
		
		if($pano_id != 0){mysql_query("delete from panos where id = ".$pano_id);}
		if($scene_id != 0){mysql_query("delete from panosxtour where id = ".$scene_id);}
		if($scene_id != 0){mysql_query("delete from panosxtour_draft where id = ".$scene_id);}
		
		if($pano_id != 0 && $pano_id != ''){
			$salida = shell_exec('rm -r ' . $upload_dir . $pano_id);
			//Borro cloud
			$salida = shell_exec('s3cmd -c /var/www/'.$bucket_config_file.' del s3://'.$cdn_string.'/panos/'.$pano_id.' --recursive 2>&1');
		}
		
		mysql_query("insert into general_process_log (proc_id, user_id, tour_id, scene_id, pano_id, filename, step, step_desc, date, agent, cancel_requested) values ('".$proc_id."', '".$_SESSION['usr']."', '".$tour_id."', '".$scene_id."', '".$pano_id."', '".$filename."', -3, 'Cancel process finished', now(), '".$_SERVER['HTTP_USER_AGENT']."', 1)");
		
		mysql_query("update general_process_log set process_finished = 1 where proc_id = '".$proc_id."'");
		
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

function check_for_slots(){
	
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
		mysql_query("insert into general_process_log (proc_id, user_id, tour_id, scene_id, pano_id, filename, step, step_desc, date, agent) values ('".$proc_id."', '".$_SESSION['usr']."', '".$tour_id."', '".$scene_id."', '".$pano_id."', '".$filename."', 1, 'Slot Found, continuing with process', now(), '".$_SERVER['HTTP_USER_AGENT']."')");
	}
	
	if ($habilitar == 0 && $max_process_wait_for_slot > 0){ //Lo pongo en cola
		//Tomo máximo de queue_pos
		$ssql_check = "SELECT max(queue_pos) as queue_pos FROM general_process_log where tour_id = ".$tour_id;
		$result_check = mysql_query($ssql_check);
		$row_check = mysql_fetch_array($result_check);
		$queue_pos = $row_check["queue_pos"] + 1;
		
		$max_process_wait_for_slot = $max_process_wait_for_slot * $queue_pos; //Multiplico el tiempo de timeout para que no se disparen todos al mismo tiempo
		
		mysql_query("insert into general_process_log (proc_id, user_id, tour_id, scene_id, pano_id, filename, step, step_desc, date, agent, queue_pos) values ('".$proc_id."', '".$_SESSION['usr']."', '".$tour_id."', '".$scene_id."', '".$pano_id."', '".$filename."', 1, 'Waiting for Slot', now(), '".$_SERVER['HTTP_USER_AGENT']."', ".$queue_pos.")");
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
					mysql_query("insert into general_process_log (proc_id, user_id, tour_id, scene_id, pano_id, filename, step, step_desc, date, agent) values ('".$proc_id."', '".$_SESSION['usr']."', '".$tour_id."', '".$scene_id."', '".$pano_id."', '".$filename."', 1, 'Slot Found, continuing with process', now(), '".$_SERVER['HTTP_USER_AGENT']."')");
				}
			}
			
			$max_process_wait_for_slot--;
			
			if ($max_process_wait_for_slot <= 0){
				mysql_query("insert into general_process_log (proc_id, user_id, tour_id, scene_id, pano_id, filename, step, step_desc, date, agent) values ('".$proc_id."', '".$_SESSION['usr']."', '".$tour_id."', '".$scene_id."', '".$pano_id."', '".$filename."', 1, 'Continuing for timeout on waiting for slots', now(), '".$_SERVER['HTTP_USER_AGENT']."')");
			}
			
			sleep(1);
		}
	
	
		
		
	}	

}


?>
