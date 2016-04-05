<?
session_start();

require_once("inc/auth.inc");
$secparam = explode('?', $_SERVER[REQUEST_URI]);
if($_SESSION['usr'] == "" && $secparam[1] == ''){
	header('Location: /landing');
}

$lastID = $_GET["lastID"];
$action = $_GET["action"];

$order = 'priority';
//$order = 'id';

if ($action != 'getLastPosts') {
 
require("inc/header.php");


		$ssqlp = "SELECT * FROM tours where state = 'publish' and privacy = '_public' ORDER BY RAND() LIMIT 1";
		$result = mysql_query($ssqlp);	
		$row = mysql_fetch_array($result);

		$ssqlp_usr = "SELECT * FROM users where id = '".$row["iduser"]."'";
		$result_usr = mysql_query($ssqlp_usr);
		$row_usr = mysql_fetch_array($result_usr);	

		$class_like = "";
		
		$ssqllike = "SELECT * FROM likes where idtour = ".$row["id"]." and iduser = ".$_SESSION["usr"];
		$resultlike = mysql_query($ssqllike);
		if($rowlike = mysql_fetch_array($resultlike)){
			$class_like = 'liked';
		}		
		
?>
  		<script src="js/ajaxscroll.js"></script>
      
        <div class="wrapper wrapper-home">
        	<div class="wrappper-posts">
	        	<div class="post-highlight">
                	<div id="pano" class="thumb">
						<noscript><table style="width:100%;height:100%;"><tr style="valign:middle;"><td><div style="text-align:center;">ERROR:<br/><br/>Javascript not activated<br/><br/></div></td></tr></table></noscript>
						<script>
							embedpano({swf:"player/tour.swf", xml:"http://<?php echo $_SERVER[HTTP_HOST];?>/customizer/data/xml.php?id=<?echo $row["id"];?>", target:"pano", html5:"prefer", wmode:"opaque"});
						</script>                	
                    </div>
                    <a href="http://<?php echo $_SERVER[HTTP_HOST];?>/<?php echo $row_usr["friendly_url"];?>" class="user"><img src="images/users/<?echo $row_usr["avatar"];?>" width="43" height="43" alt="<?echo $row["user"];?>"></a>
					<div class="by"><a href="http://<?php echo $_SERVER[HTTP_HOST];?>/<?php echo $row_usr["friendly_url"];?>">by <?echo $row["user"];?></a></div>
					
                    <a href="http://<?php echo $_SERVER[HTTP_HOST];?>/<?php echo $row_usr["friendly_url"];?>/<?php echo $row["friendly_url"];?>" class="text">
                    	<p>
							<?echo $row["title"];?>
                        </p>
                    </a>
                    
					<div class="count">
						<div class="views"><?php echo $row["views"];?></div>
						
						<?php if($row["allow_votes"] == 'on'){?>
							<a href="javascript:void(0)" id="like<?php echo $row["id"];?>" class="like<?php echo $row["id"];?> likes <?php echo $class_like;?>" onclick="like(<?php echo $row["id"];?>);"><?php echo $row["likes"];?></a>
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
		</div>
        </div>
	
	<?php require_once("inc/footer.php");?>
	
	<script type="text/javascript">

		$("document").ready(function(){
			urlstring = location.href;
			secparam = urlstring.split("?")
			if(secparam[1] == "login"){
				$(".login-register .login").trigger("click")
			}else if(secparam[1] == "register"){
				$(".login-register .register").trigger("click")
			}
		})

	</script>
</html>

<?php }else{
	$last_show = 6;
	require 'php-stubs/get_last_tours.php';
}
?>     	
