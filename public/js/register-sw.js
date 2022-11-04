if ('serviceWorker' in navigator) {
  window.addEventListener('load', function() {
	navigator.serviceWorker.register(superpwa_sw.url)
	.then(function(registration) { console.log('SuperPWA service worker ready'); 
		registration.update(); 
		subOnlineOfflineIndicator();
	})
	.catch(function(error) { console.log('Registration failed with ' + error); });

	/****************** Start : Online/Offline Indicator ******************/

	// Variables & default values
	const snackbarTimeToHide = 5000; // 5s
	let isOffline = false,
		snackbarTimeoutHide = null,
		goOfflineMsg = 'You\'re currently offline',
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
		button.setAttribute('aria-label', 'snackbar-close');
		button.addEventListener('click', hideSnackbar);
		button.innerHTML = '&times;';

		container.appendChild(button);

		document.body.appendChild(container);

		window.addEventListener('online', runOnlineOfflineIndicator);
		window.addEventListener('offline', runOnlineOfflineIndicator);

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
			showSnackbar(goOfflineMsg);
			isOffline = true;
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
 });
