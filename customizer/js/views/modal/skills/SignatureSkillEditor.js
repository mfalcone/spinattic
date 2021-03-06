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
	'models/main/ModalModel',
	'views/modal/AlertView',


], function($, _, Backbone,Modal,signatureSkillEditor,HelpFunctions,SingleUploader, mCustomScrollbar,HelpFunctions,ManageData,ManageTour,ModalModel,AlertView){

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
			var singleUploaderModel = new SingleUploaderModel({myid:"signature-skill-editor-img",caso:caso,textMessage:txtmsg,skill_id:tourSkill._template_id})
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
			var este = this;
			$.ajax({
				url:"data/json.php?t=dg",
				dataType:"json",
				async:false, 
				success:function(data){
					$("#"+myid).data("defaultImage",data.id);
					este.lastId = data.id;
					}
				})
			
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
						$("#custome-signate-list").mCustomScrollbar('destroy');
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
			var default_id = $("#"+myid).data("defaultImage");
			$.ajax({
					url:"php/updater.php?action=change_default_signature&id="+default_id,
					//url:"data/json.php?t=g",
					dataType:"json",
					success:function(data){
						
					}	
				})

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
			var este = this;
			$(e.target).parent().append('<div class="delete-overlay">deleting...</div>')
			$.ajax({
				url:"php/updater.php?action=del_signature&id="+myid,
				//url:"data/json.php?t=g",
				dataType:"json",
				success:function(data){
					console.log(data);
					if(data.state=="ERROR"){

						if(data.desc.length>1){
							var msg = "This signature is being used by the following tours: <br>";
							var toursInuse = "";
							_.each(data.desc,function(obj,ind){
								toursInuse+='<a href="'+location.protocol+'//'+location.host+'/customizer/#tour/'+obj.id+'" target="_blank">'+obj.title+'</a><br>'
							})
							msg+= toursInuse;
							msg+="If you want to delete this file, please remove it from these tours first.";
							}else{
							var msg = "This signature is being used in the current tour. Please select another and save the changes, then you can remove this file"
						}
						$("#custome-signate-list li#"+myid+" .delete-overlay").remove();
						este.showMsg(msg);

					}else{
						$("#custome-signate-list li#"+myid).remove();
						if(!$("#custome-signate-list li .fa-check").size()){
							$("#custome-signate-list li:eq(0) .check-wrap").trigger('click')
						}
					}
				}	
			})

		},

		setDefault:function(e){
			var este = this;
			var myid = this.myid;
			if($(e.target).hasClass("fa-circle-o")){
				$(e.target).attr("class","fa fa-circle");
				var default_id = $(e.target).parent().data("default_id");
				$("#"+myid).data("defaultImage",default_id);
				_.each($("#custome-signate-list li"),function(elem,ind){
							$(elem).data("default","0")
						})
				$("#custome-signate-list li .fa-check").parents("li").data("default","1")
				
			}else{
				$(e.target).attr("class","fa fa-circle-o");
				$("#"+myid).data("defaultImage",este.lastId);
			}
		},
		showMsg: function(msg){
			var modalModel = new ModalModel({msg:msg})
			var alertView = new AlertView({model:modalModel});
			alertView.render("alert",alertView.renderExtend);
		}

	});

	return SignatureSkillEditor;
	
});
