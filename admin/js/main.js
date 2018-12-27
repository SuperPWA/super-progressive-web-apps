jQuery(document).ready(function($){
    $('.superpwa-colorpicker').wpColorPicker();	// Color picker
	$('.superpwa-icon-upload').click(function(e) {	// Application Icon upload
		e.preventDefault();
		var superpwa_meda_uploader = wp.media({
			title: 'Application Icon',
			button: {
				text: 'Select Icon'
			},
			multiple: false  // Set this to true to allow multiple files to be selected
		})
		.on('select', function() {
			var attachment = superpwa_meda_uploader.state().get('selection').first().toJSON();
			$('.superpwa-icon').val(attachment.url);
		})
		.open();
	});
	$('.superpwa-splash-icon-upload').click(function(e) {	// Splash Screen Icon upload
		e.preventDefault();
		var superpwa_meda_uploader = wp.media({
			title: 'Splash Screen Icon',
			button: {
				text: 'Select Icon'
			},
			multiple: false  // Set this to true to allow multiple files to be selected
		})
		.on('select', function() {
			var attachment = superpwa_meda_uploader.state().get('selection').first().toJSON();
			$('.superpwa-splash-icon').val(attachment.url);
		})
		.open();
	});
	$('.superpwa-app-short-name').on('input', function(e) {	// Warn when app_short_name exceeds 12 characters.
		if ( $('.superpwa-app-short-name').val().length > 12 ) {
			$('.superpwa-app-short-name').css({'color': '#dc3232'});
			$('#superpwa-app-short-name-limit').css({'color': '#dc3232'});
		} else {
			$('.superpwa-app-short-name').css({'color': 'inherit'});
			$('#superpwa-app-short-name-limit').css({'color': 'inherit'});
		}
	});
});