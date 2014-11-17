$ = jQuery;
$.noConflict();

var pais;
jQuery(document).ready(function(){
	var distancia;
	jQuery('#intro a').click(function(e){
		var ctr = jQuery(this).data('ctr');
		e.preventDefault();

		if(ctr == '01'){
			jQuery('.USA').remove();
		}else{
			jQuery('.UK').remove();
		}

		setPlaces(null);

		jQuery('#products img').attr('src', base_url+'img/'+ctr+'/products_healthy.png');
		jQuery('#home div').css({'background':'url('+base_url+'img/'+ctr+'/products.png) no-repeat center center'});

		jQuery('html, body').animate({scrollTop: 0}, 10);

		jQuery('#intro').fadeOut(500);
		jQuery('main, header nav, .social').fadeIn(500);
	});

	jQuery(document).ready(function(e){
		e.preventDefault();
		pais = "uk";		
		jQuery('#cp_postcode').attr('placeholder','CITY OR POSTCODE');
		jQuery('#contact div form legend').html('You can telephone us on 02087884926 or email us at hello@unocodrinks.com <br /><br />Or, you can just enter your message here.');
	})

});

jQuery(window).load(function(jQuery){

//	jQuery(window).stellar();


/*----------------------------------------------------------------
	Contact us
----------------------------------------------------------------*/
	var validateEmail = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);



	jQuery('input:text, textarea').focus(function(e){
		valor = jQuery(this).data('dft');
		if(jQuery(this).val() != '' && jQuery(this).val() != valor) { return false; }
		jQuery(this).attr('value','');
	}).blur(function(e){
		if(jQuery(this).val() != '' && jQuery(this).val() != valor) { return false; }
		jQuery(this).val(valor);
	});


	jQuery(document).on('click', '#bt_find', function(e){
		e.preventDefault();
		distancia = jQuery('#Distance').val();
		codeAddress(jQuery('#cp_postcode').val()+","+pais);
	});

	jQuery("#cp_postcode").keyup(function(event){
		if(event.keyCode == 13){
				jQuery("#bt_find").click();
		}
	});

});

/*----------------------------------------------------------------
	Map Size
----------------------------------------------------------------*/
	ct();
	jQuery(window).resize(function(){
		ct();
		map.fitBounds(bounds);
	});

	function ct(){
		var xMap = jQuery(window).width() - 350;
		//jQuery('#map_canvas').width(xMap);
		// jQuery('#notfound').css({'left':xMap/2}).show();
	};

/*----------------------------------------------------------------
	Map Places
----------------------------------------------------------------*/
var map;
var geocoder;
var gmarkers = [];

function rad(x) {
	return x * Math.PI / 180;
};

function getDistance(p1, p2) {
	var R = 637100; // Earthâ€™s mean radius in meter
	var dLat = rad(p2.lat - p1.lat);
	var dLong = rad(p2.lng - p1.lng);
	var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
		Math.cos(rad(p1.lat)) * Math.cos(rad(p2.lat)) *
		Math.sin(dLong / 2) * Math.sin(dLong / 2);
	var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
	var d = R * c;
	return d; // returns the distance in meter
};

var markers = [];

function codeAddress(address) {
	geocoder.geocode( { 'address': address}, function(results, status) {
		// clearOverlays();
		if(markers.length >=1){
			markers[0].setMap(null);
			markers = [];
		}
		var zoom = [];
		zoom[1] = 15;
		zoom[5] = 14;
		zoom[10] = 12;
		zoom[30] = 11;
		zoom[50] = 10;
		zoom[100] = 9;
		if (status == google.maps.GeocoderStatus.OK) {
			find_closest_marker(results[0].geometry.location);

			var marker = new google.maps.Marker({
					map: map,
					position: results[0].geometry.location
			});
			markers[0] = marker;
			
			if(address == "UK" || address == "uk"){
				map.setZoom(5);
			}else{
				map.setZoom(zoom[jQuery('#Distance').val()]);
			}
			map.setCenter(results[0].geometry.location);
			map.fitBounds(results[0].geometry.location); 	

			
			
		} else {
			// setPlaces(null);
			jQuery('#plc').hide();
			jQuery('#notfound').fadeIn(500);
			setTimeout(function(){
				jQuery('#notfound').fadeOut(500);
			},2500);
			return null;
		}
	});
}

function clearOverlays() {
	for (var i = 0; i < gmarkers.length; i++ ) {
		gmarkers[i].setMap(null);
	}
	gmarkers.length = 0;
}

function setPlaces(p) {

	jQuery('#plc').hide();
	jQuery('#plc .viewport .overview ul li').hide();

	var x = [];
	var y = [];
	var bounds = new google.maps.LatLngBounds();

	var p1 = null;
	if (p != null) {
		p1 = new Object();
		p1.lat = p.k;
		p1.lng = p.A;

		var latlng2 = new google.maps.LatLng( parseFloat(p.k), parseFloat(p.A) );
		bounds.extend(latlng2);
	}
	var enderecos 			 = [];
	var nomeEstabeleciomento = [];
	var postid 				 = [];
	var t = 0;
	jQuery('.mrk').each(function(){
		
		var lat = jQuery(this).children('.boxmap').find('.lat').text();
		var lng = jQuery(this).children('.boxmap').find('.lng').text();
		var nom = jQuery(this).children('.boxmap').find('strong').text();
		var end = jQuery(this).children('.boxmap').find('p').attr('rel');
		var id  = jQuery(this).children('.boxmap').attr('rel');

		
		if (p != null) {
			var p2 = new Object();
			p2.lat = lat;
			p2.lng = lng;
			var dist = getDistance(p1, p2);

			if (dist > 3200) {
				jQuery(this).hide();
				return 1;
			}
			else {
				jQuery(this).show();
			}
		}

		// Get infos
		x.push( lat );
		y.push( lng );

		var num = x.length-1;

		enderecos.push(end);
		nomeEstabeleciomento.push(nom);		
		postid.push(id);

		// List event on map
		jQuery(this).click(function(e){
			e.preventDefault();
			google.maps.event.trigger(gmarkers[num], 'click');
		});

		if(x){
			jQuery(this).attr('rel','hover-'+t);
			t++;
		}

		// Animate pin
		jQuery(this).hover(function(){
			// gmarkers[num].setAnimation(google.maps.Animation.BOUNCE);
		},function(){
			gmarkers[num].setAnimation(null);
		});
		
	});
	jQuery('#plc').tinyscrollbar_update('relative');
	//show notfound is not adress around search
	if(x.length == 0){
			jQuery('#plc').hide();
			jQuery('#notfound').fadeIn(500);
			setTimeout(function(){
				jQuery('#notfound').fadeOut(500);
			},2500);
			return null;		
	}

	// Marker (pin)
	for(i = 0; i < x.length; i++){

		// Set pin image
		var image = base_url+'img/pin.png';

		// Set Marker options
		var marker = new google.maps.Marker({
			map:       map,
			animation: google.maps.Animation.DROP,
			position:  new google.maps.LatLng(x[i], y[i]),
			icon:      image,
			zIndex:    1
		});




		// Array to list
		gmarkers.push(marker);

		var infowindow = new google.maps.InfoWindow(), marker;


		// Marker click
		google.maps.event.addListener(marker, 'click', (function(marker, i) {
			return function(){
				map.panTo(new google.maps.LatLng(x[i],y[i]));
				// infowindow.setContent("<p>"+nomeEstabeleciomento[i]+"<br>"+enderecos[i]+"</p>");
    //     		infowindow.open(map, marker);
    			jQuery('#plc .viewport .overview ul li').hide();
    			jQuery('#plc').show().tinyscrollbar({ scroll: false });;
    			jQuery('#plc .'+postid[i]).show();

				jQuery('li[rel="hover-'+i+'"]').hover(function(){
					marker.setAnimation(google.maps.Animation.BOUNCE);
				},function(){
					marker.setAnimation(null);
				});		    			

			}
		})(marker, i));

		var latlng = new google.maps.LatLng( parseFloat(x[i]), parseFloat(y[i]) );
		bounds.extend(latlng);
	}
	map.fitBounds(bounds);

}


function sortNumber(a,b) {
    return a - b;
}
function rad(x) {return x*Math.PI/180;}
function find_closest_marker( event ) {
    var lat = event.k;
    var lng = event.B;
    var R = 6371; // radius of earth in km
    var distances = [];
    pinproximo = false;

    for( i=0;i<gmarkers.length; i++ ) {
        var mlat = gmarkers[i].position.lat();
        var mlng = gmarkers[i].position.lng();
		var dLat = rad(lat - mlat);
		var dLong = rad(lng - mlng);
		var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
		Math.cos(rad(lat)) * Math.cos(rad(mlat)) *
		Math.sin(dLong / 2) * Math.sin(dLong / 2);
		var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
		var d = R * c;
		distances[i] = Math.round(d);
		if(distances[i] <= distancia){
			pinproximo = true;
		}
    }
	//show notfound is not adress around search
	if(!pinproximo){
			jQuery('#plc').hide();
			jQuery('#notfound').fadeIn(500);
			setTimeout(function(){
				jQuery('#notfound').fadeOut(500);
			},2500);
			return null;		
	}
}

function nl2br (str, is_xhtml) {
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br ' + '/>' : '<br>'; // Adjust comment to avoid issue on phpjs.org display
  	return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}

function initialize() {
	var country = new google.maps.LatLng(-23.5394696, -46.676005599999996); //(37.090240,-95.712891);

	// Set Style
	var styles  = [ { "stylers": [ { "saturation": -100 }, { "lightness": 30 }, { "gamma": 0.64 } ] },{ "featureType": "road.local", "stylers": [ { "saturation": -100 }, { "lightness": -20 }, { "gamma": 1.07 } ] } ];
	var styledMap = new google.maps.StyledMapType(styles, {name: "Styled Map"});

	// Map Options
	var mapOptions = {
		zoom: 5,
		scrollwheel: false,
		disableDefaultUI: true,
		zoomControl: true,
		zoomControlOptions: {position: google.maps.ControlPosition.LEFT_CENTER},
		center: country,
		mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
	};

	// Set Map
	map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
	map.mapTypes.set('map_style', styledMap);
	map.setMapTypeId('map_style');

	geocoder = new google.maps.Geocoder();
}


google.maps.event.addDomListener(window, 'load', initialize);