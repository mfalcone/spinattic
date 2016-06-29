CoreActions.init({
	route : {
		home:window.location.protocol+'//'+window.location.hostname+'/index.php',
		tourRemove:window.location.protocol+'//'+window.location.hostname+'/php-stubs/tours.php?action=remove',
		tourPrivacy:window.location.protocol+'//'+window.location.hostname+'/php-stubs/tours.php?action=privacy',
		tourBatch:window.location.protocol+'//'+window.location.hostname+'/php-stubs/tours.php?action=batch'
	}
});

$(document).ready(function()
{
	jQuery('.edit-buttom').tipsy({gravity: 's', html: true});
	jQuery('.visibility').tipsy({gravity: 's', html: true});
	jQuery('.delete-item').tipsy({gravity: 'e', html: true});
	
	/* capture tours actions*/
	jquery_bind_event('.tour-remove', 'click', function()
	{        
		var data        = get_action_data(jQuery(this).attr('rel'));
		var container   = jQuery(this).parent();
		
		var params = {
					container  : container
				};
				
		launch_popup('.confirm-action', data, 'tourRemove', success_callback, params);                        

		return false;
	});

	jquery_bind_event('.tour-privacy', 'click', function()
	{        
		var data   = get_action_data(jQuery(this).attr('rel'));

		var classN = this.className;
		var begin  = classN.indexOf('_')+1;
		var end    = classN.substr(begin).indexOf(' ') + begin;

		var current_level = classN.substr(begin , end-begin);

		var params = {
			current  : current_level,
			button_el : this
		};

		launch_popup('.select-privacy', data, 'tourPrivacy', success_callback, params);              

		return false;
	});

	jquery_bind_event('#tours-batch-actions', 'change', function()
	{                        
		var option  = jQuery(this).val();                        

		if (option && option != 'none')
		{   
			var ids         = new Array();
			var containers  = new Array();
			var selected = document.getElementsByName('ids[]'); 

			for(var index = 0; index < selected.length; index++) 
			{ 
				box = selected[index]; 

				if(box.checked) 
				{
					ids.push(box.value);
					containers.push(jQuery(box).parent().parent());
				}
			} 

			if (ids.length)
			{   
				var data = {
					'option' : option, 
					'ids[]': ids
				};
				
				var params = {
					containers  : containers                    
				};

				
				launch_popup('.confirm-action',data, 'tourBatch', success_callback, params);
			}
			$("#tours-batch-actions").val($("#tours-batch-actions option:first").val());
			return false;                         
		}
	});

	jquery_bind_event('#epp','change',function(e){
		changePage(e);
	});

	jquery_bind_event('#sby','change',function(e){
		changePage(e);
	});
	
	jquery_bind_event('.paginator a','click',function(e){
		changePage(e);
	});

	jquery_bind_event('.content-btn-pop a.grey-button','click',function(){
		hide_popup();
	});

	jquery_bind_event('#tours-check-all','click',function(){
		checkAll();
	});

	jquery_bind_event('#custom-sign','click',function(e){
		e.preventDefault();
		if(jQuery(".manage_tours input[type='checkbox']:checked").size()){
			jQuery(".modal-signature").fadeIn();
		}else{
			alert("Please select one or more tours to apply a custom sinature");
		}
	});

	jquery_bind_event('.modal-signature .buttonModal','click',function(){
	   jQuery(".modal-signature").fadeOut();
	});

});