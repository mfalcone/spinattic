var styles 	= [];
var zoom 	= 2;
var image_path 	= '../images/icons/gmap/';


var div; 	
var map;
var options;
var contentString;
var myOptions;
var markers= new Array();


function initialize(thezoom,y,x)
{
	div = document.getElementById('map_canvas');

	options = {
		mapTypeId: google.maps.MapTypeId.HYBRID 
		//mapTypeIds: [ 'Styled']
		,center: new google.maps.LatLng(y,x)
		,zoom:thezoom 
		,disableDefaultUI: false
		,scrollwheel: false	
		,panControl				:	true
		,streetViewControl		:	true
		,scaleControl			: 	true
		,zoomControl			: 	true
		,backgroundColor  		: 	'#fff'
		/*  ,zoomControlOptions		:	{	style: google.maps.ZoomControlStyle.SMALL,
					position: google.maps.ControlPosition.TOP_RIGHT}*/
	};

	map = new google.maps.Map(div, options);
      	
	map.setOptions({styles: styles});

        google.maps.event.addListener(map, 'click', function() 
        {	  
            hideAllMarkers();      
	}); 

	//google.maps.event.addListener(map, 'zoom_changed',zoom_changed);
	//google.maps.event.addListener(map, 'dragend',center_changed);	
}

function hideAllMarkers()
{
    jQuery(markers).each(function()
    {
        if(this.ib.isOpen)       
        {    
            this.ib.close();
            this.ib.isOpen = false;
        }
    });
}



function addMarker(id, lat, lng, username, user_pic, img, desc, views, likes, comments, opened, allow_votes, newimage)
{
	image 	= 'marker.png';
	contentString = 
	     '<link rel="stylesheet" type="text/css" media="screen" href="../css/infomap.css" />'
        +'<div class="content-info-map">'
	    +'  <a href="../tours/'+id+'" class="thumb_map"><img width="232" src="'+img+'"></a>'
	    +'  <a href="#" class="user">'
	    +'  	<img src="../'+user_pic+'" width="43" height="43">'
	    +'  </a>'
	    +'  <div class="by">'
	    +'  	<a href="#">by '+username+'</a>'
	    +'  </div>'
	    +'  <div class="text">'
	    +'  	<p><a href="../tours/'+id+'">'+desc+'</a></p>'
	    +'  </div>'
	    +'  <div class="count">'
	    +'  	<div class="views">'+views+'</div>'
	    +'  	<!--<a href="javascript:void(0)"  class="comments">'+comments+'</a>-->'            
	    +          ((allow_votes == 'on')? '<a href="javascript:void(0)" id="like" class="like'+id+' likes">'+likes+'</a>' : '')
	    +'  	<br clear="all">'
	    +'  </div>'
	    +'  <div class="arrow_map"></div>'
	    +'</div>'
		;
	
	cant_lines =  desc.length / 30; 
	x_offset = -200 - (15 * cant_lines);	
		
	myOptions = {
		 content: contentString
		,disableAutoPan: false
		,maxWidth: 0
		//,pixelOffset: new google.maps.Size(-125, -215)
		,pixelOffset: new google.maps.Size(-125, x_offset)
		,zIndex: null
		,boxStyle: { 
		  background: "url('../images/tipbox.gif') no-repeat"
		  /*background: "url('http://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobox/examples/tipbox.gif') no-repeat"*/
		  ,opacity:1
		  ,width: "228px"
		 }
		,closeBoxMargin: "0 0 0 0"
		,closeBoxURL: "http://www.google.com/intl/en_us/mapfiles/close.gif"
		,infoBoxClearance: new google.maps.Size(1, 1)
		,isHidden: false
		,pane: "floatPane"
		,enableEventPropagation: true
	};
        
	var ib = new InfoBox(myOptions);   
	
	if(newimage != ''){
		image = newimage; 
	}

	var marker = new google.maps.Marker({
		position: new google.maps.LatLng(lat, lng) , 
		map: map,
		animation: google.maps.Animation.DROP,
		icon: image_path + image
		//title: 'Uluru (Ayers Rock)'
	});     
        
        ib.isOpen = false;
        marker.ib = ib;

	google.maps.event.addListener(marker, 'click', function() {
	  //infowindow.open(map,marker);
          if(marker.ib.isOpen)       
          {    
              marker.ib.close();
              marker.ib.isOpen = false;
          }
          else
          {   
              hideAllMarkers();   
              
              marker.ib.open(map, marker);
              marker.ib.isOpen = true;
          }    
          
        
      
	}); 

        markers.push(marker);

	if(opened)
        {            
              marker.ib.open(map, marker);
              marker.ib.isOpen = true;
        }  
}

function center_changed() {
//$('.datos_mapa').fadeOut(300);
//$('.test').text('center' + map.center);
}
function zoom_changed() {
//$('.datos_mapa').fadeOut(300);
//$('.test').text('zoom ' + map.zoom);
}






