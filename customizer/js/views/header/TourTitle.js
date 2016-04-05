define([
	'jquery',
	'underscore',
	'backbone',
	'text!templates/header/tourtitle.html',
	'helpers/ManageData',
	'helpers/HelpFunctions'
  

], function($, _, Backbone, tourtitle,ManageData, HelpFunctions){

	var TourTitle = Backbone.View.extend({

		el: $(".header-bottom"),

		initialize: function () {
		  
		},

		events:{
			"focus #tour-title":"changeTitleByEnter",
			"blur #tour-title":"changeTitleByBlur",
			"keyup #tour-title":"setWidth"
		},

		render: function(){

			var title = tourData.krpano.settings._title
			if(title ==  "360º Virtual tour created with Spinattic.com"){
				title = ""
			}
			var widthTestElHtml = "<span id='widthTestEl' class='none'></span>",
				widthEl = $("#widthTestEl");

			var compiledTemplate = _.template(tourtitle,{title:title});
			$(this.el).append( compiledTemplate );           
			$("#tour-title").data("obj","settings");
			$("#tour-title").data("bind","_title");

			//width del input
			if(!widthEl.lenght) {
				$('body').append(widthTestElHtml);
			} 
			this.setWidth();       

			var helpFunctions = new HelpFunctions();
			helpFunctions.toolTip("header .open-live-tour","open-live-tour-tt up");

			var este = this;
			$("#tourTitleBar").on("updatepublish",function(ev,param){
				este.showButton(ev,param);
			})

		},

		changeTitleByEnter:function(e){

		$(window).keydown(function(event){
			if(event.keyCode == 13) {
				$("#tour-title").blur();
			  event.preventDefault();
			  $(window).unbind("keydown");
			  return false;
			}
		  });
		},
		changeTitleByBlur:function(e){

			var helpFunctions = new HelpFunctions();
			var manageData = new ManageData();
			if(tourData.krpano.datatour.friendlyURL == ""){
				var textTitle = $("#tour-title").val();
				var textLug = helpFunctions.slug(textTitle)
				if( $("#virtualTourSettings-menu").size()){
					$("#virtualTourSettings-menu #friendlyURLTour").val(textLug);
				}
				manageData.saveTourData("friendlyURL",textLug)

			}
			manageData.saveSettings(e);

			//this.setWidth(e.target);            

		},
		setWidth : function() {
			var el = $("#tour-title"),
				elText = $(el).val(),
				widthTestEl = $('#widthTestEl'),                
				widthTest;

			$(widthTestEl).html(elText);                
			widthTest = $(widthTestEl).width();

			$(el).width(widthTest+5);
		},

		showButton:function(evt,param){
			if(param=="on"){
				var $bt = $('<button class="open-live-tour" title="Open live tour page"><i class="fa fa-external-link"></i></button>');
				$("#tourTitleBar form").append($bt)
				$bt.click(function(e){
					e.preventDefault();
					window.open("http://"+location.host+"/"+$(".user").data("nickname")+"/"+tourData.krpano.datatour.friendlyURL,'_blank');
				})
			}else{
				$("#tourTitleBar .open-live-tour").remove();
			}
		}


	});

	return TourTitle;
  
});
