<?php
/**
 * Manifest related functions of SuperPWA
 *
 * @since 1.0
 * 
 * @function	superpwa_manifest()						Manifest filename, absolute path and link
 * @function	superpwa_generate_manifest()			Generate and write manifest
 * @function	superpwa_add_manifest_to_wp_head()		Add manifest to header (wp_head)
 * @function	superpwa_register_service_worker()		Register service worker in the footer (wp_footer)
 * @function	superpwa_delete_manifest()				Delete manifest
 * @function 	superpwa_get_pwa_icons()				Get PWA Icons
 * @function	superpwa_get_scope()					Get navigation scope of PWA
 * @function	superpwa_get_orientation()				Get orientation of PWA
* @function	superpwa_get_display()					Get display of PWA
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Manifest filename, absolute path and link
 *
 * For Multisite compatibility. Used to be constants defined in superpwa.php
 * On a multisite, each sub-site needs a different manifest file.
 *
 * @param $arg 	filename for manifest filename (replaces SUPERPWA_MANIFEST_FILENAME)
 *				abs for absolute path to manifest (replaces SUPERPWA_MANIFEST_ABS)
 *				src for link to manifest (replaces SUPERPWA_MANIFEST_SRC). Default value
 *
 * @return String filename, absolute path or link to manifest. 
 *  
 * @since 1.6
 */
function superpwa_manifest( $arg = 'src' ) {
	
	$manifest_filename = 'superpwa-manifest' . superpwa_multisite_filename_postfix() . '.json';
	
	switch( $arg ) {
		
		// Name of Manifest file
		case 'filename': 
			return $manifest_filename;
			break;
		
		// Absolute path to manifest		
		case 'abs':
			return trailingslashit( ABSPATH ) . $manifest_filename;
			break;
		
		// Link to manifest
		case 'src':
		default:
			return trailingslashit( network_site_url() ) . $manifest_filename;
			break;
	}
}

/**
 * Generate and write manifest into WordPress root folder
 *
 * @return (boolean) true on success, false on failure.
 * 
 * @since 1.0
 * @since 1.3 Added support for 512x512 icon.
 * @since 1.4 Added orientation and scope.
 * @since 1.5 Added gcm_sender_id
 * @since 1.6 Added description
 * @since 1.8 Removed gcm_sender_id and introduced filter superpwa_manifest. gcm_sender_id is added in /3rd-party/onesignal.php
* @since 2.0 Added display
 */
function superpwa_generate_manifest() {
	
	// Get Settings
	$settings = superpwa_get_settings();
	
	$manifest 						= array();
	$manifest['name']				= $settings['app_name'];
	$manifest['short_name']			= $settings['app_short_name'];
	
	// Description
	if ( isset( $settings['description'] ) && ! empty( $settings['description'] ) ) {
		$manifest['description'] 	= $settings['description'];
	}
	
	$manifest['icons']				= superpwa_get_pwa_icons();
	$manifest['background_color']	= $settings['background_color'];
	$manifest['theme_color']		= $settings['theme_color'];
	$manifest['display']			= superpwa_get_display();
	$manifest['orientation']		= superpwa_get_orientation();
	$manifest['start_url']			= superpwa_get_start_url( true );
	$manifest['scope']				= superpwa_get_scope();
	
	// Filter the manifest.
	$manifest = apply_filters( 'superpwa_manifest', $manifest );
	
	// Delete manifest if it exists.
	superpwa_delete_manifest();
	
	// Write the manfiest to disk.
	if ( ! superpwa_put_contents( superpwa_manifest( 'abs' ), json_encode( $manifest ) ) ) {
		return false;
	}
	
	return true;
}

/**
 * Add manifest to header (wp_head)
 *
 * @since 1.0
 * @since 1.8 Introduced filter superpwa_wp_head_tags
 * @since 1.9 Introduced filter superpwa_add_theme_color
 */
function superpwa_add_manifest_to_wp_head() {
	
	$tags  = '<!-- Manifest added by SuperPWA - Progressive Web Apps Plugin For WordPress -->' . PHP_EOL; 
	$tags .= '<link rel="manifest" href="'. parse_url( superpwa_manifest( 'src' ), PHP_URL_PATH ) . '">' . PHP_EOL;
	
	// theme-color meta tag 
	if ( apply_filters( 'superpwa_add_theme_color', true ) ) {
		
		// Get Settings
		$settings = superpwa_get_settings();
		$tags .= '<meta name="theme-color" content="'. $settings['theme_color'] .'">' . PHP_EOL;
	}
	
	$tags  = apply_filters( 'superpwa_wp_head_tags', $tags );
	
	$tags .= '<!-- / SuperPWA.com -->' . PHP_EOL; 
	
	echo $tags;
}
add_action( 'wp_head', 'superpwa_add_manifest_to_wp_head', 0 );

/**
 * Delete manifest
 *
 * @return (boolean) true on success, false on failure
 * 
 * @since 1.0
 */
function superpwa_delete_manifest() {
	return superpwa_delete( superpwa_manifest( 'abs' ) );
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
	if ( @$settings['splash_icon'] != '' ) {
		
		$icons_array[] = array(
							'src' 	=> $settings['splash_icon'],
							'sizes'	=> '512x512', // must be 512x512.
							'type'	=> 'image/png', // must be image/png
						);
	}
	
	return $icons_array;
}

/**
 * Get navigation scope of PWA
 *
 * @return	string	Relative path to the folder where WordPress is installed. Same folder as manifest and wp-config.php
 * @since	1.4
 */
function superpwa_get_scope() {
	return parse_url( trailingslashit( get_bloginfo( 'wpurl' ) ), PHP_URL_PATH );
}

/**
 * Get orientation of PWA
 *
 * @return	string	Orientation of PWA as set in the plugin settings. 
 * @since	1.4
 */
function superpwa_get_orientation() {
	
	// Get Settings
	$settings = superpwa_get_settings();
	
	$orientation = isset( $settings['orientation'] ) ? $settings['orientation'] : 0;
	
	switch ( $orientation ) {
		
		case 0:
			return 'any';
			break;
			
		case 1:
			return 'portrait';
			break;
			
		case 2:
			return 'landscape';
			break;
			
		default: 
			return 'any';
	}
}

/**
 * Get display of PWA
 *
 * @return	string	Display of PWA as set in the plugin settings. 
 * @since	2.0
 */
function superpwa_get_display() {
	
	// Get Settings
	$settings = superpwa_get_settings();
	
	$display = isset( $settings['display'] ) ? $settings['display'] : 0;
	
	switch ( $display ) {
		
		case 0:
			return 'fullscreen';
			break;
			
		case 1:
			return 'standalone';
			break;
			
		case 2:
			return 'minimal-ui';
			break;

		case 3:
			return 'browser';
			break;
			
		default: 
			return 'standalone';
	}
}
