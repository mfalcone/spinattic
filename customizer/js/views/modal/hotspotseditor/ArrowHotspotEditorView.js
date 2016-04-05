define([
	'jquery',
	'underscore',
	'backbone',
	'views/modal/Modal',
	'text!templates/modal/hotspotarrow.html',
	'helpers/HelpFunctions',
	'views/modal/HotSpotsDropDown',
	'helpers/ManageData',
	'views/modal/HotSpotToolTip',
	'models/main/ModalModel',
	'views/modal/ConfirmView'
	

], function($, _, Backbone,Modal,hotspotarrow,HelpFunctions,HotSpotsDropDown,ManageData,HotSpotToolTip,ModalModel,ConfirmView){

	var ArrowHotspotEditorView = Modal.extend({
		
		initialize: function () {
		_.bindAll(this);        
		 _.extend(this.events, Modal.prototype.events);
		},
		events:{
			
				 },
		
		renderExtend:function(){
			var manageData = new ManageData();
			var num = this.myid.replace("spot","");
			$("#"+this.myid).addClass("arrow-hotspot");
			$("#"+this.myid+" header h2").text("Arrow Hotspot. ID "+num+":")
			var este = this;
			var allData = this.model.get("allData");
			var me = this;




			this.scenes = [];
			 _.each(tourData.krpano.scene,function(elem,ind){
					if(elem._name != $("#tour").data("scene")._name){
						este.scenes.push(elem)
					}
				})  

			 var selectedScene = {}
			 if(allData._linkedscene != ""){
				_.each(este.scenes,function(elem,ind){
					if(elem._name == allData._linkedscene){
						selectedScene.name = elem._name;
						selectedScene.title = elem._title;
						selectedScene.filename = elem._scene_filename;
						selectedScene.thumb = elem._thumburl;
					}
				})
			}else{
				 selectedScene.name = "";
				 selectedScene.title = "none";
				 selectedScene.filename = "";
				 selectedScene.thumb = "";

			}
			var compiledTemplate = _.template(hotspotarrow,{num:num,allData:allData,selectedScene:selectedScene,scenes:este.scenes,num:num})
			$("#"+this.myid+" .inner-modal").html(compiledTemplate);
			if(selectedScene.name!=""){
				$("#"+this.myid+" .inner-modal #"+selectedScene.name).addClass("mobi-selected");
			}
			$("#"+me.myid).find(".fa-close").remove();
			$("#"+me.myid+" header .save-and-close").unbind("click")
			 var $me = $("#"+me.myid);
			$("#"+me.myid+" header .save-and-close").click(function(){
				
				var selectedscene = $("#"+me.myid+" h3.dropdown1").data("name");
				
				function cback(){
					if($("#confirmDel").size()){
						$("#confirmDel .fa-close").trigger("click");
					}
					var hotspot = allData;
					var tooltiptype = $me.find(".tooltip-controller").data("tooltype")
					var tooltip = $me.find(".tooltip-controller .custom-tooltype").val();

					hotspot._tooltiptype = tooltiptype;
					hotspot._tooltip = tooltip;
					hotspot._rotate = $("#"+me.myid+" .rotate").val()
					hotspot._linkedscene = selectedscene;
					manageData.changeDataInHotSpot($("#tour").data("scene")._name, hotspot)
					
					var krpano = document.getElementById("krpanoSWFObject");
					$.each(hotspot,function(i,val){
							if(i!="_name"){
								var prop = i.replace("_","");
								krpano.set("hotspot["+hotspot._name+"]."+prop,val)  
							}
						})
					$me.fadeOut(function(){
						me.removeThis();
					})
				}
				var msg = "You are saving a hotspot with no content. It will be saved in draft but not updated publish or update the tour."
				if(selectedscene == ""){
				   var modalModel = new ModalModel({msg:msg,evt:cback})
					var alertView = new ConfirmView({model:modalModel});
					alertView.render("confirmDel",alertView.renderExtend);
				}else{
					cback();
				}
			})
			
		   
			var oldWidth = $me.width();

			var spotName =  this.myid;
				$me.find(".removeHotspot").click(function(){
					var krpano = document.getElementById("krpanoSWFObject");
					krpano.call("removehotspot("+spotName+")");
					manageData.removeHotSpot($("#tour").data("scene")._name, spotName);
					$me.fadeOut(function(){
						me.removeThis();
					})
				})

				$me.find('.rotate').change( function() {
					var val = $(this).val();
					var hpdata = $("#"+me.myid).data("spotdata")._name;
					me.limitRotate(val, hpdata, $("#"+me.myid));
				})

				$me.find("#onoffswitchhparrow-"+num).click(function(){
					if($(this).is(":checked")){
						var hpdata = $me.data("spotdata");
						var selectedscene = $("#"+me.myid+" h3.dropdown1").data("name");
						var krpano = document.getElementById("krpanoSWFObject");
						krpano.call("addhotspot("+hpdata._name +")");
						window.selectScene = function(selectedscene){
							setTimeout(function(){
								$("#"+selectedscene+" img").trigger("click")
							},500)
						}

						krpano.call('set(hotspot['+hpdata._name+'].ondown, null );');
						var styleName = $("#"+me.myid).data("spotdata")._style;
						styleName = styleName.split("|")[0];
						var clickfunctions;
						_.each(tourData.krpano.style,function(elem,ind){
							if(elem._name==styleName){
								clickfunctions = elem._onclick;
							}
						})

						clickfunctions = clickfunctions.split(";");
						_.each(clickfunctions,function(elem,ind){
							elem = elem.replace(/ /g,'')
							if(elem == "loadscene(get(linkedscene),null,MERGE,BLEND(1))"){
								clickfunctions[ind] = 'js(selectScene('+selectedscene+'))';
							}
						})
						clickfunctions=clickfunctions.join(";")
						clickfunctions = clickfunctions+";"
						
						krpano.call('set(hotspot['+hpdata._name+'].onclick,  '+clickfunctions+' );');
						$me.find(".hotspotarrow").delay(200).slideUp(function(){
						   $me.find(".test-mode").fadeIn(); 
						});
						$me.find(".removeHotspot").fadeOut();
						$me.delay(200).animate({
							width:'330px'
						})

					}else{
						var hpdata = $me.data("spotdata");
						var krpano = document.getElementById("krpanoSWFObject");
						krpano.call("addhotspot("+hpdata._name +")");
						krpano.call('set(hotspot['+hpdata._name+'].ondown, draghotspot() );');
						krpano.call('set(hotspot['+hpdata._name+'].onclick, js(openHotspotWindowEditor('+hpdata._name+')) );');
						$me.find(".save-and-close").show();
						$me.find(".hotspotarrow").delay(200).slideDown();
						$me.find(".removeHotspot").fadeIn();                    
						$me.find(".test-mode").fadeOut(); 

						$me.delay(200).animate({
							width:oldWidth+'px'
						})
					}
				})


			var selectedset = this.model.get("selectedSet");
			var myid = this.myid;


			var HotSpotDDModel = Backbone.Model.extend({});
			hotSpotDDModel = new HotSpotDDModel({selectedset:selectedset,kind:"arrow",elemid:myid});
			var hotSpotsDropDown = new HotSpotsDropDown({model:hotSpotDDModel})
			hotSpotsDropDown.render();
			this.autocomplete();


			$("#"+me.myid+" .scrollwrapper-scenes").mCustomScrollbar({
				theme:"minimal-dark",
				scrollInertia:300
			});

			$("#"+me.myid+" .dropdown1").click(function (){
				$("#"+me.myid+" .list-scene").toggleClass('none');
			})

			var ToolTipModel = Backbone.Model.extend({});
			var toolTipModel = new ToolTipModel({allData:allData,num:num});
			var hotSpotToolTip = new HotSpotToolTip({model:toolTipModel});
			hotSpotToolTip.render();

		},

		removeThis:function(){
			this.undelegateEvents();
			$("#"+this.myid).parent(".overlay").remove();
		},

		autocomplete: function(){
			var noselect = true;
			var myid = this.myid;
			var num = this.myid.replace("spot","");
			var myscenes = this.scenes;

			_.each(myscenes,function(elem,ind){
				elem.label = elem._title;
			}) 

			if (myscenes.length) {    
				$("#"+myid+" .search-scenes").autocomplete({
					source:myscenes,
					 appendTo:"#scenes-search-results-"+num,
					 focus: function( event, ui ) {
						$("#"+myid+" .search-scenes").val( ui.item._title );
						return false;
						}, 
					select:function(event,ui){
						var myTitle = ui.item._title;
						$("#"+myid+" .search-scenes").val(myTitle);
						/*$("#"+myid+" .inner-modal h2").show();*/
						 noselect = false;
						$("#"+myid+" .scenes-list #"+ui.item._name).show();
						
						return false;
					},
					search:function(event,ui){
					 $("#"+myid+" .scenes-list li").hide();
					},
					close: function(event,ui){
						 if(noselect){
							$("#"+myid+" .scenes-list li").show();
						 }
					}
				}).data("ui-autocomplete")._renderItem = function(ul,item){

					return $( "<li></li>" )
					.data( "item.autocomplete", item )
					.append( "<img src="+item._thumburl+" /><dl><dt>" + item._title + "</dt><dd>" + item._scene_filename + "</dd></dl>" )
					.appendTo( ul );
				};


				/* select */

				$("#"+myid+" .scenes-list li").click(function(e){
					noselect = true;
					$("#"+myid+" .scenes-list li").show();
					$("#"+myid+" .search-scenes").val("");
					var name = $(this).attr("id");
					var panotitle = $(this).find('dt').text();
					var panoName = $(this).find('dd').text();

					var imgsrc =  $(this).find("img").attr("src");
					 $("#"+myid+" .scenes-list li").removeClass("mobi-selected")
					$(this).addClass("mobi-selected")
					$("#"+myid+" h3 .selectedScene").text(panotitle);
					$("#"+myid+" h3 .selectedFile").text(panoName);
					$("#"+myid+" h3").data("name",name);
					$("#"+myid+" .list-scene").toggleClass('none');
					$("#"+myid+" h3.scenedd img").attr("src",imgsrc);

					if($("body").data("device")){
						 $("#"+myid+" header .save-and-close").trigger("click");
					}
				})

				$("#"+myid+" .scenelist .clear-btn").click(function(ev) {                
					$(ev.target).find('input').val('');
					$("#"+myid+" .scenes-list li").show()
				})
			}

		},

		limitRotate: function (val, spotName, modalName) {
			var inputRotate = $(modalName).find('.rotate');
			if ( !isNaN( val ) ) {
				if ( val < -180 ) {
					$(inputRotate).val(-180);
					val = -180;

				} else if ( val > 180) {
					$(inputRotate).val(180);
					val = 180;
				}
			} else {
				return
			}
			
			var krpano = document.getElementById("krpanoSWFObject");
			krpano.set("hotspot["+ spotName +"].rotate",val);

		}

		
	});

	return ArrowHotspotEditorView;
	
});
