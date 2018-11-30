<?php
/**
 * Service worker related functions of SuperPWA
 *
 * @since 1.0
 * 
 * @function	superpwa_sw()					Service worker filename, absolute path and link
 * @function	superpwa_generate_sw()			Generate and write service worker into sw.js
 * @function	superpwa_sw_template()			Service worker tempalte
 * @function	superpwa_register_sw()			Register service worker
 * @function	superpwa_delete_sw()			Delete service worker
 * @function 	superpwa_offline_page_images()	Add images from offline page to filesToCache
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Returns the Service worker's filename.
 *
 * @since 2.0
 *
 * @return string
 */
function superpwa_get_sw_filename() {
    return apply_filters( 'superpwa_sw_filename', 'superpwa-sw' . superpwa_multisite_filename_postfix() . '.js' );
}

/**
 * Service worker filename, absolute path and link
 *
 * For Multisite compatibility. Used to be constants defined in superpwa.php
 * On a multisite, each sub-site needs a different service worker.
 *
 * @param $arg 	filename for service worker filename (replaces SUPERPWA_SW_FILENAME)
 *				abs for absolute path to service worker (replaces SUPERPWA_SW_ABS)
 *				src for relative link to service worker (replaces SUPERPWA_SW_SRC). Default value
 *
 * @return (string) filename, absolute path or link to manifest.
 * 
 * @since 1.6
 * @since 1.7 src to service worker is made relative to accomodate for domain mapped multisites.
 * @since 1.8 Added filter superpwa_sw_filename.
 */
function superpwa_sw( $arg = 'src' ) {
	
	$sw_filename = superpwa_get_sw_filename();
	
	switch( $arg ) {
		// TODO: Case `filename` can be deprecated in favor of @see superpwa_get_sw_filename().
		// Name of service worker file
		case 'filename':
			return $sw_filename;
			break;

		// TODO: Case `abs` can be deprecated as the file would be generated dynamically.
		// Absolute path to service worker. SW must be in the root folder.
		case 'abs':
			return trailingslashit( ABSPATH ) . $sw_filename;
			break;

		// TODO: Case `src` and default can be deprecated in favor of @see superpwa_get_sw_filename().
		// Link to service worker
		case 'src':
		default:
			return parse_url( trailingslashit( network_site_url() ) . $sw_filename, PHP_URL_PATH );
			break;
	}
}

/**
 * Generate and write service worker into superpwa-sw.js
 *
 * @return (boolean) true on success, false on failure.
 * 
 * @since 1.0
 *
 * @deprecated 2.0 No longer used by internal code.
 */
function superpwa_generate_sw() {
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
 * @return (string) Contents to be written to superpwa-sw.js
 * 
 * @since 1.0
 * @since 1.7 added filter superpwa_sw_template
 * @since 1.9 added filter superpwa_sw_files_to_cache
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
const filesToCache = [<?php echo apply_filters( 'superpwa_sw_files_to_cache', 'startPage, offlinePage' ); ?>];
const neverCacheUrls = [<?php echo apply_filters( 'superpwa_sw_never_cache_urls', '/\/wp-admin/,/\/wp-login/,/preview=true/' ); ?>];

// Install
self.addEventListener('install', function(e) {
	console.log('SuperPWA service worker installation');
	e.waitUntil(
		caches.open(cacheName).then(function(cache) {
			console.log('SuperPWA service worker caching dependencies');
			filesToCache.map(function(url) {
				return cache.add(url).catch(function (reason) {
					return console.log('SuperPWA: ' + String(reason) + ' ' + url);
				});
			});
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
	  console.log( 'SuperPWA: Current request is excluded from cache.' );
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
<?php return apply_filters( 'superpwa_sw_template', ob_get_clean() );
}

/**
 * Register service worker
 *
 * @refer https://developers.google.com/web/fundamentals/primers/service-workers/registration#conclusion
 * 
 * @since 1.0
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
 * 
 * @since 1.0
 *
 * @deprecated 2.0 No longer used by internal code.
 */
function superpwa_delete_sw() {
	return superpwa_delete( superpwa_sw( 'abs' ) );
}

/**
 * Add images from offline page to filesToCache
 * 
 * If the offlinePage set by the user contains images, they need to be cached during sw install. 
 * For most websites, other assets (css, js) would be same as that of startPage which would be cached
 * when user visits the startPage the first time. If not superpwa_sw_files_to_cache filter can be used.
 * 
 * @param (string) $files_to_cache Comma separated list of files to cache during service worker install
 * 
 * @return (string) Comma separated list with image src's appended to $files_to_cache
 * 
 * @since 1.9
 */
function superpwa_offline_page_images( $files_to_cache ) {
	
	// Get Settings
	$settings = superpwa_get_settings();
	
	// Retrieve the post
	$post = get_post( $settings['offline_page'] );
	
	// Return if the offline page is set to default
	if( $post === NULL ) {
		return $files_to_cache;
	}
	
	// Match all images
	preg_match_all( '/<img[^>]+src="([^">]+)"/', $post->post_content, $matches );
	
	// $matches[1] will be an array with all the src's
	if( ! empty( $matches[1] ) ) {
		return superpwa_httpsify( $files_to_cache . ', \'' . implode( '\', \'', $matches[1] ) . '\'' );
	}
	
	return $files_to_cache;
}
add_filter( 'superpwa_sw_files_to_cache', 'superpwa_offline_page_images' );