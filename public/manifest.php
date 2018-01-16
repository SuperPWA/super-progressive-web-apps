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
								array(
									'src' 	=> $settings['icon'],
									'sizes'	=> getimagesize($settings['icon'])[0].'x'.getimagesize($settings['icon'])[1],
									'type'	=> getimagesize($settings['icon'])['mime'],
								),
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
	
	echo '<link rel="manifest" href="'. get_bloginfo('wpurl') .'/manifest.json"><!-- Manifest added by Super PWA - https://superwa.com -->';
}
add_action( 'wp_head', 'superpwa_add_manifest_to_header' );

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