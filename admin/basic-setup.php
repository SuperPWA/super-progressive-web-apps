<?php 
/**
 * Basic setup functions for the plugin
 *
 * @since 1.0
 * 
 * @function	superpwa_activate_plugin()			Plugin activatation todo list
 * @function	superpwa_admin_notices()			Admin notices
 * @function	superpwa_network_admin_notices()	Network Admin notices
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
 * On multisites, during network activation, this is fired only for the main site.
 * For the rest of the sites, superpwa_upgrader() handles generation of manifest and service worker. 
 *
 * @param $network_active (Boolean) True if the plugin is network activated, false otherwise. 
 * @link https://www.alexgeorgiou.gr/network-activated-wordpress-plugins/ (Thanks Alex!)
 * 
 * @since 1.0
 * @since 1.6 register_activation_hook() moved to this file (basic-setup.php) from main plugin file (superpwa.php).
 * @since 1.6 Added checks for multisite compatibility.
 */
function superpwa_activate_plugin( $network_active ) {
	
	// Generate manifest with default options
	superpwa_generate_manifest();
	
	// Generate service worker
	superpwa_generate_sw();
	
	// Not network active i.e. plugin is activated on a single install (normal WordPress install) or a single site on a multisite network
	if ( ! $network_active ) {
		
		// Set transient for single site activation notice
		set_transient( 'superpwa_admin_notice_activation', true, 60 );
		
		return;
	}
		
	// If we are here, then plugin is network activated on a multisite. Set transient for activation notice on network admin.
	set_transient( 'superpwa_network_admin_notice_activation', true, 60 );
}
register_activation_hook( SUPERPWA_PATH_ABS . 'superpwa.php', 'superpwa_activate_plugin' );

/**
 * Admin Notices
 *
 * @since 1.2 Admin notice on plugin activation
 */
function superpwa_admin_notices() {
	
	// Notices only for admins
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
 
    // Admin notice on plugin activation
	if ( get_transient( 'superpwa_admin_notice_activation' ) ) {
	
		$superpwa_is_ready = superpwa_is_pwa_ready() ? 'Your app is ready with the default settings. ' : '';
		
		echo '<div class="updated notice is-dismissible"><p>' . sprintf( __( 'Thank you for installing <strong>Super Progressive Web Apps!</strong> '. $superpwa_is_ready .'<a href="%s">Customize your app &rarr;</a>', 'super-progressive-web-apps' ), admin_url( 'admin.php?page=superpwa' ) ) . '</p></div>';
		
		// Delete transient
		delete_transient( 'superpwa_admin_notice_activation' );
	}
	
	// Admin notice on plugin upgrade
	if ( get_transient( 'superpwa_admin_notice_upgrade_complete' ) ) {
		
		echo '<div class="updated notice is-dismissible"><p>' . sprintf( __( '<strong>SuperPWA</strong>: Successfully updated to version %s. Thank you! <a href="%s" target="_blank">Discover new features and read the story &rarr;</a>', 'super-progressive-web-apps' ), SUPERPWA_VERSION, 'https://superpwa.com/category/release-notes/latest/?utm_source=superpwa-plugin&utm_medium=update-success-notice' ) . '</p></div>';
		
		// Delete transient
		delete_transient( 'superpwa_admin_notice_upgrade_complete' );
	}
}
add_action( 'admin_notices', 'superpwa_admin_notices' );

/**
 * Network Admin notices
 *
 * @since 1.6 Admin notice on multisite network activation
 */
function superpwa_network_admin_notices() {
	
	// Notices only for admins
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
 
    // Network admin notice on multisite network activation
	if ( get_transient( 'superpwa_network_admin_notice_activation' ) ) {
	
		$superpwa_is_ready = superpwa_is_pwa_ready() ? 'Your app is ready on the main website with the default settings. ' : '';
		
		echo '<div class="updated notice is-dismissible"><p>' . sprintf( __( 'Thank you for installing <strong>Super Progressive Web Apps!</strong> '. $superpwa_is_ready .'<a href="%s">Customize your app &rarr;</a><br/>Note: manifest and service worker for the individual websites will be generated on the first visit to the respective WordPress admin.', 'super-progressive-web-apps' ), admin_url( 'admin.php?page=superpwa' ) ) . '</p></div>';
		
		// Delete transient
		delete_transient( 'superpwa_network_admin_notice_activation' );
	}
	
	// Network admin notice on plugin upgrade
	if ( get_transient( 'superpwa_admin_notice_upgrade_complete' ) ) {
		
		echo '<div class="updated notice is-dismissible"><p>' . sprintf( __( '<strong>SuperPWA</strong>: Successfully updated to version %s. Thank you! <a href="%s" target="_blank">Discover new features and read the story &rarr;</a>', 'super-progressive-web-apps' ), SUPERPWA_VERSION, 'https://superpwa.com/category/release-notes/latest/?utm_source=superpwa-plugin&utm_medium=update-success-notice-multisite' ) . '</p></div>';
		
		// Delete transient
		delete_transient( 'superpwa_admin_notice_upgrade_complete' );
	}
}
add_action( 'network_admin_notices', 'superpwa_network_admin_notices' );

/**
 * Plugin upgrade todo list
 *
 * @since 1.3.1
 * @since 1.4 Added orientation setting and theme_color to database when upgrading from pre 1.4 versions.
 * @since 1.6 Added multisite compatibility.
 */
function superpwa_upgrader() {
	
	$current_ver = get_option( 'superpwa_version' );
	
	// Return if we have already done this todo
	if ( version_compare( $current_ver, SUPERPWA_VERSION, '==' ) ) {
		return;
	}
	
	/**
	 * Todo list for fresh install.
	 *
	 * On a multisite, during network activation, the activation hook (and activation todo) is not fired.
	 * Manifest and service worker is generated the first time the wp-admin is loaded (when admin_init is fired).
	 */
	if ( $current_ver === false ) {
		
		if ( is_multisite() ) {
			
			// Generate manifestx
			superpwa_generate_manifest();
			
			// Generate service worker
			superpwa_generate_sw();
			
			// For multisites, save the activation status of current blog.
			superpwa_multisite_activation_status( true );
		}
		
		// Save SuperPWA version to database.
		add_option( 'superpwa_version', SUPERPWA_VERSION );
		
		return;
	}
	
	/**
	 * Add orientation and theme_color to database when upgrading from pre 1.4 versions.
	 * 
	 * Until 1.4, there was no UI for orientation and theme_color.
	 * In the manifest, orientation was hard coded as 'natural'.
	 * background_color had UI and this value was used for both background_color and theme_color in the manifest.
	 * 
	 * @since 1.4
	 */
	if ( version_compare( $current_ver, '1.3.1', '<=' ) ) {
		
		// Get settings
		$settings = superpwa_get_settings();
		
		// Orientation was set as 'natural' until version 1.4. Set it as 1, which is 'portrait'.
		$settings['orientation'] = 1;
		
		// theme_color was same as background_color until version 1.4
		$settings['theme_color'] = $settings['background_color'];
		
		// Write settings back to database
		update_option( 'superpwa_settings', $settings );
	}
	
	/**
	 * Delete existing service worker for single sites that use OneSignal.
	 * 
	 * For OneSignal compatibility, in version 1.8 the service worker filename is renamed. 
	 * If OneSignal is active, by this point, the new filename will be filtered in. 
	 * This upgrade routine restores the defaul service worker filename and deletes the existing service worker. 
	 * Also adds back the filter for new filename. OneSignal compatibility for multisites is not available at this point.
	 * 
	 * @since 1.8
	 */
	if ( version_compare( $current_ver, '1.7.1', '<=' ) && class_exists( 'OneSignal' ) && ! is_multisite() ) {
		
		// Restore the default service worker filename of SuperPWA.
		remove_filter( 'superpwa_sw_filename', 'superpwa_onesignal_sw_filename' );
		
		// Delete service worker if it exists.
		superpwa_delete_sw();
		
		// Change service worker filename to match OneSignal's service worker.
		add_filter( 'superpwa_sw_filename', 'superpwa_onesignal_sw_filename' );
	}
	
	// Re-generate manifest
	superpwa_generate_manifest();
	
	// Re-generate service worker
	superpwa_generate_sw();
	
	// Add current version to database
	update_option( 'superpwa_version', SUPERPWA_VERSION );
	
	// For multisites, save the activation status of current blog.
	superpwa_multisite_activation_status( true );
	
	// Set transient for upgrade complete notice
	set_transient( 'superpwa_admin_notice_upgrade_complete', true, 60 );
}
add_action( 'admin_init', 'superpwa_upgrader' );

/**
 * Plugin deactivation todo list
 *
 * Runs during deactivation. 
 * During uninstall uninstall.php is also executed.
 *
 * @param $network_active (Boolean) True if the plugin is network activated, false otherwise. 
 * @link https://www.alexgeorgiou.gr/network-activated-wordpress-plugins/ (Thanks Alex!)
 * 
 * @since 1.0
 * @since 1.6 register_deactivation_hook() moved to this file (basic-setup.php) from main plugin file (superpwa.php)
 */
function superpwa_deactivate_plugin( $network_active ) {
	
	// Delete manifest
	superpwa_delete_manifest();
	
	// Delete service worker
	superpwa_delete_sw();
	
	// For multisites, save the de-activation status of current blog.
	superpwa_multisite_activation_status( false );
	
	// Run the network deactivator during network deactivation
	if ( $network_active === true ) {
		superpwa_multisite_network_deactivator();
	}
}
register_deactivation_hook( SUPERPWA_PATH_ABS . 'superpwa.php', 'superpwa_deactivate_plugin' );

/**
 * Load plugin text domain
 *
 * @since 1.0
 */
function superpwa_load_plugin_textdomain() {
	load_plugin_textdomain( 'super-progressive-web-apps', false, '/super-progressive-web-apps/languages/' );
}
add_action( 'plugins_loaded', 'superpwa_load_plugin_textdomain' );

/**
 * Print direct link to plugin settings in plugins list in admin
 *
 * @since 1.0
 */
function superpwa_settings_link( $links ) {
	
	return array_merge(
		array(
			'settings' => '<a href="' . admin_url( 'admin.php?page=superpwa' ) . '">' . __( 'Settings', 'super-progressive-web-apps' ) . '</a>'
		),
		$links
	);
}
add_filter( 'plugin_action_links_super-progressive-web-apps/superpwa.php', 'superpwa_settings_link' );

/**
 * Add donate and other links to plugins list
 *
 * @since 1.0
 */
function superpwa_plugin_row_meta( $links, $file ) {
	
	if ( strpos( $file, 'superpwa.php' ) !== false ) {
		$new_links = array(
				'demo' 	=> '<a href="https://superpwa.com/?utm_source=superpwa-plugin&utm_medium=plugin_row_meta" target="_blank">' . __( 'Demo', 'super-progressive-web-apps' ) . '</a>',
				);
		$links = array_merge( $links, $new_links );
	}
	
	return $links;
}
add_filter( 'plugin_row_meta', 'superpwa_plugin_row_meta', 10, 2 );