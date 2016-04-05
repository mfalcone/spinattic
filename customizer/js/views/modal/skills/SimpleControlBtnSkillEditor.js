define([
	'jquery',
	'underscore',
	'backbone',
	'views/modal/Modal',
	'text!templates/modal/simpleControlBtnSkillEditor.html',
	'helpers/HelpFunctions',
	'mCustomScrollbar',
	'colorpicker',
	'helpers/ManageData'
	/*'views/modal/SingleUploader',    
	'helpers/ManageData',
	'helpers/ManageTour',*/

], function($, _, Backbone,Modal,simpleControlBtnSkillEditor,HelpFunctions, mCustomScrollbar,colorpicker, ManageData){

	var SimpleControlBtnSkillEditor = Modal.extend({

		krpano:null,
		skill_controls_settings:null,
		initialize: function () {
		_.bindAll(this);        
		 _.extend(this.events, Modal.prototype.events);
		},
		events:{
			"click .simple-control-btns-skill-editor .dd-upload li":"selectDD",
			"click .simple-control-btns-skill-editor .bk-image li":"selectDDImage",
			"click .simple-control-btns-skill-editor .btnContainer .fa-plus":"addButton",
			"click .simple-control-btns-skill-editor .btnContainer .fa-trash":"removeButton",
			"click #scbPosition span":"alignSignature",
			"change input.scb_change":"changeValue",
			"change input.scb_border_change":"setBorderProp",
			"click #skillsEditor-7 #Context-menu-finish":"doneEdition",
			"change .simple-control-btns-skill-editor .onoffswitch-checkbox":"switchBox"
		},
		
		renderExtend:function(){

			var myid = this.myid;
			var tourSkill = this.model.get("tourSkill");
			this.skill_controls_settings = tourSkill.skill_controls_settings

			var orientationLabel = tourSkill.skill_controls_settings._orientation;
			orientationLabel = orientationLabel.substring(0,1).toUpperCase()+orientationLabel.substring(1);

			var layoutLabel = tourSkill.skill_controls_settings._layout;
			layoutLabel = layoutLabel.substring(0,1).toUpperCase()+layoutLabel.substring(1);

			var backimage = tourSkill.skill_controls_settings._icons_styles;
			backimage = backimage.replace("%SWFPATH%","../player");

			var borderproperties = tourSkill.skill_controls_settings._bgborder.split(" ");

			borderprop = {
				width: borderproperties[0],
				color: borderproperties[1],
				transp: borderproperties[2],
			}
			
			var arrowPos = tourSkill.skill_controls_settings._arrows_position;
			arrowPos = arrowPos.substring(0,1).toUpperCase()+arrowPos.substring(1);

			console.log(tourSkill)
			var template = _.template(simpleControlBtnSkillEditor,{tourSkill:tourSkill,orientationLabel:orientationLabel,layoutLabel:layoutLabel,backimage:backimage,borderprop:borderprop,arrowPos:arrowPos})

			$("#"+myid+" .inner-modal").html(template);

			$("#"+myid+" header h2").text(tourSkill._kind);
			$("#"+myid).find(".save-and-close").unbind("click");
			var este = this;
			 $('#scb-bgcolor').colorpicker({select:function(ev, colorPicker){
				este.setColor(colorPicker,ev)
			}});

			$('#scb-border-bgcolor').colorpicker({select:function(ev, colorPicker){
				este.setBorderProp()
			}});

			_.each($("#scbPosition span"),function(elem,ind){
				if($(elem).data("pos") == tourSkill.skill_controls_settings._position){
					$(elem).addClass("selected");
				}
			})

			var helpFunctions = new HelpFunctions();
			helpFunctions.skillTabs(myid);
			helpFunctions.dropDown("#"+myid+" .dropdown");
			
			$(".scrollwrapper").mCustomScrollbar({
				theme:"minimal-dark",
				scrollInertia:300
			});

			this.krpano = document.getElementById("krpanoSWFObject");
			var este = this;

			$("#generalSettingsContent .scbOrder").sortable({
				handle:".sortArrows",
				beforeStop:function(evt,ui){

				setTimeout(function(){    
				  
					este.ordericons();

					},200) 
				}
			});

		},

		ordericons:function(){
			var acciones = [];
			_.each($(".scbOrder .btnContainer"),function(elem,ind){
				if($(elem).find(".skills-add-btn").attr("id")){
					var myval = $(elem).find(".skills-add-btn").attr("id");
				}else{
					var myval = "false";
				}
				acciones.push(myval);
			})
			this.krpano.call("set(skill_controls_settings.order_1,"+acciones[0]+");set(skill_controls_settings.order_2,"+acciones[1]+");set(skill_controls_settings.order_3,"+acciones[2]+"); skill_controls_build();");
	   		this.skill_controls_settings._order_1 = acciones[0];
	   		this.skill_controls_settings._order_2 = acciones[1];
	   		this.skill_controls_settings._order_3 = acciones[2];
		},


		selectDD:function(e){

			var myval = $(e.target).data("value");
			$(e.target).parents(".dropdown").data("selected",myval);
			var param = $(e.target).parent().data("param");
			this.krpano.call("set(skill_controls_settings."+param+","+myval+"); skill_controls_build();");
			this.skill_controls_settings["_"+param] = myval;
		},

		selectDDImage:function(e){
			if($(e.target).prop("tagName") == "LI"){
				var myval = $(e.target).data("value");
				var src = $(e.target).find("img").attr("src");
			}else{
			   var myval = $(e.target).parent().data("value");
			   var src = $(e.target).attr("src");
			}
			var param = "icons_styles";
			this.krpano.call("set(skill_controls_settings."+param+","+myval+"); skill_controls_build();");
			this.skill_controls_settings["_"+param] = myval;
			$("#iconStyles h2 img").attr("src",src);
			$("#generalSettingsContent .icon-wrap").css("background-image","url("+src+")");
		},  

		changeValue:function(e){
			var myval = $(e.target).val();
			var param = $(e.target).data("param");            
			this.krpano.call("set(skill_controls_settings."+param+","+myval+"); skill_controls_build();");
			this.skill_controls_settings["_"+param] = myval;
		},

		setColor:function(colorPicker,e){
			var myval = "0x"+colorPicker.formatted;
			var param = $(e.target).data("param");  
			this.krpano.call("set(skill_controls_settings."+param+","+myval+"); skill_controls_build();");
			this.skill_controls_settings["_"+param] = myval;

		},

		setBorderProp:function(){
			var myval = $("#border-width").val()+" 0x"+$("#scb-border-bgcolor").val()+" "+$("#border-transparency").val();
			this.krpano.call("set(skill_controls_settings.bgborder,"+myval+"); skill_controls_build();");
			this.skill_controls_settings._bgborder = myval;

		},
		switchBox:function(e){
			var este = this;
			var param = $(e.target).data("param");
			
			if($(e.target).is(':checked')){
				este.krpano.call("set(skill_controls_settings."+param+",true); skill_controls_build();");
				este.skill_controls_settings["_"+param] = true;
			}else{
				este.krpano.call("set(skill_controls_settings."+param+",false); skill_controls_build();");
				este.skill_controls_settings["_"+param] = false;
			}
		},

		alignSignature:function(e){
			$("#scbPosition span").removeClass("selected");
			$(e.target).addClass("selected");
			var pos = $(e.target).data("pos");
			this.krpano.call("set(skill_controls_settings.position,"+pos+"); skill_controls_build();");
			this.skill_controls_settings._position = pos;
		},

		doneEdition:function(e){
			console.log("close")
			var tourSkill = this.model.get("tourSkill");
			tourSkill.skill_controls_settings = this.skill_controls_settings;
			var manageData = new ManageData();
			manageData.editSkill(tourSkill)
			this.removeModal(e);
			this.undelegateEvents();
		},

		removeButton:function(){

		},

		addButton:function(e){
			
			if($(e.target).parents(".btnContainer").find(".add-bt-wrap").size()){
				$(e.target).parents(".btnContainer").find(".add-bt-wrap").remove();
				return;
			}
			
			if($("#generalSettingsContent .scbOrder .add-bt-wrap").size()){
				$("#generalSettingsContent .scbOrder .add-bt-wrap").remove();
			}

			$btContainer = $('<div class="add-bt-wrap"></div>');
			$(e.target).parents(".btnContainer").append($btContainer);
			var allbts = ["skill_controls_full","skill_controls_autorotate","skill_controls_zoom"];
			var positions = ["_order_1","_order_2","_order_3"];
			var este = this;
			_.each(positions,function(el,ind){
				_.each(allbts,function(ele,indice){
					if(este.skill_controls_settings[positions[ind]] == allbts[indice]){
						allbts.splice(indice,1)
					}
				})
			})
			var bts = [];
			$btContainer.html('<ul></ul>')
			var backimage = este.skill_controls_settings._icons_styles;
			backimage = backimage.replace("%SWFPATH%","../player");
			_.each(allbts,function(ele,indice){
				$btContainer.find("ul").append('<li id="'+ele+'_bt" data-id="'+ele+'"><div class="icon-wrap" style="background-image:url('+backimage+')"></div></li>')
				})
			console.log(allbts)
			$btContainer.find("li").click(function(e){
					var myid = $(this).data("id");
					$(this).parents(".btnContainer").find(".skills-add-btn").attr("id",myid);
					este.ordericons();
					$btContainer.remove();
			})

			
		},

		removeButton:function(e){
			$(e.target).parents(".skills-add-btn").removeAttr("id");
			this.ordericons();	
		}

	});

	return SimpleControlBtnSkillEditor;
	
});
