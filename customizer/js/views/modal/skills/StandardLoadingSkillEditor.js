define([
	'jquery',
	'underscore',
	'backbone',
	'views/modal/Modal',
	'text!templates/modal/standardLoadingProgress.html',
	'helpers/HelpFunctions',
	'views/modal/SingleUploader',
	'mCustomScrollbar',
	'jqueryui',
	'colorpicker',
    'helpers/ManageData',
    'helpers/ManageTour'
], function($, _, Backbone,Modal,standardLoadingProgress,HelpFunctions,SingleUploader, mCustomScrollbar,jqueryui,colorpicker,ManageData,ManageTour){

	var StandardLoadingProgress = Modal.extend({
		
		initialize: function () {
		_.bindAll(this);        
		 _.extend(this.events, Modal.prototype.events);
		},
		events:{
			"click .skillModal #Context-menu-finish":"doneEdition",
			"click #standard-loading-skill-editor #chooseBarDD li":"toggleDropdown",
			"click #standard-loading-skill-editor .dropdownSelector li":"dropdownSelector"
		},
		
		renderExtend:function(){

			var myid = this.myid;
			var mytourSkill = this.model.get("tourSkill");
			var tourSkill = mytourSkill;
			console.log(tourSkill)
			if(tourSkill.progress._showload=="none"){
				tourSkill.selected = "Looping";
				tourSkill.hiddenBarClass= "none";
				tourSkill.hiddenloopingClass= "";
				s=tourSkill.progress._showwait;
				s= s.split(/[()]/)[1];
				tourSkill.loopingprop = s.split(",");
				tourSkill.barprop = ["center","100","10","0","50","solid","0x000000","0xd3322a","0xd3322a","0x000000","1","0xFFFFFF","5"]
			}else{
				tourSkill.selected = "Bar";
				tourSkill.hiddenBarClass= "";
				tourSkill.hiddenloopingClass= "none";
				s=tourSkill.progress._showload;
				s= s.split(/[()]/)[1];
				tourSkill.loopingprop = ["0xFFFFFF","15","15","0","0","0xFFFFFF","5","0.5","0.5","center"]
				tourSkill.barprop = s.split(",");
			}

			if(tourSkill.barprop[1].slice(-1) == "%"){
				tourSkill.barwidthUnit = "%";
				tourSkill.barprop[1] = tourSkill.barprop[1].replace("%","")
			}else{
				tourSkill.barwidthUnit = "px";
			}

			if(tourSkill.barprop[2].slice(-1) == "%"){
				tourSkill.barheighthUnit = "%";
				tourSkill.barprop[2] = tourSkill.barprop[2].replace("%","")
			
			}else{
				tourSkill.barheighthUnit = "px";
			}

		   /*var allColors =[
				{
					"colorname":"backcolor"
					"colorvalue":tourSkill.barprop[6]
				},
				{
					"colorname":"loadcolor"
					"colorvalue":tourSkill.barprop[7]  
				},
				{
					"colorname":"decodecolor"
					"colorvalue":tourSkill.barprop[8]
				},
				{
					"colorname":"bordercolor"
					"colorvalue":tourSkill.barprop[9]  
				},
				{
					"colorname":"glowcolor"
					"colorvalue":tourSkill.barprop[11]
				},
				{
					"colorname":"looping-color"
					"colorvalue":tourSkill.barprop[tourSkill.loopingprop[0]]  
				},
				{
					"colorname":"looping-glowcolor"
					"colorvalue":tourSkill.barprop[tourSkill.loopingprop[5]]  
				}
		   ]
		   [tourSkill.barprop[6],tourSkill.barprop[7],tourSkill.barprop[8],tourSkill.barprop[9],tourSkill.barprop[11],tourSkill.loopingprop[0],tourSkill.loopingprop[5]] 
		   */
		   var template = _.template(standardLoadingProgress,{tourSkill:tourSkill})

			$("#"+myid+" .inner-modal").html(template);
			$("#"+myid+" header h2").text("Standard Loading Progress Editor")

				_.each($("#standardLoading-bar-skill-align .fa-circle"),function(elem,ind){
					if($(elem).data("pos") == tourSkill.barprop[0]){
						$(elem).addClass("selected");
					}
				})
				_.each($("#standardLoading-looping-skill-align .fa-circle"),function(elem,ind){
					if($(elem).data("pos") == tourSkill.loopingprop[9]){
						$(elem).addClass("selected");
					}
				})

			$("#"+myid).find(".save-and-close").unbind("click");
			
			$(".scrollwrapper").mCustomScrollbar({
				theme:"minimal-dark",
				scrollInertia:300
			});

			var helpFunctions = new HelpFunctions();
			helpFunctions.dropDown("#"+myid+" .dropdown");
			helpFunctions.nineGrillSelector("#"+myid+" .position"); 

			$('#backcolor,#loadcolor,#decodecolor,#bordercolor,#glowcolor,#looping-color,#looping-glowcolor').colorpicker();
			
		},

	
		doneEdition:function(e){
			var myid = this.myid;
			var tourSkill = this.model.get("tourSkill");
			var mytourSkill = tourSkill;

			switch($("#chooseBarDD").data("selected")){

				case "Bar":
					mytourSkill.progress._showwait = "none";
					var origin 		= $("#standardLoading-bar-skill-align .selected").data("pos");
					if($("#unitsW").data("selected") == "%"){
						var width 		= $("#widthSLP").val()+"%";
					}else{
						var width 		= $("#widthSLP").val();
					}
					if($("#unitsH").data("selected") == "%"){
						var height 		= $("#heightSLP").val()+"%";
					}else{
						var height 		= $("#heightSLP").val();
					}
					var style 		= $("#styleSLP").data("selected");
					var backcolor 	= "0x"+$("#backcolor").val();
					var loadcolor 	= "0x"+$("#loadcolor").val();
					var decodecolor	= "0x"+$("#decodecolor").val();
					var bordercolor	= "0x"+$("#bordercolor").val();
					var borderwidth	= $("#borderwidth").val();
					var glowcolor	= "0x"+$("#glowcolor").val();
					var glowwidth	= $("#glowwidth").val();
					mytourSkill.progress._showload = 'bar('+origin+','+width+','+height+',0,50,'+style+','+backcolor+','+loadcolor+','+decodecolor+','+bordercolor+','+borderwidth+','+glowcolor+','+glowwidth+')';
				break;
				case "Looping":
					var color 		= "0x"+$("#looping-color").val();
					var points		= $("#looping-points").val();
					var size		= $("#looping-size").val();
					var bigpoint	= $("#looping-bigpoint").val()
					var smallpoint	= $("#looping-smallpoint").val();
					var glowcolor	= "0x"+$("#looping-glowcolor").val();
					var glowwidth	= $("#looping-glowwidth").val();
					var xpos		= $("#looping-xpos").val();
					var ypos		= $("#looping-ypos").val();
					var align		= $("#standardLoading-looping-skill-align .selected").data("pos");
					console.log(align)
					mytourSkill.progress._showwait = 'loopings('+color+','+points+','+size+','+bigpoint+','+smallpoint+','+glowcolor+','+glowwidth+','+xpos+','+ypos+','+align+')';
					mytourSkill.progress._showload ="none";
				break;
			}

				delete mytourSkill.selected;
				delete tourSkill.hiddenBarClass;
				delete tourSkill.hiddenloopingClass;
				delete tourSkill.loopingprop;
				delete tourSkill.barprop;
				delete tourSkill.barwidthUnit;
				delete tourSkill.barheighthUnit;
			/*
			mytourSkill._url = $("#signature-skill-editor-img").data("imgsrc");
			mytourSkill._x = $("#signature-skill-x").val();
			mytourSkill._y = $("#signature-skill-y").val();
			mytourSkill._zorder = $("#signature-skill-zorder").val();
			mytourSkill._alpha = $("#signature-skill-alpha").val();
			mytourSkill._onclick = "openurl("+$("#signature-skill-linkto").val()+",_blank);";


			if(tourData.krpano.plugin.length == undefined){
				tourData.krpano.plugin = mytourSkill;
			}else{
				_.each(tourData.krpano.plugin,function(elem,ind){
					if(elem._kind == mytourSkill._kind){
						tourData.krpano.plugin[ind] = mytourSkill;
					}
				})
			}*/
			var manageData = new ManageData();
			var manageTour = new ManageTour();
            manageData.editSkill(mytourSkill,manageTour.reloadTour)
           	this.removeModal(e);
			this.undelegateEvents();
		
		},

		toggleDropdown : function(e) {
			var val = $(e.target).attr('data-value');
			$("#chooseBarDD").data("selected",val)
			val = val.toLowerCase();
			var tab = $('.layer-top.' + val);
		
		   if ( $(tab).hasClass('none') ) {
			console.log('none!')
				//$(tab).toggleClass('none')
				$('.layer-top').toggleClass('none')
		   } else {
			console.log('notnone!')
				//$('.layer-top').toggleClass('none')
		   }
		},

		dropdownSelector:function(e){
			var val = $(e.target).attr('data-value');
			$(e.target).parents(".dropdownSelector").data("selected",val)
		}


	});

	return StandardLoadingProgress;
	
});
