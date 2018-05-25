<?php
/**
 * Apple Touch Icons
 *
 * @since 1.8
 * 
 * @function	superpwa_ati_add_apple_touch_icons()	Add Apple Touch Icons to the wp_head
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Add Apple Touch Icons to the wp_head
 * 
 * Uses the Application Icon and Splash Screen Icon for SuperPWA > Settings
 * and adds them to wp_head using the superpwa_wp_head_tags filter.
 * 
 * @param (string) $tags HTML element tags passed on by superpwa_wp_head_tag
 * 
 * @return (string) Appends the Apple Touch Icons to the existing tag string
 * 
 * @since 1.8
 */
function superpwa_ati_add_apple_touch_icons( $tags ) {
	
	// Get the icons added via SuperPWA > Settings
	$icons = superpwa_get_pwa_icons();
	
	foreach( $icons as $icon ) {
		$tags .= '<link rel="apple-touch-icon" sizes="' . $icon['sizes'] . '" href="' . $icon['src'] . '">' . PHP_EOL;
	}
	
	return $tags;
}
add_filter( 'superpwa_wp_head_tag', 'superpwa_ati_add_apple_touch_icons' );