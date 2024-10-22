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
 * @function	superpwa_app_monochrome_icon_cb()		Monochrome Icon
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
require_once( SUPERPWA_PATH_ABS . 'functions/wp_dropdown_posts.php' );
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
			<?php echo esc_html__('Used when there is insufficient space to display the full name of the application. ', 'super-progressive-web-apps').'<span id="superpwa-app-short-name-limit"><code>'. esc_html__('20', 'super-progressive-web-apps').'</code> '. esc_html__('characters or less.', 'super-progressive-web-apps').'</span>'; ?>
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
			<?php esc_html_e( 'A brief description of what your app is about.', 'super-progressive-web-apps' ); ?>
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
		<span class="dashicons dashicons-format-image" style="margin-top: 4px;"></span> <?php esc_html_e( 'Choose Icon', 'super-progressive-web-apps' ); ?>
	</button>
	
	<p class="description">
		<?php echo esc_html__('This will be the icon of your app when installed on the phone. Must be a ', 'super-progressive-web-apps').'<code>'. esc_html__('PNG', 'super-progressive-web-apps').'</code>'. esc_html__('image exactly ', 'super-progressive-web-apps').' <code>'. esc_html__('192x192', 'super-progressive-web-apps').'</code>'. esc_html__('in size.', 'super-progressive-web-apps'); ?>
	</p>

	<?php
}
/**
 * Application Maskable Icon
 *
 * @since 2.2.30
 */
function superpwa_app_maskable_icon_cb() {
	$settings = superpwa_get_settings(); ?>
	<input type="text" name="superpwa_settings[app_maskable_icon]" id="superpwa_settings[app_maskable_icon]" class="superpwa-maskable-icon superpwa-maskable-input regular-text" size="50" value="<?php echo isset( $settings['app_maskable_icon'] ) ? esc_attr( $settings['app_maskable_icon']) : ''; ?>">
	<button type="button" class="button superpwa-maskable-icon-upload" data-editor="content">
		<span class="dashicons dashicons-format-image" style="margin-top: 4px;"></span> <?php esc_html_e( 'Choose Icon', 'super-progressive-web-apps' ); ?>
	</button>
	<button type="button" style="background-color: red; border-color: red; color: #fff; display:none;" class="button superpwa_js_remove_maskable" > <?php echo esc_html__('Remove', 'super-progressive-web-apps'); ?></button>
	
	<p class="description">
		<?php echo esc_html__('This will be the maskable icon of your app when installed on the phone. Must be a ', 'super-progressive-web-apps').'<code>'. esc_html__('PNG', 'super-progressive-web-apps').'</code>'. esc_html__('image exactly ', 'super-progressive-web-apps').' <code>'. esc_html__('192x192', 'super-progressive-web-apps').'</code>'. esc_html__('in size.', 'super-progressive-web-apps'); ?>
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
		<span class="dashicons dashicons-format-image" style="margin-top: 4px;"></span> <?php esc_html_e( 'Choose Icon', 'super-progressive-web-apps' ); ?>
	</button>
	
	<p class="description">
		<?php echo esc_html__('This icon will be displayed on the splash screen of your app on supported devices. Must be a ', 'super-progressive-web-apps').'<code>'. esc_html__('PNG', 'super-progressive-web-apps').'</code>'. esc_html__('image exactly ', 'super-progressive-web-apps').' <code>'. esc_html__('512x512', 'super-progressive-web-apps').'</code>'. esc_html__('in size.', 'super-progressive-web-apps'); ?>
	</p>

	<?php
}

/**
 * Screenshots Icon
 *
 * @since 1.0
 */

/**
 * Application Maskable Icon
 *
 * @since 2.2.30
 */
function superpwa_splash_maskable_icon_cb() {
	$settings = superpwa_get_settings(); ?>
	<input type="text" name="superpwa_settings[splash_maskable_icon]" id="superpwa_settings[splash_maskable_icon]" class="superpwa-maskable-icon superpwa-maskable-input regular-text" size="50" value="<?php echo isset( $settings['splash_maskable_icon'] ) ? esc_attr( $settings['splash_maskable_icon']) : ''; ?>">
	<button type="button" class="button superpwa-maskable-icon-upload" data-editor="content">
		<span class="dashicons dashicons-format-image" style="margin-top: 4px;"></span> <?php esc_html_e( 'Choose Icon', 'super-progressive-web-apps' ); ?>
	</button>
	<button type="button" style="background-color: red; border-color: red; color: #fff; display:none;" class="button superpwa_js_remove_maskable" > <?php echo esc_html__('Remove', 'super-progressive-web-apps'); ?></button>
	
	<p class="description">
		<?php echo esc_html__('This icon will be displayed on the splash screen of your app on supported devices. Must be a ', 'super-progressive-web-apps').'<code>'. esc_html__('PNG', 'super-progressive-web-apps').'</code>'. esc_html__('image exactly ', 'super-progressive-web-apps').' <code>'. esc_html__('512x512', 'super-progressive-web-apps').'</code>'. esc_html__('in size.', 'super-progressive-web-apps'); ?>
	</p>

	<?php
}


function superpwa_app_screenshots_cb() {

	// Get Settings
	$settings = superpwa_get_settings(); ?>
	
	<!-- Application Icon -->
	<div class="js_clone_div" style="margin-top: 10px;">
		<input type="text" name="superpwa_settings[screenshots]" id="superpwa_settings[screenshots]" class="superpwa-screenshots regular-text" size="50" value="<?php echo isset( $settings['screenshots'] ) ? esc_attr( $settings['screenshots']) : ''; ?>">
		<button type="button" class="button button js_choose_button superpwa-screenshots-multiple-upload" data-editor="content">
			<span class="dashicons dashicons-format-image" style="margin-top: 4px;"></span> <?php esc_html_e( 'Choose Screenshots', 'super-progressive-web-apps' ); ?>
		</button>
		<select name="superpwa_settings[form_factor]" class="superpwa_settings_form_factor">
			<option value="" ><?php esc_html_e( 'Select Form Factor', 'super-progressive-web-apps' ); ?>
				</option>
			<option value="narrow" <?php if ( isset( $settings['form_factor'] ) ) { selected( $settings['form_factor'], 'narrow' ); } ?>>
				<?php esc_html_e( 'Narrow', 'super-progressive-web-apps' ); ?>
			</option>
			<option value="wide" <?php if ( isset( $settings['form_factor'] ) ) { selected( $settings['form_factor'], 'wide' ); } ?>>
				<?php esc_html_e( 'Wide', 'super-progressive-web-apps' ); ?>
			</option>
		</select>
		<button type="button" class="button button-primary" id="screenshots_add_more"> <?php echo esc_html__('Add More', 'super-progressive-web-apps'); ?> </button>
		<button type="button" style="background-color: red; border-color: red; color: #fff; display:none;" class="button js_remove_screenshot" > <?php echo esc_html__('Remove', 'super-progressive-web-apps'); ?> 
		</button>
	</div>
	<?php
	if (isset($settings['screenshots_multiple']) && is_array($settings['screenshots_multiple']) && !empty($settings['screenshots_multiple'])) {
		// print_r($settings['form_factor_multiple']);die;
		foreach ($settings['screenshots_multiple'] as $key => $screenshot) {
	?>	
		<div class="js_clone_div" style="margin-top: 10px;">
			<input type="text" name="superpwa_settings[screenshots_multiple][]"  class="superpwa-screenshots regular-text" size="50" value="<?php echo isset( $screenshot ) ? esc_attr( superpwa_httpsify($screenshot)) : ''; ?>">
			<button type="button" class="button js_choose_button superpwa-screenshots-multiple-upload" data-editor="content">
				<span class="dashicons dashicons-format-image" style="margin-top: 4px;"></span> <?php echo esc_html__('Choose Screenshots', 'super-progressive-web-apps'); ?> 
			</button>
			<select name="superpwa_settings[form_factor_multiple][]" class="superpwa_settings_form_factor_multiple">
				<option value="" ><?php esc_html_e( 'Select Form Factor', 'super-progressive-web-apps' ); ?>
				</option>
				<option value="narrow" <?php if ( isset( $settings['form_factor_multiple'][$key] ) ) { selected( $settings['form_factor_multiple'][$key], 'narrow' ); } ?>>
					<?php esc_html_e( 'Narrow', 'super-progressive-web-apps' ); ?>
				</option>
				<option value="wide" <?php if ( isset( $settings['form_factor_multiple'][$key] ) ) { selected( $settings['form_factor_multiple'][$key], 'wide' ); } ?>>
					<?php esc_html_e( 'Wide', 'super-progressive-web-apps' ); ?>
				</option>
			</select>
			<button type="button" style="background-color: red; border-color: red; color: #fff;" class="button js_remove_screenshot" > <?php echo esc_html__('Remove', 'super-progressive-web-apps'); ?> 
			</button>
		</div>
	<?php }
	}
	?>
	
	<p class="description">
		<?php echo esc_html__('This will be the screenshots of your app when installed on the phone. Must be a ', 'super-progressive-web-apps').'<code>'. esc_html__('PNG/WEBP', 'super-progressive-web-apps').'</code>'. esc_html__('image exactly ', 'super-progressive-web-apps').' <code>'. esc_html__('472x1024', 'super-progressive-web-apps').'</code>'. esc_html__('in size.', 'super-progressive-web-apps'); ?>
	</p>

	<?php
}

/**
 * Monochrome Icon
 *
 * @since 1.0
 */
function superpwa_app_monochrome_icon_cb() {

	// Get Settings
	$settings = superpwa_get_settings(); ?>
	
	<!-- Monochrome Icon -->
	<input type="text" name="superpwa_settings[monochrome_icon]" id="superpwa_settings[monochrome_icon]" class="superpwa-monochromeicon regular-text" size="50" value="<?php echo isset( $settings['monochrome_icon'] ) ? esc_attr( $settings['monochrome_icon']) : ''; ?>">
	<button type="button" class="button superpwa-monochrome-upload" data-editor="content">
		<span class="dashicons dashicons-format-image" style="margin-top: 4px;"></span> <?php esc_html_e( 'Choose Monochrome Icon', 'super-progressive-web-apps' ); ?>
	</button>
	
	<p class="description">
		<?php echo esc_html__('Please upload Monochrome icon with transparent background. Must be a ', 'super-progressive-web-apps').'<code>'. esc_html__('PNG', 'super-progressive-web-apps').'</code>'. esc_html__('image exactly ', 'super-progressive-web-apps').' <code>'. esc_html__('512x512', 'super-progressive-web-apps').'</code>'. esc_html__('in size.', 'super-progressive-web-apps'); ?>
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
		<?php esc_html_e('Background color of the splash screen.', 'super-progressive-web-apps'); ?>
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
		<?php echo esc_html__('Theme color is used on supported devices to tint the UI elements of the browser and app switcher. When in doubt, use the same color as ', 'super-progressive-web-apps').'<code>'. esc_html__('Background Color', 'super-progressive-web-apps').'</code>'; ?>
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
	$settings = superpwa_get_settings(); 
	$pro_active = function_exists('is_plugin_active')?is_plugin_active( 'super-progressive-web-apps-pro/super-progressive-web-apps-pro.php' ):false;
	?>
	
	<fieldset>
			<!-- WordPress Pages Dropdown -->
			<label for="superpwa_settings[startpage_type]">
			<select name="superpwa_settings[startpage_type]" id="superpwa_settings_startpage_type">
				<option value="page" <?php if ( isset( $settings['startpage_type'] ) ) { selected( $settings['startpage_type'], "page" ); } ?>><?php esc_html_e(' Select Page ', 'super-progressive-web-apps') ?></option>
				<option value="post" <?php if ( isset( $settings['startpage_type'] ) ) { selected( $settings['startpage_type'], "post" ); } ?>><?php esc_html_e(' Select Post ', 'super-progressive-web-apps') ?></option>
				<option value="active_url" <?php if ( isset( $settings['startpage_type'] ) ) { selected( $settings['startpage_type'], "active_url" ); } ?> ><?php esc_html_e(' &mdash; Dynamic URL &mdash;', 'super-progressive-web-apps') ?></option>
			</select>
		</label>
		<!-- WordPress Pages Dropdown -->
		<label for="superpwa_settings[start_url]">
		<?php 
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo wp_dropdown_pages( array( 
				'name' => 'superpwa_settings[start_url]', 
				'echo' => 0, 
				'show_option_none' => esc_html__( '&mdash; Homepage &mdash;', 'super-progressive-web-apps' ), 
				'option_none_value' => '0', 
				'id' =>'superpwa_start_pages',
				'class' =>'superpwa-select2 regular-text js_page',
				'selected' =>  isset($settings['start_url']) ? $settings['start_url'] : '',
			)); ?>
		</label>
		<!-- WordPress Posts Dropdown -->
		<label for="superpwa_settings[start_url]">
		<?php   wp_dropdown_posts( array( 
				'select_name' => 'superpwa_settings[start_url]', 
				'echo' => 1,
				'id' =>'superpwa_start_posts',
				'class' =>'superpwa-select2 regular-text js_post',
				'posts_per_page' => 50,
				'selected' =>  isset($settings['start_url']) ? $settings['start_url'] : '',
			)); ?>
		</label>
		<?php if(!$pro_active){ ?>
			<label style="display:none;" id="superpwa_startpage_pro_btn"> <?php esc_html_e('To use this option.', 'super-progressive-web-apps') ?> <a class="spwa-tablinks"  style="display:inline;border-radius:5px;background: #ff4c4c;color: #ffffff;font-weight: 700; padding: 4px 10px;text-decoration:none;" href="<?php echo esc_url(admin_url( 'admin.php?page=superpwa-upgrade' ))  ?>"><?php esc_html_e('Upgrade to PRO', 'super-progressive-web-apps') ?></a>
		</lable>
			<?php } ?>
		
		<p class="description">
		<?php echo esc_html__( 'Specify the page to load when the application is launched from a device. Current start page is', 'super-progressive-web-apps' ) ?>
		&nbsp;<code><?php echo esc_url(superpwa_get_start_url()); ?></code></p>
		<script>
		
		document.addEventListener('DOMContentLoaded', () => {
			const superpwa_stype = document.getElementById('superpwa_settings_startpage_type');
			if(superpwa_stype){
				superpwa_stype_toggle(superpwa_stype.value);
				superpwa_stype.addEventListener("change", (e) => {
				superpwa_stype_toggle(e.target.value);
            });
			}
           
        });
	
		function superpwa_stype_toggle(status ='page') {
			const page_select = document.getElementById('superpwa_start_pages');
			const post_select = document.getElementById('superpwa_start_posts');
			const pro_btn = document.getElementById('superpwa_startpage_pro_btn');
			if(status=="post"){
				page_select.setAttribute('disabled',true);
				page_select.parentNode.style.display="none";
				post_select.removeAttribute('disabled');
				post_select.parentNode.style.display="inline";
				if(pro_btn){
					pro_btn.style.display="none";
				}
			}
			else if (status=="page"){
				post_select.setAttribute('disabled',true);
				post_select.parentNode.style.display="none";
				page_select.removeAttribute('disabled');
				page_select.parentNode.style.display="inline";
				if(pro_btn){
					pro_btn.style.display="none";
				}
			}else{
				post_select.setAttribute('disabled',true);
				post_select.parentNode.style.display="none";
				page_select.setAttribute('disabled',true);
				page_select.parentNode.style.display="none";
				if(pro_btn){
					pro_btn.style.display="inline";
				}
			}
		}
	    </script>
		<?php if ( superpwa_is_amp() ) { ?>
		
			<!--  AMP Page As Start Page -->
			<br><input type="checkbox" name="superpwa_settings[start_url_amp]" id="superpwa_settings[start_url_amp]" value="1" 
				<?php if ( isset( $settings['start_url_amp'] ) ) { checked( '1', $settings['start_url_amp'] ); } ?>>
				<label for="superpwa_settings[start_url_amp]"><?php esc_html_e('Use AMP version of the start page.', 'super-progressive-web-apps') ?></label>
				<br>
			
			<!-- AMP for WordPress 0.6.2 doesn't support homepage, the blog index, and archive pages. -->
			<?php if ( is_plugin_active( 'amp/amp.php' ) ) { ?>
				<p class="description">
					<?php esc_html_e( 'Do not check this if your start page is the homepage, the blog index, or the archives page. AMP for WordPress does not create AMP versions for these pages.', 'super-progressive-web-apps' ); ?>
				</p>
			<?php } ?>
			
			<!-- tagDiv AMP 1.2 doesn't enable AMP for pages by default and needs to be enabled manually in settings -->			
			<?php if ( is_plugin_active( 'td-amp/td-amp.php' ) && method_exists( 'td_util', 'get_option' ) ) { 
				
				// Read option value from db
				$td_amp_page_post_type = td_util::get_option( 'tds_amp_post_type_page' );

				// Show notice if option to enable AMP for pages is disabled.
				if ( empty( $td_amp_page_post_type ) ) { ?>
					<p class="description">
					<?php echo esc_html__( 'Please enable AMP support for Page in', 'super-progressive-web-apps' ) ?>
					&nbsp;<a href="<?php echo esc_url(admin_url( 'admin.php?page=td_theme_panel' )) ?>"><?php echo esc_html__( 'Theme Settings > Theme Panel', 'super-progressive-web-apps' ) ?></a> > <?php echo esc_html__( 'AMP > Post Type Support', 'super-progressive-web-apps' ) ?>.
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
		$manifest_categories = [
			"business" => esc_html__( 'Business', 'super-progressive-web-apps' ),
			"education"=> esc_html__( 'Education', 'super-progressive-web-apps' ),
			"entertainment" => esc_html__( 'Entertainment', 'super-progressive-web-apps' ),
			"finance" => esc_html__( 'Finance', 'super-progressive-web-apps' ),
			"fitness" => esc_html__( 'Fitness', 'super-progressive-web-apps' ),
			"food" => esc_html__( 'Food', 'super-progressive-web-apps' ),
			"games" => esc_html__( 'Games', 'super-progressive-web-apps' ),
			"government" => esc_html__( 'Government', 'super-progressive-web-apps' ),
			"health" => esc_html__( 'Health', 'super-progressive-web-apps' ),
			"kids" => esc_html__( 'Kids', 'super-progressive-web-apps' ),
			"lifestyle" => esc_html__( 'Lifestyle', 'super-progressive-web-apps' ),
			"magazines" => esc_html__( 'Magazines', 'super-progressive-web-apps' ),
			"medical" => esc_html__( 'Medical', 'super-progressive-web-apps' ),
			"music" => esc_html__( 'Music', 'super-progressive-web-apps' ),
			"navigation" => esc_html__( 'Navigation', 'super-progressive-web-apps' ),
			"security" => esc_html__( 'Security', 'super-progressive-web-apps' ),
			"shopping" => esc_html__( 'Shopping', 'super-progressive-web-apps' ),
			"social" => esc_html__( 'Social', 'super-progressive-web-apps' ),
			"sports" => esc_html__( 'Sports', 'super-progressive-web-apps' ),
			"travel" => esc_html__( 'Travel', 'super-progressive-web-apps' ),
			"utilities" => esc_html__( 'Utilities', 'super-progressive-web-apps' ),
			"weather" => esc_html__( 'Weather', 'super-progressive-web-apps' )
		];
		?>
			<select name="superpwa_settings[app_category]" id="superpwa_settings[app_category]">
			<option value=""><?php esc_html_e('— Select Category —', 'super-progressive-web-apps' ); ?></option>
				<?php foreach($manifest_categories as $key_escaped => $category_escaped){ ?>
				<option value="<?php echo esc_attr($key_escaped)?>" <?php if ( isset( $settings['app_category'] ) ) { selected( $settings['app_category'], $key_escaped); } ?>>					
					<?php
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						echo $category_escaped; 
					?>
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
	<?php
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo wp_dropdown_pages( array( 
			'name' => 'superpwa_settings[offline_page]', 
			'echo' => 0, 
			'show_option_none' => __( '&mdash; Default &mdash;' ), 
			'option_none_value' => '0', 
			'selected' =>  isset($settings['offline_page']) ? $settings['offline_page'] : '',
		)); ?>
	</label>
	
	<p class="description">
		<?php echo 	esc_html__( 'Offline page is displayed when the device is offline and the requested page is not already cached. Current offline page is', 'super-progressive-web-apps' ) ?>
		&nbsp;<code><?php echo esc_html(superpwa_get_offline_page()); ?></code>
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
				<?php esc_html_e( 'Follow Device Orientation', 'super-progressive-web-apps' ); ?>
			</option>
			<option value="1" <?php if ( isset( $settings['orientation'] ) ) { selected( $settings['orientation'], 1 ); } ?>>
				<?php esc_html_e( 'Portrait', 'super-progressive-web-apps' ); ?>
			</option>
			<option value="2" <?php if ( isset( $settings['orientation'] ) ) { selected( $settings['orientation'], 2 ); } ?>>
				<?php esc_html_e( 'Landscape', 'super-progressive-web-apps' ); ?>
			</option>
		</select>
	</label>
	
	<p class="description">
		<?php echo esc_html__('Set the orientation of your app on devices. When set to ', 'super-progressive-web-apps').'<code>'. esc_html__('Follow Device Orientation', 'super-progressive-web-apps').'</code>'. esc_html__(' your app will rotate as the device is rotated.', 'super-progressive-web-apps'); ?>
		
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
				<?php esc_html_e( 'Full Screen', 'super-progressive-web-apps' ); ?>
			</option>
			<option value="1" <?php if ( isset( $settings['display'] ) ) { selected( $settings['display'], 1 ); } ?>>
				<?php esc_html_e( 'Standalone', 'super-progressive-web-apps' ); ?>
			</option>
			<option value="2" <?php if ( isset( $settings['display'] ) ) { selected( $settings['display'], 2 ); } ?>>
				<?php esc_html_e( 'Minimal UI', 'super-progressive-web-apps' ); ?>
			</option>
			<option value="3" <?php if ( isset( $settings['display'] ) ) { selected( $settings['display'], 3 ); } ?>>
				<?php esc_html_e( 'Browser', 'super-progressive-web-apps' ); ?>
			</option>
		</select>
	</label>
	
	<p class="description">
		<?php echo esc_html__( 'Display mode decides what browser UI is shown when your app is launched', 'super-progressive-web-apps' ) ?>.
		&nbsp;<code><?php echo esc_html__( 'Standalone','super-progressive-web-apps') ?></code>&nbsp;<?php echo esc_html__( 'is default','super-progressive-web-apps') ?>.&nbsp;
		<a href="<?php echo esc_attr('https://superpwa.com/doc/web-app-manifest-display-modes/?utm_source=superpwa-plugin&utm_medium=settings-display') ?>" target="_blank"><?php echo esc_html__( "What's the difference?",'super-progressive-web-apps') ?> &rarr;</a>
		
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
				<?php esc_html_e( 'LTR', 'super-progressive-web-apps' ); ?>
			</option>
			<option value="1" <?php if ( isset( $settings['text_dir'] ) ) { selected( $settings['text_dir'], 1 ); } ?>>
				<?php esc_html_e( 'RTL', 'super-progressive-web-apps' ); ?>
			</option>
		</select>
	</label>
	
	<p class="description">
		<?php echo esc_html__( 'The text direction of your PWA', 'super-progressive-web-apps' ); ?>
	</p>

	<?php
}

function superpwa_prefer_related_applications_cb() {

	// Get Settings
	$settings = superpwa_get_settings(); ?>
	<fieldset>
		<input type="checkbox" name="superpwa_settings[prefer_related_applications]" class="superpwa_related_app regular-text" value="1" <?php if ( isset( $settings['prefer_related_applications'] ) && ( $settings['prefer_related_applications'] == true ) ) echo 'checked';?>/>
	</fieldset>
	<?php
}

function superpwa_related_applications_cb() {

	// Get Settings
	$settings = superpwa_get_settings(); ?>
	<fieldset>
		<label for="superpwa_settings[related_applications]"><?php esc_html_e( 'PlayStore App ID', 'super-progressive-web-apps' ); ?></label>&nbsp;
		<input type="text" name="superpwa_settings[related_applications]" class="superpwa_related_applications regular-text" placeholder="com.example.app" value="<?php if ( isset( $settings['related_applications'] ) && ( ! empty($settings['related_applications']) ) ) echo esc_attr($settings['related_applications']); ?>"/>
	</fieldset>
	<fieldset>
		<label for="superpwa_settings[related_applications_ios]"><?php esc_html_e( 'AppStore App ID', 'super-progressive-web-apps' ); ?></label>&nbsp;
		<input type="text" name="superpwa_settings[related_applications_ios]" placeholder="id123456789" class="regular-text" value="<?php if ( isset( $settings['related_applications_ios'] ) && ( ! empty($settings['related_applications_ios']) ) ) echo esc_attr($settings['related_applications_ios']); ?>"/>
	</fieldset>

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
		?>
		<p><span class="dashicons dashicons-yes" style="color: #46b450;"></span><?php echo esc_html__( 'Manifest generated successfully. You can', 'super-progressive-web-apps' ) ?>
		<a href="<?php echo esc_url(superpwa_manifest( 'src' )); ?>" target="_blank"><?php echo esc_html__( 'See it here', 'super-progressive-web-apps' ) ?> &rarr;</a></p>
		<?php
	} else {
		?>
		<p><span class="dashicons dashicons-no-alt" style="color: #dc3232;"></span><?php echo esc_html__( 'Manifest generation failed', 'super-progressive-web-apps' ) ?>.&nbsp;
		<a href="<?php echo esc_url('https://superpwa.com/doc/fixing-manifest-service-worker-generation-failed-error/?utm_source=superpwa-plugin&utm_medium=settings-status-no-manifest'); ?>" target="_blank"><?php echo esc_html__( 'Fix it', 'super-progressive-web-apps' ) ?> &rarr;</a></p>
		<?php
		
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
	if ( superpwa_file_exists( superpwa_sw( 'src' ) . superpwa_nginx_server_fix( superpwa_sw( 'src' ) ) ) || superpwa_generate_sw() ){
		?>
		<p><span class="dashicons dashicons-yes" style="color: #46b450;"></span><?php echo esc_html__( 'Service worker generated successfully', 'super-progressive-web-apps' ) ?>.&nbsp;
		<a href="<?php echo esc_url(superpwa_sw( 'src' )); ?>" target="_blank"><?php echo esc_html__( 'See it here', 'super-progressive-web-apps' ) ?> &rarr;</a></p>
		<?php
	} else {
		?>
		<p><span class="dashicons dashicons-no-alt" style="color: #dc3232;"></span><?php echo esc_html__( 'Service worker generation failed', 'super-progressive-web-apps' ) ?>.&nbsp;
		<a href="<?php echo esc_url('https://superpwa.com/doc/fixing-manifest-service-worker-generation-failed-error/?utm_source=superpwa-plugin&utm_medium=settings-status-no-sw'); ?>" target="_blank"><?php echo esc_html__( 'Fix it', 'super-progressive-web-apps' ) ?> &rarr;</a></p>
		<?php
	}
}

/**
 * HTTPS Status
 *
 * @since 1.2
 */
function superpwa_https_status_cb() {

	if ( is_ssl() ) {
		?>
		<p><span class="dashicons dashicons-yes" style="color: #46b450;"></span><?php echo esc_html__( 'Your website is served over HTTPS', 'super-progressive-web-apps' ) ?>.</p>
		<?php
	} else {
		?>
		<p><span class="dashicons dashicons-no-alt" style="color: #dc3232;"></span><?php echo esc_html__( 'Progressive Web Apps require that your website is served over HTTPS. Please contact your host to add a SSL certificate to your domain', 'super-progressive-web-apps' ) ?>.</p>
		<?php
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
	<label for="superpwa_settings[disable_add_to_home]"><?php esc_html_e('Remove default banner', 'super-progressive-web-apps') ?></label>
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
		<?php
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo wp_dropdown_pages( array( 
				'name' => 'superpwa_settings[shortcut_url]', 
				'echo' => 0, 
				'show_option_none' => __( 'Select Page' ), 
				'option_none_value' => '0', 
				'selected' =>  isset($settings['shortcut_url']) ? $settings['shortcut_url'] : '',
			)); ?>
		</label>
		
		<p class="description">
			<?php echo esc_html__( 'Specify the page to load when the application is launched via Shortcut.', 'super-progressive-web-apps' ); ?>
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
 * Enable or disable the offline message
 *
 * @since 2.1.5
 */ 
function superpwa_offline_message_setting_cb() {
	// Get Settings
	$settings = superpwa_get_settings();
	$offline_message_checked = '';
	if(isset( $settings['offline_message'] ) && $settings['offline_message'] == 1){
		$offline_message_checked = 'checked="checked';
	}
	?><input type="checkbox" name="superpwa_settings[offline_message]" id="superpwa_settings[offline_message]" value="1" <?php echo esc_attr($offline_message_checked); ?> data-uncheck-val="0">
	<input size="50" type="text" name="superpwa_settings[offline_message_txt]" id="superpwa_settings[offline_message_txt]" value="<?php echo !empty($settings['offline_message_txt'])?esc_html($settings['offline_message_txt']):'You are currently offline.';?>" <?php echo isset( $settings['offline_message'] ) && $settings['offline_message'] == 1 ? "" : 'style="display:none;"'?> >
	<p><?php echo esc_html__('To check whether user is offline and display message you are offline', 'super-progressive-web-apps'); ?></p>
	<script>
		let offline_message = document.getElementById('superpwa_settings[offline_message]');
		if(offline_message){
			offline_message.addEventListener('change', function(e){
                if(e.target.checked){
                    document.getElementById('superpwa_settings[offline_message_txt]').style.display = 'inline-block';
                }else{
                    document.getElementById('superpwa_settings[offline_message_txt]').style.display = 'none';
                }
            });
		}
    </script>
	</script>
	<?php
}

/**
 * Enable or disable the Prefetch
 *
 * @since 2.2.24
 */ 
function superpwa_prefetch_manifest_setting_cb() {
	// Get Settings
	$settings = superpwa_get_settings();
	$prefetch_manifest_checked = '';
	if(isset( $settings['prefetch_manifest'] ) && $settings['prefetch_manifest'] == 1){
		$prefetch_manifest_checked = 'checked="checked';
	}
	?><input type="checkbox" name="superpwa_settings[prefetch_manifest]" id="superpwa_settings[prefetch_manifest]" value="1" <?php echo esc_attr($prefetch_manifest_checked); ?> data-uncheck-val="0">
	<p><?php echo esc_html__('Prefetch manifest URLs provides some control over the request priority', 'super-progressive-web-apps'); ?></p>
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

function superpwa_role_based_access_cb(){
	if( function_exists('is_super_admin') &&  is_super_admin() ){
		$settings = superpwa_get_settings(); 
		$user_roles = superpwa_get_user_roles(); 
		?>
		<label>
			<select id="superpwa_role_based_access" class="regular-text" name="superpwa_settings[superpwa_role_based_access][]" multiple="multiple">
				<?php
					foreach ($user_roles as $key => $opval) {
						$selected = "";
						if (isset($settings['superpwa_role_based_access']) && in_array($key,$settings['superpwa_role_based_access']) || $key == 'administrator') {
							$selected = "selected";
						}
						?>
						<option value="<?php echo esc_attr($key);?>" <?php echo esc_attr($selected);?>><?php echo esc_html($opval); ?></option>
					<?php }
				?>
			</select>
		</label>
		<p>
		<?php echo esc_html__('Choose the users whom you want to allow full access of this plugin','super-progressive-web-apps');?> </p>
		<?php
	} 
}

function superpwa_reset_settings_cb(){		
	?>              
        <button class="button superpwa-reset-settings">
            <?php echo esc_html__('Reset','super-progressive-web-apps'); ?>
        </button>
        
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
        }else{ echo esc_html(SUPERPWA_VERSION); } ?>"></label>      
        <code><?php echo esc_html__('Current Version', 'super-progressive-web-apps').' '.esc_html(SUPERPWA_VERSION); ?></code>
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
	if ( ! current_user_can( superpwa_current_user_can() ) ) {
        return;
    }
	
	// Handing save settings
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Reason: We are not processing form information.
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
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Reason: We are not processing form information.
				if( $_GET['page'] == 'superpwa' ) {
					wp_enqueue_style( 'superpwa-license-panel-css', SUPERPWA_PRO_PATH_SRC . '/assets/inc/css/superpwa-pro-license-data.css', array() , SUPERPWA_PRO_VERSION );
				}
			}
    }
} ?>
		<h1><?php echo esc_html__('Super Progressive Web Apps', 'super-progressive-web-apps'); ?> <sup><?php echo esc_html(SUPERPWA_VERSION); ?></sup></h1>
		
		<form action="<?php echo esc_url(admin_url("options.php")); ?>" method="post" enctype="multipart/form-data">		
			<?php
			// Output nonce, action, and option_page fields for a settings page.
			settings_fields( 'superpwa_settings_group' );
			$addon_page = admin_url( 'admin.php?page=superpwa-addons');
			?>
			<div class="spwa-tab">
			  <a id="spwa-default" class="spwa-tablinks" data-href="no" href="#general-settings" onclick="openCity(event, 'settings')"><?php echo esc_html__('Settings', 'super-progressive-web-apps'); ?></a>
			  <a class="spwa-tablinks" id="spwa-feature" href="<?php echo esc_url($addon_page);  ?>" data-href="yes"><?php echo esc_html__('Features (Addons)', 'super-progressive-web-apps'); ?></a>
			  <a class="spwa-tablinks" id="spwa-advance" href="#advance-settings" onclick="openCity(event, 'advance')" data-href="no"><?php echo esc_html__('Advanced', 'super-progressive-web-apps'); ?></a>
			  <a class="spwa-tablinks" id="spwa-support" href="#support-settings" onclick="openCity(event, 'support')" data-href="no"><?php echo esc_html__('Help & Support', 'super-progressive-web-apps'); ?></a>
			  <?php if( defined('SUPERPWA_PRO_VERSION') ){  ?>
			  <a class="spwa-tablinks" id="spwa-license" href="#license-settings" onclick="openCity(event, 'superpwa_pro_license')" data-href="no"><?php echo esc_html__('License', 'super-progressive-web-apps'); ?> <?php echo (superpwa_license_expire_warning()? "<span class='superpwa_pro_icon dashicons dashicons-warning superpwa_pro_alert' style='color: #ffb229;left: 3px;position: relative;'></span>":""); ?></a>
			  <?php } ?>
			  <?php if(!defined('SUPERPWA_PRO_VERSION')){ ?>
				<a class="spwa-tablinks" id="spwa-upgrade2pro" style="background: #ff4c4c;color: #ffffff;float: right; font-weight: 700; padding: 16px 25px" href="<?php echo esc_url(admin_url('admin.php?page=superpwa-upgrade')); ?>" onclick="openCity(event, 'superpwa-upgrade')" data-href="no"><?php echo esc_html__( 'Upgrade to PRO', 'super-progressive-web-apps' ); ?></a>
			  <?php } ?>
			</div>
			<span id="alert-warning" style=" margin-top: 10px; display: none; padding: 10px;background-color: #ff9800;color: white;"> <?php esc_html_e( 'Please Save the settings before moving to other tabs', 'super-progressive-web-apps' ); ?> </span>
			<div id="settings" class="spwa-tabcontent">
			 <?php
			  	// Basic Application Settings
				do_settings_sections( 'superpwa_basic_settings_section' );	// Page slug
				
				// Status
				do_settings_sections( 'superpwa_pwa_status_section' );	// Page slug
				// Output save settings button
				echo '<style>.submit{float:left;}</style>';
				submit_button( esc_html__('Save Settings', 'super-progressive-web-apps') );
				if(!defined('SUPERPWA_PRO_VERSION')){
					echo '<a class="button" style="background: black;color: white;margin: 30px 0px 0px 25px;" href="'.esc_url(admin_url('admin.php?page=superpwa-upgrade')).'" target="_blank">'.esc_html__( 'Go PRO', 'super-progressive-web-apps').'</a>';
				}
			?>
			</div>
			<div id="advance" class="spwa-tabcontent">
			 <?php
			  	// Advance
			  	do_settings_sections( 'superpwa_pwa_advance_section' );	// Page slug
			  	// Output save settings button
				echo '<style>.submit{float:left;}</style>';
				submit_button( esc_html__('Save Settings', 'super-progressive-web-apps') );
				if(!defined('SUPERPWA_PRO_VERSION')){
					echo '<a class="button" style="background: black;color: white;margin: 30px 0px 0px 25px;" href="'.esc_url(admin_url('admin.php?page=superpwa-upgrade')).'" target="_blank">'.esc_html__( 'Go PRO', 'super-progressive-web-apps').'</a>';
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