<?php
/**
 * Functions for compatibility with WordPress multisites
 *
 * @since 1.6
 * @function	superpwa_multisite_filename_postfix()		Filename postfix for multisites
 * @function	superpwa_multisite_activation_status()		Save activation status for current blog id
 * @function	superpwa_multisite_network_deactivator()	Handle multisite network deactivation
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Filename postfix for multisites
 * 
 * @return (string) Returns the current blog ID on a multisite. An empty string otherwise
 * 
 * @since 1.6
 */
function superpwa_multisite_filename_postfix() {
	
	// Return empty string if not a multisite
	if ( ! is_multisite() ) {
		return '';
	}
	
	return '-' . get_current_blog_id();
}

/**
 * Save activation status for current blog id
 *
 * For clean multisite uninstall. 
 * Manifest and service worker are deleted during deactivation. 
 * Database settings are cleaned during uninstall
 *
 * @param (bool) $status True when plugin is activated, false when deactivated.
 * 
 * @since 1.6
 */
function superpwa_multisite_activation_status( $status ) {
	
	// Only for multisites
	if ( ! is_multisite() || ! isset( $status ) ) {
		return;
	}
	
	// Get current list of sites where SuperPWA is activated.
	$superpwa_sites = get_site_option( 'superpwa_active_sites', array() );
	
	// Set the status for the current blog.
	$superpwa_sites[ get_current_blog_id() ] = $status;
	
	/**
	 * If SuperPWA is deactivated and if the blog is using the default settings (i.e, settings not saved to database yet).
	 * 
	 * SuperPWA ships with smart defaults. Some blogs might not change the default settings. 
	 * Default settings are not saved to the database until the user clicks the "Save Settings" in the plugin UI. (refer superpwa_get_settings() )
	 * If that is the case, there is nothing more to clean up during uninstall, and we can remove the blog id from our list.
	 * 
	 * If settings are saved in the database, we want to preserve it, in case the user activates the plugin again. 
	 * These settings are removed during uninstall.
	 */
	if ( ( $status === false ) && ( get_option( 'superpwa_settings' ) === false ) ) {
		
		// Remove activation status completely from the list of active sites.
		unset( $superpwa_sites[ get_current_blog_id() ] );
	}
	
	// Save it back to the database.
	update_site_option( 'superpwa_active_sites', $superpwa_sites );
}

/**
 * Handle multisite network deactivation
 * 
 * Deletes manifest and service worker of all sub-sites.
 * Sets the deactivation status for each site.
 *
 * Not used when wp_is_large_network() is true. Deleting that many files and db options will most likely time out. 
 * This also this gives the user an option to decide if SuperPWA should handle this by changing the defenition of wp_is_large_network.
 * @link https://developer.wordpress.org/reference/functions/wp_is_large_network/
 */
function superpwa_multisite_network_deactivator() {
	
	// Do not run on large networks
	if ( wp_is_large_network() ) {
		return;
	}
	
	// Retrieve the list of blog ids where SuperPWA is active. (saved with blog_id as $key and activation_status as $value)
	$superpwa_sites = get_site_option( 'superpwa_active_sites' );
	
	// Loop through each active site.
	foreach( $superpwa_sites as $blog_id => $actviation_status ) {
		
		// If plugin is already deactivated, move to next blog. 
		if ( $actviation_status == false ) {
			continue;
		}
		
		// Switch to each blog
		switch_to_blog( $blog_id );
		
		// Delete manifest
		superpwa_delete_manifest();
	
		// Delete service worker
		superpwa_delete_sw();
	
		// Save the de-activation status of current blog.
		superpwa_multisite_activation_status( false );
		
		// Return to main site
		restore_current_blog();
	}
}