<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * Everything in uninstall.php will be executed when user decides to delete the plugin. 
 * 
 * @since 1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// If uninstall not called from WordPress, then die.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) die;

/**
 * Clean up for Multisites
 *
 * @since 1.6
 * @since 1.7 Added clean-up for superpwa_active_addons and superpwa_utm_tracking_settings
 */
if ( is_multisite() ) {
	
	// Retrieve the list of blog ids where SuperPWA is active. (saved with blog_id as $key and activation_status as $value)
	$superpwa_sites = get_site_option( 'superpwa_active_sites' );
	
	// Loop through each active site.
	foreach( $superpwa_sites as $blog_id => $actviation_status ) {
		
		// Switch to each blog
		switch_to_blog( $blog_id );
		
		// Delete database settings for each site.
		superpwa_delete_all_options();

		// Return to main site
		restore_current_blog();
	}
	
	// Delete the list of websites where SuperPWA was activated.
	delete_site_option( 'superpwa_active_sites' );
} else {
	superpwa_delete_all_options();
}

/**
 * Delete database settings
 *
 * @since 1.0
 * @since 1.7 Added clean-up for superpwa_active_addons and superpwa_utm_tracking_settings
 * @since 2.2.23 Added clean-up for superpwa_pull_to_refresh_settings , superpwa_apple_icons_settings , superpwa_apple_icons_uploaded , superpwa_caching_strategies_settings
 */
function superpwa_delete_all_options(){

		//deleting options
		delete_option( 'superpwa_settings' );
		delete_option( 'superpwa_active_addons' );
		delete_option( 'superpwa_utm_tracking_settings' );
		delete_option( 'superpwa_version' );
		delete_option( 'superpwa_hide_newsletter' );
		delete_option( 'superpwa_pull_to_refresh_settings' );
		delete_option( 'superpwa_apple_icons_settings' );
		delete_option( 'superpwa_apple_icons_uploaded' );
		delete_option( 'superpwa_caching_strategies_settings' );

		global $wp_filesystem;

		if(isset($wp_filesystem)){
		$upload_dir = wp_upload_dir();
		
		// deleting splashscreen folder
		$folder_to_delete= $upload_dir['basedir'].'/superpwa-splashIcons';
		if($wp_filesystem->is_dir($folder_to_delete)){
			$wp_filesystem->delete($folder_to_delete, true);
		}
		// deleting manifest file
		if($wp_filesystem->is_file(ABSPATH.'superpwa-manifest.json')){
			$wp_filesystem->delete(ABSPATH.'superpwa-manifest.json');
		}
		// deleting service worker file
		if($wp_filesystem->is_file(ABSPATH.'superpwa-sw.js')){
			$wp_filesystem->delete(ABSPATH.'superpwa-sw.js');
		}
	}
}