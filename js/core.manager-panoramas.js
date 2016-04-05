jQuery(document).ready(function(){

	/*----------Manage Panoramas----------------*/
	
	/*Show tours of a selected pano and actions over them*/
	jQuery('.included').click(function(event){
		event.preventDefault();
		nueva_cantidad = $(this).attr('data-cantidad');
		elid = $(this).attr('href');
		filename = $(this).attr('data-name');
	    $.ajax({
	        url : 'ajax_get_pano_tours.php',
	        type: 'POST',
	        data: 'id='+elid+"&fn="+filename,
	        cache : false,
	        success : function(response){
	            $('.panocollection').html(response);
	            $('.panocollection').show();
	        	jQuery('.closed').click(function(){
	        		hide_popup();
	        	});
	        	$('.select_checkbox').click(function(){
	        		$(this).closest('.pano-item').toggleClass("disable");
	        	});
	        	
	        	
	        	/*delete pano from tour (batch)*/
	        	
	        	$(".remove_selected_panos").click(function(event){
	        		event.preventDefault();
	        		if($('input[name="sel[]"]:checked').length > 0){
	        			confirmMessage('Manage PANOS',"Are you sure you want to remove this scene from the selected tour?", function(){
	        				start_loader();
		        			$('input[name="sel[]"]').each(function() {
		        				//alert($(this).attr('data-scene'));
		        				//alert($(this).is(':checked'));
		        				if($(this).is(':checked')){
		        					elcheckbox = $(this);
		        					//alert($(this).attr('data-scene'));
		        					//alert($(this).attr('data-tour'));
	
			        			    $.ajax({
			        			        url : 'php-stubs/scenes.php?action=remove',
			        			        type: 'POST',
			        			        async: false,
			        			        data: 'source=manage_panoramas&scene-id='+elcheckbox.attr('data-scene'),
			        			        cache : false,
			        			        success : function(response){
			        			        	nueva_cantidad = nueva_cantidad - 1;
			        			        }
			        			    });
		        				}
							});
				        	//adapto el boton "included" con la nueva cantidad / formato
		        			me = $('.included[href$="'+elid+'"]');
		        			if(nueva_cantidad == 0){

		        				me.unbind();
		        				me.text('Not Used');
		        				me.toggleClass("included");
		        				me.toggleClass("blue-buttom grey-button");
		        				me.attr('href','#');
		        				
		        			}else{
		        				me.text('Included in '+nueva_cantidad+' tour(s)');
		        				me.attr('data-cantidad', nueva_cantidad);
		        			}
		        			stop_loader();
		        		});
	        		}else{	        		
	        			showMessage('Error', 'Your must select at least 1 scene to remove');
	        			$(".panocollection").show();
	        		}
	        	});	
	        }
	    });
	});
	
	/*End Show tours of scene*/
	
	
	/*Delete pano*/
	
	jQuery('.pano-remove').click(function(event){
		//alert($(this).attr('data-id'));
		
		elid = $(this).attr('data-id');
		contenedor = $(this).closest('.pano-item');
		
		if($('.included[href$="'+elid+'"]').length > 0){
			showMessage('Warning', "This panorama file is being used in one or more Virtual Tours. First remove this panorama from all the virtual tours to enable the removal of the file.");
		}else{
			confirmMessage('Manage PANOS',"Are you sure you want to completely delete this panorama file? You'll need to upload the file again if you want to use it in tours.", function(){
				start_loader();
			    $.ajax({
			        url : 'php-stubs/scenes.php?action=remove',
			        type: 'POST',
			        async: false,
			        data: 'source=manage_panoramas&pano-id='+elid,
			        cache : false,
			        success : function(response){
			        	contenedor.hide();
			        	if ($(".pano-item:visible").length == 0){
			        		$(".message_box").show();
			        	}
			        }
			    });			
			    stop_loader();
			});
		}
	});
	
	/*End delete Pano*/
	
	/*Batch delete pano*/

	$('#panos-batch-actions').change(function(event){
		var option  = jQuery(this).val();
		if(option == 'remove' && $('input[name="ids[]"]:checked').length > 0){
			
			/*chequeo si tienen tours asignados*/
			algunoConTour = 0;
			
			$('input[name="ids[]"]').each(function() {
				elid = $(this).val();
				if($(this).is(':checked') && $('.included[href$="'+elid+'"]').length > 0){
					algunoConTour = 1;
				}
			});
			
			if(algunoConTour == 1){
				showMessage('Error', 'Some panorama(s) file(s) are being used in one or more Virtual Tours. First remove all panoramas from all the virtual tours to enable the removal of the file.');
			}else{
			
				confirmMessage('Manage PANOS','All checked panos will be deleted. <br><br>Are you sure?', function(){
					start_loader();
					stop_loader();
					$('input[name="ids[]"]').each(function() {
						if($(this).is(':checked')){
							
							elid = $(this).val();
							contenedor = $(this).closest('.pano-item');
							
						    $.ajax({
						        url : 'php-stubs/scenes.php?action=remove',
						        type: 'POST',
						        async: false,
						        data: 'source=manage_panoramas&pano-id='+elid,
						        cache : false,
						        success : function(response){
						        	contenedor.hide();
						        	if ($(".pano-item:visible").length == 0){
						        		$(".message_box").show();
						        	}
						        }
						    });		
						}
					});					
				});
			}
		}
		
		if(option == 'create'){
			createtourwithselection();
		}
		
		$("#panos-batch-actions").val($("#panos-batch-actions option:first").val());
	});
	  	
	
	
	/*End Batch delete pano*/
	
	/*Create tour with selection*/
	
	$('.create-tour-with-selection').click(function(){
		createtourwithselection();
	});
	
	function createtourwithselection(){
		if($('input[name="ids[]"]:checked').length > 0){

			/*Creo el tour y obtengo el id*/
		    $.ajax({
		        url : 'ultour.php',
		        type: 'POST',
		        async: false,
		        data: 'autocreate=true',
		        cache : false,
		        success : function(response){
		            parsedObj = jQuery.parseJSON(response);
		            gTour_id    = parsedObj.params.tour_id;  
		            //xml_version = parsedObj.params.xml_version;
		            
		            mixpanel.track("Create New Tour");
		            
		            /*Creo las scenes*/
					$('input[name="ids[]"]').each(function() {
						elid = $(this).val();
						if($(this).is(':checked')){		            
						    $.ajax({
						        url : 'ajax_get_pano_from_collection.php?idtour='+gTour_id+'&panoid='+elid,
						        type: 'GET',
						        async: false,
						        cache : false,
						        success : function(response){
						        	
						        	window.location.href = "edit_virtual_tour.php?d=1&id="+gTour_id;
						        }
						    });		
						}
					});		
		        }
		    });
	    }else{
			showMessage('Error', 'Your must select at least 1 pano to add');
		}
	}
	
	/*End Create tour with selection*/
	

   $('#tours-check-all').click(function(){
        checkAll();
    });

   $(".paginator a").click(function(e){
   	  changePage(e);
   })

   $("#epp").change(function(e){
   	  changePage(e);
   })

	
});

