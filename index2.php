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
		
  
		<script type="text/javascript" src="js/jquery-1.2.6.pack.js"></script>	
			    
	    <script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
	    <script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
	    <!-- jquery ui -->        
	    <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
        
	    <script src="js/custom.js"></script>
	
		<script src="js/ajaxlike.js"></script>
		<script src="js/ajaxscroll.js"></script>

	    

	</head>
	<body onload="setupBlocks();">
	<script src="player/tour.js"></script>
		<div id="loading" class="loading" style="display: none;">
	        <div class="loading_img"></div>
        </div>
        

        
    	<div class="header">
			<?require("inc/head.inc");?>
        </div>


        <div class="nav">
				<?require("inc/nav2.inc");?>
        </div>
                
<?
		$ssqlp = "SELECT * FROM tours where state = 'publish' and privacy = '_public' ORDER BY RAND() LIMIT 1";
		$result = mysql_query($ssqlp);	
		$row = mysql_fetch_array($result);

		$ssqlp_usr = "SELECT * FROM users where id = '".$row["iduser"]."'";
		$result_usr = mysql_query($ssqlp_usr);
		$row_usr = mysql_fetch_array($result_usr);		
?>
        
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
                    <a href="#" class="text">
                    	<p>
							<?echo $row["title"];?>
                        </p>
                    </a>
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
