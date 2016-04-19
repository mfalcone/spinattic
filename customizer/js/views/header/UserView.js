define([
  'jquery',
  'underscore',
  'backbone',
  'text!templates/header/userview.html',
  'helpers/HelpFunctions',
  'mCustomScrollbar'

], function($, _, Backbone, userview, HelpFunctions, mCustomScrollbar){

  var SceneMenuView = Backbone.View.extend({
    el: $(".header-side"),
    initialize: function () {

      
    },
    events:{
     "click .avatar":"displayMenu",
     "click .notification":"displayNotifications"
         },
    render: function(){
      var data = this.collection.toJSON();
      var compiledTemplate = _.template(userview,{jsonObj:data});
      $(this.el).append( compiledTemplate ); 
      $(".main-header .user").data("level",data[0].level);
      $(".main-header .user").data("nickname",data[0].friendly_usr);
      var helpFunctions = new HelpFunctions();
      helpFunctions.toolTip("header .new-tour","new-tour-tt up");
      var este = this;
      $(".main-header .new-tour").click(function(){
        este.createNewTour();
      })
    },

    displayMenu: function() {
      $("header .menu-list:not(.users)").hide()
      $("header .menu-list.users").fadeToggle();
    },

    displayNotifications: function() {
      

      $("header .menu-list:not(.notifications)").hide()
      $("header .menu-list.notifications").fadeToggle();
      this.callNotif();

    },

    callNotif:function(next){
      $(".main-header .notifications .loader").show();
      var este = this;
      query_string = '';
      if(next == 'next'){
        query_string = "?action=getLastPosts&lastID="+$(".notif_item:last").attr("id");
      }else{
        query_string = "";
      }
        $.ajax({
              url:'../ajax_get_notif.php'+query_string,
              type:'POST',
              success:function(res){
                $(".main-header .notifications .loader").hide();
                if(query_string == ""){
                    $(".main-header .notifications ul").html(res);
                }else{
                    $(".main-header .notifications ul").html($(".main-header .notifications ul").html()+res);
                }

                $(".main-header .notifications .inner-notifications").mCustomScrollbar({
                  theme:"minimal-dark",
                  scrollInertia:300,
                  callbacks:{
                    onTotalScroll:function(){
                      if($('.nomore_notif').length == 0){
                            $(".main-header .notifications .inner-notifications").mCustomScrollbar("scrollTo","bottom",{scrollInertia:1,  timeout:1});
                            este.callNotif('next');
                      }
                    }
                  }
                });

                $(".header-side .notifications .delete-item").click(function(e){
                    este.deleteItem(e);
                }) 

                $(".header-side .notifications .notif_item").click(function(e){
                    este.checkNotif(e)
                })

              },
              error:function(xhr, ajaxOptions, thrownError){
              }
          })

    },

    deleteItem:function(e){
      var $li_item = $(e.target).parents("li.item");
      var notif_id = $(e.target).attr("rel");
      e.preventDefault();
      $.ajax({
        url:'../ajax_del_item.php?a=notifications&id='+notif_id,
        type:'GET',
        success:function(){
          $li_item.fadeOut(function(){
            $(this).remove();
          })
        }
      })
    },

    checkNotif:function(e){
      var $this_li = $(e.target);
      var notif_id = $this_li.data("notif_id");
      if(!$this_li.hasClass("read")){
        $.ajax({
          url:'../ajax_check_notif.php?',
          type:'POST',
          data:"id="+notif_id,
          success:function(){
           $this_li.addClass("read")
          }
        })
      } 
    },
    createNewTour:function(){
      var url = location.protocol+"//"+location.host+"/customizer/";
      location.href = url;
    }

    
  });

  return SceneMenuView;
  
});
