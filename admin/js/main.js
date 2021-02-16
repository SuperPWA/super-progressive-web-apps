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
		if ( $('.superpwa-app-short-name').val().length > 15 ) {
			$('.superpwa-app-short-name').css({'color': '#dc3232'});
			$('#superpwa-app-short-name-limit').css({'color': '#dc3232'});
		} else {
			$('.superpwa-app-short-name').css({'color': 'inherit'});
			$('#superpwa-app-short-name-limit').css({'color': 'inherit'});
		}
	});
	$('#superpwa_newsletter').submit(function(e){
		//e.preventDefault();
		var form = jQuery(this);
        var email = form.find('input[name="newsletter-email"]').val();
        jQuery.post(ajaxurl, {action:'superpwa_newsletter_submit',email:email},
          function(data) {}
        );
        return true;
	});
	//Hide superPWA other menus
	$('#toplevel_page_superpwa').find('ul').find('li').each(function(v, i){
		arr = ['superpwa', 'settings', 'add-ons', 'license', 'upgrade to pro'];
		var txt = $(this).text().toLowerCase();
		if($.inArray( txt, arr ) ===-1){
			$(this).hide();
		}
	})
	//Hide superPWA other menus
	const urlParams = new URLSearchParams(window.location.search);
	arr = ['superpwa', 'superpwa-addons', 'superpwa-upgrade']
	if($.inArray( urlParams.get('page'), arr ) ===-1){
		var heading = $('.wrap').find('h1').html()
		$('.wrap').find('h1').html('<a href="./admin.php?page=superpwa-addons" style="text-decoration:none;color: #5b5b5d;">SuperPWA Add-ons</a> > ' + heading)
	}
	$("#submit_splash_screen").click(function(e){
		$('#superpwa-apple-splash-message').text("Please wait...");
		superpwaGetZip();
	});
});
var image = '';
document.addEventListener('DOMContentLoaded', function() {
    var elmFileUpload = document.getElementById('upload_apple_function');
    if (elmFileUpload) {
        elmFileUpload.addEventListener('change', superpwaOnFileUploadChange, false);
    }
});

function superpwaOnFileUploadChange(e) {
    var file = e.target.files[0];
    var fr = new FileReader();
    fr.onload = function(e) {
		image = e.target.result;
		document.getElementById('thumbnail').src = e.target.result;
		document.getElementById('thumbnail').style.display = 'block';
	};
    fr.readAsDataURL(file);
}

function superpwaGetZip() {
	jQuery('#superpwa-apple-splash-message').text("Please wait...");
	if(image==''){
		alert("Please Select Image"); jQuery('#superpwa-apple-splash-message').text("");
		return;
	}
    var zip = new JSZip();
    var folder = zip.folder('super_splash_screens');
    var canvas = document.createElement('canvas'),
        ctx = canvas.getContext('2d');

    var img = new Image();
    img.src = image;
	var phones = JSON.parse(document.getElementById('iosScreen-data').innerHTML)
    Object.keys(phones).forEach(function(key, index) {
		var phone = phones[key];
            var ws=key.split("x")[0];
            var hs=key.split("x")[1];
            canvas.width=ws;
            canvas.height=hs;
            var wi=img.width;
            var hi=img.height;
            var wnew=wi;
            var hnew=hi;
            
			if (document.getElementById('center-mode').checked == true) {
            ctx.fillStyle = document.getElementById('ios-splash-color').value;
            ctx.fillRect(0,0,canvas.width,canvas.height);
			}else{
				var rs = ws / hs;
                var ri = wi / hi;
                var scale = rs > ri ? (ws / wi) : (hs / hi);
                wnew = wi * scale;
                hnew = hi * scale;
			}
            
            ctx.drawImage(img,(ws-wnew)/2,(hs-hnew)/2,wnew,hnew);
            var img2=canvas.toDataURL();
            folder.file(phone.file,img2.split(';base64,')[1],{base64:true});
    });
    zip.generateAsync({
        type: 'blob'
    }).then(function(content) {
        //saveAs(content, 'splashscreens.zip');

		var fileName = 'splashscreens.zip';
		var fileObj = new File([content], fileName, {
			type : 'application/zip'
		});
		console.log('File object created:', fileObj);
		var fd = new FormData();
   		fd.append('fileName', fileName);
		fd.append('file', fileObj);
		fd.append('action', 'superpwa_splashscreen_uploader');
		fd.append('security_nonce', superpwaIosScreen.nonce);
		

		// POST Ajax call
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			data: fd,
			dataType: 'json',
			success: function (data) {
				console.log(data)
				window.location.reload();
			},
			cache: false,
			contentType: false,
			processData: false
		})
    });
}