<?php
/**
 * UTM Tracking
 *
 * @since 1.7
 * 
 * @function	
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

function superpwa_utm_tracking_menu_links() {
	
	// Add-Ons page
	add_submenu_page( 'superpwa', __( 'Super Progressive Web Apps', 'super-progressive-web-apps' ), __( 'UTM Tracking', 'super-progressive-web-apps' ), 'manage_options', 'superpwa-utm-tracking', 'superpwa_addons_interface_render' );
}
add_action( 'admin_menu', 'superpwa_utm_tracking_menu_links' );