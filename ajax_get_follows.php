				<?php 
				
				require("inc/conex.inc");
				require("inc/functions.inc");				
				require("inc/auth.inc");
				
				
				$order = $_GET["o"];
				$uid = $_GET["uid"];
				$mod = $_GET["mod"];
				$cant_reg  = $_GET["cant_reg"];
				$action    = $_GET["action"];
				
				if($order == ''){
					$ord = 'cant_tours';
				} else{
					$ord = $order;
				}?>	
				
				
		           
		            
					<?php 		
					session_start();
					$myid = $_SESSION['usr'];
					
					if($mod == "following"){
						$ssqlp = "select users.id, users.friendly_url as friendlyuser, users.username, users.country, users.state, users.city, users.twitter, users.facebook, users.avatar, users.friendly_url, IFNULL(tours_view.cant_likes,0) as cant_likes, IFNULL(tours_view.cant_tours,0) as cant_tours, IFNULL(follows_view.cant_follows,0) as cant_follows from follows, users LEFT JOIN (select iduser, count(id) as cant_tours, IFNULL(sum(likes),0) as cant_likes from tours where privacy = '_public' group by iduser) as tours_view on users.id = tours_view.iduser LEFT JOIN (select id_following, count(id_follower) as cant_follows from follows group by id_following) as follows_view on follows_view.id_following = users.id where follows.id_follower = ".$uid." and follows.id_following = users.id and users.status = 1 group by users.id, users.username, users.country, users.state, users.city, users.twitter, users.facebook, users.avatar, users.friendly_url order by ".$ord." desc";
					}
					
					if($mod == "followers"){
						$ssqlp = "select users.id, users.friendly_url as friendlyuser, users.username, users.country, users.state, users.city, users.twitter, users.facebook, users.avatar, users.friendly_url, IFNULL(tours_view.cant_likes,0) as cant_likes, IFNULL(tours_view.cant_tours,0) as cant_tours, IFNULL(follows_view.cant_follows,0) as cant_follows from follows, users LEFT JOIN (select iduser, count(id) as cant_tours, IFNULL(sum(likes),0) as cant_likes from tours where privacy = '_public' group by iduser) as tours_view on users.id = tours_view.iduser LEFT JOIN (select id_following, count(id_follower) as cant_follows from follows group by id_following) as follows_view on follows_view.id_following = users.id where follows.id_following = ".$uid." and follows.id_follower = users.id and users.status = 1 group by users.id, users.username, users.country, users.state, users.city, users.twitter, users.facebook, users.avatar, users.friendly_url order by ".$ord." desc";
					}
					
					$result = mysql_query($ssqlp);

					$i=0;
					$j=0;

					if ($action == 'getLastPosts') {					
						$i = 1;
						$lastID = $_GET["lastID"];
						while ($row = mysql_fetch_array($result)){
							if ($i >= $lastID){
								break;
							};
							$i++;
						}
					}else{
						echo '<ul class="leaderboard-list">';
					}
					
					
					while($row = mysql_fetch_array($result)){
						
						$i++;
						$j++;
						
						if ($j > $cant_reg){
							break;
						}
						
						$follow_id = $row["id"];
						$avatar = $row["avatar"];
						$cover = $row["cover"];
						$username = $row["username"];
						$friendlyuser = $row["friendlyuser"];
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
						
						$ssql_stats = "SELECT count(id) as cant_tours, sum(likes) as cant_likes FROM tours where tours.state = 'publish' and tours.privacy = '_public' and iduser = ".$follow_id;
						$result_stats = mysql_query($ssql_stats);
						if($row_stats = mysql_fetch_array($result_stats)){
							$cant_tours = $row_stats["cant_tours"];
							if($cant_tours > 0){$cant_likes = $row_stats["cant_likes"];}
						}
						
						$ssql_stats = "SELECT count(*) as cant_follows FROM follows where id_following = ".$follow_id;
						$result_stats = mysql_query($ssql_stats);
						if($row_stats = mysql_fetch_array($result_stats)){
							$cant_follows = $row_stats["cant_follows"];
						}				
						
						$follow_you = 'no';
						$ssql_stats = "SELECT * FROM follows where id_following = ".$myid." and id_follower = ".$follow_id;
						$result_stats = mysql_query($ssql_stats);
						if($row_stats = mysql_fetch_array($result_stats)){
							$follow_you = 'yes';
						}				
														
						echo '<li id="'.$i.'" class="post'.$mod.'">
				                
				                    <a href="http://'.$_SERVER[HTTP_HOST].'/'.$friendlyuser.'"><img src="http://'.$_SERVER[HTTP_HOST].'/images/users/'.$avatar.'" width="100" height="100"></a>
				              
				                <dl class="foll">
				                
		                    	<dt><a href="http://'.$_SERVER[HTTP_HOST].'/'.$friendlyuser.'">'.$username.'</a></dt>';
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
										<li class="published"><span class="icon"></span><a href=""><? echo $cant_tours; ?></a></li>
										<li class="likes"><span class="icon"></span><a href=""><? echo $cant_likes; ?></a></li>
										<li class="followers"><span class="icon"></span><a href=""><? echo $cant_follows; ?></a></li>
			            			</ul>
				            			<?get_follow_btn($follow_id, $logged); ?>                   
								</div>
								<?php 
								echo '</li>';
					};
					
					if ($j < $cant_reg){
						echo '<input type="hidden" class="nomorefollows">';
					}
					
					
					if ($action != 'getLastPosts') {					
						echo '</ul>';
					}
					?>