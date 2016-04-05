<?
$id = $_GET["id"];
if($id == ''){
	header('Location:index.php');
}

		require_once ("../inc/conex.inc");
		
	   	$ssqlp = "SELECT *, DATE_FORMAT(date,'%b %d %Y %h:%i %p') as fecha FROM blog where id = ".$id;
	   	//echo $ssqlp;
	   	$result = mysql_query($ssqlp);
	   	$row = mysql_fetch_array($result);
	   	
	   	$post_image = $row["image"];
	   	$post_title = $row["title"];
	   	
	   	$meta_type='blog';
	   	$page_title = "Spinattic | Blog";
	   	
	   	
   	
	   	
?>
<!DOCTYPE HTML>
<html>
 <?require_once 'inc/head.inc';?>
        
		<h1 class="blog">Blog</h1>
		<?require_once 'inc/nav.inc';?>
		<div class="wrapper blog">

			<article>
				<header>
					<hgroup>
						<h1><a href=""><?php echo $post_title;?></a></h1>
						<h2>by Spinattic</h2>
					</hgroup>
					<time datetime="2011-11-02" pubdate=""><?php echo $row["fecha"]?></time>
				</header>
				<?php if($post_image != ''){?>
				<img src="images/blog/<?php echo $post_image;?>" alt="alt text dummy">
				<?php }
				echo $row["text"];?>
 
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
