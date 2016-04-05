define([
	'jquery',
	'underscore',
	'backbone',
	'x2js',
	'jqueryui',
	'text!templates/footer/sceneMenu.html',
	'helpers/HelpFunctions',
	'mCustomScrollbar',
	'views/main/TourView',
	'models/main/TourModel',
	'views/sidebar/SceneSettingsMenuView',
	'views/sidebar/ViewSettingsMenuView',
	'helpers/ManageData',
	'helpers/ManageHotSpots',
	'helpers/ManageTour',


], function($, _, Backbone,x2js,jqueryui, bottomMenu,HelpFunctions,mCustomScrollbar,TourView,TourModel,SceneSettingsMenuView,ViewSettingsMenuView,ManageData,ManageHotSpots,ManageTour){

	var SceneMenuView = Backbone.View.extend({
		el: $("footer.main-footer"),
		mycollection:[],
		initialize: function () {
				_.bindAll(this,"openScene")
			
		},
		events:{
			"click #sceneMenu .selector-scene":"removeItem",
			"click #sceneMenu li img":"openScene",
			"click #remove-selected-scene": "removeSelectedScene",
			"refreshtitlefromscenemenu #sceneMenu": "refreshTitleFromSceneMenu"   
				 },
		render: function(){
			if(this.collection){
				var jsonObj = this.collection.toJSON();
				this.mycollection = this.collection.toJSON();

			}else{
				var jsonObj = [];
			}

			if($(".main-footer .scene-wrapper").length){
				this.undelegateEvents();
				$(".main-footer .scene-wrapper").remove();
			}
			var compiledTemplate = _.template(bottomMenu,{jsonObj:jsonObj});
			$(this.el).append( compiledTemplate ); 
			
			_.each(jsonObj,function(val,ind){
				$("#sceneMenu li:eq("+ind+")").data("scene",val);
				if(val.hotspot){
					$("#sceneMenu li:eq("+ind+")").data("hotspots",val.hotspot)
				}
			})

			var helpFunctions = new HelpFunctions();
			helpFunctions.toolTip("#sceneMenu li img","footer");
			var este = this;
			$("#sceneMenu").sortable({
				beforeStop:function(evt,ui){

					helpFunctions.showReloadOverlay();
					
					var reloadEverything = function(){
						var manageTour = new ManageTour();
						var resetPos = function(){
							if(!$("#sceneMenu li:eq(0)").hasClass("selected")){
								$("#sceneMenu li").removeClass("selected");
								$("#sceneMenu li:eq(0)").addClass("selected");
							   
							}
						}
						var resetElem = function(){
							 helpFunctions.refreshData();
						}
					manageTour.reloadTour(resetPos,resetElem)
					}
					var manageData = new ManageData();
					manageData.SaveNewSceneOrder(reloadEverything)

				}
			});

			var liwidth = $("#sceneMenu li").outerWidth();
			var liright = $("#sceneMenu li").css("margin-right");
			liright = parseInt(liright.replace("px",""));
			var liall = liwidth+liright;
			var allwidth = liall * $("#sceneMenu li").length;
			$("#sceneMenu").width(allwidth+20);

			$(".scene-wrapper").mCustomScrollbar({
							theme:"light",
							scrollInertia:300,
							horizontalScroll: true,
					});

			window.cargarEscenasGlobal = function(sceneName){
				if(window.desdeMenu){
					delete window.desdeMenu;
				}else{
					$("#"+sceneName+" img").trigger("click");
				}
			}
		},

		removeItem:function(e){
			$(e.target).toggleClass("selected")
			if($("#sceneMenu li .selector-scene").hasClass("selected")){
				$("#remove-selected-scene").removeClass("none")
			}else{
				$("#remove-selected-scene").addClass("none");
			}

			if($(e.target).hasClass("selected")){
					$(e.target).parent().addClass("ready-to-del")
			}else{
				$(e.target).parent().removeClass("ready-to-del")
			}
			/*$(e.target).parent().fadeOut(function(){
				var thisname = $(this).data("scene")._scene_id;
				this.remove();
				var manageData = new ManageData();
				var manageTour = new ManageTour();
				manageData.deleteScene(manageTour.reloadTour,thisname);
				$("#sceneMenu li:eq(0)").addClass("selected")
			})*/
		},

		removeSelectedScene:function(){
			var scenesToDel = []
				$("#sceneMenu li .selector-scene").hasClass("selected")
				_.each($("#sceneMenu li"),function(el,i){
						if($(el).find(".selector-scene").hasClass("selected")){
							scenesToDel.push($(el).attr("id").replace("scene_",""));
						}
				})

			var manageData = new ManageData();
			var reloadEverything = function(){
						$("#remove-selected-scene").addClass("none")
						var manageTour = new ManageTour();

						var resetPos = function(){
							if(!$("#sceneMenu li:eq(0)").hasClass("selected")){
								$("#sceneMenu li").removeClass("selected");
								$("#sceneMenu li:eq(0)").addClass("selected");
							}

						if($(".scene-wrapper li").size()!=0){
							var liwidth = $("#sceneMenu li").outerWidth();
							var liright = $("#sceneMenu li").css("margin-right");
							liright = parseInt(liright.replace("px",""));
							var liall = liwidth+liright;
							var allwidth = liall * $("#sceneMenu li").length;
							$("#sceneMenu").width(allwidth+20);
							$(".scene-wrapper").mCustomScrollbar("update")
						}else{
							$("#sceneMenu").html('<li class="empty"><i class="fa fa-plus fa-2x"></i></li>');
							if($("#onoffswitchpub").is(":checked")){
								$("#publishController").trigger("unpublishforce");
							}
						}
						
						}
						var resetElem = function(){
							var helpFunctions = new HelpFunctions();
							 helpFunctions.refreshData();
						}
					manageTour.reloadTour(resetPos,resetElem)
					}
			manageData.deleteSceneFromServer(scenesToDel,reloadEverything);  
		},

		openScene:function(e){
		   
		   window.desdeMenu = true;
			
			var helpFunctions = new HelpFunctions();

			$thisli = $(e.target).parent();
			$("#tour").data("scene",$thisli.data("scene"))
			helpFunctions.refreshData();
			var customparam = jQuery.extend({},$thisli.data("scene"));
			delete customparam.view._segment;
			delete customparam.preview._segment;
			delete customparam.preview._url;
			delete customparam.image._segment;
			delete customparam._segment;
			delete customparam.hotspot;
			var krpano = document.getElementById("krpanoSWFObject");
			var param = helpFunctions.mapJSONToUriParams(customparam);
			param = param.replace(/:_/g,".");
			krpano.call("loadscene('"+$thisli.attr("id")+"','"+param+"',MERGE|KEEPCONTROL,BLEND(1));");
			if($thisli.data("hotspots")){
				var manageHotSpots = new ManageHotSpots();
				krpano.set("events.onpreviewcomplete","js(initHotSpots())");
				krpano.set("events.keep",true);
			}
			$("#sceneMenu li").removeClass("selected");
			$thisli.addClass("selected")

			$(".hotspotwindow .save-and-close").trigger("click");

			//this.openAsideMenu();

			if($("#sceneSettings-menu").size()){
				$("#sceneSettings-menu").trigger("refreshmapposition",[$thisli[0]])
			}

		},

		refreshTitleFromSceneMenu:function(){
			var scene = $("#tour").data("scene");
			var title = scene._title;
			var name = scene._name;
			$("#"+name+" img").unbind("mouseenter")
			$("#"+name+" img").unbind("mouseleave")
			$("#"+name+" img").attr("title",title);
			var helpFunctions = new HelpFunctions();
			helpFunctions.toolTip("#"+name+" img","footer");
		}
		
	});

	return SceneMenuView;
	
});
