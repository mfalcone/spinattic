define([
	'jquery',
	'underscore',
	'backbone',
	'views/modal/Modal',
	'text!templates/modal/hotspotvideo.html',
	'views/modal/HotSpotsDropDown',
	'helpers/ManageData',
	'views/modal/HotSpotToolTip',
	'models/main/ModalModel',
	'helpers/HelpFunctions',
	'views/modal/ConfirmView'

	
], function($, _, Backbone,Modal,hotspotvideo,HotSpotsDropDown,ManageData,HotSpotToolTip,ModalModel,HelpFunctions,ConfirmView){

	var PhotoHotspotEditorView = Modal.extend({
		
		initialize: function () {
		_.bindAll(this);		
		 _.extend(this.events, Modal.prototype.events);
		},
		events:{

				 },
		
		renderExtend:function(){
			var manageData = new ManageData();
			var num = this.myid.replace("spot","");
			$("#"+this.myid+" header h2").text("Video Hotspot. ID "+num+":")
			var allData = this.model.get("allData")
			var compiledTemplate = _.template(hotspotvideo,{allData:allData,num:num})
			var myid = this.myid;
			$("#"+myid+" .inner-modal").html(compiledTemplate);
			$("#"+myid).find(".fa-close").remove();
			var me = this;
			var $me = $("#"+this.myid);
			$("#"+myid+" header .save-and-close").unbind("click");
			$("#"+myid+" header .save-and-close").click(function(){

				function cback(){
					if($("#confirmDel").size()){
						$("#confirmDel .fa-close").trigger("click");
					}
					if($("#"+me.myid+" .testmodeswitcher").is(":checked")){
						$("#"+me.myid+" .testmodeswitcher").trigger("click");
					}
					var video = $("#"+myid+" #urlvideohotspot").val();
					var tooltiptype = $me.find(".tooltip-controller").data("tooltype")
					var tooltip = $me.find(".tooltip-controller .custom-tooltype").val();

					var hotspot = allData;
					hotspot._video = $("#"+me.myid).data("spotdata")._video;
					hotspot._tooltiptype = tooltiptype;
					hotspot._tooltip = tooltip;

					manageData.changeDataInHotSpot($("#tour").data("scene")._name, hotspot)
					
					var krpano = document.getElementById("krpanoSWFObject");
					$.each(hotspot,function(i,val){
							if(i!="_name"){
								var prop = i.replace("_","");
								krpano.set("hotspot["+hotspot._name+"]."+prop,val)	
							}
						})
					
					$me.fadeOut(function(){
						me.removeThis();
					})
				}

				var msg = "You are saving a hotspot with no content. It will be saved in draft but not updated publish or update the tour."
				if($("#"+myid).data("spotdata")._video==""){
				   var modalModel = new ModalModel({msg:msg,evt:cback})
					var alertView = new ConfirmView({model:modalModel});
					alertView.render("confirmDel",alertView.renderExtend);
				}else{
					cback();
				}

			})

			var selectedset = this.model.get("selectedSet");
			var HotSpotDDModel = Backbone.Model.extend({});
			hotSpotDDModel = new HotSpotDDModel({selectedset:selectedset,kind:"video",elemid:myid});
			var hotSpotsDropDown = new HotSpotsDropDown({model:hotSpotDDModel})
			hotSpotsDropDown.render();
			var spotName =  this.myid;
			$me.find(".removeHotspot").click(function(){
				var krpano = document.getElementById("krpanoSWFObject");
				krpano.call("removehotspot("+spotName+")");
				manageData.removeHotSpot($("#tour").data("scene")._name, spotName);
				$me.fadeOut(function(){
						me.removeThis();
					})
			})

			$me.find("#onoffswitchhpvideo-"+num).click(function(){
				if($(this).is(":checked")){
					var hpdata = $me.data("spotdata");
					var video = hpdata._video;
					var krpano = document.getElementById("krpanoSWFObject");
					krpano.call("addhotspot("+hpdata._name +")");
					krpano.set("hotspot["+hpdata._name+"].video", video );
					
					krpano.call('set(hotspot['+hpdata._name+'].ondown, null );');
					var styleName = $("#"+me.myid).data("spotdata")._style;
                        styleName = styleName.split("|")[0];
                        var clickfunctions;
                        _.each(tourData.krpano.style,function(elem,ind){
                            if(elem._name==styleName){
                                clickfunctions = elem._onclick;
                            }
                        })

                    krpano.call('set(hotspot['+hpdata._name+'].onclick,  '+clickfunctions+' );'); 
					//krpano.call('set(hotspot['+hpdata._name+'].onclick, showpic() );');
					$me.find(".hotspotvideo").delay(200).slideUp(function(){
                       $me.find(".test-mode").fadeIn(); 
                    });
					$me.find(".removeHotspot").fadeOut();

				}else{
					var hpdata = $me.data("spotdata");
					var krpano = document.getElementById("krpanoSWFObject");
					krpano.call("addhotspot("+hpdata._name +")");
					krpano.call('set(hotspot['+hpdata._name+'].ondown, draghotspot() );');
					krpano.call('set(hotspot['+hpdata._name+'].onclick, js(openHotspotWindowEditor('+hpdata._name+')) );');
					$me.find(".hotspotvideo").delay(200).slideDown();
					$me.find(".removeHotspot").fadeIn();
					$me.find(".test-mode").fadeOut();
				}
			})

			var este = this;
			$me.find("#urlvideohotspot").focus(function(){
				var helpFunctions = new HelpFunctions();
				helpFunctions.hideErrorNextToInput($(this));
			}).blur(function(){
				var txt = $(this).val();
				este.youtube_parser(txt);
			})
		
			var ToolTipModel = Backbone.Model.extend({});
		    var toolTipModel = new ToolTipModel({allData:allData,num:num});
			var hotSpotToolTip = new HotSpotToolTip({model:toolTipModel});
			hotSpotToolTip.render();

		},

		removeThis:function(){
			this.undelegateEvents();
			$("#"+this.myid).parent(".overlay").remove();
		},

		youtube_parser:function(url){
			var myid =  this.myid;
			var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
			var match = url.match(regExp);
			if (match&&match[7].length==11){
			    var b=match[7];
			    $("#"+myid).data("spotdata")._video=b;
			}else{
			    $("#urlvideohotspot").val("");
			    var msg = "Error: Wrong youtube link. Please paste here the URL address of the youtube video";
				var helpFunctions = new HelpFunctions();
				helpFunctions.showErrorNextToInput($("#urlvideohotspot"),msg,"width:300px;margin-top:-10px");
			}
		}



		
	});

	return PhotoHotspotEditorView;
	
});
