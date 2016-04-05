define([
	'jquery',
	'underscore',
	'backbone',
	'helpers/HelpFunctions',
	'helpers/ManageData'
], function($, _, Backbone,HelpFunctions,ManageData){

	var HotSpotsDropDown = Backbone.View.extend({

		events:{
		},
		
		render:function(){
			

		var urls = [];
		var pos = [];
		var names = [];
		var scales =[];
		var selectedurl;
		var selectedPos;
		var scaled;
		var styles = tourData.krpano.style;
		var kind = 	this.model.get("kind");
		var elemid = this.model.get("elemid");
		var selectedset = this.model.get("selectedset");

		_.each(styles,function(elem){
				if(elem._kind == kind){
					urls.push(elem._url);
					pos.push(elem._crop);
					names.push(elem._name);
					scales.push(elem._scale);
					var name = elem._name;
	                name = name.split("_");
	                if(name[1] == selectedset){
	                        selectedurl = elem._url;
	                    	selectedPos = elem._crop;
	                    	scaled = elem._scale;
	                    }
                    }
			})
			if(scaled == "0.5"){
				var scale = "scale"
			}else{
				var scale = "not-scale"
			}
			selPos = selectedPos.split("|");
			$("#"+elemid+" .dropdown h2 .default").css({
				"background-image":"url("+selectedurl+")",
				"background-position": "-"+selPos[0]+"px -"+selPos[1]+"px",
				"width": selPos[2]+"px",
				"height": selPos[3]+"px",
			}).addClass(scale).wrap('<div class="df-wrap"></div>');
			
			_.each(urls,function(elem,ind){
				var mypos = pos[ind].split("|");
				var scaled = scales[ind];
				if(scaled == "0.5"){
					var scale = "scale";
				}else{
					var scale = "not-scale";
				}
				var backpos = "-"+mypos[0]+"px -"+mypos[1]+"px";
				var $li = $('<li id="opt'+ind+'" data-name="'+names[ind]+'"><div class="default '+scale+'" style="background-image:url('+elem+');background-position:'+backpos+';width:'+mypos[2]+'px;height:'+mypos[3]+'px;"></div></li>');
				$("#"+elemid+" .styles-list").append($li);
			})


			var helpFunctions = new HelpFunctions();
			helpFunctions.dropDown("#"+elemid+" .dropdown");

		 	$("#"+elemid+" .dropdown li").click(function(){
		 		var bk = $(this).find(".default").css("background-image");
		 		var stylesel = $(this).data("name");
		 		$("#"+elemid+" .dropdown h2 .default").css("background-image",bk);
		 		var krpano = document.getElementById("krpanoSWFObject");
		 		krpano.call("hotspot["+elemid+"].loadStyle("+stylesel+");");
		 		
		 		var hotspot = $("#"+elemid).data("spotdata");
            	hotspot._style = stylesel;
            	$("#"+elemid).data("spotdata",hotspot);
            	var manageData = new ManageData();
            	manageData.changeDataInHotSpot( $("#tour").data("scene")._name,hotspot)

		 		console.log(elemid)
		 		console.log(selectedset)

		 	})

		}

		
	});

	return HotSpotsDropDown;
	
});
