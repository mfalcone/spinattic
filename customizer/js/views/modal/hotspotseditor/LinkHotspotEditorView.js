define([
	'jquery',
	'underscore',
	'backbone',
	'views/modal/Modal',
	'text!templates/modal/hotspotlink.html',
	'helpers/HelpFunctions',
	'views/modal/HotSpotsDropDown',
	'views/modal/HotSpotToolTip',
	'helpers/ManageData',
	'models/main/ModalModel',
	'views/modal/ConfirmView',


], function($, _, Backbone,Modal,hotspotlink,HelpFunctions,HotSpotsDropDown,HotSpotToolTip,ManageData,ModalModel,ConfirmView){

	var LinkHotspotEditorView = Modal.extend({
		
		initialize: function () {
		_.bindAll(this);        
		 _.extend(this.events, Modal.prototype.events);
		},
		events:{

				 },
		
		renderExtend:function(){
			var manageData = new ManageData();
			var num = this.myid.replace("spot","");
			var myid = this.myid;
			
			$("#"+this.myid+" header h2").text("Link Hotspot. ID "+num+":")
			var allData = this.model.get("allData")
			var compiledTemplate = _.template(hotspotlink,{allData:allData,num:num})
			var myid = this.myid;
			$("#"+this.myid+" .inner-modal").html(compiledTemplate);

			var helpFunctions = new HelpFunctions();
			helpFunctions.dropDown("#"+this.myid+" .dropdowntarget");
			
			var me = this;
			$("#"+myid).find(".fa-close").remove();
			var $me = $("#"+this.myid);
			$("#"+myid+" header .save-and-close").unbind("click")

			$("#"+myid+" header .save-and-close").click(function(){
				function cback(){
					if($("#confirmDel").size()){
							$("#confirmDel .fa-close").trigger("click");
					}
					if($("#"+me.myid+" .testmodeswitcher").is(":checked")){
						$("#"+me.myid+" .testmodeswitcher").trigger("click");
					}
					var linkurl = $("#"+myid+" .urllinkhotspot").val();
					var tooltiptype = $me.find(".tooltip-controller").data("tooltype")
					var tooltip = $me.find(".tooltip-controller .custom-tooltype").val();
					var target = $("#"+myid+" .dropdowntarget h2 .title").text();
					var hotspot = allData;

					hotspot._linkurl = linkurl;
					hotspot._target = target;
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
				if($("#"+myid+" .urllinkhotspot").val()==""){
				   var modalModel = new ModalModel({msg:msg,evt:cback})
					var alertView = new ConfirmView({model:modalModel});
					alertView.render("confirmDel",alertView.renderExtend);
				}else{
					cback();
				}
			})

			var selectedset = this.model.get("selectedSet");
			var HotSpotDDModel = Backbone.Model.extend({});
			hotSpotDDModel = new HotSpotDDModel({selectedset:selectedset,kind:"link",elemid:myid});
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

			$me.find("#onoffswitchhplink-"+num).click(function(){
				if($(this).is(":checked")){
					
					var hpdata = $me.data("spotdata");
					
					var linkurl = $("#"+myid+" .urllinkhotspot").val();
					if(!linkurl.substring(0,4)=="http"){
						linkurl = "http://"+linkurl
					}
					var target = $("#"+myid+" .dropdowntarget h2 .title").text();
					var krpano = document.getElementById("krpanoSWFObject");
					krpano.call("addhotspot("+hpdata._name +")");
					krpano.call('set(hotspot['+hpdata._name+'].ondown, null );');
					krpano.call('set(hotspot['+hpdata._name+'].onclick, openurl('+linkurl+','+target+') );');
					$me.find(".hotspotlink").delay(200).slideUp(function(){
					   $me.find(".test-mode").fadeIn(); 
					});
					$me.find(".removeHotspot").fadeOut();

				}else{
					var hpdata = $me.data("spotdata");
					var krpano = document.getElementById("krpanoSWFObject");
					krpano.call("addhotspot("+hpdata._name +")");
					krpano.call('set(hotspot['+hpdata._name+'].ondown, draghotspot() );');
					krpano.call('set(hotspot['+hpdata._name+'].onclick, js(openHotspotWindowEditor('+hpdata._name+')) );');
					$me.find(".hotspotlink").delay(200).slideDown();
					$me.find(".removeHotspot").fadeIn();
					$me.find(".test-mode").fadeOut();
				}
			})
			var este = this;

			$me.find(".urllinkhotspot").focus(function(){

				$(this).data('placeholder',$(this).attr('placeholder'))
          		.attr('placeholder','');
          		helpFunctions.hideErrorNextToInput($(this));
			}).blur(function(){
				var txt = $(this).val();
				$(this).attr('placeholder',$(this).data('placeholder'));
				if(!este.isUrl(txt)){
					var msg = "wrong URL format, please add http:// or https://";
					var helpFunctions = new HelpFunctions();
					helpFunctions.showErrorNextToInput($(this),msg,"width:300px;margin-top:-20px");
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
		},

		isUrl:function(s){
		   var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
		   return regexp.test(s);
		}

		
	});

	return LinkHotspotEditorView;
	
});
