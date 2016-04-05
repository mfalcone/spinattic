<?


require_once("inc/auth.inc");
require_once("inc/conex.inc");
require_once("inc/functions.inc");


$lastID = $_GET["lastID"];
$action = $_GET["action"];


$page_title = "Spinattic | ".$_SESSION["username"]." Following";

if ($action != 'getLastPosts') {


$restrict = 1;
require("inc/header.php");
 
?>
	<script src="js/ajaxscrollhome.js"></script>

    	<div>
		    <h1 class="followers-title">Following</h1>
		</div>
		
		<div class="wrapper wrapper-leaderboard">
			<input type="hidden" value="follows" id="action">
    		<?php 

			session_start();
			$myid = $_SESSION['usr'];
			$ssqlp = "SELECT * from follows, users where follows.id_follower = ".$myid." and id_following = users.id and users.status = 1 order by users.username";
			$result = mysql_query($ssqlp);
			$cant_reg = mysql_num_rows($result);
			
			
			?>            
            
			 <div class="total-followers">
				<p>All Following: <?php echo $cant_reg;?></p>
			 </div>  	
            <ul class="leaderboard-list">
            
<?php 		$i=0;
			$cant_reg=10;
			while($row = mysql_fetch_array($result)){
			
				
				$i++;
				
				if ($i > $cant_reg){
					break;
				}
				
				$uid = $row["id"];
				$avatar = $row["avatar"];
				$cover = $row["cover"];
				$username = $row["username"];
				$wb = $row["website"];
				$fb = $row["facebook"];
				$tw = $row["twitter"];
				$city = $row["city"];
				$state = $row["state"];
				$country = $row["country"];
				$loc = $city.', '.$state.' - '.$country;
				if($loc==',  - '){
					$loc = '';
				}
				
				$cant_tours = 0;
				$cant_likes = 0;
				$cant_follows = 0;
				
				$ssql_stats = "SELECT count(id) as cant_tours, sum(likes) as cant_likes FROM tours where tours.state = 'publish' and tours.privacy = '_public' and iduser = ".$uid;
				$result_stats = mysql_query($ssql_stats);
				if($row_stats = mysql_fetch_array($result_stats)){
					$cant_tours = $row_stats["cant_tours"];
					if($cant_tours > 0){$cant_likes = $row_stats["cant_likes"];}
				}
				
				$ssql_stats = "SELECT count(*) as cant_follows FROM follows where id_following = ".$uid;
				$result_stats = mysql_query($ssql_stats);
				if($row_stats = mysql_fetch_array($result_stats)){
					$cant_follows = $row_stats["cant_follows"];
				}				
				
				$follow_you = 'no';
				$ssql_stats = "SELECT * FROM follows where id_following = ".$myid." and id_follower = ".$uid;
				$result_stats = mysql_query($ssql_stats);
				if($row_stats = mysql_fetch_array($result_stats)){
					$follow_you = 'yes';
				}				
												
				echo '
		          <li id="'.$i.'" class="item">
		                
		                    <a href="profile.php?uid='.$uid.'"><img src="images/users/'.$avatar.'" width="100" height="100"></a>
		              
		                <dl class="foll">
		                
                    	<dt><a href="profile.php?uid='.$uid.'">'.$username.'</a></dt>';
                    	//get_pro();
		                //echo '<h4>'.$cant_tours.' published tours</h4>';?>
		                    <dd>
		                    	<?if($loc != ''){?><span class="marker"><?echo $loc?></span><?}?>	
		                    	<ul>
									<?if($wb != ''){?><li><a href="http://<?echo $wb?>" target="_blank" class="url"><?echo $wb?></a></li><?}?>
									<?if($fb != ''){?><li><a href="http://www.facebook.com/<?echo $fb?>" target="_blank" class="facebook"><?echo $fb?></a></li><?}?>
									<?if($tw != ''){?><li><a href="http://www.twitter.com/<?echo $tw?>" target="_blank" class="twitter"><?echo $tw?></a></li><?}?>
								</ul>
							<dd>
						</dl>
						<div class="right-column">
	            			<ul class="user-data">
								<li class="published"><span class="icon"></span><a href="profile.php?uid=<?php echo $uid;?>"><? echo $cant_tours; ?></a></li>
								<li class="likes"><span class="icon"></span><a href="profile.php?uid=<?php echo $uid;?>"><? echo $cant_likes; ?></a></li>
								<li class="followers"><span class="icon"></span><a href="profile.php?uid=<?php echo $uid;?>"><? echo $cant_follows; ?></a></li>
	            			</ul>
		            			<? get_follow_btn($uid, $logged); ?>                   
						</div>
						<?php 
						echo '</li>';
			};
			if ($i <$cant_reg){
				echo '<input type="hidden" class="nomore">';
			}
			?>            
             </ul>   	
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
	$myid = $_SESSION['usr'];
	$ssqlp = "SELECT * from follows, users where follows.id_follower = ".$myid." and id_following = users.id and users.status = 1 order by users.username";

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
		$j++;
	
		
		$uid = $row["id"];
		$avatar = $row["avatar"];
		$cover = $row["cover"];
		$username = $row["username"];
		$wb = $row["website"];
		$fb = $row["facebook"];
		$tw = $row["twitter"];
		$city = $row["city"];
		$state = $row["state"];
		$country = $row["country"];
		$loc = $city.', '.$state.' - '.$country;
		if($loc==',  - '){
			$loc = '';
		}
		
		$cant_tours = 0;
		$cant_likes = 0;
		$cant_follows = 0;
		
		$ssql_stats = "SELECT count(id) as cant_tours, sum(likes) as cant_likes FROM tours where tours.state = 'publish' and tours.privacy = '_public' and iduser = ".$uid;
		$result_stats = mysql_query($ssql_stats);
		if($row_stats = mysql_fetch_array($result_stats)){
			$cant_tours = $row_stats["cant_tours"];
			if($cant_tours > 0){$cant_likes = $row_stats["cant_likes"];}
		}
		
		$ssql_stats = "SELECT count(*) as cant_follows FROM follows where id_following = ".$uid;
		$result_stats = mysql_query($ssql_stats);
		if($row_stats = mysql_fetch_array($result_stats)){
			$cant_follows = $row_stats["cant_follows"];
		}				
		
		$follow_you = 'no';
		$ssql_stats = "SELECT * FROM follows where id_following = ".$myid." and id_follower = ".$uid;
		$result_stats = mysql_query($ssql_stats);
		if($row_stats = mysql_fetch_array($result_stats)){
			$follow_you = 'yes';
		}				
										
				echo '
		          <li id="'.$i.'" class="item">
		                
		                    <a href="profile.php?uid='.$uid.'"><img src="images/users/'.$avatar.'" width="100" height="100"></a>
		              
		                <dl class="foll">
		                
                    	<dt><a href="profile.php?uid='.$uid.'">'.$username.'</a></dt>';
                    	//get_pro();
		                //echo '<h4>'.$cant_tours.' published tours</h4>';?>
		                    <dd>
		                    	<?if($loc != ''){?><span class="marker"><?echo $loc?></span><?}?>	
		                    	<ul>
									<?if($wb != ''){?><li><a href="http://<?echo $wb?>" target="_blank" class="url"><?echo $wb?></a></li><?}?>
									<?if($fb != ''){?><li><a href="http://www.facebook.com/<?echo $fb?>" target="_blank" class="facebook"><?echo $fb?></a></li><?}?>
									<?if($tw != ''){?><li><a href="http://www.twitter.com/<?echo $tw?>" target="_blank" class="twitter"><?echo $tw?></a></li><?}?>
								</ul>
							<dd>
						</dl>
						<div class="right-column">
	            			<ul class="user-data">
								<li class="published"><span class="icon"></span><a href="profile.php?uid=<?php echo $uid;?>"><? echo $cant_tours; ?></a></li>
								<li class="likes"><span class="icon"></span><a href="profile.php?uid=<?php echo $uid;?>"><? echo $cant_likes; ?></a></li>
								<li class="followers"><span class="icon"></span><a href="profile.php?uid=<?php echo $uid;?>"><? echo $cant_follows; ?></a></li>
	            			</ul>
		            			<? get_follow_btn($uid, $logged); ?>                   
						</div>
						<?php 
						echo '</li>';
			};
			if ($j <$cant_reg){
				echo '<input type="hidden" class="nomore">';
			}
	
}
?>