<?

require_once(realpath($_SERVER["DOCUMENT_ROOT"])."/inc/auth.inc");
require_once(realpath($_SERVER["DOCUMENT_ROOT"]).'/inc/conex.inc');


if ($uid == ''){
	if(!isset($_GET["uid"]) || empty($_GET["uid"])){
		header('Location:index.php');
	}else{
		$uid = $_GET["uid"];
	}
}

$urlstring = $_SERVER[REQUEST_URI];
$secparam = explode("?", $urlstring);
$mod = $secparam[1];

$cant_reg = 10;



$lastID = $_GET["lastID"];
$action = $_GET["action"];

if ($action != 'getLastPosts') {
	
	
	$ssql_stats = "SELECT * FROM tours where iduser = ".$uid." and tours.state = 'publish' and tours.privacy = '_public'";

	$result_stats = mysql_query($ssql_stats);	
	$cant_tours = mysql_num_rows($result_stats);
	
	$ssql_stats = "SELECT * FROM follows, users where id_following = ".$uid." and id_follower = users.id and users.status = 1";
	$result_stats = mysql_query($ssql_stats);	
	$cant_follows = mysql_num_rows($result_stats);

	$ssql_stats = "SELECT * FROM follows, users where id_follower = ".$uid." and id_following = users.id and users.status = 1";
	$result_stats = mysql_query($ssql_stats);
	$cant_following = mysql_num_rows($result_stats);
	
	$ssql_stats = "SELECT * FROM users where id = ".$uid;
	$result_stats = mysql_query($ssql_stats);	
	$row_user = mysql_fetch_array($result_stats);
	$avatar = $row_user["avatar"];
	$cover = $row_user["cover"];
	$username = $row_user["username"];
	$wb = $row_user["website"];
	$fb = $row_user["facebook"];
	$tw = $row_user["twitter"];
	$city = $row_user["city"];
	$state = $row_user["state"];
	$country = $row_user["country"];
	$nickname = $row_user["nickname"];
	
	$loc = '';
				
	if($city != '' && $state == '' && $country == ''){$loc = $city;}
	if($city == '' && $state != '' && $country == ''){$loc = $state;}
	if($city == '' && $state == '' && $country != ''){$loc = $country;}
				
	if($city != '' && $state != '' && $country == ''){$loc = $city.', '.$state;}
	if($city != '' && $state == '' && $country != ''){$loc = $city.'- '.$country;}
	if($city == '' && $state != '' && $country != ''){$loc = $state.'- '.$country;}
	if($city != '' && $state != '' && $country != ''){$loc = $loc = $city.', '.$state.' - '.$country;}	
	
	if($loc==',  - '){$loc = '';}
	
	
	if($in_friendly != 1){ //Variable definida en friendlyURLmanager.php para ver si estoy llegando desde la friendly o no
		header("Location: http://".$_SERVER[HTTP_HOST]."/".$nickname ,TRUE,301);
	}
	
	$meta_type='user';
	$page_title = $username." | Spinattic";
	require(realpath($_SERVER["DOCUMENT_ROOT"])."/inc/header.php");

	if($mod == ''){
		$mod = 'tours';
		$tours_display = 'block';
		$follows_display = 'none';
	}else{
		$tours_display = 'none';
		$follows_display = 'block';
	
		echo '<script type="text/javascript">
	
		$("document").ready(function(){
		$("#select_'.$mod.'").trigger("click");
	})
	
	</script>';
	}

//	$follows_display = 'none';
?>
	
				<script src="http://<?php echo $_SERVER[HTTP_HOST];?>/js/ajaxscrollprofile.js"></script>

	            <div class="front-profile" style="background: url(http://<?php echo $_SERVER[HTTP_HOST];?>/images/users/cover/<?php echo $cover;?>) repeat-x left top;">
		            <div class="content">
	                	<a href="#" class="user"><img id="elavatar" width="107" height="107" src="http://<?php echo $_SERVER[HTTP_HOST];?>/images/users/<?echo $avatar;?>"></a>
	                    <div class="content-info">
		                    <h3><?echo $username;?></h3>
		                    <div class="user-links">
		                    <?php get_pro();?>
		                    <?if($wb != ''){?><a href="http://<?echo $wb?>" target="_blank" class="url"><?echo $wb?></a><?}?>
		                    <?if($fb != ''){?><a href="http://www.facebook.com/<?echo $fb?>" target="_blank" class="facebook"><?echo $fb?></a><?}?>
		                    <?if($tw != ''){?><a href="http://www.twitter.com/<?echo $tw?>" target="_blank" class="twitter"><?echo $tw?></a><?}?>
		                    <?if($loc != ''){?><font class="marker"><?echo $loc?></font><?}?>
		                    </div>
						</div> 
						<?php get_follow_btn($uid, $logged);?>

	                </div>
	                <ul class="profile_menu">
						<li class="tours"><a href="" <?php if($mod == 'tous'){?>class="selected"<?php }?> id="select_tours"><?php echo $cant_tours?> Tours</a></li>
						<li class="followers"><a href="" id="select_followers" data-mod="followers" class="followsInProfile <?php if($mod == 'followers'){?>selected<?php }?>" data-order="cant_tours"><?php echo $cant_follows?> Followers</a></li>
						<li class="following"><a href="" id="select_following" data-mod="following" class="followsInProfile  <?php if($mod == 'followers'){?>selected<?php }?>" data-order="cant_tours"><?php echo $cant_following?> Following</a></li>
					</ul>
	
	            </div>	

		        <div class="wrapper">
	 					
		        		<div class="wrappper-posts" id="tours" style="display:<?php echo $tours_display;?>;">	
							<?php 
								$conditions = "iduser = ".$uid;
								$first_show = 21;
								require 'php-stubs/get_first_tours.php';
								echo $postText;
							?>
						</div>
						
						
						<div class="wrapper-leaderboard profile" id="follows" style="display:<?php echo $follows_display;?>;">
						   	<header>
				        		<h3>Sort by: </h3>
				        		<ul>
				        			<li><a href="" data-order="cant_tours" class="followsInProfile orderers" id="order_by_cant_tours">Published tours</a></li>
				        			<li><a href="" data-order="cant_likes" class="followsInProfile orderers" id="order_by_cant_likes">Likes</a></li>
				        			<li><a href="" data-order="cant_follows" class="followsInProfile orderers" id="order_by_cant_follows">Followers</a></li>
				        		</ul>
		        			</header>
		        			<div id="follows_data"></div>
				        </div>
			        
			
				</div>
				<input type="hidden" value="<?php echo $mod;?>" id="mod" name="mod">
				<input type="hidden" value="<?php echo $uid;?>" id="uid" name="uid">
				<input type="hidden" value="<?php echo $cant_reg;?>" id="cant_reg" name="cant_reg">    
	
	
	<?php require_once("inc/footer.php");?>
		
	</html>
	
	
	
<?php }else{
	$conditions = "iduser = ".$uid;
	$last_show = 6;
	require 'php-stubs/get_last_tours.php';
}
?>