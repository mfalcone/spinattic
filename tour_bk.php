<?
$id = $_GET["id"];

require_once 'php-stubs/Mobile_Detect.php';
$detect = new Mobile_Detect;

if($detect->isMobile()) {
	header('Location:'."http://".$_SERVER[HTTP_HOST].'/tours/'.$id.'/');
	exit;
}

require_once("inc/auth.inc");
require_once("inc/conex.inc");
	
$lastID = $_GET["lastID"];
$action = $_GET["action"];

$order = $_GET["o"];				
	
if($order == ''){$order = 'id';};


if ($action != 'getLastPosts') {
 


	$ip = $_SERVER['REMOTE_ADDR'];
	

	if ($id !=''){
		$ssqlp = "SELECT *,DATE_FORMAT(date,'%d/%m/%Y') as fecha FROM tours where id = ".$id." and state = 'publish'";
		$result = mysql_query($ssqlp);
				
		$row = mysql_fetch_array($result);

        if (!$row) header("Location: 404.php");
        
        //si es privado y no soy el dueño, no lo pueden ver
        if ($row["privacy"] == '_private' && $row["iduser"] != $_SESSION["usr"]) header("Location: 404.php");
                
		$views = $row["views"];
		$likes = $row["likes"];
		$version_xml = $row["version_xml"];
		$title = $row["title"];
		$description = $row["description"];
		$lat = $row["lat"];
		$lon = $row["lon"];
		$location = $row["location"];
		$loc_country = $row["loc_country"];
		$date = $row["fecha"];
		$allow_comments = $row["allow_comments"];
		$allow_social = $row["allow_social"];
		$allow_embed = $row["allow_embed"];
		$allow_votes = $row["allow_votes"];

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
	
		$ssqlp = "SELECT * FROM views where idtour = ".$id." and ip like '%".$ip."%' and date > DATE_SUB(now(),INTERVAL 72 HOUR)";
	
		$result = mysql_query($ssqlp);
			
		if(!($row = mysql_fetch_array($result))){
			mysql_query("update tours_draft set views = views + 1 where id = ".$id);
			mysql_query("insert into views values (".$id.",' ".$ip."', now())");
			mysql_query("update tours set views = views + 1 where id = ".$id);
			$views++;
		}
		
	$meta_type='tour'; 
	$page_title = $title." by ".$user." | Spinattic.com";
	require("inc/header.php");

	}else{
		header("Location: index.php");
	}
?>
		<!-- google map -->
		<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
        <script type="text/javascript" src="js/infobox.js"></script>
		<script type="text/javascript" src="js/map.js"></script>

	    <script>
        	$(document).ready(function(){
            	<?php if($lat != '' && $lon != ''){?>
					initialize(17,'<?echo $lat;?>','<?echo $lon;?>');
            	<?php }else{?>
            		initialize(1,'0','0');
            	<?php }?>

<?		$ssqlpmap = "SELECT tours.*, users.avatar FROM tours, users where tours.iduser = users.id and tours.state = 'publish' and tours.privacy = '_public' and lat <> '' and lon <> '' ORDER BY ".$order." desc";
		//echo $ssqlpmap;
		//$ssqlp = "SELECT tours.*, users.avatar FROM tours, users where tours.iduser = users.id and tours.state = 'publish' and tours.privacy = '_public' ORDER BY ".$order." desc";
		$resultmap = mysql_query($ssqlpmap);	
		
		while ($rowmap = mysql_fetch_array($resultmap)){
			
			$ssqlthumb = "SELECT * FROM panosxtour where idtour = ".$rowmap["id"]." ORDER BY ord LIMIT 1";
			$resultthumb = mysql_query($ssqlthumb);	
			$rowthumb = mysql_fetch_array($resultthumb);
		
			$open = 'false';
			if($rowmap["id"] == $id)$open='true';
			?>

				addMarker('<?echo $rowmap["id"];?>', '<?echo $rowmap["lat"];?>', '<?echo $rowmap["lon"];?>', '<?echo $rowmap["user"];?>', '../images/users/<?echo $rowmap["avatar"];?>', '<?echo $cdn;?>/panos/<?echo $rowthumb["idpano"];?>/pano.tiles/thumb200x100.jpg', '<?echo $rowmap["title"];?>', <?echo $rowmap["views"];?>, <?echo $rowmap["likes"];?>, '', <?echo $open;?>, '<?=$allow_votes?>');

<?}?>
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
                        <a href="<?php echo 'https://pinterest.com/pin/create/button/?url=http://'.$_SERVER['HTTP_HOST'].$_SERVER[REQUEST_URI].'&media=&description='.$description;?>" class="pinterest" target="_blank"></a>
                        <br clear="all">
                    </div>
					<?}?>
                    <div class="content_map" id="map_canvas"></div>
	                <div id="pano" class="content_tour">
						<noscript><table style="width:100%;height:100%;"><tr style="valign:middle;"><td><div style="text-align:center;">ERROR:<br/><br/>Javascript not activated<br/><br/></div></td></tr></table></noscript>
						<script>
							embedpano({swf:"player/tour.swf", xml:"tours/<?echo $id;?>/tour<?echo $version_xml;?>.xml", target:"pano", html5:"auto", wmode:"opaque", passQueryParameters:true});
						</script>                	
					</div>
                </div>
                
                <div class="wrap-tour-element">
                

				<div class="wrap-tour-element-left">
                
                <div class="social-media">
                <?php if($allow_votes == 'on') { ?>
	                <a href="javascript:void(0)" id="like<?echo $id;?>" class="likes" onclick="like(<?echo $id;?>);"><?echo $likes;?></a>
	            <?}?>
	                <div class="views"><?echo $views;?></div>
                    <br clear="all">
                </div>
                
                
                <div class="titulo_tour">
                	<h2><?echo $title;?></h2>
                	

                    <div class="date"><?echo $date;?></div>
                    <?if($location != ''){?>
                    <p class="location">
							                    
							<?$pieces = explode(",", $location);
								foreach($pieces as $piece => $value){?>
	                        	<a href="search.php?search=<?echo trim($value);?>">
	                        		<?echo $value;?>
	                        	</a><font>,</font>
							 <?}?>                    
                    
                    </p>
                    <?}?>
                    <br clear="all">
                    <p style="margin-bottom:10px;"><?echo $description;?></p>
                </div>
                

				<?if($allow_comments == 'on'){
					require 'php-stubs/get_comments.php';
				}?>
				
                <br clear="all">

                
		</div>
        
        <br clear="all">
		</div>
        <div class="modulo_user">

                	<a href="profile.php?uid=<?php echo $iduser;?>" class="photo_user">
                    	<img width="100%" height="100%" src="images/users/<?echo $avatar;?>">
                    </a>
					<div class="name_user">                    
                    	<p><a href="profile.php?uid=<?php echo $iduser;?>" class="link_name"><?echo $user;?></a></p>
                        <?php get_pro();?>
                    </div>
                    <br clear="all">

                    
                    <?if($website != ''){?><a href="<?echo 'http://'.$website?>" class="url" target="_blank"><?echo $website?></a><?}?>
                    <?if($twitter != ''){?><a href="<?echo 'http://www.twitter.com/'.$twitter?>" class="twitter"><?echo str_replace('www.twitter.com/', '@', $twitter)?></a><?}?>
                    <?if($facebook != ''){?><a href="<?echo 'http://www.facebook.com/'.$facebook?>" class="facebook" target="_blank"><?echo str_replace('www.facebook.com/', '/', $facebook)?></a><?}?>
                    
					<?get_follow_btn($iduser, $logged);?>

<?
		$ssqlot = "SELECT *, DATE_FORMAT(date,'%d/%m/%Y') as fecha FROM tours where state = 'publish' and privacy = '_public' and id <> ".$id." and iduser = ".$iduser." ORDER BY id desc LIMIT 3";
		$resultot = mysql_query($ssqlot);	
		$cantregot = mysql_num_rows($resultot);
		
		if($cantregot > 0){?>		
                    
                    <h3>Other tours by <br><?echo $user;?></h3>
<?
		while ($rowot = mysql_fetch_array($resultot)){
			$ssqlthumb = "SELECT * FROM panosxtour where idtour = ".$rowot["id"]." ORDER BY ord LIMIT 1";
			$resultthumb = mysql_query($ssqlthumb);	
			$rowthumb = mysql_fetch_array($resultthumb);
?>
                    
                    <div class="other">
	                    <a href="tour.php?id=<?echo $rowot["id"];?>"><p><?echo $rowot["description"];?></p></a>
	                    <a href="tour.php?id=<?echo $rowot["id"];?>" class="thumb"><img src="<?echo $cdn;?>/panos/<?echo $rowthumb["idpano"];?>/pano.tiles/thumb200x100.jpg"></a>
	                    <div class="date"><?echo $rowot["fecha"];?></div>
	                    <p class="other-location">
                        	<a href="search.php?search=<?echo $rowot["location"];?>"><?echo $rowot["location"];?></a>
                            <br clear="all">
                        </p>
                        <br clear="all">
                    </div>
                    
                    
<?}?>
                    <h3><a href="profile.php?uid=<?php echo $iduser;?>">View all</a></h3>

<?}?>
                </div>
        <script>
        	jQuery(document).ready(function(){
				wrap_tour_element();
				jQuery(window).resize(function(){
					wrap_tour_element();
				});
			});
			
			
			function wrap_tour_element(){
				var ancho_content 		=	jQuery('.wrap-tour-element').outerWidth();
				var ancho_user 			= 	jQuery('.modulo_user').outerWidth();
				jQuery('.wrap-tour-element-left').css({'width': (ancho_content -ancho_user)});
  				/*jQuery('.coments_facebook iframe').css({'width': (ancho_content -ancho_user-50)});*/
			}
        </script>
        
        <div class="filter" id="filter">
            <ul>
                <li><a href="tour.php?id=<?echo $id;?>&o=date" <?if ($order=="date"){echo 'class="active"';}?>>New</a></li>
                <li><a href="tour.php?id=<?echo $id;?>&o=likes" <?if ($order=="likes"){echo 'class="active"';}?>>Top rated</a></li>
                <li><a href="tour.php?id=<?echo $id;?>&o=views" <?if ($order=="views"){echo 'class="active"';}?>>Popular</a></li>
            </ul>
        </div>
        <script src="js/ajaxscroll.js"></script>
        <div class="wrapper">
			<div class="wrappper-posts">
			<?php 
			$first_show = 21;
			require 'php-stubs/get_first_tours.php';
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
	<?php require_once("inc/footer.php");?>
</html>

<?
}else{
	$last_show = 6;
	require 'php-stubs/get_last_tours.php';
}?>