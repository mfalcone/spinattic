define([
    'jquery',
    'underscore',
    'backbone',
    'views/modal/Modal',
    'views/sidebar/MapView',

], function($, _, Backbone,Modal,MapView){

    var MapModalView = Modal.extend({
        
        initialize: function () {
        _.bindAll(this);        
         _.extend(this.events, Modal.prototype.events);
        },
        events:{
        },
        
        renderExtend:function(){

            var myid = this.myid;

            $("#"+myid+" .inner-modal").html('<div class="gmap-wrapper"></div>');
            $("#"+myid+" header h2").text("Map");
            $("#"+myid).find(".save-and-close").unbind("click");
            $("#"+myid).find(".save-and-close").click(this.doneEdition);

            
            var MapModel = Backbone.Model.extend({});
            var lat = this.model.get("lat");
            var lng = this.model.get("lng");
            var mapModel = new MapModel({lat:lat,lng:lng})
            
            var mapView = new MapView({model:mapModel});
            mapView.render(myid);

            this.verticalCent();
        },

        doneEdition:function(e){
            var myid = this.myid;
            var elemToAttach = this.model.get("elemToAttach");
            var lat = $("#"+myid+" .latFld").val();
            var lng = $("#"+myid+" .lngFld").val();
            var MapModel = Backbone.Model.extend({});
            var mapModel = new MapModel({lat:lat,lng:lng})
            var mapView = new MapView({model:mapModel});
            var scnParam = null;
            if(elemToAttach == "sceneSettings-menu"){
                var indice = $("#sceneMenu .selected").index();
                var param = "scene";
                scnParam = {param:param,indice:indice}; 
            }else{
                scnParam = "settings";
            }
            mapView.render(elemToAttach,scnParam);
            this.removeModal(e);
            this.undelegateEvents();
            
        }

    });

    return MapModalView;
    
});
