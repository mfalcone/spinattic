define([

	'jquery',
	'underscore',
	'backbone',
	'x2js',
	'helpers/HelpFunctions',
	'models/main/TourModel',
	'views/main/TourView',
	'views/mobile/MobileMainMenu',
	'views/modal/AlertView',
	'lib/krpano/embedpano'


], function($, _, Backbone, x2js, HelpFunctions, TourModel, TourView,MobileMainMenu,AlertView){

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
		//var userCollection = {};

		var helpFunctions = new HelpFunctions();
		//helpFunctions.setInnerHeight(".main-section","byClass");
		
		
		/*jQuery.ajax({
			type: "POST",
			url: "php/ajax_chk_session.php",
			data: "chklogout=true",
			cache: false,
			success: function(res){
				if(res == "1"){
					location.href= "http://dev.spinattic.com/index.php?login";
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
		})*/

		//helpFunctions.checkUser();
		//helpFunctions.detectBrowser();


		app_router.on('route:getTour', function (id) {

			jQuery.ajax({
			type: "GET",
			url: "php/general_process.php?reset_queue="+id,
			success: function(res){
				console.log("reseted")
			}})
		// Note the variable in the route definition being passed in here
			$.ajax({
			  dataType:"json",
			  url:  "data/json.php?t=u",
			 }).done(function(obj){
				//var xmlpath ="data/tour209.xml?id="+id;
				var xmlpath ="data/xml.php?id="+id+"&d=1&c=1&customizer=1";

				var locationurl = location.href;
				var hmafull = locationurl.substring(locationurl.lastIndexOf("?")+1,locationurl.lastIndexOf("#"));
				var hma = hmafull.replace("hma=","");
			
				if(hma!=location.origin+location.pathname){
					xmlpath=xmlpath+"&"+hma;
					console.log(xmlpath)
				}

				$.ajax({
				 url: xmlpath,
					type: "GET",
					dataType: "html",
					success: function(data) {
						console.log(data)
						var x2js = new X2JS({attributePrefix:"_"});

						tourData =  x2js.xml_str2json( data );
						console.log(tourData)
						helpFunctions.prepareConditionsForTour();

						$.ajax({
							url:  "data/json.php?id="+id+"&d=1&t=t",
							dataType:"json",
							success:function(datatour){

				
								tourData.krpano.datatour = datatour;
								var xml2krpano = xmlpath.replace("&c=1","&h=0");
								//var xml2krpano = "data/tour209em.xml"
								
								var tourModel = new TourModel({xmlpath:xml2krpano});
							
								var tourView = new TourView({ model: tourModel});
								tourView.render();

								var mobileMainMenu = new MobileMainMenu();
								mobileMainMenu.render();

								var scenes = tourData.krpano.scene;

								
								
							}
						})
					}
				});
			 })
	});


	app_router.on('route:defaultAction', function(actions){

		var sceneMenuView = new SceneMenuView();
		sceneMenuView.render();
		var UploaderModel = Backbone.Model.extend({});
		uploaderModer = new UploaderModel({gNewTour:true,addingPane:false});
		uploaderview = new UploaderView({model:uploaderModer});
		uploaderview.render();

		$(".main-footer").hide();
		$(".header-bottom").hide();

	});

	Backbone.history.start();
		
	};

	return {
		initialize: initialize
	};
});