<?php
/**
 * Operations of the plugin are included here. 
 *
 * @since 1.0
 * @function	superpwa_generate_sw()		Generate and write service worker into sw.js
 * @function	superpwa_sw_template()		Service Worker Tempalte
 * @function	superpwa_register_sw()		Register service worker
 * @function	superpwa_delete_sw()		Delete Service Worker
 */

// Exit if accessed directly
if ( ! defined('ABSPATH') ) exit;

/**
 * Generate and write service worker into superpwa-sw.js
 *
 * @return true on success, false on failure.
 * @since	1.0
 */
function superpwa_generate_sw() {
	
	// Get Settings
	$settings = superpwa_get_settings();
	
	// Get the service worker tempalte
	$sw = superpwa_sw_template();
	
	// Delete service worker if it exists
	superpwa_delete_sw();
	
	if ( ! superpwa_put_contents( SUPERPWA_SW_ABS, $sw ) )
		return false;
	
	return true;
}

/**
 * Service Worker Tempalte
 *
 * @return	String	Contents to be written to superpwa-sw.js
 * @since	1.0
 */
function superpwa_sw_template() {
	
	// Get Settings
	$settings = superpwa_get_settings();
	
	// Start output buffer. Everything from here till ob_get_clean() is returned
	ob_start();  ?>
'use strict';
const cacheName = '<?php echo parse_url( get_bloginfo( 'wpurl' ) )['host'] . '-ver-' . SUPERPWA_VERSION; ?>';
const startPage = '/';
const offlinePage = '/';
const fallbackImage = '<?php echo $settings['icon']; ?>';
const filesToCache = [startPage, offlinePage, fallbackImage];
const neverCache = [/\/wp-admin/,/\/wp-login/,/preview=true/];

// Install
self.addEventListener('install', function(e) {
	console.log('SuperPWA Service Worker Installation');
	e.waitUntil(
		caches.open(cacheName).then(function(cache) {
			console.log('SuperPWA Service Worker Caching Dependencies');
			return cache.addAll(filesToCache);
		})
	);
});

// Activate
self.addEventListener('activate', function(e) {
	console.log('SuperPWA Service Worker Activation');
	e.waitUntil(
		caches.keys().then(function(keyList) {
			return Promise.all(keyList.map(function(key) {
				if (key !== cacheName) {
					console.log('SuperPWA Service Worker Old Cache Removed', key);
					return caches.delete(key);
				}
			}));
		})
	);
	return self.clients.claim();
});

// Fetch
self.addEventListener('fetch', function(e) {
  console.log('SuperPWA fetched ', e.request.url);
  e.respondWith(
    caches.match(e.request).then(function(response) {
      return response || fetch(e.request);
    })
  );
});
<?php return ob_get_clean();
}

/**
 * Register service worker
 *
 * @since	1.0
 * @refer	https://developers.google.com/web/fundamentals/primers/service-workers/registration#conclusion
 */
function superpwa_register_sw() {
	
	wp_enqueue_script('superpwa-register-sw', SUPERPWA_PATH_SRC . 'public/js/register-sw.js', array(), null, true );
	wp_localize_script('superpwa-register-sw', 'superpwa_sw', array(
			'url' => SUPERPWA_SW_SRC,
		)
	);
}
add_action( 'wp_enqueue_scripts', 'superpwa_register_sw' );

/**
 * Delete Service Worker
 *
 * @return true on success, false on failure
 * @since	1.0
 */
function superpwa_delete_sw() {
	
	return superpwa_delete( SUPERPWA_SW_ABS );
}