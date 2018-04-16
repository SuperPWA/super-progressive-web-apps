<?php
/**
 * Service worker related functions of SuperPWA
 *
 * @since 1.0
 * @function	superpwa_sw()				Service worker filename, absolute path and link
 * @function	superpwa_generate_sw()		Generate and write service worker into sw.js
 * @function	superpwa_sw_template()		Service worker tempalte
 * @function	superpwa_register_sw()		Register service worker
 * @function	superpwa_delete_sw()		Delete service worker
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Service worker filename, absolute path and link
 *
 * For Multisite compatibility. Used to be constants defined in superpwa.php
 * On a multisite, each sub-site needs a different service worker.
 *
 * @param $arg 	filename for service worker filename (replaces SUPERPWA_SW_FILENAME)
 *				abs for absolute path to service worker (replaces SUPERPWA_SW_ABS)
 *				src for link to service worker (replaces SUPERPWA_SW_SRC). Default value
 *
 * @return String filename, absolute path or link to manifest.  
 * @since 1.6
 */
function superpwa_sw( $arg = 'src' ) {
	
	$sw_filename = 'superpwa-sw' . superpwa_multisite_filename_postfix() . '.js';
	
	switch( $arg ) {
		
		// Name of service worker file
		case 'filename': 
			return $sw_filename;
			break;
		
		// Absolute path to service worker. SW must be in the root folder	
		case 'abs':
			return trailingslashit( ABSPATH ) . $sw_filename;
			break;
		
		// Link to service worker
		case 'src':
		default:
			return trailingslashit( get_bloginfo('wpurl') ) . $sw_filename;
			break;
	}
}

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
	
	if ( ! superpwa_put_contents( superpwa_sw( 'abs' ), $sw ) ) {
		return false;
	}
	
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
 * To learn more and add one to your website, visit - https://superpwa.com
 */
 
const cacheName = '<?php echo parse_url( get_bloginfo( 'wpurl' ), PHP_URL_HOST ) . '-superpwa-' . SUPERPWA_VERSION; ?>';
const startPage = '<?php echo superpwa_get_start_url(); ?>';
const offlinePage = '<?php echo get_permalink( $settings['offline_page'] ) ? superpwa_httpsify( get_permalink( $settings['offline_page'] ) ) : superpwa_httpsify( get_bloginfo( 'wpurl' ) ); ?>';
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
	
	// Return if request url is from an external domain.
	if ( new URL(e.request.url).origin !== location.origin )
		return;
	
	// For POST requests, do not use the cache. Serve offline page if offline.
	if ( e.request.method !== 'GET' ) {
		e.respondWith(
			fetch(e.request).catch( function() {
				return caches.match(offlinePage);
			})
		);
		return;
	}
	
	// Revving strategy
	if ( e.request.mode === 'navigate' && navigator.onLine ) {
		e.respondWith(
			fetch(e.request).then(function(response) {
				return caches.open(cacheName).then(function(cache) {
					cache.put(e.request, response.clone());
					return response;
				});  
			})
		);
		return;
	}

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
	
	wp_enqueue_script( 'superpwa-register-sw', SUPERPWA_PATH_SRC . 'public/js/register-sw.js', array(), null, true );
	wp_localize_script( 'superpwa-register-sw', 'superpwa_sw', array(
			'url' => superpwa_sw( 'src' ),
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
	return superpwa_delete( superpwa_sw( 'abs' ) );
}