define([
    'jquery',
    'underscore',
    'backbone',
    'views/modal/Modal',
    'text!templates/modal/skillEditor.html'

], function($, _, Backbone,Modal,skillEditor){

    var SkillEditor = Modal.extend({
        initialize:function(){
            _.bindAll(this);
        },

        renderExtend:function(){
            
            var myid = this.myid;
            var compiledTemplate = _.template(skillEditor);
            $("#"+myid+" .inner-modal").html(compiledTemplate);

            this.verticalCent();
            
            var data = this.model.get("data");
            $("#"+myid+" header h2").text(data.title);
        }
        
    });


    return SkillEditor;
    
});
