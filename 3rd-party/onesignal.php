<?php
/**
 * OneSignal integration
 *
 * @link https://wordpress.org/plugins/onesignal-free-web-push-notifications/
 *
 * @since 1.6
 * 
 * @function	superpwa_onesignal_todo()						Compatibility with OneSignal
 * @function	superpwa_onesignal_add_gcm_sender_id()			Add gcm_sender_id to SuperPWA manifest
 * @function	superpwa_onesignal_sw_filename()				Change Service Worker filename to OneSignalSDKWorker.js.php
 * @function 	superpwa_onesignal_sw() 						Import OneSignal service worker in SuperPWA
 * @function	superpwa_onesignal_activation()					OneSignal activation todo
 * @function	superpwa_onesignal_deactivation()				OneSignal deactivation todo
 * @function 	superpwa_onesignal_admin_notices()				Admin notices for OneSignal compatibility
 */

// Exit if accessed directly
if ( ! defined('ABSPATH') ) exit;

/**
 * Compatibility with OneSignal
 * 
 * This was written without a function @since 1.6 but that caused issues in certain cases where 
 * SuperPWA was loaded before OneSignal. Hooked everything to plugins_loaded @since 2.0.1
 * 
 * @author Arun Basil Lal
 * 
 * @since 2.0.1
 */
function superpwa_onesignal_todo() {
	
	// If OneSignal is installed and active
	if ( class_exists( 'OneSignal' ) ) {
		
		// Filter manifest and service worker for singe websites and not for multisites.
		if ( ! is_multisite() ) {
		
			// Add gcm_sender_id to SuperPWA manifest
			add_filter( 'superpwa_manifest', 'superpwa_onesignal_add_gcm_sender_id' );
			
			// Change service worker filename to match OneSignal's service worker
			add_filter( 'superpwa_sw_filename', 'superpwa_onesignal_sw_filename' );
			
			// Import OneSignal service worker in SuperPWA
			add_filter( 'superpwa_sw_template', 'superpwa_onesignal_sw' );
		}else{
			//Added filter for multisites
			// Add gcm_sender_id to SuperPWA manifest
			add_filter( 'superpwa_manifest', 'superpwa_onesignal_add_gcm_sender_id' );
			
			// Change service worker filename to match OneSignal's service worker
			add_filter( 'superpwa_sw_filename', 'superpwa_onesignal_sw_filename' );
			
			// Import OneSignal service worker in SuperPWA
			add_filter( 'superpwa_sw_template', 'superpwa_onesignal_sw' );
		}
		

	}
}
add_action( 'plugins_loaded', 'superpwa_onesignal_todo' );

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
	return 'OneSignalSDKWorker' . superpwa_multisite_filename_postfix() . '.js.php';
}

/**
 * Import OneSignal service worker in SuperPWA
 * 
 * @param (string) $sw Service worker template of SuperPWA passed via superpwa_sw_template filter
 * 
 * @return (string) Import OneSignal's service worker into SuperPWA 
 * 
 * @author SuperPWA Team
 * 
 * @since 1.8
 * @since 2.0 Removed content-type header for compatibility with dynamic service workers. 
 * @since 2.0.1 Added back compatibility with static service workers by sending content-type header.
 */
function superpwa_onesignal_sw( $sw ) {
	
	/** 
	 * Checking to see if we are already sending the Content-Type header. 
	 * 
	 * @see superpwa_generate_sw_and_manifest_on_fly()
	 */
	$match = preg_grep( '#Content-Type: text/javascript#i', headers_list() );
	
	if ( ! empty ( $match ) ) {
		
		$onesignal = 'importScripts( \'' . superpwa_httpsify( plugin_dir_url( 'onesignal-free-web-push-notifications/onesignal.php' ) ) . 'sdk_files/OneSignalSDKWorker.js.php\' );' . PHP_EOL;
	
		return $onesignal . $sw;
	}
	
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
 * @author SuperPWA Team
 * 
 * @since 1.8
 * @since 1.8.1 Excluded multisites. No OneSignal compatibility on multisites yet. In 1.8 onesignal.php was not loaded for multisites. 
 */
function superpwa_onesignal_activation() {
	
	// Do not do anything for multisites
	if ( is_multisite() ) {
		return;
	}
	
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
 * @author SuperPWA Team
 * 
 * @since 1.8
 * @since 1.8.1 Excluded multisites. No OneSignal compatibility on multisites yet. In 1.8 onesignal.php was not loaded for multisites. 
 */
function superpwa_onesignal_deactivation() {
	
	// Do not do anything for multisites
	if ( is_multisite() ) {
		return;
	}
	
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

/**
 * Admin notices for OneSignal compatibility
 * 
 * One Single installs, warn users to add SuperPWA manifest as custom manifest in OneSignal settings.
 * One multisites, warn users that SuperPWA and OneSignal cannot work together. 
 * 
 * @since 1.8.1
 * @since 2.1 Removed the notice recommending customers to add manifest to OneSignal.
 */
function superpwa_onesignal_admin_notices() {
	
	// Incompatibility notice for Multisites
	if ( is_multisite() && current_user_can( 'manage_options' ) ) {
		
		echo '<div class="notice notice-warning"><p>' . 
		sprintf( 
			__( '<strong>SuperPWA</strong> is not compatible with OneSignal on multisites yet. Disable one of these plugins until the compatibility is available.<br>Please refer to the <a href="%s" target="_blank">OneSignal integration documentation</a> for more info. ', 'super-progressive-web-apps' ), 
			'https://superpwa.com/doc/setup-onesignal-with-superpwa/?utm_source=superpwa-plugin&utm_medium=onesignal-multisite-admin-notice#multisites'
		) . '</p></div>';
		
		// Filter PWA status since PWA is not ready yet. 
		add_filter( 'superpwa_is_pwa_ready', '__return_false' );
	}
}