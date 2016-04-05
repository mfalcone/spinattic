define([

	'jquery',
	'underscore',
	'backbone',
	'x2js',
	'views/footer/SceneMenuView',
	'views/footer/PanoMenuFooterView',
	'collections/footer/SceneCollection',
	'collections/header/UserCollection',
	'views/sidebar/MainMenuView',
	'views/header/UserView',
	'views/main/UploaderViewD',
	'helpers/HelpFunctions',
	'models/main/TourModel',
	'views/main/TourView',
	// 'views/users/list'
	'views/header/TourTitle',
	'views/header/PublishControllerView',
	'lib/krpano/embedpano'  


], function($, _, Backbone, x2js, SceneMenuView, PanoMenuFooterView, SceneCollection, UserCollection, MainMenuView, 
	UserView, UploaderView, HelpFunctions, TourModel, TourView, TourTitle, PublishControllerView){

	var AppRouter = Backbone.Router.extend({
		routes: {
			// Define some URL routes
			'tour/:id': 'getTour',
			// Default
			'*actions': 'defaultAction'
		}
	});

	var initialize = function(){        
		
			var app_router = new AppRouter;
		var userCollection = {};

		$(".main-footer").hide();
		$(".header-bottom").hide();
		var helpFunctions = new HelpFunctions();
		helpFunctions.setInnerHeight(".main-section","byClass");
		var panoMenuFooterView = new PanoMenuFooterView();
		panoMenuFooterView.render();
	   
		
		/*window.onpopstate = function(event)
		{
			location.reload();
		};*/
		
		$(window).resize(function(){
			
			helpFunctions.setInnerHeight(".main-section","byClass");        

		});

		jQuery.ajax({
			type: "POST",
			url: "php/ajax_chk_session.php",
			data: "chklogout=true",
			cache: false,
			success: function(res){
				if(res == "1"){
					location.href=  "http://"+location.host+"/index.php?login";
				}else{
					$.ajax({
						dataType:"json",
						url:  "data/json.php?t=u",
					}).done(function(obj){

						if(!userCollection.length){
							userCollection = new UserCollection(obj);
							userView = new UserView({ collection: userCollection});
							userView.render();
						}
					})
				}
			}
		})

		helpFunctions.checkUser();
		helpFunctions.detectBrowser();


		app_router.on('route:getTour', function (id) {

			jQuery.ajax({
			type: "GET",
			url: "php/general_process.php?reset_queue="+id,
			success: function(res){
				//console.log("reseted")
			}})
		// Note the variable in the route definition being passed in here
			$.ajax({
		  dataType:"json",
		  url:  "data/json.php?t=u",
		 }).done(function(obj){
		 	var userid = obj.id;
			//var xmlpath ="data/tour.xml?id="+id;
			var xmlpath ="data/xml.php?id="+id+"&d=1&c=1&customizer=1";
			$.ajax({
			 url: xmlpath,
				type: "GET",
				dataType: "html",
				success: function(data) {


					var x2js = new X2JS({attributePrefix:"_"});
					console.log("===========================XML===========================")
					console.log(data);
					tourData =  x2js.xml_str2json( data );
					console.log("===========================JSON===========================")
					console.log(tourData)
					helpFunctions.prepareConditionsForTour();

					$.ajax({
						url:  "data/json.php?id="+id+"&d=1&t=t",
						dataType:"json",
						success:function(datatour){

							if(datatour.owner_id != userid){
								location.href = "http://"+location.host+"/tours"
							}

							 $(".main-footer").show();
							$(".header-bottom").show();

							tourData.krpano.datatour = datatour;
							var xml2krpano = xmlpath.replace("&c=1","&h=0");
							//var xml2krpano = "data/tour209em.xml"
							
							var tourModel = new TourModel({xmlpath:xml2krpano});
						
							var tourView = new TourView({ model: tourModel});
							tourView.render();
							var scenes = tourData.krpano.scene;

							var sceneCollection = new SceneCollection(scenes);
							var sceneMenuView = new SceneMenuView({ collection: sceneCollection});
							sceneMenuView.render();
							var mainMenuView = new MainMenuView();
							mainMenuView.render();
							var tourTitle = new TourTitle();
							tourTitle.render();
							var publishControllerView = new PublishControllerView();
							publishControllerView.render();
							
						}
					})
				}
			});
		 })
	});


	app_router.on('route:defaultAction', function(actions){

		var sceneMenuView = new SceneMenuView();
		sceneMenuView.render();
		

		var interroute = setInterval(function(){
			if($(".main-header .user").data("level")!=undefined){
				var UploaderModel = Backbone.Model.extend({});
				uploaderModer = new UploaderModel({gNewTour:true,addingPane:false});
				uploaderview = new UploaderView({model:uploaderModer});
				uploaderview.render();
				clearInterval(interroute)
			}
		},300)
		
		$(".main-footer").hide();
		$(".header-bottom").hide();

	});

	Backbone.history.start();
		
	};

	return {
		initialize: initialize
	};
});