define([
	'jquery',
	'underscore',
	'backbone',
	'views/modal/Modal',
	'text!templates/modal/liveTour.html',
	'helpers/HelpFunctions',
	'views/modal/SingleUploader',
	'views/sidebar/MapView',
	'views/modal/SocialModal',
	'lib/tagsinput/tagsinput',
	'helpers/ManageData',
	'models/main/ModalModel',
	'views/modal/AlertView'

], function($, _, Backbone,Modal,LiveTour, HelpFunctions,SingleUploader,MapView,SocialModal,tagsinput,ManageData,ModalModel,AlertView){

	var MapModalView = Modal.extend({
		
		initialize: function () {
		_.bindAll(this);        
		 _.extend(this.events, Modal.prototype.events);
		},
		events:{
			"click #reset-live-thumb":"resetThumb",
			"click #make-live-bt":"goLive",
			"click #location-lt li":"selectLocation",
			"blur .liveTourModal #firendlyUrl":"changeFriendlyURL",
			"blur .liveTourModal #description":"saveData",
			"blur .liveTourModal #title":"saveTitle",
			"click #category-lt li":"saveDDCategory",
			"click #privacy-lt li":"saveDDPrivacy",
			"click .liveTourModal .check-group li":"CheckUncheck"
		},
		
		renderExtend:function(){

			var myid = this.myid,
				este = this;

			$("#"+myid+" header h2").text("GO LIVE!");

			if(tourData.krpano.datatour.friendlyURL == ""){
				tourData.krpano.datatour.friendlyURL=location.hash.split("/")[1];
			}
			
			var usernick = $(".main-header .user").data("nickname");
			var tourInfo = {
				settings:tourData.krpano.settings,
				datatour:tourData.krpano.datatour,
				usernick:usernick
			}

			var helpFunctions = new HelpFunctions();
			var template = _.template(LiveTour,{tourInfo:tourInfo});

			$("#"+myid+" .inner-modal").html(template);                   
			
			helpFunctions.checkbox("#"+myid+" .check-group","fa-check-square-o","fa-square-o");            
			
			helpFunctions.dropDown("#location-lt","h3");

			var tour_id = location.hash.split("/")[1];
			var caso = 'tour_thumb';
			
			var SingleUploaderModel = Backbone.Model.extend({});
			var singleUploaderModel = new SingleUploaderModel({myid:"live-tour-img-uploader",imgsrc:tourData.krpano.datatour.tour_thumb_path,tour_id:tour_id,caso:caso})
			
			este.singleUploader = new SingleUploader({model:singleUploaderModel});
			este.singleUploader.render(this.changeImage);


			$("#location-lt .title").text($('li[data-loc="'+tourInfo.datatour.show_lat_lng+'"]').text());
			$(".liveTourModal #loc-"+tourInfo.datatour.show_lat_lng).removeClass("none")
			$.ajax({
					url : 'data/json.php?t=c',
					type: 'JSON',
					cache : false,
					success : function(data){
						var data = JSON.parse(data);
						_.each(data,function(elem,ind){
							$("#category-lt ul.category").append("<li><span>"+elem.category+"</span></li>")
						})
						helpFunctions.dropDown("#category-lt","h3");
						$("#category-lt ul.category").mCustomScrollbar({
								theme:"minimal-dark",
								scrollInertia:300
							});
						$("#category-lt ul.category").height(200);
						/*$("#category-lt h3").click(function(){
							setTimeout(function(){ 
								var altura = $("#liveTourModal").height()-$("#category-lt").offset().top;
								altura = altura-30;
								$("#category-lt ul.category").height(altura);
							},500)
						})*/
					}
				});

			$.ajax({
					url : 'data/json.php?t=p',
					type: 'JSON',
					cache : false,
					success : function(data){

						var data = JSON.parse(data);
						_.each(data,function(elem,ind){
							if(tourData.krpano.datatour.privacy == elem.value){
								$("#privacy-lt .title").text(elem.privacy);
							}
							$("#privacy-lt ul.privacy").append('<li id="'+elem.value+'"><span>'+elem.privacy+'</span></li>')
						})
						helpFunctions.dropDown("#privacy-lt","h3");
						 $("#privacy-lt ul.privacy").mCustomScrollbar({
								theme:"minimal-dark",
								scrollInertia:300
							});
			
					}
				});


			$("#"+myid).find("header .fa-close").unbind().click( function (e) {
				este.removeModal(e);
				este.undelegateEvents();
				este.closePublish();
			});
			$("#"+myid).find('footer .cancel').click(function (e) {
				este.removeModal(e);
				este.undelegateEvents();
				este.closePublish();
			});
			var opening = true;
			$('.liveTourModal .tagLiveModal2').tagsInput({
			'width': '265px',
			'height':'70px',
			'defaultText':'add a tag',
			onChange: function(elem, elem_tags)
			{
				if(!opening){
					var manageData = new ManageData();
					manageData.saveTourData("tags",$(".liveTourModal .tagLiveModal2").val());
					$('#tagsTour').addTag(elem_tags);
				}
			},
			autocomplete_url:'../php-stubs/tags.php', // jquery ui autocomplete requires a json endpoint
			autocomplete:{appendTo:"#toAppendTagsModal",

					open:function(){
						$("#toAppendTagsModal .ui-widget-content").mCustomScrollbar({
							theme:"minimal-dark",
							scrollInertia:300,
							});
					},
					response:function(){
						$("#toAppendTagsModal .ui-widget-content").mCustomScrollbar("destroy")
					},
					close:function(){
						$("#toAppendTagsModal .ui-widget-content").mCustomScrollbar("destroy")
						}
					}
			});

			var MapModel = Backbone.Model.extend({});
			var mapModel = new MapModel({lat:tourData.krpano.settings._lat,lng:tourData.krpano.settings._long})            
			this.mapView = new MapView({model:mapModel});
			this.mapView.render("liveTourModal","settings");
			 /*   
			var indice = $("#sceneMenu .selected").index();
			var param = "scene";

			this.mapView.render("elem",{param:param,indice:indice});*/
			//$("#"+myid).find(".save").click(this.goLive);

			$(".scrollwrapper").mCustomScrollbar({
				theme:"minimal-dark",
				scrollInertia:300
			});
			$(".liveTourModal #description, .liveTourModal #firendlyUrl, .liveTourModal #title").focus(function(e){
				
				$(window).keydown(function(event){
					if(event.keyCode == 13) {
						$(e.target).blur();
						event.preventDefault();
						$(window).unbind("keydown");
						return false;
						}
				  });
			})

			$(".liveTourModal #firendlyUrl").focus(function(e){
					helpFunctions.hideErrorNextToInput($("#firendlyUrl"));
			})

			opening = false;
			this.modalHeight(myid);  
			this.verticalCent();


			var inputWidth = $(".liveTourModal .url").width() - $(".liveTourModal .url span").width();
			inputWidth = inputWidth-20;
			$(".liveTourModal .url input").width(inputWidth);
		
		},

		changeImage:function(){
			tourData.krpano.datatour.tour_thumb_custom = "1",
			$("#reset-live-thumb").show();
			tourData.krpano.datatour.tour_thumb_path = $("#live-tour-img-uploader img").attr("src");
		},

		goLive: function(e) {

			if($(e.target).hasClass("disabled")){
				return;
			}
			
			var este = this;

			var callB = function(){
			   //socialModal = new SocialModal();
			   //socialModal.render("socialModal",socialModal.renderExtend);
			   	este.removeModal(e);
			    este.undelegateEvents();
				$("#draft").removeClass("active");
				$("#draft").data("live","published")

				var msg = "Live tour updated";
				var modalModel = new ModalModel({msg:msg})
				var alertView = new AlertView({model:modalModel});
				alertView.render("alert",alertView.renderExtend);
				$("#tourTitleBar").trigger("updatepublish",["on"])
				$("#publishController .onoffswitch").attr("title"," Last update: "+tourData.krpano.datatour.date_published)
				var helpFunctions = new HelpFunctions();
				helpFunctions.toolTip("#publishController .onoffswitch", "publish up");

				if(tourData.krpano.datatour.brand_new=="1" && tourData.krpano.datatour.privacy=="_public"){
					var tourid = location.hash.split("/")[1];
					$.ajax({
						url:  "../ajax_notice.php?type=6&t="+tourid,
						dataType:"json",
						success:function(res){

						}
					})

					if(tourData.krpano.datatour.brand_new == "1"){
						tourData.krpano.datatour.brand_new = "0";
					}
				}
			

			}
			
			var manageData = new ManageData();
			manageData.saveLive("live",callB);
			this.undelegateEvents();

		},

		selectLocation:function(e){
			var manageData = new ManageData();
			manageData.saveTourData("show_lat_lng",$(e.target).data("loc"));
			$(".loc-description").addClass("none");
			$("#loc-"+$(e.target).data("loc")).removeClass("none")
			if($("#virtualTourSettings-menu").size()){
				$("#virtualTourSettings-menu .checkboxes li[data-val="+$(e.target).data("loc")+"]").trigger("click");
			}
			
		},

		resetThumb:function(){
			var manageData = new ManageData();
			este = this;
			var cbak = function(){
				este.singleUploader.removeThis();
				var SingleUploaderModel = Backbone.Model.extend({});
				var tour_id = location.hash.split("/")[1];
				var caso = 'tour_thumb';
				var singleUploaderModel = new SingleUploaderModel({myid:"live-tour-img-uploader",imgsrc:tourData.krpano.datatour.tour_thumb_path,tour_id:tour_id,caso:caso})
				este.singleUploader = new SingleUploader({model:singleUploaderModel});
				este.singleUploader.render(este.changeImage);
				$("#reset-live-thumb").hide();
			}

			manageData.resetThumb(cbak);
		},

		modalHeight: function(myid){

			var el = $("#"+myid+" .scrollwrapper"),
			winH = $(window).height();
			$(el).css({'height': (winH - 300) + 'px'})

		},

		closePublish: function () {
			$('header .onoffswitch-inner').click();
		},

		changeFriendlyURL:function(e){
			var urlname = encodeURIComponent($("#firendlyUrl").val()) ;
			var tourid = location.hash.split("/")[1];
			$.ajax({
				url:'data/json.php?t=chk_friendly&id='+tourid+'&friendly='+urlname,
					type:'GET',
					success:function(res){
						res = JSON.parse(res);

						if(res.result=="ERROR"){
							var helpFunctions = new HelpFunctions();
							helpFunctions.showErrorNextToInput($("#firendlyUrl"),res.msg);
						}

						var finalfriendlyURL = res.friendly_URL;
						$("#firendlyUrl").val(finalfriendlyURL);
						var manageData = new ManageData();
						manageData.saveTourData("friendlyURL",finalfriendlyURL);
						if($("#virtualTourSettings-menu").size()){
							$("#virtualTourSettings-menu #friendlyURLTour").val(finalfriendlyURL);
						}
						},
					error:function(xhr, ajaxOptions, thrownError){
						console.log(xhr)
					}
				})	
			
			$(window).unbind("keydown");
		},

		saveData:function(e){
			var manageData = new ManageData();
			manageData.saveSettings(e);
			$(window).unbind("keydown");
			if($("#virtualTourSettings-menu").size()){
				$("#virtualTourSettings-menu #tour-description").val($(e.target).val());
			}
		},

		saveTitle:function(e){
			var manageData = new ManageData();
			manageData.saveSettings(e);
			$(window).unbind("keydown");
			$("#tourTitleForm #tour-title").val($(e.target).val());
		},

		CheckUncheck:function(e){

			var name = $(e.target).prop("tagName")
			if(name == "LI"){
			$chkbox = $(e.target).find(".fa-lg")
			}else{
			$chkbox = $(e.target)
			}
			var attrid = $chkbox.attr("id");
			attrid = attrid.replace("modal_","")
		
			if($("#virtualTourSettings-menu").size()){
				$("#virtualTourSettings-menu #"+attrid).trigger("click");
			}else{
				var manageData = new ManageData();
				if($chkbox.hasClass("fa-check-square-o")){
					manageData.saveTourData(attrid,"on")
				}else{
					manageData.saveTourData(attrid,"off")
				}
			}

		},

		saveDDCategory:function(e){
			var manageData = new ManageData();
			manageData.saveTourData("category",$(e.target).text());
			if($("#virtualTourSettings-menu").size()){
				$("#virtualTourSettings-menu #Category .title").text($(e.target).text())
			}
		},

		saveDDPrivacy:function(e){
			var manageData = new ManageData();
			manageData.saveTourData("privacy",$(e.target).attr("id"));
			if($("#virtualTourSettings-menu").size()){
				$("#virtualTourSettings-menu #privacyConf h2 .title").text($(e.target).text())
			}
		}

	});

	return MapModalView;
	
});
