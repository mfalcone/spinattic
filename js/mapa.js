function initialize(){

	var styles = [];
	var zoom = 2;
	var markerMyLatlng = new google.maps.LatLng(document.getElementById("lat").value, document.getElementById("long").value);
	var centroMyLatlng = new google.maps.LatLng(document.getElementById("lat").value, document.getElementById("long").value);
	var likes = document.getElementById("likes").value;
	var views = document.getElementById("views").value;
	var description = document.getElementById("title").value;
	var id = document.getElementById("id").value;
	var iduser= document.getElementById("iduser").value;
	var user= document.getElementById("user").value;
	var options = {
		mapTypeId: google.maps.MapTypeId.ROADMAP
		//mapTypeIds: [ 'Styled']
		,center:centroMyLatlng
		,zoom:5 
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
	

	var image = 'images/icons/marker.png';
	//var image = '../images/icons/marker.png';
	
	var div = document.getElementById('map_canvas');
	var map = new google.maps.Map(div, options);
	var marker = new google.maps.Marker({
		position:markerMyLatlng , 
		map: map,
		animation: google.maps.Animation.DROP,
		icon: image
		//title: 'Uluru (Ayers Rock)'
	});

	var contentString = 
		'<link rel="stylesheet" type="text/css" media="screen" href="css/infomap.css" />'
            +'<div class="content-info-map">'
	    +'  <a href="#" class="thumb_map"><img src="images/tours/' + id + '.jpg"></a>'
	    +'  <a href="#" class="user">'
	    +'  	<img src="images/users/' + iduser + '.jpg" width="43" height="43">'
	    +'  </a>'
	    +'  <div class="by">'
	    +'  	<a href="#">by ' + user+ '</a>'
	    +'  </div>'
	    +'  <div class="text">'
	    +'  	<p><a href="#">' + description + '</a></p>'
	    +'  </div>'
	    +'  <div class="count">'
	    +'  	<div class="views">' + views + '</div>'
	    +'  	<!--<a href="#"  class="comments">0sdsdasd</a>-->'
	    +'  	<a href="javascript:void(0)" id="like' + id + '" class="likes" onclick="like(' + id + ');">' + likes + '</a>'
	    +'  	<br clear="all">'
	    +'  </div>'
	    +'  <div class="arrow_map"></div>'
	    +'</div>'
		;
		
		
		
		
		
	
        /*var boxText = document.createElement("div");
        boxText.style.cssText = "border: 1px solid black; margin-top: 8px; background: yellow; padding: 5px;";
        boxText.innerHTML = "City Hall, Sechelt<br>British Columbia<br>Canada";*/

        var myOptions = {
                 content: contentString
                ,disableAutoPan: false
                ,maxWidth: 0
                ,pixelOffset: new google.maps.Size(-120, -185)
                ,zIndex: null
                ,boxStyle: { 
                  background: "url('tipbox.gif') no-repeat"
                  ,opacity:1
                  ,width: "228px"
                 }
                ,closeBoxMargin: "0 0 0 0"
                ,closeBoxURL: "http://www.google.com/intl/en_us/mapfiles/close.gif"
                ,infoBoxClearance: new google.maps.Size(1, 1)
                ,isHidden: false
                ,pane: "floatPane"
                ,enableEventPropagation: false
        };
        
      
        var ib = new InfoBox(myOptions);        	

      	
	map.setOptions({styles: styles});


	google.maps.event.addListener(map, 'zoom_changed',zoom_changed);
	google.maps.event.addListener(map, 'dragend',center_changed);			

	google.maps.event.addListener(marker, 'click', function() {
	  //infowindow.open(map,marker);
          ib.open(map,marker);
      
	});
        

	function center_changed() {
		//$('.datos_mapa').fadeOut(300);
		//$('.test').text('center' + map.center);
	}
	function zoom_changed() {
		//$('.datos_mapa').fadeOut(300);
		//$('.test').text('zoom ' + map.zoom);
	}
	

	
}




