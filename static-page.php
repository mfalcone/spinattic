<?
$id = $_GET["id"];
if($id == ''){
	header('Location:index.php');
}

		require_once ("inc/conex.inc");
		
	   	$ssqlp = "SELECT *, DATE_FORMAT(date,'%b %d %Y %h:%i %p') as fecha FROM static_pages where id = ".$id;
	   	//echo $ssqlp;
	   	$result = mysql_query($ssqlp);
	   	$row = mysql_fetch_array($result);
	   	
	   	//$post_image = $row["image"];
	   	$post_title = $row["title"];
	   	
		$text = $row["text"];
		
	   	$meta_type='static-page';
	   	$page_title = "Spinattic | ".$post_title;
	   	
	   	
	   	require("inc/header.php");	   	
	   	
?>

        
        <div class="wrapper wrapper-blog wrapper-static-pages">
			<article>
				<header>
					<hgroup>
						<h1><a href=""><?php echo $post_title;?></a></h1>
					</hgroup>
				</header>
				<?php 
				echo $text;?>
 
				<footer>
				</footer>
			</article>
		
		</div>
	<?php require_once("inc/footer.php");?>
</html>
