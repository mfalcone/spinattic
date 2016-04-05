<?php
ini_set('memory_limit', '-1');
ini_set("display_errors", 1);
//require("../inc/auth.inc");
require("../inc/conex.inc");
include('../php-stubs/simpleimage.php');

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



$upload_dir = '../panos/';
$pano_id = $_GET["pano"];

if($_GET["s"] == 1){
	$salida = shell_exec('s3cmd -c /var/www/'.$bucket_config_file.' put ' . $upload_dir . $pano_id . ' s3://'.$cdn_string.'/panos/ --recursive 2>&1');
	echo $salida;
}else{


    	if(!(is_dir($upload_dir . $pano_id . '/pano.tiles/'))){
    		mkdir($upload_dir . $pano_id . '/pano.tiles/', 0777);
    		chmod($upload_dir . $pano_id . '/pano.tiles/', 0777);
    	};
    	
		//Si es JPEG, lo renombro a jpg
		if(is_file ( $upload_dir . $pano_id.'/pano.jpeg' )){
			rename ( $upload_dir . $pano_id.'/pano.jpeg', $upload_dir . $pano_id.'/pano.jpg' );
		}
		
    	$salida = shell_exec('identify -format "%m|%w" '.$upload_dir . $pano_id.'/pano.jpg');
    	
    	//echo $salida;
    	
    	$properties = explode('|', $salida);
    	$format = strtolower($properties[0]);
    	$witdh = $properties[1];
    	
    	//$max_witdh = 7000;   														  no limitamos mas a 7000
    	
    	//if ($format != 'jpeg' && $format != 'jpg' || $witdh > $max_witdh){          no limitamos mas a 7000
    	if ($format != 'jpg' && $format != 'JPEG'){
    		//Convertir;
    		/*                                                                        no limitamos mas a 7000
    		$new_witdh = '';
    		if ($witdh > $max_witdh){
    			$new_witdh = '-resize '.$max_witdh;
    		}
    		*/
    		//$str_exec = 'convert '.$new_witdh.' '.$upload_dir . $pano_id.'/pano.' . get_extension($file_name).' '.$upload_dir . $pano_id.'/pano.jpg';
    		
    		$str_exec = 'convert '.$upload_dir . $pano_id.'/pano.jpg '.$upload_dir . $pano_id.'/pano.jpg';
    		
    		shell_exec($str_exec);
    		if(is_file($upload_dir . $pano_id.'/pano-0.jpg')){
    			rename($upload_dir . $pano_id.'/pano-0.jpg', $upload_dir . $pano_id.'/pano.jpg');
    		}
    	}
    	
    	shell_exec('convert -resize 900 '.$upload_dir . $pano_id.'/pano.jpg '.$upload_dir.$pano_id.'/pano.tiles/thumb900x450.jpg');
    	shell_exec('convert -resize 500 '.$upload_dir . $pano_id.'/pano.jpg '.$upload_dir.$pano_id.'/pano.tiles/thumb500x250.jpg');
    	shell_exec('convert -resize 232 '.$upload_dir . $pano_id.'/pano.jpg '.$upload_dir.$pano_id.'/pano.tiles/thumb200x100.jpg');
    	shell_exec('convert -resize 100 '.$upload_dir . $pano_id.'/pano.jpg '.$upload_dir.$pano_id.'/pano.tiles/thumb100x50.jpg');


	/*Corro KRPANO */
	
	//(fuerzo el nombre del archivo a pano.jpg)
	
	
	$salida = shell_exec('../material/krpanotools makepano -config=../material/templates/normal.config ' . $upload_dir . $pano_id . '/pano.jpg');
	
	echo $salida.'<br><br><br>';
	
	$salida = shell_exec('s3cmd -c /var/www/'.$bucket_config_file.' put ' . $upload_dir . $pano_id . ' s3://'.$cdn_string.'/panos/ --recursive 2>&1');
	
	echo $salida.'<br><br><br>';
	
	/* Si todo fue bien, Cambio los estados a 1 */
	
}
	
	echo "OK !";

	
	
	
?>
