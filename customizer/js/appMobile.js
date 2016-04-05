define([
	'jquery',
	'underscore',
	'backbone',
	'routermobile', // Request router.js
], function($, _, Backbone, RouterMobile){

	var initialize = function(){
		// Pass in our Router module and call it's initialize function
			RouterMobile.initialize();
		
	}

	return {
		initialize: initialize
	};
});