define([
	'jquery',
	'underscore',
	'backbone',
	'jqueryui',
	'x2js',
	'views/modal/Modal',
	'text!templates/modal/hotSpotStyleEditor.html',
	'text!templates/modal/hotSpotStyleEditorEditor.html',
	'views/modal/SingleUploader',
	'helpers/ManageData',
	'helpers/ManageTour',
	'helpers/HelpFunctions',


	], function($, _, Backbone,jqueryui,x2js,Modal,hotSpotStyleEditor,hotSpotStyleEditorEditor,SingleUploader,ManageData,ManageTour,HelpFunctions){

	var HotSpotStyleEditor = Modal.extend({

		initialize: function () {
			_.bindAll(this);        
			_.extend(this.events, Modal.prototype.events);

		},

		events:{
			/*"click .select-pano": "selectPano",*/
			"click #hotspotStyleEditor .menuTabs .modal-bt": "tabs",
			"click .tabContent .add .addStyle": "addStyles",
			"click .tabContent .icons .addStyle": "selectAddStyles",
			"click .cancel": "removeModal",
			"change .absoluteRight":"setProperties",
			"click #hotspotStyleEditor .delete-set":"removeSet",
			//"click #scale-check-hotspot-editor span":"scale",
			"click #hotspotStyleEditor .fa-trash":"removeHP"
		},

		defaultHPValues:{
			width:32,
			height:32,
			X:0,
			Y:0
		},

		renderExtend: function() {
			var este = this;
			var myid = this.myid;
			var template = _.template(hotSpotStyleEditor);
			this.templateEditor = _.template(hotSpotStyleEditorEditor);

			$("#"+myid+" .inner-modal").html(template);
			$("#"+myid+" header h2").text("Styles of hotspots set:");


			var imgsrc = this.model.get("imgsrc")
			var family = this.model.get("family")
			var name = this.model.get("name");
			var styles = [];
			if(family){
				$("#"+myid+" .inner-modal .hotspotStyleContent").html(this.templateEditor);

				_.each(tourData.krpano.style,function(el,index){
					var elemname = el._name;
					var elemray = elemname.split("_");

					if(family == elemray[1]){
						styles.push(el);
					}
				})
				_.each(styles,function(elem,ind){
					var properties = {}
					var upcrop = elem._crop.split("|")
					var up = {
						X:upcrop[0],
						Y:upcrop[1],
						width:upcrop[2],
						height:upcrop[3],
					}

					properties.up = up;

					if(elem._ondowncrop){
						var hovercrop = elem._onovercrop.split("|")
						var over = {
							X:hovercrop[0],
							Y:hovercrop[1],
							width:hovercrop[2],
							height:hovercrop[3],
						}
						properties.over = over;
					}

					if(elem._onovercrop){
						var downcrop = elem._ondowncrop.split("|")
						var down = {
							X:downcrop[0],
							Y:downcrop[1],
							width:downcrop[2],
							height:downcrop[3],
						}
						properties.down = down;
					}



					if(elem._scale == "0.5"){
					   // $("#scale-check-hotspot-editor span").data("checked",true)
					   // $("#scale-check-hotspot-editor span").removeClass("fa-square").addClass("fa-check-square");
						$("#hotspotStyleEditor .icons .addStyle").addClass("scaled");
					}               

					$("#"+elem._kind+"Tab").data("properties",properties);
					$("#"+elem._kind+"Tab").find(".icons").removeClass("none");
					for(prop in properties){
						if(properties[prop].X != "0"){
							properties[prop].X = properties[prop].X
						}

						if(properties[prop].Y != "0"){
							properties[prop].Y = properties[prop].Y
						}

						$("#"+elem._kind+"Tab .icons ."+prop+" .addStyle").css({
							"background-image":"url("+imgsrc+")",
							"background-repeat":"no-repeat",
							"background-position": "-"+properties[prop].X +"px -"+properties[prop].Y+"px",
							"width":properties.up.width,
							"height":properties.up.height
						}).addClass("not-empty").find(".fa-plus").remove();

					}
					$("#"+elem._kind+"Tab .icons .up .addStyle").addClass("selected")
					$("#"+elem._kind+"Tab .icons .up .controls").removeClass("none")
					$("#"+elem._kind+"Tab").find(".add").addClass("none");
				})

				$("#hotspotStyleEditor .menuTabs li."+name+"Tab").trigger("click")

				var helpFunctions = new HelpFunctions();
				//helpFunctions.checkbox("#scale-check-hotspot-editor","fa-check-square","fa-square");
				$("#hotspotStyleEditor .delete-set").show();
				$("#Context-menu-finish").show();
			} //end if


			tour_id = location.hash.split("/")[1];
			var caso = 'hotspot_styles';
			var SingleUploaderModel = Backbone.Model.extend({});
			var singleUploaderModel = new SingleUploaderModel({myid:"graphic-hotspot",imgsrc:imgsrc,tour_id:tour_id,caso:caso,textMessage:"Upload graphics twice the normal size for a correct display on mobile devices. The graphics will automatically be scaled on computers and tablets."})
			var singleUploader = new SingleUploader({model:singleUploaderModel});
			var uploadComplete = this.uploadComplete;
			singleUploader.render(uploadComplete);

			if(imgsrc){
				$("#hotspotStyleEditor #graphic-hotspot").addClass("has-changed");
				$("#hotspotStyleEditor .image-uploader-wrapper").addClass("withimage");
				$("#hotspotStyleEditor .image-uploader-wrapper").mCustomScrollbar({
					theme:"minimal-dark",
					scrollInertia:300,
					axis:"yx"
				});
			}

			este.verticalCent();

			$("#hotspotStyleEditor .save-and-close").unbind("click");
			este.events = este.events || {};
			var saveandclose = 'click #hotspotStyleEditor .save-and-close';
			este.events[saveandclose] = 'saveAndClose';
			este.delegateEvents(); 

			$(".scrollwrapper").mCustomScrollbar({
				theme:"minimal-dark",
				scrollInertia:300
			});

		},

tabs: function (e) {
	var el = $(e.target),
	id = $(el).attr('data-content');
	var valueText = $(el).text();    
	if(!$(el).hasClass('selected')) {
		$(el).addClass('selected').siblings('li').removeClass('selected');
		$('.hotsPotStyleTabs #' + id).removeClass('none').siblings().addClass('none');
	}
	$('.tabContent .tab').removeClass("selected")
	$('.tabContent #' + id).addClass("selected")
	var properties = $(".tab.selected").data("properties");
	var mytype =  $(".tab.selected .selected").data("type");

	if(properties){

		$(".hotsPotStyleSelected .width").val(properties[mytype].width);
		$(".hotsPotStyleSelected .height").val(properties[mytype].height);
		$(".hotsPotStyleSelected .X").val(properties[mytype].X);
		$(".hotsPotStyleSelected .Y").val(properties[mytype].Y);
		$("#bt-selected").text(mytype)


	}else{

		$(".hotsPotStyleSelected .width").val("")
		$(".hotsPotStyleSelected .height").val("")
		$(".hotsPotStyleSelected .X").val("")
		$(".hotsPotStyleSelected .Y").val("")
		$("#bt-selected").text("")

	}


	$("#hp-type-selected").text(valueText)

},

addStyles: function(e) {
	var el = $(e.target);
	var $thistab = $(el).parents(".tab");
	$(el).parents('.add').addClass('none').siblings('.icons').removeClass('none');

	dfval = this.defaultHPValues;
	$(".hotsPotStyleSelected .width").val(dfval.width)
	$(".hotsPotStyleSelected .height").val(dfval.height)
	$(".hotsPotStyleSelected .X").val(dfval.X)
	$(".hotsPotStyleSelected .Y").val(dfval.Y)
	$thistab.find(".icons .addStyle:eq(0)").trigger("click")

},

selectAddStyles: function(e){
	if($(e.target).prop("tagName") == "SPAN"){
		var el = $(e.target).parent()
	}else{
		var el = $(e.target);
	}
	var dfval = this.defaultHPValues;

	if(!$("#Context-menu-finish").is(":visible")){
		$("#Context-menu-finish").show();
	}    
	if(!$(el).hasClass("not-empty")){
		$(el).find(".fa-plus").remove();
		$(el).css({
			"width":dfval.width+"px",
			"height":dfval.height+"px",
			"background-image":"url("+$("#graphic-hotspot").data("imgsrc")+")",
			"background-repeat":"no-repeat",
			"background-position": dfval.X +"px "+ dfval.Y+"px"
		}).addClass("not-empty");
		if($(".tab.selected").data("properties")){
			prop = $(".tab.selected").data("properties");
		}else{
			var prop = {};
		}
		prop[$(el).data("type")]=dfval;
		$(".tab.selected").data("properties",prop)
	}

	if(!$(el).hasClass('selected')) {
		$(el).addClass('selected').parents('.icons-wrapper').siblings().find('.addStyle').removeClass('selected');
	}

	var properties = $(".tab.selected").data("properties");
	var mytype =  $(".tab.selected .selected").data("type");

	if(properties){

		$(".hotsPotStyleSelected .width").val(properties[mytype].width)
		$(".hotsPotStyleSelected .height").val(properties[mytype].height)
		$(".hotsPotStyleSelected .X").val(properties[mytype].X)
		$(".hotsPotStyleSelected .Y").val(properties[mytype].Y)
	}

	var valText = $(el).data("type");
	$("#bt-selected").text(valText);
	$(".tab.selected .controls").addClass("none");
	$(el).parents('.icons-wrapper').find(".controls").removeClass("none");

},

uploadComplete:function(){
	var este = this;
	var myid = this.myid;
	$("#"+myid+" .inner-modal .hotspotStyleContent").html(this.templateEditor);
	$("#hotspotStyleEditor .image-uploader-wrapper").addClass("withimage");
	$("#hotspotStyleEditor .image-uploader-wrapper").mCustomScrollbar({
		theme:"minimal-dark",
		scrollInertia:300,
		axis:"yx"
	});
	$(".image-uploader-wrapper img").load(function(){
		este.verticalCent();
	})
	var helpFunctions = new HelpFunctions();
	//helpFunctions.checkbox("#scale-check-hotspot-editor","fa-check-square","fa-square");
	$("#hotspotStyleEditor #graphic-hotspot").addClass("has-changed");
	$("#hotspotStyleEditor .icons .addStyle").addClass("scaled")

	if(!$("#Context-menu-finish").is(":hidden")){
		$("#Context-menu-finish").hide();
	}

	$(".hotsPotStyleSelected .data-group input.width").change(function (ev) {
		helpFunctions.limitInputs(ev.target, 0, 115);
	});


},

setProperties:function(e){
	var myprop = {
		width:$(".hotsPotStyleSelected .width").val(),
		height:$(".hotsPotStyleSelected .height").val(),
		X:$(".hotsPotStyleSelected .X").val(),
		Y:$(".hotsPotStyleSelected .Y").val()
	}

	$(".tab.selected .selected").css({
		"width": $(".hotsPotStyleSelected .width").val()+"px",
		"height": $(".hotsPotStyleSelected .height").val()+"px",
		"background-position":"-"+$(".hotsPotStyleSelected .X").val() +"px -"+ $(".hotsPotStyleSelected .Y").val()+"px" 
	})
	var mytype =  $(".tab.selected .selected").data("type");
	var prop = $(".tab.selected").data("properties")
	prop[mytype] = myprop;
},

removeSet:function(){
	var este = this;
	var manageData = new ManageData();
	var family = this.model.get("family")
	if(family){
		manageData.removeStyle(family);
		_.each($("#hotspot-styles .selector"),function(el,ind){
			if($(el).data("family") == family){
				$(el).remove()
			}
		})
		_.each($("#hotspot-styles .rowinrow"),function(el,ind){
			if($(el).data("family") == family){
				$(el).parents(".row").remove()
			}
		})

		_.each($("#hotspot-styles .del-row"),function(el,ind){
			if($(el).data("family") == family){
				$(el).remove()
			}
		})

	}
	$("#hotspotStyleEditor").parents(".overlay").fadeOut(function(){

		este.undelegateEvents();
		$(this).remove();

	});
},
/*
scale:function(e){

	$(e.target).data("checked",$(e.target).hasClass("fa fa-check-square"))
	if($(e.target).data("checked")){
		$("#hotspotStyleEditor .icons .addStyle").addClass("scaled")
	}else{
		$("#hotspotStyleEditor .icons .addStyle").removeClass("scaled")
	}
},
*/
removeHP:function(e){
	$addStyle = $(e.target).parents(".icons-wrapper").find(".addStyle")
	if($addStyle.data("type") == "up"){
		$(".tab.selected .addStyle").removeClass("not-empty").removeAttr("style").html('<span class="fa fa-plus"></span>')
		$(".tab.selected").removeData("properties")
		$(".tab.selected .icons-wrapper").removeClass("none");  
		$(".tab.selected .icons").addClass("none"); 
		$(".hotsPotStyleSelected .width").val("")
		$(".hotsPotStyleSelected .height").val("")
		$(".hotsPotStyleSelected .X").val("")
		$(".hotsPotStyleSelected .Y").val("")

		var total = 0; 
		_.each($("#hotspotStyleEditor .tab"),function(elem, ind){
			if($(elem).data("properties")){
				total++
			}
		})
		if(total == 0){
			$("#Context-menu-finish").hide();
		}

	}else{

		$addStyle.removeClass("not-empty");
		$addStyle.removeAttr("style");
		$addStyle.html('<span class="fa fa-plus"></span>');
		$(".tab.selected .addStyle:eq(0)").trigger("click");
		delete $(".tab.selected").data("properties")[$addStyle.data("type")];
	}
},

saveAndClose:function(e){
	var este = this;
	var family = $("#hotspot-styles .row:last-child .rowinrow").data("family");
	var familydata = this.model.get("family")
	var integer = family.replace("set","");
	if(!familydata){
		integer = parseInt(integer)+1;
	}
	var total = 0;

	var elemToappend = '<div class="selector" data-family="set'+integer+'"><span class="fa fa-circle fa-lg"></span></div>';
	elemToappend += '<div class="del-row" data-family="set'+integer+'"><span class="fa fa-trash-o"></span></div>';

	var manageData = new ManageData()
	var ableToAppend = false;

	_.each($("#hotspotStyleEditor .tab"),function(elem, ind){

		if($(elem).data("properties")){

			total++
			var properties = $(elem).data("properties");
			var jstring = JSON.stringify(properties)
			var jstring = jstring.replace(/-/g , "")
			properties = JSON.parse(jstring);
			var valx;
			var valy;
			var width;
			var height;
			if(properties.up.X != 0){
				valx = "-"+properties.up.X
			} else{
				valx = properties.up.X
			}
			if(properties.up.Y != 0){
				valy = "-"+properties.up.Y
			}else{
				valy = properties.up.Y
			}
			//if($("#scale-check-hotspot-editor span").data("checked")){
				var scaled = "scaled"
			/*}else{
				var scaled = ""
			}*/
			width = properties.up.width;
			height = properties.up.height;
			elemToappend += '<div class="row"><div class="rowinrow custom '+scaled+'" data-name="'+$(elem).data("name")+'" data-url="'+$("#graphic-hotspot").data("imgsrc")+'" data-family="set'+integer+'" style="background-image:url('+$("#graphic-hotspot").data("imgsrc")+');background-position: '+valx+'px '+valy+'px;width:'+width+'px;height:'+height+'px"></div></div>';
			if(!familydata){
				ableToAppend = true;
			}
		}

	})

if(!ableToAppend){
	_.each($("#hotspot-styles .selector"),function(el,ind){
		if($(el).data("family") == "set"+integer){
			$(el).remove()
		}
	})
	_.each($("#hotspot-styles .rowinrow"),function(el,ind){
		if($(el).data("family") == "set"+integer){
			$(el).parents(".row").remove()
		}
	})

	_.each($("#hotspot-styles .del-row"),function(el,ind){
		if($(el).data("family") == family){
			$(el).remove()
		}
	})
}

$("#hotspot-styles .rows").append(elemToappend);
var helpFunctions = new HelpFunctions();
helpFunctions.selectChoice("#hotspot-styles .selector","fa-circle-o","fa-circle");
totalsaved = 0
_.each($("#hotspotStyleEditor .tab"),function(elem, ind){   

	if($(elem).data("properties")){

		var template_id = $(elem).data("id");
		$(e.target).text("saving...")
		$.ajax({
			url:"data/xml.php?t=htspts_styles&c=1&id="+template_id,
			dataType: "html",    
			success:function(data){
				var x2js = new X2JS({attributePrefix:"_"});
				var style =  x2js.xml_str2json( data );
				style = style.style;
				var properties = $(elem).data("properties");
				var jstring = JSON.stringify(properties)
				jstring = jstring.replace(/-/g , "")
				properties = JSON.parse(jstring);
				style._crop = properties.up.X+"|"+properties.up.Y+"|"+properties.up.width+"|"+properties.up.height;
				style._name = "hotspot_set"+integer+"_"+$(elem).data("name");
				if(properties.down){
					style._ondowncrop = properties.down.X+"|"+properties.down.Y+"|"+properties.down.width+"|"+properties.down.height;
				}else{
					delete style._ondowncrop;
				}
				if(properties.over){
					style._onovercrop = properties.over.X+"|"+properties.over.Y+"|"+properties.over.width+"|"+properties.over.height;
				}else{
					delete style._onovercrop;
				}

				//if(!$("#scale-check-hotspot-editor span").data("checked")){
					style._scale = "0.5"
				//}

				style._url = $("#graphic-hotspot").data("imgsrc");
				manageData.pushStyle(style)
				totalsaved++
				if(totalsaved == total){
					var manageTour = new ManageTour();
					manageData.saveServer(manageTour.reloadTour);
					$("#hotspotStyleEditor").parents(".overlay").fadeOut(function(){

						este.undelegateEvents();
						$(this).remove();

					});
				}

			}//end of success
		})//end of ajax
	}//end if

})//end each


}

});

return HotSpotStyleEditor;

});
