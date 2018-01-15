jQuery(document).ready(function($){
    $('.superpwa-colorpicker').wpColorPicker();	// Color picker
	$('.superpwa-icon-upload').click(function(e) {	// Media upload
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
});