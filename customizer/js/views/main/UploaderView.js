define([
	'jquery',
	'underscore',
	'backbone',
	'x2js',
	'helpers/HelpFunctions',
	'helpers/ManageTour',
	'helpers/ManageData',
	'models/main/ModalModel',
	'views/modal/AlertView',
	'views/modal/ConfirmView',
	'text!templates/main/upload.html',
	'filedrop',
	'text!templates/main/uploadProgress.html',
	'views/main/TourView',
	'views/sidebar/MainMenuView',
	'models/main/TourModel',
	'views/footer/SceneMenuView',
	'collections/footer/SceneCollection',
	'views/header/TourTitle',
	'views/header/PublishControllerView',
	'mCustomScrollbar'


	], function($, _, Backbone,x2js,HelpFunctions, ManageTour, ManageData, ModalModel, AlertView,ConfirmView,upload,filedrop,uploadProgress,TourView,MainMenuView,TourModel,SceneMenuView,SceneCollection,TourTitle,PublishControllerView, mCustomScrollbar){

	var UploaderView = Backbone.View.extend({
		el: $("body"),
		gNew_tour:null,
		gTour_id:"",
		setIntervalID:[],
		state:[],
		pano_id : [],
		scene_id : [],
		tour_id:[],
		filename:[],
		scene_name:[],
		html_ref_id:[],
		thumb_path:[],
		addingPane:null,
		initialize: function () {
			_.bindAll(this);
		},
		events:{
			"input .tile-size input":"maxTile",
			"click .dragger-wrapper .cancel":"removeView"
		},

		render: function(){
			var este = this;
			  window.proc_id = new Array();
			  window.uploader_id = 0;
	  
			este.gNew_tour = this.model.get("gNewTour");
			este.addingPane =  this.model.get("addingPane");
			este.cancel = this.model.get("cancel")
			if(location.hash.split("/")[1]){
				este.gTour_id= location.hash.split("/")[1];
			}

			var compiledTemplate = _.template(upload);
			$(this.el).append( compiledTemplate ); 

			if (este.cancel) {
				$('.dragger-wrapper .cancel').removeClass('none');
			}
			var helpFunctions = new HelpFunctions();
			helpFunctions.dropDown(".dropdown");
			var last_zindex  = null;
			var rollingOver = false;

			var dropbox = $('#drop-zone'),
			message = $('.drop-message', dropbox);

			este.verticalCent();
			dropbox.filedrop({
				// The name of the $_FILES entry:
				paramname:'pic',
				refresh: 100,
				//maxfiles: 100,
				//queuefiles: 2,
				allowedfileextensions: ['.jpg','.jpeg','.png','.tif','.tiff'],
				maxfilesize: 75,
				url: 'php/general_process.php',

			   /* data:{
				   tour_id:este.gTour_id
				},
				*/
				error: function(err, file) {
					switch(err) {
						case 'BrowserNotSupported':
						este.showMsg('Your browser does not support HTML5 file uploads!');
						break;
						case 'FileExtensionNotAllowed':
						este.showMsg('File extension not allowed');
						break;
						case 'FileTooLarge':
						este.showMsg(file.name+' is too large! Please upload files up to 75 mb.');
						break;
						default:
						break;
					}
					//$("header.main-header").removeClass("bluring")
					$(".dragger-wrapper").removeClass("scaling")
					//$("footer.main-footer").removeClass("bluring")
				},

				beforeEach: function(file){    

					if(!file.type.match(/^image\//)){
						este.showMsg('Only images are allowed!');
						return false;
					}

					if(!file.type.match(/jpeg|tiff/)){
						este.showMsg('Format not supported!');
						return false;
					}
				},

				drop:function(){
					//este.autocreateTour();
					$('.dragger-wrapper .cancel').addClass('none');

				},

				uploadStarted: function(e, file, len){
					if($(".ffmsg").size()){
						$(".ffmsg").hide();
					}
					var this_ul_id = window.uploader_id;
					if (rollingOver) { 
						$('.main-section').css('z-index', "last_zindex");
						//$("header.main-header").removeClass("bluring")
						//$("footer.main-footer").removeClass("bluring")
						$(".dragger-wrapper").removeClass("scaling")
						rollingOver = false;
					}    
					file.id = this_ul_id;
					este.createImage(file,this_ul_id,window.proc_id[this_ul_id]);
					$(".dragable").addClass("uploading-drop-zone"); 
					este.verticalCent();   
					$(".scroll-wrapper").mCustomScrollbar("scrollTo",$(".pano-item:last-child").offset().top);
					if($(".fa-clock-o").size()){
					    $(".fa-clock-o").addClass('blink').removeClass("save").text("")    
					}
										
					$("#pano-"+this_ul_id).addClass("readytogo");
					$("#pano-"+this_ul_id+" .fa-close").data("myproc",window.proc_id[this_ul_id]);
					$("#pano-"+this_ul_id+" .fa-close").data("cicle",this_ul_id);
					$("#pano-"+this_ul_id+" .fa-close").data("myfile",file.name);
					$("#pano-"+this_ul_id+" .fa-close").click(este.removePano)
					if (este.cancel) {
						$('.dragger-wrapper .cancel').addClass('none');
					}
					este.setIntervalID[this_ul_id] = setInterval(function(){
						$.ajax({
							type: "POST",
							url: "php/general_process_state.php",
							data: "proc_id=" + window.proc_id[this_ul_id],
							cache: false,
							success: function(response){
								var respuesta = JSON.parse(response);
									//console.log("STATE "+i+":"+respuesta.state);
									
									if(este.state[this_ul_id] != respuesta.state && respuesta.state != 'w'){
										
										este.state[this_ul_id] = respuesta.state;
										
										switch(este.state[this_ul_id]) {
										case "-1": //Error
											clearInterval(este.setIntervalID[this_ul_id]);    
											           
											$(".inner-dragger #pano-"+this_ul_id+" h3").addClass('error').html(respuesta.state_desc + " <span>- " + file.name + " - Please try again</span>");

											$(".inner-dragger #pano-"+this_ul_id+" .progress").css("background","#d32f31");
											$(".inner-dragger #pano-"+this_ul_id+" .percentage").text("Error");
											$(".inner-dragger #pano-"+this_ul_id).removeClass("readytogo");

											if(este.addingPane){
												$(".uploader-footer #cancelUploaded").removeClass('none');
											}
											break;
											
										case "1":
											$("#pano-"+this_ul_id+" .percentage").text(respuesta.state_desc);
											break;
										case "2":
											este.pano_id[this_ul_id]         = respuesta.pano_id;
											este.scene_id[this_ul_id]        = respuesta.scene_id;
											este.tour_id[this_ul_id]         = respuesta.tour_id;    
											este.filename[this_ul_id]        = respuesta.filename;  
											este.thumb_path[this_ul_id]        = respuesta.thumb_path;  
											este.scene_name[this_ul_id]         = este.filename[this_ul_id].replace(/\.jpg|\.jpeg|\.tiff/g, '');
											este.html_ref_id[this_ul_id]     = 'pano-'+respuesta.scene_id;
														
											$("#pano-"+this_ul_id+" .fa-close").data("state",este.state[this_ul_id]);
											$("#pano-"+this_ul_id+" .percentage").text("Processing image (1/2)");
											$("#pano-"+this_ul_id+" .icon-msg .processing").removeClass("none");
											$("#pano-"+this_ul_id+" .icon-msg .wait").addClass("none");
											$("#pano-"+this_ul_id).attr("id", este.html_ref_id[this_ul_id]);  
											$("#"+este.html_ref_id[this_ul_id]+" .fa-close").data("state",este.state[this_ul_id]);
											
										break;
										case "3":
											$("#"+este.html_ref_id[this_ul_id]+" .percentage").text("Processing image (2/2)");
											$("#"+este.html_ref_id[this_ul_id]+" .fa-close").data("state",este.state[this_ul_id]);
											break;
											
										case "4":
											$("#"+este.html_ref_id[this_ul_id]+" .thumb").attr("src",este.thumb_path[this_ul_id]);
											$("#"+este.html_ref_id[this_ul_id]+" .progress").css("background","#497f3c");
											$("#"+este.html_ref_id[this_ul_id]+" .progress").width("100%");
											$("#"+este.html_ref_id[this_ul_id]+" .percentage").text("Upload Complete!");
											$("#"+este.html_ref_id[this_ul_id]+" .icon-msg").html('<span class="fa fa-check"></span>');
											$("#"+este.html_ref_id[this_ul_id]+" .icon-msg").css("background","#497f3c");
											$("#"+este.html_ref_id[this_ul_id]).data("pano_id",este.pano_id[this_ul_id]);    
											$("#"+este.html_ref_id[this_ul_id]+" .fa-close").data("pano_id",este.pano_id[this_ul_id]);    
											$("#"+este.html_ref_id[this_ul_id]+" .fa-close").data("state",este.state[this_ul_id]);    
											$("#"+este.html_ref_id[this_ul_id]).addClass("completed");    

											var completed = 0; 

											$(".pano-item").each(function(index){
												if($(this).find(".percentage").text() == "Upload Complete!"){
												   completed++
												}
											})

											if(completed == $(".pano-item.readytogo").size()){
												este.AllUploaded();
											}
											
											clearInterval(este.setIntervalID[this_ul_id]);
											break;
										} 
										
									}
									
								}
						})}, 500);
				},

				docOver:function(e){    
					if (!rollingOver) {    
						//$("header.main-header").addClass("bluring")
						$(".dragger-wrapper").addClass("scaling")
						//$("footer.main-footer").addClass("bluring")

						var posleft = $(".dragger-wrapper").offset().left;

						rollingOver = true;
					}    
				},

				docLeave:function(e){     
					if (rollingOver) { 
						last_zindex = $('#drop-zone').css('z-index', last_zindex);
						//$("header.main-header").removeClass("bluring")
						$(".dragger-wrapper").removeClass("scaling")
						//$("footer.main-footer").removeClass("bluring")

						rollingOver = false;
					}   
				},

				progressUpdated: function(i, file, progress) {
					$(".uploader-footer p").text("uploading or processing panoramas, please don't close this page").siblings('#cancelUploaded').addClass('none');
					$("#pano-"+file.id).find('.progress').width(progress+'%');
					$("#pano-"+file.id).find('.percentage').html('Uploading '+progress+'%');
				},

				uploadFinished:function(e, file, response){  
					//console.log('STOP:' + window.proc_id[window.uploader_id] + ' - ' + window.uploader_id + ' - ' + file["name"]);
	
				   
				},

				afterAll : function(){}  

			}); // end of dropfile

			$("#click-to-select-file").click(function(e){
				$("#upload_button").trigger("click")
			})
		},

		showMsg: function(msg){
			var modalModel = new ModalModel({msg:msg})
			var alertView = new AlertView({model:modalModel});
			alertView.render("alert",alertView.renderExtend);
		},

		createImage:function(file,i,proc) {
			data = {
				filesrc:file.name,
				ind:i,
				proc:proc    

			}

			var compiledTemplate = _.template(uploadProgress,data);

			if($(".pano-item-wrapper").length == 0){
				$('.dragger-wrapper .inner-dragger').append('<div class="scroll-wrapper"><div class="pano-item-wrapper"></div></div><div class="uploader-footer"><span class="uploader-btn fa fa-clock-o blink right"></span><span id="cancelUploaded" class="uploader-btn right none">Cancel</span><p></p></div>');

				//scrollbar
				$(".scroll-wrapper").mCustomScrollbar({
					theme:"minimal-dark",
					scrollInertia:300,
				});
			}

			$('.dragger-wrapper .pano-item-wrapper').append(compiledTemplate);
			 
		},

		sendReport:function(type, extras){
			$.ajax({
				url : 'php/send_report.php',
				type: 'POST',
				async: true,
				data: 'type='+type+'&extras='+extras,
				cache : false,
				success : function(response){}
			});        
		},


		AllUploaded:function(){
			var este = this;
			$(".uploader-footer").find('#cancelUploaded').removeClass('none').siblings('p').html('All Done <span class="fa fa-smile-o"></span>');            

			if(este.addingPane){
				$(".fa-clock-o").removeClass('blink').addClass("save").text("ADD PANOS");
			}else{
				$(".fa-clock-o").removeClass('blink').addClass("save").text("CREATE TOUR");
			}
			$("#cancelUploaded").click(este.cancelUploaded);
			$(".save").unbind("click");
			$(".save").click( function(){

				if(este.addingPane){
				   
					$(".dragger-wrapper").fadeOut(function(){
					   $(this).remove();
					})
					var manageTour = new ManageTour();
					var manageData = new ManageData();
					var cargarEscenas = function(){
						var scenes = tourData.krpano.scene;
						var sceneCollection = new SceneCollection(scenes);
						var sceneMenuView = new SceneMenuView({ collection: sceneCollection});
						sceneMenuView.render();
						manageData.saveServer();
						$(".main-footer").show();
						$(".header-bottom").show();
					}

					manageTour.reloadTour(cargarEscenas);

				}else{

					/*var myscenes = [];
					$(".pano-item-wrapper .pano-item").each(function(ind,elem){
						var scene = {}

						var sceneid = $(elem).attr("id"); 
						sceneid = sceneid.replace("pano-","");
						scene.name = sceneid;
						scene.title = $(elem).find("h3 span").text();
						scene.filename = $(elem).find("h3 span").text();
						scene.thumburl = $(elem).find(".thumb").attr("src");
						scene.url = sceneid;
						myscenes.push(scene);
					})*/


					$(".dragger-wrapper").fadeOut(function(){
					   $(this).remove();
					})

					if(!este.gTour_id){
						este.gTour_id = window.gTour_id;
					}

					jQuery.ajax({
					type: "GET",
					url: "php/general_process.php?tour_id="+este.gTour_id+"&f=1",
					success: function(res){
					}})

					var xmlpath ="data/xml.php?id="+este.gTour_id+"&d=1&c=1";
					//var xmlpath ="data/xml.php?idtour=9&c=1";
					
					$.ajax({
						url: xmlpath,
						type: "GET",
						dataType: "html",

						success: function(data) {
							var x2js = new X2JS({attributePrefix:"_"});
							tourData =  x2js.xml_str2json( data );

							var helpFunctions = new HelpFunctions();
							helpFunctions.prepareConditionsForTour();

							$.ajax({
								url:  "data/json.php?id="+este.gTour_id+"&d=1&t=t",
								dataType:"json",

								success:function(datatour){

									tourData.krpano.datatour = datatour;

									var xml2krpano = xmlpath.replace("&c=1","&h=0")
									var tourModel = new TourModel({xmlpath:xml2krpano});

									var tourView = new TourView({ model: tourModel});
									tourView.render();

									$(".main-footer").show();
									$(".header-bottom").show();

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


									Backbone.history.navigate("tour/"+este.gTour_id);
									$("#publish #draft").addClass("active")
								 
								}
							})

						}
					});
				}
			})
		},


		removePano:function(e){
			var $elem = $(e.target); 
			var msg = "Are you sure you want to remove this pano from your tour?<br>"+$elem.data("myfile")+"?";
			var este = this;
			var evt=function(){
				
					if($elem.data("state") == "4"){
						var url     = 'php/updater.php';
						var type    = 'POST';
						var data    = 'id='+$elem.data('pano_id')+"&action=del_pano";
					}else{

						var url     = 'php/general_process.php';
						var type    = 'GET';
						var data    = 'proc_id='+$elem.data('myproc')+"&c=1";
						var cicle   =  parseInt($elem.data("cicle"));
						clearInterval(este.setIntervalID[cicle]);
					}
					$("#confirmDel .fa-close").trigger("click");
					$elem.parents(".pano-item").append('<div class="cancelling"><p>Removing pano...</p><div class="loading"></div></div>')
					$.ajax({
						url : url,
						type: type,
						data: data,
						success : function(response){
							$elem.parents(".pano-item").css("background","#fad368").fadeOut(function(){
								$(this).remove();
								if($(".pano-item").size() == 0){
									$(".dragger-wrapper").fadeOut(function(){
									   $(this).remove();
									   este.render()
									})
									
								}else{
									var completed = 0;
									_.each($(".pano-item.completed"),function(elem){
										if($(elem).find(".fa-close").data("state")== "4"){
											completed++
										}
									})
									if(completed == $(".pano-item.completed").size()){
											este.AllUploaded();
									}
								}
								
							});
						}
					});
					
				}
			var modalModel = new ModalModel({msg:msg,evt:evt})
			var alertView = new ConfirmView({model:modalModel});
			alertView.render("confirmDel",alertView.renderExtend);
		},

		cancelUploaded:function(){
			var este = this;
			var total = 0;
			$("#cancelUploaded").text("Cancelling...");
			_.each($(".pano-item"),function(elem,ind){
				var panoid = $(elem).data("pano_id");
				$.ajax({
					url : 'php/updater.php',
					type: 'POST',
					data: 'id='+panoid+"&action=del_pano",
					success : function(response){
						total++
						if(total == $(".pano-item").size()){
							este.removeView();
						}
					}
				})
			})
		},

		removeView:function(){
			this.undelegateEvents();            
			var este = this;
			$(".dragger-wrapper").animate({
				'top' : '0'
			}, function(){
				$('.dragger-wrapper' ).remove();
				if(!$(".main-footer").is(":visible")){
								setTimeout(este.render(),1500);
							}
			})
		},

		verticalCent : function() {
			$(".dragger-wrapper").animate({
				'top' : '50%',
				'margin-top' : -$('.dragger-wrapper').outerHeight()/2
			})
		},

		maxTile : function(e) {
			var elem = $(e.target),
				val = elem.val();

			elem.siblings('input').val(val);
		}
	});

	return UploaderView;

});
