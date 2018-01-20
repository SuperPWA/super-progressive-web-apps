<?php
/**
 * Filesystem Operations
 *
 * @since 1.0
 * @function	superpwa_wp_filesystem_init()	Initialize the WP filesystem
 * @function	superpwa_put_contents()			Write to a file using WP_Filesystem() functions
 * @function	superpwa_get_contents()			Read contents of a file using WP_Filesystem() functions
 * @function	superpwa_delete()				Delete a file
 */

// Exit if accessed directly
if ( ! defined('ABSPATH') ) exit;

/**
 * Initialize the WP filesystem
 *
 * @since 1.0
 */
function superpwa_wp_filesystem_init() {
	
	global $wp_filesystem;
	
	if ( empty( $wp_filesystem ) ) {
		require_once( trailingslashit( ABSPATH ) . 'wp-admin/includes/file.php' );
		WP_Filesystem();
	}
}

/**
 * Write to a file using WP_Filesystem() functions
 *
 * @param	$file		Filename with path
 * @param	$content	Contents to be written to the file. Default null
 * @return	True on success, false if file isn't passed or if writing failed.
 * @since	1.0
 */
function superpwa_put_contents( $file, $content = null ) {
	
	// Return false if no filename is provided
	if ( empty( $file ) )
		return false;
	
	// Initialize the WP filesystem
	superpwa_wp_filesystem_init();
	global $wp_filesystem;
	
	if( ! $wp_filesystem->put_contents( $file, $content, 0644) ) {
		return false;
	}
	
	return true;
}

/**
 * Read contents of a file using WP_Filesystem() functions
 *
 * @param	$file	Filename with path.
 * @paral	$array	Set true to return read data as an array. False by default.
 * @return	string|bool	The function returns the read data or false on failure.
 * @since 	1.0
 */
function superpwa_get_contents( $file, $array = false ) {
	
	// Return false if no filename is provided
	if ( empty( $file ) )
		return false;
	
	// Initialize the WP filesystem
	superpwa_wp_filesystem_init();
	global $wp_filesystem;
	
	// Reads entire file into a string
	if ( $array == false )
		return $wp_filesystem->get_contents( $file );
	
	// Reads entire file into an array
	return $wp_filesystem->get_contents_array( $file );
}

/**
 * Delete a file
 * 
 * @param	$file	Filename with path
 * @return	bool	True on success, false otherwise
 * @since	1.0
 */
function superpwa_delete( $file ) {
	
	// Return false if no filename is provided
	if ( empty( $file ) )
		return false;
	
	// Initialize the WP filesystem
	superpwa_wp_filesystem_init();
	global $wp_filesystem;
	
	return $wp_filesystem->delete( $file );
}