<?php
/**
 * OneSignal integration
 *
 * @link https://wordpress.org/plugins/onesignal-free-web-push-notifications/
 *
 * @since 1.6
 * 
 * @function	superpwa_onesignal_manifest_notice_check()		Check if OneSignal integration notice should be displayed or not
 * @function	superpwa_onesignal_add_gcm_sender_id()			Add gcm_sender_id to SuperPWA manifest
 * @function	superpwa_onesignal_sw_filename()				Change Service Worker filename to OneSignalSDKWorker.js.php
 * @function 	superpwa_onesignal_sw() 						Import OneSignal service worker in SuperPWA
 * @function	superpwa_onesignal_activation()					OneSignal activation todo
 * @function	superpwa_onesignal_deactivation()				OneSignal deactivation todo
 */

// Exit if accessed directly
if ( ! defined('ABSPATH') ) exit;

// If OneSignal is installed and active
if ( class_exists( 'OneSignal' ) ) {
	
	// Add gcm_sender_id to SuperPWA manifest
	add_filter( 'superpwa_manifest', 'superpwa_onesignal_add_gcm_sender_id' );
	
	// Change service worker filename to match OneSignal's service worker
	add_filter( 'superpwa_sw_filename', 'superpwa_onesignal_sw_filename' );
	
	// Import OneSignal service worker in SuperPWA
	add_filter( 'superpwa_sw_template', 'superpwa_onesignal_sw' );
}

/** 
 * Check if OneSignal integration notice should be displayed or not
 *
 * @return	Bool	True if notice should be displayed. False otherwise.
 * 
 * @since	1.5
 */
function superpwa_onesignal_manifest_notice_check() {
	
	// Get OneSignal settins
	$onesignal_wp_settings = get_option( 'OneSignalWPSetting' );
	
	// No notice needed if OneSignal custom manifest is enabled and the manifest is the SuperPWA manifest
	if ( 
		( isset( $onesignal_wp_settings["use_custom_manifest"] ) ) && ( $onesignal_wp_settings["use_custom_manifest"] == 1 ) &&
		( isset( $onesignal_wp_settings["custom_manifest_url"] ) ) && ( strcasecmp( trim( $onesignal_wp_settings["custom_manifest_url"] ), superpwa_manifest( 'src' ) ) == 0  )
	) {
		return false;
	}
	
	// Display notice for every other case
	return true;
}

/**
 * Add gcm_sender_id to SuperPWA manifest
 *
 * OneSignal's gcm_sender_id is 482941778795
 *
 * @param (array) $manifest Array with the manifest entries passed via the superpwa_manifest filter.
 * 
 * @return (array) Array appended with the gcm_sender_id of OneSignal
 * 
 * @since 1.8
 */
function superpwa_onesignal_add_gcm_sender_id( $manifest ) {
	
	$manifest['gcm_sender_id'] = '482941778795';
	
	return $manifest;
}

/**
 * Change Service Worker filename to OneSignalSDKWorker.js.php
 * 
 * OneSignalSDKWorker.js.php is the name of the service worker of OneSignal.
 * Since only one service worker is allowed in a given scope, OneSignal unregisters all other service workers and registers theirs. 
 * Having the same name prevents OneSignal from unregistering our service worker. 
 * 
 * @link https://documentation.onesignal.com/docs/web-push-setup-faq
 * 
 * @param (string) $sw_filename Filename of SuperPWA service worker passed via superpwa_sw_filename filter.
 * 
 * @return (string) Service worker filename changed to OneSignalSDKWorker.js.php
 * 
 * @since 1.8
 */
function superpwa_onesignal_sw_filename( $sw_filename ) {
	return 'OneSignalSDKWorker.js.php';
}

/**
 * Import OneSignal service worker in SuperPWA
 * 
 * @param (string) $sw Service worker template of SuperPWA passed via superpwa_sw_template filter
 * 
 * @return (string) Import OneSignal's service worker into SuperPWA 
 * 
 * @since 1.8
 */
function superpwa_onesignal_sw( $sw ) {
	
	$onesignal  = '<?php' . PHP_EOL; 
	$onesignal .= 'header( "Content-Type: application/javascript" );' . PHP_EOL;
	$onesignal .= 'echo "importScripts( \'' . superpwa_httpsify( plugin_dir_url( 'onesignal-free-web-push-notifications/onesignal.php' ) ) . 'sdk_files/OneSignalSDKWorker.js.php\' );";' . PHP_EOL;
	$onesignal .= '?>' . PHP_EOL . PHP_EOL;
	
	return $onesignal . $sw;
}

/**
 * OneSignal activation todo
 * 
 * Regenerates SuperPWA manifest with the gcm_sender_id added.
 * Delete current service worker.
 * Regenerate SuperPWA service worker with the new filename.
 * 
 * @since 1.8
 */
function superpwa_onesignal_activation() {
	
	// Filter in gcm_sender_id to SuperPWA manifest
	add_filter( 'superpwa_manifest', 'superpwa_onesignal_add_gcm_sender_id' );
	
	// Regenerate SuperPWA manifest
	superpwa_generate_manifest();
	
	// Delete service worker if it exists
	superpwa_delete_sw();
	
	// Change service worker filename to match OneSignal's service worker
	add_filter( 'superpwa_sw_filename', 'superpwa_onesignal_sw_filename' );
	
	// Import OneSignal service worker in SuperPWA
	add_filter( 'superpwa_sw_template', 'superpwa_onesignal_sw' );
	
	// Regenerate SuperPWA service worker
	superpwa_generate_sw();
}
add_action( 'activate_onesignal-free-web-push-notifications/onesignal.php', 'superpwa_onesignal_activation', 11 );

/**
 * OneSignal deactivation todo
 * 
 * Regenerates SuperPWA manifest.
 * Delete current service worker. 
 * Regenerate SuperPWA service worker.
 * 
 * @since 1.8
 */
function superpwa_onesignal_deactivation() {
	
	// Remove gcm_sender_id from SuperPWA manifest
	remove_filter( 'superpwa_manifest', 'superpwa_onesignal_add_gcm_sender_id' );
	
	// Regenerate SuperPWA manifest
	superpwa_generate_manifest();
	
	// Delete service worker if it exists
	superpwa_delete_sw();
	
	// Restore the default service worker of SuperPWA
	remove_filter( 'superpwa_sw_filename', 'superpwa_onesignal_sw_filename' );
	
	// Remove OneSignal service worker in SuperPWA
	remove_filter( 'superpwa_sw_template', 'superpwa_onesignal_sw' );
	
	// Regenerate SuperPWA service worker
	superpwa_generate_sw();
}
add_action( 'deactivate_onesignal-free-web-push-notifications/onesignal.php', 'superpwa_onesignal_deactivation', 11 );