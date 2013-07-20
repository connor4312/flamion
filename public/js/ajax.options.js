$('#myTab a').click(function (e) {
  e.preventDefault();
  $(this).tab('show');
});

$('#myTab').bind('show', function(e) {    
   var pattern=/#.+/gi
   var contentID = e.target.toString().match(pattern)[0];      
   
   $(contentID).load(window.baseurl + '/admin/options/page/' + contentID.replace('#',''), function(){
        $('#myTab').tab();
   });
});



$('.serversw').click(function() {

	var attr = $(this).attr('data-id');
	$('.sw').hide();
	$('#' + attr).show();
});
function btnInit() {
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
	$('#lessbuild').click(function(e){
		e.preventDefault();

		var attr = $(this).attr('disabled');
		if (typeof attr != 'undefined' && attr != false) {
			return false;
		}

		$(this).attr('disabled', 'true').html('Building...');

		$.get($(this).attr('href'), function(response) {
			$(this).removeAttr('disabled').html('Compile LESS');

			if (response == 'success') {
			    location.reload();
			} else {
				$(this).after('<div class="alert alert-error">' + response + '</div>');
			}
		});
	});
	$('#testmail').click(function(e){
		e.preventDefault();
		var btn = this;

		var attr = $(this).attr('disabled');
		if (typeof attr != 'undefined' && attr != false) {
			return false;
		}

		$(btn).attr('disabled', 'true').html('Sending...');
		$.ajax({
			url: $(btn).attr('href'),
			dataType: 'json'
		}).done( function(response) {
			if (response == 'success'){
				$(btn).after('<div class="alert alert-success">Email sent!</div>');
			} else {
				$(btn).after('<div class="alert alert-error">Failed to send!</div>');
			}
		}).fail(function(response) {
			$(btn).after('<div class="alert alert-error">Failed to send!</div>');
		}).always(function() {
			$(btn).removeAttr('disabled').html('Send Test Email');
		});
	});
}
btnInit();
$(document).ajaxComplete(function() {
	btnInit();
});

