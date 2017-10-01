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

function myDist(lat1,lon1,lat2,lon2) { // http://www.movable-type.co.uk/scripts/latlong.html
    var R = 6371000; // metres (mean radius)
    var ph1 = toRad(lat1);
    var ph2 = toRad(lat2);
    var chPh = toRad(lat2)-toRad(lat1);
    var chLm = toRad(lon2)-toRad(lon1);

    var a = Math.sin(chPh/2) * Math.sin(chPh/2) +
        Math.cos(ph1) * Math.cos(ph2) *
        Math.sin(chLm/2) * Math.sin(chLm/2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));

    var d = R * c / 1000;  // in Km
    d = d / 1.60934;
    
    return parseFloat(d).toPrecision(2);  // in miles
}

function codeIt(arLoc) {
    return '<td class="time">'+arLoc[2]+'</td><td class="name"><a href="'+arLoc[5].replace(/\\/g)+'">'+arLoc[1]+'</a></td><td class="distance">'+myDist(arLoc[7],arLoc[8],arLoc[9],arLoc[10])+' mi</td></tr>';
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
			
			var arLoc0 = json[0].split(';'); // dist
			var arLoc1 = json[1].split(';'); // name
			var arLoc2 = json[2].split(';'); // time
			var arLoc3 = json[3].split(';'); // addr
			var arLoc4 = json[4].split(';'); // url; 7 - meetLat; 8-meetLong;    9-yourLat; 10 - yourLong 
			
			    
			    var txt0 = codeIt(arLoc0);
			    var txt1 = codeIt(arLoc1);
			    var txt2 = codeIt(arLoc2);
			    var txt3 = codeIt(arLoc3);
			    var txt4 = codeIt(arLoc4);
 			    var head = '<thead><tr><th class="time">Time</th><th class="name">Meeting</th><th class="distance">Distance</th></tr></thead><tbody>'; 
 			    var tail = '</tbody>';

			jQuery('#dataxhr').html( head + txt0 + txt1 + txt2 + txt3 + tail );
			//closestadj();
		    
		}
	});

	return true;
}