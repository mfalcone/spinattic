define([
	'jquery',
	'underscore',
	'backbone',
	'views/modal/Modal',
	'text!templates/modal/nadirPatchSkillEditor.html',
	'helpers/HelpFunctions',
	'views/modal/SingleUploader',
	'mCustomScrollbar',
	'helpers/ManageData',
	'helpers/ManageTour',


], function($, _, Backbone,Modal,nadirPatchSkillEditor,HelpFunctions,SingleUploader, mCustomScrollbar,ManageData,ManageTour){

	var NadirPatchSkillEditor = Modal.extend({
		
		initialize: function () {
		_.bindAll(this);        
		 _.extend(this.events, Modal.prototype.events);
		},
		events:{
			"click .skillModal #Context-menu-finish":"doneEdition",
			"change .nadir-patch-skill-editor .onoffswitch-checkbox":"switchBox",
			'change .nadir-patch-skill-editor input[type="text"]':'changeVal',
			'change .nadir-patch-skill-editor input[type="number"]':'changeVal'
		},
		
		renderExtend:function(){

			var myid = this.myid;
			var tourSkill = this.model.get("tourSkill");
			var template = _.template(nadirPatchSkillEditor,{tourSkill:tourSkill})
			$("#"+myid+" .inner-modal").html(template);
	
			$("#"+myid+" header h2").text("Nadir Patch Skill Editor");
			$("#"+myid).find(".save-and-close").unbind("click");
			$(".scrollwrapper").mCustomScrollbar({
				theme:"minimal-dark",
				scrollInertia:300
			});

			var este = this;

			this.skill_nadirpatch_settings = tourSkill.skill_nadirpatch_settings;
			this.krpano = document.getElementById("krpanoSWFObject");
			
			var tour_id = location.hash.split("/")[1];
			var caso = 'skills';
			var imagepath = tourSkill.skill_nadirpatch_settings._image.replace("%SWFPATH%",location.protocol+"//"+location.host+"/player")
			var SingleUploaderModel = Backbone.Model.extend({});
			var singleUploaderModel = new SingleUploaderModel({myid:"nadirPatch-skill-editor-img",imgsrc:imagepath,tour_id:tour_id,caso:caso})
			var singleUploader = new SingleUploader({model:singleUploaderModel});
			singleUploader.render(function(){
				este.krpano.set("skill_nadirpatch_settings.image",$("#nadirPatch-skill-editor-img").data("imgsrc"))
			});

		},


		switchBox:function(e){
			var este = this;
			var param = $(e.target).data("prop");
			if($(e.target).is(':checked')){
				this.krpano.call("set(skill_nadirpatch_settings."+param+",true); skill_nadirpatch_set();");
				
				este.skill_nadirpatch_settings["_"+param] = "true";
				console.log(este.skill_nadirpatch_settings)
				if(param == "link"){
					$(".nadir-patch-skill-editor .url-text *").show();
				}
			}else{
				this.krpano.call("set(skill_nadirpatch_settings."+param+",false); skill_nadirpatch_set();");
				este.skill_nadirpatch_settings["_"+param] = "false";
				if(param == "link"){
					$(".nadir-patch-skill-editor .url-text *").hide();
				}
			}


		},

		changeVal:function(e){
			var value = $(e.target).val();
			var param = $(e.target).data("prop");
			if(param == "linkurl"){
				if(!this.validateURL(value)){
					value = "http://"+value;
					$(e.target).val(value)
				}
			}

			this.krpano.call("set(skill_nadirpatch_settings."+param+","+value+"); skill_nadirpatch_set();");
			this.skill_nadirpatch_settings["_"+param] = value;
		},

	
		doneEdition:function(e){
			var myid = this.myid;
			var este = this;
			var tourSkill = this.model.get("tourSkill");
			
			tourSkill.skill_nadirpatch_settings = este.skill_nadirpatch_settings;
		
			console.log(este.skill_nadirpatch_settings);
		
			var manageData = new ManageData();
			manageData.editSkill(tourSkill)
			this.removeModal(e);
			this.undelegateEvents();
		
		},

		validateURL:function(textval) {
			var urlregex = /^(https?|ftp):\/\/([a-zA-Z0-9.-]+(:[a-zA-Z0-9.&%$-]+)*@)*((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9][0-9]?)(\.(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]?[0-9])){3}|([a-zA-Z0-9-]+\.)*[a-zA-Z0-9-]+\.(com|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-zA-Z]{2}))(:[0-9]+)*(\/($|[a-zA-Z0-9.,?'\\+&%$#=~_-]+))*$/;
			return urlregex.test(textval);
			}
	});

	return NadirPatchSkillEditor;
	
});
