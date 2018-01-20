<?php 
/**
 * Basic setup functions for the plugin
 *
 * @since 1.0
 * @function	superpwa_activate_plugin()			Plugin activatation todo list
 * @function	superpwa_deactivate_plugin			Plugin deactivation todo list
 * @function	superpwa_load_plugin_textdomain()	Load plugin text domain
 * @function	superpwa_settings_link()			Print direct link to plugin settings in plugins list in admin
 * @function	superpwa_plugin_row_meta()			Add donate and other links to plugins list
 * @function	superpwa_footer_text()				Admin footer text
 * @function	superpwa_footer_version()			Admin footer version
 */

// Exit if accessed directly
if ( ! defined('ABSPATH') ) exit;
 
/**
 * Plugin activatation todo list
 *
 * This function runs when user activates the plugin. Used in register_activation_hook in the main plugin file. 
 * @since	1.0
 */
function superpwa_activate_plugin() {
	
	// Generate manifest with default options
	superpwa_generate_manifest();
	
	// Generate service worker
	superpwa_generate_sw();
}

/**
 * Plugin deactivation todo list
 *
 * Runs during deactivation. During uninstall uninstall.php is also executed
 * @since	1.0
 */
function superpwa_deactivate_plugin() {
	
	// Delete manifest
	superpwa_delete_manifest();
	
	// Delete service worker
	superpwa_delete_sw();
}

/**
 * Load plugin text domain
 *
 * @since	1.0
 */
function superpwa_load_plugin_textdomain() {
	
    load_plugin_textdomain( 'super-progressive-web-apps', FALSE, SUPERPWA_PATH_ABS . '/languages/' );
}
add_action( 'plugins_loaded', 'superpwa_load_plugin_textdomain' );

/**
 * Print direct link to plugin settings in plugins list in admin
 *
 * @since	1.0
 */
function superpwa_settings_link( $links ) {
	
	return array_merge(
		array(
			'settings' => '<a href="' . admin_url( 'options-general.php?page=superpwa' ) . '">' . __( 'Settings', 'super-progressive-web-apps' ) . '</a>'
		),
		$links
	);
}
add_filter( 'plugin_action_links_super-progressive-web-apps/superpwa.php', 'superpwa_settings_link' );

/**
 * Add donate and other links to plugins list
 *
 * @since	1.0
 */
function superpwa_plugin_row_meta( $links, $file ) {
	
	if ( strpos( $file, 'superpwa.php' ) !== false ) {
		$new_links = array(
				'demo' 	=> '<a href="https://superpwa.com" target="_blank">Demo</a>',
				);
		$links = array_merge( $links, $new_links );
	}
	
	return $links;
}
add_filter( 'plugin_row_meta', 'superpwa_plugin_row_meta', 10, 2 );

/**
 * Admin footer text
 *
 * A function to add footer text to the settings page of the plugin. Footer text contains plugin rating and donation links.
 * Note: Remove the rating link if the plugin doesn't have a WordPress.org directory listing yet. (i.e. before initial approval)
 * @since	1.0
 * @refer	https://codex.wordpress.org/Function_Reference/get_current_screen
 */
function superpwa_footer_text($default) {
    
	// Retun default on non-plugin pages
	$screen = get_current_screen();
	if ( $screen->id !== "settings_page_superpwa" ) {
		return $default;
	}
	
    $superpwa_footer_text = sprintf( __( 'If you like SuperPWA, please leave a <a href="%s" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating to support continued development. Thanks a bunch!', 'super-progressive-web-apps' ), 
	'https://wordpress.org/support/plugin/super-progressive-web-apps/reviews/?rate=5#new-post' 
	);
	
	return $superpwa_footer_text;
}
add_filter('admin_footer_text', 'superpwa_footer_text');

/**
 * Admin footer version
 *
 * @since	1.0
 */
function superpwa_footer_version($default) {
	
	// Retun default on non-plugin pages
	$screen = get_current_screen();
	if ( $screen->id !== "settings_page_superpwa" ) {
		return $default;
	}
	
	return 'Plugin version ' . SUPERPWA_VERSION;
}
add_filter( 'update_footer', 'superpwa_footer_version', 11 );