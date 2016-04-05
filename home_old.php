<?
require("inc/auth.inc");
require("inc/conex.inc");


$lastID = $_GET["lastID"];
$action = $_GET["action"];

if ($action != 'getLastPosts') {
 
?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
		<link href='favicon.png' rel='shortcut icon' type='image/x-icon'/>
		<link href='favicon.png' rel='icon' type='image/x-icon'/>
		<title>Spinattic</title>

	    <!--google font-->
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'>

		<!-- css main -->        
	    <link rel="stylesheet" type="text/css" media="screen" href="css/main.css" />
	    <!-- jquery -->    
	    <script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
	    <script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
	    <!-- jquery ui -->        
	    <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
        <script src="js/ajaxlike.js"></script>
	    <script src="js/custom.js"></script>
		<script src="js/ajaxscroll.js"></script>

	<script type="text/javascript">

	$(document).ready(function(){
		
		$('form#mainForm').bind('submit', function(e){
			e.preventDefault();
			checkForm();
		});
		
		$('input#hostName').focus();
	
		
		function lastPostFunc() 
		{ 
			
			$.post("home.php?action=getLastPosts&lastID="+$(".post").attr("id"),
	
			function(data){
				if (data != "") {
				$(".post:last").after(data);
				//setupBlocks();						   				
				}
			document.getElementById("loading").style.display="none";
			});
 		};  
		
		$(window).scroll(function(){
			if  ($(window).scrollTop() == $(document).height() - $(window).height()){
				document.getElementById("loading").style.display="block";
			   lastPostFunc();
			}
		}); 

	});
	


	</script>

	</head>
	<body>
	
		<div id="loading" class="loading" style="display: none;">
	        <div class="loading_img"></div>
        </div>

        
    	<div class="header">
			<?require("inc/head.inc");?>
        </div>


        <div class="nav">
				<?require("inc/nav.inc");?>
        </div>
        
        
        <div>
		    <h1 class="notifications-title">NOTIFICATIONS</h1>
		</div>
		
		<div class="wrapper wrapper-user">
		<input type="hidden" value="notifications" id="action">
            <hr class="space180px">
            
			<?
			session_start();
			//$ssqlp = "SELECT tours.*, follows.*, users.avatar FROM tours, follows, users where tours.state = 'publish' and tours.privacy = '_public' and iduser = id_following and id_follower = ".$_SESSION["usr"]." and users.id = follows.id_following order by tours.date desc";
			$ssqlp = "(SELECT 
						tours.id as id,
						tours.iduser as iduser,
						tours.user as user,
						tours.description as description,
						tours.views as views,
						tours.comments as comments,
						tours.likes as likes,
						tours.date as date,
						users.avatar as avatar,
						'tours' as type
						FROM tours, follows, users where 
						tours.state = 'publish' and 
						tours.privacy = '_public' and 
						iduser = id_following and 
						id_follower = ".$_SESSION["usr"]." and 
						users.id = follows.id_following)
						
						UNION 
						
						(SELECT 
						tours.id as id,
						tours.iduser as iduser,
						tours.user as user,
						tours.description as description,
						tours.views as views,
						tours.comments as comments,
						tours.likes as likes,
						likes.date as date,
						users.avatar as avatar,
						'likes' as type
						FROM tours, likes, users, follows 
						where 
						tours.state = 'publish' and 
						tours.privacy = '_public' and 
						follows.id_following = likes.iduser and 
						follows.id_follower = ".$_SESSION["usr"]." and 
						users.id = tours.iduser and 
						likes.idtour = tours.id)
						
						order by date desc ";
			$result = mysql_query($ssqlp);
			$cant = mysql_num_rows($result);
			if($cant == 0){?>
            <div class="landing">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus<br>commodo cursus leo, sit amet rutrum enim adipiscing</p>
                <p class="blue">Etiam scelerisque nulla ac malesuada placerat. Pellentesque eget mi a ligula viverra laoreet </p>
                <button class="btn-start-follow"><span class="icon-follow-white"></span> Start Following</button>
                <button class="btn-create-tour"><span class="icon-tour-white"></span> Create Tour</button>
                <a href="#" class="delete-item tour-remove" rel="" original-title="Delete item"></a>
            </div>				
			<?php 
			die;}
			
			$i=0;
			while($row = mysql_fetch_array($result)){
				$i++;
				
				if ($i > 10){
					break;
				}

				echo '
				<div class="post" id="'.$i.'">
					<div class="thumb">
						<a href="javascript:;"><img src="panos/'.$row["id"].'/index.tiles/thumb900x450.jpg" alt=""></a>
					</div>
					<a href="profile.php?uid='.$row["iduser"].'" class="user"><img src="images/users/'.$row["avatar"].'" width="43" height="43"></a>
					<div class="by"><a href="profile.php?uid='.$row["iduser"].'">'.$row["user"].'</a> <span class="pro">PRO</span></div>
					<a href="tour.php?id='.$row["id"].'" class="text">
						<p><strong>'.$row["description"].'</strong></p>
					</a>
					<div class="count">
						<div class="views">'.$row["views"].'</div>
						
						<a href="#" class="comments">'.$row["comments"].'</a>
						
						<a href="javascript:void(0)" id="like'.$row["id"].'" class="likes" onclick="like('.$row["id"].');">'.$row["likes"].'</a>
						<br clear="all">
					</div>
				</div>	';			
				
			};

			


			?>            
                	
        </div>
			
	</body>
</html>

<?
}else{
	$getPostsText = "";
	session_start();
	//$ssqlp = "SELECT tours.*, follows.*, users.avatar FROM tours, follows, users where tours.state = 'publish' and tours.privacy = '_public' and iduser = id_following and id_follower = ".$_SESSION["usr"]." and users.id = follows.id_following order by tours.date desc";
	$ssqlp = "(SELECT
	tours.id as id,
	tours.iduser as iduser,
	tours.user as user,
	tours.description as description,
	tours.views as views,
	tours.comments as comments,
	tours.likes as likes,
	tours.date as date,
	users.avatar as avatar,
	'tours' as type
	FROM tours, follows, users where
	tours.state = 'publish' and
	tours.privacy = '_public' and
	iduser = id_following and
	id_follower = ".$_SESSION["usr"]." and
	users.id = follows.id_following)
	
	UNION
	
	(SELECT
	tours.id as id,
	tours.iduser as iduser,
	tours.user as user,
	tours.description as description,
	tours.views as views,
	tours.comments as comments,
	tours.likes as likes,
	likes.date as date,
	users.avatar as avatar,
	'likes' as type
	FROM tours, likes, users, follows
	where
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
		if ($j >= 5){
			break;
		};
				
		$i++;
		$j++;
	

				echo '
				<div class="post" id="'.$i.'">
					<div class="thumb">
						<a href="javascript:;"><img src="panos/'.$row["id"].'/index.tiles/thumb900x450.jpg" alt=""></a>
					</div>
					<a href="profile.php?uid='.$row["iduser"].'" class="user"><img src="images/users/'.$row["avatar"].'" width="43" height="43"></a>
					<div class="by"><a href="profile.php?uid='.$row["iduser"].'">'.$row["user"].'</a> <span class="pro">PRO</span></div>
					<a href="tour.php?id='.$row["id"].'" class="text">
						<p><strong>'.$row["description"].'</strong></p>
					</a>
					<div class="count">
						<div class="views">'.$row["views"].'</div>
						
						<a href="#" class="comments">'.$row["comments"].'</a>
						
						<a href="javascript:void(0)" id="like'.$row["id"].'" class="likes" onclick="like('.$row["id"].');">'.$row["likes"].'</a>
						<br clear="all">
					</div>
				</div>	';
	};	
	
}
?>