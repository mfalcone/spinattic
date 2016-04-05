<?
	require("../inc/conex.inc");


$lastID = $_GET["lastID"];
$action = $_GET["action"];

if ($action != 'getLastPosts') {
 
?>

<!DOCTYPE HTML>
<html>
<?require_once 'inc/head.inc';?>
<?$home = 1; ?>
	<body>
		
		<?require_once 'inc/nav.inc';
		$ssqlp = "SELECT * FROM tours where state = 'publish' and privacy = '_public' ORDER BY RAND() LIMIT 1";
		$result = mysql_query($ssqlp);	
		$row = mysql_fetch_array($result);
		
		$ssqlp_usr = "SELECT * FROM users where id = '".$row["iduser"]."'";
		$result_usr = mysql_query($ssqlp_usr);
		$row_usr = mysql_fetch_array($result_usr);		
		
		$ssqlthumb = "SELECT * FROM panosxtour where idtour = ".$row["id"]." ORDER BY ord LIMIT 1";
		$resultthumb = mysql_query($ssqlthumb);
		$rowthumb = mysql_fetch_array($resultthumb);		
		
		?>
		
		
		<div class="wrapper wrapper-home">
		
				<!--post destacado-->
				<div class="post-highlight">
					<a href="../tours/<?echo $row["id"];?>" class="thumb" target="_blank">
						   <div class="play"></div>
						   <img src="<?php echo $cdn;?>/panos/<?php echo $rowthumb["idpano"];?>/pano.tiles/thumb500x250.jpg">
					</a>
					<a href="#" class="user"><img src="../images/users/<?echo $row_usr["avatar"];?>" width="43" height="43" ></a>
					<div class="by"><a href="#">by <?echo $row["user"];?></a></div>
					<a href="#" class="text">
						<p><?echo $row["title"];?></p>
					</a>
					<div class="count">
						<div class="views"><?php echo $row["views"];?></div>
						
						<?php if($row["allow_votes"] == 'on'){?>
							<a href="javascript:void(0)" id="like<?php echo $row["id"];?>" class="likes"><?php echo $row["likes"];?></a>
						<?php }?>
					
						<?php if($row["allow_comments"] == 'on'){?>
							<a href="javascript:void(0)"  class="comments"><?php echo $row["comments"];?></a>
						<?php }?>	
					
						<br clear="all">
						
					</div>
					
					<!--comentarios-->
					<?php 
					//$ssqlc = "SELECT * FROM comments where idtour = ".$row["id"]." ORDER BY date DESC";
					$ssqlc = "SELECT comments.comments, comments.id, comments.iduser, DATE_FORMAT(comments.date,'%d/%m/%Y') as fecha, users.avatar, users.username FROM comments, users where comments.idtour = ".$row["id"]." and comments.iduser = users.id order by comments.date desc LIMIT 3";
					$resultc = mysql_query($ssqlc);

					while ($rowc = mysql_fetch_array($resultc)){?>
												
												
					<div class="comments-text">
						<a href="#" class="user"><img src="../images/users/<?echo $rowc["avatar"];?>" width="43" height="43"></a>
						<p><a href="#"><strong><?echo $rowc["username"];?>:</strong></a> <?echo $rowc["comments"];?></p>
						<br clear="all">
					</div>

					<?php }?>
			

				</div>
				
				

			<?php 
			$first_show = 10;
			require 'php-stubs/get_first_tours.php';
			echo $postText;?>
			
						<br clear="all">
					</div>
				</div>
		</div>
	</body>
</html>


<?php }else{
	$last_show = 5;
	require 'php-stubs/get_last_tours.php';
}
?>   