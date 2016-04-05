define([
	'jquery',
	'underscore',
	'backbone',
	'helpers/ManageHotSpots',
	'helpers/ManageMobileHotSpots',
	'helpers/HelpFunctions',

], function($, _, Backbone, ManageHotSpots,ManageMobileHotSpots,HelpFunctions){

	var TourView = Backbone.View.extend({
		el: $(".main-section .inner"),
		initialize: function () {

			
		},
		events:{

		},
		render: function(){
			if(!tourData.krpano.scene){
				return;
			}
			var xmlpath = this.model.get("xmlpath");
			$pano_wrapper = $('<div id="tour"></div>');         
			$(this.el).append( $pano_wrapper ); 

			embedpano({
				swf:"../player/tour.swf", 
				xml:xmlpath, 
				target:"tour", html5:"prefer", 
				wmode:"transparent", 
				passQueryParameters:true,
				onready:this.initTool

			});
			$(this.el).addClass("withTour")
		},

		initTool:function(){
			var krpano = document.getElementById("krpanoSWFObject");
			krpano.call("registerattribute(int,0)");
			$("#tour").data("scene",tourData.krpano.scene[0])
			var helpFunctions = new HelpFunctions()
			helpFunctions.setInnerHeight(".main-section",true);
			$(".loading-msg").hide();
			
			if($("body").data("device")){
                var manageMobileHotSpots = new ManageMobileHotSpots();
            }else{
                var manageHotSpots = new ManageHotSpots();
           }
			

			if($("#tour").data("scene").hotspot){
				krpano.set("events.onloadcomplete","js(initHotSpots())");
				krpano.set("events.keep",true);
			}
			
		}
		
	});

	return TourView;
	
});
