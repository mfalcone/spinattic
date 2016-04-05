define([
    'jquery',
    'underscore',
    'backbone',

], function($, _, Backbone){

    var SidebarSubMenu = Backbone.View.extend({
        el: $("aside"),
        
        
        extend:function(child){
            var view = Backbone.View.extend.apply(this, arguments);
            view.prototype.events = _.extend({}, this.prototype.events, child.events);
            return view;
        },


        show:function(fun){
            this.$elem = $("#"+this.model.get("elem"));
            $width = this.model.get("elemWidth");
            this.$elem.width(0);
            this.$elem.show();

            var me = this;
            
            this.$elem.animate({
                opacity:1,
                width: $width+"px"
            },400,function(){
                if(fun != "undefined"){
                    eval(fun)
                }
                if(me.$elem.attr("id")=="sceneSettings-menu"){
                    $("#sceneSettings-menu").trigger("refreshmapposition",[$("#sceneMenu li.selected")])
                }
            });
            $("#tooltip").hide();
            
        },

        hide:function(){
            yo = this;
            yo.$elem.animate({
                opacity:0,
                width: "0px"
            },400,function(){yo.$elem.hide()})
        }
        
    });

    return SidebarSubMenu;
    
});
