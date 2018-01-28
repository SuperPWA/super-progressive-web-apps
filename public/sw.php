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

/**
 * Service Worker of SuperPWA
 * https://wordpress.org/plugins/super-progressive-web-apps/
 */
 
const cacheName = '<?php echo parse_url( get_bloginfo( 'wpurl' ) )['host'] . '-superpwa-' . SUPERPWA_VERSION; ?>';
const startPage = '<?php echo trailingslashit(get_bloginfo( 'wpurl' )); ?>';
const offlinePage = '<?php echo get_permalink($settings['offline_page']) ? trailingslashit(get_permalink($settings['offline_page'])) : trailingslashit(get_bloginfo( 'wpurl' )); ?>';
const fallbackImage = '<?php echo $settings['icon']; ?>';
const filesToCache = [startPage, offlinePage, fallbackImage];
const neverCacheUrls = [/\/wp-admin/,/\/wp-login/,/preview=true/];

// Install
self.addEventListener('install', function(e) {
	console.log('SuperPWA service worker installation');
	e.waitUntil(
		caches.open(cacheName).then(function(cache) {
			console.log('SuperPWA service worker caching dependencies');
			return cache.addAll(filesToCache);
		})
	);
});

// Activate
self.addEventListener('activate', function(e) {
	console.log('SuperPWA service worker activation');
	e.waitUntil(
		caches.keys().then(function(keyList) {
			return Promise.all(keyList.map(function(key) {
				if ( key !== cacheName ) {
					console.log('SuperPWA old cache removed', key);
					return caches.delete(key);
				}
			}));
		})
	);
	return self.clients.claim();
});

// Fetch
self.addEventListener('fetch', function(e) {
	
	// Return if the current request url is in the never cache list
	if ( ! neverCacheUrls.every(checkNeverCacheList, e.request.url) ) {
	  console.log('SuperPWA: Current page is excluded from cache');
	  return;
	}
	
	// Return if request url protocal isn't http or https
	if ( ! e.request.url.match(/^(http|https):\/\//i) )
		return;

	e.respondWith(
		caches.match(e.request).then(function(response) {
			return response || fetch(e.request).then(function(response) {
				return caches.open(cacheName).then(function(cache) {
					cache.put(e.request, response.clone());
					return response;
				});  
			});
		}).catch(function() {
			return caches.match(offlinePage);
		})
	);
});

// Check if current url is in the neverCacheUrls list
function checkNeverCacheList(url) {
	if ( this.match(url) ) {
		return false;
	}
	return true;
}
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