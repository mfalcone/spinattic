/*
		Name: Mountain
		Description: Responsive Coming Soon 
		Version: 1.1
		Author: MountainTheme

		TABLE OF CONTENTS
		---------------------------
		 1. Loading
		 2. Scroll Reveal
		 3. Backstretch Image Background
				3.1 Backstretch Slideshow Background
		 4. Countdown
		 5. Contact form 
		 6. Ajax mailchimp
		 7. Player Youtube Controls
		 8. Google map
*/


/* ================================= */
/* :::::::::: 1. Loading ::::::::::: */
/* ================================= */

 
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
 
	} 

$(window).load(function() {

		$(".loader-icon").delay(500).fadeOut();
		$(".page-loader").delay(700).fadeOut("slow");
 
		setTimeout(function() {
		$(".logo").delay(1000).css({display: 'none'}).fadeIn(1000);
		$("h1").delay(1000).css({display: 'none'}).fadeIn(1000);
		$("p").delay(1000).css({display: 'none'}).fadeIn(1000);
		$(".countdown").delay(1000).css({display: 'none'}).fadeIn(1000);
		$(".mouse-wrapper").delay(1000).css({display: 'none'}).fadeIn(1000);
		});


});


/* ================================= */
/* :::::::: 2. Scroll Reveal ::::::: */
/* ================================= */

		 (function($) {

				'use strict';

/*        window.sr= new scrollReveal({
					reset: true,
					move: '10px',
					mobile: false
				});
*/
			})();


/* ================================= */
/* ::::::::: 3. Backstretch :::::::: */
/* ================================= */

/* Active Single Image Background  */  
	
 /*$("header").backstretch("images/background2.jpg");*/

// ==== SLIDESHOW BACKGROUND ====
// Set URLs to background images inside the array
// Each image must be on its own line, inbetween speech marks (" ") and with a comma at the end of the line
// Add / remove images by changing the number of lines below
// Variable fade = transition speed for fade animation, in milliseconds
// Variable duration = time each slide is shown for, in milliseconds
					

 /* ↓ Remove comments if you want to use the slideshow  ↓  */ 

	 $("header").backstretch([
				"http://cdn.spinattic.com/panos/3871/pano.tiles/thumb900x450.jpg",
				"http://cdn.spinattic.com/panos/4613/pano.tiles/thumb900x450.jpg",
				"http://cdn.spinattic.com/panos/4254/pano.tiles/thumb900x450.jpg",
				"http://cdn.spinattic.com/panos/2458/pano.tiles/thumb900x450.jpg",
				"http://cdn.spinattic.com/panos/564/pano.tiles/thumb900x450.jpg",
				"http://cdn.spinattic.com/panos/81/pano.tiles/thumb900x450.jpg",
				"http://cdn.spinattic.com/panos/1638/pano.tiles/thumb900x450.jpg",
				"http://cdn.spinattic.com/panos/99/pano.tiles/thumb900x450.jpg"

		],{duration: 3000, fade: 750});  


/* ================================= */
/* :::::::::: 4. Countdown ::::::::: */
/* ================================= */


		// To change date, simply edit: var endDate = "Dec 01, 2015 20:39:00";

	 $(function() {
	 var endDate = "Oct 30, 2015 15:00:00";
	$('.countdown').countdown({
					date: endDate,
					render: function(data) {
						$(this.el).html("<div>" + this.leadingZeros(data.hours, 2) + " <span>hours</span></div><div>" + this.leadingZeros(data.min, 2) + " <span>minutes</span></div><div>" + this.leadingZeros(data.sec, 2) + " <span>seconds</span></div>");
					}
				});
	 });


