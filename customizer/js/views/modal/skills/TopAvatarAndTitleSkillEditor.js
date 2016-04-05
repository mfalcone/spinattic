define([
	'jquery',
	'underscore',
	'backbone',
	'views/modal/Modal',
	'text!templates/modal/topAvatarAndTitleSkillEditor.html',
	'helpers/HelpFunctions',
	'mCustomScrollbar',
	'colorpicker',
	'helpers/ManageData'

], function($, _, Backbone,Modal,topAvatarAndTitleSkillEditor,HelpFunctions, mCustomScrollbar,colorpicker, ManageData){

	var TopAvatarAndTitleSkillEditor = Modal.extend({

		krpano:null,
		simple_menu_settings:null,
		initialize: function () {
		_.bindAll(this);        
		 _.extend(this.events, Modal.prototype.events);
		},
		events:{
			'click #positionAvSkill span':'setPosition',
			'click #avAlignmentAvSkill span':'setPosition',
			'click #textAlignAvSkill span':'setPosition',
			'change .top-avatar-and-titles-skill-editor input.single-int':"setProperties",
			'change .top-avatar-and-titles-skill-editor input[type="checkbox"]':'checkboxChange',
			'click #avatarWidthDD li': 'setWidthType',
			'change #textpaddingAvSkill input':"setMultipleProperties",
			"click #skillsEditor-8 #Context-menu-finish":"doneEdition",
		},
		
		renderExtend:function(){

			var myid = this.myid;
			var tourSkill = this.model.get("tourSkill");
			this.skill_avatar_title = tourSkill.skill_avatar_title;

			padding = tourSkill.skill_avatar_title._padding.split(" ");
			
			customprop = {
				padding:padding,
			}

			var template = _.template(topAvatarAndTitleSkillEditor,{tourSkill:tourSkill,customprop:customprop})
			$("#"+myid+" .inner-modal").html(template);

			$("#positionAvSkill span[data-align="+tourSkill.skill_avatar_title._position+"]").addClass("selected");
			$("#avAlignmentAvSkill span[data-align="+tourSkill.skill_avatar_title._avatar_align+"]").addClass("selected")
			$("#textAlignAvSkill span[data-align="+tourSkill.skill_avatar_title._text_align+"]").addClass("selected")

			$("#"+myid+" header h2").text("Top Avatar and Title Skill Editor");
			$("#"+myid).find(".save-and-close").unbind("click");
			var este = this;
			
			if(tourSkill.skill_avatar_title._width_type == "fluid"){
				$("#widthAvSkill").prop('disabled',true);
			}
			$('#'+myid+' .colorpicker').colorpicker({select:function(ev, colorPicker){
				este.setColor(ev)
			}});


			var helpFunctions = new HelpFunctions();
			helpFunctions.skillTabs(myid);
			helpFunctions.dropDown("#"+myid+" .dropdown");

			
			$(".scrollwrapper").mCustomScrollbar({
				theme:"minimal-dark",
				scrollInertia:300
			});

			this.krpano = document.getElementById("krpanoSWFObject");
			var este = this;

		},

		setPosition:function(e){
			$(e.target).parent().find("span").removeClass("selected");
			$(e.target).addClass("selected");
			var prop = $(e.target).data("prop");
			var value = $(e.target).data("align");
			this.krpano.call("set(skill_avatar_title."+prop+","+value+");skill_avatar_build(); ");	
			console.log("krpano.call: "+"set(skill_avatar_title."+prop+","+value+");skill_avatar_build(); ")
			this.skill_avatar_title["_"+prop]=value;
		},

		setProperties:function(e){
			var helpFunctions = new HelpFunctions();
			helpFunctions.limitInputs($(e.target));
			var prop = $(e.target).data("prop");
			var value = $(e.target).val();
			var finalFun = $(e.target).data("finalfun");
			this.skill_avatar_title["_"+prop]=value;
			this.krpano.call("set(skill_avatar_title."+prop+","+value+");	"+finalFun+"();");
			console.log("krpano call: "+"set(skill_avatar_title."+prop+","+value+");	"+finalFun+"();")
		},


		checkboxChange:function(e){
			if($(e.target).is(":checked")){
				var value="true";
			}else{
				var value="false";
			}
			var prop = $(e.target).data("prop");
			var finalfun = $(e.target).data("finalfun");
			this.krpano.call("set(skill_avatar_title."+prop+","+value+");"+finalfun+"();");
			console.log("krpano call: "+"set(skill_avatar_title."+prop+","+value+");"+finalfun+"();")
			this.skill_avatar_title["_"+prop]=value;
		},

		setWidthType:function(e){
			myval = $(e.target).data("value");
			$("#avatarWidthDD").data("value",myval);
			if(myval == "fluid"){
				$("#widthAvSkill").prop('disabled',true);
				var defval = "100";
				$("#widthAvSkill").val(defval);
				$("#positionAvSkillController").hide();
			}else{
				$("#widthAvSkill").prop('disabled',false);
				var defval = "500";
				$("#widthAvSkill").val(defval);
				$("#positionAvSkillController").show();
			}

			this.krpano.call("set(skill_avatar_title.width,"+defval+");set(skill_avatar_title.width_type,"+myval+");skill_avatar_build();");
			console.log("set(skill_avatar_title.width_type,"+myval+");set(skill_avatar_title.width,"+defval+");skill_avatar_build();");
			this.skill_avatar_title._width=defval;
			this.skill_avatar_title._width_type=myval;
		},

		setColor:function(e){
			
			var prop = $(e.target).data("prop");
			var finalfun = $(e.target).data("finalfun");
			if($(e.target).attr("id") == "aMarginAvSkill"){
				var value = "#"+$(e.target).val();
			}else{
				var value = "0x"+$(e.target).val();
			}
			this.krpano.call("set(skill_avatar_title."+prop+","+value+");"+finalfun+"();");
			console.log("krpano call: "+"set(skill_avatar_title."+prop+","+value+");"+finalfun+"();")
			this.skill_avatar_title["_"+prop]=value;
		},

		setMultipleProperties:function(e){
			var helpFunctions = new HelpFunctions();
			var group = $(e.target).data("group");
			helpFunctions.limitInputs($('input[data-group="'+group+'"]:eq(0)'));
			helpFunctions.limitInputs($('input[data-group="'+group+'"]:eq(1)'));
			helpFunctions.limitInputs($('input[data-group="'+group+'"]:eq(2)'));
			helpFunctions.limitInputs($('input[data-group="'+group+'"]:eq(3)'));
			var val1 = $('input[data-group="'+group+'"]:eq(0)').val();
			var val2 = $('input[data-group="'+group+'"]:eq(1)').val();
			var val3 = $('input[data-group="'+group+'"]:eq(2)').val();
			var val4 = $('input[data-group="'+group+'"]:eq(3)').val();
			var prop = $(e.target).data("prop");
			var totalVal = val1+" "+val2+" "+val3+" "+val4;
			this.skill_avatar_title["_"+prop]=totalVal;
			this.krpano.call("set(skill_avatar_title."+prop+","+totalVal+");skill_avatar_build();");
			console.log("krpano call: "+"set(skill_avatar_title."+prop+","+totalVal+");skill_avatar_build();")
		},


		doneEdition:function(e){
			var tourSkill = this.model.get("tourSkill");
			tourSkill.skill_avatar_title = this.skill_avatar_title;
			var manageData = new ManageData();
			manageData.editSkill(tourSkill)
			this.removeModal(e);
			this.undelegateEvents();
		}

	});

	return TopAvatarAndTitleSkillEditor;
	
});
