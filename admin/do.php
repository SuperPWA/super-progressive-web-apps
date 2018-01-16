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
 * Regenerate manifest.json
 *
 * @since	1.0
 */
function superpwa_after_save_settings_todo() {
	
	// Regenerate manifes.json
	superpwa_generate_manifest();
	
	// Regenerate superpwa-sw.js
	superpwa_generate_sw();
}
add_action( 'update_option_superpwa_settings', 'superpwa_after_save_settings_todo' );