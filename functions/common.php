<?php
/**
 * Operations and common functions of SuperPWA
 *
 * @since 1.0
 * 
 * @function	superpwa_is_amp()				Check if any AMP plugin is installed
 * @function 	superpwa_get_start_url()		Return Start Page URL
 * @function	superpwa_httpsify()				Convert http URL to https
 * @function	superpwa_is_pwa_ready()			Check if PWA is ready
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Check if any AMP plugin is installed
 * 
 * @return (string|bool) AMP page url on success, false otherwise
 * 
 * @since 1.2
 */
function superpwa_is_amp() {
	
	// AMP for WordPress - https://wordpress.org/plugins/amp
	if ( function_exists( 'amp_init' ) ) {
		return defined( 'AMP_QUERY_VAR' ) ? AMP_QUERY_VAR . '/' : 'amp/';
	}
	
	// AMP for WP - https://wordpress.org/plugins/accelerated-mobile-pages/
	if ( function_exists( 'ampforwp_generate_endpoint' ) ) {
		return defined( 'AMPFORWP_AMP_QUERY_VAR' ) ? AMPFORWP_AMP_QUERY_VAR . '/' : 'amp/';
	}
	
	// Better AMP – https://wordpress.org/plugins/better-amp/
	if ( class_exists( 'Better_AMP' ) ) {
		return 'amp/';
	}
	
	// AMP Supremacy - https://wordpress.org/plugins/amp-supremacy/
	if ( class_exists( 'AMP_Init' ) ) {
		return 'amp/';
	}
	
	// WP AMP - https://wordpress.org/plugins/wp-amp-ninja/
	if ( function_exists( 'wpamp_init' ) ) {
		return '?wpamp';
	}
	
	return false;
}

/**
 * Return Start Page URL
 *
 * @param $rel (boolean) False by default. Set to true to return a relative URL (for use in manifest)
 * 
 * @return (string) URL to be set as the start_url in manifest and startPage in service worker
 *
 * @since 1.2
 * @since 1.3.1 Force HTTPS by replacing http:// with https://
 * @since 1.6 Use superpwa_httpsify() to force HTTPS. 
 * @since 1.6 Removed forcing of trailing slash and added dot (.) to the beginning.
 * @since 1.7 Added filter superpwa_manifest_start_url when $rel = true, for use with manifest. First ever filter in SuperPWA.
 */
function superpwa_get_start_url( $rel = false ) {
	
	// Get Settings
	$settings = superpwa_get_settings();
	
	// Start Page
	$start_url = get_permalink( $settings['start_url'] ) ? get_permalink( $settings['start_url'] ) : get_bloginfo( 'wpurl' );
	
	// Force HTTPS
	$start_url = superpwa_httpsify( $start_url );
	
	// AMP URL
	if ( superpwa_is_amp() !== false && isset( $settings['start_url_amp'] ) && $settings['start_url_amp'] == 1 ) {
		$start_url = trailingslashit( $start_url ) . superpwa_is_amp();
	}
	
	// Relative URL for manifest
	if ( $rel === true ) {
		
		// Make start_url relative for manifest
		$start_url = ( parse_url( $start_url, PHP_URL_PATH ) == '' ) ? '.' : parse_url( $start_url, PHP_URL_PATH );
		
		return apply_filters( 'superpwa_manifest_start_url', $start_url );
	}
	
	return $start_url;
}

/**
 * Convert http URL to https
 *
 * @param $url (string) The URL to convert to https
 * 
 * @return (string) Returns the converted URL
 *
 * @since 1.6
 */
function superpwa_httpsify( $url ) {
	return str_replace( 'http://', 'https://', $url );
}

/**
 * Check if PWA is ready
 * 
 * Check for HTTPS.
 * Check if manifest is generated.
 * Check if service worker is generated. 
 * 
 * @return (bool) True if PWA is ready. False otherwise
 * 
 * @since 1.8.1
 */
function superpwa_is_pwa_ready() {
	
	if ( 
		is_ssl() && 
		superpwa_get_contents( superpwa_manifest( 'abs' ) ) && 
		superpwa_get_contents( superpwa_sw( 'abs' ) ) 
	) {
		return apply_filters( 'superpwa_is_pwa_ready', true );
	}
	
	return false; 
}