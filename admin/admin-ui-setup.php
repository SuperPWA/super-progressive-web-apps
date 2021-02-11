<?php
/**
 * Admin setup for the plugin
 *
 * @since 1.0
 * @function	superpwa_add_menu_links()			Add admin menu pages
 * @function	superpwa_register_settings			Register Settings
 * @function	superpwa_validater_and_sanitizer()	Validate And Sanitize User Input Before Its Saved To Database
 * @function	superpwa_get_settings()				Get settings from database
 * @function 	superpwa_enqueue_css_js()			Enqueue CSS and JS
 * @function	superpwa_after_save_settings_todo()	Todo list after saving admin options
 * @function	superpwa_footer_text()				Admin footer text
 * @function	superpwa_footer_version()			Admin footer version
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit; 
 
/**
 * Add admin menu pages
 *
 * @since 	1.0
 * @refer	https://developer.wordpress.org/plugins/administration-menus/
 */
function superpwa_add_menu_links() {
	
	// Main menu page
	add_menu_page( __( 'Super Progressive Web Apps', 'super-progressive-web-apps' ), __( 'SuperPWA', 'super-progressive-web-apps' ), 'manage_options', 'superpwa','superpwa_admin_interface_render', SUPERPWA_PATH_SRC. 'admin/img/superpwa-menu-icon.png', 100 );
	
	// Settings page - Same as main menu page
	add_submenu_page( 'superpwa', __( 'Super Progressive Web Apps', 'super-progressive-web-apps' ), __( 'Settings', 'super-progressive-web-apps' ), 'manage_options', 'superpwa', 'superpwa_admin_interface_render', 60);

	// Add-Ons page
	add_submenu_page( 'superpwa', __( 'Super Progressive Web Apps', 'super-progressive-web-apps' ), __( 'Add-ons', 'super-progressive-web-apps' ), 'manage_options', 'superpwa-addons', 'superpwa_addons_interface_render', 70);

	// UTM Tracking sub-menu
	if ( superpwa_addons_status( 'utm_tracking' ) 		== 'active' ){ 
		add_submenu_page( 'superpwa', __( 'Super Progressive Web Apps', 'super-progressive-web-apps' ), __( 'UTM Tracking', 'super-progressive-web-apps' ), 'manage_options', 'superpwa-utm-tracking', 'superpwa_utm_tracking_interface_render', 72 );
	}

	// Caching Strategies sub-menu
	if ( superpwa_addons_status( 'caching_strategies' ) 		== 'active' ){ 
		add_submenu_page( 'superpwa', __( 'Super Progressive Web Apps', 'super-progressive-web-apps' ), __( 'Caching Strategies', 'super-progressive-web-apps' ), 'manage_options', 'superpwa-caching strategies', 'superpwa_caching_strategies_interface_render', 74 );
	}

	// Upgrade to pro page
	$textlicense = "<span style='color: #ff4c4c;font-weight: 700;font-size: 15px;'>".__( 'Upgrade to Pro', 'super-progressive-web-apps' )."</span>";
	if(defined('SUPERPWA_PRO_VERSION')){ $textlicense = __( 'License', 'super-progressive-web-apps' ); }
	add_submenu_page( 'superpwa', __( 'Super Progressive Web Apps', 'super-progressive-web-apps' ), $textlicense, 'manage_options', 'superpwa-upgrade', 'superpwa_upgread_pro_interface_render' , 9999999);
	
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
		
		// Description
		add_settings_field(
			'superpwa_description',									// ID
			__( 'Description', 'super-progressive-web-apps' ),		// Title
			'superpwa_description_cb',								// CB
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
		
		// Theme Color
		add_settings_field(
			'superpwa_theme_color',									// ID
			__('Theme Color', 'super-progressive-web-apps'),		// Title
			'superpwa_theme_color_cb',								// CB
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
		
		// Orientation
		add_settings_field(
			'superpwa_orientation',									// ID
			__('Orientation', 'super-progressive-web-apps'),		// Title
			'superpwa_orientation_cb',								// CB
			'superpwa_basic_settings_section',						// Page slug
			'superpwa_basic_settings_section'						// Settings Section ID
		);
	
		// Display
		add_settings_field(
			'superpwa_display',									// ID
			__('Display', 'super-progressive-web-apps'),		// Title
			'superpwa_display_cb',								// CB
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
			'superpwa_sw_status',									// ID
			__('Service Worker', 'super-progressive-web-apps'),		// Title
			'superpwa_sw_status_cb',								// CB
			'superpwa_pwa_status_section',							// Page slug
			'superpwa_pwa_status_section'							// Settings Section ID
		);	
		
		// HTTPS status
		add_settings_field(
			'superpwa_https_status',								// ID
			__('HTTPS', 'super-progressive-web-apps'),				// Title
			'superpwa_https_status_cb',								// CB
			'superpwa_pwa_status_section',							// Page slug
			'superpwa_pwa_status_section'							// Settings Section ID
		);	


	//Advance Page
	// PWA Advance settings
    add_settings_section(
        'superpwa_pwa_advance_section',					// ID
        __return_false(),		// Title
        '__return_false',								// Callback Function
        'superpwa_pwa_advance_section'					// Page slug
    );
    	// Disabling "Add to home screen"
		add_settings_field(
			'superpwa_disable_add_to_home',								// ID
			__('Disable "Add to home screen"', 'super-progressive-web-apps'),				// Title
			'superpwa_disable_add_to_home_cb',								// CB
			'superpwa_pwa_advance_section',							// Page slug
			'superpwa_pwa_advance_section'							// Settings Section ID
		);
        if (!defined('SUPERPWA_PRO_VERSION')) {
            // App shortcuts
            add_settings_field(
                'superpwa_app_shortcut',								// ID
            __('App shortcuts link', 'super-progressive-web-apps'),				// Title
            'superpwa_app_shortcut_link_cb',								// CB
            'superpwa_pwa_advance_section',							// Page slug
            'superpwa_pwa_advance_section'							// Settings Section ID
            );
        }
		// Yandex Support
		add_settings_field(
			'superpwa_yandex_support_shortcut',								// ID
			__('Yandex support', 'super-progressive-web-apps'),				// Title
			'superpwa_yandex_support_cb',								// CB
			'superpwa_pwa_advance_section',							// Page slug
			'superpwa_pwa_advance_section'							// Settings Section ID
		);
		// Analytics support
		add_settings_field(
			'superpwa_analytics_support_shortcut',								// ID
			__('Offline analytics ', 'super-progressive-web-apps'),				// Title
			'superpwa_analytics_support_cb',								// CB
			'superpwa_pwa_advance_section',							// Page slug
			'superpwa_pwa_advance_section'							// Settings Section ID
		);	
}
add_action( 'admin_init', 'superpwa_register_settings' );

/**
 * Validate and sanitize user input before its saved to database
 *
 * @author Arun Basil Lal
 * 
 * @param (array) $settings Values passed from the Settings API from SuperPWA > Settings
 * 
 * @since 1.0 
 * @since 1.3 Added splash_icon
 * @since 1.6 Added description
 * @since 2.0 Limit app_short_name to 12 characters
 * @since 2.0.1 Added is_static_sw and is_static_manifest
 */
function superpwa_validater_and_sanitizer( $settings ) {
	
	// Sanitize Application Name
	$settings['app_name'] = sanitize_text_field( $settings['app_name'] ) == '' ? get_bloginfo( 'name' ) : sanitize_text_field( $settings['app_name'] );
	
	// Sanitize Application Short Name
	$settings['app_short_name'] = substr( sanitize_text_field( $settings['app_short_name'] ) == '' ? get_bloginfo( 'name' ) : sanitize_text_field( $settings['app_short_name'] ), 0, 15 );
	
	// Sanitize description
	$settings['description'] = sanitize_text_field( $settings['description'] );
	
	// Sanitize hex color input for background_color
	$settings['background_color'] = preg_match( '/#([a-f0-9]{3}){1,2}\b/i', $settings['background_color'] ) ? sanitize_text_field( $settings['background_color'] ) : '#D5E0EB';
	
	// Sanitize hex color input for theme_color
	$settings['theme_color'] = preg_match( '/#([a-f0-9]{3}){1,2}\b/i', $settings['theme_color'] ) ? sanitize_text_field( $settings['theme_color'] ) : '#D5E0EB';
	
	// Sanitize application icon
	$settings['icon'] = sanitize_text_field( $settings['icon'] ) == '' ? superpwa_httpsify( SUPERPWA_PATH_SRC . 'public/images/logo.png' ) : sanitize_text_field( superpwa_httpsify( $settings['icon'] ) );
	
	// Sanitize splash screen icon
	$settings['splash_icon'] = sanitize_text_field( superpwa_httpsify( $settings['splash_icon'] ) );
	
	/**
	 * Get current settings already saved in the database.
	 * 
	 * When the SuperPWA > Settings page is saved, the form does not have the values for
	 * is_static_sw or is_static_manifest. So this is added here to match the already saved 
	 * values in the database. 
	 */
	$current_settings = superpwa_get_settings();
	
	if ( ! isset( $settings['is_static_sw'] ) ) {
		$settings['is_static_sw'] = $current_settings['is_static_sw'];
	}
	
	if ( ! isset( $settings['is_static_manifest'] ) ) {
		$settings['is_static_manifest'] = $current_settings['is_static_manifest'];
	}
	
	return $settings;
}
			
/**
 * Get settings from database
 *
 * @return (Array) A merged array of default and settings saved in database. 
 *
 * @author Arun Basil Lal
 * 
 * @since 1.0
 * @since 2.0 Added display
 * @since 2.0.1 Added is_static_manifest. 1 for static files, 0 for dynamic files.
 * @since 2.0.1 Added is_static_sw. 1 for static files, 0 for dynamic files.
 */
function superpwa_get_settings() {

	$defaults = array(
				'app_name'			=> get_bloginfo( 'name' ),
				'app_short_name'	=> substr( get_bloginfo( 'name' ), 0, 15 ),
				'description'		=> get_bloginfo( 'description' ),
				'icon'				=> SUPERPWA_PATH_SRC . 'public/images/logo.png',
				'splash_icon'		=> SUPERPWA_PATH_SRC . 'public/images/logo-512x512.png',
				'background_color' 	=> '#D5E0EB',
				'theme_color' 		=> '#D5E0EB',
				'start_url' 		=> 0,
				'start_url_amp'		=> 0,
				'offline_page' 		=> 0,
				'orientation'		=> 1,
				'display'			=> 1,
				'is_static_manifest'=> 0,
				'is_static_sw'		=> 0,
				'disable_add_to_home'=> 0,
			);

	$settings = get_option( 'superpwa_settings', $defaults );
	
	return $settings;
}

/**
 * Todo list after saving admin options
 *
 * Regenerate manifest
 * Regenerate service worker
 * 
 * @author Arun Basil Lal
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

/**
 * Enqueue CSS and JS
 *
 * @since	1.0
 */
function superpwa_enqueue_css_js( $hook ) {
	
    // Load only on SuperPWA plugin pages
	if ( strpos( $hook, 'superpwa' ) === false ) {
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

/**
 * Admin footer text
 *
 * A function to add footer text to the settings page of the plugin.
 * @since	1.2
 * @refer	https://codex.wordpress.org/Function_Reference/get_current_screen
 */
function superpwa_footer_text( $default ) {
    
	// Retun default on non-plugin pages
	$screen = get_current_screen();
	if ( strpos( $screen->id, 'superpwa' ) === false ) {
		return $default;
	}
	
    $superpwa_footer_text = sprintf( __( 'If you like SuperPWA, please <a href="%s" target="_blank">make a donation</a> or leave a <a href="%s" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating to support continued development. Thanks a bunch!', 'super-progressive-web-apps' ), 
	'https://millionclues.com/donate/',
	'https://wordpress.org/support/plugin/super-progressive-web-apps/reviews/?rate=5#new-post'
	);
	
	return $superpwa_footer_text;
}
add_filter( 'admin_footer_text', 'superpwa_footer_text' );

/**
 * Admin footer version
 *
 * @since	1.0
 */
function superpwa_footer_version( $default ) {
	
	// Retun default on non-plugin pages
	$screen = get_current_screen();
	if ( strpos( $screen->id, 'superpwa' ) === false ) {
		return $default;
	}
	
	return 'SuperPWA ' . SUPERPWA_VERSION;
}
add_filter( 'update_footer', 'superpwa_footer_version', 11 );