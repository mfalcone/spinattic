<?
$lastID = $_GET["lastID"];
$action = $_GET["action"];

$filter = '';

require_once 'inc/conex.inc';
require_once 'inc/auth.inc';

if($_GET["c"]!= ''){
	$filter = " and blog.category = ".$_GET["c"]; 
	$ssqlp_cat = "SELECT * FROM categories_blog where id = ".$_GET["c"];
	$result_cat = mysql_query($ssqlp_cat);
	$row_cat = mysql_fetch_array($result_cat);
	$cat_desc = ' - '.$row_cat["category"]; 	
}

if ($action != 'getLastPosts') {
	
$page_title = "Spinattic | Blog";
require("inc/header.php");

	   	
	   	
?>
	<script src="js/ajaxscrollhome.js"></script>
        <div class="wrapper wrapper-blog">
        	<h1 class="blog">Blog<?php echo $cat_desc;?></h1>
<?php 
		$ssqlp = "SELECT blog.privacy, blog.id, blog.title, blog.text, categories_blog.category,categories_blog.id as category_id, DATE_FORMAT(blog.date,'%b %d %Y %h:%i %p') as fecha FROM blog, categories_blog where blog.category = categories_blog.id ".$filter." ORDER BY date desc";
		$result = mysql_query($ssqlp);
		$i=0;
		$cant_reg=10;
		while($row = mysql_fetch_array($result)){    
			$i++;
			
			if ($i > $cant_reg){
				break;
			}

			if($row["privacy"] == 'Private' && $logged == 1 || $row["privacy"] == 'Public'){
			?>    	
        	
			<article class="item notification" id="<?php echo $i?>">
				<header>
					<hgroup>
						<h1><a href="blog-single-post.php?id=<?php echo $row["id"];?>"><?php echo $row["title"]?></a></h1>
						<h2>by Spinattic</h2>
						<h2><a href="blog.php?c=<?php echo $row["category_id"];?>"><?php echo $row["category"];?></a></h2>
					</hgroup>
					<time><?php echo $row["fecha"]?></time>
				</header>
				<?php 
				echo $row["text"];?>

				
				<a href="blog-single-post.php?id=<?php echo $row["id"];?>" class="read-more">Read More</a>
		<?php 		
		$ssqlp_comments = "SELECT * FROM blog_comments where idpost = ".$row["id"];
		$result_comments = mysql_query($ssqlp_comments);
		$cant_comments = mysql_num_rows($result_comments);
		?>

				<footer>
					<!--ul class="tags-counter">
						<li><a href="#">tag 1</a></li>
						<li><a href="#">tag 2</a></li>
					</ul-->
					<a href="blog-single-post.php?id=<?php echo $row["id"];?>" class="comments-count">comments(<?php echo $cant_comments;?>)</a>
				</footer>					
			</article>				
		
<?php }}
	if ($i < $cant_reg){
		echo '<input type="hidden" class="nomore">';
	}?>

				
			<!-- footer class="page-counter">
				<ul>
					<li class="selected"><a href="#">1</a></li>
					<li><a href="#">2</a></li>
					<li><a href="#">3</a></li>
					<li><a href="#">4</a></li>
				</ul>
			</footer-->
        </div>
    <?php require_once("inc/footer.php");?>
</html>

<?
}else{
	require_once("inc/conex.inc");
	require_once("inc/functions.inc");
	require_once("inc/auth.inc");
		
	$cant_reg = 5;
	$getPostsText = "";
	session_start();
	
	$ssqlp = "SELECT blog.privacy, blog.id, blog.title, blog.text, categories_blog.category,categories_blog.id as category_id, DATE_FORMAT(blog.date,'%b %d %Y %h:%i %p') as fecha FROM blog, categories_blog where blog.category = categories_blog.id ".$filter." ORDER BY date desc";
	$result = mysql_query($ssqlp);
	
	while ($row = mysql_fetch_array($result)){
		$i++;
		if ($i >= $lastID){
			break;
		};
	}
	
	while($row = mysql_fetch_array($result)){
		if ($j >= $cant_reg){
			break;
		};
				
		$i++;
		$j++;?>
		
		<article class="item notification" id="<?php echo $i?>">
		<header>
		<hgroup>
		<h1><a href="blog-single-post.php"><?php echo $row["title"]?></a></h1>
		<h2>by Spinattic</h2>
		<h2><a href="blog.php?c=<?php echo $row["category_id"];?>"><?php echo $row["category"];?></a></h2>
		</hgroup>
		<time ><?php echo $row["fecha"]?></time>
		</header>
		<?php 
		echo $row["text"];?>
		
		
		<a href="blog-single-post.php?id=<?php echo $row["id"];?>" class="read-more">Read More</a>
		
		<?php 		
		$ssqlp_comments = "SELECT * FROM blog_comments where idpost = ".$row["id"];
		$result_comments = mysql_query($ssqlp_comments);
		$cant_comments = mysql_num_rows($result_comments);
		?>
			
				<footer>
					<!--ul class="tags-counter">
						<li><a href="#">tag 1</a></li>
						<li><a href="#">tag 2</a></li>
					</ul-->
					<a href="blog-single-post.php?id=<?php echo $row["id"];?>" class="comments-count">comments(<?php echo $cant_comments;?>)</a>
				</footer>			
		
		</article>
	
		<?php 
		};
		if ($j <$cant_reg){
			echo '<input type="hidden" class="nomore">';
		}
}?>	