<?php 
/**
 * Basic setup functions for the plugin
 *
 * @since 1.0
 * @function	superpwa_activate_plugin()			Plugin activatation todo list
 * @function	superpwa_admin_notice_activation()	Admin notice on plugin activation
 * @function	superpwa_upgrader()					Plugin upgrade todo list
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
	
	// Set transient for activation notice
	set_transient( 'superpwa_admin_notice_activation', true, 5 );
}

/**
 * Add admin notice on activation
 *
 * @since 	1.2
 */
add_action( 'admin_notices', 'superpwa_admin_notice_activation' );

/**
 * Admin notice on plugin activation
 *
 * @since 	1.2
 */
function superpwa_admin_notice_activation() {
 
    // Return if transient is not set
	if ( ! get_transient( 'superpwa_admin_notice_activation' ) ) {
		return;
	}
	
	$superpwa_is_ready = is_ssl() && superpwa_get_contents( SUPERPWA_MANIFEST_ABS ) && superpwa_get_contents( SUPERPWA_SW_ABS ) && ( ! superpwa_onesignal_manifest_notice_check() ) ? 'Your app is ready with the default settings. ' : '';
	
	echo '<div class="updated notice is-dismissible"><p>' . sprintf( __( 'Thank you for installing <strong>Super Progressive Web Apps!</strong> '. $superpwa_is_ready .'<a href="%s">Customize your app &rarr;</a>', 'super-progressive-web-apps' ), admin_url( 'options-general.php?page=superpwa' ) ) . '</p></div>';
	
	// Delete transient
	delete_transient( 'superpwa_admin_notice_activation' );
}

/**
 * Plugin upgrade todo list
 *
 * @since	1.3.1
 * @since	1.4		Added orientation setting and theme_color to database when upgrading from pre 1.4 versions.
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
// add_filter( 'plugin_row_meta', 'superpwa_plugin_row_meta', 10, 2 ); // Todo: To be added once demo website is ready

/**
 * Admin footer text
 *
 * A function to add footer text to the settings page of the plugin.
 * @since	1.2
 * @refer	https://codex.wordpress.org/Function_Reference/get_current_screen
 */
function superpwa_footer_text( $default ) {
    
	// Retun default on non-plugin pages
	$screen = get_current_screen();
	if ( $screen->id !== "settings_page_superpwa" ) {
		return $default;
	}
	
    $superpwa_footer_text = sprintf( __( 'If you like our plugin, please <a href="%s" target="_blank">make a donation</a> or leave a <a href="%s" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating to support continued development. Thanks a bunch!', 'super-progressive-web-apps' ), 
	'https://millionclues.com/donate/',
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
	
	return 'SuperPWA ' . SUPERPWA_VERSION;
}
add_filter( 'update_footer', 'superpwa_footer_version', 11 );