define([
    'jquery',
    'underscore',
    'backbone',
    'views/modal/Modal',
    'text!templates/modal/confirm.html',

], function($, _, Backbone,Modal,confirm){

    var AlertView = Modal.extend({
        
        initialize: function () {
        _.bindAll(this);        
         _.extend(this.events, Modal.prototype.events);
        },
        events:{
         },
        
        renderExtend:function(){

            $("#"+this.myid+" header").hide();
            var msg = this.model.get("msg");
            var evt = this.model.get("evt");

            var template = _.template(confirm,{msg:msg})
            $("#"+this.myid+" .inner-modal").html(template)

            this.verticalCent();
            $("#"+this.myid+" .fa-close").click(this.closeBT);
            $("#"+this.myid+" #cancel-event").click(this.closeBT);
            $("#"+this.myid+" #ok-event").click(function(e){
                e.preventDefault();
                evt()
            });
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
