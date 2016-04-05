define([
	'jquery',
	'underscore',
	'backbone',
	'text!templates/header/publishcontrollerview.html',
	'views/modal/LiveTourView',
	'helpers/HelpFunctions',
	'helpers/ManageData',
	'models/main/ModalModel',
	'views/modal/ConfirmView',
	'views/modal/AlertView'


], function($, _, Backbone, publishcontrollerview, LiveTourView, HelpFunctions,ManageData,ModalModel,ConfirmView,AlertView){

	var PublishControllerView = Backbone.View.extend({

		el: $(".header-bottom"),

		initialize: function () {
		  
		},

		events:{  
			"click #onoffswitchpub": "openLiveModal",
			"click #publish.active":"sendToLive",
			"savingtour #publishController":"savingTour",
			"savedtour #publishController":"savedTour",
			"unpublishforce #publishController":"forceUnpublish"
		},

		render: function(){
			var este = this;
			var helpFunctions = new HelpFunctions();
			var datepub = tourData.krpano.datatour.date_published;
			var dateupdate = tourData.krpano.datatour.date_updated;
			var deploy;
			if(datepub != ""){
				var published = new Date(datepub);
				var updated = new Date(dateupdate);
				if(updated > published){
					deploy = true;
				}
			}
			var compiledTemplate = _.template(publishcontrollerview,{datepub:datepub,dateupdate:dateupdate,deploy:deploy});
			$(this.el).append( compiledTemplate );

			if(datepub!=""){
				$("#draft").data("live","published")
				$("#publish").attr("title","Deploy draft to LIVE version");
				helpFunctions.toolTip("#publishController #publish", "publish up");

			}
			if ($('#onoffswitchpub').is(":checked")) {
				helpFunctions.toolTip("#publishController .onoffswitch", "publish up");
				$("#tourTitleBar").trigger("updatepublish",["on"])
			}
			helpFunctions.toolTip("#publishController .fa-question-circle", "publish up");
			

			//avoid tooltip bubble up
			$("#publishController .onoffswitch .onoffswitch-inner").mouseenter(function(e){
				e.stopPropagation();
			});

		},

		openLiveModal: function(e) {

			

			var helpFunctions = new HelpFunctions();

			if($(e.target).is(":checked")){

				if(tourData.krpano.scene==undefined){
					var msgunpub = "You can't publish a tour without panos";
					var modalModelunpub = new ModalModel({msg:msgunpub})
					var alertViewunpub = new AlertView({model:modalModelunpub});
					alertViewunpub.render("alert",alertViewunpub.renderExtend);
					$("#onoffswitchpub").prop("checked",false);
					return;
				}

				var callBackHP = function(){
					if($("#confirmHP").size()){
						$("#confirmHP .fa-close").trigger("click");
						$("#onoffswitchpub").prop("checked",true);
					}
					this.liveTourView = new LiveTourView();
					this.liveTourView.render("liveTourModal",this.liveTourView.renderExtend);
				}

				var hotspotMessage = this.checkHotSpots();
				if(hotspotMessage!=""){
					$("#onoffswitchpub").prop("checked",false);
					var modalModel = new ModalModel({msg:hotspotMessage,evt:callBackHP})
					var alertView = new ConfirmView({model:modalModel});
					alertView.render("confirmHP",alertView.renderExtend);
				}else{
					callBackHP();
				}

				
			
			} else {

				var msg = "Are you sure you want to turn your tour Offline?";
				var evt = function(){
					var manageData = new ManageData();
					manageData.saveLive("notlive");
					 $("#draft").data("live","unpublished")
					$('#publishController .onoffswitch').unbind('mouseenter');
					$('#publishController .onoffswitch').removeAttr('title');
					$("#confirmDel .fa-close").trigger("click");
					$("#onoffswitchpub").prop("checked",false);
					$("#publish").removeClass("active")
					$("#publishController #publish").unbind("mouseenter");
					$("#publishController #publish").removeAttr("title");
					$("#tourTitleBar").trigger("updatepublish",["off"])
			
				}
				$("#onoffswitchpub").prop("checked",true);
				var modalModel = new ModalModel({msg:msg,evt:evt})
				var alertView = new ConfirmView({model:modalModel});
				alertView.render("confirmDel",alertView.renderExtend);

			}
		},

		sendToLive:function(){
			

				var callB = function(){
					$("#draft").removeClass("active");
					$("#publish .loading").remove();
					$("#publish").removeClass("active")
					$("#publishController #publish").unbind("mouseenter");
					$("#publishController #publish").removeAttr("title");
				}

				var callBackHP = function(){
					if($("#confirmHP").size()){
						$("#confirmHP .fa-close").trigger("click");
					}
					$("#publish").html('<div class="loading"></div>')
					var manageData = new ManageData();
					manageData.saveLive("live",callB);
				}
			
			var hotspotMessage = this.checkHotSpots();
			if(hotspotMessage!=""){	
				var modalModel = new ModalModel({msg:hotspotMessage,evt:callBackHP})
				var alertView = new ConfirmView({model:modalModel});
				alertView.render("confirmHP",alertView.renderExtend);	
				}else{
					$("#publish").html('<div class="loading"></div>')
					var manageData = new ManageData();
					manageData.saveLive("live",callB);
				}
			},

		savingTour:function(){
			$("#publish").removeClass("active");
			$("#publishController #publish").unbind("mouseenter");
			$("#publishController #publish").removeAttr("title");
			if(!$("#publishController #draft").hasClass("active")){
					$("#publishController #draft").addClass("active")
				}
				$("#draft .loading-wrapper").show();
		},
		savedTour:function(evt,text){
			$("#draft .loading-wrapper").html('<i class="fa fa-check"></i> Draft Saved').delay(1000).fadeOut(function(){
							$("#draft .loading-wrapper").html('<div class="loading"></div>')
						});
			if($("#draft").data("live") == "published"){
					$("#publish").addClass("active");
					$("#publish").attr("title","Deploy draft to LIVE version");
					var helpFunctions = new HelpFunctions();
					helpFunctions.toolTip("#publishController #publish", "publish up");
				}
						$("#draft .date").text(text);
		} ,

		forceUnpublish:function(){
			var manageData = new ManageData();
			manageData.saveLive("notlive");
			 $("#draft").data("live","unpublished")
			$('#publishController .onoffswitch').unbind('mouseenter');
			$('#publishController .onoffswitch').removeAttr('title');
			$("#confirmDel .fa-close").trigger("click");
			$("#onoffswitchpub").prop("checked",false);
			$("#publish").removeClass("active")
			$("#publishController #publish").unbind("mouseenter");
			$("#publishController #publish").removeAttr("title");
			$("#tourTitleBar").trigger("updatepublish",["off"])
		},

		checkHotSpots:function(){
			var str = "";
			_.each(tourData.krpano.scene,function(elem,ind){
				_.each(elem.hotspot,function(hp,indice){
					switch(hp._kind){
						case "info":
						if(hp._infotitle==""&&hp._infotext==""){
							str += "scene: "+elem._title+" &gt; "+hp._kind+" &amp; "+hp._name+"<br>";
						}
						break;
						case "arrow":
						if(hp._linkedscene ==""){
							str += "scene: "+elem._title+" &gt; "+hp._kind+" &amp; "+hp._name+"<br>";
						}
						break;
						case "link":
						if(hp._linkurl ==""){
							str += "scene: "+elem._title+" &gt; "+hp._kind+" &amp; "+hp._name+"<br>";
						}
						break;
						case "photo":
						if(hp._pic ==""){
							str += "scene: "+elem._title+" &gt; "+hp._kind+" &amp; "+hp._name+"<br>";
						}
						break;
						case "video":
						if(hp._video ==""){
							str += "scene: "+elem._title+" &gt; "+hp._kind+" &amp; "+hp._name+"<br>";
						}
						break;
					}
				})
			})

			if(str!=""){
				var msg="There are empty hotspots in the tour that are not going to be published in the Live version: <br>";
				str = msg+str;
			}

			return str;
		}
	});

	return PublishControllerView;  
});
