<?php

require('Uploader.php');

define('THUMBNAIL_IMAGE_MAX_WIDTH', 900);
define('THUMBNAIL_IMAGE_MAX_HEIGHT', 900);

//print_r($_GET);
//print_r($_FILES);

//die();

$input_name = $_GET['input_name'];
$tour_id    = $_GET['tour_id'];

if (!$input_name || !$tour_id) exit;

$uploader = new FileUpload($input_name);        
$uploader->sizeLimit = 209715200;

$target_dir = '../tours/'.$tour_id.'/photos/';

if (!is_dir($target_dir)) 
{    
    //chmod('../tours/'.$tour_id, 0777);
    mkdir($target_dir, 0777, true);
    chmod($target_dir, 0777);
}    

$result = $uploader->handleUpload($target_dir, $allowedExtensions=array('jpg'));

if (!$result) 
{

  echo json_encode(array(

          'result' => 'ERROR',

          'msg' => $uploader->getErrorMsg()

       ));    

} 
else 
{
    $filepath = $target_dir.$uploader->getFileName();
    
    generate_image_thumbnail($filepath, $filepath, 0);

    echo json_encode(array(

            'result' => 'SUCCESS',

            'file' => $uploader->getFileName()

         ));

}  

?>
