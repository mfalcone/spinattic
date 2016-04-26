define([
	'jquery',
	'underscore',
	'backbone',
	'jqueryui',
	'models/main/ModalModel',
	'views/modal/AlertView',
	'text!templates/modal/singleUploaderDragArea.html',
	'text!templates/modal/singleUploaderShowing.html',
	'text!templates/modal/singleUploaderUploadingAnimation.html',
	'filedropsingle',

], function($, _, Backbone,jqueryui, ModalModel,AlertView,singleUploaderDragArea,singleUploaderShowing,singleUploaderUploadingAnimation,filedropsingle){

	var SingleUploader = Backbone.View.extend({
		CallBack:null,
		events:{
		},
		initialize: function () {
		_.bindAll(this);        
		},
		render:function(cbackFun){
			if(cbackFun){
				this.CallBack = cbackFun;
			}
			console.log(this.model.get("skill_id"));
			var myid = this.model.get("myid");
			if(this.model.get("imgsrc")){
			
				var img = this.model.get("imgsrc");
				$("#"+myid).data("imgsrc",img);
				var template = _.template(singleUploaderShowing,{imgsrc:img});
				$("#"+myid).html(template);
				//$("#"+myid +" .edit-img").click(this.startEditLoader);
				$("#"+myid +" .over-edit").click(this.startEditLoader);
			}else{

				var textMessage = this.model.get("textMessage");
				if(!textMessage){
					textMessage="* JPG, PNG or GIF formats. Upload a big image size as a thumbnail It’ll be resized to 900px width."
				}
				var template = _.template(singleUploaderDragArea,{textMessage:textMessage});
				$("#"+myid).html(template);
				this.dragFile();
				$("#single-click-to-select-file").click(function(e){
					$("#upload_button").trigger("click")
				})
			}
			
		   /* $( "#"+myid+" .image-uploader-wrapper").droppable({
				over: function( event, ui ) {$(this).addClass('droppingOver');},
				out: function( event, ui ) {$(this).removeClass('droppingOver');}
			});
			$( "#"+myid+" .image-uploader-wrapper" ).on( "dropover", function( event, ui ) {
				
			} );*/
		},

		startEditLoader:function(){
			var myid = this.model.get("myid");
			var textMessage = this.model.get("textMessage");
			if(!textMessage){
				textMessage="* JPG, PNG or GIF formats. Upload a big image size as a thumbnail It’ll be resized to 900px width."
			}
			var template = _.template(singleUploaderDragArea,{textMessage:textMessage});
			$("#"+myid).html(template);
			 $("#"+myid +" .cancel-img").click(this.render)
			this.dragFile();
			$("#single-click-to-select-file").click(function(e){
				$("#upload_button").trigger("click")
			})
		},

		dragFile:function(){
			var este = this;
			var myid = this.model.get("myid");
			var dropbox = $('#single-drop-zone');
			var tour_id = este.model.get("tour_id");
			var caso = este.model.get("caso");
			var skill_id = este.model.get("skill_id");
			dropbox.filedropSingle({
				paramname:'pic',
				refresh: 100,
				maxfiles: 1,
				maxfilesize: 5,
				url: 'php/upload-single-file.php',
				data:{
					tour_id:tour_id,
					caso: caso,
					skill_id:skill_id,
					},
				error: function(err, file) {

				},
				beforeEach: function(file){    

					if(!file.type.match(/^image\//)){
					   alert('Only images are allowed!');
						return false;
					}

					if(!file.type.match(/jpeg|png/)){
						alert('Format not supported!');
						return false;
					}
				},
				uploadStarted: function(i, file, len){

				var template = _.template(singleUploaderUploadingAnimation);
				$("#"+myid).html(template);
				if($("#make-live-bt").size()){
					$("#make-live-bt").addClass("disabled");
					$("#liveTourModal footer .cancel").addClass("disabled");
					$("#make-live-bt").parent().append('<div class="block"></div>');
					$("#liveTourModal .fa-close").hide();
					}

						
					
				},

				 progressUpdated: function(i, file, progress) {
				   
				   $("#"+myid +" .ok-img").hide();
					$("#"+myid+" .progress").width(progress+'%');
					$("#"+myid+" .percentage").text('Uploading '+progress+'%');
				},

				uploadFinished:function(i, file, response){  
					if($("#make-live-bt").size()){
						$("#make-live-bt").removeClass("disabled");
						$("#liveTourModal footer .cancel").removeClass("disabled");
						$("#make-live-bt").parent().find(".block").remove();
						$("#liveTourModal .fa-close").show();
					}

					if (response.result == 'SUCCESS') {
						
						$("#"+myid+" .percentage").text("upload complete");

						var myfile = response.params.path;
						$("#"+myid +" .ok-img").show();
						
							$("#"+myid).data("imgsrc",myfile)
							este.model.set("imgsrc",myfile);
							este.render(este.CallBack);
						
							if(este.CallBack){
								este.CallBack()
							}
						
					}else{
							
						if (response.result == 'ERROR'){

							este.showMsg(response.msg);
							este.render();
							
						}else{
						   
							este.showMsg("An error occurred while uploading file " + file.name + "<br>Please try again or contact us");
							este.render();
						}

					   }
				},

				afterAll : function(){

				},

				docOver: function() {
					$("#"+myid+" .image-uploader-wrapper").addClass('backgroundBlack');
				},
				docLeave: function() {
					$("#"+myid+" .image-uploader-wrapper").removeClass('backgroundBlack');
				}

			})
		},

		removeThis:function(){
			var myid = this.model.get("myid");
			$("#"+myid+" .image-uploader-wrapper").remove();
			this.undelegateEvents();
		},

		showMsg: function(msg){
			var modalModel = new ModalModel({msg:msg})
			var alertView = new AlertView({model:modalModel});
			alertView.render("alert",alertView.renderExtend);
		},

		
	});

	return SingleUploader;
	
});
