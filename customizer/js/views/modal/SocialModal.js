define([
    'jquery',
    'underscore',
    'backbone',
    'views/modal/Modal',
    'text!templates/modal/socialModal.html',
    'helpers/HelpFunctions',
    'socialshare'

], function($, _, Backbone,Modal,socialModal,HelpFunctions,socialshare){

    var SocialModal = Modal.extend({
        
        initialize: function () {
        _.bindAll(this);        
         _.extend(this.events, Modal.prototype.events);
        },
        events:{
            "click #socialModal .cancel": "removeModal",
            "click #socialModal .save": "shareAction" 
        },
        
        renderExtend:function(){

            var myid = this.myid;
            var template = _.template(socialModal);
            var helpFunctions = new HelpFunctions();

            $("#"+myid+" .inner-modal").html(template);

            $("#"+myid+" header h2").text("YOUR TOUR IS PUBLISHED!");            

            helpFunctions.checkbox2("#"+myid+" .check-group","fa-check","unchecked");

            this.verticalCent();

            /*$('.check-group').socialShare({
                image           : 'image-url',
                twitterVia      : 'ritz078',
                twitterHashTags : 'spinattic,krpano'
            }); */
            $('.csbuttons').cSButtons();
        },

        shareAction: function() {
            var selected = $('#socialModal .check-group span.fa-check');

            $(selected).each(function(i, el){                
                $(el).siblings('div').click();
                console.log(i)
            });
        }

    });

    return SocialModal;
    
});
