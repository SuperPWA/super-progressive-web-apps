<?php
/**
 * WonderPush integration
 *
 * @link https://wordpress.org/plugins/wonderpush-web-push-notifications/
 *
 * @since 2.2.2
 * 
 * @function	superpwa_wonderpush_compatibility()						Compatibility with WonderPush

 * @function	superpwa_wonderpush_sw_filename()				Change Service Worker filename to WonderPushSDKWorker.js.php
 * @function 	superpwa_wonderpush_sw() 						Import WonderPush service worker in SuperPWA
 */

// Exit if accessed directly
if ( ! defined('ABSPATH') ) exit;

/**
 * Compatibility with WonderPush
 * 
 * @since 2.0.2
 */
function superpwa_wonderpush_compatibility() {
	
	// If OneSignal is installed and active
	if ( class_exists( 'WonderPushSettings' ) ) {

			
			// Change service worker filename to match WonderPush's service worker
			add_filter( 'superpwa_sw_filename', 'superpwa_wonderpush_sw_filename' );
			
			// Import WonderPush service worker in SuperPWA
			add_filter( 'superpwa_sw_template', 'superpwa_wonderpush_sw' );
	}
}
add_action( 'plugins_loaded', 'superpwa_wonderpush_compatibility' );


/**
 * Change Service Worker filename to WonderPushSDKWorker.js.php
 * 
 * WonderPushSDKWorker.js.php is the name of the service worker of WonderPush.
 * Since only one service worker is allowed in a given scope, WonderPush unregisters all other service workers and registers theirs. 
 * Having the same name prevents WonderPush from unregistering our service worker. 
 * 
 * @link https://documentation.onesignal.com/docs/web-push-setup-faq
 * 
 * @param (string) $sw_filename Filename of SuperPWA service worker passed via superpwa_sw_filename filter.
 * 
 * @return (string) Service worker filename changed to WonderPushSDKWorker.js.php
 * 
 * @since 2.2.2
 */
function superpwa_wonderpush_sw_filename( $sw_filename ) {
	return 'WonderPushSDKWorker' . superpwa_multisite_filename_postfix() . '.js.php';
}

/**
 * Import WonderPush service worker in SuperPWA
 * 
 * @param (string) $sw Service worker template of SuperPWA passed via superpwa_sw_template filter
 * 
 * @return (string) Import WonderPush's service worker into SuperPWA 
 * 
 * @author SuperPWA Team
 * 
 * @since 2.2.2
 */
function superpwa_wonderpush_sw( $sw ) {


	$settings = WonderPushSettings::getSettings();
    $access_token = $settings->getAccessToken();

    if(class_exists('WonderPushSettings')){
	    try {
	          $app = WonderPushUtils::application_from_access_token($access_token);
	        } catch (Exception $e) {
	    
	          return $sw;
	        }
	        if (!$app) return $sw;
	        $web_key = $app->getWebKey();
	    }else{
	    	return $sw;
	    }

	
	/** 
	 * Checking to see if we are already sending the Content-Type header. 
	 * 
	 * @see superpwa_generate_sw_and_manifest_on_fly()
	 */
	$match = preg_grep( '#Content-Type: text/javascript#i', headers_list() );
	
	if ( ! empty ( $match ) ) {
		
		$wonderpush = 'importScripts(\'https://cdn.by.wonderpush.com/sdk/1.1/wonderpush-loader.min.js\');
			WonderPush = self.WonderPush || [];
			WonderPush.push([\'init\', {
							webKey: \''.$web_key.'\',
						}]);' . PHP_EOL;
	
		return $wonderpush . $sw;
	}
	
	$wonderpush  = '<?php' . PHP_EOL; 
	$wonderpush .= 'header( "Content-Type: application/javascript" );' . PHP_EOL;
	$wonderpush .= 'echo "importScripts(\'https://cdn.by.wonderpush.com/sdk/1.1/wonderpush-loader.min.js\');
			WonderPush = self.WonderPush || [];
			WonderPush.push([\'init\', {
							webKey: \''.$web_key.'\',
						}]);";' . PHP_EOL;
	$wonderpush .= '?>' . PHP_EOL . PHP_EOL;
	
	return $wonderpush . $sw;
}