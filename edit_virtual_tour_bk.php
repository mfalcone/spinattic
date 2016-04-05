<?php
    error_reporting(E_ALL ^ E_NOTICE);
      
    require("inc/conex.inc");
    require("inc/auth.inc");

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
    $allow_comments = null;
    $allow_social   = null;
    $allow_embed    = null;
    $allow_votes    = null;
    
    if (isset($_GET['id']) && $_GET['id'])
    {
        $id = $_GET["id"];
        
        $ssqlp = "SELECT *,DATE_FORMAT(date,'%d/%m/%Y') as fecha FROM tours where iduser = '".$_SESSION["usr"]."' and id = ".$id;         
        //$ssqlp = "SELECT *,DATE_FORMAT(date,'%d/%m/%Y') as fecha FROM tours where id = ".$id;         
        
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
        $state = $row["state"];
        
        $ssqlp = "SELECT scenes.*, panos.name AS pano_name FROM panosxtour scenes LEFT JOIN panos ON scenes.idpano = panos.id where idtour = ".$id." ORDER BY scenes.ord ASC";
        $panos_result = mysql_query($ssqlp);
        
        $is_new = false;
    }
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
		<link href='favicon.png' rel='shortcut icon' type='image/x-icon'/>
		<link href='favicon.png' rel='icon' type='image/x-icon'/>
                <?php if ($is_new) : ?>
                    <title id="html_title_label">Spinattic - Creating new tour</title>
                <?php else : ?>    
                    <title id="html_title_label">Spinattic - Editing tour: <?=$title?></title>
                <?php endif; ?> 
		
                

	    <!--google font-->
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'>

		<!-- css main -->        
	    <link rel="stylesheet" type="text/css" media="screen" href="css/main_2.css" />
	    
        <!-- jquery -->    
	    <script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
	    <script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
	    <!-- jquery ui -->        
		<link rel="stylesheet" type="text/css" media="screen" href="css/tabs.css" />
	  	<script src="js/jquery-ui.js"></script>


	   <!-- tags input-->
        <link rel="stylesheet" type="text/css" href="css/jquery.tagsinput.css" />	
        
	<script type="text/javascript" src="js/jquery.tagsinput.js"></script>
        <link rel="stylesheet" type="text/css" href="css/tipsy.css" />	
        <script type="text/javascript" src="js/jquery.tipsy.js"></script>                
		<script src="js/jquery.filedrop.js"></script>	  
		<script src="js/jquery.filedrop.start.js"></script>
        <script src="js/custom.js"></script>
        <script src="js/core-actions.js"></script>	
        <script src="js/core.tour-edit.inc.js"></script>	    
		<script src="js/core-utils.js"></script>	  

<!-- filedrop -->
<!--link rel="stylesheet" type="text/css" href="css/jquery.filedrop.css" /-->


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
            
            <?php else : ?>
            
                jQuery('.uploading_pano').css('display', 'block');                
                jQuery('#drop-zone').css('margin-top', '0');
                
                jQuery('#tabs').css('display', 'block');
                gNew_tour = false;
                gTour_id = <?=$id?>;
                
                initMap();
                
                addMarker('<?echo $lat;?>', '<?echo $lon;?>');
                
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

                jQuery( "#drop-zone" ).sortable({
                    placeholder: "pano-item-placeholder",
                    items: "> div.pano-item" ,
                    cursor: "move"
                });
                
            <?php endif; ?>
        });
        
 
    </script>
    
	</head>
	<body>
            
            <!-- loader -->            
<div id="loading" class="loading" style="display: none;">
    <div class="loading_img"></div>
</div>
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
    <div class="hotspot-window" style="top:25%;">	           
        <div class="thumb-head-set">
            <img id="editor-thumb-scene" src="" width="90">
            <h3></h3>
            <br clear="all">
        </div>
        <a class="blue-buttom set-startup-view" href="#">Set as startup view</a>
        <a class="save-close" href="#"></a>   
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
                *By closing the hotspots editor the changes are not saved. You mus click on UPDATE at the bottom of this page.
        </div>
    </div>
</div>
         
<link rel="stylesheet" type="text/css" media="screen" href="css/hotspots.css" />
<script type="text/javascript" src="js/hashtable.js"></script>
<script src="js/SimpleAjaxUploader.js"></script>
<script src="js/hotspot-tool/embedpano.js"></script>
<script src="js/hotspot-tool/hotspot-tool.js"></script>
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
        <div class="overlay_2 panocollection">
            <div class="pop">
                <a href="#" class="closed"></a>
                <h2>Include from panos manager</h2>
                    <div class="message_box">
                        <p>Select from uploaded panoramas and include them in the tour.</p>
                    </div>
                    <div class="content_pop">
                
			<?
					$ssqlpfc = "SELECT *, DATE_FORMAT(date,'%b %d %Y %h:%i %p') as fecha FROM panos where user = '".$_SESSION["usr"]."' ORDER BY id desc";
					$resultpfc = mysql_query($ssqlpfc);
					while($rowpfc = mysql_fetch_array($resultpfc)){
			?>
			                
			                <!-- pano -->
			                <div class="pano-item pano-item-manager">
			                    <label class="check_action">
			                        <input class="form-user border-radius-4" type="checkbox" name="sel[]" value="<?echo $rowpfc["id"];?>">
			                    </label>
								<div class="thumb-pano">
                                    <img src="panos/<?echo $rowpfc["id"];?>/index.tiles/thumb100x50.jpg" />
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
        
        
    
    	<header class="header">
		</header>
        <div class="nav">
		</div>

        <div>
        	<h1 class="new_virtual_tour">
                    <?php if ($is_new) : ?>
                        <p id="title_label">Create a new virtual tour</p>
                    <?php else : ?>    
                        <p id="title_label">Editing tour: <?=$title?></p>
                    <?php endif; ?> 
                    
			<?if ($_GET["ok"]==1){echo ' - Your virtual tour was created!';}?>
			<?if ($_GET["ok"]==2){echo ' - ERROR: Try again';}?>
			<?if ($_GET["ok"]==3){echo ' - ERROR: Incorrect image type';}?>
			</h1>
		</div>
        <div class="wrapper wrapper-user">
        <hr class="space180px" />

<form name="form1" id="main-form" method="post" enctype="multipart/form-data" action="ultour.php">	
	<input type="hidden" value="<?echo $id;?>" name="tour_id" id="tour_id">
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
<div class="uploading_pano" style="display:none;">
    <h3>Scenes in this tour</h3>
    <a href="#" class="add-element border-radius-2 upload-more">
        <div class="icon-upload-more"></div>
        Upload more panos
    </a>
    <a href="#" class="add-element border-radius-2 add-more select-from">
        <div class="icon-add-more"></div>
        Include from panos manager
    </a>

</div>
        
<div id="drop-zone" >
    <div class="text drop-message">
        <?php if ($is_new || mysql_num_rows($panos_result) === 0) : ?>
            <div class="upload-cloud">
                <div class="text drop-message">
                    <h2>Drag and drop your panos in this box<br></h2>
                    <h3>Or click to select from your computer</h3>
                    <a href="#" class="select-from">Select from panos manager</a>
                </div>
            </div>        
        <?php endif; ?>
        
        <!--h2><a href="#" id="browse-file">Click</a> to upload your panoramas<br></h2>
        <h3>Or drag and drop your files here</h3-->
        <input id="browseFile" class="browseFile" type="file" multiple="multiple" style="position:fixed;top:-1000px;" value=""/>
        <!--a href="#" class="select-from">Select from your Panoramas Collection</a-->
    </div>
    <?php if (!$is_new && mysql_num_rows($panos_result) !== 0) : ?>
        <form action="" method="post"></form>
        <?php while($scene = mysql_fetch_array($panos_result)) : ?>
            <div class="pano-item">
                <div class="thumb-pano">
                        <img src="panos/<?=$scene['idpano']?>/index.tiles/thumb100x50.jpg" />
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
                <a href="#" class="add-element border-radius-2 edit-hotspots" data-id="<?=$scene['id']?>" data-thumb="panos/<?=$scene['idpano']?>/index.tiles/thumb100x50.jpg">
                    Edit hotspots
                </a>
                <a href="#" class="drag-item"  ></a>
            </div>

        <?php endwhile; ?>
    <?php endif; ?>

    <!-- pano loading -->                
    <!--div class="pano-item">
                            <div class="thumb-pano"></div>
                            <div class="loader-item">
            Scene name: <h3 class="otf-editable">Scene 1</h3>
            <div class="on-edit">
                <form action="php-stubs/scenes.php" method="post">                                         
                    <input type="hidden" id="scene-id" name="scene-id" value="456">
                    <input class="stringkey" id="title-456" type="text" size="32" value="t�tulo cargado" name="scene-name" /> 
                    <br clear="all">
                </form>      
            </div>
            <br />
            File name: <h4>Panorama 1</h4>


            </div>
        <br clear="all">
        <a href="#" class="delete-item" title="Delete item"></a>
                            <a href="#" class="add-element border-radius-2 edit-hotspots">
                Edit hotspots
            </a>
                            <a href="#" class="drag-item"></a>
    </div>


    <div class="pano-item pano-item-cargado">
                            <div class="thumb-pano">
            <img src="images/ejemplo/thumb-upload.jpg">
                            </div>
                            <div class="loader-item">
            Scene name: <h3 class="otf-editable">Scene 1</h3>
            <div class="on-edit">
                <form action="php-stubs/scenes.php" method="post">                                         
                    <input type="hidden" id="scene-id" name="scene-id" value="456">
                    <input class="stringkey" id="title-456" type="text" size="32" value="t�tulo cargado" name="scene-name" /> 
                    <br clear="all">
                </form>      
            </div>
            <br />
            File name: <h4>Panorama 2</h4>							          			

            </div>
        <br clear="all">
        <a href="#" class="delete-item" title="Delete item"></a>
                            <a href="#" class="add-element border-radius-2 edit-hotspots">
                Edit hotspots
            </a>
                            <a href="#" class="drag-item"></a>
    </div-->
</div>
                
                
	  
	    

				<!--tabs-->	
            <div id="tabs" style="display:none;">
                
                <a href="tour.php?id=<?echo $id;?>" class="blue-link float-right publish_element open_tour" target="_blank" <?php if ($state != 'publish') {echo 'style="display:none;"';}?>>OPEN TOUR PAGE &raquo;</a>
                
                </form>
                  <ul>
                    <li><a href="#tabs-1" class="tab-button">Tour Info</a></li>
                    <!--li><a href="#tabs-2" class="tab-button">User Interactions</a></li-->
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
                    
                    <label>
                        <input class="info-tour border-radius-4 form-user" type="checkbox" name="allow_comments" id="allow_comments" <?if($allow_comments === null || $allow_comments=='on')echo 'checked';?>>
                        Allow Comments
                    </label>
                    <label>
                        <input class="info-tour border-radius-4 form-user" type="checkbox" name="allow_social" id="allow_social" <?if($allow_social === null || $allow_social=='on')echo 'checked';?>>
                        Allow Social Sharing
                    </label>
                    <label>
                        <input class="info-tour border-radius-4 form-user" type="checkbox" name="allow_embed" id="allow_embed" <?if($allow_embed === null || $allow_embed=='on')echo 'checked';?>>
                        Allow Embed Code
                    </label>
                    <label>
                        <input class="info-tour border-radius-4 form-user" type="checkbox" name="allow_votes" id="allow_votes" <?if($allow_votes === null || $allow_votes=='on')echo 'checked';?>>
                        Allow Users votes
                    </label>


					</div>                    
  					<div class="column">
	                 <label class="privacy-config">               
                        <p>Privacy Configuration <a href="#" class="help" title="Set the privacy level of the tour. Click to learn more"></a></p>
                        <select class="info-tour border-radius-4 form-user" name="privacy" id="privacy">
                            <option value="public" <?if($privacy=='_public')echo 'selected';?>>Public</option>
                            <option value="private" <?if($privacy=='_private')echo 'selected';?>>Private</option>
                            <option value="notlisted" <?if($privacy=='_notlisted')echo 'selected';?>>Not listed</option>
                        </select>
                        <!--p><font>Anyone can search for and view</font></p-->
                    </label>
                    
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
                    <br clear="all">

                    
					<div class="map-location">
						<p>Location on Map <a href="#" class="help" title="Click on the map to set the location of your tour"></a></p>
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
                        <input class="info-tour border-radius-4 form-user" id="location" name="location" id="location" style="width:450px" value="<?echo $location;?>" readonly>
                        <br clear="all">
                    </label>
                    


                    <br clear="all">

					

                    </div>
                    <br clear="all">
                    
                    <hr class="separator" />

                    <input type="hidden" name="saving-type" id="saving-type" value="draft"/>
                    
                    <div><p id="cartel" style="display:none;"></p></div>
                    
                    <span id="botonera">
                    
                    	<a href="#" class="red-button border-radius-4 remove-tour to-draft" style="float:left;font-size: 14px;">DELETE</a>     
                    
                    	<!-- a href="#" class="green-button border-radius-4 float-left draft_element to-draft" <?php if ($state == 'publish') {echo 'style="display:none;"';}?>>SAVE DRAFT</a-->   
                        <a href="#" class="green-button border-radius-4 float-left draft_element to-publish" <?php if ($state == 'publish') {echo 'style="display:none;"';}?>>PUBLISH</a>

						<a href="#" class="green-button border-radius-4 float-left publish_element to-draft" <?php if ($state != 'publish') {echo 'style="display:none;"';}?>>UNPUBLISH</a>    
                        <a href="#" class="green-button border-radius-4 float-left publish_element to-publish" <?php if ($state != 'publish') {echo 'style="display:none;"';}?>>UPDATE</a>
    
                    	<a href="tour.php?id=<?echo $id;?>" class="blue-buttom border-radius-4 float-right publish_element open_tour" target="_blank" <?php if ($state != 'publish') {echo 'style="display:none;"';}?>>OPEN TOUR PAGE</a>
                    
                    </span>
                    
                    
                    
                    <br><br clear="all">



                  </div>
                  <!--div id="tabs-2" class="tabs-content tab-user-interaction">
                  	<h3>Comments and responses</h3>
					<label>
                        <input class="info-tour border-radius-4 form-user" type="checkbox" name="allow_comments" checked>
                        Allow Comments
                    </label>
					<label>
                        <input class="info-tour border-radius-4 form-user" type="checkbox" name="allow_social" checked>
                        Allow Social Sharing
                    </label>
					<label>
                        <input class="info-tour border-radius-4 form-user" type="checkbox" name="allow_embed" checked>
                        Allow Embed Code
                    </label>
					<label>
                        <input class="info-tour border-radius-4 form-user" type="checkbox" name="allow_votes" checked>
                        Allow Users votes
                    </label>
                    <a href="#" onclick="jQuery('#saving-type').val('publish'); return verificar()" class="red-button border-radius-4">SAVE</a>
                    <br clear="all">
                  </div-->
                </div> 
            </div>
        
</form>
            
	</body>
</html>    
