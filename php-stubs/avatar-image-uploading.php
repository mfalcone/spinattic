<?php
session_start();

define('THUMBNAIL_IMAGE_MAX_WIDTH', 107);
define('THUMBNAIL_IMAGE_MAX_HEIGHT', 107);

require("../inc/conex.inc");
require('Uploader.php');
require('gen_random.php');
include('simpleimage.php');


$uploader = new FileUpload('uploadfile');        
$uploader->sizeLimit = 209715200;

$target_dir = '../images/users/';

if (!is_dir($target_dir)) 
{    
    mkdir($target_dir, 0777, true);
    chmod($target_dir, 0777);
}    

if ($_SESSION["avatar"] != "avatar.jpg" && is_file($target_dir.$_SESSION["avatar"]))
{
	unlink($target_dir.$_SESSION["avatar"]);
}

$newname = random_string(15).".jpg";

$uploader->newFileName = $newname;

$result = $uploader->handleUpload($target_dir);


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
    
    //generate_image_thumbnail($filepath, $filepath);
    //generate_image_thumbnail($target_dir.$newname, $target_dir.$newname, 0);
    
    crop_image($target_dir.$newname, $target_dir.$newname, THUMBNAIL_IMAGE_MAX_WIDTH, THUMBNAIL_IMAGE_MAX_HEIGHT);
    
    $_SESSION["avatar"] = $newname;
    
    $ssqlp1 = "update users set avatar = '".$_SESSION["avatar"]."' where id = '".$_SESSION["usr"]."'";
    mysql_query($ssqlp1);    
    
    
    
    echo json_encode(array(

            'result' => 'SUCCESS',

            'file' => $uploader->getFileName(),
    		
    		'msg' => $target_dir.$_SESSION["avatar"]

         ));

}  

?>
