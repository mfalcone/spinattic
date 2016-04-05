define([
  'underscore',
  'backbone'
], function(_, Backbone) {
  var HotSpotWindowModel = Backbone.Model.extend({

  		default:{
  			id: '',
  		}


  });

  return HotSpotWindowModel;

});