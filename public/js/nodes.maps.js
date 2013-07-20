function initialize() {

	if (!$('#map-canvas').length)
		return false;

	var myLatlng = eval('new google.maps.LatLng(' + $('#map-canvas').html() + ');');

	var mapOptions = {
		zoom: 5,
		center: myLatlng,
		disableDefaultUI: true,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	}
	var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

	function rgb2hex(rgb) {
		rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
		function hex(x) {
			return ("0" + parseInt(x).toString(16)).slice(-2);
		}
		return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
	}

	var styles = [
		{
			stylers: [
				{ hue: rgb2hex($('#navbar-lower').css('background-color')) },
				{ saturation: -20 }
			]
		},{
			featureType: "road",
			elementType: "geometry",
			stylers: [
				{ lightness: 100 },
				{ visibility: "simplified" }
			]
		},{
			featureType: "road",
			elementType: "labels",
			stylers: [
				{ visibility: "off" }
			]
		}
	];

	//map.setOptions({styles: styles});

	var marker = new google.maps.Marker({
			position: myLatlng,
			map: map,
			title: 'Hello World!'
	});
}

$(document).ready(function() { 

	initialize();

	$('#newnode').click(function(){

		var attr = $(this).attr('disabled');

		if (typeof attr == 'undefined' || attr == false) {
			$('form').ajaxSubmit({
				dataType: 'json',
				url: window.baseurl + "/admin/nodes/add",
				beforeSubmit: function() {
					$('#newuser').attr('disabled', 'true');
				},
				success: function(response) {
					$('#newuser').removeAttr('disabled');

					if (response.result == 'success') {
						$('#gateway').html("User Created");
						window.location = window.baseurl + "/admin/nodes";
					} else {
						$('#gateway').html(response.result);
					}
				}
			});
		}
		return false;
	}); 

	$('.node-expand').click(function(){
		var attr = $(this).attr('data-id');

		if (!$('#node').is(':visible')) {
			$('#node').slideDown(300);
		}
		var user = $('#node');
		//makeLoader(new CanvasLoader('user'));
		$(window).scrollTo('#nodein', user);


		$.ajax({
			url: window.baseurl + "/admin/nodes/" + attr + "/get",
			timeout: 5000,
			cache: false
		}).success(function(response) {
			$('#nodein').html(response);
			$('td.gridedit').editable(window.baseurl + "/admin/nodes/update");
			initialize();
		}).fail(function() {
			$('#nodein').html('<div class="alert">Unable to load the daemon, we\'re sawry!</div>');
		}).always(function() {
			$('#canvasLoader').remove();
		});
	});
}); 