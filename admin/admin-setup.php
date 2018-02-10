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
	
	// Basic Application Settings
    add_settings_section(
        'superpwa_basic_settings_section',					// ID
        __return_false(),									// Title
        '__return_false',									// Callback Function
        'superpwa_basic_settings_section'					// Page slug
    );
	
		// Application Name
		add_settings_field(
			'superpwa_app_name',									// ID
			__('Application Name', 'super-progressive-web-apps'),	// Title
			'superpwa_app_name_cb',									// CB
			'superpwa_basic_settings_section',						// Page slug
			'superpwa_basic_settings_section'						// Settings Section ID
		);
		
		// Application Short Name
		add_settings_field(
			'superpwa_app_short_name',								// ID
			__('Application Short Name', 'super-progressive-web-apps'),	// Title
			'superpwa_app_short_name_cb',							// CB
			'superpwa_basic_settings_section',						// Page slug
			'superpwa_basic_settings_section'						// Settings Section ID
		);
		
		// Application Icon
		add_settings_field(
			'superpwa_icons',										// ID
			__('Application Icon', 'super-progressive-web-apps'),	// Title
			'superpwa_app_icon_cb',									// Callback function
			'superpwa_basic_settings_section',						// Page slug
			'superpwa_basic_settings_section'						// Settings Section ID
		);
		
		// Splash Screen Icon
		add_settings_field(
			'superpwa_splash_icon',									// ID
			__('Splash Screen Icon', 'super-progressive-web-apps'),	// Title
			'superpwa_splash_icon_cb',								// Callback function
			'superpwa_basic_settings_section',						// Page slug
			'superpwa_basic_settings_section'						// Settings Section ID
		);
		
		// Splash Screen Background Color
		add_settings_field(
			'superpwa_background_color',							// ID
			__('Background Color', 'super-progressive-web-apps'),	// Title
			'superpwa_background_color_cb',							// CB
			'superpwa_basic_settings_section',						// Page slug
			'superpwa_basic_settings_section'						// Settings Section ID
		);
		
		// Start URL
		add_settings_field(
			'superpwa_start_url',									// ID
			__('Start Page', 'super-progressive-web-apps'),			// Title
			'superpwa_start_url_cb',								// CB
			'superpwa_basic_settings_section',						// Page slug
			'superpwa_basic_settings_section'						// Settings Section ID
		);
		
		// Offline Page
		add_settings_field(
			'superpwa_offline_page',								// ID
			__('Offline Page', 'super-progressive-web-apps'),		// Title
			'superpwa_offline_page_cb',								// CB
			'superpwa_basic_settings_section',						// Page slug
			'superpwa_basic_settings_section'						// Settings Section ID
		);
		
	// PWA Status
    add_settings_section(
        'superpwa_pwa_status_section',					// ID
        __('Status', 'super-progressive-web-apps'),		// Title
        '__return_false',								// Callback Function
        'superpwa_pwa_status_section'					// Page slug
    );
	
		// Manifest status
		add_settings_field(
			'superpwa_manifest_status',								// ID
			__('Manifest', 'super-progressive-web-apps'),			// Title
			'superpwa_manifest_status_cb',							// CB
			'superpwa_pwa_status_section',							// Page slug
			'superpwa_pwa_status_section'							// Settings Section ID
		);
		
		// Service Worker status
		add_settings_field(
			'superpwa_sw_status',								// ID
			__('Service Worker', 'super-progressive-web-apps'),		// Title
			'superpwa_sw_status_cb',							// CB
			'superpwa_pwa_status_section',							// Page slug
			'superpwa_pwa_status_section'							// Settings Section ID
		);	
		
		// HTTPS status
		add_settings_field(
			'superpwa_https_status',								// ID
			__('HTTPS', 'super-progressive-web-apps'),				// Title
			'superpwa_https_status_cb',							// CB
			'superpwa_pwa_status_section',							// Page slug
			'superpwa_pwa_status_section'							// Settings Section ID
		);	
}
add_action( 'admin_init', 'superpwa_register_settings' );

/**
 * Validate and sanitize user input before its saved to database
 *
 * @since 		1.0
 */
function superpwa_validater_and_sanitizer( $settings ) {
	
	// Sanitize Application Name
	$settings['app_name'] = sanitize_text_field($settings['app_name']) == '' ? get_bloginfo('name') : sanitize_text_field($settings['app_name']);
	
	// Sanitize Application Short Name
	$settings['app_short_name'] = sanitize_text_field($settings['app_short_name']) == '' ? get_bloginfo('name') : sanitize_text_field($settings['app_short_name']);
	
	// Sanitize hex color input
	$settings['background_color'] = preg_match( '/#([a-f0-9]{3}){1,2}\b/i', $settings['background_color'] ) ? sanitize_text_field( $settings['background_color'] ) : '#D5E0EB';
	
	// Sanitize application icon
	$settings['icon'] = sanitize_text_field( $settings['icon'] ) == '' ? SUPERPWA_PATH_SRC . 'public/images/logo.png' : sanitize_text_field( $settings['icon'] );
	
	// Sanitize splash screen icon
	$settings['splash_icon'] = sanitize_text_field( $settings['splash_icon'] );
	
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
				'app_name'			=> get_bloginfo('name'),
				'app_short_name'	=> get_bloginfo('name'),
				'icon'				=> SUPERPWA_PATH_SRC . 'public/images/logo.png',
				'splash_icon'		=> SUPERPWA_PATH_SRC . 'public/images/logo-512x512.png',
				'background_color' 	=> '#D5E0EB',
				'start_url' 		=> 0,
				'start_url_amp'		=> 0,
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
    wp_enqueue_script( 'superpwa-main-js', SUPERPWA_PATH_SRC . 'admin/js/main.js', array( 'wp-color-picker' ), SUPERPWA_VERSION, true );
}
add_action( 'admin_enqueue_scripts', 'superpwa_enqueue_css_js' );