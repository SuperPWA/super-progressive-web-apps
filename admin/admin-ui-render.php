<?php
/**
 * Admin UI setup and render
 *
 * @since 1.0
 * @function	superpwa_manifest_cb()					Manifest Callback
 * @function	superpwa_manifest_status_cb				Manifest Status
 * @function	superpwa_splash_screen_cb()				Splash Screen Callback
 * @function	superpwa_background_color_cb()			Background Color
 * @function	superpwa_icons_cb()						Application Icons
 * @function	superpwa_offline_page_note_cb()			Offline Page Note
 * @function	superpwa_offline_page_cb()				Offline Page Dropdown
 * @function	superpwa_admin_interface_render()		Admin interface renderer
 */

// Exit if accessed directly
if ( ! defined('ABSPATH') ) exit;

/**
 * Manifest Callback
 *
 * @since	1.0
 */
function superpwa_manifest_cb() {
	
	echo '<p>' . __('The manifest includes all the information about your Progressive Web App. SuperPWA generates the manifest automatically.') . '</p>';
}

/**
 * Manifest Status
 *
 * @since 1.0
 */
function superpwa_manifest_status_cb() {

	if ( superpwa_get_contents( SUPERPWA_MANIFEST_ABS ) ) {
		
		printf( __( 'Manifest was generated successfully. You can <a href="%s" target="_blank">see it here &rarr;</a>.', 'super-progressive-web-apps' ), SUPERPWA_MANIFEST_SRC );
	} else {
		
		echo '<p>' . __('Manifest generation failed. Check if WordPress can write to your root folder (the same folder with wp-config.php).', 'super-progressive-web-apps') . '</p>';
	}
	
}

/**
 * Splash Screen Callback
 *
 * @since	1.0
 */
function superpwa_splash_screen_cb() {
	
	echo '<p>' . __('The values you set here will be used for the splash screen that supported browsers choose to display.', 'super-progressive-web-apps') . '</p>';
}

/**
 * Background Color
 *
 * @since 1.0
 */
function superpwa_background_color_cb() {

	// Get Settings
	$settings = superpwa_get_settings(); ?>
	
	<!-- Background Color -->
	<input type="text" name="superpwa_settings[background_color]" id="superpwa_settings[background_color]" class="superpwa-colorpicker" value="<?php echo isset( $settings['background_color'] ) ? esc_attr( $settings['background_color']) : '#D5E0EB'; ?>" data-default-color="#D5E0EB">

	<?php
}

/**
 * Application Icons
 *
 * @since 1.0
 */
function superpwa_icons_cb() {

	// Get Settings
	$settings = superpwa_get_settings(); ?>
	
	<!-- Application Icon -->
	<p style="margin-bottom: 8px;"><?php _e('This will be the icon of your PWA when installed on the phone. Must be a <code>PNG</code> image exactly <code>192x192</code> in size.', 'super-progressive-web-apps'); ?></p>
	<input type="text" name="superpwa_settings[icon]" id="superpwa_settings[icon]" class="superpwa-icon" size="50" value="<?php echo isset( $settings['icon'] ) ? esc_attr( $settings['icon']) : ''; ?>">
	<button type="button" class="button superpwa-icon-upload" data-editor="content">
		<span class="dashicons dashicons-format-image" style="margin-top: 4px;"></span> Choose Icon
	</button>

	<?php
}

/**
 * Offline Page Note
 *
 * @since	1.0
 */
function superpwa_offline_page_note_cb() {
	
	// Get Settings
	$settings = superpwa_get_settings();
	
	printf( '<p>' . __( 'The page you set here will be displayed if the requested page is not cached and the user is offline. Current offline page is <code>%s</code>', 'super-progressive-web-apps' ) . '</p>', get_permalink($settings['offline_page']) ? trailingslashit(get_permalink($settings['offline_page'])) : trailingslashit(get_bloginfo( 'wpurl' )) );
}

/**
 * Offline Page Dropdown
 *
 * @since 1.0
 */
function superpwa_offline_page_cb() {

	// Get Settings
	$settings = superpwa_get_settings(); ?>
	
	<!-- WordPress Pages Dropdown -->
	<label for="superpwa_settings[offline_page]">
	<?php echo wp_dropdown_pages( array( 
			'name' => 'superpwa_settings[offline_page]', 
			'echo' => 0, 
			'show_option_none' => __( '&mdash; Default &mdash;' ), 
			'option_none_value' => '0', 
			'selected' =>  isset($settings['offline_page']) ? $settings['offline_page'] : '',
		)); ?>
	</label>

	<?php
}
 
/**
 * Admin interface renderer
 *
 * @since 1.0
 */ 
function superpwa_admin_interface_render () {
	
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	/**
	 * If settings are inside WP-Admin > Settings, then WordPress will automatically display Settings Saved. If not used this block
	 * @refer	https://core.trac.wordpress.org/ticket/31000
	 * If the user have submitted the settings, WordPress will add the "settings-updated" $_GET parameter to the url
	 *
	if ( isset( $_GET['settings-updated'] ) ) {
		// Add settings saved message with the class of "updated"
		add_settings_error( 'superpwa_settings_saved_message', 'superpwa_settings_saved_message', __( 'Settings are Saved', 'super-progressive-web-apps' ), 'updated' );
	}
 
	// Show Settings Saved Message
	settings_errors( 'superpwa_settings_saved_message' ); */?> 
	
	<div class="wrap">	
		<h1>Super Progressive Web Apps <sup><?php echo SUPERPWA_VERSION; ?></sup></h1>
		
		<form action="options.php" method="post" enctype="multipart/form-data">		
			<?php
			// Output nonce, action, and option_page fields for a settings page.
			settings_fields( 'superpwa_settings_group' );
			
			// Manifest
			do_settings_sections( 'superpwa_manifest_section' );	// Page slug
			
			// Splash Screen
			do_settings_sections( 'superpwa_splash_screen_section' );	// Page slug
			
			// Offline Page
			do_settings_sections( 'superpwa_offline_page_section' );	// Page slug
			
			// Output save settings button
			submit_button( __('Save Settings', 'super-progressive-web-apps') );
			?>
		</form>
	</div>
	<?php
}