<?php

/**
 * Pull To Refresh
 *
 * @since 1.7
 * 
 * @function	superpwa_pull_to_refresh_sub_menu()			Add sub-menu page for Pull To Refresh
 * @function 	superpwa_pull_to_refresh_get_settings()		Get Pull To Refresh settings
 * @function	superpwa_pull_to_refresh_for_start_url()		Add Pull To Refresh to the start_url
 * @function 	superpwa_pull_to_refresh_save_settings_todo()	Todo list after saving Pull To Refresh settings
 * @function 	superpwa_pull_to_refresh_deactivate_todo()		Deactivation Todo
 * @function 	superpwa_pull_to_refresh_register_settings()	Register Pull To Refresh settings
 * @function	superpwa_pull_to_refresh_validater_sanitizer()	Validate and sanitize user input
 * @function 	superpwa_pull_to_refresh_section_cb()			Callback function for Pull To Refresh section
 * @function 	superpwa_pull_to_refresh_start_url_cb()		Current Start URL
 * @function 	superpwa_pull_to_refresh_enable_cb()			Campaign Source
 * @function 	superpwa_pull_to_refresh_pull_message_text_cb()			Pull message
 * @function 	superpwa_pull_to_refresh_pull_release_text_cb()				Release message
 * @function 	superpwa_pull_to_refresh_refreshing_text_cb()				Refreshing message
 * @function 	superpwa_pull_to_refresh_font_size_cb()			Font size
 * @function	superpwa_pull_to_refresh_interface_render()	Pull To Refresh UI renderer
 */

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * Get Pull To Refresh settings
 *
 * @since 1.7
 */
function superpwa_pull_to_refresh_get_settings()
{

	$defaults = array(
		'superpwa_pull_to_refresh_switch'    	=> '1',
		'superpwa_ptr_text'		            	=> 'Pull down to refresh',
		'superpwa_ptr_release_text'		        => 'Release to refresh',
		'superpwa_ptr_refreshing_text'		    => 'Refreshing',
		'superpwa_ptr_font_size'		        => '0.85em',
		'superpwa_ptr_font_color'		    	=> 'rgba(0, 0, 0, 0.3)',
	);

	return get_option('superpwa_pull_to_refresh_settings', $defaults);
}


/**
 * Todo list after saving Pull To Refresh settings
 *
 * Regenerate manifest when settings are saved. 
 * Also used when add-on is activated and deactivated.
 *
 * @since	1.7
 */
	function superpwa_pull_to_refresh_save_settings_todo(){
	
		if ( superpwa_addons_status( 'pull_to_refresh' ) 	== 'active' ) {
			// Regenerate manifest
			superpwa_generate_manifest();
		}
		
	}

	add_action('add_option_superpwa_pull_to_refresh_settings', 'superpwa_pull_to_refresh_save_settings_todo');
	add_action('update_option_superpwa_pull_to_refresh_settings', 'superpwa_pull_to_refresh_save_settings_todo');
	add_action('superpwa_addon_activated_pull_to_refresh', 'superpwa_pull_to_refresh_save_settings_todo');

/**
 * Register Pull To Refresh settings
 *
 * @since 	1.7
 */
	function superpwa_pull_to_refresh_register_settings() {
	
		if ( superpwa_addons_status( 'pull_to_refresh' ) 	== 'active' ) {

			// Register Setting
			register_setting(
				'superpwa_pull_to_refresh_settings_group',		 // Group name
				'superpwa_pull_to_refresh_settings', 			// Setting name = html form <input> name on settings form
				'superpwa_pull_to_refresh_validater_sanitizer'	// Input validator and sanitizer
			);

			// Pull To Refresh
			add_settings_section(
				'superpwa_pull_to_refresh_section',				// ID
				__return_false(),								// Title
				'',				// Callback Function
				'superpwa_pull_to_refresh_section'					// Page slug
			);


			// Pull To Refresh	
			add_settings_field(
				'superpwa_pull_to_refresh_source',							// ID
				__('Pull To Refresh	', 'super-progressive-web-apps'),	// Title
				'superpwa_pull_to_refresh_enable_cb',						// CB
				'superpwa_pull_to_refresh_section',						// Page slug
				'superpwa_pull_to_refresh_section'							// Settings Section ID
			);

			// Pull message
			add_settings_field(
				'superpwa_pull_to_refresh_pull_message',							// ID
				__('Pull message', 'super-progressive-web-apps'),	// Title
				'superpwa_pull_to_refresh_pull_message_text_cb',						// CB
				'superpwa_pull_to_refresh_section',						// Page slug
				'superpwa_pull_to_refresh_section'							// Settings Section ID
			);

			// Release message
			add_settings_field(
				'superpwa_pull_to_refresh_release_message',							// ID
				__('Release message', 'super-progressive-web-apps'),		// Title
				'superpwa_pull_to_refresh_pull_release_text_cb',						// CB
				'superpwa_pull_to_refresh_section',						// Page slug
				'superpwa_pull_to_refresh_section'							// Settings Section ID
			);

			// Refreshing message
			add_settings_field(
				'superpwa_pull_to_refresh_refreshing',							// ID
				__('Refreshing message', 'super-progressive-web-apps'),		// Title
				'superpwa_pull_to_refresh_refreshing_text_cb',						// CB
				'superpwa_pull_to_refresh_section',						// Page slug
				'superpwa_pull_to_refresh_section'							// Settings Section ID
			);

			// Font size
			add_settings_field(
				'superpwa_pull_to_refresh_font_size',						// ID
				__('Font size', 'super-progressive-web-apps'),	// Title
				'superpwa_pull_to_refresh_font_size_cb',						// CB
				'superpwa_pull_to_refresh_section',						// Page slug
				'superpwa_pull_to_refresh_section'							// Settings Section ID
			);

			// Font Color
			add_settings_field(
				'superpwa_pull_to_refresh_font_color',						// ID
				__('Font Color', 'super-progressive-web-apps'),	// Title
				'superpwa_pull_to_refresh_font_color_cb',						// CB
				'superpwa_pull_to_refresh_section',						// Page slug
				'superpwa_pull_to_refresh_section'							// Settings Section ID
			);

		}
		
	}

	add_action('admin_init', 'superpwa_pull_to_refresh_register_settings');

/**
 * Validate and sanitize user input
 *
 * @since 1.7
 */
	function superpwa_pull_to_refresh_validater_sanitizer($settings)
	{

		// Sanitize and validate campaign source. Campaign source cannot be empty.
		$settings['superpwa_pull_to_refresh_switch'] = sanitize_text_field($settings['superpwa_pull_to_refresh_switch']) == '' ? 'Pull down to refresh' : sanitize_text_field($settings['superpwa_pull_to_refresh_switch']);

		// Sanitize Pull message
		$settings['superpwa_ptr_text'] = sanitize_text_field($settings['superpwa_ptr_text']) == '' ? '' : sanitize_text_field($settings['superpwa_ptr_text']);

		// Sanitize Release message
		$settings['superpwa_ptr_release_text'] = sanitize_text_field($settings['superpwa_ptr_release_text']) == '' ? '' : sanitize_text_field($settings['superpwa_ptr_release_text']);

		// Sanitize Refreshing message
		$settings['superpwa_ptr_refreshing_text'] = sanitize_text_field($settings['superpwa_ptr_refreshing_text']);

		// Sanitize Font Size
		$settings['superpwa_ptr_font_size'] = sanitize_text_field($settings['superpwa_ptr_font_size']);

		// Sanitize Font Color
		$settings['superpwa_ptr_font_color'] = sanitize_text_field($settings['superpwa_ptr_font_color']);

		return $settings;
	}


/**
 * Campaign Source
 *
 * @since 1.7
 */
function superpwa_pull_to_refresh_enable_cb()
{
	// Get Settings
	$settings = superpwa_pull_to_refresh_get_settings(); ?>
	<fieldset>
		<input type="checkbox" name="superpwa_pull_to_refresh_settings[superpwa_pull_to_refresh_switch]" class="regular-text" value="1" <?php if (isset($settings['superpwa_pull_to_refresh_switch'])) { checked('1', $settings['superpwa_pull_to_refresh_switch']); } ?> />
	</fieldset>
<?php
}

/**
 * Pull message
 *
 * @since 1.7
 */
function superpwa_pull_to_refresh_pull_message_text_cb()
{
	// Get Settings
	$settings = superpwa_pull_to_refresh_get_settings(); ?>
	<fieldset>
		<input type="text" name="superpwa_pull_to_refresh_settings[superpwa_ptr_text]" class="regular-text" value="<?php if (isset($settings['superpwa_ptr_text']) && (!empty($settings['superpwa_ptr_text']))) echo esc_attr($settings['superpwa_ptr_text']); ?>" />
	</fieldset>
<?php
}

/**
 * Release message
 *
 * @since 1.7
 */
function superpwa_pull_to_refresh_pull_release_text_cb()
{
	// Get Settings
	$settings = superpwa_pull_to_refresh_get_settings(); ?>
	<fieldset>
		<input type="text" name="superpwa_pull_to_refresh_settings[superpwa_ptr_release_text]" class="regular-text" value="<?php if (isset($settings['superpwa_ptr_release_text']) && (!empty($settings['superpwa_ptr_release_text']))) echo esc_attr($settings['superpwa_ptr_release_text']); ?>" />
	</fieldset>
<?php
}

/**
 * Refreshing message
 *
 * @since 1.7
 */
function superpwa_pull_to_refresh_refreshing_text_cb()
{
	// Get Settings
	$settings = superpwa_pull_to_refresh_get_settings(); ?>
	<fieldset>
		<input type="text" name="superpwa_pull_to_refresh_settings[superpwa_ptr_refreshing_text]" class="regular-text" value="<?php if (isset($settings['superpwa_ptr_refreshing_text']) && (!empty($settings['superpwa_ptr_refreshing_text']))) echo esc_attr($settings['superpwa_ptr_refreshing_text']); ?>" />
	</fieldset>
<?php
}

/**
 * Font size
 *
 * @since 1.7
 */
function superpwa_pull_to_refresh_font_size_cb()
{
	// Get Settings
	$settings = superpwa_pull_to_refresh_get_settings(); ?>
	<fieldset>
		<input type="text" name="superpwa_pull_to_refresh_settings[superpwa_ptr_font_size]" class="regular-text" value="<?php if (isset($settings['superpwa_ptr_font_size']) && (!empty($settings['superpwa_ptr_font_size']))) echo esc_attr($settings['superpwa_ptr_font_size']); ?>" />
	</fieldset>
<?php
}

/**
 * Font color
 *
 * @since 1.7
 */

function superpwa_pull_to_refresh_font_color_cb()
{
	$settings = superpwa_pull_to_refresh_get_settings();
?>
	<fieldset>
		<input type="text" name="superpwa_pull_to_refresh_settings[superpwa_ptr_font_color]" class="superpwa-colorpicker" value="<?php if (isset($settings['superpwa_ptr_font_color']) && (!empty($settings['superpwa_ptr_font_color']))) echo esc_attr($settings['superpwa_ptr_font_color']); ?>" />
	</fieldset>
<?php
}


/**
 * Pull To Refresh UI renderer
 *
 * @since 1.7
 */
function superpwa_pull_to_refresh_interface_render()
{

	if ( superpwa_addons_status( 'pull_to_refresh' ) 	== 'active' ) {

		// Authentication
	if (!current_user_can('manage_options')) {
		return;
	}

	// Handing save settings
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Reason: We are not processing form information.
	if (isset($_GET['settings-updated'])) {
		// Add settings saved message with the class of "updated"
		add_settings_error('superpwa_settings_group', 'superpwa_pull_to_refresh_settings_saved_message', __('Settings saved.', 'super-progressive-web-apps'), 'updated');

		// Show Settings Saved Message
		settings_errors('superpwa_settings_group');
	}
	// Get add-on info
	$addon_pull_to_refresh = superpwa_get_addons('pull_to_refresh');
	superpwa_setting_tabs_styles();
?>

	<div class="wrap">
		<h1><?php esc_html_e('Pull To Refresh', 'super-progressive-web-apps'); ?>			
		</h1>

		<?php superpwa_setting_tabs_html(); ?>

		<form action="<?php echo esc_url(admin_url("options.php")); ?>" method="post" enctype="multipart/form-data">
			<?php
			// Output nonce, action, and option_page fields for a settings page.
			settings_fields('superpwa_pull_to_refresh_settings_group');

			// Status
			do_settings_sections('superpwa_pull_to_refresh_section');	// Page slug

			// Output save settings button
			submit_button(__('Save Settings', 'super-progressive-web-apps'));
			?>
		</form>
	</div>
	<?php	

	}	
}



function superpwa_pull_to_refresh_ptrfp_scripts_load() {

	if ( superpwa_addons_status( 'pull_to_refresh' ) 	== 'active' ) {

		if(function_exists('superpwa_pull_to_refresh_get_settings')){

			$settings = superpwa_pull_to_refresh_get_settings();

		}else{ 

			$settings = array(); 

		}

		if( isset($settings['superpwa_pull_to_refresh_switch'])  && $settings['superpwa_pull_to_refresh_switch'] ==1 ){

			wp_register_script( "superpwa_ptrfp_lib_script", SUPERPWA_PATH_SRC."admin/js/superpwa-ptr-lib.min.js", array('jquery'), SUPERPWA_VERSION, true );
			$ptrArray = array(
						'instrPullToRefresh'=> ( isset( $settings['superpwa_ptr_text'] )? $settings['superpwa_ptr_text'] : esc_html__("Pull down to refresh", 'pull-to-refresh-for-pwa') ),
						'instrReleaseToRefresh'=> (isset( $settings['superpwa_ptr_release_text'] )? $settings['superpwa_ptr_release_text'] : esc_html__("Release to refresh", 'pull-to-refresh-for-pwa') ),
						'instrRefreshing'=>( isset( $settings['superpwa_ptr_refreshing_text'] )? $settings['superpwa_ptr_refreshing_text'] : esc_html__("Refreshing", 'pull-to-refresh-for-pwa') ),
						'instrptr_font_size'=>( isset( $settings['superpwa_ptr_font_size'] ) && !empty($settings['superpwa_ptr_font_size'])? $settings['superpwa_ptr_font_size'] : "0.85em" ),
						'instrptr_font_color'=>( isset( $settings['superpwa_ptr_font_color'] ) && !empty($settings['superpwa_ptr_font_color'])? $settings['superpwa_ptr_font_color'] : "rgba(0, 0, 0, 0.3)" ),
							);
			wp_localize_script("superpwa_ptrfp_lib_script", 'superpwa_ptr_obj', $ptrArray);
			wp_enqueue_script( "superpwa_ptrfp_lib_script");

		}

	}
	
}

add_action("wp_enqueue_scripts", 'superpwa_pull_to_refresh_ptrfp_scripts_load');