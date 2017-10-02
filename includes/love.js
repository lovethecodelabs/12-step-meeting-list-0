function closestmeetings() {
navigator.geolocation.getCurrentPosition(show)
}

jQuery( document ).ready(function() { 
    //console.log( "ready!" );
    closestmeetings();
});

function toRad(deg) {
    return deg * Math.PI / 180;
}

function codeIt(arLoc) {
    return '<td class="time">'+arLoc['time_formatted']+'</td><td class="name"><a href="'+arLoc['url']+'">'+arLoc['name']+'</a></td><td class="distance">'+arLoc['distance']+' mi</td></tr>';
}

function show(position) { 
    
    var geo = jQuery("#geolocate").val();
    //var geo = "any";
	jQuery.ajax({
		url : displayclosestmeetings.ajax_url,
		
		type : "GET",
		data: {
			action : 'display_closest_meetings',
			lat : position.coords.latitude,
			long:position.coords.longitude,
			today: geo
		},
		success : function( response ) {
			
			var json = jQuery.parseJSON(response);
			var arArSrt = json.sort(function(a, b){    
			    var x = a['time']
                var y = b['time'];
                if (x < y) {return -1;}
                if (x > y) {return 1;}
                return 0;});

			
 			    var head = '<thead><tr><th class="time">Time</th><th class="name">Meeting</th><th class="distance">Distance</th></tr></thead><tbody>'; 
 			    var tail = '</tbody>';

			jQuery('#dataxhr').html( head + codeIt(arArSrt[0]) + codeIt(arArSrt[1]) + codeIt(arArSrt[2]) + codeIt(arArSrt[3]) + tail );
			//closestadj();
		    
		}
	});

	return true;
}