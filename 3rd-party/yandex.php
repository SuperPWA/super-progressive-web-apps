<?php
/**
 * yandex  integration
 *
 * @link https://wordpress.org/plugins/onesignal-free-web-push-notifications/
 *
 * @since 1.6
 * 
 * @function	superpwa_yandex_manifest_support()			Add array of yandex to SuperPWA manifest as per https://yandex.ru/dev/turboapps/doc/dev/manifest.html
 */

 add_filter( 'superpwa_manifest', 'superpwa_yandex_manifest_support' );
 function superpwa_yandex_manifest_support( $manifest ){

 	// Get Settings
	$settings = superpwa_get_settings(); 
	if( isset($settings['yandex_support']) && $settings['yandex_support']==1 ){
 			$manifest['yandex'] = array(
			 						'manifest_version' 	=> 1,
			 						'app_version'		=> SUPERPWA_VERSION,
			 						'cache'				=> array(
			 												'resources'=> array('/style.css'),
			 												'ignored_query_params'=> array(),
			 												)
			 							);
 	}
	
	return $manifest;
 }