<?
$id = $_GET["id"];
if($id == ''){
	header('Location:index.php');
}

		require_once ("inc/conex.inc");
		require_once ("inc/auth.inc");
		
	   	$ssqlp = "SELECT *, DATE_FORMAT(date,'%b %d %Y %h:%i %p') as fecha FROM blog where id = ".$id;
   	
	   	$result = mysql_query($ssqlp);
	   	$row = mysql_fetch_array($result);

	   	if($logged != 1 && $row["privacy"] == 'Private'){
	   		header('Location:blog.php');
	   	}
	   	
	   	
	   	
	   	$post_title = $row["title"];
	   	
		$text = $row["text"];
		
	   	$meta_type='blog';
	   	$page_title = "Spinattic | Blog";
	   	
	   	
	   	require("inc/header.php");	   	
	   	
?>

        
        <div class="wrapper wrapper-blog">
        	<h1 class="blog">Blog</h1>
			<article>
				<header>
					<hgroup>
						<h1><a href=""><?php echo $post_title;?></a></h1>
						<h2>by Spinattic</h2>
					</hgroup>
					<time datetime="2011-11-02" pubdate=""><?php echo $row["fecha"]?></time>
				</header>

				<?php 
				echo $text;?>
 
				<footer>
				<a href="blog.php" target="_self" >&#171; back to blog</a>
<!--
				<ul class="tags-counter">
						<li><a href="#">tag 1</a></li>
						<li><a href="#">tag 2</a></li>
						<li><a href="#">tag 3</a></li>
					</ul>
					<a href="#" class="comments-count">comments(4)</a>
-->
				
				</footer>
			<!--COMMENTS-->
			<?php require 'php-stubs/get_comments_blog.php';?>
			<!-- fin de get_comments.php-->
				
			</article>
		
		</div>
		<input type="hidden" id="id" name="id" value="<?php echo $id;?>">
	<?php require_once("inc/footer.php");?>
</html>
