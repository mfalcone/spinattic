<?php
ini_set("display_errors", 0);
error_reporting(E_ALL ^ E_NOTICE);
	$restrict = 1;
  
	require_once("inc/conex.inc");
	require_once("inc/auth.inc");
	
	
	$is_new         = true;
	
	$id             = null;
	$url            = null;
	$title          = null;
	$description    = null;
	$lat            = null;
	$lon            = null;
	$location       = null;
	$privacy        = null;
	$category       = null;
	$tags           = null;
	$skin_id		= null;
	$allow_comments = null;
	$allow_social   = null;
	$allow_embed    = null;
	$allow_votes    = null;
	$enable_avatar	= null;
	$enable_title	= null;
	$thumb_width 	= 200;
	$thumb_height 	= 100;
	$thumb_margin 	= 10;	
	
	if ((isset($_GET['d']) && $_GET['d']=='1') || $_GET['id'] == ''){
		$draft_subscript='_draft'; 
	}
	
	if (isset($_GET['id']) && $_GET['id'])
	{
		$id = $_GET["id"];
		
		$ssqlp = "SELECT *,DATE_FORMAT(date,'%d/%m/%Y') as fecha FROM tours".$draft_subscript." where iduser = '".$_SESSION["usr"]."' and id = ".$id;
		$result = mysql_query($ssqlp);

		if (!($row = mysql_fetch_array($result)))
		{
				header('Location: index.php');
		};

		$url = 'http://'.$row["friendly_url"];
		$title = $row["title"];
		$description = $row["description"];
		$lat = $row["lat"];
		$lon = $row["lon"];
		$version_xml = $row["version_xml"];
		$location = $row["location"];
		$privacy = $row["privacy"];
		$category = $row["category"];
		$tags = $row["tags"];
		$allow_comments = $row["allow_comments"];
		$allow_social = $row["allow_social"];
		$allow_embed = $row["allow_embed"];
		$allow_votes = $row["allow_votes"];
		$enable_avatar = $row["enable_avatar"];
		$enable_title = $row["enable_title"];
		$state = $row["state"];
		$skin_id = $row["skin_id"];
		$thumb_width = $row["thumb_width"];
		$thumb_height = $row["thumb_height"];
		$thumb_margin = $row["thumb_margin"];
		
		$ssqlp = "SELECT scenes.*, panos.name AS pano_name FROM panosxtour".$draft_subscript." scenes LEFT JOIN panos ON scenes.idpano = panos.id where idtour = ".$id." ORDER BY scenes.ord ASC";
		$panos_result = mysql_query($ssqlp);
		
		$is_new = false;
		
		//Reseteo todas las colas del tour por si quedó residuo
		mysql_query("update general_process_log set queue_pos = 0 where tour_id = '".$id."'");
		
	}


 if ($is_new) {
	$page_title = " Create new Virtual Tour | Spinattic";
 }else{
	$page_title = "Edit: ".$title ." | Spinattic";
 }      

	require("inc/header.php");
?>

		<!-- jquery ui -->        
		<link rel="stylesheet" type="text/css" media="screen" href="css/tabs.css" />

	   <!-- tags input-->
		<link rel="stylesheet" type="text/css" href="css/jquery.tagsinput.css" />	
		
		<script type="text/javascript" src="js/jquery.tagsinput.js"></script>
		<link rel="stylesheet" type="text/css" href="css/tipsy.css" />	
		<script type="text/javascript" src="js/jquery.tipsy.js"></script>                
		<script src="js/jquery.filedrop.js"></script>	  
		<script src="js/jquery.filedrop.start.js?r=<?php echo rand();?>"></script>
		<script src="js/core-actions.js"></script>	
		<script src="js/core.tour-edit.inc.js"></script>	    
	


	<style type="text/css">
		#map_canvas {height:400px;width:460px}
	</style>
	<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&language=en"></script>

	<script src="js/maploc.js"></script>        
 
		
	<script type="text/javascript">
		var gNew_tour;
		var gTour_id;
		var overlay;
		
		jQuery(document).ready(function()
		{ 
			<?php if ($is_new) : ?> 
											 
				gNew_tour = true;   
				mixpanel.track("Create New Tour");

				
			<?php else : ?>
			
				jQuery('.uploading_pano').css('display', 'block');                
				jQuery('#drop-zone').css('margin-top', '0');
				
				jQuery('#tabs').css('display', 'block');
				gNew_tour = false;
				gTour_id = <?=$id?>;
				
				initMap();

				<?php if($lat != '' && $lon != ''){?>
				addMarker('<?echo $lat;?>', '<?echo $lon;?>');
				<?php }?>
				
				
				
				jQuery('.scene-remove').click(function()
				{
					var data         = 'scene-id='+jQuery(this).attr('data-id');

					var container   = jQuery(this).parent();

					var params = {
								container  : container
							};

					launch_popup('.confirm-action', data, 'sceneRemove', removeCallback, params);                        

					return false;


					//CoreActions.sceneRemove(data, removeCallback);
				});
				
			<?php endif; ?>

			$(".wrapper-edit-tour").height($(window).height()-281)
		});
		
 
	</script>
	
	</head>
<!--popup confirm action-->
<div class="overlay confirm-action" style="display:none;">
	<div class="pop">
		<a href="#" class="closed"></a>
		<h2>Virtual Tours Manager</h2>
		<div class="content_pop">
			<form class="pop-up">
				<label>
					<p>Are you sure you want to delete this scene?</p>                
				</label>                        
				<div class="content-btn-pop">
					<a href="#" class="grey-button border-radius-4" onclick="hide_popup();">NO</a>
					<a href="#" class="red-button border-radius-4 save-button">YES</a>
				</div>
			</form>
		</div>
	</div>
</div>    
		   



<!-- HOTSPOT TOOL WINDOW -->
<div style="display:none;" class="overlay hotspot-tool">
	<div class="hotspot-window">	           
		<div class="thumb-head-set">
			<img id="editor-thumb-scene" src="" width="90">
			<h3></h3>
			<br clear="all">
		</div>
		<a class="blue-buttom set-startup-view" href="#">Set as startup view</a>
		<a class="save-close" href="#">
			DONE
		</a>   
		<div class="content-set">
			<div class="bar">
				<div class="controls">
					<h5 style="margin-top: 0;">Add hotspots by clicking on the icons</h5>

					<a href="#" class="add-hotspot hslink" onclick="newHotspot('link');" title="Add Link"></a>
					<a href="#" class="add-hotspot hsinfo" onclick="newHotspot('info');" title="Add Info"></a>
					<a href="#" class="add-hotspot hsmedia" onclick="newHotspot('media');" title="Add Photo"></a>
				</div>  

				<div class="hotspots-list">   
					<h5>Hotspots in this scene</h5>

					<img id="hs-loader" src="js/hotspot-tool/loader.gif" style="display:none;width:16px;margin:0 auto;">
					<div class="hs-collection"><br><br><br><br><br><br><br><br><br></div>
				</div>
			</div>                    
			<div id="krpano-container"></div>                    
		</div>
		<div class="foot-set-tour">

		</div>
	</div>
</div>
		 
<link rel="stylesheet" type="text/css" media="screen" href="css/hotspots.css" />
<script type="text/javascript" src="js/hashtable.js"></script>
<script src="js/SimpleAjaxUploader.js"></script>
<script src="js/hotspot-tool/embedpano.js"></script>
<script src="js/hotspot-tool/hotspot-tool.js?r=<?php echo rand();?>"></script>
<!--script src="js/hotspot-tool/swfkrpano.js"></script-->

<link rel="stylesheet" href="css/jquery.jscrollpane.css" type="text/css" media="screen" />
<script type="text/javascript" src="js/jquery.mousewheel.js"></script>
<script type="text/javascript" src="js/jquery.jscrollpane.min.js"></script>    
<script type="text/javascript" src="js/jquery.ddslick.js"></script>    

	
<script>
// <![CDATA[
	var base_path = 'js/hotspot-tool/';
	var xml_tool_path = 'js/hotspot-tool/toolset.xml';            
	
	var xml_version = '<?=$version_xml?>';
	
	var swf_path = "player/tour.swf";
	var html5_param = 'auto';
	
// ]]>
</script>         



			
		
		<!--pop Panorama Collection-->
		<div class="overlay panocollection">
			<div class="pop">
				<a href="#" class="closed"></a>
				<h2>Include from panos manager</h2>
					<div class="message_box">
						<p>Select from uploaded panoramas and include them in the tour.</p>
					</div>
					<div class="content_pop">
				
			<?
					$ssqlpfc = "SELECT *, DATE_FORMAT(date,'%b %d %Y %h:%i %p') as fecha FROM panos where user = '".$_SESSION["usr"]."' and state = 1 ORDER BY id desc";
					$resultpfc = mysql_query($ssqlpfc);
					while($rowpfc = mysql_fetch_array($resultpfc)){
			?>
							
							<!-- pano -->
							<div class="pano-item pano-item-manager">
								<label class="check_action">
									<input class="form-user border-radius-4" type="checkbox" name="sel[]" value="<?echo $rowpfc["id"];?>">
								</label>
								<div class="thumb-pano">
									<img src="<?echo $cdn;?>/panos/<?echo $rowpfc["id"];?>/pano.tiles/thumb100x50.jpg" />
								</div>
								<div class="loader-item">
									<h3><?echo $rowpfc["name"]?></h3>
									<p><?echo $rowpfc["fecha"];?></p>
								</div>
								<br clear="all">
							</div>
							
			<?php }?>
				
				</div><!-- end content_pop -->
				
				<div class="btnBkg">
					<a href="#" class="green-button border-radius-4 add_selected_panos">ADD SELECTION TO TOUR</a>
				</div>

			</div>
		</div> 
	
		<div>
			<h1 class="new_virtual_tour">
					<?php if ($is_new) : ?>
						<p id="title_label">Create a new virtual tour</p>
					<?php else : ?>    
						<p id="title_label">Tour editor: <?=$title?></p>
					<?php endif; ?> 
					
			<?if ($_GET["ok"]==1){echo ' - Your virtual tour was created!';}?>
			<?if ($_GET["ok"]==2){echo ' - ERROR: Try again';}?>
			<?if ($_GET["ok"]==3){echo ' - ERROR: Incorrect image type';}?>
			</h1>
		</div>
		<div class="wrapper wrapper-user wrapper-edit-tour <?php if ($is_new){echo 'new';} ?>" >
		
<form name="form1" id="main-form" method="post" enctype="multipart/form-data" action="ultour.php">	
	<input type="hidden" value="<?echo $id;?>" name="tour_id" id="tour_id">
	<input type="hidden" value="<?echo $draft_subscript;?>" name="draft_subscript" id="draft_subscript">
	<input type="hidden" value="0" name="proc_cancelled" id="proc_cancelled">

	<!--div class="drag-upload">
				<div class="upload-cloud">
					<div class="text drop-message">
						<h2>Click to upload your panoramas<br></h2>
						<h3>Or drag and drop your files here</h3>
						<a href="#" class="select-from">Select from your Panoramas Collection</a>
					</div>
				</div>
				<div class="features-here">
					<div class="text">
						<p>
							We can put information
							<br>
							or
							<br>
							features here
						</p>
					</div>
				</div>           
				<br clear="all">
			</div-->

			
			
<!-- MENSAJE DE PAGINA GUARDADA 


<div class="message_box good_m" style="display:block;"><p>Good! Your Tour is saved!</p></div>
<div class="message_box good_m"><p>Your Tour is saved and set as draft.</p></div>
<div class="message_box good_m"><p>YEAH! Your Tour is Published.<br>If you set the privacy configuration to "Private" the tour is published only for you!</p></div>
-->

<?php //If published, check if there is a newer version of this tour in draft
	
	if($draft_subscript != '_draft' && $id != ''){
			
		$ssqlp_draft = "SELECT id FROM tours_draft where date_updated > '".$row["date_updated"]."' and iduser = '".$_SESSION["usr"]."' and id = ".$id;
		
		$result_draft = mysql_query($ssqlp_draft);
		if ($row_draft = mysql_fetch_array($result_draft)){
			echo '<div class="message_box warning_m"><p><u>ATTENTION</u>: There is a newer version of this tour in draft state - <a href="edit_virtual_tour.php?id='.$id.'&d=1">Go to draft version</a><a class="delete-item dismiss" href="#" original-title="Dismiss"></a></p></div>';
		}
	}

?>	

<?php //If draft, check if there is a published version
	
	if($draft_subscript == '_draft'){
			
		$ssqlp_published = "SELECT id FROM tours where id = ".$id;
		
		$result_published = mysql_query($ssqlp_published);
		if ($row_published = mysql_fetch_array($result_published)){
			echo '<div class="message_box warning_m"><p><u>ATTENTION</u>: You are editing a draft of this tour. To go back and edit the live published version of this tour, <a href="edit_virtual_tour.php?id='.$id.'">click here</a>. <br>Loading the previously published version will cause you to lose all recent edits.<a class="delete-item dismiss" href="#" original-title="Dismiss"></a></p></div>';
		}
	}

?>	


<div class="uploading_pano" style="display:none;">
	<h3>Scenes in this tour</h3>
	
</div>
		
<div id="drop-zone" class="<?php if (!$is_new){?>not-new<?}?>">
	<div class="text drop-message">
			<div class="upload-cloud">
				<div class="text drop-message">
					<h2>Drag and drop your panos in this box<br></h2>
					<h3>Or click to select from your computer</h3>
					<a href="#" class="select-from">Select from panos manager</a>
				</div>
			</div>        
		
		<!--h2><a href="#" id="browse-file">Click</a> to upload your panoramas<br></h2>
		<h3>Or drag and drop your files here</h3-->
		<input id="browseFile" class="browseFile" type="file" multiple="multiple" style="position:fixed;top:-1000px;" value=""/>
		<!--a href="#" class="select-from">Select from your Panoramas Collection</a-->
	</div>
</div>
<div id="scenelist">
	<?php if (!$is_new && mysql_num_rows($panos_result) !== 0) : ?>
		<form action="" method="post"></form>
		<?php while($scene = mysql_fetch_array($panos_result)) : ?>
			<div class="pano-item">
				<div class="thumb-pano">
						<img src="<?php echo $cdn;?>/panos/<?=$scene['idpano']?>/pano.tiles/thumb100x50.jpg" />
				</div>
				<div class="loader-item">
					Scene name: <h3 class="otf-editable"><?=$scene['name']?></h3> 

					<div class="on-edit">
						<form action="php-stubs/scenes.php" method="post">
							<input type="hidden" class="scene-id" name="scene-id" value="<?=$scene['id']?>">
							<input class="stringkey" type="text" size="32" value="<?=$scene['name']?>" name="scene-name" /> 
							<br clear="all">
						</form>
					</div>

					<input type="hidden" class="scene-field" name="scene-id[]" value="<?=$scene['id']?>">

					<br />File name: <h4 class="pano-title"><?=$scene['pano_name']?></h4>

					<input type="hidden" class="pano-field" name="pano-id[]" value="<?=$scene['idpano']?>">


				 </div>
				<br clear="all">

				<a href="#" class="delete-item scene-remove" title="Remove Scene" data-id="<?=$scene['id']?>"></a>
				<a href="#" class="add-element border-radius-2 edit-hotspots" data-id="<?=$scene['id']?>" data-thumb="<?php echo $cdn;?>/panos/<?=$scene['idpano']?>/pano.tiles/thumb100x50.jpg">
					Edit hotspots
				</a>
				<a href="#" class="drag-item"  ></a>
			</div>

		<?php endwhile; ?>
	<?php endif; ?>
	
</div>
				
				
	  
		

				<!--tabs-->	
			<div id="tabs" class="hide" >
				
				<a href="tour.php?id=<?echo $id;?>" class="blue-link float-right publish_element open_tour" target="_blank" <?php if ($state != 'publish') {echo 'style="display:none;"';}?>>OPEN TOUR PAGE &raquo;</a>
				
				</form>
				  <ul>
					<li><a href="#tabs-1" class="tab-button">Tour Info</a></li>
					<li><a href="#tabs-2" class="tab-button">Skin - <span class="new">New!</span></a> </li>
				  </ul>
				  <div id="tabs-1" class="tabs-content">
					<div class="column">
					<label class="title">
						<p>Title * </p>
						<input class="info-tour border-radius-4 form-user" name="title" id="title" value="<?echo $title;?>">
					</label>
					<label class="description">
						<p>Description </p>
						<textarea class="info-tour border-radius-4 form-user" name="description" id="description"><?echo $description;?></textarea>
					</label>

<!--
					<label class="frendly">
						<p>URL * </p>
						<input class="info-tour border-radius-4 form-user" value="" name="friendly_url">
						<br clear="all">
					</label>


					<label class="frendly">
						<p>Thumb * </p>
						<input class="info-tour border-radius-4 form-user" type="file" value="" name="thumb">
						<br clear="all">
					</label>


					<label class="frendly">
						<p>Frendly URL</p>
						<font>https://www.spinattic.com/ </font>
						<input class="info-tour border-radius-4 form-user" value="Panoramas" name="friendly_url">
						<br clear="all">
					</label>

-->

					<label class="tags" style="width:93%;">
						<p>Tags <a href="#" class="help" title="Add multiple tags to your tour. Separate words by comma"></a></p>
						<input class="info-tour border-radius-4 form-user" value="<?echo $tags;?>" name="tags_loaded" id="tags_loaded">
					</label>    

					<!--label>
						<p>User Interactions</p>
					</label-->
					 <label class="category">               
						<p>Category <a href="#" class="help" title="Choose the category to publish the tour. Click to learn more"></a></p>
						<select class="info-tour border-radius-4 form-user" name="category" id="category">
							<option value="">Choose category</option>
							<?		$ssqlp = "SELECT * FROM categories ORDER BY category";
									$result = mysql_query($ssqlp);	
									
									while($row = mysql_fetch_array($result)){?>
														
							<option value="<?echo $row["category"];?>" <?if($category==$row["category"])echo 'selected';?>><?echo $row["category"];?></option>
							<?}?>
						</select>
					</label>
					
					<label class="privacy-config">               
						<p>Privacy Configuration <a href="#" class="help" title="Set the privacy level of the tour. Click to learn more"></a></p>
						<select class="info-tour border-radius-4 form-user" name="privacy" id="privacy">
							<option value="public" <?if($privacy=='_public')echo 'selected';?>>Public</option>
							<option value="private" <?if($privacy=='_private')echo 'selected';?>>Private</option>
							<option value="notlisted" <?if($privacy=='_notlisted')echo 'selected';?>>Not listed</option>
						</select>
						<!--p><font>Anyone can search for and view</font></p-->
					</label>
					
					<label>
						<input class="info-tour border-radius-4 form-user" type="checkbox" name="allow_comments" id="allow_comments" <?if($allow_comments===null || $allow_comments=='on')echo 'checked="checked"';?>>
						Allow Comments
					</label>
					<label>
						<input class="info-tour border-radius-4 form-user" type="checkbox" name="allow_social" id="allow_social" <?if($allow_social===null || $allow_social=='on')echo 'checked="checked"';?>>
						Allow Social Sharing
					</label>
					<label>
						<input class="info-tour border-radius-4 form-user" type="checkbox" name="allow_embed" id="allow_embed" <?if($allow_embed===null || $allow_embed=='on')echo 'checked="checked"';?>>
						Allow Embed Code
					</label>
					<label>
						<input class="info-tour border-radius-4 form-user" type="checkbox" name="allow_votes" id="allow_votes" <?if($allow_votes===null || $allow_votes=='on')echo 'checked="checked"';?>>
						Allow Users votes
					</label>
					</div>                    

					<div class="column">
					<div class="map-location">
						<p>Click the tour's location on the map <a href="#" class="help" title="Click on the map to set the location of your tour"></a></p>
						<div id="map_canvas"></div>
					</div>
					
				   
					<label class="latitude">
						<p>Latitude</p>
						<input name="lat" id = "latFld" class="info-tour border-radius-4 form-user" value="<?echo $lat;?>" readonly>
					</label>
					<label class="longitude">
						<p>Longitude</p>
						<input name="lon" id = "lngFld" class="info-tour border-radius-4 form-user" value="<?echo $lon;?>" readonly>
					</label>

					 <label class="location-info">               
						<p>Location</p>
						<input class="info-tour border-radius-4 form-user" id="location" name="location" id="location" value="<?echo $location;?>" readonly>
						<br clear="all">
					</label>
					


					<br clear="all">

					

					</div>
					<br clear="all">
					


				  </div>
				  <div id="tabs-2" class="tabs-content tab-user-interaction">
					
					<div class="choose-skin">	
						<h3>Choose the skin for your tour:</h3>
						<p>
							<label>Dark standard skin - bottom menu
								<input class="info-tour border-radius-4 form-user" type="radio" name="skin" value="0" <?if($skin_id==0) echo 'checked="checked"';?>>
							</label>
						</p>
							<div class="content-info-map hide">
								<img src="images/skin/dark-bottom-skin.jpg" alt="dark-skin" />
							</div>
						<p>
							<label>Light standard skin - bottom menu
							<input class="info-tour border-radius-4 form-user" type="radio" name="skin" value="1" <?if($skin_id==1) echo 'checked="checked"';?>>
							</label>
						</p>
							<div class="content-info-map hide">
								<img src="images/skin/light-bottom-skin.jpg" alt="dark-skin" />
							</div>					
					</div>
					<h3>Skin Settings:</h3>
						
						<label>Enable Top Avatar and Name 
							<input class="info-tour border-radius-4 form-user" type="checkbox" name="enabled-top-left" id="enable_avatar" <?if($enable_avatar===null || $enable_avatar=='on') echo 'checked="checked"';?>>
						</label>
						<label>Enable Top Tour title and scene name
							<input class="info-tour border-radius-4 form-user" type="checkbox" name="enabled-title" id="enable_title" <?if($enable_title===null || $enable_title=='on') echo 'checked="checked"';?>>
						</label>						
						<label class="disabled">Disabled Spinattic signature (Coming soon for Pro Users)
							<input class="info-tour border-radius-4 form-user" type="checkbox" name="allow_social" disabled="disabled">
						</label>
						<div class="image-resizer">
							<div class="forms">
							<label>Thumbnails size:<br>
								<input type="number" max="232" min="50" step="any" id="image-width"  value="<?php echo $thumb_width?>"/> x
								<input type="number" max="116" min="25" step="any" id="image-height" value="<?php echo $thumb_height?>" />
								px
							</label>
							<label>
								Margins:
								<input type="number" max="50" min="2" id="margins" value="<?php echo $thumb_margin?>" /> px
							</label>
							</div>
							<div id="image-wrapper">
								<img src="panos/thumb200x100.jpg" id="image-to-change"  />
							</div>
						</div>


					<h3>Customizer:</h3>
					<p class="last-parap">Coming soon! We're working on tools for you to customize the skins</p>
					<script type="text/javascript">
						$(".content_pop").mCustomScrollbar({
								theme:"minimal-dark",
								scrollInertia:200
							});	

							
					$(".content_pop").on({
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
							

					</script>
				  </div>
				  
		
					<input type="hidden" name="saving-type" id="saving-type" value="draft"/>
					
					
					
					<span id="botonera">
					
						<a href="#" class="red-button border-radius-4 remove-tour" style="float:left;font-size: 14px;">DELETE</a>     
					
						<!-- a href="#" class="green-button border-radius-4 float-left draft_element to-draft" <?php if ($state == 'publish') {echo 'style="display:none;"';}?>>SAVE DRAFT</a-->   
						<a href="#" class="green-button border-radius-4 float-left draft_element to-publish" <?php if ($state == 'publish') {echo 'style="display:none;"';}?>>PUBLISH</a>

						<a href="#" class="green-button border-radius-4 float-left publish_element to-draft" <?php if ($state != 'publish') {echo 'style="display:none;"';}?>>UNPUBLISH</a>    
						<a href="#" class="green-button border-radius-4 float-left publish_element to-publish" <?php if ($state != 'publish') {echo 'style="display:none;"';}?>>UPDATE</a>
	
						<a href="tour.php?id=<?echo $id;?>" class="blue-buttom border-radius-4 float-right publish_element open_tour" target="_blank" <?php if ($state != 'publish') {echo 'style="display:none;"';}?>>OPEN TOUR PAGE</a>
					
					</span>
					
					
					
					<br><br clear="all">

				</div> 
			<div class="cartel-wrapper"><p id="cartel" style="display:none;"></p></div>
			</div>
		
</form>
			
	<?php require_once("inc/footer.php");?>
</html>    
