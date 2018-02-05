<?php
/**
 * Operations of the plugin are included here. 
 *
 * @since 1.0
 * @function	superpwa_after_save_settings_todo()		Todo list after saving admin options
 * @function	superpwa_is_amp()						Check if any AMP plugin is installed
 * @function 	superpwa_get_start_url()				Return Start Page URL
 */

// Exit if accessed directly
if ( ! defined('ABSPATH') ) exit;

/**
 * Todo list after saving admin options
 *
 * Regenerate manifest
 * Regenerate service worker
 *
 * @since	1.0
 */
function superpwa_after_save_settings_todo() {
	
	// Regenerate manifest
	superpwa_generate_manifest();
	
	// Regenerate service worker
	superpwa_generate_sw();
}
add_action( 'add_option_superpwa_settings', 'superpwa_after_save_settings_todo' );
add_action( 'update_option_superpwa_settings', 'superpwa_after_save_settings_todo' );

/**
 * Check if any AMP plugin is installed
 * 
 * @return	String|Bool	AMP page url on success, false otherwise
 * @since	1.2
 */
function superpwa_is_amp() {
	
	// AMP for WordPress - https://wordpress.org/plugins/amp
	if ( function_exists( 'amp_init' ) )
		return 'amp/';
	
	// AMP for WP - https://wordpress.org/plugins/accelerated-mobile-pages/
	if ( function_exists( 'ampforwp_generate_endpoint' ) ) 
		return 'amp/';
	
	// Better AMP – https://wordpress.org/plugins/better-amp/
	if ( class_exists( 'Better_AMP' ) ) 
		return 'amp/';
	
	// AMP Supremacy - https://wordpress.org/plugins/amp-supremacy/
	if ( class_exists( 'AMP_Init' ) ) 
		return 'amp/';
	
	// WP AMP - https://wordpress.org/plugins/wp-amp-ninja/
	if ( function_exists( 'wpamp_init' ) ) 
		return '?wpamp';
	
	return false;
}

/**
 * Return Start Page URL
 *
 * @param	$rel	False by default. Set to true to return a relative URL (for use in manifest)
 * @return	String	URL to be set as the start_url in manifest and startPage in service worker
 * @since	1.2
 */
function superpwa_get_start_url( $rel=false ) {
	
	// Get Settings
	$settings = superpwa_get_settings();
	
	// Start Page
	$start_url = get_permalink( $settings['start_url'] ) ? trailingslashit( get_permalink( $settings['start_url'] ) ) : trailingslashit( get_bloginfo( 'wpurl' ) );
	
	if ( $rel === true ) {
		
		$start_url = str_replace( untrailingslashit( get_bloginfo( 'wpurl' ) ), '', $start_url );
	}
	
	// AMP URL
	$amp_url = superpwa_is_amp() !== false ? superpwa_is_amp() : '';
	
	return $start_url . $amp_url;
}