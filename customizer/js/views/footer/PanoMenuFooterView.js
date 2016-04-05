define([
    'jquery',
    'underscore',
    'backbone',
    'views/main/UploaderViewD',
    'text!templates/footer/panoMenuFooter.html',
    'helpers/HelpFunctions',
    'views/modal/AddFromPanosManager'

], function($, _, Backbone,UploaderView,panoMenuFooter,HelpFunctions,AddFromPanosManager){

    var PanoMenuFooterView = Backbone.View.extend({
        el: $("footer.main-footer"),
        minHeight:0,
        totalHeight:0,
        uploaderview:null,
        addfrompanosmanager:null,

        initialize: function () {            
        },

        events:{
            "click #footer-hide-show":"showHideFooter",
            "click #pano-uploader":"openPanoUpload",
            "click #add-pano-manager":"openPanoManager"
        },

        render: function(){
         var compiledTemplate = _.template(panoMenuFooter);
            $(this.el).append( compiledTemplate ); 
            this.minHeight =  $("footer menu").outerHeight();
            this.totalHeight = $("footer").height()
        },

        showHideFooter:function(e){
            me = this;
            $footer = $(e.target).parents("footer");
            var helpFunctions = new HelpFunctions();
            if($footer.height() == me.totalHeight){
                $footer.animate({
                    height:me.minHeight
                },function(){
                    helpFunctions.setInnerHeight(".submenu",true);
                    helpFunctions.setInnerHeight(".main-section",true);
                })
            $footer.find("#footer-hide-show i").removeClass("fa-angle-double-down").addClass("fa-angle-double-up")
            
            }else{
                $footer.animate({
                    height:me.totalHeight
                },function(){
                    helpFunctions.setInnerHeight(".submenu",true);
                    helpFunctions.setInnerHeight(".main-section",true);
                })
            $footer.find("#footer-hide-show i").removeClass("fa-angle-double-up").addClass("fa-angle-double-down")
            }
        },

        openPanoUpload: function() {

            if($(".uploaderBallon").children().size()){
                this.uploaderview.removeView(); 
            }else{
               
                var UploaderModel = Backbone.Model.extend({});
                var cancel = true;
                uploaderModer = new UploaderModel({gNewTour:false,addingPane:true,cancel:true});
                this.uploaderview = new UploaderView({model:uploaderModer});
                this.uploaderview.render();
            }

            $(".uploaderBallon").toggle();
            $(".uploaderBallon").height($(".main-section").height());
        },

        openPanoManager: function() {

            if($(".pano-manager").length) {            
                this.addfrompanosmanager.removeView();                
            }else{
                this.addfrompanosmanager = new AddFromPanosManager();
                this.addfrompanosmanager.render("panoManager",this.addfrompanosmanager.renderExtend);
            }
        }
        
    });

    return PanoMenuFooterView;
    
});
