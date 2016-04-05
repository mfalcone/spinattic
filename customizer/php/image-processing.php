<?php
ini_set('memory_limit', '-1');

/*
require("../inc/auth.inc");
require("../inc/conex.inc");
*/

include('../php/simpleimage.php');

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



if(strtolower($_SERVER['REQUEST_METHOD']) != 'post')
{
    exit_status(array(
                    'result' => 'ERROR', 
                    'msg' => 'Error! Wrong HTTP method!'
    ));
}

$upload_dir = '../panos/';

$file_name = $_POST['file_name'];
$pano_id   = $_POST['pano_id'];
$scene_id  = $_POST['scene_id'];

if($_GET["step"] == 1){
	

	if(isset($_POST['file_name']) && isset($_POST['pano_id']) && isset($_POST['scene_id']))    
	{        

	
	   // sleep(2);
    

    	/* Convertir imagen y Crear Thumbs 
    	 *   Using: http://www.imagemagick.org/script/convert.php
    	 */
    
    	if(!(is_dir($upload_dir . $pano_id . '/pano.tiles/'))){
    		mkdir($upload_dir . $pano_id . '/pano.tiles/', 0777);
    		chmod($upload_dir . $pano_id . '/pano.tiles/', 0777);
    	};

    	
    	$salida = shell_exec('identify -format "%m|%w" '.$upload_dir . $pano_id.'/pano.' . get_extension($file_name));
    	
    		echo $salida;
    	
    	$properties = explode('|', $salida);
    	$format = strtolower($properties[0]);
    	$witdh = $properties[1];
    	
    	//$max_witdh = 7000;   														  no limitamos mas a 7000
    	
    	//if ($format != 'jpeg' && $format != 'jpg' || $witdh > $max_witdh){          no limitamos mas a 7000
    	if ($format != 'jpg'){
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
    	
    	shell_exec('convert -resize 900 '.$upload_dir . $pano_id.'/pano.jpg '.$upload_dir.$pano_id.'/pano.tiles/thumb900x450.jpg');
    	shell_exec('convert -resize 500 '.$upload_dir . $pano_id.'/pano.jpg '.$upload_dir.$pano_id.'/pano.tiles/thumb500x250.jpg');
    	shell_exec('convert -resize 232 '.$upload_dir . $pano_id.'/pano.jpg '.$upload_dir.$pano_id.'/pano.tiles/thumb200x100.jpg');
    	shell_exec('convert -resize 100 '.$upload_dir . $pano_id.'/pano.jpg '.$upload_dir.$pano_id.'/pano.tiles/thumb100x50.jpg');

    	//Count number of files
    	$num_of_files = 0;
    	$dir = $upload_dir.$pano_id.'/pano.tiles/';
    	if ($handle = opendir($dir)) {
    		while (($file = readdir($handle)) !== false){
    			if (!in_array($file, array('.', '..')) && !is_dir($dir.$file))
    				$num_of_files++;
    		}
    	}
    	
    	exit_status(array(
    			'result' => 'SUCCESS',
    			'msg' => $salida,
    			'num_of_files' => $num_of_files,
    			'params' => array(
    			'thumb_path' => str_replace('../', '', $upload_dir) . $pano_id . '/pano.tiles/thumb200x100.jpg'
    					)
    			//'thumb_path' => $cdn. str_replace('..', '', $upload_dir) . $pano_id . '/pano.tiles/thumb200x100.jpg')
    	));    	
    	
	}

}else{
	/*Corro KRPANO */
	
	//(fuerzo el nombre del archivo a pano.jpg)
	
	
	$salida = shell_exec('../../material/krpanotools makepano -config=../../material/templates/normal.config ' . $upload_dir . $pano_id . '/pano.jpg');
	
	//$salida = shell_exec('s3cmd -c /var/www/'.$bucket_config_file.' put ' . $upload_dir . $pano_id . ' s3://'.$cdn_string.'/panos/ --recursive 2>&1');
	
	/* Si todo fue bien, Cambio los estados a 1 */
	/*
	$ssqlp1 = "update panosxtour set state = 1 where id = ".$scene_id;
	mysql_query($ssqlp1);
	
	$ssqlp1 = "update panos set state = 1 where id = ".$pano_id;
	mysql_query($ssqlp1);
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
	
	//$salida = shell_exec('rm -r ' . $upload_dir . $pano_id);
	
	exit_status(array(
			'result' => $salida,
			'msg' => $salida,
			'num_of_files' => $num_of_files,
			'params' => array(
			'thumb_path' => str_replace('../', '', $upload_dir) . $pano_id . '/pano.tiles/thumb200x100.jpg')
			//'thumb_path' => $cdn. str_replace('..', '', $upload_dir) . $pano_id . '/pano.tiles/thumb200x100.jpg')
			));
};



exit_status(array(
                'result' => 'ERROR', 
                'msg' => 'Something went wrong with image processing!'
));


?>
