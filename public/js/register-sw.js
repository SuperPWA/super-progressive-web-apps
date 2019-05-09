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
	}

	/**
 	* Inject style css of snackbar
 	*/
	function injectSnackbarCss() {
	}

	/**
 	* Show the state of the mode of connectivity to the user :  back onLine | go offLine
 	*/
	function runOnlineOfflineIndicator() {
	}

	/**
 	* Show with the given message in the snackbar
 	* @param {String} msg 
 	*/
	function showSnackbar(msg) {
	}

	/**
	* Hide snackbar
	*/
	function hideSnackbar() {
	}

	/****************** End : Online/Offline Indicator ******************/
}