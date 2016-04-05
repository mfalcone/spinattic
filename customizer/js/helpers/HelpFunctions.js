define([
    'jquery',
    'underscore',
    'backbone',
    'models/main/ModalModel',
    'views/modal/AlertView',
    'bowser'
  
], function($, _, Backbone, ModalModel, AlertView){

    var HelpFunctions =  function(){

     
        this.toolTip = function(obj,myclass){
            var el, text, $tooltip;
            var showTT = function ( $tooltip, sideL, valH, valV) {
                if (sideL) {
                    $tooltip.css({
                        left: valH,
                        top: valV
                    }).show();
                } else {
                    $tooltip.css({
                        right: valH,
                        top: valV
                    }).show();
                }                
            }

            if(!$("#tooltip").length){
                $("body").append('<div id="tooltip"><span class="txt">text</span><span class="arrow"></span></div>')
            }

            $tooltip = $("#tooltip"); 
            
            $(obj).mouseenter(function(event){                      
                el = $(event.target);
                text = $(el).attr("title");

                var eleOffset = $(el).offset(),            
                    elWidth = $(el).outerWidth(),
                    ttWidth,
                    leftP,
                    rightP,
                    arrowRightP,
                    arrow = $($tooltip).find('.arrow'),
                    arrowLeft;

                $(el).removeAttr("title");

                if($(el).attr("id")){
                    singleClass = $(el).attr("id");
                }else{
                    singleClass = "";
                }

                $tooltip.addClass(myclass +" "+ singleClass).find('.txt').text(text); 

                ttWidth = $tooltip.outerWidth();
                leftP = (eleOffset.left) + (elWidth - ttWidth) / 2;
                rightP =  ($(window).width() - (eleOffset.left + elWidth)) + (elWidth - ttWidth) / 2;
                arrowLeft = eleOffset.left + elWidth/2;
                arrowRightP = ($(window).width() - arrowLeft);                

                //footer or header thumbs tooltip
                if ( myclass === 'footer' || myclass === 'publish up') {

                    if (rightP < 0) {
                        rightP = 10;
                        $tooltip.addClass('rightist').find('.arrow').css({
                            right: arrowRightP-10, left: "initial"
                        });

                        //arrow position
                        $(arrow).css('right', arrowRightP-20);

                        showTT($tooltip, false, rightP, eleOffset.top)
                    } else {
                        if ( leftP < 0 ) {
                            leftP = 10;
                            $tooltip.addClass('leftist');
                            //arrow position
                            $(arrow).css('left', arrowLeft-10);
                        }
                        
                        showTT($tooltip, true, leftP, eleOffset.top)
                    }
                } else {
                    showTT($tooltip, true, leftP, eleOffset.top)
                }         
             
            }).mouseleave( function( event ){
                $tooltip.attr("style","").hide();
                $tooltip.removeClass().find('.arrow').attr('style', '');
                $(el).attr("title",text);
            });
        }

        this.toolTipHtml = function(obj,myclass){
            var el, text, $tooltip;

            if(!$("#tooltip").length){
                $("body").append('<div id="tooltip"><span class="txt">text</span><span class="arrow"></span></div>')
            }

            $tooltip = $("#tooltip"); 

            $(obj).mouseenter(function(event){ 
                el = $(event.target);

                var eleOffset = $(el).offset(),            
                    elWidth = $(el).outerWidth(),
                    ttWidth,
                    leftP,
                    rightP,
                    arrowRightP;

                var text = $(el).siblings('.tooltipHtml').html();                
                $tooltip.addClass(myclass).find('.txt').html(text);

                ttWidth = $tooltip.outerWidth();
                leftP = (eleOffset.left) + (elWidth - ttWidth) / 2;
                rightP =  ($(window).width() - (eleOffset.left + elWidth)) + (elWidth - ttWidth) / 2;
                arrowRightP = ($(window).width() - (eleOffset.left + elWidth/2));

                $tooltip.css({
                        left: leftP,
                        top: eleOffset.top
                    }).show();

            }).mouseleave( function( event ){
                $tooltip.attr("style","").hide().removeClass();
            })
        }

        this.capitaliseFirstLetter = function(string){
            return string.charAt(0).toUpperCase() + string.slice(1);
        }


        this.setInnerHeight = function(obj,byClass){

             var  innerHeight = $(window).height()-($("header.main-header").outerHeight()+$("footer.main-footer").outerHeight());
                if(!byClass){
                $("#"+obj +" .inner").height(innerHeight);
                }else{
                $(obj +" .inner").height(innerHeight);
                }
        }

        this.dropDown = function(elem, titleElement){
            var titleEl = (titleElement) ? titleElement : "h2"

            $(elem).find(titleEl).click(function(e){
                $(this).next("ul").slideToggle();
            })

             $(elem).find("li").click(function(e){
                $(this).closest(elem).find(titleEl + " .title").text($(e.target).text())
                $(this).closest(elem).find("ul").slideToggle();
             })
        }

        this.checkbox = function(elem,firstClass,secClass){
                $(elem+" li").click(function(){
                        $(this).find("span").toggleClass(firstClass).toggleClass(secClass);
                })
        }
        this.checkbox2 = function(elem,firstClass,secClass){

                $(elem+" span").click(function(e){
                    e.stopPropagation();
                        $(this).toggleClass(firstClass).toggleClass(secClass);
                })
        }

        this.selectChoice = function(elem,firstClass,secClass){
                $(elem).click(function(){
                        $(elem).each(function(){
                                $(this).find("span").attr("class","fa "+secClass+" fa-lg")
                        })
                        $(this).find("span").attr("class","fa "+firstClass+" fa-lg")
                })
        }

        this.mapJSONToUriParams=function(data, prefix, call){
            
            prefix = typeof prefix !== 'undefined' ? prefix : "";
            call = typeof call !== 'undefined' ? call : 0;

            var map = [];
            var me = this;
            if( Object.prototype.toString.call( data ) === '[object Array]' ) {

                    for (var ik = 0; ik < data.length; ik++){
                            map.push(me.mapJSONToUriParams(data[ik], prefix + "[" + ik + "]", call + 1));
                    };
                    
            }else if ( Object.prototype.toString.call( data ) === '[object Object]' ) {
                    Object.keys(data).map(function(k){
                            var sep = "";
                            
                            //not empty
                            if (prefix !== ""){
                                    
                                    if (prefix.slice(-1) !== "]"){
                                            sep = ":";
                                    }
                            }
                            
                            map.push(me.mapJSONToUriParams(data[k], prefix + sep + k, call + 1));
                    });      
                    
            }else{
                    map.push(prefix + "=" + encodeURIComponent(data));
            }   
                    
            return map.join("&");
        }


        this.refreshData = function(){
            if($("#viewSettings-menu").length){
                var scenedata = $("#tour").data("scene");
                $("#viewSettings-menu").data("scenename",scenedata._name)
                $("#viewSettings-menu #hor").val(scenedata.view._hlookat)
                $("#viewSettings-menu #hor").data("obj","_hlookat")
                $("#viewSettings-menu #vert").val(scenedata.view._vlookat)
                $("#viewSettings-menu #vert").data("obj","_vlookat")
                $("#viewSettings-menu #fov").val(scenedata.view._fov)
                $("#viewSettings-menu #fov").data("obj","_fov")
                $("#viewSettings-menu #fov-min").val(scenedata.view._fovmin)
                $("#viewSettings-menu #fov-min").data("obj","_fovmin")
                $("#viewSettings-menu #fov-max").val(scenedata.view._fovmax)
                $("#viewSettings-menu #fov-max").data("obj","_fovmax")
                $("#viewSettings-menu #max-zoom").val(scenedata.view._maxpixelzoom)
                $("#viewSettings-menu #max-zoom").data("obj","_maxpixelzoom")
                
            }

            if($("#sceneSettings-menu").length){
                var scenedata = $("#tour").data("scene");
                $("#sceneSettings-menu").data("scenename",scenedata._name)
                $("#sceneSettings-menu #scenetitle").val(scenedata._title)
                $("#sceneSettings-menu #scenetitle").data("obj","_title")
                $("#sceneSettings-menu #filename").val(scenedata._scene_filename)
                $("#sceneSettings-menu #friendlyURL").val(scenedata._urlname)
                $("#sceneSettings-menu #friendlyURL").data("obj","_urlname")
                $("#sceneSettings-menu #scene-thumbnail-src img").attr("src",scenedata._thumburl);
                $("#sceneSettings-menu #scene-description").val(scenedata._description);
                $("#sceneSettings-menu #scene-description").data("obj","_description");
            }

        }


        this.nineGrillSelector = function(selector){
            $(selector).find(".fa-circle").click(function(){
                $(selector).find(".fa-circle").removeClass("selected")
                $(this).addClass("selected");
            })
        },

        this.prepareConditionsForTour=function(){
            if(tourData.krpano.scene){
                if(tourData.krpano.scene.length == undefined){
                        var escenas = [];
                        escenas[0] = tourData.krpano.scene;
                        tourData.krpano.scene = escenas
                }
            }else{
                if(!$(".loading-msg").is(":visible")){
                    $(".loading-msg").show();
                }

                $(".loading-msg .loading").addClass("fa fa-image").removeClass("loading");
                $(".loading-msg .fa-image").next().text("The tour has no panos");
                
            }

            _.each(tourData.krpano.scene,function(scene,ind){
                if(scene.hotspot){
                    if(scene.hotspot.length == undefined){
                        var myhp = []
                        myhp[0] = scene.hotspot;
                        tourData.krpano.scene[ind].hotspot = myhp;
                    }
                }
            })

            if(tourData.krpano.skill){
                if(tourData.krpano.skill.length == undefined){
                    var capacidad = [];
                    capacidad[0] = tourData.krpano.skill;
                    tourData.krpano.skill = capacidad
                }
            }
        }

        this.limitInputs = function(el, min, max){
            var val = Number($(el).val());
          
            var min = $(el).attr("min")

            max ? max = max : max = $(el).attr("max");
            min ? min = min : min = $(el).attr("min");

            if(val < min){
                $(el).val(min);
            } else if ( val > max){
                $(el).val(max);
            }
        }

        this.slug = function(str){
          str = str.replace(/^\s+|\s+$/g, ''); // trim
          str = str.toLowerCase();

          var from = "ãàáäâẽèéëêìíïîõòóöôùúüûñç·/_,:;";
          var to   = "aaaaaeeeeeiiiiooooouuuunc------";
          for (var i=0, l=from.length ; i<l ; i++) {
            str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
          }

          str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
            .replace(/\s+/g, '_') // collapse whitespace and replace by -
            .replace(/-+/g, '-'); // collapse dashes

          return str;
        };

        this.skillTabs = function(modalId) {
            $('.skill-tabs .menuTabs li', '#'+modalId).click(function(){
                var tabId = $(this).attr('data-content');

                $('.skill-tabs .menuTabs li', '#'+modalId).removeClass('selected');
                $('.tabContent section').addClass('none');
                
                $(this).addClass('selected');
                $('#'+tabId).removeClass('none');
                
            })
        }

        this.checkUser = function(){
            if(location.host != "www.spinattic.com"){
                return;
            }

            setInterval(function(){

                $.ajax({
                    type: "POST",
                    url: "php/ajax_chk_session.php",
                    data: "chklogout=true",
                    cache: false,
                    success: function(res){
                        if(res == "1"){
                            location.href= location.host+"/index.php?login";
                        }else{
                            $.ajax({
                                dataType:"json",
                                url:  "data/json.php?t=u",
                            }).done(function(obj){
                                if(obj.unread_notifications != 0){
                                    $(".user-wrapper .notification").html('<span class="counter">'+obj.unread_notifications+'</span><span class="fa fa-bell fa-lg"></span>')
                                }
                            })
                        }
                    }
                })

            },2000)

        }

        this.showReloadOverlay = function(){
            if(!$("#reloadMsg").size()){
                var $reloadMsg = $('<div id="reloadMsg"></div>');
                $reloadMsg.append("<p>Reloading tour, please wait...</p>")
                $("body").append($reloadMsg);
            }   
        }

        this.hideReloadOverlay = function(){
            if($("#reloadMsg").size()){
                $("#reloadMsg").remove();
            }
        }

        this.showMsg = function(msg){
            var modalModel = new ModalModel({msg:msg})
            var alertView = new AlertView({model:modalModel});
            alertView.render("alertUploader",alertView.renderExtend);
        }

        this.detectBrowser = function () {
            var browserMsg = "You're trying to access the Customizer with <span>" + require('bowser').name + " " + require('bowser').version + "</span>. <br> <br> The Spinattic Customizer is not optimized for older browsers. Please install the latest version of your desired Browser.";



            if ( require('bowser').msie && require('bowser').version <= 8 ) {
              this.showMsg(browserMsg);
            } /*else if (bowser.firefox){
              alert('Hello Foxy');
            } else if (bowser.chrome){
              alert('Hello Silicon Valley');
            } else if (bowser.safari){
              alert('Hello Apple Fan');
            } else if(bowser.iphone || bowser.android){
              alert('Hello mobile');
            }*/
        }

        this.showErrorNextToInput = function($obj,msg,style){
            if(!$obj.next().hasClass("error-after-input")){
                $obj.after('<div class="error-after-input" style="'+style+'">'+msg+'</div>');
                $obj.addClass("input-error");
            }
        }

        this.hideErrorNextToInput=function($obj){
            if($obj.next().hasClass("error-after-input")){
                $obj.next(".error-after-input").remove();
                $obj.removeClass("input-error");
            }
        }


}

    return HelpFunctions;
    
});
