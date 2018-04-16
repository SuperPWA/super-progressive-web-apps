<?php
/**
 * OneSignal integration
 *
 * @since 1.6
 * @function	superpwa_onesignal_manifest_notice_check()		Check if OneSignal integration notice should be displayed or not.
 * @function	superpwa_onesignal_get_gcm_sender_id()			Extract gcm_sender_id from OneSignal settings
 */

// Exit if accessed directly
if ( ! defined('ABSPATH') ) exit;

/** 
 * Check if OneSignal integration notice should be displayed or not.
 *
 * @return	Bool	True if notice should be displayed. False otherwise.
 * @since	1.5
 */
function superpwa_onesignal_manifest_notice_check() {
	
	// No notice needed if OneSignal is not installed or there is no gcm_sender_id
	if ( ! superpwa_onesignal_get_gcm_sender_id() ) {
		return false;
	}
	
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