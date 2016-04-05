<?
$restrict = 2;
require("inc/header.php");

$i = $_GET["i"];

?>
        <link rel="stylesheet" type="text/css" href="css/tipsy.css" />	
        <script type="text/javascript" src="js/jquery.tipsy.js"></script>                
        <script src="js/core-actions.js"></script>	
		<script src="js/core-utils.js"></script>	  

<?php 
$conerror = 0;

$folder="images/posts/";


if($i != ''){
	//Delete
	unlink($folder.$i);
}else{
	//Insert
	if ($_FILES['file']['name'] != ""){
		
		move_uploaded_file($_FILES['file']['tmp_name'], $folder.$_FILES['file']['name']);
		
		/*
		error_reporting(E_ALL);
		include_once 'php-stubs/class.upload.php';
	
	
		$crop1="C";
		$croparray=array($crop1);
	
	
		$file = $_FILES["file"];
		$nombredelarchivo = "";
	
		$j=0;
		// now we feed each element to the class
		$crop=$croparray[$j];
		$j++;
		// we instanciate the class for the element of $file
		$handle = new Upload($file);
	
		// then we check if the file has been uploaded properly
		// in its *temporary* location in the server (often, it is /tmp)
		if ($handle->uploaded) {
	
	
			$nombredelarchivo = $handle->file_src_name_body;
	
	
			$handle->file_new_name_body	= $nombredelarchivo;
			$handle->file_overwrite 	= true;
			$handle->image_resize         = true;
			$handle->image_x              = 1000;
			$handle->image_ratio_y        = true;
	
	
			// now, we start the upload 'process'. That is, to copy the uploaded file
			// from its temporary location to the wanted location
			// It could be something like $handle->Process('/home/www/my_uploads/');
			$handle->Process($folder);
			// we check if everything went OK
			if ($handle->processed) {
	
				$conerror = 2;
	
			} else {
	
				$conerror = 1;
				// one error occured
	
			}
	
		}
		*/
	}	
	
}




?>
		
        <div class="wrapper wrapper-terms wrapper-edit-post">
        
			<h1 class="blog">Manage Images</h1>

			<span id="mensajes">
				<?php
				if($conerror == 1){
					echo '<div class="message_box error_m"><p>Invalid image file. Please try again</p></div>';
				}
				
				if($conerror == 2){
					echo '<div class="message_box good_m"><p>Success !</p></div>';
				}
				?>
			</span>
						
			
			<form action="edit_images.php" method="post" enctype="multipart/form-data" name="form1" id="form1">
		
        	<label><p>Upload New Image</p></label>
 			
 			<input id="browseFile" class="browseFile" name="file" type="file" value=""/>
			
			<input type="submit" value="UPLOAD">
        	
        	</form>
			<table border="0"  style="margin-top:150px">
				<tr><th>Delete</th><th style="width:250px">Image</th><th style="width:400px">URL</th></tr>
				<?php
				$gestor = opendir($folder);
				$count = 0;
				$retval = array();
				while ($elemento = readdir($gestor))
				{
					if($elemento != "." && $elemento != ".."){
						echo '<tr><td align="center"><a href="edit_images.php?i='.$elemento.'"><img src="images/icons/delete_pano.png" alt="Delete Image"></a></td><td><img src="'.$folder.'/'.$elemento.'" style="max-height:250px; max-width:250px;"></td><td>'.'http://'.$_SERVER[HTTP_HOST].'/images/posts/'.$elemento.'</td></tr>';
					}
				}?> 			
 			</table> 
        </div>
        
	</body>
</html>
