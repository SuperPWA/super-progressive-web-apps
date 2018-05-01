<?php
/**
 * UTM Tracking
 *
 * @since 1.7
 * 
 * @function	superpwa_utm_tracking_sub_menu()			Add sub-menu page for UTM Tracking
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
	
	// Add-Ons page
	add_submenu_page( 'superpwa', __( 'Super Progressive Web Apps', 'super-progressive-web-apps' ), __( 'UTM Tracking', 'super-progressive-web-apps' ), 'manage_options', 'superpwa-utm-tracking', 'superpwa_utm_tracking_interface_render' );
}
add_action( 'admin_menu', 'superpwa_utm_tracking_sub_menu' );

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
			settings_fields( 'superpwa_settings_group' );
			
			// Status
			do_settings_sections( 'superpwa_pwa_status_section' );	// Page slug
			
			// Output save settings button
			submit_button( __('Save Settings', 'super-progressive-web-apps') );
			?>
		</form>
	</div>
	<?php
}