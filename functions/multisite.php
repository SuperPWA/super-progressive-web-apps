<?php
/**
 * Functions for compatibility with WordPress multisites
 *
 * @since 1.6
 * @function	superpwa_multisite_filename_postfix()	 Filename postfix for multisites
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Filename postfix for multisites
 * 
 * @return String Returns the current blog ID on a multisite. An empty string otherwise
 * @since 1.6
 */
function superpwa_multisite_filename_postfix() {
	
	// Return empty string if not a multisite
	if ( ! is_multisite() ) {
		return '';
	}
	
	return '-' . get_current_blog_id();
}