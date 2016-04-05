<?
	require("inc/conex.inc");
	require("inc/functions.inc");

$lastID = $_GET["lastID"];
$action = $_GET["action"];

$order = $_GET["o"];				
	
if($order == ''){$order = 'id';};


if ($action != 'getLastPosts') {
 


	$ip = $_SERVER['REMOTE_ADDR'];
	$id = $_GET["id"];

		
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
        
		<!-- google map -->
		<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
                <script type="text/javascript" src="js/infobox.js"></script>
        
	    <script src="js/custom.js"></script>
	    <script>
        	$(document).ready(function(){
				initialize();
			});
        </script>

		<script src="js/ajaxlike.js"></script>
		<script type="text/javascript" src="js/map.js"></script>

	<script type="text/javascript">

	$(document).ready(function(){
		
		$('form#mainForm').bind('submit', function(e){
			e.preventDefault();
			checkForm();
		});
		
		$('input#hostName').focus();
	
		
		function lastPostFunc() 
		{ 
		
			$.post("mapa.php?o=<?echo $order;?>&action=getLastPosts&lastID="+$(".post:last").attr("id"),
	
			function(data){
				if (data != "") {
				$(".post:last").after(data);			
				document.getElementById("loading").style.display="none";
				setupBlocks();						   				
				}
			});
 		};  
		
		$(window).scroll(function(){
			if  ($(window).scrollTop() == $(document).height() - $(window).height()){
				document.getElementById("loading").style.display="block";
			   lastPostFunc();
			}
		}); 
		
	});
	


        	$(document).ready(function(){
				initialize();
<?		$ssqlp = "SELECT * FROM tours ORDER BY ".$order." desc";
		$result = mysql_query($ssqlp);	
		
		while ($row = mysql_fetch_array($result)){?>

				addMarker('<?echo $row["lat"];?>', '<?echo $row["lon"];?>', '<?echo $row["user"];?>', '../images/users/<?echo $row["iduser"];?>.jpg', '../images/tours/<?echo $row["id"];?>.jpg', '<?echo $row["title"];?>', <?echo $row["views"];?>, <?echo $row["likes"];?>, '', '<?=$row["allow_votes"]?>');

<?}?>

			});


	</script>
        
	</head>
	<body onload="setupBlocks();">
		<div id="loading" class="loading" style="display: none;">
	        <div class="loading_img"></div>
        </div>	
		<header class="header">
				<?require("inc/head.inc");?>
        </header>

        <div class="nav">
				<?require("inc/nav.inc");?>
        </div>

        <div>
        	<h1 class="map">World Map</h1>
		        <div class="mapa" id="map_canvas"></div>
                
                
                <div class="filter"  id="filter">
                	<ul>
                    	<li><a href="mapa.php?id=<?echo $id;?>&o=date#filter" <?if ($order=="date"){echo 'class="active"';}?>>New</a></li>
                    	<li><a href="mapa.php?id=<?echo $id;?>&o=likes#filter" <?if ($order=="likes"){echo 'class="active"';}?>>Toprated</a></li>
                    	<li><a href="mapa.php?id=<?echo $id;?>&o=views#filter" <?if ($order=="views"){echo 'class="active"';}?>>Popular</a></li>
                    </ul>
                </div>
		</div>
        <div class="wrapper">
			
<?
		$i = 0;
		$ssqlp = "SELECT * FROM tours ORDER BY ".$order." desc";
		$result = mysql_query($ssqlp);	
		
		while ($row = mysql_fetch_array($result)){
		$i++;
		if ($i >=17){break;}
?>			
			
	        		<div class="post" id="<?echo $i;?>">
						<div class="thumb"><a href="tour.php?id=<?echo $row["id"];?>"><img src="images/tours/<?echo $row["id"];?>.jpg"></a></div>                    
							<a href="tour.php?id=<?echo $row["id"];?>" class="user"><img src="images/users/<?echo $row["iduser"];?>.jpg" width="43" height="43" ></a>
						<div class="by"><a href="#">by <?echo $row["user"];?></a></div>
					    <a href="tour.php?id=<?echo $row["id"];?>" class="text">
					    	<p>
								<?echo $row["title"];?>
					        </p>
					    </a>
						<div class="count">
					    	<div class="views"><?echo $row["views"];?></div>
					    	<!-- 
					    	<a href="#" class="comments"><?echo $row["comments"];?></a>
					    	-->   
				    		<a href="javascript:void(0)" id="like<?echo $row["id"];?>" class="likes" onclick="like(<?echo $row["id"];?>);"><?echo $row["likes"];?></a>
					        <br clear="all">
					    </div>
			    
					</div>
			
          
		<?}?>
			        
	        	
        </div>
			<table>
			<input type='hidden' name='lat' id="lat" value="<?echo $lat;?>">
			<input type='hidden' name='long' id="long" value="<?echo $long;?>">
			<input type='hidden' name='likes' id="likes" value="<?echo $likes;?>">
			<input type='hidden' name='views' id="views" value="<?echo $views;?>">
			<input type='hidden' name='user' id="user" value="<?echo $user;?>">
			<input type='hidden' name='id' id="id" value="<?echo $id;?>">
			<input type='hidden' name='iduser' id="iduser" value="<?echo $iduser;?>">
			<input type='hidden' name='description' id="description" value="<?echo $description;?>">
	</body>
</html>

<?
}else{
	$getPostsText = "";

	$ssqlp = 'SELECT * FROM tours ORDER BY '.$order.' DESC';
	$result = mysql_query($ssqlp);	
	
	$i = 1;
	
	while ($row = mysql_fetch_array($result)){
		if ($i >= $lastID){break;};
		$i++;
	}

	$j=1;
		
	while ($row = mysql_fetch_array($result)){
		if ($j >= 5){break;};
		$j++;
		$i++;


		$getPostsText .= '<div class="post" id="'.$i.'">
                	<div class="thumb"><a href="tour.php?id='.$row["id"].'"><img src="images/tours/'.$row["id"].'.jpg"></a></div>                    
                	<a href="tour.php?id='.$row["id"].'" class="user"><img src="images/users/'.$row["iduser"].'.jpg" width="43" height="43" ></a>
					<div class="by"><a href="#">by '.$row["user"].'</a></div>
                    <a href="tour.php?id='.$row["id"].'" class="text">
                    	<p>
							'.$row["title"].'
                        </p>
                    </a>
					<div class="count">
                    	<div class="views">'.$row["views"].'</div>
                    		<!-- 
                    	<a href="#"  class="comments">'.$row["comments"].'</a>
                    		-->        
						<a href="javascript:void(0)" id="like'.$row["id"].'" class="likes" onclick="like('.$row["id"].');">'.$row["likes"].'</a>                    		
                        <br clear="all">
                        </div>
                   </div>';
	}
	echo $getPostsText; //Writes The Result Of The Query
	//When User Scrolls This Query Is Run End
}
?>
