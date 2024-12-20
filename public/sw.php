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
 *				src for link to service worker (replaces SUPERPWA_SW_SRC). Default value
 *
 * @return (string) filename, absolute path or link to manifest.
 * 
 * @since 1.6
 * @since 1.7 src to service worker is made relative to accomodate for domain mapped multisites.
 * @since 1.8 Added filter superpwa_sw_filename.
 * @since 2.0 src actually returns the link and the URL_PATH is extracted in superpwa_register_sw().
 * @since 2.0 src uses home_url instead of network_site_url since manifest is no longer in the root folder.
 */
function superpwa_sw( $arg = 'src' ) {
	
	$sw_filename = superpwa_get_sw_filename();
	
	switch( $arg ) {
		// TODO: Case `filename` can be deprecated in favor of @see superpwa_get_sw_filename().
		// Name of service worker file
		case 'filename':
			return $sw_filename;
			break;

		/**
		* Absolute path to service worker. SW must be in the root folder.
		* 
		* @since 2.0 service worker is no longer a physical file and absolute path doesn't make sense. 
		* Also using home_url instead of network_site_url in "src" in 2.0 changes the apparent location of the file. 
		* However, absolute path is preserved at the "old" location, so that phyiscal files can be deleted when upgrading from pre-2.0 versions.
		*/
		case 'abs':
			return trailingslashit( ABSPATH ) . $sw_filename;
			break;

		// Link to service worker
		case 'src':
		default:
		
			// Get Settings
			$settings = superpwa_get_settings();
			
			/**
			 * For static file, return site_url and network_site_url
			 * 
			 * Static files are generated in the root directory. 
			 * The site_url template tag retrieves the site url for the 
			 * current site (where the WordPress core files reside).
			 */
			if ( $settings['is_static_sw'] === 1 ) {
				return trailingslashit( network_site_url() ) . $sw_filename;
			}
			
			// For dynamic files, return the home_url
			return superpwa_home_url() . $sw_filename;
			
			break;
	}
}

/**
 * Generate and write service worker into superpwa-sw.js
 *
 * Starting with 2.0, files are only generated if dynamic files are not possible. 
 * Some webserver configurations does not load WordPress and attempts to server files directly
 * from the server. This returns 404 when files do not exist physically. 
 * 
 * @return (boolean) true on success, false on failure.
 * 
 * @author Arun Basil Lal
 *
 * @since 1.0
 * @since 2.0 Deprecated since Service worker is generated on the fly {@see superpwa_generate_sw_and_manifest_on_fly()}.
 * @since 2.0.1 No longer deprecated since physical files are now generated in certain cases. See funtion description. 
 *
 */
function superpwa_get_language_shortcode() {
    return apply_filters( 'wpml_current_language', null );
}

function superpwa_generate_sw() {
	
	// Delete service worker if it exists
	superpwa_delete_sw();

	// check if sw is already generated. To avoid generation loop on settings page when superpwa_file_exists returns false.
	global $superpwa_sw_generated;

	if($superpwa_sw_generated){
		return true;
	}
	
	// Get Settings
	$settings = superpwa_get_settings();
	// Return true if dynamic file returns a 200 response.
	if ( superpwa_file_exists( home_url( '/' ) . superpwa_get_sw_filename() ) && defined( 'WP_CACHE' ) && ! WP_CACHE ) {
		
		// set file status as dynamic file in database.
		$settings['is_static_sw'] = 0;
		
		// Write settings back to database.
		update_option( 'superpwa_settings', $settings );
		
		return true;
	}
	
	$dynamic_check = (isset($settings['startpage_type']) && $settings['startpage_type'] =='active_url' && function_exists('superpwa_pro_init'))?false:true;
	
	if ( $dynamic_check && superpwa_put_contents( superpwa_sw( 'abs' ), superpwa_sw_template() ) ) {
		
		// set file status as satic file in database.
		$settings['is_static_sw'] = 1;
		
		// Write settings back to database.
		update_option( 'superpwa_settings', $settings );

		// set sw generated to true.
		$superpwa_sw_generated = true;
		
		return true;
	}
	
	return false;
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

	$cache_version = SUPERPWA_VERSION;
                
    if(isset($settings['force_update_sw_setting']) && $settings['force_update_sw_setting'] !=''){
      $cache_version =   $settings['force_update_sw_setting'];
      if(!version_compare($cache_version,SUPERPWA_VERSION, '>=') ){
        $cache_version = SUPERPWA_VERSION;
      }
    }

	if (function_exists('superpwa_home_url')) {
		$superpwa_home_url = superpwa_home_url();
	}
   
	// Start output buffer. Everything from here till ob_get_clean() is returned
	ob_start();  ?>
'use strict';

/**
 * Service Worker of SuperPWA
 * To learn more and add one to your website, visit - https://superpwa.com
 */
 
const cacheName = '<?php echo esc_url(wp_parse_url( get_bloginfo( 'url' ), PHP_URL_HOST ) . '-superpwa-' .esc_html($cache_version)); ?>';
const startPage = '<?php echo esc_url(superpwa_get_start_url()); ?>';
const offlinePage = '<?php echo esc_url(superpwa_get_offline_page()); ?>';

const filesToCache = [<?php 
// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo apply_filters( 'superpwa_sw_files_to_cache', 'startPage, offlinePage' ); ?>];
const neverCacheUrls = [<?php echo esc_html(apply_filters( 'superpwa_sw_never_cache_urls', '/\/wp-admin/,/\/wp-login/,/preview=true/' )); ?>];

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

// Range Data Code
var fetchRangeData = function(event){
    var pos = Number(/^bytes\=(\d+)\-$/g.exec(event.request.headers.get('range'))[1]);
            console.log('Range request for', event.request.url, ', starting position:', pos);
            event.respondWith(
              caches.open(cacheName)
              .then(function(cache) {
                return cache.match(event.request.url);
              }).then(function(res) {
                if (!res) {
                  return fetch(event.request)
                  .then(res => {
                    return res.arrayBuffer();
                  });
                }
                return res.arrayBuffer();
              }).then(function(ab) {
                return new Response(
                  ab.slice(pos),
                  {
                    status: 206,
                    statusText: 'Partial Content',
                    headers: [
                      // ['Content-Type', 'video/webm'],
                      ['Content-Range', 'bytes ' + pos + '-' +
                        (ab.byteLength - 1) + '/' + ab.byteLength]]
                  });
              }));
}

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
	
    <?php if(!isset($settings['cache_external_urls']) || (isset($settings['cache_external_urls']) && $settings['cache_external_urls'] !== '1')){	?>
	// Return if request url is from an external domain.
	if ( new URL(e.request.url).origin !== location.origin )
		return;
    <?php }	?>

	<?php $active_addons = get_option( 'superpwa_active_addons', array() );
		if ( in_array('offline_form_for_superpwa', $active_addons) ){
	     ?>
			if ( e.request.referrer.match(/^(wp-admin):\/\//i) ) return;

			if ( e.request.method !== 'GET' ) {

				e.respondWith(

					fetch(e.request)

						.catch(error => {

							if(e.request.method == 'POST' ){
								console.log(form_data)
								saveOfflineFormPostRequests(e.request.url,form_data);

								return new Response(`
									<meta name="viewport" content="width=device-width, initial-scale=1.0">
									<p align="center">
										<br><br><br>
										<h2 align="center">Your form submission is saved. It will be submitted when you are back online</h2>
									</p>
									<p align="center">
										<button 
											type="button" 
											style="background: #2271b1; padding: 10px 20px; color: #fff; text-align: center; border-radius: 60px; font-size: 16px; margin: 0 auto 15px; text-decoration: none; display: inline-block; cursor: pointer;" 
											onclick="window.location=superpwa_home_url;">
											Go to Home
										</button>
									</p>
									<script>
										var superpwa_home_url = "<?php echo esc_url($superpwa_home_url)?>";
									</script>
								`, {
									status: 200,
									statusText: 'Form saved in offline mode',
									headers: new Headers({
										'Content-Type': 'text/html'
									})
								});



							}else{

								console.log('inside else')
								return caches.match(offlinePage);

							}
						})

				);

				return;

			}
	<?php } ?>
			
			// For Range Headers
			if (e.request.headers.has('range')) {
				return;
			}
			// Revving strategy
			if ( (e.request.mode === 'navigate' || e.request.mode === 'cors') && navigator.onLine ) {
				e.respondWith(
					fetch(e.request).then(function(response) {
						return caches.open(cacheName).then(function(cache) {
							cache.put(e.request, response.clone());
							return response;
						});  
					}).catch(function(){
						// If the network is unavailable, get
						return cache.match(e.request.url);
					})
				);
				return;
			}

			//strategy_replace_start
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
			//strategy_replace_end


});

// Check if current url is in the neverCacheUrls list
function checkNeverCacheList(url) {
	if ( this.match(url) ) {
		return false;
	}
	return true;
}
<?php
 if(isset($settings['analytics_support']) && $settings['analytics_support']==1){
	echo 'importScripts("https://storage.googleapis.com/workbox-cdn/releases/6.0.2/workbox-sw.js");
	            if(workbox.googleAnalytics){
                  try{
                    workbox.googleAnalytics.initialize();
                  } catch (e){ console.log(e.message); }
                }';    
}
?>
<?php
	$active_addons = get_option( 'superpwa_active_addons', array() );
	if ( in_array('offline_form_for_superpwa', $active_addons) ){
		echo  apply_filters( 'superpwa_offline_form_sw_template', '' );
	}
	return apply_filters( 'superpwa_sw_template', ob_get_clean() );
	
}

/**
 * Register service worker
 *
 * @refer https://developers.google.com/web/fundamentals/primers/service-workers/registration#conclusion
 * 
 * @since 1.0
 */
function superpwa_register_sw() {
	
	$settings = superpwa_get_settings(); 
	$include_script = true;

	if(isset($settings['exclude_homescreen']) && !empty($settings['exclude_homescreen'])){
		$exclude_homescrn_setting = $settings['exclude_homescreen'];
	    $exclude_homescreen = explode(",",$settings['exclude_homescreen']);

	    $siteUrl = filter_input(INPUT_SERVER, 'REQUEST_URI');

	    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http").'://'.$_SERVER['HTTP_HOST'].$siteUrl;
	    if(in_array($actual_link, $exclude_homescreen)){
    	  $include_script = false;
	    }
	}

	if($include_script){
		$active_addons = get_option( 'superpwa_active_addons', array() );
		$offline_form_addon_active = false;
		$ajax_url = admin_url( 'admin-ajax.php' );
		if ( in_array('offline_form_for_superpwa', $active_addons) ){
			$offline_form_addon_active = true;
		}
		wp_enqueue_script( 'superpwa-register-sw', SUPERPWA_PATH_SRC . 'public/js/register-sw.js', array(), SUPERPWA_VERSION, true );
		$superpwa_sw_version = isset($settings['force_update_sw_setting'])? $settings['force_update_sw_setting'] : time();
		$localize = array(
				'url' => wp_parse_url( superpwa_sw( 'src' ), PHP_URL_PATH ). superpwa_nginx_server_fix( superpwa_sw( 'src' ) ). '?'.$superpwa_sw_version,
				'disable_addtohome' => isset($settings['disable_add_to_home'])? $settings['disable_add_to_home'] : 0,
				'enableOnDesktop'=> false,
				'offline_form_addon_active' =>$offline_form_addon_active,
				'ajax_url' =>$ajax_url,
				'offline_message'=> !isset($settings['offline_message']) ? 1 : $settings['offline_message'],
				'offline_message_txt'=> !isset($settings['offline_message_txt']) ? esc_html__('You are currently offline.','super-progressive-web-apps') : $settings['offline_message_txt'],
			);
		$localize = apply_filters('superpwa_sw_localize_data', $localize);
		wp_localize_script( 'superpwa-register-sw', 'superpwa_sw',  $localize);
    }

}
add_action( 'wp_enqueue_scripts', 'superpwa_register_sw' );

/**
 * Delete Service Worker
 *
 * @return true on success, false on failure
 * 
 * @author Arun Basil Lal
 * 
 * @since 1.0
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


/**
 * Exclude Urls from Cache list of service worker
 *
 * @since 2.1.2
 */
function superpwa_exclude_urls_cache_sw($never_cacheurls){

	// Get Settings
	$settings = superpwa_get_settings();
	if(isset($settings['excluded_urls']) && !empty($settings['excluded_urls'])){

		$exclude_from_cache     = $settings['excluded_urls']; 

		$exclude_from_cache     = str_replace('/', '\/', $exclude_from_cache);     
		$exclude_from_cache     = '/'.str_replace(',', '/,/', $exclude_from_cache);

		$exclude_from_cache     = str_replace('\//', '/', $exclude_from_cache);

		$exclude_from_cache  = $exclude_from_cache.'endslash';

		$exclude_from_cache     = str_replace('\/endslash', '/', $exclude_from_cache);

		$exclude_from_cache     = str_replace('endslash', '/', $exclude_from_cache);
		
		$never_cacheurls  .= ','.$exclude_from_cache;
    }

	return $never_cacheurls;
}

add_filter( 'superpwa_sw_never_cache_urls', 'superpwa_exclude_urls_cache_sw' );

/**
 * Get offline page
 * 
 * @return (string) the URL of the offline page.
 * 
 * @author Arun Basil Lal
 * 
 * @since 2.0.1
 */
function superpwa_get_offline_page() {
	
	// Get Settings
	$settings = superpwa_get_settings();
	
	return (isset($settings['offline_page'] ) && get_permalink( $settings['offline_page'] )) ? superpwa_httpsify( get_permalink( $settings['offline_page'] ) ) : superpwa_httpsify( superpwa_get_bloginfo( 'sw' ) );
}

function superpwa_sanitize_exclude_urls_cache_sw($urls)
{
	$urls_array = explode(',',$urls);
	foreach($urls_array as $key=>$url){
		$urls_array[$key]=trailingslashit( $url );
	}
	return implode(',',$urls_array);
}



add_filter( 'superpwa_sw_never_cache_urls', 'superpwa_sanitize_exclude_urls_cache_sw' ,9999);