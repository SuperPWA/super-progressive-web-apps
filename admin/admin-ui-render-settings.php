<?php
/**
 * Admin UI setup and render
 *
 * @since 1.0
 * 
 * @function	superpwa_app_name_cb()					Application Name
 * @function	superpwa_app_short_name_cb()			Application Short Name
 * @function	superpwa_description_cb()				Description
 * @function	superpwa_background_color_cb()			Splash Screen Background Color
 * @function	superpwa_theme_color_cb()				Theme Color
 * @function	superpwa_app_icon_cb()					Application Icon
 * @function	superpwa_app_icon_cb()					Splash Screen Icon
 * @function	superpwa_start_url_cb()					Start URL Dropdown
 * @function	superpwa_offline_page_cb()				Offline Page Dropdown
 * @function	superpwa_orientation_cb()				Default Orientation Dropdown
 * @function	superpwa_display_cb()					Default Display Dropdown
 * @function	superpwa_manifest_status_cb()			Manifest Status
 * @function	superpwa_sw_status_cb()					Service Worker Status
 * @function	superpwa_https_status_cb()				HTTPS Status
 * @function	superpwa_disable_add_to_home_cb()		Disable Add to home
 * @function	superpwa_admin_interface_render()		Admin interface renderer
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Application Name
 *
 * @since 1.2
 */
function superpwa_app_name_cb() {

	// Get Settings
	$settings = superpwa_get_settings(); ?>
	
	<fieldset>
		
		<input type="text" name="superpwa_settings[app_name]" class="regular-text" value="<?php if ( isset( $settings['app_name'] ) && ( ! empty($settings['app_name']) ) ) echo esc_attr($settings['app_name']); ?>"/>
		
	</fieldset>

	<?php
}

/**
 * Application Short Name
 *
 * @since 1.2
 */
function superpwa_app_short_name_cb() {

	// Get Settings
	$settings = superpwa_get_settings(); ?>
	
	<fieldset>
		
		<input type="text" name="superpwa_settings[app_short_name]" class="regular-text superpwa-app-short-name" value="<?php if ( isset( $settings['app_short_name'] ) && ( ! empty($settings['app_short_name']) ) ) echo esc_attr($settings['app_short_name']); ?>"/>
		
		<p class="description">
			<?php _e('Used when there is insufficient space to display the full name of the application. <span id="superpwa-app-short-name-limit"><code>15</code> characters or less.</span>', 'super-progressive-web-apps'); ?>
		</p>
		
	</fieldset>

	<?php
}

/**
 * Description
 *
 * @since 1.6
 */
function superpwa_description_cb() {

	// Get Settings
	$settings = superpwa_get_settings(); ?>
	
	<fieldset>
		
		<input type="text" name="superpwa_settings[description]" class="regular-text" value="<?php if ( isset( $settings['description'] ) && ( ! empty( $settings['description'] ) ) ) echo esc_attr( $settings['description'] ); ?>"/>
		
		<p class="description">
			<?php _e( 'A brief description of what your app is about.', 'super-progressive-web-apps' ); ?>
		</p>
		
	</fieldset>

	<?php
}

/**
 * Application Icon
 *
 * @since 1.0
 */
function superpwa_app_icon_cb() {

	// Get Settings
	$settings = superpwa_get_settings(); ?>
	
	<!-- Application Icon -->
	<input type="text" name="superpwa_settings[icon]" id="superpwa_settings[icon]" class="superpwa-icon regular-text" size="50" value="<?php echo isset( $settings['icon'] ) ? esc_attr( $settings['icon']) : ''; ?>">
	<button type="button" class="button superpwa-icon-upload" data-editor="content">
		<span class="dashicons dashicons-format-image" style="margin-top: 4px;"></span> <?php _e( 'Choose Icon', 'super-progressive-web-apps' ); ?>
	</button>
	
	<p class="description">
		<?php _e('This will be the icon of your app when installed on the phone. Must be a <code>PNG</code> image exactly <code>192x192</code> in size.', 'super-progressive-web-apps'); ?>
	</p>

	<?php
}

/**
 * Splash Screen Icon
 *
 * @since 1.3
 */
function superpwa_splash_icon_cb() {

	// Get Settings
	$settings = superpwa_get_settings(); ?>
	
	<!-- Splash Screen Icon -->
	<input type="text" name="superpwa_settings[splash_icon]" id="superpwa_settings[splash_icon]" class="superpwa-splash-icon regular-text" size="50" value="<?php echo isset( $settings['splash_icon'] ) ? esc_attr( $settings['splash_icon']) : ''; ?>">
	<button type="button" class="button superpwa-splash-icon-upload" data-editor="content">
		<span class="dashicons dashicons-format-image" style="margin-top: 4px;"></span> <?php _e( 'Choose Icon', 'super-progressive-web-apps' ); ?>
	</button>
	
	<p class="description">
		<?php _e('This icon will be displayed on the splash screen of your app on supported devices. Must be a <code>PNG</code> image exactly <code>512x512</code> in size.', 'super-progressive-web-apps'); ?>
	</p>

	<?php
}

/**
 * Splash Screen Background Color
 *
 * @since 1.0
 */
function superpwa_background_color_cb() {

	// Get Settings
	$settings = superpwa_get_settings(); ?>
	
	<!-- Background Color -->
	<input type="text" name="superpwa_settings[background_color]" id="superpwa_settings[background_color]" class="superpwa-colorpicker" value="<?php echo isset( $settings['background_color'] ) ? esc_attr( $settings['background_color']) : '#D5E0EB'; ?>" data-default-color="#D5E0EB">
	
	<p class="description">
		<?php _e('Background color of the splash screen.', 'super-progressive-web-apps'); ?>
	</p>

	<?php
}

/**
 * Theme Color
 *
 * @since 1.4
 */
function superpwa_theme_color_cb() {

	// Get Settings
	$settings = superpwa_get_settings(); ?>
	
	<!-- Theme Color -->
	<input type="text" name="superpwa_settings[theme_color]" id="superpwa_settings[theme_color]" class="superpwa-colorpicker" value="<?php echo isset( $settings['theme_color'] ) ? esc_attr( $settings['theme_color']) : '#D5E0EB'; ?>" data-default-color="#D5E0EB">
	
	<p class="description">
		<?php _e('Theme color is used on supported devices to tint the UI elements of the browser and app switcher. When in doubt, use the same color as <code>Background Color</code>.', 'super-progressive-web-apps'); ?>
	</p>

	<?php
}

/**
 * Start URL Dropdown
 *
 * @since 1.2
 */
function superpwa_start_url_cb() {

	// Get Settings
	$settings = superpwa_get_settings(); ?>
	
	<fieldset>
	
		<!-- WordPress Pages Dropdown -->
		<label for="superpwa_settings[start_url]">
		<?php echo wp_dropdown_pages( array( 
				'name' => 'superpwa_settings[start_url]', 
				'echo' => 0, 
				'show_option_none' => __( '&mdash; Homepage &mdash;' ), 
				'option_none_value' => '0', 
				'selected' =>  isset($settings['start_url']) ? $settings['start_url'] : '',
			)); ?>
		</label>
		
		<p class="description">
			<?php printf( __( 'Specify the page to load when the application is launched from a device. Current start page is <code>%s</code>', 'super-progressive-web-apps' ), superpwa_get_start_url() ); ?>
		</p>
		
		<?php if ( superpwa_is_amp() ) { ?>
		
			<!--  AMP Page As Start Page -->
			<br><input type="checkbox" name="superpwa_settings[start_url_amp]" id="superpwa_settings[start_url_amp]" value="1" 
				<?php if ( isset( $settings['start_url_amp'] ) ) { checked( '1', $settings['start_url_amp'] ); } ?>>
				<label for="superpwa_settings[start_url_amp]"><?php _e('Use AMP version of the start page.', 'super-progressive-web-apps') ?></label>
				<br>
			
			<!-- AMP for WordPress 0.6.2 doesn't support homepage, the blog index, and archive pages. -->
			<?php if ( is_plugin_active( 'amp/amp.php' ) ) { ?>
				<p class="description">
					<?php _e( 'Do not check this if your start page is the homepage, the blog index, or the archives page. AMP for WordPress does not create AMP versions for these pages.', 'super-progressive-web-apps' ); ?>
				</p>
			<?php } ?>
			
			<!-- tagDiv AMP 1.2 doesn't enable AMP for pages by default and needs to be enabled manually in settings -->			
			<?php if ( is_plugin_active( 'td-amp/td-amp.php' ) && method_exists( 'td_util', 'get_option' ) ) { 
				
				// Read option value from db
				$td_amp_page_post_type = td_util::get_option( 'tds_amp_post_type_page' );

				// Show notice if option to enable AMP for pages is disabled.
				if ( empty( $td_amp_page_post_type ) ) { ?>
					<p class="description">
						<?php printf( __( 'Please enable AMP support for Page in <a href="%s">Theme Settings > Theme Panel</a> > AMP > Post Type Support.', 'super-progressive-web-apps' ), admin_url( 'admin.php?page=td_theme_panel' ) ); ?>
					</p>
				<?php }
			} ?>
				
		<?php } ?>
	
	</fieldset>

	<?php
}

/**
 * Offline Page Dropdown
 *
 * @since 1.1
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
	
	<p class="description">
		<?php printf( __( 'Offline page is displayed when the device is offline and the requested page is not already cached. Current offline page is <code>%s</code>', 'super-progressive-web-apps' ), superpwa_get_offline_page() ); ?>
	</p>

	<?php
}

/**
 * Default Orientation Dropdown
 *
 * @since 1.4
 */
function superpwa_orientation_cb() {

	// Get Settings
	$settings = superpwa_get_settings(); ?>
	
	<!-- Orientation Dropdown -->
	<label for="superpwa_settings[orientation]">
		<select name="superpwa_settings[orientation]" id="superpwa_settings[orientation]">
			<option value="0" <?php if ( isset( $settings['orientation'] ) ) { selected( $settings['orientation'], 0 ); } ?>>
				<?php _e( 'Follow Device Orientation', 'super-progressive-web-apps' ); ?>
			</option>
			<option value="1" <?php if ( isset( $settings['orientation'] ) ) { selected( $settings['orientation'], 1 ); } ?>>
				<?php _e( 'Portrait', 'super-progressive-web-apps' ); ?>
			</option>
			<option value="2" <?php if ( isset( $settings['orientation'] ) ) { selected( $settings['orientation'], 2 ); } ?>>
				<?php _e( 'Landscape', 'super-progressive-web-apps' ); ?>
			</option>
		</select>
	</label>
	
	<p class="description">
		<?php _e( 'Set the orientation of your app on devices. When set to <code>Follow Device Orientation</code> your app will rotate as the device is rotated.', 'super-progressive-web-apps' ); ?>
	</p>

	<?php
}

/**
 * Default Display Dropdown
 *
 * @author Jose Varghese
 * 
 * @since 2.0
 */
function superpwa_display_cb() {

	// Get Settings
	$settings = superpwa_get_settings(); ?>
	
	<!-- Display Dropdown -->
	<label for="superpwa_settings[display]">
		<select name="superpwa_settings[display]" id="superpwa_settings[display]">
			<option value="0" <?php if ( isset( $settings['display'] ) ) { selected( $settings['display'], 0 ); } ?>>
				<?php _e( 'Full Screen', 'super-progressive-web-apps' ); ?>
			</option>
			<option value="1" <?php if ( isset( $settings['display'] ) ) { selected( $settings['display'], 1 ); } ?>>
				<?php _e( 'Standalone', 'super-progressive-web-apps' ); ?>
			</option>
			<option value="2" <?php if ( isset( $settings['display'] ) ) { selected( $settings['display'], 2 ); } ?>>
				<?php _e( 'Minimal UI', 'super-progressive-web-apps' ); ?>
			</option>
			<option value="3" <?php if ( isset( $settings['display'] ) ) { selected( $settings['display'], 3 ); } ?>>
				<?php _e( 'Browser', 'super-progressive-web-apps' ); ?>
			</option>
		</select>
	</label>
	
	<p class="description">
		<?php printf( __( 'Display mode decides what browser UI is shown when your app is launched. <code>Standalone</code> is default. <a href="%s" target="_blank">What\'s the difference? &rarr;</a>', 'super-progressive-web-apps' ) . '</p>', 'https://superpwa.com/doc/web-app-manifest-display-modes/?utm_source=superpwa-plugin&utm_medium=settings-display' ); ?>
	</p>

	<?php
}

/**
 * Manifest Status
 *
 * @author Arun Basil Lal
 *
 * @since 1.2
 * @since 1.8 Attempt to generate manifest again if the manifest doesn't exist.
 * @since 2.0 Remove logic to check if manifest exists in favour of dynamic manifest.
 * @since 2.0.1 Added checks to see if dynamic file is valid. If not, generates a physical file. 
 */
function superpwa_manifest_status_cb() {
	
	/** 
	 * Check to see if the file exists, If not attempts to generate a new one.
	 */
	if ( superpwa_file_exists( superpwa_manifest( 'src' ) ) || superpwa_generate_manifest() ) {
		
		printf( '<p><span class="dashicons dashicons-yes" style="color: #46b450;"></span> ' . __( 'Manifest generated successfully. You can <a href="%s" target="_blank">See it here &rarr;</a>', 'super-progressive-web-apps' ) . '</p>', superpwa_manifest( 'src' ) );
	} else {
		
		printf( '<p><span class="dashicons dashicons-no-alt" style="color: #dc3232;"></span> ' . __( 'Manifest generation failed. <a href="%s" target="_blank">Fix it &rarr;</a>', 'super-progressive-web-apps' ) . '</p>', 'https://superpwa.com/doc/fixing-manifest-service-worker-generation-failed-error/?utm_source=superpwa-plugin&utm_medium=settings-status-no-manifest' );
	}
}

/**
 * Service Worker Status
 *
 * @author Arun Basil Lal
 * 
 * @since 1.2
 * @since 1.8 Attempt to generate service worker again if it doesn't exist.
 * @since 2.0 Modify logic to check if Service worker exists.
 * @since 2.0.1 Added checks to see if dynamic file is valid. If not, generates a physical file. 
 */
function superpwa_sw_status_cb() {
	
	/** 
	 * Check to see if the file exists, If not attempts to generate a new one.
	 */
	if ( superpwa_file_exists( superpwa_sw( 'src' ) ) || superpwa_generate_sw() ) {
		
		printf( '<p><span class="dashicons dashicons-yes" style="color: #46b450;"></span> ' . __( 'Service worker generated successfully. <a href="%s" target="_blank">See it here &rarr;</a>', 'super-progressive-web-apps' ) . '</p>', superpwa_sw( 'src' ) );
	} else {
		
		printf( '<p><span class="dashicons dashicons-no-alt" style="color: #dc3232;"></span> ' . __( 'Service worker generation failed. <a href="%s" target="_blank">Fix it &rarr;</a>', 'super-progressive-web-apps' ) . '</p>', 'https://superpwa.com/doc/fixing-manifest-service-worker-generation-failed-error/?utm_source=superpwa-plugin&utm_medium=settings-status-no-sw' );
	}
}

/**
 * HTTPS Status
 *
 * @since 1.2
 */
function superpwa_https_status_cb() {

	if ( is_ssl() ) {
		
		printf( '<p><span class="dashicons dashicons-yes" style="color: #46b450;"></span> ' . __( 'Your website is served over HTTPS.', 'super-progressive-web-apps' ) . '</p>' );
	} else {
		
		printf( '<p><span class="dashicons dashicons-no-alt" style="color: #dc3232;"></span> ' . __( 'Progressive Web Apps require that your website is served over HTTPS. Please contact your host to add a SSL certificate to your domain.', 'super-progressive-web-apps' ) . '</p>' );
	}
}
 

/**
 * Admin can disable the add to home bar
 *
 * @since 2.1.4
 */ 
function superpwa_disable_add_to_home_cb() {
	// Get Settings
	$settings = superpwa_get_settings(); 
	?><input type="checkbox" name="superpwa_settings[disable_add_to_home]" id="superpwa_settings[disable_add_to_home]" value="1" 
	<?php if ( isset( $settings['disable_add_to_home'] ) ) { checked( '1', $settings['disable_add_to_home'] ); } ?>>
	<label for="superpwa_settings[disable_add_to_home]"><?php _e('Remove default banner', 'super-progressive-web-apps') ?></label>
	<br>
	<?php
}

/**
 * Admin interface renderer
 *
 * @since 1.0
 * @since 1.7 Handling of settings saved messages since UI is its own menu item in the admin menu.
 */ 
function superpwa_admin_interface_render() {
	
	// Authentication
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	
	// Handing save settings
	if ( isset( $_GET['settings-updated'] ) ) {
		
		// Add settings saved message with the class of "updated"
		add_settings_error( 'superpwa_settings_group', 'superpwa_settings_saved_message', __( 'Settings saved.', 'super-progressive-web-apps' ), 'updated' );
		
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
			
			// Basic Application Settings
			do_settings_sections( 'superpwa_basic_settings_section' );	// Page slug
			
			// Status
			do_settings_sections( 'superpwa_pwa_status_section' );	// Page slug

			// Advance
			do_settings_sections( 'superpwa_pwa_advance_section' );	// Page slug
			
			// Output save settings button
			 echo '<style>.submit{float:left;}</style>';
			submit_button( __('Save Settings', 'super-progressive-web-apps') );
			if(!defined('SUPERPWA_PRO_VERSION')){
				echo '<a class="button" style="background: black;color: white;margin: 30px 0px 0px 25px;" href="'.admin_url('admin.php?page=superpwa-upgrade').'" target="_blank">Go PRO</a>';
			}
			?>
		</form>
	</div>
	<?php
}