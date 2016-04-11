define([
	'jquery',
	'underscore',
	'backbone',
	'views/modal/SingleUploader',
	'text!templates/signatureUploader/main.html',

], function($, _, Backbone,SingleUploader,main){

	var MainSignatureUploader = Backbone.View.extend({

		el: $("body"),

		initialize: function () {
		  
		},

		events:{
		//	"focus #tour-title":"changeTitleByEnter",
		//	"blur #tour-title":"changeTitleByBlur",
		//	"keyup #tour-title":"setWidth"
		},

		render: function(){

			
			var compiledTemplate = _.template(main);
			$(this.el).append( compiledTemplate ); 
			var caso = 'skills';
			var SingleUploaderModel = Backbone.Model.extend({});
			var singleUploaderModel = new SingleUploaderModel({myid:"mainsignatureuploader-skill-editor-img",imgsrc:"",tour_id:"",caso:caso})
			var singleUploader = new SingleUploader({model:singleUploaderModel});
			singleUploader.render(function(){
				//este.krpano.set("skill_nadirpatch_settings.image",$("#nadirPatch-skill-editor-img").data("imgsrc"))
			});         
			
		}


	});

	return MainSignatureUploader;
  
});
