define([
	'jquery',
	'underscore',
	'backbone',
	'jqueryui',
	'text!templates/modal/modaltemplate.html',


], function($, _, Backbone,jqueryui,modaltemplate){

	var Modal = Backbone.View.extend({
		el: $("body"),
		myid:"",
		initialize: function () {
			_.bindAll(this,'render');       
		},

		extend:function(child){
			var view = Backbone.View.extend.apply(this, arguments);
			view.prototype.events = _.extend({}, this.prototype.events, child.events);
			return view;
		},


		render:function(id,fun){

			this.myid = id;
			var compiledTemplate = _.template(modaltemplate,{myid:this.myid});
			$(this.el).append( compiledTemplate );

			$("#"+this.myid).parent().fadeIn();            
			
			$(".modal").draggable({ handle:'header'});

			var este = this;

			$("#"+this.myid).find(".fa-close").click( function(e){
				este.removeModal(e);
				este.undelegateEvents();
			})

			$("#"+this.myid).find(".save-and-close").click( function(e){
				este.removeModal(e);
				este.undelegateEvents();
			})
		  
			if(fun != undefined){
				fun();
			}
			
		},

		verticalCent : function() {
			$("#"+this.myid).css({
				'top' : '50%',
				'margin-top' : - $("#"+this.myid).outerHeight()/2
			});
		},

		removeModal: function(evnt) {
			$(evnt.target).parents(".overlay").remove();
		}
		
	});

	return Modal;
	
});
