<?php
session_start();


//define('THUMBNAIL_IMAGE_MAX_WIDTH', 700);
define('THUMBNAIL_IMAGE_MAX_HEIGHT', 250);

require("../inc/conex.inc");
require('Uploader.php');
require('gen_random.php');

$uploader = new FileUpload('uploadfile');        
//$uploader->sizeLimit = 209715200;

$target_dir = '../images/users/cover/';

if (!is_dir($target_dir)) 
{    
    mkdir($target_dir, 0777, true);
    chmod($target_dir, 0777);
}    

if ($_SESSION["cover"] != "profile.jpg" && is_file($target_dir.$_SESSION["cover"]))
{
	unlink($target_dir.$_SESSION["cover"]);
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
    generate_image_thumbnail($target_dir.$newname, $target_dir.$newname, 1);
    
    $_SESSION["cover"] = $newname;
    
    $ssqlp1 = "update users set cover = '".$_SESSION["cover"]."' where id = '".$_SESSION["usr"]."'";
    mysql_query($ssqlp1);    

    echo json_encode(array(

            'result' => 'SUCCESS',

            'file' => $uploader->getFileName(),
    		
    		'msg' => $target_dir.$newname

         ));

}  

?>
