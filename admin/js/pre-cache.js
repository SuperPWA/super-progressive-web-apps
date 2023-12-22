jQuery(document).ready(function($){
	$('#precaching_automatic').click(function(e){

		if(e.target.checked == true){
		     // Code in the case checkbox is checked.
		     console.log('checked');
		     $('#superpwa-automatic-suboption').removeClass('hide').addClass('show');
		} else {
		     // Code in the case checkbox is NOT checked.
		     $('#superpwa-automatic-suboption').removeClass('show').addClass('hide');
		}
	});

	$('#precaching_manual').click(function(e){

		if(e.target.checked == true){
		     // Code in the case checkbox is checked.
		     $('#superpwa-pre-manual-suboption').removeClass('hide').addClass('show');
		} else {
		     // Code in the case checkbox is NOT checked.
		     $('#superpwa-pre-manual-suboption').removeClass('show').addClass('hide');
		}
	});

});