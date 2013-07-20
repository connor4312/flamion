

var canvas = document.getElementById('serverstat');
var ctx = canvas.getContext('2d');
var status = 'offline';
var fps = 15;
var waxing = true;
var opacity = 0.6;
var opacity_min = 0.6;
var speed = 0.03;


$('.serversw').click(function() {

	var attr = $(this).attr('data-id');
	$('.sw').hide();
	$('#' + attr).show();
});
$('.btn-group[data-toggle="buttons-radio"]').each(function() {
	var group = this;

	$('button', this).click(function() {
		$('input', group).val($(this).attr('data-id'));
	});
});
$('.serversw').click(function() {

	var attr = $(this).attr('data-id');
	$('.sw').hide();
	$('#' + attr).show();
});

setInterval(function() {

	var stat = $('#serverstat');

	$.ajax({
		type: 'POST',
		url: window.baseurl + '/api/private',
		data: 'method=status&server=' + stat.attr('data-id'),
		dataType: 'json'
	}).done( function(response) {
		stat.attr('class', response.results);

		$('.serverbtn').removeAttr('disabled');
		status = response.results;

		if (response.results == 'online') {
			$('.serverbtn[data-action="start"]').attr('disabled', 'true');
		}
		if (response.results == 'offline') {
			$('.serverbtn[data-action="stop"], .serverbtn[data-action="restart"]').attr('disabled', 'true');
		}
		if (response.results == 'busy') {
			$('.serverbtn').attr('disabled', 'true');
		}

	}).fail(function(response) {
		stat.attr('class', 'checking');
		status = 'err';

		$('.serverbtn').attr('disabled', 'true');
	});
}, 5000);

$('.serverbtn').click(function(e) {
	e.preventDefault();

	var attr = $(this).attr('disabled');
	if (typeof attr != 'undefined' && attr != false) {
		return false;
	}

	$.ajax({
		url: window.baseurl + '/api/private?method=' + $(this).attr('data-action') + '&server=' + $(this).attr('data-id')
	});
});

function getColor(status) {
	switch (status) {
		case 'online':
			return '#3ae73a';
			break;
		case 'offline':
			return '#ccc';
			break;
		case 'busy':
			return '#1e9ce4';
			break;
		default:
			return '#e41e1e';
			break;
	}
}
function draw() {
	ctx.clearRect(0, 0, 20, 20);
	
	if (status != 'offline') {
		ctx.globalAlpha = opacity;
		opacity += speed * (waxing ? 1 : -1);
		if (opacity >= 1) {
			 waxing = false;  
		}
		if (opacity <= opacity_min) {
			waxing = true;
		}
	}
	gradient = ctx.createRadialGradient(10, 10, 8, 10, 10, 10);
	gradient.addColorStop(0, getColor(status));
	gradient.addColorStop(1, 'rgba(255, 255, 255, 0)');
	ctx.fillStyle = gradient;
	ctx.fillRect(0, 0, 20, 20);
	
	ctx.globalAlpha = 1;
	
	ctx.beginPath();
	ctx.strokeStyle = 'rgba(0, 0, 0, 0.2)';
	ctx.arc(10,10,8,0,Math.PI*2,true);
	ctx.stroke();
	
	ctx.beginPath();
	ctx.strokeStyle = 'rgba(255, 255, 255, 0.7)';
	ctx.arc(10,10,7,Math.PI/6,Math.PI*(5/6),true);
	ctx.stroke();
}

setInterval(function() {
  draw();
}, 1000/fps);