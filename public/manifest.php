<?php
/**
 * Manifest related functions of SuperPWA
 *
 * @since 1.0
 * @function	superpwa_generate_manifest()			Generate and write manifest
 * @function	superpwa_add_manifest_to_wp_head()		Add manifest to header (wp_head)
 * @function	superpwa_register_service_worker()		Register service worker in the footer (wp_footer)
 * @function	superpwa_delete_manifest()				Delete manifest
 * @function 	superpwa_get_pwa_icons()				Get PWA Icons
 * @function	superpwa_get_scope()					Get navigation scope of PWA
 * @function	superpwa_get_orientation()				Get orientation of PWA
 * @function	superpwa_onesignal_get_gcm_sender_id()	Extract gcm_sender_id from OneSignal settings
 */

// Exit if accessed directly
if ( ! defined('ABSPATH') ) exit;

/**
 * Generate and write manifest into WordPress root folder
 *
 * @return true on success, false on failure.
 * @since	1.0
 * @since	1.3		Added support for 512x512 icon.
 * @since	1.4		Added orientation and scope.
 * @since	1.5		Added gcm_sender_id
 */
function superpwa_generate_manifest() {
	
	// Get Settings
	$settings = superpwa_get_settings();
	
	$manifest = array(
		'name'				=> $settings['app_name'],
		'short_name'		=> $settings['app_short_name'],
		'icons'				=> superpwa_get_pwa_icons(),
		'background_color'	=> $settings['background_color'],
		'theme_color'		=> $settings['theme_color'],
		'display'			=> 'standalone',
		'orientation'		=> superpwa_get_orientation(),
		'start_url'			=> superpwa_get_start_url( true ),
		'scope'				=> superpwa_get_scope(),
	);
	
	// gcm_sender_id
	if ( superpwa_onesignal_get_gcm_sender_id() !== false ) {
		$manifest['gcm_sender_id'] = superpwa_onesignal_get_gcm_sender_id();
	}
	
	// Delete manifest if it exists
	superpwa_delete_manifest();
	
	if ( ! superpwa_put_contents( SUPERPWA_MANIFEST_ABS, json_encode( $manifest ) ) )
		return false;
	
	return true;
}

/**
 * Add manifest to header (wp_head)
 *
 * @since	1.0
 */
function superpwa_add_manifest_to_wp_head() {
	
	// Get Settings
	$settings = superpwa_get_settings();
	
	echo '<!-- Manifest added by SuperPWA -->' . PHP_EOL . '<link rel="manifest" href="'. SUPERPWA_MANIFEST_SRC . '">' . PHP_EOL;
	echo '<meta name="theme-color" content="'. $settings['theme_color'] .'">' . PHP_EOL;
}
add_action( 'wp_head', 'superpwa_add_manifest_to_wp_head', 0 );

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
 * Extract gcm_sender_id from OneSignal settings
 *
 * @link	https://wordpress.org/plugins/onesignal-free-web-push-notifications/
 *
 * @return	String|Bool	gcm_sender_id if it exists, false otherwise
 * @since 	1.5
 */
function superpwa_onesignal_get_gcm_sender_id() {
	
	// If OneSignal is installed and active
	if ( class_exists( 'OneSignal' ) ) {
		
		// Get OneSignal settins
		$onesignal_wp_settings = get_option( 'OneSignalWPSetting' );
		
		if ( isset( $onesignal_wp_settings['gcm_sender_id'] ) && ( $onesignal_wp_settings['gcm_sender_id'] != '' ) ) {
			return $onesignal_wp_settings['gcm_sender_id'];
		}
	}
	
	return false;
}