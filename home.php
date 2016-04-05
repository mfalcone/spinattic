<?

$restrict = 1;

$lastID = $_GET["lastID"];
$action = $_GET["action"];


if ($action != 'getLastPosts') {
 
 require("inc/header.php");

 $ssqlp = "(SELECT
 tours.id as id,
 tours.friendly_url as friendly_url,
 tours.title as title,
 tours.iduser as iduser,
 tours.user as user,
 tours.description as description,
 tours.views as views,
 tours.comments as comments,
 tours.likes as likes,
 tours.tour_thumb_path,
 tours.date as date,
 tours.user as source_user,
 tours.iduser as source_user_id,
 users.avatar as avatar,
 users.friendly_url as userfriendly,
 'tours' as type,
 tours.allow_votes as allow_votes,
 tours.allow_comments as allow_comments
 FROM tours, follows, users where
 tours.state = 'publish' and
 tours.privacy = '_public' and
 iduser = id_following and
 id_follower = ".$_SESSION["usr"]." and
 users.id = follows.id_following and
 tours.date >= follows.date)
 
 UNION
 
 (SELECT
 tours.id as id,
 tours.friendly_url as friendly_url,
 tours.title as title,
 tours.iduser as iduser,
 tours.user as user,
 tours.description as description,
 tours.views as views,
 tours.comments as comments,
 tours.likes as likes,
 tours.tour_thumb_path,
 likes.date as date,
 users_for_source.username as source_user,
 follows.id_following as source_user_id,
 users.avatar as avatar,
 users.friendly_url as userfriendly,
 'likes' as type,
 tours.allow_votes as allow_votes,
 tours.allow_comments as allow_comments
 FROM tours, likes, users, follows, users as users_for_source
 where
 follows.id_following = users_for_source.id and
 tours.state = 'publish' and
 tours.privacy = '_public' and
 follows.id_following = likes.iduser and
 follows.id_follower = ".$_SESSION["usr"]." and
 users.id = tours.iduser and
 likes.idtour = tours.id and
 likes.date >= follows.date)
 
 order by date desc "; 
 
 
 ?>

		<script src="js/ajaxscrollhome.js"></script>

		<input type="hidden" value="notifications" id="action">
           
            
			<?
			session_start();

			$result = mysql_query($ssqlp);
			$cant = mysql_num_rows($result);
			if($cant == 0){?>
            <div class="landing">
                <p>Welcome to your Spinattic news feed! Here are a few tips to get you started.</p>
                <p class="blue">
                    News feed populates as you follow, comment, and like other tours and users. <br>
                    Create and upload tours so followers can see what you are up to.
                </p>
                <p class="blue">Create, Share and Interact! </p>
                <a class="btn-start-follow" href="/leaderboard.php"><span class="icon-follow-white"></span> Start following Photographers</a>
                <a class="btn-create-tour" href="/edit_virtual_tour.php"><span class="icon-tour-white"></span> Create a Tour</a>
                <a href="#" class="delete-item tour-remove" rel="" original-title="Delete item"></a>
            </div>

			<?php 
			die;}?>
		<div class="wrapper wrapper-news wrappper-posts">
			
			<?php $i=0;
			$cant_reg=10;
			
			while($row = mysql_fetch_array($result)){
				$i++;
				
				if ($i > $cant_reg){
					break;
				}
				if($row["type"] == 'likes'){
					$typetext = 'likes this virtual tour';
				}else{
					$typetext = 'published this virtual tour';
				}
				/*
				$ssqlthumb = "SELECT * FROM panosxtour where idtour = ".$row["id"]." ORDER BY ord LIMIT 1";
				$resultthumb = mysql_query($ssqlthumb);
				$rowthumb = mysql_fetch_array($resultthumb);
					*/
					
				$class_like = "";
				
				$ssqllike = "SELECT * FROM likes where idtour = ".$row["id"]." and iduser = ".$_SESSION["usr"];
				$resultlike = mysql_query($ssqllike);
				if($rowlike = mysql_fetch_array($resultlike)){
					$class_like = 'liked';
				}				

				echo '

				<div class="item post" id="'.$i.'">
	            
	                <div class="posthead">
	                    <p><a href="http://'.$_SERVER[HTTP_HOST].'/'.$row["userfriendly"].'" target="_self" >'.$row["source_user"].'</a> '.$typetext.'</p>
	                </div>				
					<div class="thumb" style="background:url('.$row["tour_thumb_path"].'thumb900x450.jpg) no-repeat center left;" >
						<a href="http://'.$_SERVER[HTTP_HOST].'/'.$row["userfriendly"].'/'.$row["friendly_url"].'"  title="'.$row["title"].'" target="_self">'.$row["title"].'</a>
					</div>
					<div class="by"><a href="http://'.$_SERVER[HTTP_HOST].'/'.$row["userfriendly"].'" class="user" target="_self" ><img src="images/users/'.$row["avatar"].'" width="43" height="43" /></a><a href="http://'.$_SERVER[HTTP_HOST].'/'.$row["userfriendly"].'" target="_self" >'.$row["user"].'</a>'; 
					get_pro();
					echo '</div>
					<a href="http://'.$_SERVER[HTTP_HOST].'/'.$row["userfriendly"].'/'.$row["friendly_url"].'" class="text" target="_self" >
						<p><strong>'.$row["title"].'</strong></p>
					</a>
					<div class="count">
						<div class="views">'.$row["views"].'</div>';
						
				if($row["allow_comments"] == 'on'){
						echo '<a href="http://'.$_SERVER[HTTP_HOST].'/'.$row["userfriendly"].'/'.$row["friendly_url"].'#comments" class="comments">'.$row["comments"].'</a>';
				};
				if($row["allow_votes"] == 'on'){
						echo '<a href="javascript:void(0)" id="like'.$row["id"].'" class="like'.$row["id"].' likes '.$class_like.'" onclick="like('.$row["id"].');">'.$row["likes"].'</a>';
				};
						
						
						echo '<br clear="all">
					</div>
				</div>	';			
				
			};

			if ($i <$cant_reg){
				echo '<input type="hidden" class="nomore">';
			}
			

			?>            
            <script type="text/javascript">
				document.getElementById("loading").style.display="block";
			</script>    	
        </div>
		
	<?php require_once("inc/footer.php");?>test
</html>	

<?
}else{

	require_once("inc/conex.inc");
	require_once("inc/functions.inc");
	require_once("inc/auth.inc");	
	
	$cant_reg = 10;
	$getPostsText = "";
	session_start();

	$ssqlp = "(SELECT
	tours.id as id,
 	tours.friendly_url as friendly_url,	
	tours.title as title,
	tours.iduser as iduser,
	tours.user as user,
	tours.description as description,
	tours.views as views,
	tours.comments as comments,
	tours.likes as likes,
	tours.tour_thumb_path,
	tours.date as date,
	tours.user as source_user,
	tours.iduser as source_user_id,
	users.avatar as avatar,
	users.friendly_url as userfriendly,
	'tours' as type,
	tours.allow_votes as allow_votes,
	tours.allow_comments as allow_comments
	FROM tours, follows, users where
	tours.state = 'publish' and
	tours.privacy = '_public' and
	iduser = id_following and
	id_follower = ".$_SESSION["usr"]." and
	users.id = follows.id_following)
	
	UNION
	
	(SELECT
	tours.id as id,
	tours.friendly_url as friendly_url,
	tours.title as title,
	tours.iduser as iduser,
	tours.user as user,
	tours.description as description,
	tours.views as views,
	tours.comments as comments,
	tours.likes as likes,
	tours.tour_thumb_path,
	likes.date as date,
	users_for_source.username as source_user,
	follows.id_following as source_user_id,
	users.avatar as avatar,
	users.friendly_url as userfriendly,
	'likes' as type,
	tours.allow_votes as allow_votes,
	tours.allow_comments as allow_comments
	FROM tours, likes, users, follows, users as users_for_source
	where
	follows.id_following = users_for_source.id and
	tours.state = 'publish' and
	tours.privacy = '_public' and
	follows.id_following = likes.iduser and
	follows.id_follower = ".$_SESSION["usr"]." and
	users.id = tours.iduser and
	likes.idtour = tours.id)
	
	order by date desc ";
	
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
	
		if($row["type"] == 'likes'){
			$typetext = 'likes this virtual tour';
		}else{
			$typetext = 'published this virtual tour';
		}

			/*
			$ssqlthumb = "SELECT * FROM panosxtour where idtour = ".$row["id"]." ORDER BY ord LIMIT 1";
			$resultthumb = mysql_query($ssqlthumb);
			$rowthumb = mysql_fetch_array($resultthumb);
				*/

			$class_like = "";
			
			$ssqllike = "SELECT * FROM likes where idtour = ".$row["id"]." and iduser = ".$_SESSION["usr"];
			$resultlike = mysql_query($ssqllike);
			if($rowlike = mysql_fetch_array($resultlike)){
				$class_like = 'liked';
			}			
			
				echo '

				<div class="item post" id="'.$i.'">
	                <div class="posthead">
	                    <p><a href="http://'.$_SERVER[HTTP_HOST].'/'.$row["userfriendly"].'" target="_self" >'.$row["source_user"].'</a> '.$typetext.'</p>
	                </div>					
					<div class="thumb" style="background:url('.$row["tour_thumb_path"].'thumb900x450.jpg) no-repeat center left;" >
						<a href="http://'.$_SERVER[HTTP_HOST].'/'.$row["userfriendly"].'/'.$row["friendly_url"].'"  title="'.$row["title"].'" target="_self">'.$row["title"].'</a>
					</div>
					<div class="by"><a href="http://'.$_SERVER[HTTP_HOST].'/'.$row["userfriendly"].'" class="user" target="_self" ><img src="images/users/'.$row["avatar"].'" width="43" height="43" /></a><a href="http://'.$_SERVER[HTTP_HOST].'/'.$row["userfriendly"].'" target="_self" >'.$row["user"].'</a>';
					 get_pro();
					 echo '</div>
					<a href="http://'.$_SERVER[HTTP_HOST].'/'.$row["userfriendly"].'/'.$row["friendly_url"].'" class="text" target="_self" >
						<p><strong>'.$row["title"].'</strong></p>
					</a>
					<div class="count">
						<div class="views">'.$row["views"].'</div>';
						
				if($row["allow_comments"] == 'on'){
						echo '<a href="http://'.$_SERVER[HTTP_HOST].'/'.$row["userfriendly"].'/'.$row["friendly_url"].'#comments" class="comments">'.$row["comments"].'</a>';
				};
				if($row["allow_votes"] == 'on'){
						echo '<a href="javascript:void(0)" id="like'.$row["id"].'" class="like'.$row["id"].' likes '.$class_like.'" onclick="like('.$row["id"].');">'.$row["likes"].'</a>';
				};
						
						
						echo '<br clear="all">
					</div>
				</div>	';
	};	
	if ($j <$cant_reg){
		echo '<input type="hidden" class="nomore">';
	}	
}
?>

