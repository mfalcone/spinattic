define([
	'jquery',
	'underscore',
	'backbone',
	'x2js',
	'helpers/HelpFunctions',
	'helpers/ManageHotSpots',
    'views/footer/SceneMenuView',
    'collections/footer/SceneCollection',

 
], function($, _, Backbone,x2js,HelpFunctions,ManageHotSpots,SceneMenuView,SceneCollection){

  var ManageTour =  function(){


		this.reloadTour = function(escenas,secCall){

		var helpFunctions = new HelpFunctions()
		helpFunctions.showReloadOverlay();

		var tourId = location.hash.split("/")[1];
		var xmlpath ="data/xml.php?id="+tourId+"&d=1&c=1";

		var locationurl = location.href;
		var hmafull = locationurl.substring(locationurl.lastIndexOf("?")+1,locationurl.lastIndexOf("#"));
		var hma = hmafull.replace("hma=","");
	
		if(hma!=location.origin+location.pathname){
			xmlpath=xmlpath+"&"+hma;
		}

		$(".dragger-wrapper").fadeOut(function(){
			 $(this).remove();
		})

		$("#tour").remove();
		
		$.ajax({
				url: xmlpath,
				type: "GET",
				dataType: "html",

				success: function(data) {
						var x2js = new X2JS({attributePrefix:"_"});
						tourData =  x2js.xml_str2json( data );
						helpFunctions.prepareConditionsForTour();

						$.ajax({
								url:  "data/json.php?id="+tourId+"&d=1&t=t",
								dataType:"json",
								success:function(datatour){

										tourData.krpano.datatour = datatour;
										var xml2krpano = xmlpath.replace("&c=1","&h=0")
										
										if(escenas != undefined){
											escenas();
										}
										if(!tourData.krpano.scene){
											helpFunctions.hideReloadOverlay();
											return;
										}
										$pano_wrapper = $('<div id="tour"></div>');         
										$(".main-section .inner").append( $pano_wrapper ); 
										embedpano({
											swf:"../player/tour.swf", 
											xml:xml2krpano, 
											target:"tour", html5:"prefer", 
											wmode:"transparent", 
											passQueryParameters:true,
											onready:function(){

												var krpano = document.getElementById("krpanoSWFObject");
												krpano.call("registerattribute(int,0)");
												$("#tour").data("scene",tourData.krpano.scene[0])
												$(".loading-msg").hide();
												helpFunctions.setInnerHeight(".main-section",true);

												if($("#tour").data("scene").hotspot){
													setTimeout(function(){
														if(!window.initHotSpots){
															var manageHotSpots = new ManageHotSpots();
														}
														initHotSpots();
													},2000)
												}
												if(secCall){
													secCall();
													helpFunctions.hideReloadOverlay();
												}else{
													helpFunctions.hideReloadOverlay();
												}
											}

										});
										$(".main-section .inner").addClass("withTour")

										
								}
						})
				}
		});

	}

	
	
}

  return ManageTour;
  
});
