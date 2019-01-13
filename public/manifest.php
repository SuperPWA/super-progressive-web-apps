<?php
/**
 * Manifest related functions of SuperPWA
 *
 * @since 1.0
 * 
 * @function	superpwa_manifest()						Manifest filename, absolute path and link
 * @function	superpwa_generate_manifest()			Generate and write manifest
 * @function	superpwa_add_manifest_to_wp_head()		Add manifest to header (wp_head)
 * @function	superpwa_register_service_worker()		Register service worker in the footer (wp_footer)
 * @function	superpwa_delete_manifest()				Delete manifest
 * @function 	superpwa_get_pwa_icons()				Get PWA Icons
 * @function	superpwa_get_scope()					Get navigation scope of PWA
 * @function	superpwa_get_orientation()				Get orientation of PWA
 * @function	superpwa_get_display()					Get display of PWA
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Returns the Manifest filename.
 *
 * @since 2.0
 *
 * @return string
 */
function superpwa_get_manifest_filename() {
	return 'superpwa-manifest' . superpwa_multisite_filename_postfix() . '.json';
}

/**
 * Manifest filename, absolute path and link
 *
 * For Multisite compatibility. Used to be constants defined in superpwa.php
 * On a multisite, each sub-site needs a different manifest file.
 *
 * @uses  superpwa_get_manifest_filename()
 *
 * @param $arg    filename for manifest filename (replaces SUPERPWA_MANIFEST_FILENAME)
 *                abs for absolute path to manifest (replaces SUPERPWA_MANIFEST_ABS)
 *                src for link to manifest (replaces SUPERPWA_MANIFEST_SRC). Default value
 *
 * @return String filename, absolute path or link to manifest.
 *
 * @since 1.6
 * @since 2.0 src uses home_url instead of network_site_url since manifest is no longer in the root folder.
 */
function superpwa_manifest( $arg = 'src' ) {

	$manifest_filename = superpwa_get_manifest_filename();

	switch ( $arg ) {
		// TODO: Case `filename` can be deprecated in favor of @see superpwa_get_manifest_filename().
		// Name of Manifest file
		case 'filename':
			return $manifest_filename;
			break;

		/**
		* Absolute path to manifest. 
		* 
		* Note: @since 2.0 manifest is no longer a physical file and absolute path doesn't make sense. 
		* Also using home_url instead of network_site_url in "src" in 2.0 changes the apparent location of the file. 
		* However, absolute path is preserved at the "old" location, so that phyiscal files can be deleted when upgrading from pre-2.0 versions.
		* 
		* Since static files are being used in conditions where dynamic files are not possible, this path 
		* pointing to the root folder of WordPress is still useful. 
		*/
		case 'abs':
			return trailingslashit( ABSPATH ) . $manifest_filename;
			break;

		// Link to manifest
		case 'src':
		default:
		
			// Get Settings
			$settings = superpwa_get_settings();
			
			/**
			 * For static file, return site_url and network_site_url
			 * 
			 * Static files are generated in the root directory. 
			 * The site_url template tag retrieves the site url for the 
			 * current site (where the WordPress core files reside).
			 */
			if ( $settings['is_static_manifest'] === 1 ) {
				return trailingslashit( network_site_url() ) . $manifest_filename;
			}
			
			// For dynamic files, return the home_url
			return home_url( '/' ) . $manifest_filename;
			
			break;
	}
}

/**
 * Returns the Manifest template.
 *
 * @author Maria Daniel Deepak <daniel@danieldeepak.com>
 *
 * @return array
 * 
 * @since 2.0 Replaces superpwa_generate_manifest()
 * @since 2.0 Added display
 */
function superpwa_manifest_template() {
	
	// Get Settings
	$settings = superpwa_get_settings();

	$manifest               = array();
	$manifest['name']       = $settings['app_name'];
	$manifest['short_name'] = $settings['app_short_name'];

	// Description
	if ( isset( $settings['description'] ) && ! empty( $settings['description'] ) ) {
		$manifest['description'] = $settings['description'];
	}

	$manifest['icons']            = superpwa_get_pwa_icons();
	$manifest['background_color'] = $settings['background_color'];
	$manifest['theme_color']      = $settings['theme_color'];
	$manifest['display']          = superpwa_get_display();
	$manifest['orientation']      = superpwa_get_orientation();
	$manifest['start_url']        = superpwa_get_start_url( true );
	$manifest['scope']            = superpwa_get_scope();

	/**
	 * Values that go in to Manifest JSON.
	 *
	 * The Web app manifest is a simple JSON file that tells the browser about your web application.
	 *
	 * @param array $manifest
	 */
	return apply_filters( 'superpwa_manifest', $manifest );
}

/**
 * Generate and write manifest into WordPress root folder
 * 
 * Starting with 2.0, files are only generated if dynamic files are not possible. 
 * Some webserver configurations does not load WordPress and attempts to server files directly
 * from the server. This returns 404 when files do not exist physically. 
 *
 * @return (boolean) true on success, false on failure.
 * 
 * @author Arun Basil Lal
 * @author Maria Daniel Deepak <daniel@danieldeepak.com>
 *
 * @since 1.0
 * @since 1.3 Added support for 512x512 icon.
 * @since 1.4 Added orientation and scope.
 * @since 1.5 Added gcm_sender_id
 * @since 1.6 Added description
 * @since 1.8 Removed gcm_sender_id and introduced filter superpwa_manifest. gcm_sender_id is added in /3rd-party/onesignal.php
 * @since 2.0 Deprecated since Manifest is generated on the fly {@see superpwa_generate_sw_and_manifest_on_fly()}.
 * @since 2.0.1 No longer deprecated since physical files are now generated in certain cases. See funtion description. 
 */
function superpwa_generate_manifest() {
	
	// Delete manifest if it exists.
	superpwa_delete_manifest();
	
	// Get Settings
	$settings = superpwa_get_settings();
	
	// Return true if dynamic file returns a 200 response.
	if ( superpwa_file_exists( home_url( '/' ) . superpwa_get_manifest_filename() ) ) {
		
		// set file status as dynamic file in database.
		$settings['is_static_manifest'] = 0;
		
		// Write settings back to database.
		update_option( 'superpwa_settings', $settings );
		
		return true;
	}
	
	// Write the manfiest to disk.
	if ( superpwa_put_contents( superpwa_manifest( 'abs' ), json_encode( superpwa_manifest_template() ) ) ) {
		
		// set file status as satic file in database.
		$settings['is_static_manifest'] = 1;
		
		// Write settings back to database.
		update_option( 'superpwa_settings', $settings );
		
		return true;
	}
	
	return false;
}

/**
 * Add manifest to header (wp_head)
 *
 * @since 1.0
 * @since 1.8 Introduced filter superpwa_wp_head_tags
 * @since 1.9 Introduced filter superpwa_add_theme_color
 */
function superpwa_add_manifest_to_wp_head() {
	
	$tags  = '<!-- Manifest added by SuperPWA - Progressive Web Apps Plugin For WordPress -->' . PHP_EOL; 
	$tags .= '<link rel="manifest" href="'. parse_url( superpwa_manifest( 'src' ), PHP_URL_PATH ) . '">' . PHP_EOL;
	
	// theme-color meta tag 
	if ( apply_filters( 'superpwa_add_theme_color', true ) ) {
		
		// Get Settings
		$settings = superpwa_get_settings();
		$tags .= '<meta name="theme-color" content="'. $settings['theme_color'] .'">' . PHP_EOL;
	}
	
	$tags  = apply_filters( 'superpwa_wp_head_tags', $tags );
	
	$tags .= '<!-- / SuperPWA.com -->' . PHP_EOL; 
	
	echo $tags;
}
add_action( 'wp_head', 'superpwa_add_manifest_to_wp_head', 0 );

/**
 * Delete manifest
 *
 * @return (boolean) true on success, false on failure
 * 
 * @author Arun Basil Lal
 *
 * @since 1.0
 */
function superpwa_delete_manifest() {
	return superpwa_delete( superpwa_manifest( 'abs' ) );
}

/**
 * Get PWA Icons
 *
 * @return	array	An array of icons to be used as the application icons and splash screen icons
 * @since	1.3
 */
function superpwa_get_pwa_icons() {
	
	// Get settings
	$settings = superpwa_get_settings();
	
	// Application icon
	$icons_array[] = array(
							'src' 	=> $settings['icon'],
							'sizes'	=> '192x192', // must be 192x192. Todo: use getimagesize($settings['icon'])[0].'x'.getimagesize($settings['icon'])[1] in the future
							'type'	=> 'image/png', // must be image/png. Todo: use getimagesize($settings['icon'])['mime']
						);
	
	// Splash screen icon - Added since 1.3
	if ( @$settings['splash_icon'] != '' ) {
		
		$icons_array[] = array(
							'src' 	=> $settings['splash_icon'],
							'sizes'	=> '512x512', // must be 512x512.
							'type'	=> 'image/png', // must be image/png
						);
	}
	
	return $icons_array;
}

/**
 * Get navigation scope of PWA
 *
 * @return	string	Relative path to the folder where WordPress is installed. Same folder as manifest and wp-config.php
 * @since	1.4
 */
function superpwa_get_scope() {
	return parse_url( trailingslashit( get_bloginfo( 'url' ) ), PHP_URL_PATH );
}

/**
 * Get orientation of PWA
 *
 * @return	string	Orientation of PWA as set in the plugin settings. 
 * @since	1.4
 */
function superpwa_get_orientation() {
	
	// Get Settings
	$settings = superpwa_get_settings();
	
	$orientation = isset( $settings['orientation'] ) ? $settings['orientation'] : 0;
	
	switch ( $orientation ) {
		
		case 0:
			return 'any';
			break;
			
		case 1:
			return 'portrait';
			break;
			
		case 2:
			return 'landscape';
			break;
			
		default: 
			return 'any';
	}
}

/**
 * Get display of PWA
 *
 * @return	string	Display of PWA as set in the plugin settings.
 * 
 * @author Jose Varghese
 * 
 * @since 2.0
 */
function superpwa_get_display() {
	
	// Get Settings
	$settings = superpwa_get_settings();
	
	$display = isset( $settings['display'] ) ? $settings['display'] : 0;
	
	switch ( $display ) {
		
		case 0:
			return 'fullscreen';
			break;
			
		case 1:
			return 'standalone';
			break;
			
		case 2:
			return 'minimal-ui';
			break;

		case 3:
			return 'browser';
			break;
			
		default: 
			return 'standalone';
	}
}