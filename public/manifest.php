<?php
/**
 * Operations of the plugin are included here. 
 *
 * @since 1.0
 * @function	superpwa_generate_manifest()			Generate and write manifest
 * @function	superpwa_add_manifest_to_header()		Add manifest to header (wp_head)
 * @function	superpwa_register_service_worker()		Register service worker in the footer (wp_footer)
 * @function	superpwa_delete_manifest()				Delete manifest
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
		'name'				=> get_bloginfo('name'),
		'short_name'		=> get_bloginfo('name'),
		'icons'				=> array( 
								array(
									'src' 	=> $settings['icon'],
									'sizes'	=> '192x192', // must be 192x192. Todo: use getimagesize($settings['icon'])[0].'x'.getimagesize($settings['icon'])[1] in the future
									'type'	=> 'image/png', // must be image/png. Todo: use getimagesize($settings['icon'])['mime']
								),
							   ),
		'background_color'	=> $settings['background_color'],
		'theme_color'		=> $settings['background_color'],
		'display'			=> 'standalone',
		'orientation'		=> 'natural',
		'start_url'			=> '/',
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
	
	echo '<!-- Manifest added by SuperPWA -->' . PHP_EOL . '<link rel="manifest" href="'. SUPERPWA_MANIFEST_SRC . '">';
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