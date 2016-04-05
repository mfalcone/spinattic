
var speed_language = 250;
var map_tour = false;
var embed_tour = false;
var share_tour = false;
var heightScreen;
var navMobile = false;
var positionScroll;
var register_mobile = false;
var anchoImg;
var altoImg;
var	positionIsVertical;
var positionImg;
var anchoImgOriginal;
var altoImgOriginal;
$(document).ready(function(){

	$(".text-message .bt_close").click(function(){
		$(".text-message").hide();
	})


	$("html:not(:animated),body:not(:animated)").animate({ scrollTop: 1}, 0 );




	$(window).resize(function(){
		heightScreen = $(window).height();
	});
	$(window).scroll(function(){
		positionScroll = $(window).scrollTop();
	});
	
	
	//inicialización de vertical o horizontal
	if(window.orientation == 0 || window.orientation == 180  ){
		positionIsVertical = true;
		calculo_vertical();
	}
	else if(window.orientation == -90 || window.orientation == 90){
		positionIsVertical = false;	
		calculo_horizontal();
	}
	
	
	
	updateOrientation();
	window.onorientationchange = updateOrientation;

	heightScreen = $(window).height();

	/*menu accordion*/
	$('.menu').accordion({ 
		active: false
		,header : "a.accordion"
		,collapsible:true
		,heightStyle: "content"
		,activate: function( event, ui ) {
			/*if( $(ui.newHeader).parent().attr('class') == 'content_accorion_items')*/
			$(ui.newHeader).parent().addClass('active');
			$(ui.oldHeader).parent().removeClass('active');			
		}	
	});
	

	/*btn language*/
	$('.language_btn').hover(
		function(){
			$('.select_language').stop().fadeIn( speed_language);
		},
		function(){
			
		}
	);

	$('.select_language').on("mouseleave",function(){
		$('.select_language').stop().fadeOut( speed_language);
	});


	/*btn language*/
	$('.search input').hover(
		function(){
			$('.predictive').stop().fadeIn(250, "easeOutCirc")
		},
		function(){
			$('.predictive').stop().fadeOut(250, "easeOutCirc")
		}
	);

	/*btn_map*/
	$(".btn_map").click(function(){
		if(map_tour == false){
			$(this).addClass('active');
			$('.content_map').stop().animate({'width':'100%'}, 250);
			$('.content_tour').stop().animate({'left':'100%'}, 250, function(){
				map_tour = true;
				//initialize();
			});
		}else{
			$(this).removeClass('active');
			$('.content_map').stop().animate({'width':'0'}, 250);
			$('.content_tour').stop().animate({'left':'0'}, 250, function(){
				map_tour = false;
				//$('.content_map').empty();	
			});
		}
		return false;
	});
	
	/*btn embed*/
	$(".btn_embed").click(function(){
		if(embed_tour == false){
			$(this).addClass('active');
			$('.embed').stop().fadeIn(350, function(){
				embed_tour = true;
			});

			$('.btn_share').removeClass('active');
			$('.share').stop().animate({'height':'0'}, 400, "easeOutCirc",function(){
				$('.share').stop().fadeOut(0,function(){
					share_tour = false;
				});	
			});
		}
		else{
			$(this).removeClass('active');
			$('.embed').stop().fadeOut(350,function(){
				embed_tour = false;
			});
		}
		return false;
	});

	/*btn share*/
	$(".btn_share").click(function(){
		if(share_tour == false){
			$(this).addClass('active');
			$('.share').stop().fadeIn(0, function(){
				$('.share').stop().animate({'height':'175px'}, 400, "easeOutCirc", function(){
					share_tour = true;
				});	
			});

			$('.btn_embed').removeClass('active');
			$('.embed').stop().fadeOut(250,function(){
				embed_tour = false;
			});
		}
		else{
			$(this).removeClass('active');
			$('.share').stop().animate({'height':'0'}, 400, "easeOutCirc",function(){
				$('.share').stop().fadeOut(0,function(){
					share_tour = false;
				});	
			});
		}
		return false;
	});
	
	
	/*nav mobile*/
	$('.nav_btn_mobile').click(function(){
		if(navMobile == false){
			$('.nav_btn_mobile').stop().addClass('nav_btn_mobile_active');
			positionScroll = $(window).scrollTop();
			$('.wrapper').fadeOut(0);
			$('.nav_panel').stop().animate({'left':'0'},250, "easeOutCirc", function(){
				navMobile = true;
			});
		}
		else{
			$('.nav_btn_mobile').stop().removeClass('nav_btn_mobile_active');
			$('.wrapper').fadeIn(0);
			$("html:not(:animated),body:not(:animated)").animate({ scrollTop: positionScroll}, 0 );
			$('.nav_panel').stop().animate({'left':'-100%'},250, "easeOutCirc", function(){
				navMobile = false;
			});
		}
		return false;
	
	
	});
	
	
	
	
	/*login_btn_mobile*/
	
	$('.login_btn_mobile').click(function(){
		if( register_mobile == false){
			$('.overlay_mobile').stop().fadeIn(250);
			register_mobile = true;
		}
		return false;
	});
	
	$('.closed_register_mobile').click(function(){
		$('.overlay_mobile').stop().fadeOut(250);
		register_mobile = false;	
		return false;
	});
	
	
});

function updateOrientation(){
	if((window.orientation == 0 || window.orientation == 180) && !positionIsVertical){
		calculo_vertical();

	}
    else if((window.orientation == -90 || window.orientation == 90) && positionIsVertical){
		calculo_horizontal();
	}
}

function calculo_vertical(){
		anchoImgOriginal = $('.wrapper .post-highlight .thumb img').attr('width');
		altoImgOriginal = $('.wrapper .post-highlight .thumb img').attr('height');
		
		$('.wrapper .post-highlight .thumb img').attr('width','');
		$('.wrapper .post-highlight .thumb img').attr('height','');

		anchoImg = $('.wrapper .post-highlight .thumb img').width();

		altoImg = (anchoImg * altoImgOriginal) / anchoImgOriginal;
		$('.wrapper .post-highlight .thumb .play , .wrapper .post-highlight .thumb').css({ 'height': altoImg +' !important' });

		$('.wrapper .post-highlight').children('.user').css({ 'top': altoImg-20 });
		$('.wrapper .post-highlight .thumb img').css({'margin-top':0, 'height': altoImg +' !important'});
		if(positionScroll <= 1){
			$("html:not(:animated),body:not(:animated)").animate({ scrollTop: 1}, 0 );
		}
		
		positionIsVertical = true;
}


function calculo_horizontal(){
		heightScreen = $(window).height();
		altoContentImgHome = heightScreen-30;
		$('.post-highlight .thumb').css({'height':altoContentImgHome});
		$('.wrapper .post-highlight .thumb .play , .wrapper .post-highlight .thumb').css({ 'height': altoContentImgHome });
		$('.wrapper .post-highlight').children('.user').css({ 'top': altoContentImgHome-20 });
		
		altoImg = $('.wrapper .post-highlight .thumb img').height();
		positionImg = (altoContentImgHome - altoImg)/2 ;
		
		$('.wrapper .post-highlight .thumb img').css({'margin-top':positionImg});
		if(positionScroll <= 1){
			$("html:not(:animated),body:not(:animated)").animate({ scrollTop: 1}, 0 );
		}

		positionIsVertical = false;

}


	/*
	$('.wrapper .post-highlight .thumb').css({'height':heightScreen*0.7,'overflow':'hidden', 'position':'relative'});
	
	altoImgHome = $('.wrapper .post-highlight .thumb img').height();
	anchoImgHome = $('.wrapper .post-highlight .thumb img').width();
	
	alto
	
	$('.wrapper .post-highlight .thumb img').css({'position':'absolute','width':'100%','left':'0','top':-((heightScreen*0.7) - altoImgHome)/2});*/

/*Create notification*/
function notificate(target, type){
	//alert("ajax_notice.php?type="+type+"&t="+target+"&txt="+text);
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
		 //alert(xmlhttp.responseText); /*do something if you want*/
	  }
	}
	xmlhttp.open("GET","../ajax_notice.php?type="+type+"&t="+target,true);
	xmlhttp.send();
	return false;		
}