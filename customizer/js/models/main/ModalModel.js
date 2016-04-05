define([
  'underscore',
  'backbone'
], function(_, Backbone) {
  var ModalModel = Backbone.Model.extend({

  		default:{
  			msg: '',
  		}


  });

  return ModalModel;

});