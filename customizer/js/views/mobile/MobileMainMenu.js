define([
	'jquery',
	'underscore',
	'backbone',
	'text!templates/mobile/mobilemainmenu.html',
    'helpers/ManageData',
    'helpers/ManageMobileHotSpots',
    'helpers/HelpFunctions',
], function($, _, Backbone, mobilemainmenu,ManageData,ManageMobileHotSpots,HelpFunctions){

	var MobileMainMenu = Backbone.View.extend({

		el: $("#mobile-header"),

		initialize: function () {
		  
		},

		events:{  
			"click #startup": "setStartUpView",
			"click #hotspot": "addHotSpot",
            "changeScene #mobile-header":"cargarEscena"
		},

		render: function(){
			var compiledTemplate = _.template(mobilemainmenu);
			$(this.el).append( compiledTemplate );
            var este = this;
            window.cargarEscenasGlobal = function(sceneName){

                if(window.desdeMenu){
                    delete window.desdeMenu;
                }else{
                    console.log("aca")
                   este.cargarEscena(sceneName)
                }
           
             
                /*
              setTimeout(function(){
              var krpano = document.getElementById("krpanoSWFObject");
              var currentSceneName = krpano.get("xml.scene");
              
              _.each(tourData.krpano.scene,function(elem,ind){
                    if(elem._name == $("#tour").data("scene")._name){
                        _.each(elem.hotspot,function(hpelem,indice){
                             krpano.call("removehotspot("+hpelem._name+")")
                        })
                    }
                })
            
            krpano.set("events.onpreviewcomplete","js(initHotSpots())");
            krpano.set("events.keep",true);
            },1000)   
            */}
		},

        cargarEscena:function(sceneName){
            console.log(sceneName)
            window.desdeMenu = true;

             var selectedScene;
           
           _.each(tourData.krpano.scene,function(elem,ind){
                    if(elem._name == sceneName){
                       
                       selectedScene = elem

                    }
                })  

             $("#tour").data("scene",selectedScene)
             var helpFunctions =  new HelpFunctions();

            var customparam = jQuery.extend({},selectedScene);
            delete customparam.view._segment;
            delete customparam.preview._segment;
            delete customparam.preview._url;
            delete customparam.image._segment;
            delete customparam._segment;
            delete customparam.hotspot;
            
            var krpano = document.getElementById("krpanoSWFObject");
            var param = helpFunctions.mapJSONToUriParams(customparam);
            param = param.replace(/:_/g,".");
            krpano.call("loadscene('"+selectedScene._name+"','"+param+"',MERGE|KEEPCONTROL,BLEND(1));");

            if(selectedScene.hotspot){
            var manageMobileHotSpots = new ManageMobileHotSpots();
                krpano.set("events.onpreviewcomplete","js(initHotSpots())");
                krpano.set("events.keep",true);
                }
        },

		setStartUpView:function(){
            var krpano = document.getElementById("krpanoSWFObject");
            var hlookat = krpano.get("view.hlookat");
            var vlookat = krpano.get("view.vlookat");
            krpano.set("view.hlookat",hlookat);
            krpano.set("view.vlookat",vlookat);

            $("#startupViewUpdated").fadeIn("slow",function(){
                $(this).delay(2000).fadeOut("slow")
            })
            var currentSceneName = krpano.get("xml.scene");
             var manageData = new ManageData();
            manageData.saveSceneOnTour(currentSceneName,"_hlookat",hlookat,"view")
            manageData.saveSceneOnTour(currentSceneName,"_vlookat",vlookat,"view")
        },

        addHotSpot:function(){
        	if($("#tour").data("scene").hotspot){
                var numbs = []
                _.each($("#tour").data("scene").hotspot,function(hp,val){
                    var num = parseInt(hp._name.replace("spot",""));
                    numbs.push(num);
                })


                this.hotspotCount = Math.max.apply(Math, numbs);
            }else{
                this.hotspotCount = 0;
            }
            this.hotspotCount++;
           	urlid = "1"
           	this.selectedset = "set1";
            var me = this;

            $.ajax({
                url:"data/xml.php?t=htspts&c=1&id="+urlid,
                 dataType: "html",
                 success:function(data){
                    console.log(data)
                    var x2js = new X2JS({attributePrefix:"_"});
                    var newHotspot =  x2js.xml_str2json( data ).hotspot;
                    console.log(newHotspot)
                    var krpano = document.getElementById("krpanoSWFObject");
                    var __ath   =  krpano.get('view.hlookat')//-Math.floor(Math.random() * 45);
                    var __atv   =  krpano.get('view.vlookat')//-Math.floor(Math.random() * 25);
                    newHotspot._ath = __ath;
                    newHotspot._atv = __atv;
                    var oldstyle=newHotspot._style.replace("[style]","hotspot_"+me.selectedset+"_arrow")
                    newHotspot._style = oldstyle;
                    newHotspot._name = "spot"+me.hotspotCount;

                    var manageHotSpots = new ManageMobileHotSpots();

                    krpano.call("addhotspot("+newHotspot._name +")");
                    
                    /*krpano.set("hotspot["+newHotspot._name+"].ath", newHotspot._ath );
                    krpano.set("hotspot["+newHotspot._name+"].atv", newHotspot._atv );
                    krpano.set("hotspot["+newHotspot._name+"].tooltip_type", newHotspot._tooltip_type );
                    */

                    $.each(newHotspot,function(i,val){
                        if(i!="_name"){
                            var prop = i.replace("_","");
                            krpano.set("hotspot["+newHotspot._name+"]."+prop,val) 
                        }
                    })
                    krpano.call("hotspot["+newHotspot._name+"].loadStyle("+newHotspot._style+");");
                    krpano.call('set(hotspot['+newHotspot._name+'].ondown, draghotspot() );');
                    krpano.call('set(hotspot['+newHotspot._name+'].onclick, js(openHotspotWindowEditor('+newHotspot._name+')) );');
                    krpano.call('set(hotspot['+newHotspot._name+'].onup, js(regPos('+newHotspot._name+')) );');
                    var manageData = new ManageData();
                    manageData.pushHotspot( $("#tour").data("scene")._name,newHotspot)
                    openHotspotWindowEditor(newHotspot._name);
                 }
             })
        }

	});

	return MobileMainMenu;  
});
