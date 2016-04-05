define([
    'jquery',
    'underscore',
    'backbone',
    'views/modal/Modal',
    'text!templates/modal/contextMenuSkillEditor.html',
    'mCustomScrollbar',
    'helpers/ManageData',
    'helpers/ManageTour',


], function($, _, Backbone,Modal,contextMenuSkillEditor,mCustomScrollbar,ManageData,ManageTour){

    var ContextMenuSkillEditor = Modal.extend({
        
        initialize: function () {
        _.bindAll(this);        
         _.extend(this.events, Modal.prototype.events);
        },
        events:{
            "click #Context-menu-add-item": "addItem",
            "click .skillModal #Context-menu-finish":"doneEdition",
            "click #Context-menu-Skill-editor fieldset .fa-close": "removeItem",
        },
        
        renderExtend:function(){
            
            var myid = this.myid;
            var tourSkill = this.model.get("tourSkill");
            if(tourSkill.contextmenu.item.length == undefined){
                            var items = []
                            items[0] = tourSkill.contextmenu.item;
                            tourSkill.contextmenu.item = items;
                        }

            var template = _.template(contextMenuSkillEditor,{tourSkill:tourSkill})

            
            $("#"+myid+" .inner-modal").html(template);
            $("#"+myid+" header h2").text("Context Menu Skill Editor")
            $("#"+myid).find(".save-and-close").unbind("click");
            $(".scrollwrapper").mCustomScrollbar({
                theme:"minimal-dark",
                scrollInertia:300
            });
        },

        addItem:function(){
            $item = $("#"+this.myid+" .fieldwrapper fieldset:eq(0)").clone();

            var length = $("#"+this.myid+" .fieldwrapper").children().length;
            length++
            $item.attr("id","contextMenu-"+length);
            $item.find("h2").text("item-"+length)
            $item.find("input").val("")
            $("#"+this.myid+" .fieldwrapper").append($item);
        },

        doneEdition:function(e){
            var myid = this.myid;
            var tourSkill = this.model.get("tourSkill");
            var items = []        
            var itemModelstr = JSON.stringify(tourSkill.contextmenu.item[0]);
                
            
            _.each($("#"+myid+" .fieldwrapper fieldset"),function(elem,ind){

                var itemModel = JSON.parse(itemModelstr)
                itemModel._caption = $(elem).find("input.caption").val();
                itemModel._name = $(elem).find("h2").text();
                var numberInt = parseInt(itemModel._tag_ident);
                numberInt = numberInt+ind;
                itemModel._tag_ident = numberInt.toString();
                itemModel._onclick = "openurl("+$(elem).find("input.action").val()+",_blank);";

                items.push(itemModel)
            })
            if(!_.isEqual(items,tourSkill.contextmenu.item)){
                tourSkill.contextmenu.item = items;
                var manageData = new ManageData();
                var manageTour = new ManageTour();
                manageData.editSkill(tourSkill,manageTour.reloadTour)
            }
            this.removeModal(e);
            this.undelegateEvents();
        
        },

        removeItem:function(e){
            $(e.target).parents("fieldset").remove();
        }
    });

    return ContextMenuSkillEditor;
    
});
