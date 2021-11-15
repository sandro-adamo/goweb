<?php

?>	 
<!DOCTYPE html>
<html>
  <head>
    <title>Simple Map</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
  </head>
  <body>
    <div id="map"></div>
    
    
<script src="/js/jquery.min.js" language="javascript"></script>
<script>

//	var ceps = [ '36030-770', '77015-290'];

	var ceps = [


		['SHOPPING ELDORADO', 18],
		['ATITUDE POINT', 19],
	
		['05058-010', 11]


		
		
		
		
		
		
	];


	
	
  var map;
  function initMap() {
	map = new google.maps.Map(document.getElementById('map'), {
	  center: {lat: -14.34546025, lng: -51.70074692},
	  zoom: 3
	});
	


	var image = 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png';		
	
	ceps.forEach(function(index,value ) {
		var valor = index[0] + ' Brasil';
		return $.ajax({
			url: "https://maps.googleapis.com/maps/api/geocode/json",
			data: {
				address: valor,
				key: 'AIzaSyCmC7alneeiljpEvewb5OHK5FTtf4RQx8U'
			},
			dataType:"json",
			async: false,
			success: function(result) {
				var registros = String(index[2]);
				var lat = result.results["0"].geometry.location.lat;
				var lng = result.results["0"].geometry.location.lng;

				if (index[2] > 230) {
					var pinColor = "FE2E2E";
				} else if (index[2] > 23 && index[2] < 1) {
					var pinColor = "FFFF00";				
				} else {
					var pinColor = "64FE2E";					
				}
				var pinImage = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" + pinColor,
					new google.maps.Size(21, 34),
					new google.maps.Point(0,0),
					new google.maps.Point(10, 34));


				var beachMarker = new google.maps.Marker({
				  position: {lat: lat, lng: lng},
				  label: {text: registros, color: 'black',fontSize: '11px'},
				  map: map,
				  icon: pinImage,
				  title: index[0]+index[1]
				});		
							
			}
			
		});

		//var lat = localizacao.results["0"].geometry.location.lat
//			console.log(lat);
	});

  }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCmC7alneeiljpEvewb5OHK5FTtf4RQx8U&callback=initMap"
async defer></script>

 
</body>
</html>