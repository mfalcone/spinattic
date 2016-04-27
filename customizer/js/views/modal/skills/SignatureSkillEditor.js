define([
	'jquery',
	'underscore',
	'backbone',
	'views/modal/Modal',
	'text!templates/modal/signatureSkillEditor.html',
	'helpers/HelpFunctions',
	'views/modal/SingleUploader',
	'mCustomScrollbar',
	'helpers/HelpFunctions',
	'helpers/ManageData',
	'helpers/ManageTour',


], function($, _, Backbone,Modal,signatureSkillEditor,HelpFunctions,SingleUploader, mCustomScrollbar,HelpFunctions,ManageData,ManageTour){

	var SignatureSkillEditor = Modal.extend({
		
		initialize: function () {
		_.bindAll(this);        
		 _.extend(this.events, Modal.prototype.events);
		},
		events:{
			"click .skillModal #Context-menu-finish":"doneEdition",
			"click #signature-skill-align .selected":"alignSignature",
			"change .signature-skill-editor input":"changeVal",
			"click #custome-signate-list .check-wrap":"selectSignature",
			"click #custome-signate-list li .fa-close":"deleteSignatureImage",
			"click .signature-skill-editor .defaultset .fa":"setDefault"
		},
		
		renderExtend:function(){

			var myid = this.myid;
			var tourSkill = this.model.get("tourSkill");

			var template = _.template(signatureSkillEditor,{tourSkill:tourSkill})
			$("#"+myid+" .inner-modal").html(template);
			_.each($("#signature-skill-align .fa-circle"),function(elem,ind){
				if($(elem).data("pos") == tourSkill.plugin._align){
					$(elem).addClass("selected");
				}
			})
			$("#"+myid+" header h2").text("Signature Skill Editor")
			$("#"+myid).find(".save-and-close").unbind("click");
			
			$(".scrollwrapper").mCustomScrollbar({
				theme:"minimal-dark",
				scrollInertia:300
			});

			var helpFunctions = new HelpFunctions();
			helpFunctions.nineGrillSelector("#"+myid+" .position");

			var tour_id = location.hash.split("/")[1];
			var caso = 'skills';
			
			
			txtmsg="*JPG, PNG or GIF formats.Upload images twice the screen size. They'll be set to 1/2 scale for correct mobile screen display"
			this.oldImg  = tourSkill.plugin._url;

			var SingleUploaderModel = Backbone.Model.extend({});
			var singleUploaderModel = new SingleUploaderModel({myid:"signature-skill-editor-img",tour_id:tour_id,caso:caso,textMessage:txtmsg,skill_id:tourSkill._template_id})
			var este = this;
			var singleUploader = new SingleUploader({model:singleUploaderModel});
			singleUploader.render(function(){
				$("#"+myid +" .over-edit").trigger("click");
				tourSkill.plugin._url=$("#signature-skill-editor-img").data("imgsrc");
				este.model.set("tourSkill",tourSkill);
				este.loadImages();
				$(".signature-skill-editor .defaultset .fa").attr("class","fa fa-circle-o");
				var krpano = document.getElementById("krpanoSWFObject");
				krpano.set("plugin["+tourSkill.plugin._name+"].url",$("#signature-skill-editor-img").data("imgsrc"));
				
			});

		this.loadImages();
				

		},

		loadImages:function(){
			var tourSkill = this.model.get("tourSkill");
			$.ajax({
				//url:"data/sign_json.json",
				url:"data/json.php?t=g",
				dataType:"json",
				success:function(data){
					if(data){
						$("#custome-signate-list").html("");
						_.each(data,function(elem,ind){
							var $li = $('<li id="'+elem.id+'" data-default="'+elem.default+'"><div class="check-wrap"></div><img src="'+elem.path+'"/><span class="fa fa-close"></span></li>')
							$("#custome-signate-list").append($li);
							if(elem.path==tourSkill.plugin._url){
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

		alignSignature:function(e){

			var tourSkill = this.model.get("tourSkill");
			var pos = $(e.target).data("pos");
			var krpano = document.getElementById("krpanoSWFObject");
			krpano.set("plugin["+tourSkill.plugin._name+"].align",pos);

		},

		changeVal:function(e){
			var tourSkill = this.model.get("tourSkill");
			var nuval =  $(e.target).val();
			var krpano = document.getElementById("krpanoSWFObject");
			if($(e.target).data("prop") == "onclick"){
				krpano.set("plugin["+tourSkill.plugin._name+"]."+$(e.target).data("prop"), "openurl("+nuval+",_blank);");
			}else{
				krpano.set("plugin["+tourSkill.plugin._name+"]."+$(e.target).data("prop"), nuval);
			}
		},

		doneEdition:function(e){
			var myid = this.myid;
			var tourSkill = this.model.get("tourSkill");
			
			
			tourSkill.plugin._url = $("#custome-signate-list .fa-check").parent().next().attr("src");
			tourSkill.plugin._x = $("#signature-skill-x").val();
			tourSkill.plugin._y = $("#signature-skill-y").val();
			tourSkill.plugin._zorder = $("#signature-skill-zorder").val();
			tourSkill.plugin._onclick = "openurl("+$("#signature-skill-linkto").val()+",_blank);";
			tourSkill.plugin._align = $("#signature-skill-align .selected").data("pos")
			tourSkill.plugin._alpha = $("#signature-skill-alpha").val();

			var manageData = new ManageData();
			manageData.editSkill(tourSkill)
			this.removeModal(e);
			this.undelegateEvents();
		
		},

		selectSignature:function(e){
			$("#custome-signate-list li .fa-check").remove();
			var krpano = document.getElementById("krpanoSWFObject");

			if($(e.target).attr('class')=="fa fa-check"){
				$(e.target).remove();
				if(this.oldImg){
						krpano.set("plugin[skill_signature].url",this.oldImg);
						$(".signature-skill-editor .defaultset").hide();
					}
				}else{
					if($(e.target).children().length){
						$(e.target).children().remove();
						if(this.oldImg){
							krpano.set("plugin[skill_signature].url",this.oldImg);
							$(".signature-skill-editor .defaultset").hide();
						}
					}else{
						$(e.target).append('<span class="fa fa-check"></span>');
						var myid = $(e.target).parent().attr("id");
						$(".signature-skill-editor .defaultset").show();
						$(".signature-skill-editor .defaultset").data("default_id",myid);
						if($(e.target).parent().data("default")=="1"){
							$(".signature-skill-editor .defaultset .fa").attr("class","fa fa-circle")
						}else{
							$(".signature-skill-editor .defaultset .fa").attr("class","fa fa-circle-o")
						}
						var imgsrc = $(e.target).parent().find("img").attr("src");
						krpano.set("plugin[skill_signature].url",imgsrc)
					}
				}
			},

		deleteSignatureImage:function(e){
			var myid = $(e.target).parent().attr("id");
			$(e.target).parent().append('<div class="delete-overlay">deleting...</div>')
			$.ajax({
				url:"php/updater.php?action=del_signature&id="+myid,
				//url:"data/json.php?t=g",
				dataType:"json",
				success:function(data){
					$("#custome-signate-list li#"+myid).remove();
					if(!$("#custome-signate-list li:eq(0) .check-wrap").children().size()){
						$("#custome-signate-list li:eq(0) .check-wrap").trigger('click')
					}
				}	
			})

		},

		setDefault:function(e){
			if($(e.target).hasClass("fa-circle-o")){
				$(e.target).attr("class","fa fa-circle");
				var default_id = $(e.target).parent().data("default_id");
				console.log(default_id);
				$.ajax({
					url:"php/updater.php?action=change_default_signature&id="+default_id,
					//url:"data/json.php?t=g",
					dataType:"json",
					success:function(data){
						console.log(data)
					}	
				})
			}
		}

	});

	return SignatureSkillEditor;
	
});
