if ('serviceWorker' in navigator) {
  window.addEventListener('load', function() {
	navigator.serviceWorker.register(superpwa_sw.url)
	.then(function(registration) { console.log('SuperPWA service worker ready'); registration.update(); })
	.catch(function(error) { console.log('Registration failed with ' + error); });
  });
}