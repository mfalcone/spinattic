CoreActions.init({
    route : {
        
        sceneModify:'php-stubs/scenes.php?action=modify',
        sceneRemove:'php-stubs/scenes.php?action=remove',
        tourItselfRemove:'php-stubs/tours.php?action=remove'
    }
});


jQuery(document).ready(function()
{

	/*Elementos que disparan el autosave*/
	
	
	//Para los blur, verifico antes que el elemento clickeado no sea uno de los botones y que se hayan hecho cambios

	var actualizar = 0;

	$(document).mousedown(function(e){
		if($(e.target).hasClass( "to-draft" ) || $(e.target).hasClass( "to-publish" )){
			$('.warning_m').hide();
			actualizar = 0;
		}
	});
	
	$("#title").keypress(function(){
		actualizar = 1;
	});
	
	$("#description").keypress(function(){
		actualizar = 1;
	});

	$("#title").blur(function(){
		if(actualizar == 1){
			actualizar = 0;
			verificar(true);
		}
	});
	$("#description").blur(function(){
		if(actualizar == 1){
			actualizar = 0;		
			verificar(true);
		}
	});		
	$("#privacy").change(function(){
		actualizar = 0;
	    verificar(true);
	});
	$("#category").change(function(){
		actualizar = 0;
	    verificar(true);
	});	 
	$("#allow_comments").click(function(){
		actualizar = 0;	
	    verificar(true);
	});	  
	$("#allow_social").click(function(){
		actualizar = 0;	
	    verificar(true);
	});
	$("#allow_embed").click(function(){
		actualizar = 0;	
	    verificar(true);
	});
	$("#allow_votes").click(function(){
		actualizar = 0;	
	    verificar(true);
	});
	$("#enable_avatar").click(function(){
		actualizar = 0;	
	    verificar(true);
	});	  	
	$("#enable_title").click(function(){
		actualizar = 0;	
	    verificar(true);
	});	
	$("input:radio[name=skin]").click(function() {
		actualizar = 0;	
	    verificar(true);
	});	
	
	$("#image-width").change(function(){
		$imageWidth = $("#image-width");
		max = $imageWidth.attr("max");
		min =  $imageWidth.attr("min");
		widthval = $imageWidth.val();
		if((widthval <= Number(max)) && (widthval >= Number(min))){
					maxresize();
			}else{

				if($imageWidth.val() > Number(max)){
					$imageWidth.val(max);
					widthval = $("#image-width").val();
					maxresize();
				}else if($imageWidth.val() < Number(min)){
					$imageWidth.val(min);
					widthval = $("#image-width").val();
					maxresize();
				}

			} 
		actualizar = 0;	
	    verificar(true);		
	});


	$("#image-height").change(function(){
		$imageHeight = $("#image-height");
		max = $imageHeight.attr("max");
		min =  $imageHeight.attr("min");
		heightVal = $imageHeight.val();
		if((heightVal <= Number(max)) && (heightVal >= Number(min))){
					minresize();
			}else{

				if($imageHeight.val() > Number(max)){
					$imageHeight.val(max);
					heightVal = $("#image-height").val();
					minresize();
				}else if($imageHeight.val() < Number(min)){
					$imageHeight.val(min);
					heightVal = $("#image-height").val();
					minresize();
				}

			} 
		actualizar = 0;	
	    verificar(true);		
	});


	$("#margins").change(function(){
		$margins = $("#margins");
		max = $margins.attr("max");
		min =  $margins.attr("min");
		paddings = $("#margins").val();
		if(paddings <= Number(max) && paddings >= Number(min)){
			$("#image-wrapper").css("padding",paddings+"px");
		}else{
			if(paddings > Number(max)){
				paddings = max;
				$("#margins").val(paddings);
				$("#image-wrapper").css("padding",paddings+"px");
			}else{
				paddings = min;
				$("#margins").val(paddings);
				$("#image-wrapper").css("padding",paddings+"px");
			}
		}
		actualizar = 0;	
	    verificar(true);		
	});    	
	
	
	//El cambio en el mapa está en maploc.js
	  
	/*Fin elementos*/
	  
    jQuery( "#tabs" ).tabs(); 
    
    jQuery('.delete-item').tipsy({gravity: 'e', html: true});
    jQuery('.help').tipsy({gravity: 's', html: true});
   
    $('#tags_loaded').tagsInput({
            width: 'auto',
            onChange: function(elem, elem_tags)
            {
                    /*   var languages = ['php','ruby','javascript'];
                    $('.tag', elem_tags).each(function()
                    {
                            if($(this).text().search(new RegExp('\\b(' + languages.join('|') + ')\\b')) >= 0)
                                    $(this).css('background-color', 'yellow');
                    });*/

                //console.log($(this).val());
            },
            onRemoveTag: function(elem, elem_tags)
            {

            },                            
            autocomplete_url:'php-stubs/tags.php' // jquery ui autocomplete requires a json endpoint

    });
    
    jQuery('.remove-tour').click(function()
    {
    	if(!$(this).hasClass('grey-button')){
    	var data         = 'id='+gTour_id;
        
        launch_popup('.confirm-action', data, 'tourItselfRemove', 
                            function(response)
                            {                                
                                defaultCallback(response);
                                
                                window.location = 'manager_tour.php';
                            }, {});  
    	}
        return false;


        //CoreActions.sceneRemove(data, removeCallback);
    });

    jQuery('.to-draft').click(function(){
	   	if (!$(this).hasClass('grey-button')) {
			$('#saving-type').val('draft');
			return verificar();
		} else {
			event.preventDefault();
		}
    });    
    
    jQuery('.to-publish').click(function(){
	   	if (!$(this).hasClass('grey-button')) {
        	if($('.on-edit').length == 0){
        		showMessage('Saving data', 'ERROR: The tour must have at least 1 pano to be published')
            	event.preventDefault();
        	}else{
    			$('#saving-type').val('publish');
    			return verificar();
        	}

		} else {
			event.preventDefault();
		}
    });    
    

    
    
    
    enable_title_edit();
});


function enable_title_edit()
{
    jquery_bind_event('.otf-editable','click', function()
    {
        jQuery('.on-edit').fadeOut(120,function()
        {            
            jQuery(this).prev().fadeIn(120);
        });
        
        var titulo = jQuery(this).text();
        
        jQuery(this).fadeOut(120,function(){
                jQuery(this).next().find('input.stringkey').attr({'value': titulo});
                jQuery(this).next().fadeIn(0,function()
                {
                    jQuery(this).css('display', 'inline-block');
                    jQuery(this).find('input.stringkey').focus().select();
                });
        });
    });

   /* jquery_bind_event('input.stringkey', 'focusout', function(){
        jQuery(this).attr('value', jQuery(this).parent().parent().prev().text());
        
        jQuery(this).parent().parent().fadeOut(120,function()
        {            
            jQuery(this).prev().fadeIn(120);
        });
    });*/
    
    jquery_bind_event('input.stringkey', 'focusout', function(){
        var nuevo_titulo = jQuery(this).val();                
        var data         = jQuery(this).parent().serialize();

        CoreActions.sceneModify(data, modifyCallback);

        jQuery(this).parent().parent().fadeOut(120,function()
        {                
            jQuery(this).prev().text(nuevo_titulo);
            jQuery(this).prev().fadeIn(120);
        });

        return false;
    });    
    
    jQuery('input.stringkey').bind('keypress', function(event)
    {            
        if (event.keyCode == '13')
        {
            event.preventDefault();
            
            var nuevo_titulo = jQuery(this).val();                
            var data         = jQuery(this).parent().serialize();

            CoreActions.sceneModify(data, modifyCallback);

            jQuery(this).parent().parent().fadeOut(120,function()
            {                
                jQuery(this).prev().text(nuevo_titulo);
                jQuery(this).prev().fadeIn(120);
            });

            return false;
        }            
    });
}


function modifyCallback(response)
{
    defaultCallback(response);
}

function removeCallback(response)
{
    defaultCallback(response);
}

function defaultCallback(response)
{    
    stop_loader(); 
    return false; 
}

function submit_form(data, autosave)
{
    doAjaxRq(
        "POST", 
        "ultour.php?autosave="+autosave, 
        data,                 
        function(){
        	if(!autosave){
	       		overlay = $('<div class="overlay" style="display:block;"></div>');
	       		overlay.appendTo('body');
	       		$('#loading').css('display', 'block');
        	}else{
        		$('#cartel').html('Saving changes ...');
        		$('#cartel').show();
        	}
        }, 
        function(response){          
            
            if(!autosave){
                xml_version = $.parseJSON(response).params.xml_version;
                brand_new = $.parseJSON(response).params.brand_new;
                
                $('#loading').css('display', 'none');
                
            	window.scrollTo(0,0);
	            
	            $('#title_label').html('Editing tour: '+$('#title').val());
	            $('#html_title_label').html('Spinattic - Editing tour: '+$('#title').val());
	            $('.open_tour').attr('href','tour.php?id='+$('#tour_id').val());
	
	
	            if(jQuery('#saving-type').val() == 'draft'){
	            	$('.draft_element').show();
	            	$('.publish_element').hide();
	            }else{
	            	$('.draft_element').hide();
	            	$('.publish_element').show();
	            }
	            
	            overlay.remove();
	            showMessage('Saving data', 'Your changes have been saved');
	            if(brand_new == '1'){
	            	mixpanel.track("Published tour");
	            	notificate($('#tour_id').val(), 6);
	            }else{
	            	mixpanel.track("Updates tour");
	            }
            }else{
            	$('#draft_subscript').val('_draft');
            	$('#cartel').html('Changes saved in draft!');
            	$('#cartel').delay( 5000 ).fadeOut( 400 );
            }
        }
    );
}

function verificar(autosave)
{                    
	//Elimino el cartel con la posibilidad de volver al draft:
	if($('.dismiss').length > 0){
		$( ".dismiss" ).trigger( "click" );
	}
	
	
	
	
    var title        = encodeURIComponent(jQuery('#title').val());
    var description  = encodeURIComponent(jQuery('#description').val());
    var location     = jQuery('#location').val();
    var tags_loaded  = jQuery('#tags_loaded').val();
    var category     = jQuery('#category').val();
    var privacy      = jQuery('#privacy').val();
    var lat          = jQuery('#latFld').val();
    var lng          = jQuery('#lngFld').val();
    var saving_type  = jQuery('#saving-type').val();
    var skin_id		 = jQuery("input:radio[name=skin]:checked").val();
    var thumb_width  = jQuery('#image-width').val();
    var thumb_height  = jQuery('#image-height').val();
    var thumb_margin  = jQuery('#margins').val();    
    
    var allow_comments   = jQuery('#allow_comments').is(':checked')? 'on' : '';
    var allow_social     = jQuery('#allow_social').is(':checked')? 'on' : '';
    var allow_embed      = jQuery('#allow_embed').is(':checked')? 'on' : '';
    var allow_votes      = jQuery('#allow_votes').is(':checked')? 'on' : '';
    var enable_avatar    = jQuery('#enable_avatar').is(':checked')? 'on' : '';
    var enable_title     = jQuery('#enable_title').is(':checked')? 'on' : '';

    
    var data = $('#main-form').serialize();
        
    data += '&title='+title;
    data += '&description='+description;
    data += '&location='+location;
    data += '&tags_loaded='+tags_loaded;
    data += '&category='+category;
    data += '&privacy='+privacy;
    data += '&lat='+lat;
    data += '&lon='+lng;
    data += '&saving-type='+saving_type;
    data += '&allow_comments='+allow_comments;
    data += '&allow_social='+allow_social;
    data += '&allow_embed='+allow_embed;
    data += '&allow_votes='+allow_votes;
    data += '&enable_avatar='+enable_avatar;
    data += '&enable_title='+enable_title;
    data += '&skin_id='+skin_id;
    data += '&thumb_width='+thumb_width;  
    data += '&thumb_height='+thumb_height;  
    data += '&thumb_margin='+thumb_margin;  

//console.log( document.form1.title + description + location + tags_loaded + category);

    if (jQuery('#saving-type').val() == 'draft' || title )
    {               

        var hotspot_changes = exportHotspotsToString();

		if(hotspot_changes == null){
			hotspot_changes = '';
		}
        
        submit_form(data + hotspot_changes, autosave);
        
        
        //document.form1.submit();
    }    
    else
    {
        showMessage('Saving data', 'Please complete the title.');
    }    

    return false;
}

function toogle_botonera(action){
	if(action == 'on'){
		$('#botonera a.remove-tour').removeClass('grey-button').addClass('red-button');
		$('#botonera a.draft_element').removeClass('grey-button').addClass('green-button');
		$('#botonera a.publish_element').not('.open_tour').removeClass('grey-button').addClass('green-button');
	}else{
		$('#botonera a.remove-tour').removeClass('red-button').addClass('grey-button');
		$('#botonera a.draft_element').removeClass('green-button').addClass('grey-button');
		$('#botonera a.publish_element').not('.open_tour').removeClass('green-button').addClass('grey-button');
	}
}

//funcion para plasmar cambios en el XML de un tour modificado
function make_tour_files(idtour){
	if(idtour != ''){	
		var xmlhttp;
		if (window.XMLHttpRequest)
		  {// code for IE7+, Firefox, Chrome, Opera, Safari
		  xmlhttp=new XMLHttpRequest();
		  }
		else
		  {// code for IE6, IE5
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		  }
		xmlhttp.onreadystatechange=function()
		{
		  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		  {

		  }
		}
		xmlhttp.open("GET","php-stubs/make_tour_files.php?ajax_call=on&idtour="+idtour,true);
		xmlhttp.send();
	}
	return false;	
}

$(".choose-skin p:not('.disabled')").on({
	mouseenter:function(e){
		$(e.target).parent("p").next(".content-info-map").fadeIn(); 
	},
	mouseleave:function(e){
		$(e.target).parent("p").next(".content-info-map").fadeOut();
	}

})

function maxresize(){
			$("#image-to-change").width(widthval);
			$("#image-wrapper").width(widthval);
			$("#image-height").val(widthval/2);	
			$("#image-to-change").height($("#image-height").val());
			$("#image-wrapper").height($("#image-height").val());
}

function minresize(){
			$("#image-to-change").height(heightVal);
			$("#image-wrapper").height(heightVal);
			$("#image-width").val(heightVal*2);	
			$("#image-to-change").width($("#image-width").val());
			$("#image-wrapper").width($("#image-width").val());
}


