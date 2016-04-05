<?


if(strpos($_SERVER[REQUEST_URI], "leaderboard.php")){
	header("Location: http://".$_SERVER[HTTP_HOST]."/leaderboard" ,TRUE,301);
}


$lastID = $_GET["lastID"];
$action = $_GET["action"];
$order = $_GET["o"];

$page_title = "Spinattic | Leaderboard";


if($order == ''){
	$ord = 'cant_tours';
} else{
	$ord = $order;
}

if ($action != 'getLastPosts') {

require("inc/header.php");
 
?>
	<script src="js/ajaxscrollleader.js"></script>

        <div class="wrapper wrapper-leaderboard">
        	<h1 class="leaderboard">Leaderboard</h1>
        	<header>
        		<h3>Sort by: </h3>
        		<ul>
        			<li><a href="leaderboard.php?o=cant_tours" class="order_this_leader selected" data-order="cant_tours">Published tours</a></li>
        			<li><a href="leaderboard.php?o=cant_likes" class="order_this_leader" data-order="cant_likes">Likes</a></li>
        			<li><a href="leaderboard.php?o=cant_follows" class="order_this_leader" data-order="cant_follows">Followers</a></li>
        		</ul>
        	</header>
        	
        	<ul class="leaderboard-list" id="grid_target">
        	
        	
    		<?php 

			session_start();
			$myid = $_SESSION['usr'];
			$ssqlp = "select users.id, users.website, users.friendly_url, users.username, users.country, users.state, users.city, users.twitter, users.facebook, users.avatar, users.friendly_url, IFNULL(tours_view.cant_likes,0) as cant_likes, IFNULL(tours_view.cant_tours,0) as cant_tours, IFNULL(follows_view.cant_follows,0) as cant_follows from users LEFT JOIN (select iduser, count(id) as cant_tours, IFNULL(sum(likes),0) as cant_likes from tours where privacy = '_public' group by iduser) as tours_view on users.id = tours_view.iduser LEFT JOIN (select id_following, count(id_follower) as cant_follows from follows group by id_following) as follows_view on follows_view.id_following =  users.id where users.status = 1 group by users.id, users.username, users.country, users.state, users.city, users.twitter, users.facebook, users.avatar, users.friendly_url order by ".$ord." desc";
			//echo $ssqlp;
			$result = mysql_query($ssqlp);
			$cant_reg = mysql_num_rows($result);
			
			$i=0;
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
				$friendly_url = $row["friendly_url"];
				
				$cant_tours = $row["cant_tours"];
				$cant_likes = $row["cant_likes"];
				$cant_follows = $row["cant_follows"];				
				
				$loc = '';
				
				if($city != '' && $state == '' && $country == ''){$loc = $city;}
				if($city == '' && $state != '' && $country == ''){$loc = $state;}
				if($city == '' && $state == '' && $country != ''){$loc = $country;}
				
				if($city != '' && $state != '' && $country == ''){$loc = $city.', '.$state;}
				if($city != '' && $state == '' && $country != ''){$loc = $city.'- '.$country;}
				if($city == '' && $state != '' && $country != ''){$loc = $state.'- '.$country;}
				if($city != '' && $state != '' && $country != ''){$loc = $loc = $city.', '.$state.' - '.$country;}
				
				
				if($loc==',  - '){
					$loc = '';
				}

		
				echo '<li id="'.$i.'" class="item">
				
				<h4>#'.$i.'</h4>

                      <a href="http://'.$_SERVER[HTTP_HOST].'/'.$friendly_url.'"><img src="images/users/'.$avatar.'" width="100" height="100"></a>
		              
		                <dl class="foll">
		                
                    	<dt><a href="http://'.$_SERVER[HTTP_HOST].'/'.$friendly_url.'">'.$username.'</a></dt>';
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
								<li class="published"><span class="icon"></span><a href="<?php echo 'http://'.$_SERVER[HTTP_HOST].'/'.$friendly_url;?>"><? echo $cant_tours; ?></a></li>
								<li class="likes"><span class="icon"></span><a href="<?php echo 'http://'.$_SERVER[HTTP_HOST].'/'.$friendly_url;?>"><? echo $cant_likes; ?></a></li>
								<li class="followers"><span class="icon"></span><a href="<?php echo 'http://'.$_SERVER[HTTP_HOST].'/'.$friendly_url;?>"><? echo $cant_follows; ?></a></li>
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
	
	
	$cant_reg = 10;
	$getPostsText = "";
	session_start();
	$myid = $_SESSION['usr'];
	$ssqlp = "select users.id, users.friendly_url, users.username, users.country, users.state, users.city, users.twitter, users.facebook, users.avatar, users.friendly_url, IFNULL(tours_view.cant_likes,0) as cant_likes, IFNULL(tours_view.cant_tours,0) as cant_tours, IFNULL(follows_view.cant_follows,0) as cant_follows from users LEFT JOIN (select iduser, count(id) as cant_tours, IFNULL(sum(likes),0) as cant_likes from tours where privacy = '_public' group by iduser) as tours_view on users.id = tours_view.iduser LEFT JOIN (select id_following, count(id_follower) as cant_follows from follows group by id_following) as follows_view on follows_view.id_following = users.id where users.status = 1 group by users.id, users.username, users.country, users.state, users.city, users.twitter, users.facebook, users.avatar, users.friendly_url order by ".$ord." desc";	
	//echo $ssqlp;
	$result = mysql_query($ssqlp);
	
	if($_GET["from_ajax"] !=1 ){
		while ($row = mysql_fetch_array($result)){
			$i++;
			if ($i >= $lastID){
				break;
			};
		}
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
				$friendly_url = $row["friendly_url"];
				
				$cant_tours = $row["cant_tours"];
				$cant_likes = $row["cant_likes"];
				$cant_follows = $row["cant_follows"];				
				
				$loc = '';
				
				if($city != '' && $state == '' && $country == ''){$loc = $city;}
				if($city == '' && $state != '' && $country == ''){$loc = $state;}
				if($city == '' && $state == '' && $country != ''){$loc = $country;}
				
				if($city != '' && $state != '' && $country == ''){$loc = $city.', '.$state;}
				if($city != '' && $state == '' && $country != ''){$loc = $city.'- '.$country;}
				if($city == '' && $state != '' && $country != ''){$loc = $state.'- '.$country;}
				if($city != '' && $state != '' && $country != ''){$loc = $loc = $city.', '.$state.' - '.$country;}
				
				
				if($loc==',  - '){
					$loc = '';
				}
		

												
				echo '<li id="'.$i.'" class="item">
				
				<h4>#'.$i.'</h4>

                      <a href="http://'.$_SERVER[HTTP_HOST].'/'.$friendly_url.'"><img src="images/users/'.$avatar.'" width="100" height="100"></a>
		              
		                <dl class="foll">
		                
                    	<dt><a href="http://'.$_SERVER[HTTP_HOST].'/'.$friendly_url.'">'.$username.'</a></dt>';
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
								<li class="published"><span class="icon"></span><a href="<?php echo 'http://'.$_SERVER[HTTP_HOST].'/'.$friendly_url;?>"><? echo $cant_tours; ?></a></li>
								<li class="likes"><span class="icon"></span><a href="<?php echo 'http://'.$_SERVER[HTTP_HOST].'/'.$friendly_url;?>"><? echo $cant_likes; ?></a></li>
								<li class="followers"><span class="icon"></span><a href="<?php echo 'http://'.$_SERVER[HTTP_HOST].'/'.$friendly_url;?>"><? echo $cant_follows; ?></a></li>
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