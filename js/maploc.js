		var image 	= 'images/icons/marker.png';
		var geocoder;
		var infowindow = new google.maps.InfoWindow();
		var marker;    
        var map;
        var markersArray = [];
        
		geocoder = new google.maps.Geocoder();
		var latlng = new google.maps.LatLng(40.730885,-73.997383);
		var mapOptions = {
		  zoom: 8,
		  center: latlng,
		  mapTypeId: 'roadmap'
		}
		if(document.getElementById('map-canvas')){
		map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
		  }
		           

        function initMap(ind)
        {
            var latlng = new google.maps.LatLng(50, 0);
            var myOptions = {
                zoom: 1,
                center: latlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

            // add a click event handler to the map object
            google.maps.event.addListener(map, "click", function(event)
            {
                // place a marker
                placeMarker(event.latLng);


                // display the lat/lng in your form's lat/lng fields
                document.getElementById("latFld").value = event.latLng.lat();
                document.getElementById("lngFld").value = event.latLng.lng();
                codeLatLng();
            });

        }
        function placeMarker(location) {

            // first remove all markers if there are any
            deleteOverlays();

            var marker = new google.maps.Marker({
                position: location, 
                optimized: false,
                map: map
            });
            
    		google.maps.event.addListener(marker, 'click', function() {
    			  //para eliminar con click en el marker;
    		  		deleteOverlays();
    		        document.getElementById("latFld").value = "";
    		        document.getElementById("lngFld").value = "";
    		        verificar(true);
    		  	      
    		  		});             

            // add marker in markers array
            markersArray.push(marker);
             

            //map.setCenter(location);
        }

        // Deletes all markers in the array by removing references to them
        function deleteOverlays() {
			if(marker){
				marker.setMap(null);
			}
            if (markersArray) {
                for (i in markersArray) {
                    markersArray[i].setMap(null);
                }
            markersArray.length = 0;
            }
        }
        
        


		function codeLatLng() {
		  var thelocation = '';
		  var lat = parseFloat(document.getElementById('latFld').value);
		  var lng = parseFloat(document.getElementById('lngFld').value);
		  var latlng = new google.maps.LatLng(lat, lng);
		  geocoder.geocode({'latLng': latlng}, function(results, status) {
		    if (status == google.maps.GeocoderStatus.OK) {
		      if (results[1]) {
		       // map.setZoom(11);
		        marker = new google.maps.Marker({
		            position: latlng,
		            map: map
		        });
		        infowindow.setContent(results[1].formatted_address);
		        infowindow.open(map, marker);

				for (var ind=7;ind>=0;ind--)
				{
					if(typeof results[ind] != 'undefined'){
						parte = results[ind].formatted_address.replace(' - ',',').split(",");
						for(var i in parte){
//							alert (parte[i]);
							if(thelocation.indexOf(parte[i]) == -1){
								if(thelocation == ''){
									thelocation = parte[i] + thelocation;
								}else{
									thelocation = parte[i] + ', ' + thelocation;
								}
//								alert (thelocation);
							}
						}
					}
				}
				
		        document.getElementById('location').value = thelocation;
		        verificar(true);

		      } else {
		        alert('No location results found. Please try again');
		      }
		    } else {
		      alert('Geocoder failed due to: ' + status + '. Please try again');
		    }
		  });
		return false;}


function addMarker(lat, lng)
{

		
	myOptions = {
		disableAutoPan: false
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
		,enableEventPropagation: true
	};
        

	var marker = new google.maps.Marker({
		position: new google.maps.LatLng(lat, lng) , 
		map: map,
		animation: google.maps.Animation.DROP,

	});     
        
		google.maps.event.addListener(marker, 'click', function() {
	  //para eliminar con click en el marker;
  		deleteOverlays();
        document.getElementById("latFld").value = "";
        document.getElementById("lngFld").value = "";
        verificar(true);
  	      
  		});             


        markersArray.push(marker);

}
