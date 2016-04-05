/**
 * @name CoreActions
 * @version 1.0.1 [Jun 25, 2013]
 * @author hardkito
 * @copyright Copyright 2013 hardkito [hardkito at yahoo.com.ar]
 * @fileoverview This script involve main AJAX requests/routing and capturing UI actions. *  
 * 
 */

var CoreActions = CoreActions || (function()
{
    // private
    var params = {};     

    return {
        init : function(parameters) 
        {            
            this.params = jQuery.extend({                                    
                                    action_buttons:     null                                    
                            }, parameters); 
                            
        },
        get : function(parameter)
        {
            return this.params[parameter];

        },        
        set : function(parameter, value)
        {
            this.params[parameter] = value;

        },
        doRqGET : function(url, d, start, success)
        {
            var data = d;

            doAjaxRq(
                    "GET",
                    url,
                    data,
                    start,
                    success
            );
        },
        doRqPOST : function(url, d, start, success)
        {
            var data = d;

            doAjaxRq(
                    "POST",
                    url,
                    data,
                    start,
                    success
            );
        },
        tourRemove : function(data)
        {            
            this.doRqPOST(this.params.route.tourRemove, data, start_callback, success_callback);
        },
        tourBatch : function(data)
        {            
            this.doRqPOST(this.params.route.tourBatch, data, start_callback, success_callback);
        },
        tourPrivacy : function(data)
        {            
            this.doRqPOST(this.params.route.tourPrivacy, data, start_callback, success_callback);
        },
        
        validateUsername : function(data, sc_callback)
        {            
            this.doRqPOST(this.params.route.validateUsername, data, start_callback, sc_callback);
        },
        validateEmail : function(data, sc_callback)
        {            
            this.doRqPOST(this.params.route.validateEmail, data, start_callback, sc_callback);
        },
                
        sceneModify : function(data, sc_callback)
        {            
            this.doRqPOST(this.params.route.sceneModify, data, start_callback, sc_callback);
            make_tour_files($('#tour_id').val());
            verificar(true);
            
        },
        sceneRemove : function(data, sc_callback)
        {            
            this.doRqPOST(this.params.route.sceneRemove, data, start_callback, sc_callback);
            verificar(true);
        }        
        
    };
}());

// Request execution
doAjaxRq = function(t, u, d, bc, sc, as)
{
	if(as == false){
		as = false;
	}else{
		as = true;
	}
    jQuery.ajax({
            type: t,
            url: u,
            data: d,
            beforeSend:  bc,
            async:as,
            success: function(response)
            {                
                if (response == "UNAUTHORIZED") 
                {
                    var routes = CoreActions.get('route');
                    
                    window.location = routes['home'];
                }
                
                sc(response);
            },            
            dataType: 'html'
    });
};

/* DUMP FUNCTIONS */
generaldump = function(response)
{
    var parsedObject         = jQuery.parseJSON(response);
       
   // jQuery('#main-data').html(parsedObject.html);  
    
    stop_loader();
    
    return parsedObject;
}

/* CALLBACKS */
start_callback = function() 
{   
    start_loader();
};

success_callback = function(response) 
{
    //jQuery('.tipsy').remove(); //remove all idle tipsy
    
    //remove_deleting_message();
    
    if (response !== null) 
    {
        //console.log(response);
        showMessage('', response);
        
        stop_loader(); 
        return false;
    }   
    
   // var parsedObject = generaldump(response);
    
   // capture_general_actions();    
        
    /*if (parsedObject.action == "list") 
    {          
        
    }*/
}  


