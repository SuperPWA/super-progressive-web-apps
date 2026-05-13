<?php
/**
 * Service Worker Minification
 *
 * Minifies the service worker JavaScript when SCRIPT_DEBUG is false or undefined
 *
 * @since 1.0
 */

// Exit if accessed directly
if ( ! defined('ABSPATH') ) exit;

// Load JShrink Minifier
require_once( SUPERPWA_PATH_ABS . 'includes/minifier.php' );

/**
 * Minify Service Worker Template
 *
 * Hooks into superpwa_sw_template filter to minify the service worker
 * JavaScript code when SCRIPT_DEBUG is false or undefined.
 *
 * @param string $sw_template The service worker template content
 * @return string Minified or original service worker template
 *
 * @since 1.0
 */
function superpwa_minify_sw_template( $sw_template ) {
	
	// Check if SCRIPT_DEBUG is defined and set to true
	// If true, return the original template without minification
	if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
		return $sw_template;
	}
	
	// SCRIPT_DEBUG is false or undefined, proceed with minification
	try {
		// Use JShrink to minify the JavaScript
		$minified = \JShrink\Minifier::minify( $sw_template, array( 'flaggedComments' => true ) );
		
		// Return minified content if successful
		if ( ! empty( $minified ) ) {
			return $minified;
		}
		
	} catch ( \Exception $e ) {
		// If minification fails, log error (optional) and return original
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( 'SuperPWA: JShrink minification failed - ' . $e->getMessage() );
		}
	}
	
	// Return original template if minification failed or returned empty
	return $sw_template;
}

// Hook into the superpwa_sw_template filter
add_filter( 'superpwa_sw_template', 'superpwa_minify_sw_template', PHP_INT_MAX );
