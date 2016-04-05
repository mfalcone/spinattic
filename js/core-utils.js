/**
 * @name Core Utils
 * @version 1.0.1 [Jun 25, 2013]
 * @author hardkito
 * @copyright Copyright 2013 hardkito [hardkito at yahoo.com.ar]
 * @fileoverview Utils functions related to CoreActions. *  
 * 
 */

 function validateEmail($email) {
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
  if( !emailReg.test( $email ) ) {
    return false;
  } else {
    return true;
  }
}


var labels = {
            pub: 'Public',
            pri: 'Private',
            not: 'Not listed'
        };

get_action_data = function(params)
{
    if (!params) return "";
    
    var arr_data = params.split(";");
    var data = "";

    jQuery(arr_data).each(function(i) {
        if (arr_data[i]) data += arr_data[i] + "&";
    });
    
    return data;
}

launch_popup = function(selector, data, route, success_func, params) 
{
    //jQuery(selector).children('.pop').css('margin-top', -100);
    
    
    if (route == 'tourBatch')
    {
        var item_list_str = '<br /><br />';
        
        jQuery(params.containers).each(function(i){
            item_list_str += '<i>» ' + jQuery(this).children('.loader-item').children('a').children('h3').text() + '</i><br/>';
        });
        
        if (data.option == 'remove')
            jQuery(selector+' div.pop form.pop-up label p').html('Are you sure you want to delete the following tours?' + item_list_str);
        else
            jQuery(selector+' div.pop form.pop-up label p').html('Are you sure you want to set to '+data.option.toUpperCase()+' the following tours?' + item_list_str);
    }
    else if(route == 'tourRemove' || route == 'tourItselfRemove')
    {        
        jQuery(selector+' div.pop form.pop-up label p').html('Are you sure you want to delete this tour?');
    }
    
    
    
    jQuery( selector ).fadeIn(250, function()
    {
        if (route == 'tourPrivacy')
        {
            jQuery('.select-privacy select#privacy-level').val(params.current);            
        }   
        
        jquery_bind_event( selector + " .save-button", 'click', function()
        {
            var routes = CoreActions.get('route');
            
            if (route == 'tourPrivacy')
            {
                var new_level = jQuery('.select-privacy select#privacy-level').val();
                data += '&option=' + new_level;
                
                jQuery(params.button_el).removeClass('_'+params.current);
                jQuery(params.button_el).attr('title', labels[new_level.substr(0,3)]);
                params.button_el.className = '_'+new_level+' '+params.button_el.className;
            } 
            else if (route == 'tourRemove' || route == 'sceneRemove')
            {
                jQuery(params.container).remove();
            } 
            else if (route == 'tourBatch')
            {                
                var option = data.option;
                
                if (option == 'remove')
                {
                    jQuery(params.containers).each(function(i){
                        jQuery(this).remove();
                    });
                }   
                else
                {
                    jQuery(params.containers).each(function(i)
                    {
                        var visib_button = jQuery(this).children('a.visibility');
                        
                        var new_level = option;                        
                       
                        jQuery(visib_button).attr('title', labels[new_level.substr(0,3)]);
                        jQuery(visib_button).attr('class', '_'+new_level+' visibility tour-privacy');                        
                    });
                }    
                
            }   
            
            CoreActions.doRqPOST(routes[route], data, start_callback, success_func);
            
            jQuery(this).unbind('click');
            
            
           // remove_deleting_message();
            hide_popup();
            
            return false;
        });
    });
}

jquery_bind_event = function(obj, event, func)
{
    jQuery(obj).unbind(event);
    jQuery(obj).bind(event, func);
}

function start_loader()
{    
    document.getElementById("loading").style.display="block";    
}

function stop_loader()
{
    //LoaderObject.stop();
    document.getElementById("loading").style.display="none";
}

function checkAll()
{
    var boxes = document.getElementsByTagName('input'); 
    
    for(var index = 0; index < boxes.length; index++) 
    { 
        box = boxes[index]; 
   
        if (box.type == 'checkbox' && box.className.indexOf('tour-batch-checkbox') != -1)
        {    
            box.checked = document.getElementById('tours-check-all').checked 
        }    
    } 
    
    return true;
} 

function confirmMessage(title, message, successfunction){


    var html = '<div class="overlay confirm-action">'
        +'      <div class="pop">'
        +'          <a href="#" class="closed"  onclick="hide_popup();"></a>'
        +'          <h2>'+title+'</h2>'
        +'          <div class="content_pop">'
        +'              <form class="pop-up">'
        +'                  <label>           '    
        +'                      <p>'+message+'</p>'
        +'                  </label>'
        +'                    <div class="content-btn-pop">'
        +'	                    <a href="#" class="grey-button border-radius-4" onclick="hide_popup();">NO</a>'
        +'	                    <a href="#" class="red-button border-radius-4 save-button yes-action">YES</a>'
        +'                    </div>'
        +'             </form>'
        +'          </div>'
        +'      </div>'
        +'  </div>';	
	
	 var el = jQuery(html).appendTo('body');
	 jQuery(el).fadeIn(200);
	 $(".yes-action").click(function(){
		 successfunction();
		 hide_popup();
	 });
}

function showMessage(title, message)
{
    var html = '<div class="overlay show-response">'
              +'      <div class="pop">'
              +'          <a href="#" class="closed"  onclick="hide_popup();"></a>'
              +'          <h2>'+title+'</h2>'
              +'          <div class="content_pop">'
              +'              <form class="pop-up">'
              +'                  <label>           '    
              +'                      <p>'+message+'</p>'
              +'                  </label>'
              +'                    <div class="content-btn-pop">'
              +'                        <a onclick="hide_popup();" class="red-button border-radius-4" href="#">OK</a>'
              +'                    </div>'
              +'             </form>'
              +'          </div>'
              +'      </div>'
              +'  </div>';
      
    var el = jQuery(html).appendTo('body');
    var $pop = jQuery(el).children('.pop');
  
    //$pop.css('margin-top', -parseInt(jQuery(window).height()/2));
    //$pop.css('margin-top', -100);
    
    
    jQuery(el).fadeIn(200);
    
    //return false;
}