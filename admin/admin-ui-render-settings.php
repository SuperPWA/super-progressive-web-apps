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
 * @function	superpwa_app_screenshots_cb()			Screenshots Icon
 * @function	superpwa_start_url_cb()					Start URL Dropdown
 * @function	superpwa_app_category_cb()				App Category Dropdown
 * @function	superpwa_offline_page_cb()				Offline Page Dropdown
 * @function	superpwa_orientation_cb()				Default Orientation Dropdown
 * @function	superpwa_display_cb()					Default Display Dropdown
 * @function	superpwa_text_direction_cb()			Text Direction Dropdown
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
 * Screenshots Icon
 *
 * @since 1.0
 */
function superpwa_app_screenshots_cb() {

	// Get Settings
	$settings = superpwa_get_settings(); ?>
	
	<!-- Application Icon -->
	<input type="text" name="superpwa_settings[screenshots]" id="superpwa_settings[screenshots]" class="superpwa-screenshots regular-text" size="50" value="<?php echo isset( $settings['screenshots'] ) ? esc_attr( $settings['screenshots']) : ''; ?>">
	<button type="button" class="button superpwa-screenshots-upload" data-editor="content">
		<span class="dashicons dashicons-format-image" style="margin-top: 4px;"></span> <?php _e( 'Choose Screenshots', 'super-progressive-web-apps' ); ?>
	</button>
	
	<p class="description">
		<?php _e('This will be the screenshots of your app when installed on the phone. Must be a <code>PNG</code> image exactly <code>472x1024</code> in size.', 'super-progressive-web-apps'); ?>
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
 * App Category Dropdown
 *
 * @since 1.2
 */
function superpwa_app_category_cb() {

	// Get Settings
	$settings = superpwa_get_settings(); ?>
	
	<fieldset>
	
		<!-- WordPress Pages Dropdown -->
		<label for="superpwa_settings[app_category]">
		<?php 
		// Allowed manifest categories
		$manifest_categories=["business","education","entertainment","finance","fitness","food","games","government","health","kids","lifestyle","magazines","medical","music","navigation","security","shopping","social","sports","travel","utilities","weather"];
		?>
			<select name="superpwa_settings[app_category]" id="superpwa_settings[app_category]">
			<option value=""><?php _e('— Select Category —', 'super-progressive-web-apps' ); ?></option>
				<?php foreach($manifest_categories as $category){ ?>
				<option value="<?php echo $category?>" <?php if ( isset( $settings['app_category'] ) ) { selected( $settings['app_category'], $category); } ?>>
					<?php _e($category, 'super-progressive-web-apps' ); ?>
				</option>
				<?php } ?>
			</select>
		</label>
	
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
 * Text Direction Dropdown
 *
 * @author Jose Varghese
 * 
 * @since 2.0
 */
function superpwa_text_direction_cb() {

	// Get Settings
	$settings = superpwa_get_settings(); ?>
	
	<!-- Display Dropdown -->
	<label for="superpwa_settings[text_dir]">
		<select name="superpwa_settings[text_dir]" id="superpwa_settings[display]">
			<option value="0" <?php if ( isset( $settings['text_dir'] ) ) { selected( $settings['text_dir'], 0 ); } ?>>
				<?php _e( 'LTR', 'super-progressive-web-apps' ); ?>
			</option>
			<option value="1" <?php if ( isset( $settings['text_dir'] ) ) { selected( $settings['text_dir'], 1 ); } ?>>
				<?php _e( 'RTL', 'super-progressive-web-apps' ); ?>
			</option>
		</select>
	</label>
	
	<p class="description">
		<?php printf( __( 'The text direction of your PWA', 'super-progressive-web-apps' )); ?>
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
 * App Shortcut link Dropdown
 *
 * @since 1.2
 */
function superpwa_app_shortcut_link_cb() {

	// Get Settings
	$settings = superpwa_get_settings(); ?>
	
	<fieldset>
	
		<!-- WordPress Pages Dropdown -->
		<label for="superpwa_settings[shortcut_url]">
		<?php echo wp_dropdown_pages( array( 
				'name' => 'superpwa_settings[shortcut_url]', 
				'echo' => 0, 
				'show_option_none' => __( 'Select Page' ), 
				'option_none_value' => '0', 
				'selected' =>  isset($settings['shortcut_url']) ? $settings['shortcut_url'] : '',
			)); ?>
		</label>
		
		<p class="description">
			<?php echo __( 'Specify the page to load when the application is launched via Shortcut.', 'super-progressive-web-apps' ); ?>
		</p>
	</fieldset>

	<?php
}

/**
 * Enable or disable the yandex support
 *
 * @since 2.1.4
 */ 
function superpwa_yandex_support_cb() {
	// Get Settings
	$settings = superpwa_get_settings(); 
	?><input type="checkbox" name="superpwa_settings[yandex_support]" id="superpwa_settings[yandex_support]" value="1" 
	<?php if ( isset( $settings['yandex_support'] ) ) { checked( '1', $settings['yandex_support'] ); } ?>>
	<br>
	<?php
}
/**
 * Enable or disable the analytics support
 *
 * @since 2.1.5
 */ 
function superpwa_analytics_support_cb() {
	// Get Settings
	$settings = superpwa_get_settings(); 
	?><input type="checkbox" name="superpwa_settings[analytics_support]" id="superpwa_settings[analytics_support]" value="1" 
	<?php if ( isset( $settings['analytics_support'] ) ) { checked( '1', $settings['analytics_support'] ); } ?>>
	<br>
	<?php
}

/**
 * Enable or disable cache external urls support
 *
 * @since 2.1.6
 */ 
function superpwa_cache_external_urls_support_cb() {
	// Get Settings
	$settings = superpwa_get_settings(); 
	?><input type="checkbox" name="superpwa_settings[cache_external_urls]" id="superpwa_settings[cache_external_urls]" value="1" 
	<?php if ( isset( $settings['cache_external_urls'] ) ) { checked( '1', $settings['cache_external_urls'] ); } ?>>
	<br>
	<?php
}

/**
 * Exclude Urls from Cache list of service worker
 *
 * @since 2.1.2
 */

function superpwa_exclude_url_cache_cb(){
	// Get Settings
	$settings = superpwa_get_settings(); 
	?>
        <label><textarea placeholder="https://example.com/contact-us/, https://example.com/checkout/"  rows="4" cols="70" id="superpwa_settings[excluded_urls]" name="superpwa_settings[excluded_urls]"><?php echo (isset($settings['excluded_urls']) ? esc_attr($settings['excluded_urls']): ''); ?></textarea></label>
        <p><?php echo esc_html__('Note: Seperate the URLs using a Comma(,)', 'super-progressive-web-apps'); ?></p>
	<p><?php echo esc_html__('Place the list of URLs which you do not want to cache by service worker', 'super-progressive-web-apps'); ?></p>	
	
	<?php
}

/**
 * Exclude add to home screen popup on particular pages
 *
 * @since 2.1.19
 */

function superpwa_exclude_add_to_homescreen_cb(){
	// Get Settings
	$settings = superpwa_get_settings(); 
	?>
        <label><textarea placeholder="https://example.com/contact-us/, https://example.com/checkout/"  rows="4" cols="70" id="superpwa_settings[exclude_homescreen]" name="superpwa_settings[exclude_homescreen]"><?php echo (isset($settings['exclude_homescreen']) ? esc_attr($settings['exclude_homescreen']): ''); ?></textarea></label>
        <p><?php echo esc_html__('Note: Seperate the URLs using a Comma(,)', 'super-progressive-web-apps'); ?></p>
	<p><?php echo esc_html__('Place the list of URLs on which add to homescreen will be hidden', 'super-progressive-web-apps'); ?></p>	
	
	<?php
}

function superpwa_reset_settings_cb(){		
	?>              
        <button class="button superpwa-reset-settings">
            <?php echo esc_html__('Reset','super-progressive-web-apps'); ?>
        </button>
        
	<?php
}

function superpwa_bypass_sw_url_cache_cb(){		
	$settings = superpwa_get_settings(); 
	?><input type="checkbox" name="superpwa_settings[bypass_sw_url_cache]" id="superpwa_settings[bypass_sw_url_cache]" value="1" 
	<?php if ( isset( $settings['bypass_sw_url_cache'] ) ) { checked( '1', $settings['bypass_sw_url_cache'] ); } ?>>
	<br>
	<p><?php echo esc_html__(' Enable this option when ', 'super-progressive-web-apps'); ?></p>	
	<p><?php echo esc_html__(' * Your service worker file does not update or is cached by your server.', 'super-progressive-web-apps'); ?></p>	
	<p><?php echo esc_html__(' * If manual pre caching pages are not cached.', 'super-progressive-web-apps'); ?></p>	
	<?php
}

/**
 * Force Update Service Worker
 *
 * @since 2.1.6
 */

function superpwa_force_update_sw_cb(){
	// Get Settings
	$settings = superpwa_get_settings(); 
	?>
         <label><input type="text" id="superpwa_settings[force_update_sw_setting]" name="superpwa_settings[force_update_sw_setting]" value="<?php if(isset($settings['force_update_sw_setting'])){ 
        	if(!version_compare($settings['force_update_sw_setting'],SUPERPWA_VERSION, '>=') ){
				$settings['force_update_sw_setting'] = SUPERPWA_VERSION;
			}
        	echo esc_attr($settings['force_update_sw_setting']);
        }else{ echo SUPERPWA_VERSION; } ?>"></label>      
        <code>Current Version <?php echo SUPERPWA_VERSION; ?></code>
	<p><?php echo esc_html__('Update the version number. It will automatically re-install the service worker for all the users', 'super-progressive-web-apps'); ?></p>
	
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
	<style type="text/css">.spwa-tab {overflow: hidden;border: 1px solid #ccc;background-color: #fff;margin-top: 15px;}.spwa-tab a {background-color: inherit;text-decoration: none;float: left;border: none;outline: none;cursor: pointer;padding: 14px 16px;transition: 0s;font-size: 15px;color: #2271b1;}.spwa-tab a:hover {color: #0a4b78;}.spwa-tab a.active {box-shadow: none;border-bottom: 4px solid #646970;color: #1d2327;}.spwa-tab a:focus {box-shadow: none;outline: none;}.spwa-tabcontent {display: none;padding: 6px 12px;border-top: none; animation: fadeEffect 1s; }p.support-cont {font-size: 14px;font-weight: 500;color: #646970;}#support{margin-top: 1em;} @keyframes fadeEffect { from {opacity: 0;} to {opacity: 1;} }</style>

	<div class="wrap">	
		<?php
        if ( defined('SUPERPWA_PRO_VERSION') ) {
        	wp_enqueue_script('superpwa-pro-admin', trailingslashit(SUPERPWA_PRO_PATH_SRC).'assets/js/admin.js', array('jquery'), SUPERPWA_PRO_VERSION, true);
        	$array = array('security_nonce'=>wp_create_nonce('superpwa_pro_post_nonce'));
		wp_localize_script('superpwa-pro-admin', 'superpwa_pro_var', $array);
            $license_info = get_option("superpwa_pro_upgrade_license");
            if ( defined('SUPERPWA_PRO_PLUGIN_DIR_NAME') && !empty($license_info) ){
            $superpwa_pro_manager = SUPERPWA_PRO_PLUGIN_DIR_NAME.'/assets/inc/superpwa-pro-license-data.php';                
                if( file_exists($superpwa_pro_manager) ){
                    require_once $superpwa_pro_manager;
                    if( $_GET['page'] == 'superpwa' ) {
                wp_enqueue_style( 'superpwa-license-panel-css', SUPERPWA_PRO_PATH_SRC . '/assets/inc/css/superpwa-pro-license-data.css', array() , SUPERPWA_PRO_VERSION );
            }
        }
    }
} ?>
		<h1><?php echo esc_html__('Super Progressive Web Apps', 'super-progressive-web-apps'); ?> <sup><?php echo SUPERPWA_VERSION; ?></sup></h1>
		
		<form action="options.php" method="post" enctype="multipart/form-data">		
			<?php
			// Output nonce, action, and option_page fields for a settings page.
			settings_fields( 'superpwa_settings_group' );
			$addon_page = admin_url( 'admin.php?page=superpwa-addons');
			?>
			<div class="spwa-tab">
			  <a id="spwa-default" class="spwa-tablinks" data-href="no" href="#general-settings" onclick="openCity(event, 'settings')"><?php echo __('Settings', 'super-progressive-web-apps'); ?></a>
			  <a class="spwa-tablinks" id="spwa-feature" href="<?php echo $addon_page;  ?>" data-href="yes"><?php echo __('Features (Addons)', 'super-progressive-web-apps'); ?></a>
			  <a class="spwa-tablinks" id="spwa-advance" href="#advance-settings" onclick="openCity(event, 'advance')" data-href="no"><?php echo __('Advanced', 'super-progressive-web-apps'); ?></a>
			  <a class="spwa-tablinks" id="spwa-support" href="#support-settings" onclick="openCity(event, 'support')" data-href="no"><?php echo __('Help & Support', 'super-progressive-web-apps'); ?></a>
			  <?php if( defined('SUPERPWA_PRO_VERSION') ){ 
			     $expiry_warning = superpwa_license_expire_warning();
			  	?>
			    <a class="spwa-tablinks" id="spwa-license" href="#license-settings" onclick="openCity(event, 'superpwa_pro_license')" data-href="no">License <?php echo $expiry_warning; ?></a>
			  <?php } ?>
			  <?php if(!defined('SUPERPWA_PRO_VERSION')){ ?>
				<a class="spwa-tablinks" id="spwa-upgrade2pro" style="background: #ff4c4c;color: #ffffff;margin-right: 5px; float: right; font-weight: 700; padding: 16px 25px" href="<?php echo admin_url('admin.php?page=superpwa-upgrade'); ?>" onclick="openCity(event, 'superpwa-upgrade')" data-href="no"><?php echo __( 'Upgrade to PRO', 'super-progressive-web-apps' ); ?></a>
			  <?php } ?>
			</div>
			<span id="alert-warning" style=" margin-top: 10px; display: none; padding: 10px;background-color: #ff9800;color: white;"> <?php _e( 'Please Save the settings before moving to other tabs', 'super-progressive-web-apps' ); ?> </span>
			<div id="settings" class="spwa-tabcontent">
			 <?php
			  	// Basic Application Settings
				do_settings_sections( 'superpwa_basic_settings_section' );	// Page slug
				
				// Status
				do_settings_sections( 'superpwa_pwa_status_section' );	// Page slug
				// Output save settings button
				echo '<style>.submit{float:left;}</style>';
				submit_button( __('Save Settings', 'super-progressive-web-apps') );
				if(!defined('SUPERPWA_PRO_VERSION')){
					echo '<a class="button" style="background: black;color: white;margin: 30px 0px 0px 25px;" href="'.admin_url('admin.php?page=superpwa-upgrade').'" target="_blank">'.__( 'Go PRO', 'super-progressive-web-apps').'</a>';
				}
			?>
			</div>
			<div id="advance" class="spwa-tabcontent">
			 <?php
			  	// Advance
			  	do_settings_sections( 'superpwa_pwa_advance_section' );	// Page slug
			  	// Output save settings button
				echo '<style>.submit{float:left;}</style>';
				submit_button( __('Save Settings', 'super-progressive-web-apps') );
				if(!defined('SUPERPWA_PRO_VERSION')){
					echo '<a class="button" style="background: black;color: white;margin: 30px 0px 0px 25px;" href="'.admin_url('admin.php?page=superpwa-upgrade').'" target="_blank">'.__( 'Go PRO', 'super-progressive-web-apps').'</a>';
				}
			?>
			</div>
			<div id="support" class="spwa-tabcontent">

			 <?php
              //1)Docs 2)Find new or whats new in superpwa(Blog Post Link)
			 //3)Technical issue (supportLink) 4)Report a Bug(Support Link)

			  ?>
			 <h1><?php esc_html_e(' 1) Documentation', 'super-progressive-web-apps'); ?></h1>
			 <p class="support-cont"><?php esc_html_e('All the documents regarding SuperPWA Setup, it\'s settings detail and also about add-ons setup all you can go through this ', 'super-progressive-web-apps'); ?><b><a href="https://superpwa.com/docs/" target="_blank"><?php esc_html_e('Docs link', 'super-progressive-web-apps'); ?></a></b></p>

			 <h1><?php esc_html_e(' 2) What\'s New', 'super-progressive-web-apps'); ?></h1>
			 <p class="support-cont"><?php esc_html_e('We will be continuously working on new features whereas also fixing the bugs and at the sametime releasing new feature add-ons, So to catch all those things just check this link ', 'super-progressive-web-apps'); ?> <b><a href="https://superpwa.com/blog/" target="_blank"><?php esc_html_e('What\'s new in SuperPWA', 'super-progressive-web-apps'); ?></a></b></p>

			 <h1><?php esc_html_e(' 3) Technical Issue', 'super-progressive-web-apps'); ?></h1>
			 <p class="support-cont"><?php esc_html_e('If you are facing any issues or unable to Setup, you can directly connect us using this link', 'super-progressive-web-apps'); ?> <b><a href="https://superpwa.com/contact/" target="_blank"><?php esc_html_e('Contact us', 'super-progressive-web-apps'); ?></a></b></p>

			 <h1><?php esc_html_e(' 4) Report a Bug', 'super-progressive-web-apps'); ?></h1>
			 <p class="support-cont"><?php esc_html_e('If you found any bug or having issues with any third party plugins you can contact us ', 'super-progressive-web-apps'); ?> <b><a href="https://superpwa.com/contact/" target="_blank"><?php esc_html_e('Bug Report', 'super-progressive-web-apps'); ?></a></b></p>
			</div>

			<div id="superpwa_pro_license" class="spwa-tabcontent">

			 <?php
			 if ( function_exists('superpwa_pro_upgrade_license_page') ) {
			 	superpwa_pro_upgrade_license_page();
			 }
			 ?>
			 
			</div>
 
</form>
<details id="superpwa-ocassional-pop-up-container">
<summary class="superpwa-ocassional-pop-up-open-close-button"><?php esc_html_e('40% OFF - Limited Time Only', 'super-progressive-web-apps')?><svg fill="#fff" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 288.359 288.359" style="enable-background:new 0 0 288.359 288.359;" xml:space="preserve"><g><path d="M283.38,4.98c-3.311-3.311-7.842-5.109-12.522-4.972L163.754,3.166c-4.334,0.128-8.454,1.906-11.52,4.972L4.979,155.394   c-6.639,6.639-6.639,17.402,0,24.041L108.924,283.38c6.639,6.639,17.402,6.639,24.041,0l147.256-147.256   c3.065-3.065,4.844-7.186,4.972-11.52l3.159-107.103C288.49,12.821,286.691,8.291,283.38,4.98z M247.831,130.706L123.128,255.407   c-1.785,1.785-4.679,1.785-6.464,0l-83.712-83.712c-1.785-1.785-1.785-4.679,0-6.464L157.654,40.529   c1.785-1.785,4.679-1.785,6.464,0l83.713,83.713C249.616,126.027,249.616,128.921,247.831,130.706z M263.56,47.691   c-6.321,6.322-16.57,6.322-22.892,0c-6.322-6.321-6.322-16.57,0-22.892c6.321-6.322,16.569-6.322,22.892,0   C269.882,31.121,269.882,41.37,263.56,47.691z"/><path d="M99.697,181.278c-5.457,2.456-8.051,3.32-10.006,1.364c-1.592-1.591-1.5-4.411,1.501-7.412   c1.458-1.458,2.927-2.52,4.26-3.298c1.896-1.106,2.549-3.528,1.467-5.438l-0.018-0.029c-0.544-0.96-1.455-1.658-2.522-1.939   c-1.067-0.279-2.202-0.116-3.147,0.453c-1.751,1.054-3.64,2.48-5.587,4.428c-7.232,7.23-7.595,15.599-2.365,20.829   c4.457,4.457,10.597,3.956,17.463,0.637c5.004-2.364,7.55-2.729,9.46-0.819c2.002,2.002,1.638,5.004-1.545,8.186   c-1.694,1.694-3.672,3.044-5.582,4.06c-0.994,0.528-1.728,1.44-2.027,2.525c-0.3,1.085-0.139,2.245,0.443,3.208l0.036,0.06   c1.143,1.889,3.575,2.531,5.503,1.457c2.229-1.241,4.732-3.044,6.902-5.215c8.412-8.412,8.002-16.736,2.864-21.875   C112.475,178.141,107.109,177.868,99.697,181.278z"/><path d="M150.245,157.91l-31.508-16.594c-1.559-0.821-3.47-0.531-4.716,0.714l-4.897,4.898c-1.25,1.25-1.537,3.169-0.707,4.73   l16.834,31.654c0.717,1.347,2.029,2.274,3.538,2.5c1.509,0.225,3.035-0.278,4.114-1.357c1.528-1.528,1.851-3.89,0.786-5.771   l-3.884-6.866l8.777-8.777l6.944,3.734c1.952,1.05,4.361,0.696,5.928-0.871c1.129-1.129,1.654-2.726,1.415-4.303   C152.63,160.023,151.657,158.653,150.245,157.91z M125.621,165.632c0,0-7.822-13.37-9.187-15.644l0.091-0.092   c2.274,1.364,15.872,8.959,15.872,8.959L125.621,165.632z"/><path d="M173.694,133.727c-1.092,0-2.139,0.434-2.911,1.205l-9.278,9.278l-21.352-21.352c-0.923-0.923-2.175-1.441-3.479-1.441   s-2.557,0.519-3.479,1.441c-1.922,1.922-1.922,5.037,0,6.958l24.331,24.332c1.57,1.569,4.115,1.569,5.685,0l13.395-13.395   c1.607-1.607,1.607-4.213,0-5.821C175.833,134.16,174.786,133.727,173.694,133.727z"/><path d="M194.638,111.35l-9.755,9.755l-7.276-7.277l8.459-8.458c1.557-1.558,1.557-4.081-0.001-5.639   c-1.557-1.557-4.082-1.557-5.639,0l-8.458,8.458l-6.367-6.366l9.117-9.117c1.57-1.57,1.57-4.115,0-5.686   c-0.754-0.755-1.776-1.179-2.843-1.179c-1.066,0-2.089,0.424-2.843,1.178l-13.234,13.233c-0.753,0.754-1.177,1.776-1.177,2.843   c0,1.066,0.424,2.089,1.178,2.843l24.968,24.968c1.57,1.569,4.115,1.569,5.685,0l13.87-13.87c1.57-1.57,1.57-4.115,0-5.686   C198.752,109.78,196.208,109.78,194.638,111.35z"/></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg></summary>
<span class="superpwa-promotion-close-btn">  &times;  </span>
<div class="superpwa-ocassional-pop-up-contents">

	<img src="<?php echo SUPERPWA_PATH_SRC.'public/images/offers.png'?>" class="superpwa-promotion-surprise-icon" />
	<p class="superpwa-ocassional-pop-up-headline"><?php esc_html_e('40% OFF on ', 'super-progressive-web-apps')?><span><?php esc_html_e('SuperPWA PRO', 'super-progressive-web-apps')?></span></p>
	<p class="superpwa-ocassional-pop-up-second-headline"><?php esc_html_e('Upgrade the PRO version during this festive season and get our biggest discount of all time on New Purchases, Renewals &amp; Upgrades', 'super-progressive-web-apps')?></p>
	<a class="superpwa-ocassional-pop-up-offer-btn" href="<?php echo esc_url('https://superpwa.com/november-deal/')?>" target="_blank"><?php esc_html_e('Get This Offer Now', 'super-progressive-web-apps')?></a>
	<p class="superpwa-ocassional-pop-up-last-line"><?php esc_html_e('Black Friday, Cyber Monday, Christmas &amp; New year are the only times we offer discounts this big.', 'super-progressive-web-apps')?></p>
</div>

</details>
<style>details#superpwa-ocassional-pop-up-container{position:fixed;right:1rem;bottom:1rem;margin-top:2rem;color:#6b7280;display:flex;flex-direction:column;z-index:99999}details#superpwa-ocassional-pop-up-container div.superpwa-ocassional-pop-up-contents{background-color:#1e1e27;box-shadow:0 5px 10px rgba(0,0,0,.15);padding:25px 25px 10px;border-radius:8px;position:absolute;max-height:calc(100vh - 100px);width:350px;max-width:calc(100vw - 2rem);bottom:calc(100% + 1rem);right:0;overflow:auto;transform-origin:100% 100%;color:#95a3b9;margin-bottom:44px}details#superpwa-ocassional-pop-up-container div.superpwa-ocassional-pop-up-contents::-webkit-scrollbar{width:15px;background-color:#1e1e27}details#superpwa-ocassional-pop-up-container div.superpwa-ocassional-pop-up-contents::-webkit-scrollbar-thumb{width:5px;border-radius:99em;background-color:#95a3b9;border:5px solid #1e1e27}details#superpwa-ocassional-pop-up-container div.superpwa-ocassional-pop-up-contents>*+*{margin-top:.75em}details#superpwa-ocassional-pop-up-container div.superpwa-ocassional-pop-up-contents p>code{font-size:1rem;font-family:monospace}details#superpwa-ocassional-pop-up-container div.superpwa-ocassional-pop-up-contents pre{white-space:pre-line;border:1px solid #95a3b9;border-radius:6px;font-family:monospace;padding:.75em;font-size:.875rem;color:#fff}details#superpwa-ocassional-pop-up-container[open] div.superpwa-ocassional-pop-up-contents{bottom:0;-webkit-animation:.25s superpwa_ocassional_pop_up_scale;animation:.25s superpwa_ocassional_pop_up_scale}details#superpwa-ocassional-pop-up-container span.superpwa-promotion-close-btn{font-weight:400;font-size:20px;background:#37474f;font-family:sans-serif;border-radius:30px;color:#fff;position:absolute;right:-10px;z-index:99999;padding:0 8px;top:-311px;cursor:pointer;line-height:28px}details#superpwa-ocassional-pop-up-container div.superpwa-ocassional-pop-up-contents img.superpwa-promotion-surprise-icon{width:40px;float:left;margin-right:10px}details#superpwa-ocassional-pop-up-container div.superpwa-ocassional-pop-up-contents p.superpwa-ocassional-pop-up-headline{font-size:21px;margin:0;line-height:47px;font-weight:500;color:#fff}details#superpwa-ocassional-pop-up-container div.superpwa-ocassional-pop-up-contents p.superpwa-ocassional-pop-up-headline span{color:#F04720;font-weight:700}details#superpwa-ocassional-pop-up-container div.superpwa-ocassional-pop-up-contents p.superpwa-ocassional-pop-up-second-headline{font-size:16px;color:#fff}details#superpwa-ocassional-pop-up-container div.superpwa-ocassional-pop-up-contents a.superpwa-ocassional-pop-up-offer-btn{background:#1EB7E7;padding:13px 38px 14px;color:#fff;text-align:center;border-radius:60px;font-size:18px;display:inline-flex;align-items:center;margin:0 auto 15px;text-decoration:none;line-height:1.2;transform:perspective(1px) translateZ(0);box-shadow:0 0 20px 5px rgb(0 0 0 / 6%);transition:.3s ease-in-out;box-shadow:3px 5px .65em 0 rgb(0 0 0 / 15%);display:inherit}details#superpwa-ocassional-pop-up-container div.superpwa-ocassional-pop-up-contents p.superpwa-ocassional-pop-up-last-line{font-size:12px;color:#a6a6a6}details#superpwa-ocassional-pop-up-container summary{display:inline-flex;margin-left:auto;margin-right:auto;justify-content:center;align-items:center;font-weight:600;padding:.5em 1.25em;border-radius:99em;color:#fff;background-color:#185adb;box-shadow:0 5px 15px rgba(0,0,0,.1);list-style:none;text-align:center;cursor:pointer;transition:.15s;position:relative;font-size:.9rem;z-index:99999}details#superpwa-ocassional-pop-up-container summary::-webkit-details-marker{display:none}details#superpwa-ocassional-pop-up-container summary:hover,summary:focus{background-color:#1348af}details#superpwa-ocassional-pop-up-container summary svg{width:25px;margin-left:5px;vertical-align:baseline}@-webkit-keyframes superpwa_ocassional_pop_up_scale{0%{transform:superpwa_ocassional_pop_up_scale(0)}100%{transform:superpwa_ocassional_pop_up_scale(1)}}@keyframes superpwa_ocassional_pop_up_scale{0%{transform:superpwa_ocassional_pop_up_scale(0)}100%{transform:superpwa_ocassional_pop_up_scale(1)}}</style>
<script>function superpwa_set_admin_occasional_ads_pop_up_cookie(){var o=new Date;o.setFullYear(o.getFullYear()+1),document.cookie="superpwa_hide_admin_occasional_ads_pop_up_cookie_feedback=1; expires="+o.toUTCString()+"; path=/"}function superpwa_delete_admin_occasional_ads_pop_up_cookie(){document.cookie="superpwa_hide_admin_occasional_ads_pop_up_cookie_feedback=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;"}function superpwa_get_admin_occasional_ads_pop_up_cookie(){for(var o="superpwa_hide_admin_occasional_ads_pop_up_cookie_feedback=",a=decodeURIComponent(document.cookie).split(";"),e=0;e<a.length;e++){for(var c=a[e];" "==c.charAt(0);)c=c.substring(1);if(0==c.indexOf(o))return c.substring(o.length,c.length)}return""}jQuery(function(o){var a=superpwa_get_admin_occasional_ads_pop_up_cookie();0==a&&""==a&&jQuery("details#superpwa-ocassional-pop-up-container").attr("open",1);void 0!==a&&""!==a&&o("details#superpwa-ocassional-pop-up-container").attr("open",!1),o("details#superpwa-ocassional-pop-up-container span.superpwa-promotion-close-btn").click(function(a){o("details#superpwa-ocassional-pop-up-container summary").click()}),o("details#superpwa-ocassional-pop-up-container summary").click(function(a){var e=o(this).parents("details#superpwa-ocassional-pop-up-container"),c=o(e).attr("open");void 0!==c&&!1!==c?superpwa_set_admin_occasional_ads_pop_up_cookie():superpwa_delete_admin_occasional_ads_pop_up_cookie()})});</script>		       

	</div>
	<?php superpwa_newsletter_form(); ?>
	<script type="text/javascript">function openCity(evt, cityName) {var i, tabcontent, tablinks;tabcontent = document.getElementsByClassName("spwa-tabcontent");for (i = 0; i < tabcontent.length; i++) { tabcontent[i].style.display = "none"; } tablinks = document.getElementsByClassName("spwa-tablinks"); for (i = 0; i < tablinks.length; i++) { tablinks[i].className = tablinks[i].className.replace(" active", ""); } document.getElementById(cityName).style.display = "block"; evt.currentTarget.className += " active"; }
	     var url = window.location.href; 
	    if(url.indexOf('#advance-settings') > -1){
            document.getElementById("spwa-advance").click();
	    }else if(url.indexOf('#support-settings') > -1){
            document.getElementById("spwa-support").click();
	    }else if(url.indexOf('#license-settings') > -1){
            document.getElementById("spwa-license").click();
	    }else{	
        	document.getElementById("spwa-default").click();
	    }
        </script>
	<?php
}