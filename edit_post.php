<?
$restrict = 2;
require("inc/header.php");

$id = $_GET["id"];

?>
  		<script type="text/javascript" src="js/ckeditor.js"></script>
		<style>

		/* Style the CKEditor element to look like a textfield */
		.cke_textarea_inline
		{
			padding: 10px;
			height: 300px;
			overflow: auto;

			border: 1px solid #DDD;
		}

	</style>
        
        <link rel="stylesheet" type="text/css" href="css/tipsy.css" />	
        <script type="text/javascript" src="js/jquery.tipsy.js"></script>                
        <script src="js/core-actions.js"></script>	
		<script src="js/core-utils.js"></script>	  

<?php 
$conerror = 0;

if (isset($_POST["title"]) && $_POST["title"] != ''){
	
	$id = $_POST["post_id"];
	$body = $_POST["article-body"];
	$title = $_POST["title"];
	$category = $_POST["cat"];
	$privacy = $_POST["privacy"];
	$send_mail = $_POST["send_mail"];
	
	if($send_mail == 'y'){
		$send_mail = 1;
	}else{
		$send_mail = 0;
	}
	
	
	// Insercion en BD
	
	if($conerror != 1){
		if($id == ''){
			$action = "published";
			$ssqlp = "insert into blog (title, text, date, category, privacy, send_mail) values ('".mysql_real_escape_string($title)."','".mysql_real_escape_string($body)."',now(),'".$category."','".$privacy."','".$send_mail."')";
			mysql_query($ssqlp);
			$ssqlp = "SELECT * FROM blog order by id desc LIMIT 1";
			$result = mysql_query($ssqlp);
			$row = mysql_fetch_array($result);
			$body = $row["text"];
			$id = $row["id"];
	
		}else{
			
			$action = "updated";	
			
			$ssqlp = "update blog set send_mail = '".$send_mail."', privacy = '".$_POST["privacy"]."', category = '".$_POST["cat"]."', title = '".mysql_real_escape_string($_POST["title"])."', text = '".mysql_real_escape_string($body)."', date = now() where id = '".$id."'";
			
			
			mysql_query($ssqlp);
			$ssqlp = "SELECT * FROM blog where id = ".$id;
			$result = mysql_query($ssqlp);
			$row = mysql_fetch_array($result);
			$title = $row["title"];
			$body = $row["text"];
	
		}
	
		$result = 'ok';
	
	}else{
	
		$result = 'nok';
	
	}	
	
}else{
	$result = 'ok';
	
	$ssqlp = "SELECT * FROM blog where id = ".$id;
	$result = mysql_query($ssqlp);
	$row = mysql_fetch_array($result);
	$title = $row["title"];
	$body = $row["text"];
	$category = $row["category"];
	$privacy = $row["privacy"];
	$send_mail = $row["send_mail"];
}


//Si insertò, lanzo notificaciones:
if($result == 'ok'){
	echo '<script>jQuery(document).ready(function(){notificate("'.$id.'",4);})</script>';
}


?>
		
        <div class="wrapper wrapper-terms wrapper-edit-post">
        
			<h1 class="blog">Blog - edit Post</h1>

			<span id="mensajes">
				<?php
				if($result == 'nok'){
					echo '<div class="message_box error_m"><p>Error. Please try again</p></div>';
				}
				
				if($result == 'ok'){
					echo '<div class="message_box good_m"><p>The post was '.$action.'</p></div>';
				}
				?>
			</span>
						
			
			<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
			<input type="hidden" id="post_id" name="post_id" value="<?php echo $id;?>">
			<label><p>Category</p></label>
        	<select class="border-radius-4 form-user" name="cat" id="cat">
        	<option value="">Choose Category</option>
			<?		$ssqlp_cat = "SELECT * FROM categories_blog ORDER BY category";
					$result_cat = mysql_query($ssqlp_cat);	
					
					while($row_cat = mysql_fetch_array($result_cat)){?>
			                            
			                            <option value="<?echo $row_cat["id"];?>" <?if($category==$row_cat["id"])echo 'selected';?>><?echo $row_cat["category"];?></option>
			                            
			<?}?>        	
        	</select>		
			<label><p>Privacy</p></label>
        	<select class="border-radius-4 form-user" name="privacy" id="privacy">
        	<option value="Public" <?if($privacy == 'Public')echo 'selected';?>>Public</option>
        	<option value="Private" <?if($privacy == 'Private')echo 'selected';?>>Private</option>
        	</select>	        		
			<label><p>Post Title</p></label><br>
        	<input class="title-post border-radius-4 form-user" name="title" id="title" value="<?php echo $title;?>">
 			<label><p>Post Body</p></label><br>
         	<textarea name="article-body" id="article-body" cols="30" rows="10"><?php echo $body;?></textarea>
         	
        	<hr class="separator">
        	
        	<label for="send_mail">email to users
        	<input type="checkbox" value="y" name="send_mail" id="send_mail">
        	</label>
        	<br><br>
        	<?php if($id != ''){?>
        	<a href="#" class="red-button border-radius-4 remove-blog-post" style="float:left;font-size: 14px;">DELETE</a>
        	<?php }?>

        	<a href="#" class="blue-buttom border-radius-4 float-right udblogdata" target="_blank">POST</a>
        	
        	</form>
        </div>
	</body>
</html>
	<script type="text/javascript">
		//CKEDITOR.inline( 'article-body' );
		CKEDITOR.replace( 'article-body' );

	</script>
