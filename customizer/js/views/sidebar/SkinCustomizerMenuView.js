define([
    'jquery',
    'underscore',
    'backbone',
    'views/sidebar/SidebarSubMenu',
    'text!templates/sidebar/skinCustomizerMenu.html',
    'helpers/HelpFunctions',
    'views/modal/SkillsModalList',
    'mCustomScrollbar',
    'views/sidebar/SkinCustomizerItem',

], function($, _, Backbone, SidebarSubMenu, skinCustomizerMenu, HelpFunctions, SkillsModalList, mCustomScrollbar,SkinCustomizerItem){

    var SkinCustomizerMenuView = SidebarSubMenu.extend({
        initialize: function () {
          this.events = this.events || {};
          var addStyles = 'click #' + this.model.get("elem") + ' .add-link';
          this.events[addStyles] = 'addStyle';
          this.delegateEvents(); 
        },

        render: function(){

            var skill = tourData.krpano.skill;
            var skill_del = tourData.krpano.datatour.skills;
            var compiledTemplate = _.template(skinCustomizerMenu);


            $(this.el).append( compiledTemplate );

            _.each(skill,function(skl,ind){

                var no_delete_if_free;
                var customizable;
                var SkillItemModel = Backbone.Model.extend({});
                _.each(skill_del,function(elem,indice){
                    if(elem.skill_id == skl._template_id){

                        no_delete_if_free = elem.no_delete_if_free;
                        customizable = elem.customizable;
                    }
                })

                skillItemModel = new SkillItemModel({tourSkill:skl,no_delete_if_free:no_delete_if_free,customizable:customizable});
                var skinCustomizerItem = new SkinCustomizerItem({model:skillItemModel});
                skinCustomizerItem.render();

            })


            elem =  this.model.get("elem");

            this.$elem = $("#"+elem);
            this.model.set("elemWidth",this.$elem.width());
            var helpFunctions = new HelpFunctions();
            helpFunctions.setInnerHeight(elem);

            $("#skinCustomizer-menu .inner").mCustomScrollbar({
                theme:"minimal-dark",
                scrollInertia:300
            });

            this.show();
        },

        openSubItems:function(e){
            $mineSub = $(e.target).parent("li").find(".sub-items")
            $("#"+this.model.get("elem")+" .sub-items").not($mineSub).slideUp();
            $mineSub.slideToggle();
        },
        addStyle:function(e){
           var este = this;
           if(!$("#skillsModalList").size()){
            este.skillsModalList = new SkillsModalList();
            este.skillsModalList.render("skillsModalList",este.skillsModalList.renderExtend);
            $("#skillsModalList").addClass("skillModal").parent(".overlay").addClass("skillWindow");
            este.skillsModalList.verticalCent();
            }else{
                $("#skillsModalList .skill-list").html("");
                $("#skillsModalList .loading").show("");
               este.skillsModalList.fullfillSkillList(); 
            }
        }

        
    });

    return SkinCustomizerMenuView;
    
});
