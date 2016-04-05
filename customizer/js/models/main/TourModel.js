define([
  'underscore',
  'backbone'
], function(_, Backbone) {
  var TourModel = Backbone.Model.extend({

  		default:{
  			xmlpath: '',
  		},


  });

  return TourModel;

});