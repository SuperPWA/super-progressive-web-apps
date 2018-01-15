<?php
/**
 * Operations of the plugin are included here. 
 *
 * @since 1.0
 * @function	superpwa_generate_manifest()			Generate and write manifest into manifest.json
 * @function	superpwa_add_manifest_to_header()		Add manifest to header (wp_head)
 * @function	superpwa_register_service_worker()		Register service worker in the footer (wp_footer)
 * @function	superpwa_delete_manifest()				Delete manifest
 */

// Exit if accessed directly
if ( ! defined('ABSPATH') ) exit;

// Load Filesystem functions
require_once( SUPERPWA_PATH_ABS . 'admin/filesystem.php');

/**
 * Generate and write manifest into manifest.json
 *
 * @return true on success, false on failure.
 * @since	1.0
 */
function superpwa_generate_manifest() {
	
	// Get Settings
	$settings = superpwa_get_settings();
	
	$manifest = array(
		'name'				=> get_bloginfo('name'),
		'short_name'		=> get_bloginfo('name'),
		'description'		=> get_bloginfo('description'),
		'icons'				=> array( 
									'src' 	=> '/wp-content/uploads/2018/01/million-clues-logo.png',
									'sizes'	=> '150x150',
									'type'	=> 'image/png',
								),
		'background_color'	=> $settings['background_color'],
		'display'			=> 'standalone',
		'orientation'		=> 'natural',
		'start_url'			=> '/',
	);
	
	// manifest.json is saved in the root folder of WordPress
	$file = ABSPATH . 'manifest.json';
	
	if ( ! superpwa_put_contents( $file, json_encode($manifest) ) )
		return false;
	
	return true;
}

/**
 * Add manifest to header (wp_head)
 *
 * @since	1.0
 */
function superpwa_add_manifest_to_header() {
	
	echo '<link rel="manifest" href="'. get_bloginfo('wpurl') .'/manifest.json"><!-- Manifest added by Plugin name -->';
}
add_action( 'wp_head', 'superpwa_add_manifest_to_header' );

/**
 * Register service worker in the footer (wp_footer)
 *
 * @since	1.0
 * @refer	https://developers.google.com/web/fundamentals/primers/service-workers/registration#conclusion
 */
function superpwa_register_service_worker() {
	
echo "<!-- Load PWA service worker. Added by Plugin name -->
<script type='text/javascript'>
if ('serviceWorker' in navigator) {
  window.addEventListener('load', function() {
	navigator.serviceWorker.register('". SUPERPWA_PATH_SRC . "public/sw.js');
  });
}
</script>";
}
add_action( 'wp_footer', 'superpwa_register_service_worker' );

/**
 * Delete manifest
 *
 * @return true on success, false on failure
 * @since	1.0
 */
function superpwa_delete_manifest() {
	
	// manifest.json is in the root folder of WordPress
	$file = ABSPATH . 'manifest.json';
	
	return superpwa_delete( $file );
}

/**
 * Todo list after saving admin options
 *
 * Regenerate manifest.json
 *
 * @since	1.0
 */
function superpwa_after_save_settings_todo() {
	
	// Regenerate manifes.json
	superpwa_generate_manifest();
}
add_action( 'update_option_superpwa_settings', 'superpwa_after_save_settings_todo' );