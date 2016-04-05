<?

$lastID = $_GET["lastID"];
$action = $_GET["action"];

if ($action != 'getLastPosts') {
 
require("inc/header.php");

?>
                
<?
		$ssqlp = "SELECT * FROM tours where state = 'publish' and privacy = '_public' ORDER BY RAND() LIMIT 1";
		$result = mysql_query($ssqlp);	
		$row = mysql_fetch_array($result);

		$ssqlp_usr = "SELECT * FROM users where id = '".$row["iduser"]."'";
		$result_usr = mysql_query($ssqlp_usr);
		$row_usr = mysql_fetch_array($result_usr);		
?>
  		<script src="js/ajaxscroll.js"></script>
      
        <div class="wrapper wrapper-home">
	        	<div class="post-highlight">
                	<div id="pano" class="thumb">
						<noscript><table style="width:100%;height:100%;"><tr style="valign:middle;"><td><div style="text-align:center;">ERROR:<br/><br/>Javascript not activated<br/><br/></div></td></tr></table></noscript>
						<script>
							embedpano({swf:"player/tour.swf", xml:"tours/<?echo $row["id"];?>/tour<?echo $row["version_xml"];?>.xml", target:"pano", html5:"auto", wmode:"opaque"});
						</script>                	
                    </div>
                    <a href="profile.php?uid=<?php echo $row_usr["id"];?>" class="user"><img src="images/users/<?echo $row_usr["avatar"];?>" width="43" height="43" ></a>
					<div class="by"><a href="profile.php?uid=<?php echo $row_usr["id"];?>">by <?echo $row["user"];?></a></div>
					
                    <a href="tour.php?id=<?php echo $row["id"];?>" class="text">
                    	<p>
							<?echo $row["title"];?>
                        </p>
                    </a>
                    
					<div class="count">
						<div class="views"><?php echo $row["views"];?></div>
						
						<?php if($row["allow_votes"] == 'on'){?>
							<a href="javascript:void(0)" id="like<?php echo $row["id"];?>" class="likes" onclick="like(<?php echo $row["id"];?>);"><?php echo $row["likes"];?></a>
						<?php }?>
					
						<?php if($row["allow_comments"] == 'on'){?>
							<a href="tour.php?id=<?php echo $row["id"];?>#comments"  class="comments"><?php echo $row["comments"];?></a>
						<?php }?>	
					
						<br clear="all">
						
					</div>                    
                </div>
                
                

                
			<?php 
			$first_show = 21;
			require 'php-stubs/get_first_tours.php';
			echo $postText;?>

	
	        	

				<br clear="all">
        </div>
	</body>
</html>

<?php }else{
	$last_show = 6;
	require 'php-stubs/get_last_tours.php';
}
?>     	
