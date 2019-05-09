if ('serviceWorker' in navigator) {
	window.addEventListener('load', function () {
		navigator.serviceWorker.register(superpwa_sw.url)
			.then(function (registration) {
				console.log('SuperPWA service worker ready');
				registration.update();
				subOnlineOfflineIndicator();
			})
			.catch(function (error) { console.log('Registration failed with ' + error); });
	});

	/****************** Start : Online/Offline Indicator ******************/

	// Variables & default values
	let isOffline = false,
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
		button.addEventListener('click', hideMsg);
		button.innerHTML = '&times;';

		container.appendChild(button);

		document.body.appendChild(container);

		window.addEventListener('online', runOnlineOfflineIndicator);
		window.addEventListener('offline', runOnlineOfflineIndicator);
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
			background: #dbb11c;
			border: none;
			font-size: 28px;
			font-weight: normal;
			border-radius: 0 5px 5px 0;
			color: #FFF;
			font-family: Arial, Helvetica, sans-serif;
		}

		.snackbar .snackbar-close:hover,
		.snackbar .snackbar-close:focus {
			background: #ddb728;
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
				showMsg(backOnlineMsg);
			}
			isOffline = false;
		} else {
			showMsg(goOfflineMsg);
			isOffline = true;
		}
	}

	/**
 	* Show with the given message in the snackbar
 	* @param {String} msg 
 	*/
	function showSnackbar(msg) {
		document.getElementById('msgOffline').innerHTML = msg;
		document.body.classList.add('snackbar--show');
	}

	/**
	* Hide snackbar
	*/
	function hideSnackbar() {
		document.body.classList.remove('snackbar--show');
	}

	/****************** End : Online/Offline Indicator ******************/
}