<?php 
/**
 * Basic setup functions for the plugin
 *
 * @since 1.0
 * @function	superpwa_activate_plugin()			Plugin activatation todo list
 * @function	superpwa_admin_notice_activation()	Admin notice on plugin activation
 * @function	superpwa_upgrader()					Plugin upgrade todo list
 * @function	superpwa_deactivate_plugin()		Plugin deactivation todo list
 * @function	superpwa_load_plugin_textdomain()	Load plugin text domain
 * @function	superpwa_settings_link()			Print direct link to plugin settings in plugins list in admin
 * @function	superpwa_plugin_row_meta()			Add donate and other links to plugins list
 */

// Exit if accessed directly
if ( ! defined('ABSPATH') ) exit;
 
/**
 * Plugin activatation todo list
 *
 * This function runs when user activates the plugin. Used in register_activation_hook()
 * 
 * @since 1.0
 * @since 1.6 register_activation_hook() moved to this file (basic-setup.php) from main plugin file (superpwa.php)
 */
function superpwa_activate_plugin() {
	
	// Generate manifest with default options
	superpwa_generate_manifest();
	
	// Generate service worker
	superpwa_generate_sw();
	
	// Set transient for activation notice
	set_transient( 'superpwa_admin_notice_activation', true, 5 );
}
register_activation_hook( SUPERPWA_PATH_ABS . 'superpwa.php', 'superpwa_activate_plugin' );

/**
 * Admin notice on plugin activation
 *
 * @since 1.2
 */
function superpwa_admin_notice_activation() {
 
    // Return if transient is not set
	if ( ! get_transient( 'superpwa_admin_notice_activation' ) ) {
		return;
	}
	
	$superpwa_is_ready = is_ssl() && superpwa_get_contents( superpwa_manifest( 'abs' ) ) && superpwa_get_contents( superpwa_sw( 'abs' ) ) && ( ! superpwa_onesignal_manifest_notice_check() ) ? 'Your app is ready with the default settings. ' : '';
	
	echo '<div class="updated notice is-dismissible"><p>' . sprintf( __( 'Thank you for installing <strong>Super Progressive Web Apps!</strong> '. $superpwa_is_ready .'<a href="%s">Customize your app &rarr;</a>', 'super-progressive-web-apps' ), admin_url( 'options-general.php?page=superpwa' ) ) . '</p></div>';
	
	// Delete transient
	delete_transient( 'superpwa_admin_notice_activation' );
}
add_action( 'admin_notices', 'superpwa_admin_notice_activation' );

/**
 * Plugin upgrade todo list
 *
 * @since 1.3.1
 * @since 1.4 Added orientation setting and theme_color to database when upgrading from pre 1.4 versions.
 */
function superpwa_upgrader() {
	
	$current_ver = get_option('superpwa_version');
	
	// Return if we have already done this todo
	if ( $current_ver == SUPERPWA_VERSION ) 
		return;
	
	// Return if this is the first time the plugin is installed.
	if ( $current_ver === false ) {
		
		add_option( 'superpwa_version', SUPERPWA_VERSION );
		return;
	}
	
	if ( $current_ver < 1.4 ) {
		
		// Get settings
		$settings = superpwa_get_settings();
		
		// Orientation was set as 'natural' until version 1.4. Set it as 1, which is 'portrait'.
		$settings['orientation'] = 1;
		
		// theme_color was same as background_color until version 1.4
		$settings['theme_color'] = $settings['background_color'];
		
		// Write settings back to database
		update_option( 'superpwa_settings', $settings );
	}
	
	// Re-generate manifest
	superpwa_generate_manifest();
	
	// Re-generate service worker
	superpwa_generate_sw();
	
	// Add current version to database
	update_option( 'superpwa_version', SUPERPWA_VERSION );
}
add_action( 'admin_init', 'superpwa_upgrader' );

/**
 * Plugin deactivation todo list
 *
 * Runs during deactivation. During uninstall uninstall.php is also executed
 * 
 * @since 1.0
 * @since 1.6 register_deactivation_hook() moved to this file (basic-setup.php) from main plugin file (superpwa.php)
 */
function superpwa_deactivate_plugin() {
	
	// Delete manifest
	superpwa_delete_manifest();
	
	// Delete service worker
	superpwa_delete_sw();
}
register_deactivation_hook( SUPERPWA_PATH_ABS . 'superpwa.php', 'superpwa_deactivate_plugin' );

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
				'demo' 	=> '<a href="https://superpwa.com" target="_blank">' . __( 'Demo', 'super-progressive-web-apps' ) . '</a>',
				);
		$links = array_merge( $links, $new_links );
	}
	
	return $links;
}
add_filter( 'plugin_row_meta', 'superpwa_plugin_row_meta', 10, 2 );