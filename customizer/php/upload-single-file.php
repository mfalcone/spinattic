<?php

//ini_set("display_errors", 1);

include_once('simpleimage.php');
require("functions.php");

//Switcheo de Folders
function get_folder($case, $idtour)
{
	switch ($case){
		case 'hotspots':
			$folder = '../../tours/'.$idtour.'/hotspots/';
			$path = $http.$_SERVER[HTTP_HOST].'/tours/'.$idtour.'/hotspots/';
			break;
		case 'hotspot_styles':
			$folder = '../../tours/'.$idtour.'/hotspot_styles/';
			$path = $http.$_SERVER[HTTP_HOST].'/tours/'.$idtour.'/hotspot_styles/';
			break;
		case 'skills': //Para los skills, recibo el template_id
			$folder = '../../temp_upload/';
			$path = $http.$_SERVER[HTTP_HOST].'/temp_upload/';
			break;
		case 'tour_thumb':
			$folder = '../../tours/'.$idtour.'/thumb/';
			$path = $http.$_SERVER[HTTP_HOST].'/tours/'.$idtour.'/thumb/';
			break;			
	}

	$return = array('folder' => strtolower($folder),'path' => strtolower($path));
	return json_encode($return);

}

//End Switcheo Folders

function after_upload_actions($case, $tour_id, $path, $file, $folder){ //Acciones particulares despues del upload
	global $bucket_config_file;
	global $environment;
	global $cdn;
	global $cdn_string;	
	
	switch ($case){
		case 'tour_thumb':

			
			//Restriccion. Debe tener mas de 900px de ancho por 450px de alto
			$image_size = getimagesize($folder . $file);
			
			if($image_size[0] < 900 || $image_size[1] < 450){
				
				die(
						exit_status(array(
                			'result' => 'ERROR', 
                			'msg' => 'Min file size: 900 px x 450 px', 
		                	'params' => array(
		                             
		                                'file_name' => $pic_name,
		                				'path' => $new_path,
		                		
		                    
		        			)))
						);
				
			}else{
			
				$rand = date("YmdHis")."_";
				
				shell_exec('convert -resize 900 '.$folder . $file.' '.$folder.$rand.'thumb900x450.jpg');
				shell_exec('convert -resize 500 '.$folder . $file.' '.$folder.$rand.'thumb500x250.jpg');
				shell_exec('convert -resize 232 '.$folder . $file.' '.$folder.$rand.'thumb200x100.jpg');
				shell_exec('convert -resize 100 '.$folder . $file.' '.$folder.$rand.'thumb100x50.jpg');			
				
				//Elimino lo que haya en el cloud
				$salida = shell_exec('s3cmd -c /var/www/'.$bucket_config_file.' del s3://'.$cdn_string.'/tours/'.$tour_id.'/thumb --recursive 2>&1');
			
				//Subo
				$salida = shell_exec('s3cmd -c /var/www/'.$bucket_config_file.' put ' . $folder . ' s3://'.$cdn_string.'/tours/'.$tour_id.'/thumb/ --recursive 2>&1');
				
				//echo 's3cmd -c /var/www/'.$bucket_config_file.' put ' . str_replace('/thumb/', '', $folder) . ' s3://'.$cdn_string.'/tours/ --recursive 2>&1';
				
				$ssqlp1="update tours_draft set tour_thumb_custom = 1, tour_thumb_path = '".$cdn."/tours/".$tour_id."/thumb/".$rand."' where id = ".$tour_id;
				mysql_query($ssqlp1);
				audit($ssqlp1, 'tour_thumb', $tour_id, mysql_affected_rows());				
				
				$file = 'thumb200x100.jpg';
			
			}
			
			//elimino lo que haya
			shell_exec('rm -r ' . $folder);			
			
			$complete_path = $cdn."/tours/".$tour_id."/thumb/".$rand.$file."?rndstr=".rand();
			
			break;
			
		case 'hotspot_styles':

			
			//Restriccion. Debe tener menos de 500px de ancho por 500px de alto
			$image_size = getimagesize($folder . $file);
			
			if($image_size[0] > 500 || $image_size[1] > 500){

				//elimino lo que haya
				shell_exec('rm -r ' . $folder);		
				
				die(
						exit_status(array(
                			'result' => 'ERROR', 
                			'msg' => 'Max file size: 500 px x 500 px', 
		                	'params' => array(
		                             
		                                'file_name' => $pic_name,
		                				'path' => $new_path,
		                		
		                    
		        			)))
						);
				
			}else{
			
				$new_filename = date("YmdHis").".".get_extension($folder . $file);
				$salida = shell_exec('s3cmd -c /var/www/'.$bucket_config_file.' put ' . $folder . addslashes($file). ' s3://'.$cdn_string.'/tours/'.$tour_id.'/'.$case.'/'.$new_filename.' --recursive 2>&1');
				
				//elimino lo que haya
				shell_exec('rm -r ' . $folder);		
				
				$complete_path = $cdn."/tours/".$tour_id."/".$case."/".$new_filename;
			}
			
			break;			
			
			
		case 'skills':
		
			if(isset($_REQUEST["template_id"]) && $_REQUEST["template_id"] != ''){
				$skill_id = $_REQUEST["skill_id"];
				
				switch ($skill_id){
					case '4': //Signature
						
					//Restriccion. Debe tener menos de 500px de ancho por 500px de alto
					$image_size = getimagesize($folder . $file);
				
					if($image_size[0] > 500 || $image_size[1] > 100){
				
						//elimino lo que haya
						if($file != ''){
							shell_exec('rm -r ' . $folder.$file);
						}
				
						die(
								exit_status(array(
										'result' => 'ERROR',
										'msg' => 'Max file size: 500 px x 100 px',
										'params' => array(
				
												'file_name' => $pic_name,
												'path' => $new_path,
				
				
										)))
						);
				
					}else{
				
				
						$new_filename = date("YmdHis").".".get_extension($folder . $file);
				
						$salida = shell_exec('s3cmd -c /var/www/'.$bucket_config_file.' put ' . $folder . addslashes($file). ' s3://'.$cdn_string.'/skills/custom_signature/'.$new_filename.' --recursive 2>&1');
				
						//elimino lo que haya
						if($file != ''){
							shell_exec('rm -r ' . $folder.$file);
						}
				
						$complete_path = $cdn."/skills/custom_signature/".$new_filename;
				
						$ssqlp1="insert into signatures (user_id, filename) values ('".$_SESSION["usr"]."', '".$new_filename."')";
						mysql_query($ssqlp1);
						audit($ssqlp1, 'tour_thumb', $tour_id, mysql_affected_rows());
				
						$complete_path = $cdn."/skills/custom_signature/".$new_filename;
				
					}
					break;
				}				
				
			}else{
				die(
						exit_status(array(
								'result' => 'ERROR',
								'msg' => 'No skill id'))
				);
			}
			

			break;			
			
	default:

		$new_filename = date("YmdHis").".".get_extension($folder . $file);
		$salida = shell_exec('s3cmd -c /var/www/'.$bucket_config_file.' put ' . $folder . addslashes($file). ' s3://'.$cdn_string.'/tours/'.$tour_id.'/'.$case.'/'.$new_filename.' --recursive 2>&1');
		
		//elimino lo que haya
		shell_exec('rm -r ' . $folder);		
		
		$complete_path = $cdn."/tours/".$tour_id."/".$case."/".$new_filename;
		
			break;

	}
	
	return $complete_path;
	
}



if(isset($_REQUEST['tour_id']) && $_REQUEST['tour_id'] != '' && $_REQUEST['tour_id'] != 'undefined'){
	$tour_id = $_REQUEST['tour_id'];
}

if(isset($_REQUEST['caso']) && $_REQUEST['caso'] != '' && $_REQUEST['caso'] != 'undefined'){
	$case = $_REQUEST['caso'];
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


$structure = get_folder($case,$tour_id);

$structure_array = json_decode($structure);

foreach ($structure_array as $key => $value) {
	if($key=='folder'){$upload_dir = $value;}
	if($key=='path'){$path = $value;}
}



if (!is_dir($upload_dir))
{
	mkdir($upload_dir, 0777, true);
	chmod($upload_dir, 0777);
}

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
    
    
    if(!in_array($ext,$allowed_ext))
    {
        exit_status(array(
                'result' => 'ERROR', 
                'msg' => 'Only '.implode(',',$allowed_ext).' files are allowed!'
        ));   
    }	



    $pic_name = str_replace(')', '', str_replace('(', '', str_replace('%20', '_', $pic['name'])));
    

    if(move_uploaded_file($pic['tmp_name'], $upload_dir.'/'.$pic_name))    
    {
    	
    	$new_path = after_upload_actions($case, $tour_id, $path, $pic_name, $upload_dir);
        
        exit_status(array(
                'result' => 'SUCCESS', 
                'msg' => 'File was uploaded successfuly!', 
                'params' => array(
                             
                                'file_name' => $pic_name,
                				'path' => $new_path,
                		
                    
        )));
    }
	
}


?>
