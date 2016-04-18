define([
	'jquery',
	'underscore',
	'backbone',
	'text!templates/sidebar/skincustomizeritem.html',
	'views/modal/skills/SkillEditor',
	'views/modal/skills/ContextMenuSkillEditor',
	'views/modal/skills/LogoSkillEditor',
	'views/modal/skills/SignatureSkillEditor',
	'views/modal/skills/LoadingBarSkillEditor',
	'views/modal/skills/NadirPatchSkillEditor',
	'helpers/HelpFunctions',
	'helpers/ManageData',
	'helpers/ManageTour',
	'views/modal/skills/StandardLoadingSkillEditor',
	'views/modal/skills/SimpleControlBtnSkillEditor',
	'models/main/ModalModel',
	'views/modal/AlertView',
	'views/modal/ConfirmView',
	'views/modal/skills/SimpleMenuSkillEditor',
	'views/modal/skills/TopAvatarAndTitleSkillEditor'
   

], function($, _, Backbone,skincustomizeritem,SkillEditor,ContextMenuSkillEditor,LogoSkillEditor,SignatureSkillEditor,LoadingBarSkillEditor,NadirPatchSkillEditor,HelpFunctions,ManageData,ManageTour, StandardLoadingSkillEditor, SimpleControlBtnSkillEditor,ModalModel,AlertView,ConfirmView,SimpleMenuSkillEditor,TopAvatarAndTitleSkillEditor){

	var SkinCustomizerItem = Backbone.View.extend({

		events:{
		},

		initialize: function () {

		   _.bindAll(this);
	   },

	   disableMessage:'Sorry! You can\'t customize or remove this Skill because your account level is "Free user". This Skill is only for "Advanced" and "Pro" users.',
		
		render:function(){
			var disable = false;
			var no_delete_if_free = this.model.get("no_delete_if_free");
			var customizable = this.model.get("customizable");
			var allow_customize = this.model.get("allow_customize");
			console.log(allow_customize);
			if(allow_customize == "0"){
				var disable = true;
			}
			var tourSkill = this.model.get("tourSkill");
			var compiledTemplate = _.template(skincustomizeritem,{tourSkill:tourSkill,disable:disable,customizable:customizable});

			$("#skinCustomizer-menu .skill-list").append(compiledTemplate);
			$('#skill-' + tourSkill._template_id ).data("skill",tourSkill);
			$('#skill-' + tourSkill._template_id + ' .customizelink').click(this.editSkill);
			
			var view = this;
			$('#skill-' + tourSkill._template_id + ' .fa-close').click(function(event){
				
				var v = view;
				view.removeSkill(event,v)
				
				});
		  	
		},

		editSkill:function(e){
			var skill = $(e.target).parents("li").data("skill");
			var tourSkill = this.model.get("tourSkill");

			var no_delete_if_free = this.model.get("no_delete_if_free");
			var allow_customize = this.model.get("allow_customize");
			console.log(allow_customize)
			var este = this;

				if(allow_customize == "0"){
					este.showMsg(este.disableMessage);
					return;
				}
			
			switch(tourSkill._template_id){
				case "1":
				var mview = ContextMenuSkillEditor;
				break;
				case "2":
				var mview = LoadingBarSkillEditor;
				break;
				case "3":
				var mview = LogoSkillEditor;
				break;
				case "4":
				var mview = SignatureSkillEditor;
				break;
				case "5":
				var mview = StandardLoadingSkillEditor;
				break;
				case "6":
				var mview = SimpleMenuSkillEditor;
				break;
				case "7":
				var mview = SimpleControlBtnSkillEditor;
				break;
				case "8":
				var mview = TopAvatarAndTitleSkillEditor;
				break;
				case "10":
				var mview = NadirPatchSkillEditor
				break;
				default:
				var mview = SkillEditor
			}
			
			if($(".skillModal").length){

					$(".skillModal").find(".save-and-close").trigger("click");

			}
				var SkillModel = Backbone.Model.extend({});
				skillModel = new SkillModel({tourSkill:tourSkill});
				var skillEditor = new mview({model:skillModel});
				skillEditor.render("skillsEditor-"+tourSkill._template_id,skillEditor.renderExtend);
				$("#skillsEditor-"+tourSkill._template_id).addClass("skillModal").parent(".overlay").addClass("skillWindow");
				skillEditor.verticalCent();
			
		},

		removeSkill:function(e,v){

			var tourSkill = this.model.get("tourSkill");
			var no_delete_if_free = this.model.get("no_delete_if_free");
			var este = this;

			if($(".main-header .user").data("level") == "FREE"){
				if(no_delete_if_free == "1"){
					este.showMsg(este.disableMessage);
					return;
				}
			}

			var customizable = this.model.get("customizable");
			if(customizable=="0"){
				var msg = "Are you sure you want to remove "+tourSkill._kind+"?";
			}else{
				var msg = "If you remove this skill, you'll lose the custom configurations associated with it.";
			}

			evt = function(){
				if($('#skillsEditor-' + tourSkill._template_id ).length){
					$('#skillsEditor-' + tourSkill._template_id ).find(".save-and-close").trigger("click");
				}
				
				var helpFunctions = new HelpFunctions();
				helpFunctions.showReloadOverlay();
				var manageData = new ManageData()
				
				$("#confirmDel .fa-close").trigger("click");
				beforeReload = function(){
					var manageTour = new ManageTour();
					manageTour.reloadTour(function(){
						if($("#skillsModalList").length){
							$("#skinCustomizer-menu .add-link").trigger("click")
						}
					})
				}

				manageData.removeSkill(tourSkill._kind,beforeReload)

				$('#skill-' + tourSkill._template_id).remove();
				v.undelegateEvents();
				v.remove();
			}
			var modalModel = new ModalModel({msg:msg,evt:evt})
			var alertView = new ConfirmView({model:modalModel});
			alertView.render("confirmDel",alertView.renderExtend);
		},

		showMsg: function(msg){
			var modalModel = new ModalModel({msg:msg})
			var alertView = new AlertView({model:modalModel});
			alertView.render("alert",alertView.renderExtend);
		},
		
	});

	return SkinCustomizerItem;
	
});
