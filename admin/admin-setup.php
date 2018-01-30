<?php
/**
 * Admin setup for the plugin
 *
 * @since 1.0
 * @function	superpwa_add_menu_links()			Add admin menu pages
 * @function	superpwa_register_settings			Register Settings
 * @function	superpwa_validater_and_sanitizer()	Validate And Sanitize User Input Before Its Saved To Database
 * @function	superpwa_get_settings()		Get settings from database
 */

// Exit if accessed directly
if ( ! defined('ABSPATH') ) exit; 
 
/**
 * Add admin menu pages
 *
 * @since 	1.0
 * @refer	https://developer.wordpress.org/plugins/administration-menus/
 */
function superpwa_add_menu_links() {
	
	add_options_page( __('Super Progressive Web Apps','super-progressive-web-apps'), __('SuperPWA','super-progressive-web-apps'), 'manage_options', 'superpwa','superpwa_admin_interface_render'  );
}
add_action( 'admin_menu', 'superpwa_add_menu_links' );

/**
 * Register Settings
 *
 * @since 	1.0
 */
function superpwa_register_settings() {

	// Register Setting
	register_setting( 
		'superpwa_settings_group', 			// Group name
		'superpwa_settings', 				// Setting name = html form <input> name on settings form
		'superpwa_validater_and_sanitizer'	// Input sanitizer
	);
	
	// Manifest
    add_settings_section(
        'superpwa_manifest_section',						// ID
        __('Manifest', 'super-progressive-web-apps'),		// Title
        'superpwa_manifest_cb',								// Callback Function
        'superpwa_manifest_section'							// Page slug
    );
	
		// Manifest status
		add_settings_field(
			'superpwa_manifest_status',								// ID
			__('Manifest Status', 'super-progressive-web-apps'),	// Title
			'superpwa_manifest_status_cb',							// Callback function
			'superpwa_manifest_section',							// Page slug
			'superpwa_manifest_section'								// Settings Section ID
		);
	
	// Splash Screen Settings
    add_settings_section(
        'superpwa_splash_screen_section',					// ID
        __('Splash Screen', 'super-progressive-web-apps'),	// Title
        'superpwa_splash_screen_cb',						// Callback Function
        'superpwa_splash_screen_section'					// Page slug
    );
	
		// Background Color
		add_settings_field(
			'superpwa_background_color',							// ID
			__('Background Color', 'super-progressive-web-apps'),	// Title
			'superpwa_background_color_cb',						// Callback function
			'superpwa_splash_screen_section',						// Page slug
			'superpwa_splash_screen_section'						// Settings Section ID
		);
		
		// Icons
		add_settings_field(
			'superpwa_icons',										// ID
			__('Application Icon', 'super-progressive-web-apps'),	// Title
			'superpwa_icons_cb',									// Callback function
			'superpwa_splash_screen_section',						// Page slug
			'superpwa_splash_screen_section'						// Settings Section ID
		);
		
	// Offline Page
    add_settings_section(
        'superpwa_offline_page_section',					// ID
        __('Offline Page', 'super-progressive-web-apps'),	// Title
        'superpwa_offline_page_note_cb',					// Callback Function
        'superpwa_offline_page_section'						// Page slug
    );
	
		// Background Color
		add_settings_field(
			'superpwa_offline_page',							// ID
			__('Offline Page', 'super-progressive-web-apps'),	// Title
			'superpwa_offline_page_cb',							// Callback function
			'superpwa_offline_page_section',					// Page slug
			'superpwa_offline_page_section'						// Settings Section ID
		);
		
}
add_action( 'admin_init', 'superpwa_register_settings' );

/**
 * Validate and sanitize user input before its saved to database
 *
 * @since 		1.0
 */
function superpwa_validater_and_sanitizer( $settings ) {
	
	// Sanitize hex color input
	$settings['background_color'] = preg_match( '/#([a-f0-9]{3}){1,2}\b/i', $settings['background_color'] ) ? sanitize_text_field( $settings['background_color'] ) : '#D5E0EB';
	
	// Sanitize application icon
	$settings['icon'] = sanitize_text_field($settings['icon']) == '' ? SUPERPWA_PATH_SRC . 'public/images/logo.png' : sanitize_text_field($settings['icon']);
	
	return $settings;
}
			
/**
 * Get settings from database
 *
 * @since 	1.0
 * @return	Array	A merged array of default and settings saved in database. 
 */
function superpwa_get_settings() {

	$defaults = array(
				'background_color' 	=> '#D5E0EB',
				'icon'				=> SUPERPWA_PATH_SRC . 'public/images/logo.png',
				'offline_page' 		=> 0,
			);

	$settings = get_option('superpwa_settings', $defaults);
	
	return $settings;
}

/**
 * Enqueue CSS and JS
 *
 * @since	1.0
 */
function superpwa_enqueue_css_js( $hook ) {
	
    // Load only on SuperPWA plugin pages
	if ( $hook != "settings_page_superpwa" ) {
		return;
	}
	
	// Color picker CSS
	// @refer https://make.wordpress.org/core/2012/11/30/new-color-picker-in-wp-3-5/
    wp_enqueue_style( 'wp-color-picker' );
	
	// Everything needed for media upload
	wp_enqueue_media();
	
	// Main JS
    wp_enqueue_script( 'superpwa-main-js', SUPERPWA_PATH_SRC . 'admin/js/main.js', array( 'wp-color-picker' ), false, true );
}
add_action( 'admin_enqueue_scripts', 'superpwa_enqueue_css_js' );