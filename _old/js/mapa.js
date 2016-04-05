function initialize(){

	var styles = [];
	var zoom = 2;
	var markerMyLatlng = new google.maps.LatLng(-33.077507080414875, -61.796097729217536);
	var centroMyLatlng = new google.maps.LatLng(-33.212326150925975, -59.79727265597535);
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
		'<style>'
		+'	.content-info-map 				{ -webkit-border-radius:4px; -moz-border-radius:4px; border-radius:4px;'
		+'									  border:solid 1px #d4d4d4; overflow:visible; width:230px; height:129px; position:absolute; background-color:#ffffff; text-align:center; }'
		+'	.content-info-map .thumb_map 	{ width:227px; height:60px; display:block; margin-top:1px;}'
	    +'   .content-info-map img 			{ border:none;}'
		+'	.content-info-map .text			{ padding:0 8px;   }'
	    +'  .content-info-map .text p		{ font-size:14px; }'
	    +'  .content-info-map .text p a     { color:#444;}'
	    +'  .content-info-map .text p a:hover     { color:#444;}'
	    +'  .content-info-map .user			{ position:absolute; top:37px; left:5px; width:43px; height:43px; border:solid 1px white; display:block;}'
	    +'  .content-info-map .by			{ margin-left:59px; margin-top:-5px; text-align:left; }'
	    +'  .content-info-map .by a			{ color:#457ec1; font-size:11px;  }'
	    +'  .content-info-map .by a:hover	{ color:#255185;}'
	    +'  .content-info-map .count 			{ margin-right:10px; }'
	    +'  .content-info-map .count a 			{ display:block; overflow:hidden; background-position:0 0; background-repeat:no-repeat; padding:0 0 0 18px; font-size:10px; color:#777; float:right; min-width:16px; height:14px; overflow:hidden; margin:0 2px;}'
	    +'  .content-info-map .count .likes		{ background-image:url(images/icons/likes.png);}'
	    +'  .content-info-map .count .comments	{ background-image:url(images/icons/comments.png); background-position:0 1px;}'
	    +'  .content-info-map .count .views		{ background-image:url(images/icons/views.png); display:block; overflow:hidden; background-position:0 0; background-repeat:no-repeat; padding:0 0 0 18px; font-size:10px; color:#777; float:right; min-width:16px; height:14px; overflow:hidden; margin:0 2px;}'
	    +'  .content-info-map .arrow_map 		{ background:url(images/bg/arrow_map.png); width:11px; height:11px; position:absolute; bottom:-11px; left:50%;}'
	    +'</style>'
        +'<div class="content-info-map">'
	    +'  <a href="#" class="thumb_map"><img src="images/ejemplo/img_map.jpg"></a>'
	    +'  <a href="#" class="user">'
	    +'  	<img src="images/ejemplo/user.jpg">'
	    +'  </a>'
	    +'  <div class="by">'
	    +'  	<a href="#">by Derrick Clark</a>'
	    +'  </div>'
	    +'  <div class="text">'
	    +'  	<p><a href="#">Lorem ipsum dolor sit amet...</a></p>'
	    +'  </div>'
	    +'  <div class="count">'
	    +'  	<div class="views">10</div>'
	    +'  	<a href="#"  class="comments">0sdsdasd</a>'
	    +'  	<a href="#" class="likes">5254</a>'
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




