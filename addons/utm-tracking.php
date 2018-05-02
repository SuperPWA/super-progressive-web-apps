<?php
/**
 * UTM Tracking
 *
 * @since 1.7
 * 
 * @function	superpwa_utm_tracking_sub_menu()			Add sub-menu page for UTM Tracking
 * @function 	superpwa_utm_tracking_register_settings()	Register UTM Tracking settings
 * @function 	superpwa_utm_tracking_get_settings()		Get UTM Tracking settings
 * @function 	superpwa_utm_tracking_section_cb()			Callback function for UTM Tracking section
 * @function	superpwa_utm_tracking_interface_render()	UTM Tracking UI renderer
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Add sub-menu page for UTM Tracking
 * 
 * @since 1.7
 */
function superpwa_utm_tracking_sub_menu() {
	
	// UTM Tracking sub-menu
	add_submenu_page( 'superpwa', __( 'Super Progressive Web Apps', 'super-progressive-web-apps' ), __( 'UTM Tracking', 'super-progressive-web-apps' ), 'manage_options', 'superpwa-utm-tracking', 'superpwa_utm_tracking_interface_render' );
}
add_action( 'admin_menu', 'superpwa_utm_tracking_sub_menu' );

/**
 * Register UTM Tracking settings
 *
 * @since 	1.7
 */
function superpwa_utm_tracking_register_settings() {

	// Register Setting
	register_setting( 
		'superpwa_utm_tracking_settings_group', // Group name
		'superpwa_utm_tracking_settings' 		// Setting name = html form <input> name on settings form
		// 'superpwa_utm_tracking_validater'	// Input validator and sanitizer
	);
		
	// UTM Tracking
    add_settings_section(
        'superpwa_utm_tracking_section',				// ID
        __return_false(),								// Title
        'superpwa_utm_tracking_section_cb',				// Callback Function
        'superpwa_utm_tracking_section'					// Page slug
    );
	
		// Current Start URL
		add_settings_field(
			'superpwa_manifest_status',								// ID
			__('Campaign Source', 'super-progressive-web-apps'),			// Title
			'something',							// CB
			'superpwa_utm_tracking_section',						// Page slug
			'superpwa_utm_tracking_section'							// Settings Section ID
		);
		
		// Campaign Source
		add_settings_field(
			'superpwa_manifest_status',								// ID
			__('Campaign Source', 'super-progressive-web-apps'),			// Title
			'something',							// CB
			'superpwa_utm_tracking_section',						// Page slug
			'superpwa_utm_tracking_section'							// Settings Section ID
		);
		
		// Campaign Medium
		add_settings_field(
			'superpwa_sw_status',									// ID
			__('Campaign Medium', 'super-progressive-web-apps'),		// Title
			'something',								// CB
			'superpwa_utm_tracking_section',						// Page slug
			'superpwa_utm_tracking_section'							// Settings Section ID
		);	
		
		// Campaign Name
		add_settings_field(
			'superpwa_https_status',								// ID
			__('Campaign Name', 'super-progressive-web-apps'),				// Title
			'something',								// CB
			'superpwa_utm_tracking_section',						// Page slug
			'superpwa_utm_tracking_section'							// Settings Section ID
		);
		
		// Campaign Term
		add_settings_field(
			'superpwa_https_status',								// ID
			__('Campaign Term', 'super-progressive-web-apps'),				// Title
			'something',								// CB
			'superpwa_utm_tracking_section',						// Page slug
			'superpwa_utm_tracking_section'							// Settings Section ID
		);	
		
		// Campaign Content
		add_settings_field(
			'superpwa_https_status',								// ID
			__('Campaign Content', 'super-progressive-web-apps'),				// Title
			'something',								// CB
			'superpwa_utm_tracking_section',						// Page slug
			'superpwa_utm_tracking_section'							// Settings Section ID
		);	
}
add_action( 'admin_init', 'superpwa_utm_tracking_register_settings' );

/**
 * Get UTM Tracking settings
 *
 * @since 1.7
 */
function superpwa_utm_tracking_get_settings() {
	
	$defaults = array(
				'utm_source'		=> 'superpwa',
			);
	
	return get_option( 'superpwa_utm_tracking_settings', $defaults );
}

/**
 * Callback function for UTM Tracking section
 *
 * @since 1.7
 */
function superpwa_utm_tracking_section_cb() {
	echo '<p>' . __( 'A long description for the settings section goes here.', 'starter-plugin' ) . '</p>';
}

/**
 * Application Name
 *
 * @since 1.7
 */
function something() {

	// Get Settings
	$settings = superpwa_get_settings(); ?>
	
	<fieldset>
		
		<input type="text" name="superpwa_settings[app_name]" class="regular-text" value="<?php if ( isset( $settings['app_name'] ) && ( ! empty($settings['app_name']) ) ) echo esc_attr($settings['app_name']); ?>"/>
		
	</fieldset>
	
	<p class="description" id="tagline-description">
		<?php _e('This will be the icon of your app when installed on the phone. Must be a <code>PNG</code> image exactly <code>192x192</code> in size.', 'super-progressive-web-apps'); ?>
	</p>

	<?php
}

/**
 * UTM Tracking UI renderer
 *
 * @since 1.7
 */ 
function superpwa_utm_tracking_interface_render() {
	
	// Authentication
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	
	// Handing save settings
	if ( isset( $_GET['settings-updated'] ) ) {
		
		// Add settings saved message with the class of "updated"
		add_settings_error( 'superpwa_settings_group', 'superpwa_utm_tracking_settings_saved_message', __( 'Settings saved.', 'super-progressive-web-apps' ), 'updated' );
		
		// Show Settings Saved Message
		settings_errors( 'superpwa_settings_group' );
	}
	
	?>
	
	<div class="wrap">	
		<h1>Super Progressive Web Apps <sup><?php echo SUPERPWA_VERSION; ?></sup></h1>
		
		<form action="options.php" method="post" enctype="multipart/form-data">		
			<?php
			// Output nonce, action, and option_page fields for a settings page.
			settings_fields( 'superpwa_utm_tracking_settings_group' );
			
			// Status
			do_settings_sections( 'superpwa_utm_tracking_section' );	// Page slug
			
			// Output save settings button
			submit_button( __('Save Settings', 'super-progressive-web-apps') );
			?>
		</form>
	</div>
	<?php
}