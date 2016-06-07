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


		tourSkill = {

		    _kind: "Signature",
		    _prev_tag_ident: "0",
		    _segment: "SKILLS",
		    _tag_ident: "1",
		    _template_id: "4",
		    plugin: {
		        _align: "bottomright",
		        _alpha: "0.6",
		        _keep: "true",
		        _kind: "Signature",
		        _name: "skill_signature",
		        _onclick: "openurl(http://www.spinattic.com/,_blank);",
		        _onout: "tween(alpha,0.6)",
		        _onover: "tween(alpha,1)",
		        _prev_tag_ident: "1",
		        _segment: "SKILLS",
		        _tag_ident: "2",
		        _template_id: "4",
		        _url: "http://localhost/dev.spinattic.com/player/skills/custom_signature/customsignature.png",
		        _x: "3",
		        _y: "5",
		        _zorder: "102",
		    }
		}
			
			var compiledTemplate = _.template(main,{tourSkill:tourSkill})
			$(this.el).append( compiledTemplate ); 
			var caso = 'skills';
			txtmsg="*JPG, PNG or GIF formats.Upload images twice the screen size. They'll be set to 1/2 scale for correct mobile screen display"
			
			var SingleUploaderModel = Backbone.Model.extend({});
			var singleUploaderModel = new SingleUploaderModel({myid:"mainsignatureuploader-skill-editor-img",caso:caso,textMessage:txtmsg,skill_id:tourSkill._template_id})
			var singleUploader = new SingleUploader({model:singleUploaderModel});
			singleUploader.render(function(){
				//este.krpano.set("skill_nadirpatch_settings.image",$("#nadirPatch-skill-editor-img").data("imgsrc"))
			});         
			_.each($("#signature-skill-align .fa-circle"),function(elem,ind){
				if($(elem).data("pos") == tourSkill.plugin._align){
					$(elem).addClass("selected");
				}
			})
		}


		


	});

	return MainSignatureUploader;
  
});
