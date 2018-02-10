<?php
/**
 * Operations of the plugin are included here. 
 *
 * @since 1.0
 * @function	superpwa_generate_manifest()			Generate and write manifest
 * @function	superpwa_add_manifest_to_header()		Add manifest to header (wp_head)
 * @function	superpwa_register_service_worker()		Register service worker in the footer (wp_footer)
 * @function	superpwa_delete_manifest()				Delete manifest
 * @function 	superpwa_get_pwa_icons()				Get PWA Icons
 */

// Exit if accessed directly
if ( ! defined('ABSPATH') ) exit;

/**
 * Generate and write manifest into WordPress root folder
 *
 * @return true on success, false on failure.
 * @since	1.0
 */
function superpwa_generate_manifest() {
	
	// Get Settings
	$settings = superpwa_get_settings();
	
	$manifest = array(
		'name'				=> $settings['app_name'],
		'short_name'		=> $settings['app_short_name'],
		'icons'				=> superpwa_get_pwa_icons(),
		'background_color'	=> $settings['background_color'],
		'theme_color'		=> $settings['background_color'],
		'display'			=> 'standalone',
		'orientation'		=> 'natural',
		'start_url'			=> superpwa_get_start_url(true),
	);
	
	// Delete manifest if it exists
	superpwa_delete_manifest();
	
	if ( ! superpwa_put_contents( SUPERPWA_MANIFEST_ABS, json_encode($manifest) ) )
		return false;
	
	return true;
}

/**
 * Add manifest to header (wp_head)
 *
 * @since	1.0
 */
function superpwa_add_manifest_to_header() {
	
	// Get Settings
	$settings = superpwa_get_settings();
	
	echo '<!-- Manifest added by SuperPWA -->' . PHP_EOL . '<link rel="manifest" href="'. SUPERPWA_MANIFEST_SRC . '">' . PHP_EOL;
	echo '<meta name="theme-color" content="'. $settings['background_color'] .'">' . PHP_EOL;
}
add_action( 'wp_head', 'superpwa_add_manifest_to_header' );

/**
 * Delete manifest
 *
 * @return true on success, false on failure
 * @since	1.0
 */
function superpwa_delete_manifest() {
	
	return superpwa_delete( SUPERPWA_MANIFEST_ABS );
}

/**
 * Get PWA Icons
 *
 * @return	array	An array of icons to be used as the application icons and splash screen icons
 * @since	1.3
 */
function superpwa_get_pwa_icons() {
	
	// Get settings
	$settings = superpwa_get_settings();
	
	// Application icon
	$icons_array[] = array(
							'src' 	=> $settings['icon'],
							'sizes'	=> '192x192', // must be 192x192. Todo: use getimagesize($settings['icon'])[0].'x'.getimagesize($settings['icon'])[1] in the future
							'type'	=> 'image/png', // must be image/png. Todo: use getimagesize($settings['icon'])['mime']
						);
	
	// Splash screen icon - Added since 1.3
	if ( @$settings['splash_icon'] != null ) {
		
		$icons_array[] = array(
							'src' 	=> $settings['splash_icon'],
							'sizes'	=> '512x512', // must be 192x192.
							'type'	=> 'image/png', // must be image/png
						);
	}
	
	return $icons_array;
}