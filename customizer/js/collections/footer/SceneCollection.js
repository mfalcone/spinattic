define([
  'jquery',
  'underscore',
  'backbone',
  'models/footer/SceneModel'
], function($, _, Backbone, SceneModel){
  
SceneColection = Backbone.Collection.extend({
    model: SceneModel,
    initialize: function(){
    }
});
 
  return SceneColection;
});
