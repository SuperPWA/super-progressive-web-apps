<?php
/**
 * Admin UI setup and render
 *
 * @since 1.0
 * @function	superpwa_background_color_callback()	Callback function for General Settings field
 * @function	superpwa_admin_interface_render()		Admin interface renderer
 */

// Exit if accessed directly
if ( ! defined('ABSPATH') ) exit;

/**
 * Background Color
 *
 * @since 1.0
 */
function superpwa_background_color_callback() {

	// Get Settings
	$settings = superpwa_get_settings(); ?>
	
	<!-- Background Color -->
	<label for="superpwa_settings[background_color]"><?php esc_html_e('Background Color', 'superpwa_td') ?>
		<input type="text" name="superpwa_settings[background_color]" id="superpwa_settings[background_color]" class="superpwa-colorpicker" value="<?php echo isset( $settings['background_color'] ) ? esc_attr( $settings['background_color']) : '#D5E0EB'; ?>" data-default-color="#D5E0EB">
	</label><br>

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
		add_settings_error( 'superpwa_settings_saved_message', 'superpwa_settings_saved_message', __( 'Settings are Saved', 'superpwa_td' ), 'updated' );
	}
 
	// Show Settings Saved Message
	settings_errors( 'superpwa_settings_saved_message' ); */?> 
	
	<div class="wrap">	
		<h1>Super Progressive Web Apps</h1>
		
		<form action="options.php" method="post" enctype="multipart/form-data">		
			<?php
			// Output nonce, action, and option_page fields for a settings page.
			settings_fields( 'superpwa_settings_group' );
			
			echo '<h2>' . __('Splash Screen Settings', 'superpwa_td') . '</h2>';
			echo '<p>' . __('The values you set here will be used for the splash screen that some browsers choose to display.', 'superpwa_td') . '</p>';
			
			// Prints out all settings sections added to a particular settings page. 
			do_settings_sections( 'superpwa_basic_settings_section' );	// Page slug
			
			// Output save settings button
			submit_button( __('Save Settings', 'superpwa_td') );
			?>
		</form>
	</div>
	<?php
}