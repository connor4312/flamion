$(document).ready(function() { 
	$('#newuser').click(function(){

		var attr = $(this).attr('disabled');

		if (typeof attr == 'undefined' || attr == false) {
			$('form').ajaxSubmit({
				dataType: 'json',
				url: window.baseurl + "/admin/users/add",
				beforeSubmit: function() {
					$('#newuser').attr('disabled', 'true');
				},
				success: function(response) {
					$('#newuser').removeAttr('disabled');

					if (response.result == 'success') {
						$('#gateway').html("User Created");
						window.location = window.baseurl + "/admin/users";
					} else {
						$('#gateway').html(response.result);
					}
				}
			});
		}
		return false;
	}); 

	$('.login-expand').click(function(){
		var attr = $(this).attr('data-id');

		if (!$('#user').is(':visible')) {
			$('#user').slideDown(300);
		}
		var user = $('#user');
		makeLoader(new CanvasLoader('user'));
		$(window).scrollTo('#user .padded', user);


		$.ajax({
			url: window.baseurl + "/admin/users/" + attr + "/get",
			timeout: 5000,
			cache: false
		}).success(function(response) {
			$('#user .padded').html(response);
			$('td.gridedit').editable(window.baseurl + "/admin/users/update");
		}).fail(function() {
			$('#user .padded').html('<div class="alert">Unable to load the user, we\'re sawry!</div>');
		}).always(function() {
			$('#canvasLoader').remove();
		});
	});
}); 