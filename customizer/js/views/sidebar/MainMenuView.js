define([
    'jquery',
    'underscore',
    'backbone',
    'text!templates/sidebar/mainMenu.html',
    'helpers/HelpFunctions',
    'models/sidebar/SubMenuModel',
    'views/sidebar/HotSpotsMenuView',
    'views/sidebar/ViewSettingsMenuView',
    'views/sidebar/SceneSettingsMenuView',
    'views/sidebar/VirtualTourSettingsMenuView',
    'views/sidebar/SkinCustomizerMenuView'

], function($, _, Backbone,mainMenu,HelpFunctions,SubMenuModel,HotSpotsMenuView,ViewSettingsMenuView,SceneSettingsMenuView,VirtualTourSettingsMenuView,SkinCustomizerMenuView){

    var MainMenuView = Backbone.View.extend({
        el: $("aside"),
        selectedView:null,
        initialize: function () {


            
        },
        events:{
        "click .main-menu li":"openMenuItem"
                 },
        
        render: function(){
        $(this.el).show();  
         var compiledTemplate = _.template(mainMenu);
            $(this.el).append( compiledTemplate ); 
            var helpFunctions = new HelpFunctions();
            helpFunctions.toolTip(".main-menu li","aside");
        },

        openMenuItem : function(e) {
            selector = $(e.target).attr("id");
            
            $(".main-menu li").removeClass("selected");
            
            if($("#"+selector+"-menu").length == 0){

                if(this.selectedView){
                    this.selectedView.hide()
                }
                var helpFunctions = new HelpFunctions();
                strcap = helpFunctions.capitaliseFirstLetter(selector);
                eval("var "+selector+"Model = new SubMenuModel({elem:'"+selector+"-menu'})");
                eval("this."+selector+"MenuView = new "+strcap+"MenuView({model:"+selector+"Model})");
                eval("this.selectedView = this."+selector+"MenuView");
                eval("this."+selector+"MenuView.render()");
            
                $(e.target).addClass("selected");

            }else{                
                if($("#"+selector+"-menu").is(":visible")){
                    eval("this."+selector+"MenuView.hide()");
                    $(e.target).removeClass("selected")
                    this.selectedView = null;

                }else{

                    if(this.selectedView){
                        selectedElem = this.selectedView.model.get("elem")
                        this.selectedView.hide()
                    }

                    eval("this."+selector+"MenuView.show()");
                    eval("this.selectedView = this."+selector+"MenuView");
                
                    $(e.target).addClass("selected")
                }
            }
        }        
    });

    return MainMenuView;
    
});
