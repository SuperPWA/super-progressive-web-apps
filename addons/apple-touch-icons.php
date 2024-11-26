<?php
/**
 * Apple Touch Icons
 *
 * @since 1.8
 * 
 * @function	superpwa_ati_add_apple_touch_icons()	Add Apple Touch Icons to the wp_head
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Add Apple Touch Icons to the wp_head
 * 
 * Uses the Application Icon and Splash Screen Icon for SuperPWA > Settings
 * and adds them to wp_head using the superpwa_wp_head_tags filter.
 * 
 * @param (string) $tags HTML element tags passed on by superpwa_wp_head_tags
 * 
 * @return (string) Appends the Apple Touch Icons to the existing tag string
 * 
 * @since 1.8
 */
function superpwa_ati_add_apple_touch_icons( $tags ) {
	
    // Get the icons added via SuperPWA > Settings
    //$icons = superpwa_get_pwa_icons();
        // Get settings
    $settings = superpwa_get_settings();

    $splashIcons = superpwa_apple_icons_get_settings();

    if(isset($splashIcons['status_bar_style']) && !empty($splashIcons['status_bar_style'])){
         $status_bar_style = $splashIcons['status_bar_style'];
     }else{
        $status_bar_style = 'default';
     }
    $tags .= '<meta name="mobile-web-app-capable" content="yes">' . PHP_EOL;
    $tags .= '<meta name="apple-touch-fullscreen" content="yes">' . PHP_EOL;
    $tags .= '<meta name="apple-mobile-web-app-title" content="'.esc_attr($settings['app_name']).'">' . PHP_EOL;
    $tags .= '<meta name="application-name" content="'.esc_attr($settings['app_name']).'">' . PHP_EOL;
    $tags .= '<meta name="apple-mobile-web-app-capable" content="yes">' . PHP_EOL;
    $tags .= '<meta name="apple-mobile-web-app-status-bar-style" content="'.esc_attr($status_bar_style).'">' . PHP_EOL;

    if(isset($settings['icon']) && !empty($settings['icon']))
    {
        $tags .= '<link rel="apple-touch-icon"  href="' .esc_url($settings['icon']) . '">' . PHP_EOL; 
        $tags .= '<link rel="apple-touch-icon" sizes="192x192" href="' .esc_url($settings['icon']) . '">' . PHP_EOL; 
    }
    //Ios splash screen
    $iosScreenSetting = get_option( 'superpwa_apple_icons_uploaded' );
    if( $iosScreenSetting && isset($iosScreenSetting['ios_splash_icon']) && !empty($iosScreenSetting['ios_splash_icon']) ) {
        $iconsInfo = superpwa_apple_splashscreen_files_data();
        foreach ( $iosScreenSetting['ios_splash_icon'] as $key => $value ) {
            if( !empty($value) && !empty($key) && isset($iconsInfo[$key]) ) {
                $screenData = $iconsInfo[$key];
                $tags .= '<link rel="apple-touch-startup-image" media="screen and (device-width: '.esc_attr($screenData['device-width']).') and (device-height: '.esc_attr($screenData['device-height']).') and (-webkit-device-pixel-ratio: '.esc_attr($screenData['ratio']).') and (orientation: '.esc_attr($screenData['orientation']).')" href="'.esc_url($value).'"/>'."\n";
            }//if closed
        }//foreach closed
    }

    return $tags;
}
add_filter( 'superpwa_wp_head_tags', 'superpwa_ati_add_apple_touch_icons' );

/**
 * Remove apple-touch-icon added by WordPress in heading (site_icon_meta_tags)
 *
 * Wordpress introduce this filter since 4.3.0 (site_icon_meta_tags)
 * @since 2.1.6 introduce
 * @param (string) $tags HTML element tags passed on by site_icon_meta_tags
 * 
 * @return (string) Remove the Apple Touch Icons from the existing tag string
 */
function superpwa_remove_site_apple_touch_icon($meta_tags) {
	if(is_customize_preview() && is_admin()){
            return $meta_tags;
        }
        foreach ($meta_tags as $key => $value) {
            if(strpos($value, 'apple-touch-icon') !== false){
                unset($meta_tags[$key]);
            }
        }
        return $meta_tags;
}
add_filter( 'site_icon_meta_tags', 'superpwa_remove_site_apple_touch_icon', 0 );


/**
 * Get UTM Tracking settings
 *
 * @since 1.7
 */
function superpwa_apple_icons_get_settings() {
	
	$defaults = array(
                'background_color'  => '#cdcdcd',
				'screen_centre_icon'=> '',
                'status_bar_style'  => 'default'
			);
	
	return get_option( 'superpwa_apple_icons_settings',$defaults);
}
/**
 * Register Apple icons & splash screen settings
 *
 * @since 	2.1.7
 */
function superpwa_apple_icons_register_settings() {
    // Register Setting
	register_setting( 
		'superpwa_apple_icons_settings_group',		 // Group name
		'superpwa_apple_icons_settings', 			// Setting name = html form <input> name on settings form
		'superpwa_apple_icons_validater_sanitizer'	// Input validator and sanitizer
	);

    // Apple icons
    add_settings_section(
        'superpwa_apple_icons_section',				// ID
        __return_false(),								// Title
        'superpwa_apple_icons_section_cb',				// Callback Function
        'superpwa_apple_icons_section'					// Page slug
    );
        // Splash screen URL
		add_settings_field(
			'superpwa_apple_icons_splash_screen',						// ID
			esc_html__('Touch Icons', 'super-progressive-web-apps'),	// Title
			'superpwa_apple_icons_splash_screen_cb',					// CB
			'superpwa_apple_icons_section',						// Page slug
			'superpwa_apple_icons_section'							// Settings Section ID
		);

        // Splash screen URL
		add_settings_field(
			'superpwa_apple_icons_splash_screen_center_background_color',						// ID
			esc_html__('Centralize Image with Background Color', 'super-progressive-web-apps'),	// Title
			'superpwa_apple_icons_splash_with_centre_screen_cb',					// CB
			'superpwa_apple_icons_section',						// Page slug
			'superpwa_apple_icons_section'							// Settings Section ID
		);
        // Splash screen URL
		add_settings_field(
			'superpwa_apple_icons_splash_color_screen',						// ID
			esc_html__('Background Color', 'super-progressive-web-apps'),	// Title
			'superpwa_apple_icons_splash_color_screen_cb',					// CB
			'superpwa_apple_icons_section',						// Page slug
			'superpwa_apple_icons_section'							// Settings Section ID
		);
      // Display
        add_settings_field(
            'superpwa_apple_icons_status_bar_style',                                 // ID
            __('Mobile App Status Bar Style', 'super-progressive-web-apps'),        // Title
            'superpwa_apple_icons_status_bar_style_cb',                              // CB
            'superpwa_apple_icons_section',                      // Page slug
            'superpwa_apple_icons_section'                       // Settings Section ID
        );
}
add_action( 'admin_init', 'superpwa_apple_icons_register_settings' );

/**
 * Upload the image of splash screen
 *
 * @since 	2.1.7
 */
function superpwa_apple_icons_section_cb() {
    echo esc_html__( 'Select png icon and background colour to show in splash screen, we automatically create images for all multiple other screen sizes', 'super-progressive-web-apps' );
}

/**
 * Upload the image of splash screen
 *
 * @since 	2.1.7
 */
function superpwa_apple_icons_splash_screen_cb() {
    $splashIcons = superpwa_apple_icons_get_settings();
    $splashIconsScreens = superpwa_apple_splashscreen_files_data();
    $iosScreenSetting = get_option( 'superpwa_apple_icons_uploaded' ) ; //New generated icons
    ?>
    <input type="file" id="upload_apple_function" accept="image/png">
    <p class="description"><?php echo esc_html__('Must select PNG images only', 'super-progressive-web-apps'); ?> </p><br/>
    <?php
        $a = 'style="display:none"';$src = '';
        if(isset($iosScreenSetting['ios_splash_icon']) && !empty($iosScreenSetting['ios_splash_icon'])){
            $a = '';
            $src = end($iosScreenSetting['ios_splash_icon']).'?nocache='.uniqid();
        } 
    ?>
    <p id="aft_img_gen"> </p>
    <img src="<?php echo esc_url($src); ?>" id="thumbnail" title="<?php echo esc_attr__('Currently selected splash screen', 'super-progressive-web-apps'); ?>"  width="100">

    <script id="iosScreen-data" type="application/json"><?php echo wp_json_encode($splashIconsScreens); ?></script>
    <br/>
    <?php
}

function superpwa_apple_icons_splash_with_centre_screen_cb() {
    $splashIcons = superpwa_apple_icons_get_settings();

    echo '<input type="checkbox" id="center-mode"  value="center" name="superpwa_apple_icons_settings[screen_centre_icon]" '.(isset( $splashIcons['screen_centre_icon']) && $splashIcons['screen_centre_icon']=='center'? 'checked': '') .'/>';
}

/**
 * Splash Screen Pro 
 *
 * @since 	2.1.7
 */
function superpwa_apple_icons_splash_color_screen_cb() {
    $splashIcons = superpwa_apple_icons_get_settings();
    ?>
    <input type="text" name="superpwa_apple_icons_settings[background_color]"  class="superpwa-colorpicker" id="ios-splash-color" value="<?php echo (isset($splashIcons['screen_icon']) && !empty($splashIcons['screen_icon']))? esc_attr($splashIcons['screen_icon']): esc_attr($splashIcons['background_color']) ?>">
    <?php
}

/**
 * Splash Screen Pro 
 *
 * @since   2.1.17
 */
function superpwa_apple_icons_status_bar_style_cb() {
    $splashIcons = superpwa_apple_icons_get_settings();
    ?>
    <!-- Display Dropdown -->
    <label for="superpwa_apple_icons_settings[status_bar_style]">
        <select name="superpwa_apple_icons_settings[status_bar_style]" id="superpwa_apple_icons_settings[status_bar_style]">
            <option value="default" <?php if ( isset( $splashIcons['status_bar_style'] ) ) { selected( $splashIcons['status_bar_style'], 'default' ); } ?>>
                <?php esc_html_e( 'Default', 'super-progressive-web-apps' ); ?>
            </option>
            <option value="black" <?php if ( isset( $splashIcons['status_bar_style'] ) ) { selected( $splashIcons['status_bar_style'], 'black' ); } ?>>
                <?php esc_html_e( 'Black', 'super-progressive-web-apps' ); ?>
            </option>
            <option value="black-translucent" <?php if ( isset( $splashIcons['status_bar_style'] ) ) { selected( $splashIcons['status_bar_style'], 'black-translucent' ); } ?>>
                <?php esc_html_e( 'Black translucent', 'super-progressive-web-apps' ); ?>
            </option>
        </select>
    </label>
    <?php
}

/**
 * Apple Touch Icon & splash screen require tags data
 *
 * @since 1.7
 */ 
function superpwa_apple_splashscreen_files_data(){
    $iosSplashData = array(
            '1136x640'=> array("device-width"=> '320px', "device-height"=> "568px","ratio"=> 2,"orientation"=> "landscape","file"=> "icon_1136x640.png",'name'=> 'iPhone 5/iPhone SE'),
            '640x1136'=> array("device-width"=> '320px', "device-height"=> "568px","ratio"=> 2,"orientation"=> "portrait", "file"=> "icon_640x1136.png",'name'=> 'iPhone 5/iPhone SE'),
            '2688x1242'=>array("device-width"=> '414px', "device-height"=> "896px","ratio"=> 3,"orientation"=> "landscape", "file"=> "icon_2688x1242.png", 'name'=>'iPhone XS Max'),
            '1792x828'=> array("device-width"=> '414px', "device-height"=> "896px","ratio"=> 2, "orientation"=> "landscape", "file"=> "icon_1792x828.png", 'name'=>'iPhone XR'),
            '1125x2436'=>array("device-width"=> '375px', "device-height"=> "812px","ratio"=> 3,"orientation"=> 'portrait', "file"=>"icon_1125x2436.png", 'name'=> 'iPhone X/Xs'),
            '828x1792'=> array("device-width"=> "414px", "device-height"=> "896px","ratio"=> 2,"orientation"=> "portrait","file"=>"icon_828x1792.png",'name' => 'iPhone Xr'),
            '2436x1125'=> array("device-width"=> "375px","device-height"=> "812px","ratio"=> 3,"orientation"=> "landscape", "file"=>"icon_2436x1125.png", 'name'=> 'iPhone X/Xs'),
            '1242x2208'=> array("device-width"=> "414px","device-height"=> "736px","ratio"=> 3,"orientation"=> "portrait", "file"=>"icon_1242x2208.png", 'name'=> 'iPhone 6/7/8 Plus'),
            '2208x1242'=>array("device-width"=> "414px","device-height"=> "736px","ratio"=> 3,"orientation"=> "landscape", "file"=>"icon_2208x1242.png", 'name'=> 'iPhone 6/7/8 Plus'),
            '1334x750'=>array("device-width"=> "375px","device-height"=> "667px","ratio"=> 2,"orientation"=> "landscape", "file"=>"icon_1334x750.png", 'name'=> 'iPhone 6/7/8'),
            '750x1334'=>array("device-width"=> "375px","device-height"=> "667px","ratio"=> 2,"orientation"=> "portrait","file"=>"icon_750x1334.png", 'name'=> 'iPhone 6/7/8'),
            '2732x2048'=>array("device-width"=> "1024px","device-height"=>"1366px","ratio"=> 2,"orientation"=> "landscape","file"=>"icon_2732x2048.png", 'name'=> 'iPad Pro 12.9"'),
            '2048x2732'=>array("device-width"=> "1024px","device-height"=> "1366px","ratio"=> 2,"orientation"=> "portrait","file"=>"icon_2048x2732.png", 'name'=> 'iPad Pro 12.9"'),
            '2388x1668'=>array("device-width"=> "834px","device-height"=> "1194px","ratio"=> 2,"orientation"=> "landscape", "file"=>"icon_2388x1668.png",'name'=> 'iPad Pro 11"'),
            '1668x2388'=>array("device-width"=> "834px","device-height"=> "1194px","ratio"=> 2,"orientation"=> "portrait","file"=>"icon_1668x2388.png",'name'=> 'iPad Pro 11"'),
            '2224x1668'=>array("device-width"=> "834px", "device-height"=> "1112px","ratio"=> 2,"orientation"=>"landscape","file"=>"icon_2224x1668.png", 'name'=> 'iPad Pro 10.5"'),
            '1242x2688'=>array("device-width"=> "414px","device-height"=> "896px","ratio"=> 3, "orientation"=> "portrait","file"=>"icon_1242x2688.png", 'name' => 'iPhone Xs Max'),
            '1668x2224'=>array("device-width"=> "834px","device-height"=> "1112px","ratio"=> 2, "orientation"=> "portrait","file"=>"icon_1668x2224.png", 'name'=> 'iPad Pro 10.5"'),
            '1536x2048'=>array("device-width"=> "768px","device-height"=> "1024px","ratio"=> 2, "orientation"=> "portrait","file"=>"icon_1536x2048.png", 'name'=> 'iPad Mini/iPad Air'),
            '2048x1536'=>array("device-width"=> "768px","device-height"=> "1024px","ratio"=> 2,"orientation"=> "landscape","file"=>"icon_2048x1536.png", 'name'=> 'iPad Mini/iPad Air'),
            '1170x2532'=>array("device-width"=> "390px","device-height"=> "844px","ratio"=> 3,"orientation"=> "portrait","file"=>"icon_1170x2532.png", 'name'=> 'iPhone 12/13/14'),
            '2532x1170'=>array("device-width"=> "844px","device-height"=> "390px","ratio"=> 3,"orientation"=> "landscape","file"=>"icon_2532x1170.png", 'name'=> 'iPhone 12/13/14'),
            '2778x1284'=>array("device-width"=> "926px","device-height"=> "428px","ratio"=> 3,"orientation"=> "landscape","file"=>"icon_2778x1284.png", 'name'=> 'iPhone 12 Pro Max/13 Pro Max/14 Plus'),
            '1284x2778'=>array("device-width"=> "428px","device-height"=> "926px","ratio"=> 3,"orientation"=> "portrait","file"=>"icon_2532x1170.png", 'name'=> 'iPhone 12 Pro Max/13 Pro Max/14 Plus'),
            '2556x1179'=>array("device-width"=> "852px","device-height"=> "393px","ratio"=> 3,"orientation"=> "landscape","file"=>"icon_2556x1179.png", 'name'=> 'iPhone 14 Pro'),
            '1179x2556'=>array("device-width"=> "393px","device-height"=> "852px","ratio"=> 3,"orientation"=> "portrait","file"=>"icon_1179x2556.png", 'name'=> 'iPhone 14 Pro'),
            '2796x1290'=>array("device-width"=> "932px","device-height"=> "430px","ratio"=> 3,"orientation"=> "landscape","file"=>"icon_2796x1290.png", 'name'=> 'iPhone 14 Pro Max'),
            '1290x2796'=>array("device-width"=> "430px","device-height"=> "932px","ratio"=> 3,"orientation"=> "portrait","file"=>"icon_1290x2796.png", 'name'=> 'iPhone 14 Pro Max'),

            );
    return $iosSplashData;
}

function superpwa_load_admin_scripts($hooks){
    if( !in_array($hooks, array('superpwa_page_superpwa-apple-icons', 'super-pwa_page_superpwa-apple-icons')) && strpos($hooks, 'superpwa-apple-icons') == false ) {
        return false;
    }
    wp_enqueue_media();
    wp_register_script('superpwa-admin-apple-script',SUPERPWA_PATH_SRC .'/admin/js/jszip.min.js', array('superpwa-main-js'), SUPERPWA_VERSION, true);
    wp_enqueue_script('superpwa-admin-apple-script'); 
    wp_localize_script( 'superpwa-admin-apple-script', 'superpwaIosScreen', 
                        array(
                            'nonce'=> wp_create_nonce( 'superpwaIosScreenSecurity' ),
                            'max_file_size'=> ini_get('upload_max_filesize')
                        ) );


}
add_action( 'admin_enqueue_scripts', 'superpwa_load_admin_scripts' );

/**
 * Validate and sanitize user input
 *
 * @since 2.1.7
 */
function superpwa_apple_icons_validater_sanitizer( $settings ) {
    // Sanitize and validate campaign source. Campaign source cannot be empty.
	$settings['screen_icon'] = (isset($settings['screen_icon'])) ? sanitize_text_field( $settings['screen_icon'] ) : '';

    // Sanitize and validate campaign source. Campaign source cannot be empty.
	$settings['background_color'] = (isset($settings['background_color'])) ? sanitize_text_field( $settings['background_color'] ) : '';

    $settings['screen_centre_icon'] = (isset($settings['screen_centre_icon'])) ? sanitize_text_field( $settings['screen_centre_icon'] ) : '';

    $settings['status_bar_style'] = (isset($settings['status_bar_style'])) ? sanitize_text_field( $settings['status_bar_style'] ) : '';

    return $settings;
}
/**
 * Apple Touch Icon & splash screen UI renderer
 *
 * @since 1.7
 */ 
function superpwa_apple_icons_interface_render() {
	
	// Authentication
    if ( ! current_user_can( superpwa_current_user_can() ) ) {
        return;
    }
	
	// Handing save settings
    // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Reason: We are not processing form information.
	if ( isset( $_GET['settings-updated'] ) ) {
		
		// Add settings saved message with the class of "updated"
		add_settings_error( 'superpwa_settings_group', 'superpwa_apple_icons_settings_saved_message', __( 'Settings Saved.', 'super-progressive-web-apps' ), 'updated' );
		
		// Show Settings Saved Message
		settings_errors( 'superpwa_settings_group' );
	}
	// Get add-on info
	$addon_utm_tracking = superpwa_get_addons( 'apple_touch_icons' );

	superpwa_setting_tabs_styles();
	?>
	<div class="wrap">
    <!--Duplicate h1 To show saved settings message above h1 tag -->
        <h1 style="display: none" ><?php esc_html_e( 'Apple touch icons & Splash Screen', 'super-progressive-web-apps' ); ?> <small>(<a href="<?php echo esc_url($addon_utm_tracking['link']) . '?utm_source=superpwa-plugin&utm_medium=utm-tracking-settings'?>"><?php echo esc_html__( 'Docs', 'super-progressive-web-apps' ); ?></a>)</small></h1>	
		<h1><?php esc_html_e( 'Apple touch icons & Splash Screen', 'super-progressive-web-apps' ); ?> <small>(<a href="<?php echo esc_url($addon_utm_tracking['link']) . '?utm_source=superpwa-plugin&utm_medium=utm-tracking-settings'?>"><?php echo esc_html__( 'Docs', 'super-progressive-web-apps' ); ?></a>)</small></h1>

		  <?php superpwa_setting_tabs_html(); ?>
          
		<form action="<?php echo esc_url(admin_url("options.php")); ?>" method="post" enctype="multipart/form-data">		
			<?php
			// Output nonce, action, and option_page fields for a settings page.
			settings_fields( 'superpwa_apple_icons_settings_group' );
			
			// Status
			do_settings_sections( 'superpwa_apple_icons_section' );	// Page slug
			
            echo "<p id='superpwa-apple-splash-message'></p>";
			// Output save settings button
			submit_button( __('Save Settings', 'super-progressive-web-apps'), 'primary ', 'submit', true, array( 'data-type' => 'create_images', 'id' => 'submit_splash_screen') );
			?>
		</form>
	</div>
    <?php superpwa_newsletter_form(); ?>
	<?php
}

function superpwa_splashscreen_uploader(){

    // Authentication
    if ( ! current_user_can( 'manage_options' ) ) {
       return;
    }

    if( (!isset($_POST['security_nonce'])) || (isset($_POST['security_nonce']) && !wp_verify_nonce( $_POST['security_nonce'], 'superpwaIosScreenSecurity' )) ) {
        echo wp_json_encode(array('status'=>400, 'message'=>esc_html__('security nonce not matched','super-progressive-web-apps')));die;
    }
    
    if(isset($_FILES['file']['type']) && $_FILES['file']['type']!='application/zip'){
        echo wp_json_encode(array('status'=>500, 'message'=>esc_html__('file type not matched','super-progressive-web-apps')));die;
    }
    if(isset($_FILES['file']['error']) && $_FILES['file']['error']!='0'){
        echo wp_json_encode(array('status'=>500, 'message'=>esc_html__('file contains error','super-progressive-web-apps')));die;
    }

    $upload = wp_upload_dir();
    $path =  $upload['basedir']."/superpwa-splashIcons/";
    $subpath = $upload['basedir']."/superpwa-splashIcons/super_splash_screens/";
    wp_mkdir_p($path);

    if ( ! function_exists( 'wp_filesystem' ) ) {
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
    }
    
    // Initialize the WP_Filesystem global variable
    global $wp_filesystem;
    
    if ( ! WP_Filesystem() ) {
        WP_Filesystem();
    }
    
    $wp_filesystem->put_contents( $path . '/index.html', '', FS_CHMOD_FILE ); // FS_CHMOD_FILE is the default file permission
    $wp_filesystem->put_contents( $subpath . '/index.html', '', FS_CHMOD_FILE );
    
    $zipFileName = $path."/splashScreen.zip";
    // phpcs:ignore Generic.PHP.ForbiddenFunctions.Found
    $moveFile = move_uploaded_file($_FILES['file']['tmp_name'], $zipFileName);

    if($moveFile && superpwa_zip_allowed_extensions($zipFileName,['png'])){
        $result = unzip_file($zipFileName, $path);
        wp_delete_file($zipFileName);    
    }else{
        echo wp_json_encode(array('status'=>500, 'message'=>esc_html__('Files are not uploading','super-progressive-web-apps')));die;
    }

    $pathURL = $upload['baseurl']."/superpwa-splashIcons/super_splash_screens/";
    $iosScreenData = superpwa_apple_splashscreen_files_data(); 
    $iosScreenSetting = (array)get_option( 'superpwa_apple_icons_uploaded' ) ;
    foreach ($iosScreenData as $key => $value) {
         $iosScreenSetting['ios_splash_icon'][$key] = sanitize_url($pathURL.$value['file']);
    }
    update_option( 'superpwa_apple_icons_uploaded', $iosScreenSetting ) ;
	
	echo wp_json_encode(array("status"=>200, "message"=> esc_html__('Splash screen uploaded successfully','super-progressive-web-apps')));
	 	  die;
}
function superpwa_zip_allowed_extensions($zip_path, array $allowed_extensions) {
    $zip = new ZipArchive;
    $zip->open($zip_path);

    for ($i = 0; $i < $zip->numFiles; $i++) {
        $stat = $zip->statIndex( $i );
        $ext = pathinfo($stat['name'], PATHINFO_EXTENSION);
    
        // Skip folders name (but their content will be checked)
        if ($ext === '' && substr($stat['name'], -1) === '/')
            continue;
        
        if (!in_array(strtolower($ext), $allowed_extensions))
            return false;
    }
    return true;
}
add_action('wp_ajax_superpwa_splashscreen_uploader', 'superpwa_splashscreen_uploader');