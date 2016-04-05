define([
    'jquery',
    'underscore',
    'backbone',
    'views/modal/Modal',
    'text!templates/modal/loadingBarSkillEditor.html',
    'helpers/HelpFunctions',
    'views/modal/SingleUploader',
    'mCustomScrollbar',
    'jqueryui',
    'colorpicker',
    'helpers/ManageData'

], function($, _, Backbone,Modal,loadingBarSkillEditor,HelpFunctions,SingleUploader, mCustomScrollbar,jqueryui,colorpicker,ManageData){

    var LoadingBarSkillEditor = Modal.extend({
        
        initialize: function () {
        _.bindAll(this);        
         _.extend(this.events, Modal.prototype.events);
        },
        events:{
            "click .skillModal #Context-menu-finish":"doneEdition",
            "change .loading-bar-skill-editor input":"changeVal",
            "click #loading-bar-align li":"changeVal",
            "click #unitsWloading li":"changeUnit"
        
        },
        
        renderExtend:function(){

            var myid = this.myid;
            var tourSkill = this.model.get("tourSkill");
            var template = _.template(loadingBarSkillEditor,{tourSkill:tourSkill})

            $("#"+myid+" .inner-modal").html(template);
            $("#"+myid+" header h2").text("Loading Bar Editor")
            $("#"+myid).find(".save-and-close").unbind("click");
            
            $(".scrollwrapper").mCustomScrollbar({
                theme:"minimal-dark",
                scrollInertia:300
            });

            var helpFunctions = new HelpFunctions();
            helpFunctions.dropDown(".dropdown");
            este = this;
            $('#loading-bar-back-bgcolor, #loading-bar-bar-bgcolor').colorpicker({select:function(ev, colorPicker){
                este.setColor(colorPicker,ev)
            }});

            //limit values
            $('#loading-bar-bar-alpha').change(function(e){
                helpFunctions.limitInputs(e.target,0.1, 1)
            });
            $('#loading-bar-skill-alpha').change(function(e){
                helpFunctions.limitInputs(e.target,0, 1)
            });
            $('#loading-bar-width').change(function(e){
                var units = $('#unitsWloading .title').text();

                if (units === 'px') {
                    helpFunctions.limitInputs(e.target,10);
                } else if (units === '%'){
                    helpFunctions.limitInputs(e.target,1, 100);
                }
            });
            $('#loading-bar-skill-height').change(function(e){
                helpFunctions.limitInputs(e.target,1)
            });
        },

        setColor:function(colorPicker,ev){
            var nuval = "0x"+colorPicker.formatted;
            var krpano = document.getElementById("krpanoSWFObject");
            krpano.set("layer["+$(ev.target).data("name")+"]."+$(ev.target).data("prop"),nuval);

        },
        changeVal:function(e){
            var tourSkill = this.model.get("tourSkill");
            if($(e.target).attr("id") == "loading-bar-width" && $("#unitsWloading h2 .title").text() == "%"){
                var nuval =  $(e.target).val()+"%";
            }else if($(e.target).prop("tagName") == "LI"){
                var nuval = $(e.target).data("value")
            }else{
                var nuval =  $(e.target).val();
            }
            var krpano = document.getElementById("krpanoSWFObject");
            krpano.set("layer["+$(e.target).data("name")+"]."+$(e.target).data("prop"),nuval);
        },

        changeUnit:function(e){
            console.log("a")
            var krpano = document.getElementById("krpanoSWFObject");
            var type = $(e.target).data("value")
            krpano.set("layer[loadingbar_bg].width",$("#loading-bar-width").val()+type);
        },
    
        doneEdition:function(e){
            var myid = this.myid;
            var mytourSkill = this.model.get("tourSkill");
            var mytourSkill;
            mytourSkill.layer._align = $("#loading-bar-align h2 .title").text().toLowerCase();
            mytourSkill.layer._y = $("#loading-bar-skill-offset").val();
            if($("#unitsWloading h2").text() == "%"){
                var type="%"
            }else{
                var type="";
            }
            mytourSkill.layer._width = $("#loading-bar-width").val()+type;
            mytourSkill.layer._height = $("#loading-bar-skill-height").val();
            mytourSkill.layer._bgcolor = "0x"+$("#loading-bar-back-bgcolor").val();
            mytourSkill.layer._bgalpha = $("#loading-bar-skill-alpha").val();
            mytourSkill.layer.layer.layer._bgcolor = "0x"+$("#loading-bar-bar-bgcolor").val();
            mytourSkill.layer.layer.layer._bgalpha = $("#loading-bar-bar-alpha").val();
            
            var manageData = new ManageData();
            manageData.editSkill(mytourSkill)
            this.removeModal(e);
            this.undelegateEvents();
        
        }
    });

    return LoadingBarSkillEditor;
    
});
