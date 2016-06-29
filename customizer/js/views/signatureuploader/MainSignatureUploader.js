define([
	'jquery',
	'underscore',
	'backbone',
	'mCustomScrollbar',
	'views/modal/SingleUploader',
	'text!templates/signatureUploader/main.html',

], function($, _, Backbone,mCustomScrollbar,SingleUploader,main){

	var MainSignatureUploader = Backbone.View.extend({

		el: $("body"),

		initialize: function () {
		  
		},

		events:{
		//	"focus #tour-title":"changeTitleByEnter",
		//	"blur #tour-title":"changeTitleByBlur",
		//	"keyup #tour-title":"setWidth"
		"click .check-wrap":"selectSignature",
		"click .save-and-close":"saveAndClose"
		},

		render: function(){
			var este = this;

			$.ajax({
				url:"../data/xml.php?t=skills&c=1&id=4",
				 dataType: "html",
				 async:false,
				 success:function(data){
					var SkillItemModel = Backbone.Model.extend({});
					var x2js = new X2JS({attributePrefix:"_"});
					tourSkill =  x2js.xml_str2json( data );
					este.tourSkill = tourSkill;
						$.ajax({
							url:"../data/json.php?t=dg",
							dataType:"json",
							async:false, 
							success:function(data){

								console.log($(".save-and-close"))
								tourSkill.skill.plugin._url = data.url;

								var compiledTemplate = _.template(main,{tourSkill:tourSkill.skill})
								$(este.el).append( compiledTemplate ); 
								var caso = 'skills';
								txtmsg="*JPG, PNG or GIF formats.Upload images twice the screen size. They'll be set to 1/2 scale for correct mobile screen display"
								
								var SingleUploaderModel = Backbone.Model.extend({});
								var singleUploaderModel = new SingleUploaderModel({myid:"mainsignatureuploader-skill-editor-img",caso:caso,textMessage:txtmsg,skill_id:tourSkill.skill._template_id})
								var singleUploader = new SingleUploader({model:singleUploaderModel});
								singleUploader.render(function(){
									$("#"+myid +" .over-edit").trigger("click");
									tourSkill.skill.plugin._url=$("#signature-skill-editor-img").data("imgsrc");
									este.model.set("tourSkill",tourSkill);
									este.loadImages();
									$(".defaultset .fa").attr("class","fa fa-circle-o");
									//este.krpano.set("skill_nadirpatch_settings.image",$("#nadirPatch-skill-editor-img").data("imgsrc"))
								});         
								_.each($("#signature-skill-align .fa-circle"),function(elem,ind){
										if($(elem).data("pos") == tourSkill.skill.plugin._align){
											$(elem).addClass("selected");
										}
									})
								este.loadImages();
								}
							})

					
				}

			})

		
			
			
		},

		loadImages:function(){
			var tourSkill = this.tourSkill;
			console.log(tourSkill)
			$.ajax({
				//url:"data/sign_json.json",
				url:"../data/json.php?t=g",
				dataType:"json",
				success:function(data){
					if(data){
						$("#custome-signate-list").mCustomScrollbar('destroy');
						$("#custome-signate-list").html("");
						_.each(data,function(elem,ind){
							var $li = $('<li id="'+elem.id+'" data-default="'+elem.default+'"><div class="check-wrap"></div><img src="'+elem.path+'"/><span class="fa fa-close"></span></li>')
							$("#custome-signate-list").append($li);
							if(elem.path==tourSkill.skill.plugin._url){
								$li.find(".check-wrap").trigger('click')
							}
						})
							$("#custome-signate-list").mCustomScrollbar({
								theme:"minimal-dark",
								scrollInertia:300
							});
						
					}
				}
			})
		},

		selectSignature:function(e){
			$("li .fa-check").remove();
			
			if($(e.target).attr('class')=="fa fa-check"){
				$(e.target).remove();
				if(this.oldImg){
						$(".defaultset").hide();
					}
				}else{
					if($(e.target).children().length){
						$(e.target).children().remove();
						if(this.oldImg){
							$(".defaultset").hide();
						}
					}else{
						$(e.target).append('<span class="fa fa-check"></span>');
						var myid = $(e.target).parent().attr("id");
						$(".defaultset").show();
						$(".defaultset").data("default_id",myid);
						if($(e.target).parent().data("default")=="1"){
							$(".defaultset .fa").attr("class","fa fa-circle")
						}else{
							$(".defaultset .fa").attr("class","fa fa-circle-o")
						}
						var imgsrc = $(e.target).parent().find("img").attr("src");
					}
				}
		},

		saveAndClose:function(e){
			window.parent.$(".modal-signature").fadeOut();
		}
		


	});

	return MainSignatureUploader;
  
});
