define([
  'underscore',
  'backbone'
], function(_, Backbone) {
  var SubMenuModel = Backbone.Model.extend({

  		default:{
  			elem: '',
  			elemWidth:''
  		}

  });

  return SubMenuModel;

});