define([
    'jquery',
    'underscore',
    'backbone',
    'views/modal/Modal',
    'text!templates/modal/alert.html',

], function($, _, Backbone,Modal,alert){

    var AlertView = Modal.extend({
        
        initialize: function () {
        _.bindAll(this);        
         _.extend(this.events, Modal.prototype.events);
        },
        events:{
         },
        
        renderExtend:function(){

            $("#"+this.myid+" header").hide();
            msg = this.model.get("msg");
            
            var template = _.template(alert,msg)
            $("#"+this.myid+" .inner-modal").html(template)

            this.verticalCent();
            $("#"+this.myid+" .fa-close").click(this.closeBT);
            $("#"+this.myid+" #ok-close").click(this.closeBT);
        },

        closeBT:function(e){
            e.preventDefault();
            $("#"+this.myid).parent(".overlay").fadeOut(function(){

                $(this).remove();
            });

        }



        
    });

    return AlertView;
    
});
