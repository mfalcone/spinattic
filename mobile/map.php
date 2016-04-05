<?
	require("../inc/conex.inc");


$lastID = $_GET["lastID"];
$action = $_GET["action"];

$page_title = "Spinattic - MAP";

	
if ($action != 'getLastPosts') {
 


	$ip = $_SERVER['REMOTE_ADDR'];
	$id = $_GET["id"];
	
	$ssqlp = "SELECT tours.*, users.avatar FROM tours, users where tours.iduser = users.id and tours.state = 'publish' and tours.privacy = '_public' and lat <> '' and lon <> '' ORDER BY id desc";
	$result = mysql_query($ssqlp);
		
?>
<!DOCTYPE HTML>
<html>
 <?require_once 'inc/head.inc';?>
 
 		<!-- google map -->
		<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
        <script type="text/javascript" src="../js/infobox.js"></script>
		<script type="text/javascript" src="../js/map_mobile.js"></script>

		
		<script type="text/javascript">
	
	        	$(document).ready(function(){
					initialize(2,'10','20');
					<?while ($row = mysql_fetch_array($result)){
						$ssqlthumb = "SELECT * FROM panosxtour where idtour = ".$row["id"]." ORDER BY ord LIMIT 1";
						$resultthumb = mysql_query($ssqlthumb);	
						$rowthumb = mysql_fetch_array($resultthumb);?>
						addMarker('<?echo $row["id"];?>', '<?echo $row["lat"];?>', '<?echo $row["lon"];?>', '<?echo $row["user"];?>', '../images/users/<?echo $row["avatar"];?>', '<?echo $cdn;?>/panos/<?echo $rowthumb["idpano"];?>/pano.tiles/thumb200x100.jpg', '<?echo $row["title"];?>', <?echo $row["views"];?>, <?echo $row["likes"];?>, '', false, '<?=$row["allow_votes"]?>');
					<?}?>
	
				});
	
		</script>
    <body>
            	<h1 class="map">World Map</h1>
		        <div class="mapa" id="map_canvas"></div>
<?require_once 'inc/nav.inc';?>
        <div class="wrapper">
			<?php 
			$first_show = 1;
			require 'php-stubs/get_first_tours.php';
			echo $postText;?>
			
        </div>
    </body>
</html>
<?
}else{
	$last_show = 6;
	require 'php-stubs/get_last_tours.php';
}?>