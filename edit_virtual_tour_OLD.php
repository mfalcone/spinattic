﻿<?
	require("inc/conex.inc");
	require("inc/auth.inc");
	

	$id = $_GET["id"];

	if ($id !=''){
	
		$ssqlp = "SELECT *,DATE_FORMAT(date,'%d/%m/%Y') as fecha FROM tours where id = ".$id;
		$result = mysql_query($ssqlp);
				
		$row = mysql_fetch_array($result);

		$url = 'http://'.$row["friendly_url"];
		$title = $row["title"];
		$description = $row["description"];
		$lat = $row["lat"];
		$lon = $row["lon"];
		$location = $row["location"];
		$privacy = $row["privacy"];
		$category = $row["category"];
		$tags = $row["tags"];
		$allow_comments = $row["allow_comments"];
		$allow_social = $row["allow_social"];
		$allow_embed = $row["allow_embed"];
		$allow_votes = $row["allow_votes"];
		
		
	}else{
		header("Location: index.php");
	}


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
		<link rel="stylesheet" type="text/css" media="screen" href="css/tabs.css" />
	  	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>


	   <!-- tags input-->
        <link rel="stylesheet" type="text/css" href="css/jquery.tagsinput.css" />	
        
	<script type="text/javascript" src="js/jquery.tagsinput.js"></script>
        <link rel="stylesheet" type="text/css" href="css/tipsy.css" />	
        <script type="text/javascript" src="js/jquery.tipsy.js"></script>                
	    <script src="js/custom.js"></script>
            <script src="js/core.tour-edit.inc.js"></script>	    
<script src="js/core-utils.js"></script>	  
	    
        
        
    <style type="text/css">
        #map_canvas {height:400px;width:460px}
    </style>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&language=en"></script>

    <script src="js/maploc.js"></script>        
        
    <script type="text/javascript">
    

   
		function verificar()
                {
                    var title        = jQuery(document.form1.title).val();
                    var description  = jQuery(document.form1.description).val();
                    var friendly_url = jQuery(document.form1.friendly_url).val();
                    var location     = jQuery(document.form1.location).val();
                    var tags_loaded  = jQuery(document.form1.tags_loaded).val();
                    var category     = jQuery(document.form1.category).val();
                    
                    if (title && description && friendly_url && location && tags_loaded && category)
                    {
                        document.form1.submit();
                        
                    }    
                    else
                    {
                        showMessage('Saving data', 'Please complete all required fields.')
                    }    
			
                    return false;
		}
    </script>
    
	</head>
	<body onload="initMap();addMarker('<?echo $lat;?>', '<?echo $lon;?>');">
    
        <!--pop Panorama Collection-->
        <div class="overlay overlay_login edit-panorama">
            <div class="pop">
                <a href="#" class="closed"></a>
                <h2>Panorama Collection</h2>
                
                <!-- pano -->
                <div class="pano-item pano-item-manager">
                    <label class="check_action">
                        <input class="form-user border-radius-4" type="checkbox">
                    </label>
					<div class="thumb-pano"></div>
					<div class="loader-item">
                    	<h3>Panorama 1</h3>
	          			<p>May 6, 2013 1:45 PM.</p>
	                </div>
                    <br clear="all">
                </div>
                
                <!-- pano -->
                <div class="pano-item pano-item-manager">
                    <label class="check_action">
                        <input class="form-user border-radius-4" type="checkbox">
                    </label>
					<div class="thumb-pano"></div>
					<div class="loader-item">
                    	<h3>Panorama 1</h3>
	          			<p>May 6, 2013 1:45 PM.</p>
	                </div>
                    <br clear="all">
                </div>

                <!-- pano -->
                <div class="pano-item pano-item-manager">
                    <label class="check_action">
                        <input class="form-user border-radius-4" type="checkbox">
                    </label>
					<div class="thumb-pano"></div>
					<div class="loader-item">
                    	<h3>Panorama 1</h3>
	          			<p>May 6, 2013 1:45 PM.</p>
	                </div>
                    <br clear="all">
                </div>
                
                
	           	<a href="#" class="red-button border-radius-4">ADD TO TOUR</a>

            </div>
        </div> 
        
        
        <!--pop setTour-->
        <div class="overlay" style="display:none;">
            <div class="pop set-tour">
	            <!--borrar--><a href="#" class="closed"></a>
                <div class="thumb-head-set">
                	<img src="images/ejemplo/thumb-set-tour.jpg">
                	<h3>Panorama 3</h3>
                    <br clear="all">
				</div>
				<a href="#" class="blue-buttom border-radius-4 set-startup-view">Set startup view</a>
                <a href="#" class="add-element border-radius-2 save-close">Save & Close</a>
                
                <div class="content-set">
                	<div class="bar">
                        <div class="pano-orientation">
                            <div class="orientation">
                                <p class="titulo">Pano</p>
                                <font>Select a pano from the list below,<br>then orient the initial view.</font>
	                            <br clear="all">
                                <!--item-->
                                <div class="item-orientation">
                                    <div class="thumb-pano"><img src="images/ejemplo/thumb-orientation.jpg"></div>
                                    <div class="loader-item">
                                        <h3>Panorama 1</h3>
                                        <p>May 6, 2013</p>
                                    </div>
									<a href="#" class="drag-item"></a>
                                    <br clear="all">
                                </div>
                                <!--item-->
                                <div class="item-orientation">
                                    <div class="thumb-pano"><img src="images/ejemplo/thumb-orientation.jpg"></div>
                                    <div class="loader-item">
                                        <h3>Panorama 1</h3>
                                        <p>May 6, 2013</p>
                                    </div>
									<a href="#" class="drag-item"></a>
                                    <br clear="all">
                                </div>
                                <!--item-->
                                <div class="item-orientation">
                                    <div class="thumb-pano"><img src="images/ejemplo/thumb-orientation.jpg"></div>
                                    <div class="loader-item">
                                        <h3>Panorama 1</h3>
                                        <p>May 6, 2013</p>
                                    </div>
									<a href="#" class="drag-item"></a>
                                    <br clear="all">
                                </div>

                            </div>
                        </div>
                        
                        <!---->
                        <div class="pano-linkto">
                            <div class="link-to">
								<p class="titulo">Link To</p>
                                <font>Enter the URL of any web page.</font>
	                            <br clear="all">
                                <label>
                                    <input class="info-tour border-radius-4 form-user" value="http://www.my-pano.com">
                                </label>
					            <a href="#" class="delete-item" title="Delete item"></a>
                                <br clear="all">
                            </div>                        
                        </div>
                        
                        <!---->
                        <div class="pano-infospot">
                            <div class="info-spot">
								<p class="titulo">Info Spot</p>
                                <font>Type your text here.</font>
	                            <br clear="all">
                                <label>
                                    <textarea class="info-tour border-radius-4 form-user"></textarea>
                                </label>
					            <a href="#" class="delete-item" title="Delete item"></a>
                                <br clear="all">
                            </div>   
                        </div>
                    </div>
                    <img src="images/ejemplo/set-tour.jpg">
                </div>
                <div class="foot-set-tour">
	                *Drag the hotspots and place them on the scene.
                </div>
            </div>
        </div>   
    
    	<header class="header">
				<?require("inc/head.inc");?>
        </header>
        <div class="nav">
				<?require("inc/nav.inc");?>
        </div>

        <div>
        	<h1 class="new_virtual_tour">Create a new virtual tour
			<?if ($_GET["ok"]==1){echo ' - Your virtual tour was created!';}?>
			<?if ($_GET["ok"]==2){echo ' - ERROR: Try again';}?>
			<?if ($_GET["ok"]==3){echo ' - ERROR: Incorrect image type';}?>
			</h1>
		</div>
        <div class="wrapper wrapper-user">

	<div class="drag-upload">
                <div class="upload-cloud">
                    <div class="text">
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
            </div>


			<div class="uploading_pano">
				<h3>Uploading panoramas</h3>
                <a href="#" class="add-element border-radius-2 upload-more">
                	<div class="icon-upload-more"></div>
                    Upload more
                </a>
				<a href="#" class="add-element border-radius-2 add-more">
                	<div class="icon-add-more"></div>
                    Add more panos from collection
                </a>

                </div>
                <!-- pano loading -->
                <div class="pano-item">
					<div class="thumb-pano"></div>
					<div class="loader-item">
                    	<h3>Panorama 1</h3>
	          			<p>Uploading panorama.</p>
						<div class="loader-item-bg border-radius-10">
		                	<div class="loader-bar border-radius-10" style="width:89%">
		                    	<div class="percentage">Loading 89%</div>
		                        <br>
		                    </div>
		                </div>
	                </div>
                    <br clear="all">
                    <a href="#" class="delete-item" title="Delete item"></a>
					<a href="#" class="add-element border-radius-2 edit-hotspots">
	                    Edit hotspots
	                </a>
					<a href="#" class="drag-item"></a>
                </div>
                
                <!-- pano cargado -->
                <div class="pano-item pano-item-cargado">
					<div class="thumb-pano">
                    	<img src="images/ejemplo/thumb-upload.jpg">
					</div>
					<div class="loader-item">
                    	<h3>Panorama 2</h3>
						<div class="ok"></div>
	          			<p>Upload complete!</br>Now you can add hotspots to this scene</p>
						<div class="loader-item-bg border-radius-10">
		                	<div class="loader-bar border-radius-10" style="width:89%">
		                    	<div class="percentage">Loading 89%</div>
		                        <br>
		                    </div>
		                </div>
	                </div>
                    <br clear="all">
                    <a href="#" class="delete-item" title="Delete item"></a>
					<a href="#" class="add-element border-radius-2 edit-hotspots">
	                    Edit hotspots
	                </a>
					<a href="#" class="drag-item"></a>
                </div>

<form name="form1" method="post" enctype="multipart/form-data" action="udtour.php">
<input type="hidden" value = "<?echo $id;?>" name="id">

				<!--tabs-->	
                <div id="tabs">
                  <ul>
                    <li><a href="#tabs-1" class="tab-button">Basic Info</a></li>
                    <li><a href="#tabs-2" class="tab-button">User Interactions</a></li>
                  </ul>
                  <div id="tabs-1" class="tabs-content">
  					<div class="column">
                    <label class="title">
                        <p>Title * </p>
                        <input class="info-tour border-radius-4 form-user" name="title" value="<?echo $title;?>">
                    </label>
                    <label class="description">
                        <p>Description * </p>
                        <textarea class="info-tour border-radius-4 form-user" name="description"><?echo $description;?></textarea>
                    </label>

                    <label class="frendly">
                        <p>URL * </p>
                        <input class="info-tour border-radius-4 form-user" value="<?echo $url;?>" name="friendly_url">
                        <br clear="all">
                    </label>


                    <label class="frendly">
                        <p>Thumb (Leave blank if doesn't change)</p>
                        <input class="info-tour border-radius-4 form-user" type="file" value="" name="thumb">
                        <br clear="all">
                    </label>

<!--
                    <label class="frendly">
                        <p>Frendly URL</p>
                        <font>https://www.spinattic.com/ </font>
                        <input class="info-tour border-radius-4 form-user" value="Panoramas" name="friendly_url">
                        <br clear="all">
                    </label>

-->

                    <label class="tags">
                        <p>Tags * <a href="#" class="help" title="Lorem ipsum lorem ipsum, lorem ipsum"></a></p>
                        <input class="info-tour border-radius-4 form-user" value="<?echo $tags;?>" name="tags_loaded" id="tags-loaded">
                    </label>                    
					</div>                    
  					<div class="column">
	                 <label class="privacy-config">               
                        <p>Privacy Configuration <a href="#" class="help" title="Lorem ipsum lorem ipsum, lorem ipsum"></a></p>
                        <select class="info-tour border-radius-4 form-user" name="privacy">
                            <option value="public" <?if($privacy=='_public')echo 'selected';?>>Public</option>
                            <option value="private" <?if($privacy=='_private')echo 'selected';?>>Private</option>
                            <option value="notlisted" <?if($privacy=='_notlisted')echo 'selected';?>>Not listed</option>
                        </select>
                        <p><font>Anyone can search for and view</font></p>
                    </label>
                    
	                 <label class="category">               
                        <p>Category * <a href="#" class="help" title="Lorem ipsum lorem ipsum, lorem ipsum"></a></p>
                        <select class="info-tour border-radius-4 form-user" name="category">
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
						<p>Location on Map</p>
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
                        <p>Location * <a href="#" class="help" title="Lorem ipsum lorem ipsum, lorem ipsum"></a></p>
                        <input class="info-tour border-radius-4 form-user" id="location" name="location" style="width:450px" value="<?echo $location;?>" readonly>
                        <br clear="all">
                    </label>
                    


                    <br clear="all">

					

                    </div>
                    <br clear="all">

                    <a href="#" onclick="return verificar()" class="red-button border-radius-4">SAVE</a>
                    <br clear="all">

                    
                  </div>
                  <div id="tabs-2" class="tabs-content tab-user-interaction">
                  	<h3>Comments and responses</h3>
					<label>
                        <input class="info-tour border-radius-4 form-user" type="checkbox" name="allow_comments" <?if($allow_comments=='on')echo 'checked';?>>
                        Allow Comments
                    </label>
					<label>
                        <input class="info-tour border-radius-4 form-user" type="checkbox" name="allow_social" <?if($allow_social=='on')echo 'checked';?>>
                        Allow Social Sharing
                    </label>
					<label>
                        <input class="info-tour border-radius-4 form-user" type="checkbox" name="allow_embed" <?if($allow_embed=='on')echo 'checked';?>>
                        Allow Embed Code
                    </label>
					<label>
                        <input class="info-tour border-radius-4 form-user" type="checkbox" name="allow_votes" <?if($allow_votes=='on')echo 'checked';?>>
                        Allow Users votes
                    </label>
                    <a href="#" onclick="return verificar()" class="red-button border-radius-4">SAVE</a>
                    <br clear="all">
                  </div>
                </div> 
            </div>
        </div>
</form>
	</body>
</html>    
