if ('serviceWorker' in navigator) {
  window.addEventListener('load', function() {
	navigator.serviceWorker.register(superpwa_sw.url)
	.then(function() { console.log('SuperPWA service worker ready'); });
  });
}