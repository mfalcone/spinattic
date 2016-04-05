define([
  'underscore',
  'backbone'
], function(_, Backbone) {
  var GlobalModel = Backbone.Model.extend({

  		defaults:{
  			"selectedDB":null,
        "serverURL":"http://www2.vicar.com.ar/api",
        "token":null,
        "activa":null,
   		}

  });

  return GlobalModel;

});