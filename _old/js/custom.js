
var speed_language = 250;

var margenInferiorTour = 228;
var map_tour = false;
var embed_tour = false;
var share_tour = false;
var heightScreen;
var navMobile = false;
var popUp = false;

$(document).ready(function(){
	        
	$(window).resize(function(){
		heightScreen = $(window).height();
		setupBlocks();
		tourHight();
                /*menu accordion*/
                $('.menu').accordion('destroy');
                
                $('.menu').accordion({ 
                        active: false
                        ,header : "a.browse"
                        ,collapsible:true
						,heightStyle: "content"
                        ,activate: function( event, ui ) {
                                /*if( $(ui.newHeader).parent().attr('class') == 'content_accorion_items')*/
                                $(ui.newHeader).parent().addClass('active');
                                $(ui.oldHeader).parent().removeClass('active');			
                        }	
                });
	});

	heightScreen = $(window).height();
	tourHight();

	/*menu accordion*/
	$('.menu').accordion({ 
		active: false
		,header : "a.browse"
		,collapsible:true
		,heightStyle: "content"
		,activate: function( event, ui ) {
			/*if( $(ui.newHeader).parent().attr('class') == 'content_accorion_items')*/
			$(ui.newHeader).parent().addClass('active');
			$(ui.oldHeader).parent().removeClass('active');			
		}	
	});
	

	/*login_btn*/
	$('.login').click(function(){
		if( popUp == false){
			$('.overlay_login').stop().fadeIn(250);
			popUp = true;
		}
		return false;
	});
	
	/*register_btn*/
	$('.register').click(function(){
		if( popUp == false){
			$('.overlay_register').stop().fadeIn(250);
			popUp = true;
		}
		return false;
	});
	
	$('.closed').click(function(){
		$('.overlay').stop().fadeOut(250);
		popUp = false;	
		$(".ballon").hide();
		return false;

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
	$('.search').hover(
		function(){
			$('.predictive').stop().fadeIn(250, "easeOutCirc")
		},
		function(){
			$('.predictive').stop().fadeOut(250, "easeOutCirc")
		}
	);


	$('.loading').click(function(){
		$(this).fadeOut(250);
	});


	
	/*btn_map*/
	$(".btn_map").click(function(){
		if(map_tour == false){
			$(this).addClass('active');
			$('.content_map').stop().animate({'width':'500px'}, 250);
			$('.content_tour').stop().animate({'left':'544px'}, 250, function(){
				map_tour = true;
				initialize();
			});
		}else{
			$(this).removeClass('active');
			$('.content_map').stop().animate({'width':'0px'}, 250);
			$('.content_tour').stop().animate({'left':'42px'}, 250, function(){
				map_tour = false;
				$('.content_map').empty();	
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
			$('.share').stop().animate({'width':'0'}, 400, "easeOutCirc",function(){
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
				$('.share').stop().animate({'width':'175px'}, 400, "easeOutCirc", function(){
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
			$('.share').stop().animate({'width':'0'}, 400, "easeOutCirc",function(){
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
			$('.wrapper').stop().animate({'left':'255px'},250, "easeOutCirc");
			$('.header').stop().animate({'left':'255px'},250, "easeOutCirc");
			$('.search').stop().animate({'left':'0'},250, "easeOutCirc");
			$('.nav_btn_mobile').stop().addClass('nav_btn_mobile_active');
			$('.nav').stop().animate({'left':'0'},250, "easeOutCirc", function(){
				navMobile = true;
			});
		}
		else{
			$('.wrapper').stop().animate({'left':'0'},250, "easeOutCirc");
			$('.header').stop().animate({'left':'0'},250, "easeOutCirc");
			$('.search').stop().animate({'left':'-255px'},250, "easeOutCirc");
			$('.nav_btn_mobile').stop().removeClass('nav_btn_mobile_active');
			$('.nav').stop().animate({'left':'-255px'},250, "easeOutCirc", function(){
				navMobile = false;
			});
		}

	});
	
	setTimeout ( function () {
           setupBlocks();
        }, 200);
	
	
	//SUGERENCIA PARA IMPLEMENTACION DE BUSQUEDA PREDICTIVA
	/* $( "#search-input" ).autocomplete(	 
	 {
		source: function( request, response ) 
		{
			$.ajax({
				url: "http://ws.geonames.org/searchJSON",
				dataType: "jsonp",
				data: {
				featureClass: "P",
				style: "full",
				maxRows: 12,
				name_startsWith: request.term
				},
				success: function( data ) {
					response( $.map( data.geonames, function( item ) {
						return {
							label: item.name + (item.adminName1 ? ", " + item.adminName1 : "") + ", " + item.countryName,
							value: item.name
						}
					}));
				}
			});
		},
		minLength: 2,
		select: function( event, ui ) {
			alert( ui.item ?
			"Selected: " + ui.item.label :
			"Nothing selected, input was " + this.value);
		},
		open: function() {
			$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
		},
		close: function() {
			$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
		}
	});
*/
	
});

function tourHight(){
	$('.tour').stop().css({ 'height': heightScreen - margenInferiorTour });
}




/*post*/
var colCount = 0;
var colWidth = 0;
var margin = 6;
var windowWidth = 0;
var blocks = [];
var destacado;
var colsDestacado = 3;
var altoDestacado;
var anchoDestacado;

function setupBlocks() {

	anchoDestacado = $('.post-highlight').width();
	//var altoDestacado = $('.post-highlight').outerHeight(true)+(margin*2);        
        altoDestacado = $('.post-highlight').outerHeight(true);
        
	
	windowWidth = $('.wrapper').width() - 17;
	colWidth = $('.post').outerWidth();
	blocks = [];
	colCount = Math.floor(windowWidth/(colWidth+margin*2));
//console.log(colCount)	;
	var colsDestacado = Math.floor(anchoDestacado/(colWidth));

	
	for(var i=0;i<colCount;i++)
	{
		if( i < colsDestacado)
			blocks.push(altoDestacado+margin);
		else
			blocks.push(margin);	
           // console.log(i + ': ' + blocks[i])	;	
	}
	
	positionBlocks();
	
}

function positionBlocks() {
	$('.post').each(function(){
		var min = Array.min(blocks);

		var index = $.inArray(min, blocks);
			//alert(min+' '+blocks+' '+index)	;
		var leftPos;
		//altoDestacado = $('.post-highlight').outerHeight(true)+(margin*2);
		//var anchoDestacado = $('.post-highlight').width();
		var colsDestacado = Math.floor(anchoDestacado/(colWidth));

//		if( min < altoDestacado){
//			leftPos = margin+((colsDestacado)*(colWidth+margin));
//		}
//		else{
//			leftPos = margin+((index)*(colWidth+margin));			
//		}

	leftPos = margin+((index)*(colWidth+margin));	
                
		$(this).stop().animate({
				'left':leftPos+'px'
				,'top':min+'px'
				,'opacity': '1'
			}, 500, "easeInOutCirc", function(){
			
		});
		/*
		if( index < colsDestacado && min < altoDestacado)
		{
			//blocks[index] = altoDestacado+min+$(this).outerHeight()+margin;
			blocks[index] = altoDestacado+$(this).outerHeight()+margin;
		}
		else
		{
			blocks[index] = min+$(this).outerHeight()+margin;
		}
		*/
		blocks[index] = min + $(this).outerHeight(true);
	});	
}

Array.min = function(arr) {
    return Math.min.apply(Math, arr);
};

////////////////////////////////////////////////////////////////////////////////////