<?php
ini_set('memory_limit', '-1');
require("../inc/auth.inc");
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



if(strtolower($_SERVER['REQUEST_METHOD']) != 'post')
{
    exit_status(array(
                    'result' => 'ERROR', 
                    'msg' => 'Error! Wrong HTTP method!'
    ));
}



if(isset($_POST['file_name']) && isset($_POST['pano_id']) && isset($_POST['scene_id']))    
{        
    $upload_dir = '../panos/';
    
    $file_name = $_POST['file_name'];
    $pano_id   = $_POST['pano_id'];
    $scene_id  = $_POST['scene_id'];

   // sleep(2);
    

    	/* Convertir imagen y Crear Thumbs 
    	 *   Using: http://www.imagemagick.org/script/convert.php
    	 */
    
    	if(!(is_dir($upload_dir . $pano_id . '/index.tiles/'))){
    		mkdir($upload_dir . $pano_id . '/index.tiles/', 0777);
    		chmod($upload_dir . $pano_id . '/index.tiles/', 0777);
    	};

    	
    	$salida = shell_exec('identify -format "%m|%w" '.$upload_dir . $pano_id.'/index.' . get_extension($file_name));
    	
    //	echo $salida;
    	
    	$properties = explode('|', $salida);
    	$format = strtolower($properties[0]);
    	$witdh = $properties[1];
    	
    	$max_witdh = 7000;
    	
    	if ($format != 'jpeg' && $format != 'jpg' || $witdh > $max_witdh){
    		//Convertir;
    		$new_witdh = '';
    		if ($witdh > $max_witdh){
    			$new_witdh = '-resize '.$max_witdh;
    		}
    		$str_exec = 'convert '.$new_witdh.' '.$upload_dir . $pano_id.'/index.' . get_extension($file_name).' '.$upload_dir . $pano_id.'/index.jpg';
    		shell_exec($str_exec);
    		if(is_file($upload_dir . $pano_id.'/index-0.jpg')){
    			rename($upload_dir . $pano_id.'/index-0.jpg', $upload_dir . $pano_id.'/index.jpg');
    		}
    	}
    	
    	shell_exec('convert -resize 900 '.$upload_dir . $pano_id.'/index.jpg '.$upload_dir.$pano_id.'/index.tiles/thumb900x450.jpg');
    	shell_exec('convert -resize 500 '.$upload_dir . $pano_id.'/index.jpg '.$upload_dir.$pano_id.'/index.tiles/thumb500x250.jpg');
    	shell_exec('convert -resize 232 '.$upload_dir . $pano_id.'/index.jpg '.$upload_dir.$pano_id.'/index.tiles/thumb200x100.jpg');
    	shell_exec('convert -resize 100 '.$upload_dir . $pano_id.'/index.jpg '.$upload_dir.$pano_id.'/index.tiles/thumb100x50.jpg');
    	
    	/*
    	$image = new SimpleImage();
    	
    	$image->load($upload_dir . $pano_id.'/index.' . get_extension($file_name));
    	
    	$image->resize(900,450);
    	$image->save($upload_dir . $pano_id . '/index.tiles/thumb900x450.jpg');
    	$image->resize(500,250);
    	$image->save($upload_dir . $pano_id . '/index.tiles/thumb500x250.jpg');
    	$image->resize(232,116);
    	$image->save($upload_dir . $pano_id . '/index.tiles/thumb200x100.jpg');
    	$image->resize(100,50);
    	$image->save($upload_dir . $pano_id . '/index.tiles/thumb100x50.jpg');

		*/

    	/*Corro KRPANO */
    	
        $salida = shell_exec('wine ../material/kmakemultires.exe ' . $upload_dir . $pano_id . '/index.'.get_extension($file_name).' -config=../material/templates/normal.config');


    /* Si todo fue bien, Cambio los estados a 1 */

    $ssqlp1 = "update panosxtour set state = 1 where id = ".$scene_id;
    mysql_query($ssqlp1);  

    $ssqlp1 = "update panos set state = 1 where id = ".$pano_id;
    mysql_query($ssqlp1);

    exit_status(array(
            'result' => 'SUCCESS', 
            'msg' => 'Pano was created successfuly!', 
            'params' => array(/*
                            'tour_id' => $tour_id,
                            'pano_id' => $elid,
                            'scene_id' => $scene_id,
                            'file_name' => $pic['name']*/
                            'thumb_path' => str_replace('../', '', $upload_dir) . $pano_id . '/index.tiles/thumb200x100.jpg'

    )));
}



exit_status(array(
                'result' => 'ERROR', 
                'msg' => 'Something went wrong with image processing!'
));


?>
