define([
	'jquery',
	'underscore',
	'backbone',
	'x2js',
	'lib/fineuploader/fineuploader',
	'text!templates/main/uploadD.html',
	'views/footer/SceneMenuView',
	'collections/footer/SceneCollection',
	'views/header/TourTitle',
	'views/header/PublishControllerView',
	'views/main/TourView',
	'views/sidebar/MainMenuView',
	'models/main/TourModel',
	'helpers/HelpFunctions',
	'models/main/ModalModel',
	'views/modal/AlertView',
	'views/modal/ConfirmView',
	'helpers/ManageTour',
	'helpers/ManageData',
	
	

	], function($, _, Backbone,x2js,fineuploader,upload,SceneMenuView,SceneCollection,TourTitle,PublishControllerView,TourView,MainMenuView,TourModel,HelpFunctions,ModalModel,AlertView,ConfirmView,ManageTour,ManageData){

	var UploaderView = Backbone.View.extend({
		el: $("body"),
		
		initialize: function () {
			_.bindAll(this);
		},
		events:{
		 "click .dragger-wrapper .save":"createTour",
		 "click .uploader-list-wrapper .customizer-cancel":"cancelPano",
		 "click #cancelUploader":"cancelAllPanos",
		},
		filedata:[],
		setIntervalID:[],
		timerId:[],
		uploader:{},
		render: function(){
			window.uploader_id = 0;
			window.proc_id = [];
			var gNew_tour = this.model.get("gNewTour");
			
			var este = this;
			var accountType =  $(".main-header .user").data("level");
			switch(accountType){
				case "FREE":
				este.allowedExtensions = ['jpeg','jpg'];
				este.maxWidth = 30000;
				este.maxHeight = 15000;
				este.sizeLimit = 31457280;
				este.ImageError = "Your Free Account can upload JPG files with the max size of 25mb and 30,000px width";
				break;
				case "ADVANCED":
				este.allowedExtensions = ['jpeg','jpg','png', 'tif','tiff'];
				este.maxWidth = 50000;
				este.maxHeight = 25000;
				este.sizeLimit = 52428800;
				este.ImageError = "Your Advanced Account can upload JPG, PNG or TIFF files with the max size of 50mb and 50,000px width";
				break;
				case "PRO":
				este.allowedExtensions = ['jpeg','jpg','png', 'tif','tiff'];
				este.maxWidth = 150000;
				este.maxHeight = 75000;
				este.sizeLimit = 157286400;
				este.ImageError = "Your PRO Account can upload JPG, PNG or TIFF files with the max size of 150mb and 150,000px width";
				break;
			}
			var maxImage=este.ImageError;
			var compiledTemplate = _.template(upload,{maxImage:maxImage});
			if(gNew_tour){
				$(this.el).append( compiledTemplate ); 
				this.verticalCent();
			}else{
				$(".uploaderBallon").append( compiledTemplate );
			}

				este.uploader = new qq.FineUploader({
					callbacks:{

						onSubmit:function(id,filename){
							/*console.log(window.proc_id[id])
							newParams = {
								proc_id: window.proc_id[id],
								tour_id: window.gTour_id,
								test:"test"+id
							}
							uplder = este.uploader;
	            			//uplder.setParams(newParams);
							*/
						},
						onStatusChange:function(id,oldStatus,newStatus){
							este.statusChange(id,oldStatus,newStatus);
						},	

						onProgress:function(id,str,uploadBytes,totalBytes){
							este.uploaderProgress(id,str,uploadBytes,totalBytes)
						},

						onComplete:function(id){
							este.uploaderComplete(id);
						},
						onAllComplete:function(){
							este.uploaderAllComplete()
						},
						onCancel:function(id){

							$.ajax({
								url :'php/general_process.php?c=1',
								type: 'POST',
								data: 'proc_id='+window.proc_id[id]+'&qquuid='+este.filedata[id].qquuid,
								success : function(response){
									console.log(response)
								}
							})

							completed = 0;

							_.each($(".qq-upload-success"),function(elem){
										if($(elem).data("state")== "4"){
											completed++
										}
									})
									
									if($(".qq-upload-success").size() == $(".qq-upload-list li:not(.first-li)").size()){
										if(completed == $(".qq-upload-success").size()){
												$(".uploader-footer").removeClass("uploader-hidden");
										}
									}
						},
						onUploadChunk:function(id,name,chunkData){
							var qquuid = this.getUuid(id)
							este.filedata[id] = {
								qquuid:qquuid,
								totalParts:chunkData.totalParts,
								proc_id:window.proc_id[id],
								name:name,
								tour_id:window.gTour_id
							}
							
						},
						onError:function(id, name, errorReason, xhrOrXdr) {
							
							console.log(qq.format("Error on file number {} - {}.  Reason: {}", id, name, errorReason));
					       
							if($(".qq-file-id-"+id+" img").size()){
							este.filedata[id].error = true;
							
							$(".qq-progress-bar").css("background","rgba(255,0,0,0.5)")
							}
							//alert(qq.format("Error on file number {} - {}.  Reason: {}", id, name, errorReason));
						},
						onManualRetry:function(id,name){
							return false;
						},

						onDelete:function(id){
							
							return false;
						}
						
					},
					template:"qq-template",
					element: document.getElementById('fine-uploader'),
					request: {
						endpoint: 'php/general_process.php',
					},

					chunking: {
						enabled: true,
						concurrent: {
							enabled: true
						},
						mandatory:true
					},
					resume: {
						enabled: true
					},
					retry: {
						enableAuto: false,
						showButton: true
					},
					validation: {
						allowedExtensions: este.allowedExtensions,
						sizeLimit:este.sizeLimit,
						image:{
							maxWidth:este.maxWidth,
							maxHeight:este.maxHeight,
							minWidth:3000,
							minHeight:1500
							},

					},
					text:{
						formatProgress: '{percent}%',
						paused:''
					},
					messages:{
						maxHeightImageError:este.ImageError,
						maxWidthImageError:este.ImageError,
						minHeightImageError:'The image is too small. Please upload panoramas bigger than 3000x1500 px.',
						minWidthImageError:'The image is too small. Please upload panoramas bigger than 3000x1500 px.',
						sizeError:este.ImageError
					},
					debug:false
			});
		},

		verticalCent : function() {
			$(".dragger-wrapper").animate({
				'top' : '55%',
				'margin-top' : -$('.dragger-wrapper').outerHeight()/2
			})
		},

		statusChange:function(id,oldStatus,newStatus){
			uplder = this.uploader;
			window.uploader = this.uploader;			

			var respuesta = uplder.getInProgress();
			var este = this;
			if(newStatus=="submitting"){

				//animación de los tipitos
				this.moveAnimate(27,39,2,".qq-file-id-"+id+" .wait",id);
				this.filedata[id] = {}
				if($(".uploader-footer").is(":visible")){
					$(".uploader-footer").addClass("uploader-hidden");
				}
				if(!$(".qq-upload-list .first-li").size()){
					$firstli = $('<li class="first-li"><div class="fa fa-cloud-upload"></div><h2>Drag and drop your panos in this box</h2></li>');
					$(".qq-upload-list").prepend($firstli)
					$(".qq-upload-drop-area").appendTo(".qq-upload-list .first-li");
					$(".qq-upload-button").appendTo(".qq-upload-list .first-li");
					
					$("#drop-zone").remove();
					$(".uploader-list-wrapper-wrapper").height($(".inner-dragger").height());
					$(".uploader-list-wrapper-wrapper").mCustomScrollbar({
						theme:"minimal-dark",
						scrollInertia:300,
					});
					$(".max-pano-text").hide();
				}
				
				if(!location.hash.split("/")[1] && !window.gTour_id){ 
					$.ajax({
						url:'php/ultour.php',
						type:'POST',
						data:'autocreate=true',
						async:false,
						success:function(response){
							start_time = new Date().getTime();
							var parsedObj = jQuery.parseJSON(response);
							if(!window.gTour_id){
								window.gTour_id    = parsedObj.params.tour_id; 
							 }
						window.proc_id[id] =  start_time+'-'+id+'-'+window.gTour_id;
						
						/*newParams = {
							proc_id: window.proc_id[id],
							tour_id:window.gTour_id
						}
						uplder.setParams(newParams);*/
						}
					 })
				}else{
					start_time = new Date().getTime();
					if(!window.gTour_id){
						window.gTour_id = location.hash.split("/")[1];
					}
					window.proc_id[id] =  start_time+'-'+id+'-'+window.gTour_id;
				}
			}

			if(newStatus=="uploading"){
				$(".qq-file-id-"+id).data("stateChange","progress")
			
				var uplder = this.uploader;
				var este = this;
				$(".qq-file-id-"+id+" .queueText").hide();

				newParams = {
								proc_id: window.proc_id[id],
								tour_id: window.gTour_id,
								test:"test"+id
							}

				uplder.setParams(newParams);


				/*$(".qq-file-id-"+id+" img").load(function(){
					var width = $(".qq-file-id-"+id+" img").width();
					var height = $(".qq-file-id-"+id+" img").height();
					
					
					if(height != Math.round(width/2)){
						este.showMsg("Can't upload this image. Aspect ratio required: 2x1.");
						uplder.cancel(id)
					
					}
				})*/
			}

			if(newStatus =="canceled"){
				var respuesta = uplder.getInProgress();
				if(respuesta == 0){
				var qquuid = este.filedata[id].qquuid;
							var myurl = "php/general_process.php?clean="+qquuid;

							$.ajax({
									type: "GET",
									url: myurl,
									success: function(res){
									}})
							}

			}

		},

		uploaderProgress:function(id,str,uploadBytes,totalBytes){
			
			if(uploadBytes == totalBytes){
					$(".qq-file-id-"+id+" .qq-upload-size-selector").addClass("uploader-hidden");
				}
		},

		uploaderComplete:function(id){

			$(".qq-file-id-"+id).data("stateChange","not-progress")
			var este = this;
			$(".qq-file-id-"+id+" .icon-msg .wait").removeClass("none");
			
			$(".qq-file-id-"+id+" .qq-upload-status-text").text("wait...");
			$(".qq-file-id-"+id+" .qq-upload-status-text").addClass("process-text")
			
			$.ajax({
					url:'php/general_process.php?done',
					type:'POST',
					data:'qquuid='+este.filedata[id].qquuid+"&qqfilename="+este.filedata[id].name+"&proc_id="+este.filedata[id].proc_id+"&qqtotalparts="+este.filedata[id].totalParts+"&tour_id="+este.filedata[id].tour_id,
					success:function(response){
						
						var respuesta = JSON.parse(response);
						if(respuesta.result == "ERROR"){

						if(respuesta.msg=="notowner"){
							location.href= location.protocol+"//"+location.host;
						}

						if(!respuesta.msg == "Something went wrong with your upload!"){
							este.showMsg(respuesta.msg);
							este.uploader.cancel(id)
							}
						}

					}
				})

			if(!este.filedata[id].error){
				$(".qq-file-id-"+id+" .fa-close").show();
				$(".qq-file-id-"+id+" .qq-upload-cancel-selector").hide();
				este.setIntervalID[id] = setInterval(function(){
							$.ajax({
								type: "POST",
								url: "php/general_process_state.php",
								data: "proc_id=" + window.proc_id[id],
								cache: false,
								success: function(response){
									var respuesta = JSON.parse(response);
									var state = respuesta.state;
									
									$(".qq-file-id-"+id).data("state",state)
									switch(state){
										case "-1": //Error
										clearInterval(este.setIntervalID[id]);
										break;
										case "1":
											
											$(".qq-file-id-"+id+" .qq-upload-status-text").text(respuesta.state_desc);
											$(".qq-file-id-"+id+" .qq-upload-status-text").addClass("process-text");

										break;   
										case "2":
										$(".qq-file-id-"+id+" .qq-upload-status-text").text(respuesta.state_desc);
										$(".qq-file-id-"+id).data("pano_id",respuesta.pano_id);
										break;
										case "3":
										if(!$(".qq-file-id-"+id+" .icon-msg .wait").hasClass("none")){
											$(".qq-file-id-"+id+" .icon-msg .wait").addClass("none");
											clearInterval(este.timerId[id]);
											este.moveAnimate(27,0,8,".qq-file-id-"+id+" .processing",id);
											$(".qq-file-id-"+id+" .icon-msg .processing").removeClass("none")

										}
										$(".qq-file-id-"+id+" .qq-progress-bar").css("background","rgba(213,172,61,0.5)")
										$(".qq-file-id-"+id+" .qq-upload-status-text").text(respuesta.state_desc);
										break;
										case "4":
										clearInterval(este.setIntervalID[id])
										$(".qq-file-id-"+id+" .qq-upload-status-text").html('<span class="fa fa-check"></span>');
										$(".qq-file-id-"+id+" .qq-progress-bar").addClass("uploader-hidden");
										$(".qq-file-id-"+id).addClass("full-completed");
										
										$(".qq-file-id-"+id+" .icon-msg .processing").addClass("none");
										clearInterval(este.timerId[id]);

										// se crea el thumb
										if(id==0){
											$.ajax({
												type: "GET",
												url: "php/general_process.php?tour_id="+gTour_id+"&f=1",
												success: function(res){
												}})
										}
										
										if($("li.full-completed:not(.first-li)").size() == $(".qq-upload-list li:not(.first-li)").size()){
											var addingPane =  este.model.get("addingPane");
											if(addingPane){
												$(".uploaderBallon footer .save").text("add Panos")
											}
											$(".uploader-footer").removeClass("uploader-hidden");


										}
										

									}
									
								}
							})
						},500)
					}
		},
		uploaderAllComplete:function(){
			
		},
		createTour:function(e){
			e.preventDefault();
			var addingPane =  this.model.get("addingPane");
			
			if(addingPane){

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
					$(".uploaderBallon").hide();
					$(".main-footer").show();
					$(".header-bottom").show();
				}

				manageTour.reloadTour(cargarEscenas);

			}else{
					gTour_id = window.gTour_id
					
					$.ajax({
							type: "GET",
							url: "php/general_process.php?tour_id="+gTour_id+"&f=1",
							success: function(res){
							}})

					var xmlpath ="data/xml.php?id="+gTour_id+"&d=1&c=1&customizer=1";
					$.ajax({
						url: xmlpath,
						type: "GET",
						dataType: "html",
						success: function(data) {
						
							var x2js = new X2JS({attributePrefix:"_"});
							tourData =  x2js.xml_str2json( data );

							var helpFunctions = new HelpFunctions();
							helpFunctions.prepareConditionsForTour();
							$(".dragger-wrapper").animate({
								'top' : '-550px',
							})
							$.ajax({
								url:  "data/json.php?id="+gTour_id+"&d=1&t=t",
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


									Backbone.history.navigate("tour/"+gTour_id);
									$("#publish #draft").addClass("active")
								 
								}
							})

						}
					})
				}

		},

		cancelPano:function(e){
			var este = this;
			var id = $(e.target).parents("li").attr("qq-file-id");
			var $elem = $(".qq-file-id-"+id);
			var uplder = este.uploader;
			var name = $elem.find(".qq-file-name").text()
			 
			var msg = "Are you sure you want to remove this pano from your tour?<br>"+name+"?";
			var este = this;

			var estados = 0;
			_.each($("li:data(stateChange)"),function(elem,ind){
				if($(elem).data("stateChange")=="progress"){
					estados++;
				}

			})
			

			var evt=function(){
				
					if($elem.data("state") == "4"){
						var url     = 'php/updater.php';
						var type    = 'POST';
						var data    = 'id='+$elem.data('pano_id')+"&action=del_pano";
					}else{

						var url     = 'php/general_process.php?c=1';
						var type    = 'POST';
						var data    = 'proc_id='+window.proc_id[id]+'&qquuid='+este.filedata[id].qquuid;
						var cicle   =  id;
					}

					clearInterval(este.setIntervalID[id]);
					$("#confirmDel .fa-close").trigger("click");
					$elem.append('<div class="cancelling"><p>Removing pano...</p><div class="loading"></div></div>')
					$.ajax({
						url : url,
						type: type,
						data: data,
						success : function(response){
							$elem.fadeOut(function(){
								
								if($(".qq-file-id-"+id).data("state")){
									uplder.deleteFile(id);
							

								}else{
								$(".qq-file-id-"+id).data("error","true");
								uplder.cancel(id)
								}
								$elem.remove();

								//$(this).remove();
								if($(".qq-upload-list li:not(.first-li)").size() == 0){
									$(".dragger-wrapper").fadeOut(function(){
									   $(this).remove();
									   este.render()
									})
									
								}else{

									/*_.each($(".qq-upload-list-selector li:not(.first-li)"),function(elem,ind){
										var myid = $(elem).attr("qq-file-id");
										var myfile = uplder.getFile(myid);
										uplder.cancel(myid)
										uplder.addFile(myfile)
									
									})*/

									var completed = 0;
									
									_.each($(".qq-upload-success"),function(elem){
										if($(elem).data("state")== "4"){
											completed++
										}
									})
									
									if($(".qq-upload-success").size() == $(".qq-upload-list li:not(.first-li)").size()){
										
										if(completed == $(".qq-upload-success").size()){
												$(".uploader-footer").removeClass("uploader-hidden");
										}
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

		cancelAllPanos:function(e){
			e.preventDefault();
			var este = this;
			_.each($(".qq-upload-list li.full-completed"),function(elem){
				var id = $(elem).attr("qq-file-id");
				$(elem).append('<div class="cancelling"><p>Removing pano...</p><div class="loading"></div></div>');

				$.ajax({
						url : 'php/updater.php',
						type: 'POST',
						data: 'id='+$(elem).data('pano_id')+"&action=del_pano",
						success:function(){
							$(elem).remove();

							if($(".qq-upload-list li:not(.first-li)").size() == 0){
								$(".dragger-wrapper").fadeOut(function(){
								   $(this).remove();
								   var addingPane =  este.model.get("addingPane");
								   if(addingPane){
								   		este.removeView();
								   		$(".uploaderBallon").hide();
								   }else{
								   		$.ajax({
											url : 'php/updater.php',
											type: 'POST',
											data: 'tour_id='+window.gTour_id+"&action=del_tour",
										   	success:function(res){
										   		
										   		este.render()
										   	}
									   })
								   }
								})
							}



						}
					})

			})
			
		},

		moveAnimate:function (xpos, ypos, times, element,id) {
			var i = 0; 
			this.timerId[id] = setInterval(function(){
				if (i > times) {// if the last frame is reached, set counter to zero
					i = 0;
				}
				$(element).css("background-position","-"+i * xpos + 'px '+ypos+'px')
					i++;
				}, 100); // every 100 milliseconds
			},

		showMsg: function(msg){
			var modalModel = new ModalModel({msg:msg})
			var alertView = new AlertView({model:modalModel});
			alertView.render("alert",alertView.renderExtend);
		},

		removeView:function(){
			this.undelegateEvents();            
			$(".uploaderBallon").html("");
			
		}
	});

	return UploaderView;

});
