
jQuery(document).ready(function(){

	
        var drag_overlay = null;
        var last_zindex  = null;
        var rollingOver = false;
        
	var dropbox = $('#drop-zone'),
		message = $('.drop-message', dropbox);
	
	dropbox.filedrop({
		// The name of the $_FILES entry:
		paramname:'pic',
		refresh: 100,
		maxfiles: 3,
                maxfilesize: 200,
		url: 'php-stubs/upload-files.php',
		
	/*	uploadFinished:function(i,file,response){
			$.data(file).addClass('done');
			// response is the JSON object that post_file.php returns
		},*/
		
                error: function(err, file) {
			switch(err) {
				case 'BrowserNotSupported':
					showMsg('Your browser does not support HTML5 file uploads!');
					break;
				case 'TooManyFiles':
					showMsg('Too many files! Please select 3 at most!');
					break;
				case 'FileTooLarge':
					showMsg(file.name+' is too large! Please upload files up to 200 mb.');
					break;
				default:
					break;
			}
		},
		
		// Called before each upload is started
		beforeEach: function(file){    
			
			toogle_botonera('off');
			
			if(!file.type.match(/^image\//)){
                            //alert('Only images are allowed!');
                            showMsg('Only images are allowed!');
                            
                            // Returning false will cause the
                            // file to be rejected
                            return false;
			}
                        
                        if(!file.type.match(/jpeg|tiff/)){
				showMsg('Format not supported!');

				return false;
			}
		},
		
		uploadStarted:function(i, file, len){
                    
                    if (rollingOver)
                    { 
                        last_zindex = $('#drop-zone').css('z-index', last_zindex);
                        drag_overlay.remove();

                        rollingOver = false;
                        //$('.upload-cloud').
                    }    
                        
                    createImage(file);
		},
                
                dragOver:function(e){    
                    
                    if (!rollingOver)
                    {    
                        drag_overlay = $('<div class="overlay" style="display:block;"></div>');
                        drag_overlay.appendTo('body');

                        last_zindex = $('#drop-zone').css('z-index');
                        $('#drop-zone').css('z-index', '999999999');
                        
                        rollingOver = true;
                    }    
		},
                
                dragLeave:function(e){     
                    
                    if (rollingOver)
                    { 
                        last_zindex = $('#drop-zone').css('z-index', last_zindex);
                        drag_overlay.remove();

                        rollingOver = false;
                        //$('.upload-cloud').
                    }    
		},
		
		progressUpdated: function(i, file, progress) {
			$.data(file).find('.progress').width(progress+'%');
                        if (parseInt(progress) < 30 ) $.data(file).find('.percentage').html(progress+'%');
                        else $.data(file).find('.percentage').html('Loading '+progress+'%');
		},
                
                uploadFinished:function(i, file, response)
                {        
                   
                    if (response.result == 'SUCCESS')
                    {
                        var $success_result = $.data(file).find('.ok');
                        var $loader_item    = $.data(file).find('.loader-item');
                        
                        var pano_id         = response.params.pano_id;
                        var scene_id        = response.params.scene_id;
                        var tour_id         = response.params.tour_id;    
                        var filename        = response.params.file_name;  
                        var scene_name      = response.params.file_name.replace(/\.jpg|\.jpeg|\.tiff/g, '');
                        var html_ref_id     = 'item-'+scene_id;
                        
                        $success_result.after( "<p>Processing image... Step 1/2</br><img src=\"images/loading.gif\"></p>" );
                        
                        $.data(file).attr('id', html_ref_id);


                        $.data(file).find('.loader-item-bg').remove();
                        $.data(file).find('.uploadind_message').remove();
                        
                        $loader_item.children('h3.otf-editable').html(scene_name); 
                        $loader_item.children('input.scene-field').val(scene_id);                                         
                                 
                        $loader_item.children('h4.pano-title').html(filename);         
                        $loader_item.children('input.pano-field').val(pano_id);
                        
                        $loader_item.children('.on-edit').children('form').children('input.scene-id').val(scene_id);
                                      
                 
                        // Executes Krpano script
                        launchImageProcessing (filename, pano_id, scene_id, html_ref_id);
                        verificar(true);
                        
                    }
                    else if (response.result == 'ERROR')
                    {
                        $.data(file).remove();
                        showMsg(response.msg);
                    }    
		},
                
                afterAll : function()
                {
                    enable_title_edit();
                    toogle_botonera('on');
                }
                
                
    	 
	});
	
	/*var template = '<div class="preview">'+
						'<span class="imageHolder">'+
							'<img />'+
							'<span class="uploaded"></span>'+
						'</span>'+
						'<div class="progressHolder">'+
							'<div class="progress"></div>'+
						'</div>'+
					'</div>'; */
	
        
        var template = '<div class="pano-item">' +
				'<div class="thumb-pano">' +
                                        '<img />'+
                                '</div>' +
				'<div class="loader-item">' +
                                    'Scene name: <h3 class="otf-editable">@SCENE@</h3> '+    
                        
                        
                                    '<div class="on-edit">' +
                                        '<form action="php-stubs/scenes.php" method="post">'  +
                                            '<input type="hidden" class="scene-id" name="scene-id" value="">'  +
                                            '<input class="stringkey" type="text" size="32" value="" name="scene-name" /> '  +
                                            '<br clear="all">' +
                                        '</form>'  +
                                    '</div>' +

                                    '<input type="hidden" class="scene-field" name="scene-id[]" value="">' +

                                    '<br />File name: <h4 class="pano-title">@PANO@</h4>' +

                                    '<input type="hidden" class="pano-field" name="pano-id[]" value="">' +

                                    '<div class="ok" style="display:none;"></div>'+                                    
                                    '<p class="uploadind_message">Uploading panorama.</p>' +
                                    '<div class="loader-item-bg border-radius-10">' +
                                     '     	<div class="loader-bar border-radius-10 progress" style="width:89%">' +
                                     '           	<div class="percentage"></div>' +
                                     '               <br>' +
                                     '      </div>' +
                                    ' </div>' +
                                ' </div>' +
                             '<br clear="all">' +
         '                    <a href="#" class="delete-item scene-remove" title="Delete item" style="display:none;" data-id="#"></a>' +
         '		      <a href="#" class="add-element border-radius-2 edit-hotspots" data-id="#" data-thumb="#" style="display:none;">' +         
         '	                    Edit hotspots' +
         '	                </a>' +
         '					<a href="#" class="drag-item"  style="display:none;"></a>' +
          '               </div>';
        
        
	
	function createImage(file)
        {
            

            var scene_name = file.name.replace(/\.jpg|\.jpeg|\.tiff/g, '');
            var pano_name = file.name;

            block = template.replace('@SCENE@', scene_name);
            block = block.replace('@PANO@', pano_name);
            block = block.replace('@PANO@', pano_name);
            

            var preview = $(block), 
                    image = $('img', preview);

            var reader = new FileReader();

            image.width = 100;
            image.height = 100;

            reader.onload = function(e){

                    // e.target.result holds the DataURL which
                    // can be used as a source of the image:

                    //   image.attr('style','width:16px;height:16px;');

                    //image.attr('src',e.target.result);                        
                    // image.attr('src','images/in-progress.gif');
            };

            // Reading the file as a DataURL. When finished,
            // this will trigger the onload function above:
            reader.readAsDataURL(file);

            message.hide();
            preview.appendTo(dropbox);

            // Associating a preview container
            // with the file, using jQuery's $.data():

            $.data(file,preview);
	}
	

	function createFromCollection(panoid)
    {
		//si el tour no aparece creo el tour y hago aparecer el form ( autocreateTour() )
	    if ($("#tour_id").val() == ''){ autocreateTour();}
	    
		tourid = $("#tour_id").val();

		var block = '<div class="pano-item" id="item-@scene_id@">'
				+ '<div class="thumb-pano">'
				+ '<img src="panos/'
				+ panoid
				+ '/index.tiles/thumb200x100.jpg">'
				+ '</div>'
				+ '<div class="loader-item">'
				+ 'Scene name: <h3 class="otf-editable">@scene_name@</h3>'
				+ '<div class="on-edit">'
				+ '<form action="php-stubs/scenes.php" method="post">'
				+ '<input class="scene-id" name="scene-id" value="@scene_id@" type="hidden">'
				+ '<input class="stringkey" size="32" value="@scene_name@" name="scene-name" type="text">'
				+ '<br clear="all">'
				+ '</form>'
				+ '</div>'
				+ '<input class="scene-field" name="scene-id[]" value="@scene_id@" type="hidden">'
				+ '<br>File name: <h4 class="pano-title">@file_name@</h4>'
				+ '<input class="pano-field" name="pano-id[]" value="'
				+ panoid
				+ '" type="hidden">'
				+ '</div>'
				+ '<br clear="all">'
				+ '<a original-title="Remove Scene" href="#" class="delete-item scene-remove" data-id="@scene_id@"></a>'
				+ '<a href="#" class="add-element border-radius-2 edit-hotspots" data-id="@scene_id@" data-thumb="panos/'
				+ panoid
				+ '/index.tiles/thumb100x50.jpg">'
				+ 'Edit hotspots'
				+ '</a>'
				+ '<a href="#" class="drag-item"></a>'
				+ '</div>';

		// linkeo la pano al tour por AJAX (panosxtour):
		var xmlhttp;
		if (window.XMLHttpRequest) {// code for IE7+, Firefox,
									// Chrome, Opera, Safari
			xmlhttp = new XMLHttpRequest();
		} else {// code for IE6, IE5
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}

		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				respuesta = JSON.parse(xmlhttp.responseText);
				if (respuesta.result == 'SUCCESS') {

					var scene_name = respuesta.scene_name;
					var scene_id = respuesta.scene_id;
					var file_name = respuesta.file_name;

					block = block.replace(/@scene_name@/g, scene_name);
					block = block.replace(/@scene_id@/g, scene_id);
					block = block.replace(/@file_name@/g, file_name);

					var preview = $(block);

					preview.appendTo(dropbox);

					// oculto el drop zone
					$('.upload-cloud').hide();

					// bind hotspots
					hs_unbind_all();
					hs_bind_all();

					// bind delete scene (core-utils.js)
					preview.children('.scene-remove').click(function() {
										var data = 'scene-id=' + scene_id;
										var container = jQuery(this).parent();
										var params = {container : container};
										launch_popup('.confirm-action',	data, 'sceneRemove', removeCallback, params);
										});

					//bind edicion de nombre de escena (core.tour-edit.inc.js)                    
					enable_title_edit();
					verificar(true);

				}
			}
			}		
			xmlhttp.open("GET","ajax_get_pano_from_collection.php?idtour="+tourid+"&panoid="+panoid,true);
			xmlhttp.send();	
		

   
}	
	
	$(".select-from").click(function(){
		$(".panocollection").show();
	});

	$(".add_selected_panos").click(function(){
		var any = 0;
		$('input[name="sel[]"]').each(function() {
			if($(this).is(':checked')){
				createFromCollection($(this).val());
				any = 1;
			}
		});
		
		if(any == 0){
			showMessage('Important', 'Your must select at least 1 pano');
			$(".panocollection").show();
		}else{
			hide_popup();
			$('input[name="sel[]"]').removeAttr('checked');
		}
	});	
	
	function showMsg(msg){
		//message.html(msg);
                showMessage('', msg);
	}
        
        

});


function autocreateTour ()
{
    if (gNew_tour) 
    {
    	//A ESTA FUNCION LE PUSE ASYNC: FALSE PARA SIMPLIFICAR EL AGREGADO DE PANOS FROM COLLECTION (CORE-ACTIONS.JS) 
    	
        doAjaxRq('POST', 'ultour.php', 'autocreate=true', function(){}, function(response){                                         
            parsedObj = jQuery.parseJSON(response);
            
            gTour_id    = parsedObj.params.tour_id;  
            xml_version = parsedObj.params.xml_version;
            
            gNew_tour = false;
                        
            //jQuery(document.form1).append('<input id="tour_id" name="tour_id" type="hidden" value="'+gTour_id+'"/>');
            $("#tour_id").val(gTour_id);
            jQuery('#tabs-1 input#title').val('Untitled '+gTour_id);
            jQuery('#tabs').css('display', 'block');
            
            jQuery('.uploading_pano').css('display', 'block'); 
            jQuery('#drop-zone').css('margin-top', '0');
            
            setTimeout(initMap, 1000);
        });      

    }    
}

function launchImageProcessing (file_name, pano_id, scene_id, html_ref_id)
{
    
    doAjaxRq(
            'POST', 
            'php-stubs/image-processing.php', 
            'file_name='+file_name+'&pano_id='+pano_id+'&scene_id='+scene_id, 
            function()       {}, 
            function(response)
            {
                response = jQuery.parseJSON(response);

               if (response.result == 'SUCCESS')  
               {                    
            	   
                    var $scene_item    = $('#'+html_ref_id);
                    var $success_result = $scene_item.find('.ok');
                    var $loader_item    = $scene_item.find('.loader-item');
                    var $thumb_pano     = $scene_item.find('.thumb-pano').children('img');
                    
                    $thumb_pano.width = 100;
                    $thumb_pano.height = 100;
                    $thumb_pano.attr('src', response.params.thumb_path);
                    
                    $scene_item.children('a.delete-item').css('display', 'block');
                    $scene_item.children('a.drag-item').css('display', 'block');
                    $scene_item.children('a.add-element').css('display', 'block');
                    
                    $scene_item.children('a.delete-item').attr('data-id', scene_id);
                    
                    $scene_item.children('a.edit-hotspots').attr('data-id', scene_id);
                    $scene_item.children('a.edit-hotspots').attr('data-thumb', 'panos/'+pano_id+'/index.tiles/thumb100x50.jpg');
                    
                    hs_unbind_all();
                    hs_bind_all();

                    $scene_item.children('.scene-remove').click(function()
                    {
                        var data         = 'scene-id='+scene_id;

                        var container   = jQuery(this).parent();

                        var params = {
                                    container  : container
                                };

                        launch_popup('.confirm-action', data, 'sceneRemove', removeCallback, params);                        

                        return false;


                        //CoreActions.sceneRemove(data, removeCallback);
                    });
                    
                    $success_result.next().remove(); // remove processing text
                    $success_result.next().remove(); // remove loading gif
                    
                    $success_result.css('display', 'inline-block');
                    $success_result.after( "<p>Upload complete!</br>Now you can add hotspots to this scene</p>" ); 

                    jQuery( "#drop-zone" ).sortable('destroy');

                    jQuery( "#drop-zone" ).sortable({
                    	scroll:true,
                    	stop: function(){
                    		verificar(true);
                    		},
                        placeholder: "pano-item-placeholder",
                        items: "> div.pano-item" ,
                        cursor: "move"
            	
                    });       
                                        
 
               }  

                
            }
        );      

      
}
