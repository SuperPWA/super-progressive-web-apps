var changes = false;
jQuery(document).ready(function($){
    $('.superpwa-colorpicker').wpColorPicker();	// Color picker
    $('.superpwa-colorpicker').wpColorPicker('option','change',function(event, ui) {
  	changes = true;});	// When Color picker changes
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

	$('.superpwa-maskable-icon-upload').click(function(e) {	// Application Icon upload
		e.preventDefault();
		var t = $(this);
		var superpwa_meda_uploader = wp.media({
			title: 'Maskable Icon',
			button: {
				text: 'Select Icon'
			},
			multiple: false  // Set this to true to allow multiple files to be selected
		})
		.on('select', function() {
			var attachment = superpwa_meda_uploader.state().get('selection').first().toJSON();
			t.parents('td').find('.superpwa-maskable-input').val(attachment.url);
			superpwa_check_maskable_input();
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
	$('.superpwa-screenshots-upload').click(function(e) {	// Application Icon upload
		e.preventDefault();
		var superpwa_meda_uploader = wp.media({
			title: 'Screenshots',
			button: {
				text: 'Select Icon'
			},
			multiple: true  // Set this to true to allow multiple files to be selected
		})
		.on('select', function() {
			var attachment = superpwa_meda_uploader.state().get('selection').toJSON();
			var len= attachment.length;
			var screenshots=[];
			if(len>8)
			{
				alert('Screenshots must be less than or equal to 8');
				return;
			}
			if(len>0)
			{
				for(var i=0;i<len;i++)
				{
					screenshots.push(attachment[i].url);
				}
			}
			
			$('.superpwa-screenshots').val(screenshots.join(','));
		})
		.open();
	});
	$('.superpwa-monochrome-upload').click(function(e) {	// Monochrome Icon upload
		e.preventDefault();
		var superpwa_meda_uploader = wp.media({
			title: 'Monochrome Icon',
			button: {
				text: 'Select Icon'
			},
			multiple: false  // Set this to true to allow multiple files to be selected
		})
		.on('select', function() {
			var attachment = superpwa_meda_uploader.state().get('selection').first().toJSON();
			$('.superpwa-monochromeicon').val(attachment.url);
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
        jQuery.post(ajaxurl, {action:'superpwa_newsletter_submit',email:email,superpwa_security_nonce:superpwa_obj.superpwa_security_nonce},
          function(data) {}
        );
        return true;
	});
	$('.superpwa_newsletter_hide').click(function(e){
		//e.preventDefault();
		jQuery('.superpwa-newsletter-wrapper').css("display", "none");
		var form = jQuery(this);
        jQuery.post(ajaxurl, {action:'superpwa_newsletter_hide_form',superpwa_security_nonce:superpwa_obj.superpwa_security_nonce},
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

	function superpwa_select2(){
		var $select2 = jQuery('.superpwa-select2');
		if($select2.length > 0){

			jQuery($select2).each(function(i, obj) {
				var currentP = jQuery(this);  
				var $defaultResults = jQuery('option[value]:not([selected])', currentP);  
				
				var defaultResults = [];
				$defaultResults.each(function () {
					var $option = jQuery(this);
					defaultResults.push({
						id: $option.attr('value'),
						text: $option.text()
					});
				});

				var type = jQuery('#superpwa_settings_startpage_type').val();
				type = 'page';
				if (currentP.hasClass("js_post")) {
					type = 'post';
				}
				var ajaxnewurl = ajaxurl + '?action=superpwa_get_select2_data&superpwa_security_nonce='+superpwa_obj.superpwa_security_nonce+'&type='+type;

				currentP.select2({           
					ajax: {             
						url: ajaxnewurl,
						delay: 250, 
						cache: false,
					},            
					minimumInputLength: 2, 
					minimumResultsForSearch : 50,
					dataAdapter: jQuery.fn.select2.amd.require('select2/data/extended-ajax'),
					defaultResults: defaultResults
				});

			});

		}                    
		
	}

	superpwa_select2();
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
		document.getElementById('thumbnail').style.display = 'none';
	};
    fr.readAsDataURL(file);
    document.getElementById('aft_img_gen').innerHTML = "Generating Images Please Wait...";
    setTimeout(function(){ superpwaGetZip(); }, 300);
}



function superpwaGetZip() {
	 jQuery('#aft_img_gen').text("Generating Images Please Wait...");
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
		compression: 'DEFLATE',
        type: 'blob'
    })
	.then(function(content) {
		var fileName = 'splashscreens.zip';
		var fileObj = new File([content], fileName, {
			type : 'application/zip'
		});
		const zip_size = (fileObj.size / 1024 / 1024).toFixed(2);
		var fd = new FormData();
   		fd.append('fileName', fileName);
		fd.append('file', fileObj);
		fd.append('action', 'superpwa_splashscreen_uploader');
		fd.append('security_nonce', superpwaIosScreen.nonce);
		fd.append('mimeType', 'application/zip');
		var max_upload_size = superpwaIosScreen.max_file_size;
		var allowed_size = parseFloat(max_upload_size.replace('M',''));
		
		// POST Ajax call
		if (zip_size < allowed_size) {		
			jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: fd,
				dataType: 'json',
				cache: false,
				contentType: false,
				processData: false,
				success: function (data) {
					if (data.status == 200) {
						jQuery('#thumbnail').css("display", "block");
						jQuery('#aft_img_gen').text("Touch Icons Generated Successfully");
						jQuery('#aft_img_gen').css({"color":"green","margin-bottom":"20px"});
						jQuery('#submit_splash_screen').trigger('click');
					}else{
						jQuery('#thumbnail').css("display", "block");
						jQuery('#aft_img_gen').text(data.message);
						jQuery('#aft_img_gen').css({"color":"red","margin-bottom":"20px"});
					}
					//window.location.reload();
				},
				
			})
		}else{
			jQuery('#thumbnail').css("display", "block");
			jQuery('#aft_img_gen').text('Generated zip file size('+zip_size+'MB) excceding to server max file size '+max_upload_size);
			jQuery('#aft_img_gen').css({"color":"red","margin-bottom":"20px"});
		}
    });
}

// Settings unsaved alert message
    var tablinks,select_tag,input_tag,button_tag;
    	tablinks = document.getElementsByClassName("spwa-tablinks");
    	select_tag = document.getElementsByTagName("select");
    	input_tag = document.getElementsByTagName("input");
    	button_tag = document.getElementsByTagName("button");

    if(input_tag){
		  for(var h=0; h<input_tag.length;h++ ){
		    input_tag[h].addEventListener( 'change', function(e) {
		      //console.log(changes);
		      changes = true;
		     // console.log(changes);
		    });
		  }
     }
     if(select_tag){
		  for(var j=0; j<select_tag.length;j++ ){
		    select_tag[j].addEventListener( 'change', function(e) {
		      changes = true;
		    });
		  }
     }
     if(button_tag){
		  for(var k=0; k<button_tag.length;k++ ){
		    button_tag[k].addEventListener( 'click', function(e) {
		      changes = true;
		    });
		  }
     }

    if(tablinks){
	  for(var l=0; l<tablinks.length;l++ ){
         tablinks[l].addEventListener( 'click', function(e) {

				    if(changes){
     				    window.onbeforeunload = function(e) {
   							 return "Sure you want to leave?";
						};
					} 
	         	
			});
        }
     }

 //Reset settings
   	 jQuery(document).on("click",".superpwa-reset-settings", function(e){
	 //$('.superpwa-reset-settings').click(function(e){
	        e.preventDefault();
	     
	        var reset_confirm = confirm("Are you sure?");
	     
	        if(reset_confirm == true){
	            
	        jQuery.ajax({
	                    type: "POST",    
	                    url:ajaxurl,                    
	                    dataType: "json",
	                    data:{action:"superpwa_reset_all_settings", superpwa_security_nonce:superpwa_obj.superpwa_security_nonce},
	                    success:function(response){                               
	                        setTimeout(function(){ location.reload(); }, 1000);
	                    },
	                    error: function(response){                    
	                    console.log(response);
	                    }
	                    }); 
        
       }
    });


	jQuery('.superpwa-install-require-plugin').on("click", function(e){
		e.preventDefault();
		/*var result = confirm("This required a free plugin to install in your WordPress");
		if (!result) {
	
		}*/
		var self = jQuery(this);
		self.html('Installing..').addClass('updating-message');
		var nonce = self.attr('data-secure');
		var activate_url = self.attr('data-activate-url');
		var currentId = self.attr('id');
		var activate = '';
		 if (currentId == 'pushnotification') {
				activate = '&activate=pushnotification';
			}
	
		console.log(wp.updates);
	
	
		jQuery.ajax({
			url: ajaxurl,
			type: 'post',
			data: 'action=superpwa_enable_modules_upgread' + activate + '&verify_nonce=' + nonce,
			dataType: 'json',
			success: function (response) {
				if (response.status == 200) {
					if (self.hasClass('not-exist')) {
	
						//To installation
						wp.updates.installPlugin(
							{
								slug: response.slug,
								success: function (pluginresponse) {
									console.log(pluginresponse.activateUrl);
									superpwa_Activate_Modules_Upgrade(pluginresponse.activateUrl, self, response, nonce)
								}
							}
						);
	
					} else {
						var activateUrl = self.attr('data-activate-url');
						superpwa_Activate_Modules_Upgrade(activateUrl, self, response, nonce)
					}
				} else {
					alert(response.message)
				}
	
			}
		});
	});
	
	var superpwa_Activate_Modules_Upgrade = function(url, self, response, nonce){
		if (typeof url === 'undefined' || !url) {
			return;
		}
		 console.log( 'Activating...' );
		 self.html('Activating...');
		 jQuery.ajax(
			{
				async: true,
				type: 'GET',
				//data: dataString,
				url: url,
				success: function () {
					var msgplug = '';
					if(self.attr('id')=='pushnotification'){
						msgplug = 'push notification';
						console.log("push notification Activated");
						self.removeClass('updating-message')
						self.removeClass("button")
						self.removeClass('superpwa-install-require-plugin')
						self.unbind('click');
						self.html('<a target="_blank" href="'+response.redirect_url+'" style="color:#fff;text-decoration:none;">View Settings</a>');
					}
				},
				error: function (jqXHR, exception) {
					var msg = '';
					if (jqXHR.status === 0) {
						msg = 'Not connect.\n Verify Network.';
					} else if (jqXHR.status === 404) {
						msg = 'Requested page not found. [404]';
					} else if (jqXHR.status === 500) {
						msg = 'Internal Server Error [500].';
					} else if (exception === 'parsererror') {
						msg = 'Requested JSON parse failed.';
					} else if (exception === 'timeout') {
						msg = 'Time out error.';
					} else if (exception === 'abort') {
						msg = 'Ajax request aborted.';
					} else {
						msg = 'Uncaught Error.\n' + jqXHR.responseText;
					}
					console.log(msg);
				},
			}
		);
	}

	jQuery("#screenshots_add_more").click(function(e) {  // Add more screenshots
		e.preventDefault();
		clone_tr = jQuery(this).parents('.js_clone_div:first').clone();
		clone_tr.find('input').val("")
		clone_tr.find('input').prop('name','superpwa_settings[screenshots_multiple][]')
		clone_tr.find('select').prop('name','superpwa_settings[form_factor_multiple][]')
		clone_tr.find('select').prop('selectedIndex', 0)
		clone_tr.find("#screenshots_add_more").remove()
		clone_tr.find(".js_remove_screenshot").show()
		clone_tr.find('.js_choose_button').addClass('superpwa-screenshots-multiple-upload')
		clone_tr.find('.js_choose_button').removeClass('superpwa-screenshots-upload')
		clone_tr.insertAfter('.js_clone_div:last')
	});
	jQuery("body").on('click','.js_remove_screenshot',function(e) {  // Add more screenshots
		e.preventDefault();
		jQuery(this).parents('.js_clone_div').remove()
	});

	// after added add more functionality form multiple screenshots
	jQuery("body").on('click','.superpwa-screenshots-multiple-upload',function(e) {  // Application screenshots upload
		e.preventDefault();
		this_ = jQuery(this).parents('.js_clone_div');
		var superpwawpMediaUploader = wp.media({
			title: 'Screenshots',
			button: {
				text: 'Select Icon'
			},
			multiple: false,  // Set this to true to allow multiple files to be selected
			library:{type : 'image'}
		})
		.on("select", function() {
			var attachment = superpwawpMediaUploader.state().get("selection").first().toJSON();
			this_.find(".superpwa-screenshots").val(attachment.url);
		})
		.open();
	});

	jQuery('.superpwa_js_remove_maskable').click(function(e) {
		e.preventDefault();
		jQuery(this).parents('td').find('.superpwa-maskable-input').val("");
		jQuery(this).hide();
	});

	jQuery('.superpwa-maskable-input').keyup(function() {
		if ( jQuery(this).val() == null || jQuery(this).val() == "") {
			jQuery(this).parents('td').find('.superpwa_js_remove_maskable').hide();
		}else{
			jQuery(this).parents('td').find('.superpwa_js_remove_maskable').show();
		}
	})


	function superpwa_check_maskable_input() {
		jQuery('.superpwa-maskable-input').each(function() {
			console.log(jQuery(this).val())
			if ( jQuery(this).val() == null || jQuery(this).val() == "") {
				jQuery(this).parents('td').find('.superpwa_js_remove_maskable').hide();
			}else{
				jQuery(this).parents('td').find('.superpwa_js_remove_maskable').show();
			}
		});
	}
	superpwa_check_maskable_input();