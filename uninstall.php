<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * Everything in uninstall.php will be executed when user decides to delete the plugin. 
 * @since		1.0
 */


// Exit if accessed directly
if ( ! defined('ABSPATH') ) exit;

// If uninstall not called from WordPress, then die.
if ( ! defined('WP_UNINSTALL_PLUGIN') ) die;
 

/**
 * Delete database settings
 *
 * @since 1.0
 */ 
delete_option( 'superpwa_settings' );
delete_option( 'superpwa_version' );

/**
 * Clean up for Multisites
 *
 * @since 1.6
 */
if ( is_multisite() ) {
	
	// Todo: Loop through all sites and delete the saved settings.superpwa_settings
	
	// Delete the list of websites where SuperPWA was activated.
	delete_site_option( 'superpwa_active_sites' );
}