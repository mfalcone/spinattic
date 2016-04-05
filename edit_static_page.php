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
	
	$id = $_POST["page_id"];
	$body = $_POST["article-body"];
	$title = $_POST["title"];
	$privacy = $_POST["privacy"];
	
	
	// Insercion en BD
	
	if($conerror != 1){
		if($id == ''){
			$action = "published";
			$ssqlp = "insert into static_pages (title, privacy, text, date) values ('".mysql_real_escape_string($title)."','".mysql_real_escape_string($privacy)."','".mysql_real_escape_string($body)."',now())";
			mysql_query($ssqlp);
			$ssqlp = "SELECT * FROM static_pages order by id desc LIMIT 1";
			$result = mysql_query($ssqlp);
			$row = mysql_fetch_array($result);
			$body = $row["text"];
			$id = $row["id"];
	
		}else{
			
			$action = "updated";
			$ssqlp = "update static_pages set title = '".mysql_real_escape_string($_POST["title"])."', privacy = '".mysql_real_escape_string($_POST["privacy"])."', text = '".mysql_real_escape_string($body)."', date = now() where id = '".$id."'";

			mysql_query($ssqlp);
			$ssqlp = "SELECT * FROM static_pages where id = ".$id;
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
	
	$ssqlp = "SELECT * FROM static_pages where id = ".$id;
	$result = mysql_query($ssqlp);
	$row = mysql_fetch_array($result);
	$title = $row["title"];
	$body = $row["text"];
	$privacy = $row["privacy"];
}




?>
		
        <div class="wrapper wrapper-terms wrapper-edit-post">
        
			<h1 class="blog">Static Pages - edit page</h1>

			<span id="mensajes">
				<?php
				if($result == 'nok'){
					echo '<div class="message_box error_m"><p>Error. Please try again</p></div>';
				}
				
				if($result == 'ok'){
					echo '<div class="message_box good_m"><p>The page was '.$action.'</p></div>';
				}
				?>
			</span>
						
			
			<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
				<input type="hidden" id="page_id" name="page_id" value="<?php echo $id;?>">
				<label><p>Privacy</p></label>
        		<select class="border-radius-4 form-user" name="privacy" id="privacy">
        			<option value="public" <?php if ($privacy == "public"){echo selected;}?>>Public</option>
        			<option value="private" <?php if ($privacy == "private"){echo selected;}?>>Private</option>
        		</select>
				<label><p>Page Title</p></label><br>
	        	<input class="title-post border-radius-4 form-user" name="title" id="title" value="<?php echo $title;?>">

	 			<label><p>Page Body</p></label><br>
	         	<textarea name="article-body" id="article-body" cols="30" rows="10"><?php echo $body;?></textarea>
	         	
	        	<hr class="separator">
	        	<?php if($id != ''){?>
	        		<a href="#" class="red-button border-radius-4 remove-static-page" style="float:left;font-size: 14px;">DELETE</a>
	        		<a href="#" class="blue-buttom border-radius-4 float-right udpagedata" target="_blank">UPDATE</a>
	        	<?php }else{?>
	        		<a href="#" class="blue-buttom border-radius-4 float-right udpagedata" target="_blank">CREATE</a>
	        	<?php }?>
        	</form>
        </div>
	</body>
</html>
	<script type="text/javascript">
		//CKEDITOR.inline( 'article-body' );
		CKEDITOR.replace( 'article-body' );
	</script>
