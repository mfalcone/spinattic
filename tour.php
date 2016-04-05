<?

if (!(isset($id) && $id != '')){
	$id = $_GET["id"];
}
require(realpath($_SERVER["DOCUMENT_ROOT"])."/inc/functions.inc");
require_once(realpath($_SERVER["DOCUMENT_ROOT"])."/inc/auth.inc");
require_once(realpath($_SERVER["DOCUMENT_ROOT"]).'/inc/conex.inc');



if ($fullscreen == 1){ //Tours en fullscreen (seteado en friendlyURLmanager.php)

			$ip = $_SERVER['REMOTE_ADDR'];
			
			$ssqlp = "SELECT * FROM tours where id = ".$id." and state = 'publish' and (privacy = '_public' or privacy = '_notlisted')";
			$result = mysql_query($ssqlp);
			
			if(!($row = mysql_fetch_array($result))){
				header("Location: http://".$_SERVER[HTTP_HOST]);
			}
			
			
			$user = $row["user"];
			$title = $row["title"];
			$description = $row["description"];
			$meta_type='tour';
			
			$page_title = $title." by ".$user." | Spinattic.com";
			
			
			$ssqlp = "SELECT * FROM views where idtour = ".$id." and ip like '%".$ip."%' and date > DATE_SUB(now(),INTERVAL 72 HOUR)";
		
			$result = mysql_query($ssqlp);
				
			if(!($row = mysql_fetch_array($result))){
				mysql_query("update tours_draft set views = views + 1 where id = ".$id);
				mysql_query("insert into views values (".$id.",'".$ip."', now())");
				mysql_query("update tours set views = views + 1 where id = ".$id);
			}			
			
			
			?>
			<!DOCTYPE html>
			<html>
			<head>
				<meta name="viewport" content="target-densitydpi=device-dpi, width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />
				<meta name="apple-mobile-web-app-capable" content="yes" />
				<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
				<title><?php echo $page_title;?></title>
				
				<?php 
				
				require_once(realpath($_SERVER["DOCUMENT_ROOT"])."/inc/fk-meta.inc");
				
				?>
	
				<style>
					html { height:100%; }
					body { height:100%; overflow: hidden; margin:0; padding:0; font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#FFFFFF; background-color:#000000; }
					a{ color:#AAAAAA; text-decoration:underline; }
					a:hover{ color:#FFFFFF; text-decoration:underline; }
				</style>
				
			<?if($environment=='prod'){
				require_once(realpath($_SERVER["DOCUMENT_ROOT"])."/inc/ganalitycs.inc");
			}?>					
				
			</head>
			<body>
	
			<script src="http://<?php echo $_SERVER[HTTP_HOST];?>/player/tour.js"></script>
	
			<div id="pano" style="width:100%; height:100%;">
				<noscript><table style="width:100%;height:100%;"><tr style="valign:middle;"><td><div style="text-align:center;">ERROR:<br/><br/>Javascript not activated<br/><br/></div></td></tr></table></noscript>
				<script>
					embedpano({swf:"http://<?php echo $_SERVER[HTTP_HOST];?>/player/tour.swf", xml:"http://<?php echo $_SERVER[HTTP_HOST];?>/customizer/data/xml.php?id=<?echo $id;?>", target:"pano", html5:"prefer", wmode:"opaque", passQueryParameters:true});
				</script>
			</div>
	
			</body>
			</html>

<?}else{ //Tour.php (embebido)


		
	$lastID = $_GET["lastID"];
	$action = $_GET["action"];

	$order = $_GET["o"];				


	if ($action != 'getLastPosts') {
	 


		$ip = $_SERVER['REMOTE_ADDR'];
		

		if ($id !=''){
			
			$ssqlp = "SELECT tours.*, users.friendly_url as friendlyuser ,DATE_FORMAT(date,'%d/%m/%Y') as fecha FROM tours, users where tours.iduser = users.id and tours.id = ".$id;
			$result = mysql_query($ssqlp);
			
			if(!($row = mysql_fetch_array($result))){
				//Si no existe, puede estar en draft:
				$ssqlp = "SELECT *,DATE_FORMAT(date,'%d/%m/%Y') as fecha FROM tours_draft where id = ".$id;
				$result = mysql_query($ssqlp);
				if($row = mysql_fetch_array($result)){			
					header("Location: 404-draft.php");
				}else{
					header("Location: 404-deleted.php");
				}
			}else{
				//Si existe puede ser privado
				if ($row["privacy"] == '_private' && $row["iduser"] != $_SESSION["usr"]) header("Location: 404-private.php");
			}


			
			//si es privado y no soy el dueño, no lo pueden ver
			
					
			$views = $row["views"];
			$likes = $row["likes"];
			$version_xml = $row["version_xml"];
			$title = $row["title"];
			$description = $row["description"];
			$lat = $row["lat"];
			$lon = $row["lon"];
			$location = $row["location"];
			$loc_country = $row["loc_country"];
			$category = $row["category"];
			$tags = $row["tags"];
			$date = $row["fecha"];
			$allow_comments = $row["allow_comments"];
			$allow_social = $row["allow_social"];
			$allow_embed = $row["allow_embed"];
			$allow_votes = $row["allow_votes"];
			$thumb_path = $row["tour_thumb_path"];
			$friendlyuser = $row["friendlyuser"];
			$friendly = $row["friendly_url"];
			
			if($in_friendly != 1){ //Variable definida en friendlyURLmanager.php para ver si estoy llegando desde la friendly o no
				header("Location: http://".$_SERVER[HTTP_HOST]."/".$friendlyuser."/".$friendly ,TRUE,301);
			}
			
			
	//Liked me?
			
			$class_like = "";
			
			$ssqllike = "SELECT * FROM likes where idtour = ".$row["id"]." and iduser = ".$_SESSION["usr"];
			$resultlike = mysql_query($ssqllike);
			if($rowlike = mysql_fetch_array($resultlike)){
				$class_like = 'liked';
			}		

	//User Data

			$ssqlu = "SELECT * FROM users where id = ".$row["iduser"];
			$resultu = mysql_query($ssqlu);
			$rowu = mysql_fetch_array($resultu);
		
			$user = $rowu["username"];
			$iduser = $rowu["id"];
			$website = $rowu["website"];
			$facebook = $rowu["facebook"];
			$twitter  = $rowu["twitter"];
			$avatar = $rowu["avatar"];
			$friendlyuser = $rowu["friendly_url"];
		
			$ssqlp = "SELECT * FROM views where idtour = ".$id." and ip like '%".$ip."%' and date > DATE_SUB(now(),INTERVAL 72 HOUR)";
		
			$result = mysql_query($ssqlp);
				
			if(!($row = mysql_fetch_array($result))){
				mysql_query("update tours_draft set views = views + 1 where id = ".$id);
				mysql_query("insert into views values (".$id.",' ".$ip."', now())");
				mysql_query("update tours set views = views + 1 where id = ".$id);
				$views++;
				
				//Buscco el valor maximo de views
				$result_max = mysql_query("select max(amount) as max from views_priority_steps where factor = 1");
				$row = mysql_fetch_array($result_max);
				$max = $row["max"];
				
				
				//Busco el valor del factor
				$result_factor = mysql_query("select amount from views_priority_steps where factor = 0");
				$row = mysql_fetch_array($result_factor);
				$factor = $row["amount"];
				
				if ($views > $max){
					//Si el valor de views es $factor veces cambio el valor de priority en la tabla tours
					if (($views-$max)/$factor == intval(($views-$max)/$factor)){
						setMaxPriority($id);
					}
				}else{
					//Si el valor de views es igual a alguno de los valores de views_priority_steps cambio el valor de priority en la tabla tours
					$ssqlp_views_steps = "select * from views_priority_steps where amount = ".$views." and factor = 1";
					$result_views = mysql_query($ssqlp_views_steps);
				
					if (mysql_num_rows($result_views)){
						setMaxPriority($id);
					}
				}
				
			}
			
		$meta_type='tour'; 
		$page_title = $title." by ".$user." | Spinattic.com";
		
		if($description != ''){
			$description_head = $description. " | Spinattic.com 360 virtual tours.";
		}else{
			$description_head = "Create, customize and share virtual tours with 360 panoramas from your 360 camera";
		}
		
		require(realpath($_SERVER["DOCUMENT_ROOT"])."/inc/header.php");

		}else{
			header("Location: index.php");
		}
	?>
			<!-- google map -->
			<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
			<script type="text/javascript" src="http://<?php echo $_SERVER[HTTP_HOST];?>/js/infobox.js"></script>
			<script type="text/javascript" src="http://<?php echo $_SERVER[HTTP_HOST];?>/js/map.js"></script>
			
			<script type="text/javascript" src="http://<?php echo $_SERVER[HTTP_HOST];?>/js/markerclusterer.js"></script>

			<script>
				$(document).ready(function(){

					<?php if($lat != '' && $lon != ''){?>
						initialize(17,'<?echo $lat;?>','<?echo $lon;?>');
					<?php }else{?>
						initialize(1,'0','0');
					<?php }?>

	<?		
	
			//Agrego los que tienen opcion "tour"
			$ssqlpmap = "SELECT tours.*, users.avatar, users.friendly_url as friendlyuser FROM tours, users where tours.iduser = users.id and tours.state = 'publish' and tours.privacy = '_public' and lat <> '' and lon <> '' and show_lat_lng = 'tour'";
		
			//$ssqlpmap = "SELECT tours.*, users.avatar, users.nickname FROM tours, users where tours.iduser = users.id and tours.state = 'publish' and tours.privacy = '_public' and lat <> '' and lon <> ''";
			$resultmap = mysql_query($ssqlpmap);	
			
			while ($rowmap = mysql_fetch_array($resultmap)){
				
				/*
				$ssqlthumb = "SELECT * FROM panosxtour where idtour = ".$rowmap["id"]." ORDER BY ord LIMIT 1";
				$resultthumb = mysql_query($ssqlthumb);	
				$rowthumb = mysql_fetch_array($resultthumb);
				*/
				
				$open = 'false';
				if($rowmap["id"] == $id)$open='true';
				
				$class_like_map = "";
				
				$ssqllike_map = "SELECT * FROM likes where idtour = ".$rowmap["id"]." and iduser = ".$_SESSION["usr"];
				$resultlike_map = mysql_query($ssqllike_map);
				if($rowlike_map = mysql_fetch_array($resultlike_map)){
					$class_like_map = 'liked';
				}			

				
				$icon = '';
				
				if($rowmap["category"] != ''){
					$icon = $rowmap["category"].'.png';
				}			
				
				?>

					addMarker('<?echo $rowmap["id"];?>', '<?echo $rowmap["lat"];?>', '<?echo $rowmap["lon"];?>', '<?echo addslashes($rowmap["user"]);?>', '../images/users/<?echo $rowmap["avatar"];?>', '<?echo $rowmap["tour_thumb_path"];?>thumb200x100.jpg', '<?echo addslashes($rowmap["title"]);?>', <?echo $rowmap["views"];?>, <?echo $rowmap["likes"];?>, '', <?echo $open;?>, '<?=$allow_votes?>', '<?=$class_like_map?>', '<?echo $rowmap["iduser"];?>', '<?echo $icon;?>', '<?echo $rowmap["friendlyuser"];?>', '<?echo $rowmap["friendly_url"];?>', '');

				<?}?>

				<?//Agrego los que tienen opcion "scene"
						$ssqlpmap = "SELECT tours.*, panosxtour.idpano, panosxtour.lat as latitud, panosxtour.lng as longitud, panosxtour.name as scenename , users.avatar, users.friendly_url as friendlyuser FROM tours, users, panosxtour where panosxtour.idtour = tours.id and tours.iduser = users.id and tours.state = 'publish' and tours.privacy = '_public' and panosxtour.lat <> '' and panosxtour.lng <> '' and show_lat_lng = 'scene'";
						$resultmap = mysql_query($ssqlpmap);	
				
						while ($rowmap = mysql_fetch_array($resultmap)){
							
							$class_like_map = "";
							
							$ssqllike_map = "SELECT * FROM likes where idtour = ".$rowmap["id"]." and iduser = ".$_SESSION["usr"];
							$resultlike_map = mysql_query($ssqllike_map);
							if($rowlike_map = mysql_fetch_array($resultlike_map)){
								$class_like_map = 'liked';
							}
							
							
							$icon = '';
				
							if($row["category"] != ''){
								$icon = $row["category"].'.png';
							}
							?>				 
							
							addMarker('<?echo $rowmap["id"];?>', '<?echo $rowmap["latitud"];?>', '<?echo $rowmap["longitud"];?>', '<?echo addslashes($rowmap["user"]);?>', '../images/users/<?echo $rowmap["avatar"];?>', '<?echo $cdn;?>/panos/<?echo $rowmap["idpano"];?>/pano.tiles/thumb200x100.jpg', '<?echo addslashes($rowmap["title"]);?>', <?echo $rowmap["views"];?>, <?echo $rowmap["likes"];?>, '', false, '<?=$rowmap["allow_votes"]?>', '<?=$class_like_map?>', '<?echo $rowmap["iduser"];?>', '<?echo $icon;?>', '<?echo $rowmap["friendlyuser"];?>', '<?echo $rowmap["friendly_url"];?>', '<?echo $rowmap["scenename"];?>');
				
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
			
			<script type="text/javascript">
			function analiza(evt)
				  {
					 var charCode = (evt.which) ? evt.which : event.keyCode
					 if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 46)
						return false;
					 
					return true;
				  }

			function embed(evt){
				document.getElementById('inp_embed').value = '<iframe width="' +  document.getElementById('w_embed').value + '" height="' + document.getElementById('h_embed').value + '" src="http://<?echo $_SERVER['HTTP_HOST'].'/tours/'.$id;?>" frameborder="0" allowfullscreen></iframe>';
			}


			function copyToClipboard (text) {

				document.getElementById('inp_embed').select();
				window.clipboardData.setData(text,str);
	//			window.prompt ("Copy to clipboard: Ctrl+C, Enter", text);
			}
			</script>
		
			<div id="fb-root"></div>

			<script>(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = "//connect.facebook.net/es_ES/all.js#xfbml=1";
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));</script>	
					
					<div class="tour">
						<div class="nav">
							<a href="#" class="btn_map"></a>
							<a href="#" class="btn_embed"></a>
							<a href="#" class="btn_share"></a>
						</div>
						<!--embed-->
					<?if($allow_embed == 'on'){?>
						<div class="embed">
							<p>Embed</p>
							<p class="info-embed">
								Copy and Paste this code in your page to embed this virtual tour.
							</p>
							<br clear="all">
						  <input value='<iframe width="640" height="480" src="http://<?echo $_SERVER[HTTP_HOST].'/tours/'.$id;?>" frameborder="0" allowfullscreen></iframe>' id="inp_embed">
						  <br clear="all">
						  <div class="size_embed">
							  <label>width:<input type="text" value="640" onkeyup="return embed(event);" onkeypress="return analiza(event);" id="w_embed"></label>
							  <label>height:<input type="text" value="480" onkeyup="return embed(event);" id="h_embed"></label>
							  
						  </div>
						</div>
						
					  <?}?>
					  <?if($allow_social == 'on'){?>
						<!--share-->
						<div class="share">
							<a href="<?php echo 'https://twitter.com/home?status=http://'.$_SERVER['HTTP_HOST'].$_SERVER[REQUEST_URI];?>" class="twitter" target="_blank"></a>
							<a href="<?php echo 'https://www.facebook.com/sharer/sharer.php?u=http://'.$_SERVER['HTTP_HOST'].$_SERVER[REQUEST_URI];?>" class="facebook" target="_blank"></a>
							<a href="<?php echo 'https://plus.google.com/share?url=http://'.$_SERVER['HTTP_HOST'].$_SERVER[REQUEST_URI];?>" class="plus" target="_blank"></a>
							<a href="<?php echo 'https://pinterest.com/pin/create/button/?url=http://'.$_SERVER['HTTP_HOST'].$_SERVER[REQUEST_URI].'&media='.$thumb_path.'thumb900x450.jpg&description='.$description;?>" class="pinterest" target="_blank"></a>
							<br clear="all">
						</div>
						<?}?>
						<div class="content_map" id="map_canvas"></div>
						<div id="pano" class="content_tour">
							<noscript><table style="width:100%;height:100%;"><tr style="valign:middle;"><td><div style="text-align:center;">ERROR:<br/><br/>Javascript not activated<br/><br/></div></td></tr></table></noscript>
							<script>
								embedpano({swf:"http://<?php echo $_SERVER[HTTP_HOST];?>/player/tour.swf", xml:"http://<?php echo $_SERVER[HTTP_HOST];?>/customizer/data/xml.php?id=<?echo $id;?>", target:"pano", html5:"prefer", wmode:"opaque", passQueryParameters:true});
							</script>                	
						</div>
					</div>
					
					<div class="wrap-tour-element">
					

					<div class="wrap-tour-element-left">
					
					<div class="social-media">
					<?php if($allow_votes == 'on') { ?>
						<a href="javascript:void(0)" id="like<?echo $id;?>" class="like<?echo $id;?> likes <?echo $class_like;?>" onclick="like(<?echo $id;?>);"><?echo $likes;?></a>
					<?}?>
						<div class="views"><?echo $views;?></div>
						<br clear="all">
					</div>
					
					
					<div class="titulo_tour">
						<h1><?echo $title;?></h1>
						

						<div class="date"><?echo $date;?></div>
						<?if($location != ''){?>
						<p class="location">
													
								<?$pieces = explode(",", $location);
									foreach($pieces as $piece => $value){?>
									<a href="http://<?php echo $_SERVER[HTTP_HOST];?>/search.php?search=<?echo trim($value);?>">
										<?echo $value;?>
									</a>,
								 <?}?>                    
						
						</p>
					   <?}?>
						
						<?php if ($category != ''){?>
						<p class="category">
							<span class="title">Category:</span>
							<a href="http://<?php echo $_SERVER[HTTP_HOST];?>/search.php?c=1&search=<?php echo $category;?>"><?php echo $category;?></a>
						</p>
						<?php }?>
						
						<?php if ($tags != ''){
							$the_tags = explode(',', $tags)?>
							<p class="tags">
								<span class="title">Tags:</span>
								
									<?php foreach ($the_tags as $the_tag){
										$tab_html .= '<a href="http://'.$_SERVER[HTTP_HOST].'search.php?c=1&search='.$the_tag.'">'.$the_tag.'</a>, ';
									}
									$tab_html = substr($tab_html, 0, strlen($tab_html)-2);
									echo $tab_html;?>
									
							</p>
						<?php }?>
						
						<p class="description"><?echo $description;?></p>
					</div>
					

					<?if($allow_comments == 'on'){
						require realpath($_SERVER["DOCUMENT_ROOT"]).'/php-stubs/get_comments.php';
					}?>
					
					<br clear="all">

					
			</div>
			
			<br clear="all">
			</div>
			<div class="modulo_user">

						<a href="http://<?php echo $_SERVER[HTTP_HOST];?>/<?php echo $friendlyuser;?>" class="photo_user">
							<img width="100%" height="100%" src="http://<?php echo $_SERVER[HTTP_HOST];?>/images/users/<?echo $avatar;?>">
						</a>
						<div class="name_user">                    
							<p><a href="http://<?php echo $_SERVER[HTTP_HOST];?>/<?php echo $friendlyuser;?>" class="link_name"><?echo $user;?></a></p>
							<?php get_pro();?>
						</div>
						<br clear="all">

						
						<?if($website != ''){?><a href="<?echo 'http://'.$website?>" class="url" target="_blank"><?echo $website?></a><?}?>
						<?if($twitter != ''){?><a href="<?echo 'http://www.twitter.com/'.$twitter?>" class="twitter"><?echo str_replace('www.twitter.com/', '@', $twitter)?></a><?}?>
						<?if($facebook != ''){?><a href="<?echo 'http://www.facebook.com/'.$facebook?>" class="facebook" target="_blank"><?echo str_replace('www.facebook.com/', '/', $facebook)?></a><?}?>
						
						<?get_follow_btn($iduser, $logged);?>

	<?
			$ssqlot = "SELECT tours.*, DATE_FORMAT(date,'%d/%m/%Y') as fecha, users.friendly_url as friendlyuser FROM tours, users where tours.state = 'publish' and tours.privacy = '_public' and tours.id <> ".$id." and tours.iduser = ".$iduser." and tours.iduser = users.id ORDER BY id desc LIMIT 3";
			$resultot = mysql_query($ssqlot);	
			$cantregot = mysql_num_rows($resultot);
			
			if($cantregot > 0){?>		
						
						<h3>Other tours by <br><?echo $user;?></h3>
	<?
			while ($rowot = mysql_fetch_array($resultot)){
				/*
				$ssqlthumb = "SELECT * FROM panosxtour where idtour = ".$rowot["id"]." ORDER BY ord LIMIT 1";
				$resultthumb = mysql_query($ssqlthumb);	
				$rowthumb = mysql_fetch_array($resultthumb);
				*/
	?>
						
						<div class="other">
							<a href="http://<?php echo $_SERVER[HTTP_HOST];?>/<?echo $rowot["friendlyuser"];?>/<?echo $rowot["friendly_url"];?>"><p><?echo $rowot["title"];?></p></a>
							<a href="http://<?php echo $_SERVER[HTTP_HOST];?>/<?echo $rowot["friendlyuser"];?>/<?echo $rowot["friendly_url"];?>" class="thumb"><img src="<?echo $rowot["tour_thumb_path"];?>thumb200x100.jpg"></a>
							<div class="date"><?echo $rowot["fecha"];?></div>
							<p class="other-location">
								<a href="http://<?php echo $_SERVER[HTTP_HOST];?>/search.php?search=<?echo $rowot["location"];?>"><?echo $rowot["location"];?></a>
								<br clear="all">
							</p>
							<br clear="all">
						</div>
						
						
	<?}?>
						<h3><a href="http://<?php echo $_SERVER[HTTP_HOST];?>/<?php echo $friendlyuser;?>">View all</a></h3>

	<?}?>
					</div>
			<script>
				jQuery(document).ready(function(){
					wrap_tour_element();
					jQuery(window).resize(function(){
						wrap_tour_element();
					});
					$(window).scroll(fixDiv);
					$(".modulo_user").mCustomScrollbar({
						theme:"minimal-dark",
						scrollInertia:200
					});	

					$(".modulo_user").on({
						mouseenter:function(){
							 $('body').on({
							   'mousewheel': function(e) {
							   e.preventDefault();
							   e.stopPropagation();
							   }
						   });
						},
						mouseleave:function(){
							 $('body').unbind('mousewheel');
						}
					})

				});
				
				
				function wrap_tour_element(){
					var ancho_content 		=	jQuery('.wrap-tour-element').outerWidth();
					var ancho_user 			= 	jQuery('.modulo_user').outerWidth();
					jQuery('.wrap-tour-element-left').css({'width': (ancho_content -ancho_user)});
					/*jQuery('.coments_facebook iframe').css({'width': (ancho_content -ancho_user-50)});*/
				}

				function fixDiv() {
					var $cache = $('.modulo_user'); 
					toppos = $(".tour").height();
					moduleHeight = $(window).height()-$(".header").height()-$cache.css("padding-top").replace("px", "")-$cache.css("padding-bottom").replace("px", "")
					if ($(window).scrollTop() > toppos) {
						$cache.css({'position': 'fixed', 'top': '0','height':moduleHeight+'px'}); 
					}else{
						$cache.css({'position': 'absolute', 'top': toppos+'px', 'height':moduleHeight+'px'});
					}
				}
			</script>
			
			<div class="filter toursontour" id="filter">
				<ul>
					<li><a href="" data-order = "date" data-first_show="21" class="order_this">New</a></li>
					<li><a href="" data-order = "likes" data-first_show="21" class="order_this">Top rated</a></li>
					<li><a href="" data-order = "views" data-first_show="21" class="order_this">Popular</a></li>
					<?php /*?>
					<li><a href="http://<?php echo $_SERVER[HTTP_HOST];?>/tour.php?id=<?echo $id;?>&o=date" <?if ($order=="date"){echo 'class="active"';}?>>New</a></li>
					<li><a href="http://<?php echo $_SERVER[HTTP_HOST];?>/tour.php?id=<?echo $id;?>&o=likes" <?if ($order=="likes"){echo 'class="active"';}?>>Top rated</a></li>
					<li><a href="http://<?php echo $_SERVER[HTTP_HOST];?>/tour.php?id=<?echo $id;?>&o=views" <?if ($order=="views"){echo 'class="active"';}?>>Popular</a></li>
					<?*/?>
				</ul>
			</div>
			<script src="http://<?php echo $_SERVER[HTTP_HOST];?>/js/ajaxscrolltour.js"></script>
			<div class="wrapper toursontour">
				<div class="wrappper-posts" id="grid_target">
					<?php 
					$first_show = 21;
					require realpath($_SERVER["DOCUMENT_ROOT"]).'/php-stubs/get_first_tours.php';
					echo $postText;?>
				</div>
					
			</div>
				<input type='hidden' name='lat' id="lat" value="<?echo $lat;?>">
				<input type='hidden' name='long' id="long" value="<?echo $lon;?>">
				<input type='hidden' name='likes' id="likes" value="<?echo $likes;?>">
				<input type='hidden' name='views' id="views" value="<?echo $views;?>">
				<input type='hidden' name='user' id="user" value="<?echo $user;?>">
				<input type='hidden' name='id' id="id" value="<?echo $id;?>">
				<input type='hidden' name='iduser' id="iduser" value="<?echo $iduser;?>">
				<input type='hidden' name='title' id="title" value="<?echo $title;?>">
			
				
		<?php require_once(realpath($_SERVER["DOCUMENT_ROOT"]).'/inc/footer.php');?>
	</html>

	<?
	}else{
		$last_show = 6;
		require realpath($_SERVER["DOCUMENT_ROOT"]).'/php-stubs/get_last_tours.php';
	}
}?>

