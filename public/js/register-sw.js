if ('serviceWorker' in navigator) {
  window.addEventListener('load', function() {
	navigator.serviceWorker.register(superpwa_sw.url)
	.then(function(registration) { console.log('SuperPWA service worker ready'); 
		if(registration.active)
		{
			registration.update(); 
		}
		if(typeof firebase !='undefined' && typeof pushnotification_load_messaging =='function'){
			const messaging = firebase.messaging();
			messaging.useServiceWorker(registration);
			pushnotification_load_messaging();
		}
		subOnlineOfflineIndicator();
	})
	.catch(function(error) { console.log('Registration failed with ' + error); });

	/****************** Start : Online/Offline Indicator ******************/

	// Variables & default values
	const snackbarTimeToHide = 5000; // 5s
	let isOffline = false,
		snackbarTimeoutHide = null,
		goOfflineMsg = superpwa_sw.offline_message_txt,
		backOnlineMsg = 'You\'re back online <a href="javascript:location.reload()">refresh</a>';

	/**
	* Subscribe to online offline indicator
	*/
	function subOnlineOfflineIndicator() {
		injectSnackbarHtml();
		injectSnackbarCss();
		runOnlineOfflineIndicator();
	}

	/**
 	* Inject html of snackbar
 	*/
	function injectSnackbarHtml() {
		const container = document.createElement('div');
		container.className = 'snackbar';

		const parag = document.createElement('p');
		parag.id = 'snackbar-msg';
		container.appendChild(parag);

		const button = document.createElement('button');
		button.type = 'button';
		button.className = 'snackbar-close';
		button.setAttribute('aria-label', 'Close ×');
		button.addEventListener('click', hideSnackbar);
		button.innerHTML = '&times;';

		container.appendChild(button);

		document.body.appendChild(container);

		window.addEventListener('online', runOnlineOfflineIndicator);
		window.addEventListener('offline', runOnlineOfflineIndicator);

		window.addEventListener('fetch',() => console.log("fetch"));

		// Clean snackbarTimeToHide varibale when user hover on the snackbar to prevent hide it
		container.addEventListener('mouseover', function () {
			if (snackbarTimeoutHide !== null)
				clearTimeout(snackbarTimeoutHide);
		});

		// Call setTimeout and set snackbarTimeToHide variable to hide snackbar
		container.addEventListener('mouseout', function () {
			if (snackbarTimeoutHide !== null)
				snackbarTimeoutHide = setTimeout(hideSnackbar, snackbarTimeToHide / 2);
		});
	}

	/**
 	* Inject style css of snackbar
 	*/
	function injectSnackbarCss() {
		const css = `body.snackbar--show .snackbar {
			-webkit-transform: translateY(0);
			transform: translateY(0); 
		}
		.snackbar {
			box-sizing: border-box;
			background-color: #121213;
			color: #fff;
			padding: 10px 55px 10px 10px;
			position: fixed;
			z-index: 9999999999999999;
			left: 15px;
			bottom: 15px;
			border-radius: 5px 8px 8px 5px;
			max-width: 90%;
			min-height: 48px;
			line-height: 28px;
			font-size: 16px;
			-webkit-transform: translateY(150%);
			transform: translateY(150%);
			will-change: transform;
			-webkit-transition: -webkit-transform 200ms ease-in-out;
			-webkit-transition-delay: 0s;
					transition-delay: 0s;
			-webkit-transition: -webkit-transform 200ms ease-in-out false;
			transition: -webkit-transform 200ms ease-in-out false;
			transition: transform 200ms ease-in-out false;
			transition: transform 200ms ease-in-out false, -webkit-transform 200ms ease-in-out false; 
		}
		.snackbar p {
			margin: 0;
			color: #fff;
			text-align: center; 
		}
		.snackbar .snackbar-close {
			position: absolute;
			top: 0;
			right: 0;
			width: 45px;
			height: 100%;
			padding: 0;
			background: #2a2a2a;
			border: none;
			font-size: 28px;
			font-weight: normal;
			border-radius: 0 5px 5px 0;
			color: #FFF;
			font-family: Arial, Helvetica, sans-serif;
		}
		.snackbar .snackbar-close:hover,
		.snackbar .snackbar-close:focus {
			background: #3f3f3f;
		}
		.snackbar a {
			color: #FFF;
			font-weight: bold;
			text-decoration: underline; 
		}`;
			
		const head = document.head || document.getElementsByTagName('head')[0];
		const style = document.createElement('style');

		style.type = 'text/css';
		if (style.styleSheet) {
			// This is required for IE8 and below.
			style.styleSheet.cssText = css;
		} else {
			style.appendChild(document.createTextNode(css));
		}

		head.appendChild(style);
	}

	/**
 	* Show the state of the mode of connectivity to the user :  back onLine | go offLine
 	*/
	function runOnlineOfflineIndicator() {
		if (navigator.onLine) {
			if (isOffline === true) {
				showSnackbar(backOnlineMsg);
			}
			isOffline = false;
		} else {
			if (superpwa_sw.offline_message == 1) {
				showSnackbar(goOfflineMsg);
				isOffline = true;
			}
		}
	}

	/**
 	* Show with the given message in the snackbar
 	* @param {String} msg 
 	*/
	function showSnackbar(msg) {
		document.getElementById('snackbar-msg').innerHTML = msg;
		document.body.classList.add('snackbar--show');

		clearTimeout(snackbarTimeoutHide);
		snackbarTimeoutHide = setTimeout(hideSnackbar, snackbarTimeToHide);
	}

	/**
	* Hide snackbar
	*/
	function hideSnackbar() {
		document.body.classList.remove('snackbar--show');
	}

	/****************** End : Online/Offline Indicator ******************/

	var deferredPrompt;
	window.addEventListener('beforeinstallprompt', function(e){
		deferredPrompt = e;
		if(deferredPrompt != null || deferredPrompt != undefined){
			if(superpwa_sw.disable_addtohome==1){
				deferredPrompt.preventDefault();
			}

			var a2hsBanner = document.getElementsByClassName("superpwa-sticky-banner");
			if(a2hsBanner.length){
				deferredPrompt.preventDefault();
				//Disable on desktop
				if(superpwa_sw.enableOnDesktop!=1 && !window.mobileCheck()){return ;}
				if(typeof super_check_bar_closed_or_not == 'function' && !super_check_bar_closed_or_not()){return ;}
				for (var i = 0; i < a2hsBanner.length; i++) {
					var showbanner = a2hsBanner[i].getAttribute("data-show");
					a2hsBanner[i].style.display="flex";
				}
			}
			document.cookie = "hidecta=no";
		}
	})

window.addEventListener('appinstalled', function(evt){
		var a2hsBanner = document.getElementsByClassName("superpwa-sticky-banner");
		if(a2hsBanner.length){
			for (var i = 0; i < a2hsBanner.length; i++) {
				var showbanner = a2hsBanner[i].getAttribute("data-show");
					    document.cookie = "hidecta=yes";
					a2hsBanner[i].style.display="none";
			    }
			}
	});
	
	var a2hsviaClass = document.getElementsByClassName("superpwa-add-via-class");
    if(a2hsviaClass !== null){
        for (var i = 0; i < a2hsviaClass.length; i++) {
          a2hsviaClass[i].addEventListener("click", addToHome); 
      }
    }

    function addToHome(){
		if(!deferredPrompt){return ;}
		deferredPrompt.prompt(); 
		deferredPrompt.userChoice.then(function(choiceResult) {
			if (choiceResult.outcome === "accepted") {
				var a2hsBanner = document.getElementsByClassName("superpwa-sticky-banner");
				if(a2hsBanner){
					for (var i = 0; i < a2hsBanner.length; i++) {
						var showbanner = a2hsBanner[i].getAttribute("data-show");
						a2hsBanner[i].style.display="none";
					}
				}//a2hsBanner if
				console.log("User accepted the prompt");
			}else{
				console.log("User dismissed the prompt");
			}
			deferredPrompt = null;
		});
	} // function closed addToHome


  });
}
window.mobileCheck = function() {
	  let check = false;
	  (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
	  return check;
	};

	window.addEventListener('load', function() {
		var manifestLink = document.querySelectorAll("link[rel='manifest']");
			if(manifestLink.length > 1){
				for (var i = 0; i < manifestLink.length; i++) {
					var href = manifestLink[i].getAttribute("href");
					if(href.indexOf("superpwa-manifest.json") == -1){
						manifestLink[i].remove();
					}
				}
			}
			// fix for href="#" in Ios safari standalone
			var ua = window.navigator.userAgent;
			var iOS = ua.match(/iPad/i) || ua.match(/iPhone/i);
			var webkit = ua.match(/WebKit/i);
			var iOSSafari = iOS && webkit && !ua.match(/CriOS/i);

			if(iOSSafari && (window.matchMedia('(display-mode: standalone)').matches)){
			setTimeout(function(){
				const anchor_fix = document.querySelectorAll("a[href='#']");
				if(anchor_fix.length > 1){
					for (var i = 0; i < anchor_fix.length; i++) {
						anchor_fix[i].setAttribute("href","javascript:void(0);");
					}
				}
			},600);
		}

	});

	document.addEventListener('DOMContentLoaded', function () {
		if (typeof pnScriptSetting !== 'undefined' && pnScriptSetting.superpwa_apk_only !== undefined && pnScriptSetting.superpwa_apk_only == 1) {
			const reffer = document.referrer; 
			if (reffer && reffer.includes('android-app://')) {
				sessionStorage.setItem('superpwa_mode', 'apk');
			}
		}
	});
	

	if (superpwa_sw.offline_form_addon_active) {
		navigator.serviceWorker.ready.then(function (registration) {
			return registration.sync.register('superpwa_form_sendFormData')
		}).then(function () {
			console.log('sync event registered');
		}).catch(function () {
			console.log('sync registration failed')
		});
		
	
		function superpwa_formSubmitOptions(event) {
			console.log('vikas here')
			var finalData = {};
			var inputElements = document.querySelectorAll('input, textarea, select');/*closest('form')*/
			for (let elem of inputElements) {
				if (elem.getAttribute('type') == 'radio') {
		
					let chk_name_radio = elem.getAttribute('name');
		
					var parent_div = elem.closest('.frm_opt_container')
		
					if (parent_div.getAttribute('aria-required') == 'true') {
		
						let chk_value = parent_div.querySelectorAll('input[name=\"' + chk_name_radio + '\"]:checked');
		
						if (chk_value.length == 0) {
		
							event.preventDefault();
		
							event.stopPropagation();
		
							alert('Please fill all mandatory fields');
		
							return;
						}
					}
				}
		
		
				if (!elem.value && ((elem.getAttribute('aria-required') == 'true' || elem.getAttribute('required')) && elem.offsetParent !== null)) {
					event.preventDefault();
					event.stopPropagation();
					alert('Please fill all mandatory fields');
					return;
				}
				var name = elem.getAttribute('name');
				if (name) {
					//name=name.replace(/[\[']/g, '_').replace(/[\]']/g, '');
					if (elem.getAttribute('type') == 'checkbox') {
						if (elem.checked) {
							finalData[name] = elem.value;
						}
					} else if (elem.getAttribute('type') == 'radio') {
						if (elem.checked) {
							finalData[name] = elem.value;
						}
					} else {
						finalData[name] = elem.value;
					}
				}
			}
		
			console.log(finalData);
		
			if (JSON.stringify(finalData) !== '{}') {
				var allData = {
					'form_data': finalData,
					'action': 'form_submit_data'
				};
				/* send data in serviceWorker */
				navigator.serviceWorker.ready.then((registration) => {
					console.log(allData);
					registration.active.postMessage(allData);
				});
			} else {
				event.preventDefault();
				event.stopPropagation();
				alert('Error occured during form submission, please try again');
				return;
			}
		}
		
		function handleGravityMultistep(btn_type, event) {
			let target_parent = event.parentNode.parentNode;
			let go_next = false;
			if (btn_type == 'previous') {
				target_parent.style.display = 'none';
				let prev_id = target_parent.previousElementSibling.id;
				let source_page = document.querySelector('input[name^=\"gform_source_page_number_\"]');
				let target_page = document.querySelector('input[name^=\"gform_target_page_number_\"]');
				source_page.value = parseInt(source_page.value) - 1;
				target_page.value = parseInt(target_page.value) - 1;
				if (prev_id) {
					document.getElementById(prev_id).style.display = 'block';
				}
			} else {
				let inputs = target_parent.querySelectorAll('input, textarea, select');
				for (let elem of inputs) {
					let req_flag = elem.getAttribute('aria-required');
					if (req_flag == 'true' && (elem.getAttribute('type') == 'checkbox' || elem.getAttribute('type') == 'radio')) {
						let chk_name = elem.getAttribute('name');
						let chk_value = target_parent.querySelectorAll('input[name=\"' + chk_name + '\"]:checked');
						if (!chk_value.length) {
							go_next = false;
							break;
						} else {
							go_next = true;
						}
					} else {
						if (!elem.value) {
							go_next = false;
							break;
						} else {
							go_next = true;
						}
					}
				}
				if (go_next == true) {
					target_parent.style.display = 'none';
					let next_id = target_parent.nextElementSibling.id;
					let source_page = document.querySelector('input[name^=\"gform_source_page_number_\"]');
					let target_page = document.querySelector('input[name^=\"gform_target_page_number_\"]');
					let gform_wrapper = document.querySelector('.gform_wrapper');
					let total_page = gform_wrapper.getAttribute('id').split('_')[2];
					source_page.value = parseInt(source_page.value) + 1;
					if (target_page < total_page) { target_page.value = parseInt(target_page.value) + 1; }
					else { target_page.value = 0; }
					if (next_id) {
						document.getElementById(next_id).style.display = 'block';
					}
				} else {
					alert('Please fill correct values in all mandatory fields');
				}
			}
		}
		
		window.addEventListener('online', (e) => { updateOnlineStatus(e) });
		
		function updateOnlineStatus(event) {
			window.location.reload();
		}
		window.onload = function (event) {
			console.log('Page Load');
			if (!navigator.onLine) {
				updateOfflineStatus(event);
			}
			window.addEventListener('offline', (e) => {
				updateOfflineStatus(e);
				reRenderPage();
			});
			fallbackForIosSync();
			var our_db;
			function fallbackForIosSync() {
				var isSyncSupported = ('serviceWorker' in navigator && 'SyncManager' in window);
				if (!isSyncSupported && navigator.onLine) {
					var indexedDBOpenRequest = indexedDB.open('superpwa-form', 2);
					indexedDBOpenRequest.onerror = function (error) {
						console.error('IndexedDB error:', error)
					}
					indexedDBOpenRequest.onupgradeneeded = function () {
						if (!this.result.objectStoreNames.contains('post_requests')) {
							this.result.createObjectStore('post_requests', { autoIncrement: true, keyPath: 'id' })
						}
					}
					indexedDBOpenRequest.onsuccess = function () {
						our_db = this.result;
						sendPostToServerAjax();
					}
				}
			}
		
			function reRenderPage() {
				var condition = navigator.onLine ? 'online' : 'offline';
				if (condition == 'offline') {
					var formElement = document.querySelector('form[method=\"post\"]');
					if (formElement && (formElement.classList.contains('frm-fluent-form') || formElement.querySelector('.frm_dropzone'))) {
						// window.location.reload();
					}
				}
			}
		
			function updateOfflineStatus(event) {
				var condition = navigator.onLine ? 'online' : 'offline';
				if (condition == 'offline') {
					setTimeout(() => {
						var formElement = document.querySelectorAll('form[method=\"post\"]');
						if (formElement && formElement.length > 0) {
							for (var i = 0; i < formElement.length; i++) {
								// Fluent Form Compatibility
		
								if (formElement[i].hasAttribute('data-form_instance') && formElement[i].classList.contains('frm-fluent-form')) {
									var class_to_remove = formElement[i].getAttribute('data-form_instance');
									formElement[i].classList.remove(class_to_remove)
								}
		
								// Fix for Formidable form not adding data due to anti-spam javascript
		
								if (formElement[i].hasAttribute('data-token')) {
									const antispamInput = document.createElement('input');
									antispamInput.type = 'hidden';
									antispamInput.value = formElement[i].getAttribute('data-token');
									antispamInput.name = 'antispam_token';
									formElement[i].appendChild(antispamInput);
								}
		
								if (formElement[i].querySelector('button[type=\"submit\"]')) { let ourEle = formElement[i].querySelector('button[type=\"submit\"]'); ourEle.removeAttribute('onclick'); ourEle.removeAttribute('onkeypress'); ourEle.replaceWith(ourEle.cloneNode(true)); }
		
								if (formElement[i].querySelector('button[type=\"button\"]')) { let ourEle = formElement[i].querySelector('button[type=\"button\"]'); ourEle.removeAttribute('onclick'); ourEle.removeAttribute('onkeypress'); ourEle.replaceWith(ourEle.cloneNode(true)); }
		
								if (formElement[i].querySelector('input[type=\"submit\"]')) { let ourEle = formElement[i].querySelector('input[type=\"submit\"]'); ourEle.removeAttribute('onclick'); ourEle.removeAttribute('onkeypress'); ourEle.replaceWith(ourEle.cloneNode(true)); }
		
								formElement[i].addEventListener('submit', superpwa_formSubmitOptions, true);
							}
						}
		
						//For Formidable Form ajax upload field
						var inputs = document.getElementsByTagName('input');
						if (inputs && inputs.length) {
							for (var i = 0; i < inputs.length; i++) {
								if (inputs[i].type.toLowerCase() == 'file') {
									if (document.querySelector('.dz-error-message')) {
										document.querySelector('.dz-error-message').remove();
										setTimeout(
											function () {
												document.querySelector('.dz-error-message').innerHTML = '';
											},
											500);
									}
								}
							}
						}
		
						// Fix for Formidable Ajax uploads
		
						var frm_dropzone_attrs = document.querySelectorAll('.frm_dropzone');
						if (frm_dropzone_attrs && frm_dropzone_attrs.length > 0) {
							frm_dropzone_attrs.forEach(ele => {
								var container_id = ele.id;
								container_id = container_id.match(/\d+/);
								const upload_input_parent = ele.closest('.frm_form_field');
								var upload_input = upload_input_parent.querySelector('.dz-hidden-input');
								if (upload_input) {
									upload_input.setAttribute("data-containerid", container_id);
								}
							});
						}
		
						var frm_dropzone_ele = document.querySelectorAll('.dz-hidden-input');
						if (frm_dropzone_ele && frm_dropzone_ele.length > 0) {
							frm_dropzone_ele.forEach(ele => {
								ele.addEventListener('change', (function (e) {
									if (e.target.files[0] || e.target) {
										var formElement = document.querySelector('form[method="post"]');
										var field_id = e.target.getAttribute("data-containerid");
										var field_id_label = 'file' + field_id;
										var object1 = {};
										object1['action'] = 'frm_submit_dropzone';
										object1['field_id'] = field_id;
										object1['form_id'] = document.querySelector('[name="form_id"]').value;
										object1['nonce'] = frm_js.nonce;
										object1['antispam_token'] = formElement.getAttribute('data-token');
										object1[field_id_label] = e.target.files[0];
										saveAddlAjaxSubmits(frm_js.ajax_url, object1);
									}
								}));
							});
						}
		
		
		
						// For Gravity Form multistep form
						let gform_next_buttons = document.querySelectorAll('.gform_next_button');
						let gform_previous_button = document.querySelectorAll('.gform_previous_button');
						if (gform_next_buttons.length > 0) {
							gform_next_buttons.forEach(button => {
								button.setAttribute('onclick', 'handleGravityMultistep("next",this)');
								button.setAttribute('onkeypress', 'handleGravityMultistep("next",this)');
								button.setAttribute('type', 'button');
							});
						}
						if (gform_previous_button.length > 0) {
							gform_previous_button.forEach(button => {
								button.setAttribute('onclick', 'handleGravityMultistep(\"previous\",this)');
								button.setAttribute('onkeypress', 'handleGravityMultistep(\"previous\",this)');
								button.setAttribute('type', 'button');
							});
						}
					}, 500);
				}
			}
		
			function sendPostToServerAjax() {
				var savedRequests = [];
				var objStore = getObjectStore('ajax_requests');
				if (!!objStore) {
					var req = getObjectStore('ajax_requests').openCursor() // FOLDERNAME is 'ajax_requests'
					req.onsuccess = async function (event) {
						var cursor = event.target.result
						if (cursor) {
							// Keep moving the cursor forward and collecting saved requests.
							savedRequests.push(cursor.value)
							cursor.continue()
						} else {
							if (savedRequests && savedRequests.length) {
								for (let savedRequest of savedRequests) {
									var formData = new FormData();
									for (const [key, value] of Object.entries(savedRequest.payload)) {
										formData.append(key, value);
									}
									var requestUrl = savedRequest.url
									var payload = JSON.stringify(savedRequest.payload)
									var method = savedRequest.method
									/*request to forms*/
									var headers = {
										'Accept': 'application/json, text/javascript, */*; q=0.01',
									} // if you have any other headers put them here
									fetch(superpwa_sw.ajax_url+'?action=superpwa_form_store_send', {
										headers: headers,
										method: method,
										body: formData
									}).then(function (response) {
										if (response.status < 400) {
											// If sending the POST request was successful, then remove it from the IndexedDB.
											getObjectStore('ajax_requests', 'readwrite').delete(savedRequest.id);
											return response.json();
										}
										return false;
									}).then(function (response) {
										if (response) {
											sendPostToServer([response[0], savedRequest.payload.field_id]);
											console.log('Form Submitted with ajax fields : success');
										} else {
											sendPostToServer();
											console.log('Form Submitted without ajax fields : ajax fields present but their saving failed to db');
										}
									});
								}
							} else {
								sendPostToServer();
								console.log('Form Submitted without ajax fields : no values present in ajax_requests object');
							}
						}
					}
					req.onerror = function (error) {
						sendPostToServer();
					}
				} else {
					sendPostToServer();
				}
			}
		
			function sendPostToServer(ajax_params = null) {
				var savedRequests = [];
				var objStore = getObjectStore('post_requests');
				console.log(objStore)
				if (!!objStore) {
					var req = getObjectStore('post_requests').openCursor();
					req.onsuccess = async function (event) {
						var cursor = event.target.result;
						if (cursor) {
							// Keep moving the cursor forward and collecting saved requests.
							savedRequests.push(cursor.value);
							cursor.continue();
						} else {
							// Process each saved request.
							console.log(savedRequests)
							for (let savedRequest of savedRequests) {
								var formData = new FormData();
								for (const [key, value] of Object.entries(savedRequest.payload)) {
									formData.append(key, value);
								}
								if (ajax_params && ajax_params.length && ajax_params[0]) {
									formData.set('item_meta[' + ajax_params[1] + ']', ajax_params[0]);
								}
								var requestUrl = savedRequest.url;
								var method = savedRequest.method;
								/* Request to admin-ajax.php */
								var headers = {
									'Accept': 'application/json, text/javascript, */*; q=0.01',
									// Add any other headers here if needed
								};
								try {
									console.log('requestUrl')
									console.log(requestUrl)
									const response = await fetch(requestUrl, {
										headers: headers,
										method: method,
										body: formData
									});
									console.log('server response', response);
									if (response.status < 400) {
										// If sending the POST request was successful, then remove it from the IndexedDB.
										try {
											var forDataSave = {};
											formData.forEach(function (value, key) {
												forDataSave[key] = value;
											});
											var forDataSaveJson = JSON.stringify(forDataSave);
											const response = await fetch(superpwa_sw.ajax_url+'?action=superpwa_form_store_send', {
												headers: headers,
												method: method,
												body: forDataSaveJson,
											});
											console.log('saved request', savedRequest);
											if (response.status < 400) {
												// If successful, remove the request from IndexedDB.
												await getObjectStore('post_requests', 'readwrite').delete(savedRequest.id);
											}
										} catch (error) {
											console.error('Send to Server failed:', error);
											throw error;
										}
									} else {
										console.error('Server responded with an error:', response.status);
										// Handle the error as needed
									}
								} catch (error) {
									console.error('Send to Server failed:', error);
									// Handle the error as needed
									throw error; // Rethrow the error if necessary
								}
							}
						}
					};
				}
			}
		
			function getObjectStore(storeName, mode) {
				if (!our_db) { return null; }
				// retrieve our object store
				return our_db.transaction(storeName, mode).objectStore(storeName)
			}
		
			function saveAddlAjaxSubmits(submit_url, obj) {
				/*send data in serviceWorker*/
				var indexedDBOpenRequest = indexedDB.open('superpwa-form', 2);
				indexedDBOpenRequest.onerror = (event) => {
					console.error('a post form request has been not added to IndexedDB');
				};
				indexedDBOpenRequest.onupgradeneeded = (event) => {
					const db = event.target.result;
					if (!db.objectStoreNames.contains('ajax_requests')) {
						db.createObjectStore('ajax_requests', { autoIncrement: true, keyPath: 'id' });
					}
				}
				indexedDBOpenRequest.onsuccess = (event) => {
					const db = event.target.result;
					var request = db.transaction('ajax_requests', 'readwrite').objectStore('ajax_requests').add({
						url: submit_url,
						payload: obj,
						method: 'POST'
					})
					request.onsuccess = function (event) {
						console.log('a post form request has been added to indexedb')
					}
					request.onerror = function (error) {
						console.error(error)
					}
				}
			}
		}
	}