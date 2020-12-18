if ('serviceWorker' in navigator) {
  window.addEventListener('load', function() {
	navigator.serviceWorker.register(superpwa_sw.url)
	.then(function(registration) { console.log('SuperPWA service worker ready'); registration.update(); })
	.catch(function(error) { console.log('Registration failed with ' + error); });


	var deferredPrompt;
	window.addEventListener('beforeinstallprompt', function(e){
		deferredPrompt = e;
		if(deferredPrompt != null || deferredPrompt != undefined){
			var a2hsBanner = document.getElementsByClassName("superpwa-sticky-banner");
			if(a2hsBanner.length){
				deferredPrompt.preventDefault();
				for (var i = 0; i < a2hsBanner.length; i++) {
					var showbanner = a2hsBanner[i].getAttribute("data-show");
					a2hsBanner[i].style.display="flex";
				}
			}
		}
	})

	window.addEventListener('appinstalled', function(evt){
		var a2hsBanner = document.getElementsByClassName("superpwa-sticky-banner");
		if(a2hsBanner.length){
			for (var i = 0; i < a2hsBanner.length; i++) {
				var showbanner = a2hsBanner[i].getAttribute("data-show");
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
