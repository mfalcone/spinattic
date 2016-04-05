var hoverOutTimer = null; 
 
jQuery(document).ready(function($) { 
	'use strict';
	$(".menulink a").click(function() { 
	  	jQuery("#menu").stop(true, true).slideToggle('fast');
	});
	resize_intro(); 
	jQuery(".hero").stop(true, true).fadeIn('fast');
	jQuery(".form-box").stop(true, true).fadeIn('fast');
	jQuery(".shadow").stop(true, true).fadeIn('fast');

	if(jQuery(window).height()>420 && jQuery(window).width()>769 ){
	var height = (jQuery(window).height()-86)*1;
	jQuery('.container.container-info-iframe').height(height);
	}
});

jQuery(window).resize(function () { 
	'use strict';
	resize_intro(); 

	if(jQuery(window).height()>420 && jQuery(window).width()>769 ){
	var height = (jQuery(window).height()-86)*1;
	jQuery('.container.container-info-iframe').height(height);

	}

});

function resize_intro(){
	if(jQuery(window).width() > 720 ) {  
		page_height =  (((jQuery(window).height()) - jQuery('#header').height())) - 50;
		hero_margin = ((page_height - jQuery('.hero.hero-narrrow').height())-50) / 2;
		jQuery(".hero.hero-narrrow").css('margin-top',hero_margin+'px');  
	
	} 
}
