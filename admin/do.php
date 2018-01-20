<?php
/**
 * Operations of the plugin are included here. 
 *
 * @since 1.0
 * @function	superpwa_after_save_settings_todo()		Todo list after saving admin options
 */

// Exit if accessed directly
if ( ! defined('ABSPATH') ) exit;

/**
 * Todo list after saving admin options
 *
 * Regenerate manifest
 * Regenerate service worker
 *
 * @since	1.0
 */
function superpwa_after_save_settings_todo() {
	
	// Regenerate manifest
	superpwa_generate_manifest();
	
	// Regenerate service worker
	superpwa_generate_sw();
}
add_action( 'add_option_superpwa_settings', 'superpwa_after_save_settings_todo' );
add_action( 'update_option_superpwa_settings', 'superpwa_after_save_settings_todo' );