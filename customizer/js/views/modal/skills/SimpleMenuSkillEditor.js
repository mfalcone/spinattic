define([
	'jquery',
	'underscore',
	'backbone',
	'views/modal/Modal',
	'text!templates/modal/simpleMenuSkillEditor.html',
	'helpers/HelpFunctions',
	'mCustomScrollbar',
	'colorpicker',
	'helpers/ManageData'

], function($, _, Backbone,Modal,simpleMenuSkillEditor,HelpFunctions, mCustomScrollbar,colorpicker, ManageData){

	var SimpleMenuSkillEditor = Modal.extend({

		krpano:null,
		simple_menu_settings:null,
		initialize: function () {
		_.bindAll(this);        
		 _.extend(this.events, Modal.prototype.events);
		},
		events:{
			'change .simple-menu-skill-editor .single-int':"setProperties",
			'change .simple-menu-skill-editor .multiple-int':"setMultipleProperties",
			'change #widthSmSkill':'setWidth',
			'click #widthSmSkillDD li':'setUnit',
			'click #alignPosSmSkill':'alignlayout',
			'click #alignPosSmSkillText':'alignText',
			'change input[type="checkbox"]':'checkboxChange',
			'click #fontFamilyDD li':'selectFontFamily',
			'click #fontweightdd li':'selectFontWeight',
			'change #textpaddingsm input':'changeTextPadding',
			"click #skillsEditor-6 #Context-menu-finish":"doneEdition",
		},
		
		renderExtend:function(){

			var myid = this.myid;
			var tourSkill = this.model.get("tourSkill");
			this.simple_menu_settings = tourSkill.simple_menu_settings

			tour_width_unit = tourSkill.simple_menu_settings._layout_width.slice(-1);
			tour_width = tourSkill.simple_menu_settings._layout_width.split("%")[0];
			if(tour_width_unit != "%")tour_width_unit = "px";
			backborder = tourSkill.simple_menu_settings._design_bgborder.split(" ")
			thumbborder = tourSkill.simple_menu_settings._design_thumbborder_bgborder.split(" ")
			titlePadding = tourSkill.simple_menu_settings._title_padding.split(" ")


			customprop = {
				tour_width:tour_width,
				tour_width_unit:tour_width_unit,
				backborder:backborder,
				thumbborder:thumbborder,
				titlePadding:titlePadding,
			}

			var template = _.template(simpleMenuSkillEditor,{tourSkill:tourSkill,customprop:customprop})
			$("#"+myid+" .inner-modal").html(template);

			$("#alignPosSmSkill span[data-align="+tourSkill.simple_menu_settings._layout_align+"]").addClass("selected")
			$("#alignPosSmSkillText span[data-align="+tourSkill.simple_menu_settings._title_align+"]").addClass("selected")

			$("#"+myid+" header h2").text("Thumbs menu skill editor");
			$("#"+myid).find(".save-and-close").unbind("click");
			var este = this;
			
			 $('#bkgColorSmSkill,#textcolorsm').colorpicker({select:function(ev, colorPicker){
				este.setProperties(ev)
			}});

			$("#thumbColorSmSkill,#bkgBorderColorSmSkill").colorpicker({select:function(ev, colorPicker){
				este.setMultipleProperties(ev)
			}});

			var helpFunctions = new HelpFunctions();
			helpFunctions.skillTabs(myid);
			helpFunctions.dropDown("#"+myid+" .dropdown");
			/*helpFunctions.nineGrillSelector("#"+myid+" .position");*/
			
			$(".scrollwrapper").mCustomScrollbar({
				theme:"minimal-dark",
				scrollInertia:300
			});

			$("#fontFamilyDD ul").mCustomScrollbar({
				theme:"minimal-dark",
				scrollInertia:300
			});

			this.krpano = document.getElementById("krpanoSWFObject");
			var este = this;

		},

		setWidth:function(e){
			var helpFunctions = new HelpFunctions();
			helpFunctions.limitInputs($("#widthSmSkill"));
			var unit =  ($("#widthSmSkillDD").data("value")=="%") ? "%":"";
			var value = $("#widthSmSkill").val()+unit;
			this.krpano.call("set(simple_menu_settings.layout_width,"+value+");	simple_menu_design();");
			console.log("krpano.call: "+"set(simple_menu_settings.layout_width,"+value+");simple_menu_design();")
			this.simple_menu_settings._layout_width=value;
		},

		setUnit:function(e){
			var value = $(e.target).data("value");
			$("#widthSmSkillDD").data("value",value);
			if(value == "%"){
				$("#widthSmSkill").attr("min","10");
				$("#widthSmSkill").attr("max","100");
				if($("#widthSmSkill").val() == "50"){
					$("#widthSmSkill").val("10")
				}else if($("#widthSmSkill").val() > "100"){
					$("#widthSmSkill").val("100")
				}
			}else{
				$("#widthSmSkill").attr("min","50");
				$("#widthSmSkill").attr("max","1000");
				if($("#widthSmSkill").val() == "10"){
					$("#widthSmSkill").val("50")
				}
			}
		this.setWidth();
		},

		alignlayout:function(e){
			$("#alignPosSmSkill span").removeClass("selected");
			$(e.target).addClass("selected");
			var value = $(e.target).data("align");
			this.krpano.call("set(simple_menu_settings.layout_align,"+value+");simple_menu_design();");
			console.log("krpano.call: "+"set(simple_menu_settings.layout_align,"+value+");simple_menu_design();")
			this.simple_menu_settings._layout_align=value;
		},

		alignText:function(e){
			$("#alignPosSmSkillText span").removeClass("selected");
			$(e.target).addClass("selected");
			var value = $(e.target).data("align");
			this.krpano.call("set(simple_menu_settings.title_align,"+value+");simple_menu_title_styles();");
			console.log("krpano.call: "+"set(simple_menu_settings.title_align,"+value+");simple_menu_title_styles();")
			this.simple_menu_settings._title_align=value;
		},

		setProperties:function(e){
			var helpFunctions = new HelpFunctions();
			helpFunctions.limitInputs($(e.target));
			var prop = $(e.target).data("prop");
			if($(e.target).attr("type") == "text"){
				if($(e.target).attr("id") == "textcolorsm"){
					var value = "#"+$(e.target).val();
				}else{
				var value = "0x"+$(e.target).val();
				}
			}else{
				var value = $(e.target).val();
			}
			var finalFun = $(e.target).data("finalfun");
			this.simple_menu_settings["_"+prop]=value;
			this.krpano.call("set(simple_menu_settings."+prop+","+value+");	"+finalFun+"();");
			console.log("krpano call: "+"set(simple_menu_settings."+prop+","+value+");	"+finalFun+"();")
		},

		setMultipleProperties:function(e){
			var helpFunctions = new HelpFunctions();
			var group = $(e.target).data("group");
			helpFunctions.limitInputs($('input[data-group="'+group+'"]:eq(0)'));
			helpFunctions.limitInputs($('input[data-group="'+group+'"]:eq(2)'));
			console.log(group)
			var val1 = $('input[data-group="'+group+'"]:eq(0)').val();
			var val2 = $('input[data-group="'+group+'"]:eq(1)').val();
			var val3 = $('input[data-group="'+group+'"]:eq(2)').val();
			var prop = $(e.target).data("prop");
			var totalVal = val1+" 0x"+val2+" "+val3;
			this.simple_menu_settings["_"+prop]=totalVal;
			this.krpano.call("set(simple_menu_settings."+prop+","+totalVal+");simple_menu_startup();");
			console.log("krpano call: "+"set(simple_menu_settings."+prop+","+totalVal+");simple_menu_startup();")
		},

		checkboxChange:function(e){
			if($(e.target).is(":checked")){
				var value="true";
			}else{
				var value="false";
			}
			var prop = $(e.target).data("prop");
			var finalfun = $(e.target).data("finalfun");
			this.krpano.call("set(simple_menu_settings."+prop+","+value+");"+finalfun+"();");
			console.log("krpano call: "+"set(simple_menu_settings."+prop+","+value+");"+finalfun+"();")
			this.simple_menu_settings["_"+prop]=value;
		},

		selectFontFamily:function(e){
			var value= $(e.target).data("value");
			this.krpano.call("set(simple_menu_settings.title_font,"+value+");simple_menu_title_styles();");
			console.log("krpano call: "+"set(simple_menu_settings.title_font,"+value+");simple_menu_title_styles();")
			this.simple_menu_settings._title_font=value;
		},
		selectFontWeight:function(e){
			var value= $(e.target).data("value");
			this.krpano.call("set(simple_menu_settings.title_weight,"+value+");simple_menu_title_styles();");
			console.log("krpano call: "+"set(simple_menu_settings.title_weight,"+value+");simple_menu_title_styles();")
			this.simple_menu_settings._title_weight=value;
		},

		changeTextPadding:function(e){
			var value = $("#textpaddingsm input:eq(0)").val()+" "+$("#textpaddingsm input:eq(1)").val()+" "+$("#textpaddingsm input:eq(2)").val()+" "+$("#textpaddingsm input:eq(3)").val();
			this.krpano.call("set(simple_menu_settings.title_padding,"+value+");simple_menu_title_styles();");
			console.log("krpano call: "+"set(simple_menu_settings.title_padding,"+value+");simple_menu_title_styles();")
			this.simple_menu_settings._title_padding=value;
		},

		doneEdition:function(e){
			var tourSkill = this.model.get("tourSkill");
			tourSkill.simple_menu_settings = this.simple_menu_settings;
			var manageData = new ManageData();
			manageData.editSkill(tourSkill)
			this.removeModal(e);
			this.undelegateEvents();
		}

	});

	return SimpleMenuSkillEditor;
	
});
