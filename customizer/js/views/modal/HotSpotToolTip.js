define([
	'jquery',
	'underscore',
	'backbone',
	'text!templates/modal/hotspottooltip.html',

], function($, _, Backbone,hotspottooltip){

	var HotSpotToolTip = Backbone.View.extend({
		
		initialize: function () {
		_.bindAll(this);     
		},
		events:{

				 },
		
		render:function(){
			var allData = this.model.get("allData");
			var num = this.model.get("num");
			var myid = allData._name;
			var $me = $("#"+myid);
			var compiledTemplate = _.template(hotspottooltip,{allData:allData,num:num});
			$me.find(".tooltipview").append(compiledTemplate);
			me = this;
			console.log($me.find(".tooltip-controller .onoffswitch-checkbox"))
			$me.find(".tooltip-controller .onoffswitch-checkbox").click(me.tooltipswitch)
			$me.find(".tooltip-controller input[type=radio]").click(me.selectTooltip)
			$me.find(".tooltip-controller input[type=text]").change(me.typeToolTip)

		},

		tooltipswitch:function(e){
			var allData = this.model.get("allData");
			var myid = allData._name;
			var krpano = document.getElementById("krpanoSWFObject");
			console.log($(e.target).is(':checked'))
			if($(e.target).is(':checked')){
				$("#"+myid+" .tooltip-controller .tooltype").show();
				$("#"+myid+" .tooltip-controller input[type=radio]:eq(0)").prop("checked",true);
				var value = $("#"+myid+" .tooltip-controller input[type=radio]:checked").val();
				$("#"+myid+" .custom-tooltype").hide();
		   }else{
		   		$("#"+myid+" .tooltip-controller .tooltype").hide();
				$("#"+myid+" .tooltip-controller input[type=text]").val("");
				var value = "false";
		   }
		  console.log(value)
		console.log('krpano.set("hotspot["'+myid+'"].tooltiptype,"'+value+')'); 
		krpano.set("hotspot["+myid+"].tooltiptype",value); 
		$("#"+myid+" .tooltip-controller").data("tooltype",value)
		krpano.set("hotspot["+myid+"].tooltip","");   
		},

		selectTooltip:function(e){
			var allData = this.model.get("allData");
			var myid = allData._name;
			var krpano = document.getElementById("krpanoSWFObject");
			 if($(e.target).val()=="custom"){
			 	$("#"+myid+" .custom-tooltype").show();
			 }else{
			 	$("#"+myid+" .custom-tooltype").hide();
			 	$("#"+myid+" .custom-tooltype").val("");
			 }
			var tooltipValue = $("#"+myid+" .tooltip-controller input[type=text]").val();
			krpano.set("hotspot["+myid+"].tooltiptype",$(e.target).val()); 
			$("#"+myid+" .tooltip-controller").data("tooltype",$(e.target).val())
			krpano.set("hotspot["+myid+"].tooltip",tooltipValue); 
		},

		typeToolTip:function(e){
			var allData = this.model.get("allData");
			var myid = allData._name;
			var krpano = document.getElementById("krpanoSWFObject");
			var value = $(e.target).val();
			krpano.set("hotspot["+myid+"].tooltip",value);
		},
		
	});

	return HotSpotToolTip;
	
});
