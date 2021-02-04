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
 * @param (string) $tags HTML element tags passed on by superpwa_wp_head_tags
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
add_filter( 'superpwa_wp_head_tags', 'superpwa_ati_add_apple_touch_icons' );

/**
 * Remove apple-touch-icon added by WordPress in heading (site_icon_meta_tags)
 *
 * Wordpress introduce this filter since 4.3.0 (site_icon_meta_tags)
 * @since 2.1.6 introduce
 * @param (string) $tags HTML element tags passed on by site_icon_meta_tags
 * 
 * @return (string) Remove the Apple Touch Icons from the existing tag string
 */
function superpwa_remove_site_apple_touch_icon($meta_tags) {
	if(is_customize_preview() && is_admin()){
            return $meta_tags;
        }
        foreach ($meta_tags as $key => $value) {
            if(strpos($value, 'apple-touch-icon') !== false){
                unset($meta_tags[$key]);
            }
        }
        return $meta_tags;
}
add_filter( 'site_icon_meta_tags', 'superpwa_remove_site_apple_touch_icon', 0 );