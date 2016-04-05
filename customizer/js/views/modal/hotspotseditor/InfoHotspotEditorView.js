define([
	'jquery',
	'underscore',
	'backbone',
	'views/modal/Modal',
	'text!templates/modal/hotspotinfo.html',
	'helpers/HelpFunctions',
	'helpers/ManageData',
	'views/modal/HotSpotsDropDown',
	'views/modal/HotSpotToolTip',
	'models/main/ModalModel',
	'views/modal/ConfirmView'


], function($, _, Backbone,Modal,hotspotinfo,HelpFunctions,ManageData,HotSpotsDropDown,HotSpotToolTip,ModalModel,ConfirmView){

	var InfoHotspotEditorView = Modal.extend({
		
		initialize: function () {
		_.bindAll(this);        
		 _.extend(this.events, Modal.prototype.events);
		},
		events:{

				 },
		
		renderExtend:function(){

			var manageData = new ManageData();
			var num = this.myid.replace("spot","");
			var spotName =  this.myid;
			var allData = this.model.get("allData");

			$("#"+spotName+" header h2").text("Info Hotspot. ID "+num+":")

			var compiledTemplate = _.template(hotspotinfo,{allData:allData,num:num})
			$("#"+spotName+" .inner-modal").html(compiledTemplate);
			
			$("#"+this.myid+" header .fa-close").unbind("click")
			var $me = $("#"+this.myid);
			var myid = this.myid;
			var selectedset = this.model.get("selectedSet");
			var HotSpotDDModel = Backbone.Model.extend({});
			hotSpotDDModel = new HotSpotDDModel({selectedset:selectedset,kind:"info",elemid:myid});
			var hotSpotsDropDown = new HotSpotsDropDown({model:hotSpotDDModel})
			hotSpotsDropDown.render();

			$("#"+this.myid).find(".fa-close").remove();
			$("#"+this.myid+" header .save-and-close").unbind("click")
			var me = this;
			
			$("#"+this.myid+" header .save-and-close").click(function(){
				
				function cback(){
					if($("#confirmDel").size()){
							$("#confirmDel .fa-close").trigger("click");
					}
					if($("#"+me.myid+" .testmodeswitcher").is(":checked")){
						$("#"+me.myid+" .testmodeswitcher").trigger("click");
					}
					var infoTitle = $me.find(".infotitle").val();
					var infoText = $me.find(".infotext").val();
					var tooltiptype = $me.find(".tooltip-controller").data("tooltype")
					var tooltip = $me.find(".tooltip-controller .custom-tooltype").val();

					var hotspot = allData;
					hotspot._infotitle = infoTitle;
					hotspot._infotext = infoText;
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
				if($me.find(".infotitle").val() == "" && $me.find(".infotext").val()==""){
				   var modalModel = new ModalModel({msg:msg,evt:cback})
					var alertView = new ConfirmView({model:modalModel});
					alertView.render("confirmDel",alertView.renderExtend);
				}else{
					cback();
				}


			})

			$me.find(".removeHotspot").click(function(){
				var krpano = document.getElementById("krpanoSWFObject");
				krpano.call("removehotspot("+spotName+")");
				$(this).parents(".modal").fadeOut(function(){
					$(this).parent(".hotspotwindow").remove();
				});
				manageData.removeHotSpot($("#tour").data("scene")._name, spotName)
			})

			$me.find("#onoffswitchhpinfo-"+num).click(function(){
				if($(this).is(":checked")){
					
					var hpdata = $me.data("spotdata");
					var infoTitle = $me.find(".infotitle").val();
					var infoText = $me.find(".infotext").val(); 
					var krpano = document.getElementById("krpanoSWFObject");
					krpano.call("addhotspot("+hpdata._name +")");
					
					krpano.call('set(hotspot['+hpdata._name+'].ondown, null );');
					krpano.call('set(hotspot['+hpdata._name+'].onclick, showinfo('+escape(infoTitle)+','+escape(infoText)+') );');
					$me.find(".hotspotinfo").delay(200).slideUp(function(){
					   $me.find(".test-mode").fadeIn(); 
					});
					$me.find(".removeHotspot").fadeOut();

				}else{
					var hpdata = $me.data("spotdata");
					var krpano = document.getElementById("krpanoSWFObject");
					krpano.call("addhotspot("+hpdata._name +")");
					krpano.call('set(hotspot['+hpdata._name+'].ondown, draghotspot() );');
					krpano.call('set(hotspot['+hpdata._name+'].onclick, js(openHotspotWindowEditor('+hpdata._name+')) );');
					$me.find(".hotspotinfo").delay(200).slideDown();
					$me.find(".removeHotspot").fadeIn();
					$me.find(".test-mode").fadeOut();
				}
			})

			var ToolTipModel = Backbone.Model.extend({});
		    var toolTipModel = new ToolTipModel({allData:allData,num:num});
		    var hotSpotToolTip = new HotSpotToolTip({model:toolTipModel});
			hotSpotToolTip.render();

		},
		removeThis:function(){
			this.undelegateEvents();
			$("#"+this.myid).parent(".overlay").remove();
		}

		
	});

	return InfoHotspotEditorView;
	
});
