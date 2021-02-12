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
	$("#submit_splash_screen").click(function(e){
		e.preventDefault();
		var self = $(this);
		var imageUrl = $('.select-apple-splash-icon').val();
		if(imageUrl==''){alert("Please Select Image");return false;}
		getBase64Image(imageUrl);
		//superpwaGetImageDiffrentScreen(imageUrl);
		return false;
	})
});
function getBase64Image(imgUrl) {
    var img = new Image();
    img.onload = function(){
		var canvas = document.createElement("canvas");
		canvas.width = img.width;
		canvas.height = img.height;
		var ctx = canvas.getContext("2d");
		ctx.drawImage(img, 0, 0);
		var dataURL = canvas.toDataURL("image/png"),
			dataURL = dataURL.replace(/^data:image\/(png|jpg);base64,/, "");

		superpwaGetImageDiffrentScreen(dataURL); // the base64 string
    };

    img.setAttribute('crossOrigin', 'anonymous'); 
    img.src = imgUrl;

}
function superpwaGetImageDiffrentScreen(imageUrl){
	if(imageUrl==''){alert("Please Select Image");return;}
	var imageMessage = document.getElementById("superpwa-apple-splash-message")
	imageMessage.innerHTML = 'Generating splash screen...';
	imageMessage.setAttribute("class", "updating-message");

	//var zip=new JSZip();
	//var folder=zip.folder('splashscreens');
	var canvas=document.createElement('canvas'),ctx=canvas.getContext('2d');
	var img=new Image();
	img.src=imageUrl;
	Object.keys(pwaforwp_obj.iosSplashIcon).forEach(function(key, index) {
		var phone = pwaforwp_obj.iosSplashIcon[key];
		var ws=key.split("x")[0];
		var hs=key.split("x")[1];
		canvas.width=ws;
		canvas.height=hs;
		var wi=img.width;
		var hi=img.height;
		var wnew=wi;
		var hnew=hi;
		
		ctx.fillStyle = document.getElementById('ios-splash-color').value;
		ctx.fillRect(0,0,canvas.width,canvas.height);
		
		ctx.drawImage(img,(ws-wnew)/2,(hs-hnew)/2,wnew,hnew);
		var img2=canvas.toDataURL();
		alert(img2);
		//document.getElementById('a').src= img2;
		//folder.file(phone.file,img2.split(';base64,')[1],{base64:true});
	});
	// zip.generateAsync({type:'blob'}).then(function(content){
	// 	saveAs(content,'splashscreens.zip');
	// 	// var request = new XMLHttpRequest();
	// 	// request.open("POST", ajaxurl+"?action=pwaforwp_splashscreen_uploader&pwaforwp_security_nonce="+pwaforwp_obj.pwaforwp_security_nonce);
	// 	// request.send(content);
	// 	// request.onreadystatechange = function() {
	// 	// 	if (request.readyState === 4) {
	// 	// 		var reponse = JSON.parse(request.response);
	// 	// 	  if(reponse.status==200){
	// 	// 		imageMessage.innerHTML = 'Splash Screen generated';
	// 	// 		imageMessage.setAttribute("class", "dashicons dashicons-yes");
	// 	// 		imageMessage.style.color = "#46b450";
	// 	// 		setTimeout(function(){
	// 	// 			window.location.reload();
	// 	// 		}, 1000);
	// 	// 	  }
	// 	// 	}
	// 	//   }
	// });
}