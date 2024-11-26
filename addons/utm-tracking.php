<?php
/**
 * UTM Tracking
 *
 * @since 1.7
 * 
 * @function	superpwa_utm_tracking_sub_menu()			Add sub-menu page for UTM Tracking
 * @function 	superpwa_utm_tracking_get_settings()		Get UTM Tracking settings
 * @function	superpwa_utm_tracking_for_start_url()		Add UTM Tracking to the start_url
 * @function 	superpwa_utm_tracking_save_settings_todo()	Todo list after saving UTM Tracking settings
 * @function 	superpwa_utm_tracking_deactivate_todo()		Deactivation Todo
 * @function 	superpwa_utm_tracking_register_settings()	Register UTM Tracking settings
 * @function	superpwa_utm_tracking_validater_sanitizer()	Validate and sanitize user input
 * @function 	superpwa_utm_tracking_section_cb()			Callback function for UTM Tracking section
 * @function 	superpwa_utm_tracking_start_url_cb()		Current Start URL
 * @function 	superpwa_utm_tracking_source_cb()			Campaign Source
 * @function 	superpwa_utm_tracking_medium_cb()			Campaign Medium
 * @function 	superpwa_utm_tracking_name_cb()				Campaign Name
 * @function 	superpwa_utm_tracking_term_cb()				Campaign Term
 * @function 	superpwa_utm_tracking_content_cb()			Campaign Content
 * @function	superpwa_utm_tracking_interface_render()	UTM Tracking UI renderer
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Get UTM Tracking settings
 *
 * @since 1.7
 */
function superpwa_utm_tracking_get_settings() {
	
	$defaults = array(
				'utm_source'		=> 'superpwa',
				'utm_medium'		=> 'superpwa',
				'utm_campaign'		=> 'superpwa',
			);
	
	return get_option( 'superpwa_utm_tracking_settings', $defaults );
}

/**
 * Add UTM Tracking to the start_url
 * 
 * Hooks onto the superpwa_manifest_start_url filter to add the 
 * UTM tracking parameters to the start_url
 *
 * Example: https://superpwa.com/?utm_source=superpwa&utm_medium=medium&utm_campaign=name&utm_term=terms&utm_content=content
 * 
 * @param $start_url (string) the start_url for manifest from superpwa_get_start_url()
 * @return (string) Filtered start_url with UTM tracking added
 * 
 * @since 1.7
 */
function superpwa_utm_tracking_for_start_url( $start_url ) {
	
	// Get UTM Tracking settings
	$utm_params = superpwa_utm_tracking_get_settings();
	
	// Add the initial '/?'
	$start_url = trailingslashit( $start_url ) . '?';
	
	// Build the URL
	foreach ( $utm_params as $param => $value ) {
		
		if ( ! empty( $value ) ) {
			$start_url = $start_url . $param . '=' . rawurlencode( $value ) . '&';
		}
	}
	
	// Remove trailing '&'
	return rtrim( $start_url, '&' );
}
add_filter( 'superpwa_manifest_start_url', 'superpwa_utm_tracking_for_start_url' );

/**
 * Todo list after saving UTM Tracking settings
 *
 * Regenerate manifest when settings are saved. 
 * Also used when add-on is activated and deactivated.
 *
 * @since	1.7
 */
function superpwa_utm_tracking_save_settings_todo() {
	
	// Regenerate manifest
	superpwa_generate_manifest();
}
add_action( 'add_option_superpwa_utm_tracking_settings', 'superpwa_utm_tracking_save_settings_todo' );
add_action( 'update_option_superpwa_utm_tracking_settings', 'superpwa_utm_tracking_save_settings_todo' );
add_action( 'superpwa_addon_activated_utm_tracking', 'superpwa_utm_tracking_save_settings_todo' );

/**
 * Deactivation Todo
 * 
 * Unhook the filter and regenerate manifest
 * 
 * @since 1.7
 */
function superpwa_utm_tracking_deactivate_todo() {
	
	// Unhook the UTM tracking params filter
	remove_filter( 'superpwa_manifest_start_url', 'superpwa_utm_tracking_for_start_url' );
	
	// Regenerate manifest
	superpwa_generate_manifest();
}
add_action( 'superpwa_addon_deactivated_utm_tracking', 'superpwa_utm_tracking_deactivate_todo' );

/**
 * Register UTM Tracking settings
 *
 * @since 	1.7
 */
function superpwa_utm_tracking_register_settings() {

	// Register Setting
	register_setting( 
		'superpwa_utm_tracking_settings_group',		 // Group name
		'superpwa_utm_tracking_settings', 			// Setting name = html form <input> name on settings form
		'superpwa_utm_tracking_validater_sanitizer'	// Input validator and sanitizer
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
			'superpwa_utm_tracking_start_url',						// ID
			__('Current Start URL', 'super-progressive-web-apps'),	// Title
			'superpwa_utm_tracking_start_url_cb',					// CB
			'superpwa_utm_tracking_section',						// Page slug
			'superpwa_utm_tracking_section'							// Settings Section ID
		);
		
		// Campaign Source
		add_settings_field(
			'superpwa_utm_tracking_source',							// ID
			__('Campaign Source', 'super-progressive-web-apps'),	// Title
			'superpwa_utm_tracking_source_cb',						// CB
			'superpwa_utm_tracking_section',						// Page slug
			'superpwa_utm_tracking_section'							// Settings Section ID
		);
		
		// Campaign Medium
		add_settings_field(
			'superpwa_utm_tracking_medium',							// ID
			__('Campaign Medium', 'super-progressive-web-apps'),	// Title
			'superpwa_utm_tracking_medium_cb',						// CB
			'superpwa_utm_tracking_section',						// Page slug
			'superpwa_utm_tracking_section'							// Settings Section ID
		);	
		
		// Campaign Name
		add_settings_field(
			'superpwa_utm_tracking_name',							// ID
			__('Campaign Name', 'super-progressive-web-apps'),		// Title
			'superpwa_utm_tracking_name_cb',						// CB
			'superpwa_utm_tracking_section',						// Page slug
			'superpwa_utm_tracking_section'							// Settings Section ID
		);
		
		// Campaign Term
		add_settings_field(
			'superpwa_utm_tracking_term',							// ID
			__('Campaign Term', 'super-progressive-web-apps'),		// Title
			'superpwa_utm_tracking_term_cb',						// CB
			'superpwa_utm_tracking_section',						// Page slug
			'superpwa_utm_tracking_section'							// Settings Section ID
		);	
		
		// Campaign Content
		add_settings_field(
			'superpwa_utm_tracking_content',						// ID
			__('Campaign Content', 'super-progressive-web-apps'),	// Title
			'superpwa_utm_tracking_content_cb',						// CB
			'superpwa_utm_tracking_section',						// Page slug
			'superpwa_utm_tracking_section'							// Settings Section ID
		);	
}
add_action( 'admin_init', 'superpwa_utm_tracking_register_settings' );

/**
 * Validate and sanitize user input
 *
 * @since 1.7
 */
function superpwa_utm_tracking_validater_sanitizer( $settings ) {
	
	// Sanitize and validate campaign source. Campaign source cannot be empty.
	$settings['utm_source'] = sanitize_text_field( $settings['utm_source'] ) == '' ? 'superpwa' : sanitize_text_field( $settings['utm_source'] );
	
	// Sanitize campaign medium
	$settings['utm_medium'] = sanitize_text_field( $settings['utm_medium'] ) == '' ? 'superpwa' : sanitize_text_field( $settings['utm_medium'] );
	
	// Sanitize campaign name
	$settings['utm_campaign'] = sanitize_text_field( $settings['utm_campaign'] ) == '' ? 'superpwa' : sanitize_text_field( $settings['utm_campaign'] );
	
	// Sanitize campaign term
	$settings['utm_term'] = sanitize_text_field( $settings['utm_term'] );
	
	// Sanitize campaign medium
	$settings['utm_content'] = sanitize_text_field( $settings['utm_content'] );
	
	return $settings;
}

/**
 * Callback function for UTM Tracking section
 *
 * @since 1.7
 */
function superpwa_utm_tracking_section_cb() {
	
	// Get add-on info
	$addon_utm_tracking = superpwa_get_addons( 'utm_tracking' );
	$src = superpwa_manifest( 'src' );
	$utm_link = $addon_utm_tracking['link'] . '?utm_source=superpwa-plugin&utm_medium=utm-tracking-settings';
	echo'<p>' . esc_html__( "This add-on automatically adds UTM campaign parameters to the", "super-progressive-web-apps" ).'<code>'.esc_html__("Start Page", "super-progressive-web-apps" ).'</code>'.esc_html__("URL in your", "super-progressive-web-apps").'&nbsp;<a href="'.esc_url($src).'" target="_blank">' . esc_html__( "manifest", "super-progressive-web-apps").'</a>'.esc_html__( " This will help you identify visitors coming specifically from your app", "super-progressive-web-apps").'&nbsp;<a href="'.esc_url($utm_link).'" target="_blank">'.esc_html__("Read more", "super-progressive-web-apps").'</a> '.esc_html__("about UTM Tracking.", "super-progressive-web-apps").'</p>';
}

/**
 * Current Start URL
 *
 * @since 1.7
 */
function superpwa_utm_tracking_start_url_cb() {
	
	echo '<code style="word-break: break-all;">' . esc_url(superpwa_get_start_url( true )) . '</code>';
}

/**
 * Campaign Source
 *
 * @since 1.7
 */
function superpwa_utm_tracking_source_cb() {

	// Get Settings
	$settings = superpwa_utm_tracking_get_settings(); ?>
	
	<fieldset>
		
		<input type="text" name="superpwa_utm_tracking_settings[utm_source]" class="regular-text" value="<?php if ( isset( $settings['utm_source'] ) && ( ! empty($settings['utm_source']) ) ) echo esc_attr( $settings['utm_source'] ); ?>"/>
		
	</fieldset>
	
	<p class="description">
		<?php echo esc_html__( 'Campaign Source is mandatory and defaults to', 'super-progressive-web-apps').'<code>'.esc_html__('superpwa', 'super-progressive-web-apps' ).'</code>'; ?>
	</p>

	<?php
}

/**
 * Campaign Medium
 *
 * @since 1.7
 */
function superpwa_utm_tracking_medium_cb() {

	// Get Settings
	$settings = superpwa_utm_tracking_get_settings(); ?>
	
	<fieldset>
		
		<input type="text" name="superpwa_utm_tracking_settings[utm_medium]" class="regular-text" value="<?php if ( isset( $settings['utm_medium'] ) && ( ! empty($settings['utm_medium']) ) ) echo esc_attr( $settings['utm_medium'] ); ?>"/>
		
	</fieldset>
	
	<p class="description">
		<?php echo esc_html__( 'Campaign medium is mandatory and defaults to', 'super-progressive-web-apps').'<code>'.esc_html__('superpwa', 'super-progressive-web-apps' ).'</code>'; ?>
	</p>

	<?php
}

/**
 * Campaign Name
 *
 * @since 1.7
 */
function superpwa_utm_tracking_name_cb() {

	// Get Settings
	$settings = superpwa_utm_tracking_get_settings(); ?>
	
	<fieldset>
		
		<input type="text" name="superpwa_utm_tracking_settings[utm_campaign]" class="regular-text" value="<?php if ( isset( $settings['utm_campaign'] ) && ( ! empty($settings['utm_campaign']) ) ) echo esc_attr( $settings['utm_campaign'] ); ?>"/>
		
	</fieldset>
	
	<p class="description">
	<?php echo esc_html__( 'UTM Campaign is mandatory and defaults to', 'super-progressive-web-apps').'<code>'.esc_html__('superpwa', 'super-progressive-web-apps' ).'</code>'; ?>
	</p>

	<?php
}

/**
 * Campaign Term
 *
 * @since 1.7
 */
function superpwa_utm_tracking_term_cb() {

	// Get Settings
	$settings = superpwa_utm_tracking_get_settings(); ?>
	
	<fieldset>
		
		<input type="text" name="superpwa_utm_tracking_settings[utm_term]" placeholder="Optional" class="regular-text" value="<?php if ( isset( $settings['utm_term'] ) && ( ! empty($settings['utm_term']) ) ) echo esc_attr( $settings['utm_term'] ); ?>"/>
		
	</fieldset>

	<?php
}

/**
 * Campaign Content
 *
 * @since 1.7
 */
function superpwa_utm_tracking_content_cb() {

	// Get Settings
	$settings = superpwa_utm_tracking_get_settings(); ?>
	
	<fieldset>
		
		<input type="text" name="superpwa_utm_tracking_settings[utm_content]" placeholder="Optional" class="regular-text" value="<?php if ( isset( $settings['utm_content'] ) && ( ! empty($settings['utm_content']) ) ) echo esc_attr( $settings['utm_content'] ); ?>"/>
		
	</fieldset>

	<?php
}

/**
 * UTM Tracking UI renderer
 *
 * @since 1.7
 */ 
function superpwa_utm_tracking_interface_render() {
	
	// Authentication

	if ( ! current_user_can( superpwa_current_user_can() ) ) {
        return;
    }
	
	// Handing save settings
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Reason: We are not processing form information.
	if ( isset( $_GET['settings-updated'] ) ) {
		
		// Add settings saved message with the class of "updated"
		add_settings_error( 'superpwa_settings_group', 'superpwa_utm_tracking_settings_saved_message', __( 'Settings saved.', 'super-progressive-web-apps' ), 'updated' );
		
		// Show Settings Saved Message
		settings_errors( 'superpwa_settings_group' );
	}
	// Get add-on info
	$addon_utm_tracking = superpwa_get_addons( 'utm_tracking' );

	superpwa_setting_tabs_styles();
	?>
	
	<div class="wrap">	
		<h1><?php esc_html_e( 'UTM Tracking', 'super-progressive-web-apps' ); ?> <small>(<a href="<?php echo esc_url($addon_utm_tracking['link']) . '?utm_source=superpwa-plugin&utm_medium=utm-tracking-settings'?>"><?php echo esc_html__( 'Docs', 'super-progressive-web-apps' ); ?></a>)</small></h1>

		<?php superpwa_setting_tabs_html(); ?>

		<form action="<?php echo esc_url(admin_url("options.php")); ?>" method="post" enctype="multipart/form-data">		
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
	<?php superpwa_newsletter_form(); ?>
	<?php
}