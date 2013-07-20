function syntactic(data) {
	var rules = [
		{
			"description": "Matches player chat names",
			"selector": /\&lt;.*?\&gt;/g,
			"before": "",
			"after": "<span style='color:#ff00cc'>&gt;</span><span style='color:#999'>"
		},
		{
			"description": "Full date matcher",
			"selector": /^\d\d\d\d-(\d)?\d-(\d)?\d \d\d:\d\d:\d\d/g,
			"before": "<span style='color:#999'>",
			"after": "</span>"
		},
		{
			"description": "Hides YYYY-MM-DD at the beginning. Don't really need it.",
			"selector": /\d\d\d\d-(\d)?\d-(\d)?\d/g,
			"omit": true
		},
		{
			"description": "Matches [INFO] tag",
			"selector": /\[INFO\]/g,
			"before": "<span style='color:#66ffff !important;'>",
			"after": "</span>"
		},
		{
			"description": "Matches [WARNING] tag",
			"selector": /\[WARNING\]/g,
			"before": "<span style='color:#ff9900 !important;font-weight:bold'>",
			"after": "</span>"
		},
		{
			"description": "Matches [SEVERE] tag",
			"selector": /\[SEVERE\]/g,
			"before": "<span style='color:#fff !important;background:#ff0000'>",
			"after": "</span>"
		},
		{
			"description": "Messy match to plugins-specific tags.",
			"selector": /\[(INFO|WARNING|SEVERE)\] \[.*?\]/g,
			"before": "<span style='color:#33ff99'>",
			"after": "</span>"
		},
		{
			"description": "Hides MC color codes",
			"selector": /(§|&)./g,
			"omit": true
		},
		{
			"description": "Fade for the server command",
			"selector": /issued server command:/g,
			"before": "<span style='color:#999'>",
			"after": "</span><span style='color:#ffff00'>"
		},
		{
			"description": "Command access denied",
			"selector": /was denied access to command/g,
			"before": "<span style='color:#993333'>",
			"after": "</span>"
		},
		{
			"description": "Player stat.",
			"selector": /There are \d*? out of maximum \d*? players online/g,
			"before": "<span style='color:#999'>",
			"after": "</span>"
		},
		{
			"description": "Startup stuff, preparing... NO ONE CARES",
			"selector": /\[INFO\].*? Preparing .*/g,
			"before": "<span style='color:#999'>",
			"after": "</span>"
		},
		{
			"description": "Player joins, yay :)",
			"selector": /\[\/.*?\] logged in with entity id \d*? at .*/g,
			"before": "<span style='color:#99cc99'>",
			"after": "</span>"
		},
		{
			"description": "Player leaves ;(",
			"selector": /\[INFO\].*? lost connection: .*/g,
			"before": "<span style='color:#cc9999'>",
			"after": "</span>"
		}
	];

	data = $('<div/>').text(data).html();

	for (i = 0; i < rules.length; i++) {

		var matches = data.match(rules[i].selector);
		if (!matches) {
			continue;
		}
		for (k = 0; k < matches.length; k++) {
			if ('omit' in rules[i]) {
				data = data.replace(matches[k], '');
			} else {
				data = data.replace(matches[k], rules[i].before + matches[k] + rules[i].after);
			}
		}
	}
	while (data.match(/<span/g).length < data.match(/<\/span>/).length) {
		data += '</span>';
	}


	return data;
}

var last = 0;
var paused = false;

$('#pause').click(function() {
	if (!paused) {
		$(this).html('Unpause');
		paused = true;
	} else {
		$(this).html('Pause');
		paused = false;
	}
});
$('#clear').click(function() {
	$('#console').html('');
});
$('#delete').click(function() {
	$.post(window.baseurl + '/console/delete');
});
$('#issuecommnd').click(function() {

	var attr = $(this).attr('disabled');
	if (typeof attr != 'undefined' && attr != false) {
		return false;
	}

	$(this).attr('disabled', 'true');
	var input = $('#command');
	

	$.ajax({
		type: "POST",
		url: window.baseurl + '/api/private',
		data: 'method=issuecommand&server=' + $('#console').attr('data-id') + '&' + input.serialize(),
		dataType: 'json'
	}).always(function(response) {
		$(this).removeAttr();
	});
});

setInterval(function() {

	if (paused) {
		return false;
	}

	var prompt = $('#console');

	$.ajax({
		type: "POST",
		url: window.baseurl + '/api/private',
		data: 'method=getlog&server=' + prompt.attr('data-id') + '&lasttime=' + last,
		dataType: 'json'
	}).done( function(response) {
		
		if (response.result != 'success') {
			return false;
		}

		for (i = 0; i < response.data.length; i++) {
			prompt.append('<div class="row">' + syntactic(response.data[i]) + '</div>');
		}
		match = response.data[response.data.length - 1].match(/^\d\d\d\d-(\d)?\d-(\d)?\d \d\d:\d\d:\d\d/g);
		date = new Date(match[0].substr(1, match[0].length - 2));
		last = date.getTime() + 1;

    	prompt.animate({"scrollTop": $('#console')[0].scrollHeight}, "fast");

	}).fail(function(response) {
		
	});
}, 2000);