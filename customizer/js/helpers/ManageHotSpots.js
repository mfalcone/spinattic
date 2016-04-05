define([
	'jquery',
	'underscore',
	'backbone',
	'helpers/ManageData',
   'views/modal/hotspotseditor/LinkHotspotEditorView',
	'views/modal/hotspotseditor/InfoHotspotEditorView',
	'views/modal/hotspotseditor/PhotoHotspotEditorView',
	'views/modal/hotspotseditor/VideoHotspotEditorView',
	'views/modal/hotspotseditor/ArrowHotspotEditorView',
 
], function($, _, Backbone,ManageData,LinkHotspotEditorView,InfoHotspotEditorView,PhotoHotspotEditorView,VideoHotspotEditorView,ArrowHotspotEditorView){

  var ManageHotSpots =  function(){


	this.initHotSpots = function(){
	var hotspot = $("#tour").data("scene").hotspot;
	var krpano = document.getElementById("krpanoSWFObject");
		_.each(hotspot,function(elem){
					krpano.call("addhotspot("+elem._name+")");
				
					/*krpano.set("hotspot["+elem._name+"].ath", elem._ath);
					krpano.set("hotspot["+elem._name+"].atv", elem._atv);
					if(elem._rotate){
						krpano.set("hotspot["+elem._name+"].rotate", elem._rotate);
					}
					if(elem._tooltiptype){
						krpano.set("hotspot["+elem._name+"].tooltiptype", elem._tooltiptype);
					}
					if(elem._linkedscene){
						krpano.set("hotspot["+elem._name+"].linkedscene", elem._linkedscene);
					}
					if(elem._tooltip){
					 	krpano.call('set(hotspot['+elem._name+'].tooltip,'+elem._tooltip+' );');
					}else{
						krpano.call('set(hotspot['+elem._name+'].tooltip,"");');	
					}*/

					$.each(elem,function(i,val){
						if(i!="_name"){
							var prop = i.replace("_","");
							krpano.set("hotspot["+elem._name+"]."+prop,val)	
						}
					})


					var krpanocall = "hotspot["+elem._name+"].loadStyle("+elem._style+");";
					krpanocall+='set(hotspot['+elem._name+'].ondown, draghotspot() );';
					krpanocall+='set(hotspot['+elem._name+'].onclick, js(openHotspotWindowEditor('+elem._name+')) );';
					krpanocall+='set(hotspot['+elem._name+'].onup, js(regPos('+elem._name+')) );';
					krpano.call(krpanocall);

				})
	}

	this.openHotspotWindowEditor = function(elem){
		 var hotspot;
		 	_.each($("#tour").data("scene").hotspot,function(hs,ind){
				if(hs._name == elem){
						hotspot = hs;
					}
			})
		var modalView;
		var kind = hotspot._kind;
			
	
			switch(kind){
				case "link":
					modalView = LinkHotspotEditorView;
				break;
				case "video":
					modalView = VideoHotspotEditorView;
				break;
				case "photo":
					modalView = PhotoHotspotEditorView;
				break;
				case "info":
					modalView = InfoHotspotEditorView;
				break;
				case "arrow":
					modalView = ArrowHotspotEditorView;
				break;
			}

			var integer = hotspot._name.replace("spot","")

			if($("#spot"+integer).size() == 1){
				return;
			}

			var style = hotspot._style;
			var selectedSet = style.split("_");
			selectedSet = selectedSet[1]
			var HotSpotWindowModel = Backbone.Model.extend({});

		    var hotSpotWindowModel = new HotSpotWindowModel({id:integer,selectedSet:selectedSet,allData:hotspot})
			var linkhotspotEditorview = new modalView({model:hotSpotWindowModel});
		
			linkhotspotEditorview.render("spot"+integer,linkhotspotEditorview.renderExtend);
				
			$("#spot"+integer).parent(".overlay").addClass("hotspotwindow");
		
			$("#spot"+integer).data("spotdata",hotspot)
		}

	this.regPos = function(elem){
			var krpano = document.getElementById("krpanoSWFObject");
			var ath = krpano.get("hotspot["+elem+"].ath");
			var atv = krpano.get("hotspot["+elem+"].atv");
			var hotspot;
			_.each($("#tour").data("scene").hotspot,function(hs,ind){
				if(hs._name == elem){
						hotspot = hs;
					}
			})
			if(parseInt(hotspot._ath) != parseInt(ath) || parseInt(hotspot._atv) != parseInt(atv)){
				hotspot._ath = ath;
				hotspot._atv = atv;
				var manageData = new ManageData();
				manageData.changeDataInHotSpot( $("#tour").data("scene")._name,hotspot)
			}
		}

	if(!window.openHotspotWindowEditor){
		window.openHotspotWindowEditor = this.openHotspotWindowEditor;
	}

	if(!window.regPos){
		window.regPos = this.regPos;
	}

	if(!window.initHotSpots){
		window.initHotSpots = this.initHotSpots;
	}
	

}

  return ManageHotSpots;
  
});
