define([
	'jquery',
	'underscore',
	'backbone',
	'jqueryui',
	'views/modal/Modal',
	'text!templates/modal/addFromPanosManager.html',
	'helpers/HelpFunctions',
	'views/footer/SceneMenuView',
	'collections/footer/SceneCollection',
	'helpers/ManageTour'
   
], function($, _, Backbone,jqueryui,Modal,addFromPanosManager, HelpFunctions,SceneMenuView,SceneCollection,ManageTour){

	var AddFromPanosManager = Modal.extend({
		
		initialize: function () {
			_.bindAll(this);        
			_.extend(this.events, Modal.prototype.events);
			
		},

		events:{            
			"click #panoManager .remove": "removePano",
			"click #panoManager .cancel": "removeModal",
			"click #panoManager .save": "AddPanosToTour"
		},
		panosorderby:"filename",
		panosorder:"asc",
		panoscount:0,
		renderExtend: function() {
			var este = this;
			var myid = this.myid;
			var template = _.template(addFromPanosManager);

			$("#"+myid+" .inner-modal").html(template);
			$("#"+myid+" header h2").text("Add panos to this tour from Pano files manager:");
			
			este.verticalCent();

			$.ajax({
				url:"data/json.php?t=panos",
				dataType:"json",
				success: function( data ){
					
					
					_.each( data, function ( ely, i ){
						ely.label = ely.fileName;
					})
					este.data = data;
					$("#"+myid+" .pano-list ul").html("");
					este.sortByKey("filename","verse",0)
					//este.data = data;
					este.verticalCent();
					este.autocomplete( myid );

					$("#"+myid+" .sort").click(function(){

						if(!$(this).hasClass("selected")){

							if($(this).hasClass("fa-sort-alpha-asc")){
								
								$(".scrollwrapper").mCustomScrollbar("scrollTo",0)
									setTimeout(function(){
											$("#"+myid+" .pano-list ul").html("");
											este.panoscount = 0;
											este.sortByKey("filename","asc",0);
										},500)
									
								este.panosorderby = "filename";
								este.panosorder = "asc";
							
							}else if($(this).hasClass("fa-sort-alpha-desc")){
								
								$(".scrollwrapper").mCustomScrollbar("scrollTo",0)
									setTimeout(function(){
											$("#"+myid+" .pano-list ul").html("");
											este.panoscount = 0;
											este.sortByKey("filename","desc",0)
										},500)
								este.panosorderby = "filename";
								este.panosorder = "desc";

							}else if($(this).hasClass("fa-sort-amount-asc")){
								$(".scrollwrapper").mCustomScrollbar("scrollTo",0)
									setTimeout(function(){
											$("#"+myid+" .pano-list ul").html("");
											este.panoscount = 0;
											este.sortByDate("asc",0);
										
									},500)
								
								este.panosorderby = "date";
								este.panosorder = "asc";
							
							}else if($(this).hasClass("fa-sort-amount-desc")){
								$(".scrollwrapper").mCustomScrollbar("scrollTo",0)
									setTimeout(function(){
											$("#"+myid+" .pano-list ul").html("");
											este.panoscount = 0;
											este.sortByDate("desc",0);
										},500)
								
								este.panosorderby = "date";
								este.panosorder = "desc";
							}

							$("#"+myid+" .sort-btn").removeClass("selected");
							$(this).addClass("selected")
						}
					});
				}
			})

			$("#" + myid + " .clear-btn").click(this.clearSearch)
			$("#"+myid+" .cancel").click(this.removeModal);            
			
			$(".scrollwrapper").mCustomScrollbar({
				theme:"minimal-dark",
				scrollInertia:300,
				callbacks:{
					onTotalScroll:function(){
						console.log("se dispara el scroll");
						este.panoscount = este.panoscount+10;
						switch(este.panosorderby){
							case "filename":
							este.sortByKey("filename",este.panosorder,este.panoscount);
							break;
							case "date":
							este.sortByDate("date",este.panoscount);
							break;
						}
					}
				}
			});
		},

		selectPano: function(e) {
			var el = e.target,
				panoImg;
			if (!$(el).hasClass('selected')) {

				$(el).addClass('selected yellow').removeClass('blue').text('selected');

				panoImg = '<li data-id="'+$(el).parents("li").attr("id")+'"><span class="tooltipHtml"></span><span class="remove fa fa-close"></span></li>';
				$(panoImg).prepend($(el).siblings('img').clone().addClass('htmlTt')).find('.tooltipHtml').append($(el).siblings('.pano-data').clone()).end().prependTo(".pano-manager .selected-panos");
			}            
		},

		removePano: function(e) {
			var el = e.target,
				id = $(el).siblings('img').attr('data-id');

			$(el).parent().remove();
			$('#tooltip').hide();
			$('.pano-list li').find('a[data-id="' + id + '"]').removeClass('yellow selected').addClass('blue').text('select');

		},

		autocomplete:function(myid){

    		var data = this.data;
    		$("#pano-search").autocomplete({
				source: data,
				appendTo:"#pano-search-results",

				focus: function( event, ui ) {
					$( "#pano-search" ).val( ui.item.fileName );
					return false;
					}, 
				select:function(event,ui){
					var myTitle = ui.item.fileName,
						panoId = ui.item.pano_id;
					$("#pano-search").val(myTitle);
					$("#"+myid+" .pano-list-ul li").hide()
					
					$("#"+myid+" .pano-list-ul #" + panoId).show();
					return false;
				},
				search:function(event,ui){
					console.log("search"+data)
				/*  $("#"+myid+" .skill-list li").hide();
					$("#"+myid+" .inner-modal h2").hide();*/
				},
				change: function(event,ui){
					console.log(data)
					if(!ui.item){
						$( "#pano-search" ).val('');
						//$("#"+myid+" .skill-list li").show()
						//$("#"+myid+" .inner-modal h2").show();
					}                               
				},
				open:function(){
					$("#pano-search-results ul").mCustomScrollbar({
						theme:"minimal-dark",
						scrollInertia:300,
						});
				},
				response:function(){
					$("#pano-search-results ul").mCustomScrollbar("destroy")
				},
				close:function(){
					$("#pano-search-results ul").mCustomScrollbar("destroy")
					}
			}).data("ui-autocomplete")._renderItem = function(ul,item){
				console.log(item)
				return $( "<li></li>" )
				.data( "item.autocomplete", item )
				.append( "<img src="+item.img+" /><dl><dt>" + item.fileName + "</dt><dd>" + item.uploadDate + "</dd></dl>" )
				.appendTo( ul ); 

			};   
		},

		sortByKey:function(key,ascdes,n) {
			var data = this.data;

			var compare = function(el1, el2, index) {
				return el1[index] == el2[index] ? 0 : (el1[index] < el2[index] ? -1 : 1);
			}

			var panosOrder = data.sort(function(el1,el2){
			  return compare(el1, el2, "fileName")
			});

			if(ascdes == "desc"){
				panosOrder = panosOrder.reverse();
			}
			this.data = panosOrder;

			this.fullfillList(n)
		},

		sortByDate:function(ascdes,n){
			var data = this.data;
			function comp(a, b) {
				return new Date(a.uploadDate).getTime() - new Date(b.uploadDate).getTime();
			}
			var panosOrder = data.sort(comp);
			if(ascdes == "desc"){
				panosOrder = panosOrder.reverse();
			}
			this.data = panosOrder;
			this.fullfillList(n)
		},

		fullfillList:function(n){
			var midata =  this.data;
			var este = this;
			var myid = this.myid;
			
			

			midata = midata.slice(n,n+10)
			
			var este = this;
			_.each( midata, function ( el, i ){
				var panoId = el.pano_id;
				var added = $("#"+myid+" .selected-panos li");
				var isThere = "blue";
				var selectText = "select";
				_.each(added,function(elem,ind){
			
					if(panoId == $(elem).data("id")){
						isThere = " selected yellow ";
						selectText = "selected"
					}
				}) 
				
				var fileName = '<div class="pano-entry">File Name: <span>' + el.fileName + '</span></div>',
					res = el.resolution,
					resolution,
					date = '<div class="pano-date">Upload date: ' + el.uploadDate + '</div>',
					pano;

				if(res === 'Multiresolution'){
					resolution = '<div class="pano-entry">' + res + '</div>'
				} else {
					resolution = '<div class="pano-entry">Tile size: <span>' + res + '</span></div>'
				};

				var panoData = '<div class="pano-data">' + fileName + resolution + date + '</div>',
					img = '<img data-id="' + i + '" class="pano-img" src="' + el.img + '" alt="Pano Name">'

				pano = '<li id="' + panoId + '">' + img + panoData + '<a data-id="' + i + '" class="modal-bt select-pano '+ isThere +'">'+selectText+'</a></li>';

				$("#"+myid+" .pano-list ul").append(pano);
				el.label = el.fileName;
			});
			console.log(this.data)
			$('.select-pano', "#" + myid).click(function(e){
						console.log("a")
						este.selectPano(e);
						var helpFunctions = new HelpFunctions();
						helpFunctions.toolTipHtml(".selected-panos li", "panoManager", "html");
					});
		},
		clearSearch: function (ev) {                
			$(ev.target).siblings('input').val('');
			$("#"+this.myid+" .pano-list-ul li").show()
		},

		AddPanosToTour:function(e){
			var tourId = location.hash.split("/")[1];
			var total = $("#panoManager .selected-panos li").size();
			var counter = 0;
			var este = this;
			var cargarEscenas = function(){
				var scenes = tourData.krpano.scene;
				console.log(scenes)
				var sceneCollection = new SceneCollection(scenes);
				var sceneMenuView = new SceneMenuView({ collection: sceneCollection});
				sceneMenuView.render();
			}
			var manageTour = new ManageTour();

			_.each($("#panoManager .selected-panos li"),function(elem,ind){
				var panoId = $(elem).data("id");
				$("#publishController").trigger("savingtour");
				$.ajax({
					url:"php/add_pano_from_collection.php?idtour="+tourId+"&panoid="+panoId,
					dataType:"json",
					success: function( data ){

						if(data.result == "ERROR"){
							location.href= location.protocol+"//"+location.host;
						}else{
						counter ++
						if(counter == total){
							manageTour.reloadTour(cargarEscenas);
								este.removeModal(e);
								este.undelegateEvents();
								$("#publishController").trigger("savedtour",[data.date])
							}
						}
						
					}
				})

			})

		}

	});

	return AddFromPanosManager;

});
