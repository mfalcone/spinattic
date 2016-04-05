define([
	'jquery',
	'underscore',
	'backbone',
	'views/sidebar/SidebarSubMenu',
	'text!templates/sidebar/viewSettingsMenu.html',
	'helpers/ManageData',
	'helpers/HelpFunctions',
	'text!templates/main/setStartup.html'

], function($, _, Backbone, SidebarSubMenu, viewSettingsMenu, ManageData, HelpFunctions, setStartup){

	var ViewSettingsMenuView = SidebarSubMenu.extend({
		
		initialize: function () {
		
		},
		events:{
			"change #viewSettings-menu input":"changeValuePano",
			"click #setStartUpView":"setStartUpView"
		},
		
		render: function(){
			var compiledTemplate = _.template(viewSettingsMenu);
			$(this.el).append( compiledTemplate );             

			this.$elem = $("#"+this.model.get("elem"));
			this.model.set("elemWidth",this.$elem.width());
			
			this.$elem.append( setStartup ); 
			this.show();
			
			var helpFunctions = new HelpFunctions();
			helpFunctions.refreshData();
		},

		changeValuePano:function(e){
			myid = $(e.target).attr("id");
			var param = ""
			switch(myid){
				case "fov":
				param = "fov"
				this.limitFov()
				break;

				case "max-zoom":
				param = "maxpixelzoom"
				break;

				case "fov-max":
				param = "fovmax"
				this.limitFov()
				break;

				case "fov-min":
				param = "fovmin"
				this.limitFov()
				break;

				case "hor":
				param = "hlookat"
				break;

				case "vert":
				param = "vlookat"
				this.limitVert()
				break;
			}

			var krpano = document.getElementById("krpanoSWFObject");
			krpano.set("view." + param, $("#" + myid).val());
			this.updateData(e);
		},

		updateData:function(e){
			var manageData = new ManageData();
			manageData.saveSceneOnTour($("#viewSettings-menu").data("scenename"),$(e.target).data("obj"),$(e.target).val(),"view")
		},

		setStartUpView:function(){
			var krpano = document.getElementById("krpanoSWFObject");
			var hlookat = krpano.get("view.hlookat");
			var vlookat = krpano.get("view.vlookat");
			var fov = krpano.get("view.fov");
			$("#hor").val(hlookat);
			$("#hor").trigger("change");
			$("#vert").val(vlookat);
			$("#vert").trigger("change");
			$("#fov").val(fov);
			$("#fov").trigger("change");

			$("#startupViewUpdated").fadeIn("slow",function(){
				$(this).delay(2000).fadeOut("slow")
			})
		},

		limitFov: function () {
			var min = Number($('#fov-min').val()),
				max = Number($('#fov-max').val()),
				fov = $('#fov'),
				curFov = Number($(fov).val());

			if (curFov < min){
				$(fov).val(min);
			} else if (curFov > max) {
				$(fov).val(max);
			}
		},

		limitVert: function(e) {
			var el = $('#vert'),
				val = Number($(el).val());

			if(val < -90){
				$(el).val(-90);
			} else if ( val > 90){
				$(el).val(90);
			}
		}
		
	});

	return ViewSettingsMenuView;
	
});
