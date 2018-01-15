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
 * @since		1.0
 */ 
delete_option( 'superpwa_settings' );
delete_option( 'superpwa_version' );