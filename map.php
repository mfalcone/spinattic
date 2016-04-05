<?
require("inc/conex.inc");

if(!(strpos($_SERVER[REQUEST_URI], "worldmap"))){
	header("Location: http://".$_SERVER[HTTP_HOST]."/worldmap" ,TRUE,301);
}



$lastID = $_GET["lastID"];
$action = $_GET["action"];

$order = $_GET["o"];				
$page_title = "Spinattic | World Map";

	
if($order == ''){$order = 'priority';};


if ($action != 'getLastPosts') {
 


	$ip = $_SERVER['REMOTE_ADDR'];
	$id = $_GET["id"];

 require("inc/header.php");		
?>
		<script src="js/ajaxscroll.js"></script>

		<!-- google map -->
		<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
        <script type="text/javascript" src="js/infobox.js"></script>
        
        <script type="text/javascript" src="js/map.js"></script>
		
		<script type="text/javascript" src="js/markerclusterer.js"></script>
		
		<script type="text/javascript">
	        	$(document).ready(function(){
		        	
					initialize(2,'10','20');

					
					<?//Agrego los que tienen opcion "tour"
					$ssqlp = "SELECT tours.*, users.avatar, users.friendly_url as friendlyuser FROM tours, users where tours.iduser = users.id and tours.state = 'publish' and tours.privacy = '_public' and lat <> '' and lon <> '' and show_lat_lng = 'tour' ORDER BY ".$order." desc";
					$result = mysql_query($ssqlp);	
			
					while ($row = mysql_fetch_array($result)){
						$ssqlthumb = "SELECT * FROM panosxtour where idtour = ".$row["id"]." ORDER BY ord LIMIT 1";
						$resultthumb = mysql_query($ssqlthumb);	
						$rowthumb = mysql_fetch_array($resultthumb);
						
						$class_like_map = "";
						
						$ssqllike_map = "SELECT * FROM likes where idtour = ".$row["id"]." and iduser = ".$_SESSION["usr"];
						$resultlike_map = mysql_query($ssqllike_map);
						if($rowlike_map = mysql_fetch_array($resultlike_map)){
							$class_like_map = 'liked';
						}
						
						
						$icon = '';
			
						if($row["category"] != ''){
							$icon = $row["category"].'.png';
						}
						?>				 
						
						addMarker('<?echo $row["id"];?>', '<?echo $row["lat"];?>', '<?echo $row["lon"];?>', '<?echo addslashes($row["user"]);?>', '../images/users/<?echo $row["avatar"];?>', '<?echo $cdn;?>/panos/<?echo $rowthumb["idpano"];?>/pano.tiles/thumb200x100.jpg', '<?echo addslashes($row["title"]);?>', <?echo $row["views"];?>, <?echo $row["likes"];?>, '', false, '<?=$row["allow_votes"]?>', '<?=$class_like_map?>', '<?echo $row["iduser"];?>', '<?echo $icon;?>', '<?echo $row["friendlyuser"];?>', '<?echo $row["friendly_url"];?>', '');
			
					<?}?>

					<?//Agrego los que tienen opcion "scene"
							$ssqlp = "SELECT tours.*, panosxtour.idpano, panosxtour.lat as latitud, panosxtour.lng as longitud, panosxtour.name as scenename , users.avatar, users.friendly_url as friendlyuser FROM tours, users, panosxtour where panosxtour.idtour = tours.id and tours.iduser = users.id and tours.state = 'publish' and tours.privacy = '_public' and panosxtour.lat <> '' and panosxtour.lng <> '' and show_lat_lng = 'scene' ORDER BY ".$order." desc";
							$result = mysql_query($ssqlp);	
					
							while ($row = mysql_fetch_array($result)){
								
								$class_like_map = "";
								
								$ssqllike_map = "SELECT * FROM likes where idtour = ".$row["id"]." and iduser = ".$_SESSION["usr"];
								$resultlike_map = mysql_query($ssqllike_map);
								if($rowlike_map = mysql_fetch_array($resultlike_map)){
									$class_like_map = 'liked';
								}
								
								
								$icon = '';
					
								if($row["category"] != ''){
									$icon = $row["category"].'.png';
								}
								?>				 
								
								addMarker('<?echo $row["id"];?>', '<?echo $row["latitud"];?>', '<?echo $row["longitud"];?>', '<?echo addslashes($row["user"]);?>', '../images/users/<?echo $row["avatar"];?>', '<?echo $cdn;?>/panos/<?echo $row["idpano"];?>/pano.tiles/thumb200x100.jpg', '<?echo addslashes($row["title"]);?>', <?echo $row["views"];?>, <?echo $row["likes"];?>, '', false, '<?=$row["allow_votes"]?>', '<?=$class_like_map?>', '<?echo $row["iduser"];?>', '<?echo $icon;?>', '<?echo $row["friendlyuser"];?>', '<?echo $row["friendly_url"];?>', '<?echo $row["scenename"];?>');
					
							<?}?>
					

					var mcOptions = {styles: [{
						height: 45,
						url: "<?echo "http://".$_SERVER[HTTP_HOST];?>/images/icons/gmap/marker-group.png",
						width: 45,
						textColor: '#000',
						textSize: 16,
						},
						
						{
						height: 45,
						url: "<?echo "http://".$_SERVER[HTTP_HOST];?>/images/icons/gmap/marker-group.png",
						width: 45,
						textColor: '#000',
						textSize: 14,
						},
						
						{
						height: 45,
						url: "<?echo "http://".$_SERVER[HTTP_HOST];?>/images/icons/gmap/marker-group.png",
						width: 45,
						textColor: '#000',
						textSize: 12
						},
						
						{
						height: 45,
						url: "<?echo "http://".$_SERVER[HTTP_HOST];?>/images/icons/gmap/marker-group.png",
						width: 45,
						textColor: '#000',
						textSize: 10
						},
						
						{
						height: 45,
						url: "<?echo "http://".$_SERVER[HTTP_HOST];?>/images/icons/gmap/marker-group.png",
						width: 45,
						textColor: '#000',
						textSize: 8
						}]
					} 


			        var markerCluster = new MarkerClusterer(map, markers, mcOptions);
	
				});
		</script>
	
	
        <div>
        	<h1 class="map">World Map</h1>
		        <div class="mapa" id="map_canvas"></div>
                
                
                <div class="filter"  id="filter">
                	<ul>
                    	<li><a href="map.php?id=<?echo $id;?>&o=date" <?if ($order=="date"){echo 'class="active"';}?>>New</a></li>
                    	<li><a href="map.php?id=<?echo $id;?>&o=likes" <?if ($order=="likes"){echo 'class="active"';}?>>Top Rated</a></li>
                    	<li><a href="map.php?id=<?echo $id;?>&o=views" <?if ($order=="views"){echo 'class="active"';}?>>Popular</a></li>
                    </ul>
                </div>
		</div>
        <div class="wrapper">
			<div class="wrappper-posts">
			<?php 
			$first_show = 21;
			require 'php-stubs/get_first_tours.php';
			echo $postText;?>
			        
	        </div>
        </div>
			<!-- table>
			<input type='hidden' name='lat' id="lat" value="<?echo $lat;?>">
			<input type='hidden' name='long' id="long" value="<?echo $long;?>">
			<input type='hidden' name='likes' id="likes" value="<?echo $likes;?>">
			<input type='hidden' name='views' id="views" value="<?echo $views;?>">
			<input type='hidden' name='user' id="user" value="<?echo $user;?>">
			<input type='hidden' name='id' id="id" value="<?echo $id;?>">
			<input type='hidden' name='iduser' id="iduser" value="<?echo $iduser;?>">
			<input type='hidden' name='description' id="description" value="<?echo $description;?>"-->
    <?php require_once("inc/footer.php");?>
</html>

<?
}else{
	$last_show = 6;
	require 'php-stubs/get_last_tours.php';
}?>
