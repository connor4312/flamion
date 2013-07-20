$('th input[type="checkbox"]').mousedown(function() {
    if (!$(this).is(':checked')) {
        $('input[value*="' + $(this).attr('value').replace('STAR', '') +'"]').prop("checked", true);
     	$(this).prop("checked", true);
    } else {
    	$('input[value*="' + $(this).attr('value').replace('STAR', '') +'"]').prop("checked", false);
    	$(this).prop("checked", false);
    }
});