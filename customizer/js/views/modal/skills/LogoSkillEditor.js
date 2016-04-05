define([
	'jquery',
	'underscore',
	'backbone',
	'views/modal/Modal',
	'text!templates/modal/logoSkillEditor.html',
	'helpers/HelpFunctions',
	'views/modal/SingleUploader',
	'mCustomScrollbar',
	'helpers/ManageData',
	'helpers/ManageTour',


], function($, _, Backbone,Modal,logoSkillEditor,HelpFunctions,SingleUploader, mCustomScrollbar,ManageData,ManageTour){

	var LogoSkillEditor = Modal.extend({
		
		initialize: function () {
		_.bindAll(this);        
		 _.extend(this.events, Modal.prototype.events);
		},
		events:{
			"click .skillModal #Context-menu-finish":"doneEdition",
			"click #logo-skill-align .selected":"alignSignature",
			"change .logo-skill-editor input":"changeVal"
		},
		
		renderExtend:function(){

			var myid = this.myid;
			var tourSkill = this.model.get("tourSkill");
			var template = _.template(logoSkillEditor,{tourSkill:tourSkill})
			$("#"+myid+" .inner-modal").html(template);
	
			_.each($("#logo-skill-align .fa-circle"),function(elem,ind){
				if($(elem).data("pos") == tourSkill.plugin._align){
					$(elem).addClass("selected");
				}
			})

			$("#"+myid+" header h2").text("Logo Skill Editor");
			$("#"+myid).find(".save-and-close").unbind("click");
			$(".scrollwrapper").mCustomScrollbar({
				theme:"minimal-dark",
				scrollInertia:300
			});

			var helpFunctions = new HelpFunctions();
			helpFunctions.dropDown("#"+myid+" .dropdown");
			helpFunctions.nineGrillSelector("#"+myid+" .position");

			var tour_id = location.hash.split("/")[1];
			var caso = 'skills';

			var SingleUploaderModel = Backbone.Model.extend({});
			var singleUploaderModel = new SingleUploaderModel({myid:"logo-skill-editor-img",imgsrc:tourSkill.plugin._url,tour_id:tour_id,caso:caso})
			var singleUploader = new SingleUploader({model:singleUploaderModel});
			singleUploader.render(function(){
				var krpano = document.getElementById("krpanoSWFObject");
				krpano.set("plugin["+tourSkill.plugin._name+"].url",$("#logo-skill-editor-img").data("imgsrc"));
			});

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
			
			tourSkill.plugin._url = $("#logo-skill-editor-img").data("imgsrc");
			tourSkill.plugin._x = $("#logo-skill-x").val();
			tourSkill.plugin._y = $("#logo-skill-y").val();
			tourSkill.plugin._zorder = $("#logo-skill-zorder").val();
			tourSkill.plugin._onclick = "openurl("+$("#logo-skill-linkto").val()+",_blank);";
			tourSkill.plugin._align = $("#logo-skill-align .selected").data("pos")
			tourSkill.plugin._align = $("#logo-skill-align .selected").data("pos")

			var manageData = new ManageData();
			manageData.editSkill(tourSkill)
			this.removeModal(e);
			this.undelegateEvents();
		
		}
	});

	return LogoSkillEditor;
	
});
