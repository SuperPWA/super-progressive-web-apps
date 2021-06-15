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
	$icons = superpwa_get_pwa_icons();
	
	foreach( $icons as $icon ) {
		$tags .= '<link rel="apple-touch-icon" sizes="' . $icon['sizes'] . '" href="' . $icon['src'] . '">' . PHP_EOL;
	}
    //Ios splash screen
    $iosScreenSetting = get_option( 'superpwa_apple_icons_uploaded' );
    if( $iosScreenSetting && isset($iosScreenSetting['ios_splash_icon']) && !empty($iosScreenSetting['ios_splash_icon']) ) {
        $iconsInfo = apple_splashscreen_files_data();
        foreach ( $iosScreenSetting['ios_splash_icon'] as $key => $value ) {
            if( !empty($value) && !empty($key) && isset($iconsInfo[$key]) ) {
                $screenData = $iconsInfo[$key];
                echo '<link rel="apple-touch-startup-image" media="screen and (device-width: '.$screenData['device-width'].') and (device-height: '.$screenData['device-height'].') and (-webkit-device-pixel-ratio: '.$screenData['ratio'].') and (orientation: '.$screenData['orientation'].')" href="'.$value.'"/>'."\n";
            }//if closed
        }//foreach closed
    }
    // Get settings
    $settings = superpwa_get_settings();
    
    $tags .= '<meta name="apple-mobile-web-app-title" content="'.esc_attr($settings['app_name']).'">' . PHP_EOL;
    $tags .= '<meta name="application-name" content="'.esc_attr($settings['app_name']).'">' . PHP_EOL;
    $tags .= '<meta name="apple-mobile-web-app-capable" content="yes">' . PHP_EOL;
    $tags .= '<meta name="apple-mobile-web-app-status-bar-style" content="black">' . PHP_EOL;
    $tags .= '<meta name="mobile-web-app-capable" content="yes">' . PHP_EOL;
    $tags .= '<meta name="apple-touch-fullscreen" content="yes">' . PHP_EOL;
	
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
				'mode'		        => ''
			);
	
	return get_option( 'superpwa_apple_icons_settings', $defaults );
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
			esc_html__('Splash Screen Image', 'super-progressive-web-apps'),	// Title
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
    $splashIconsScreens = apple_splashscreen_files_data();
    $iosScreenSetting = get_option( 'superpwa_apple_icons_uploaded' ) ; //New generated icons
    ?>
    <input type="file" id="upload_apple_function" accept="image/png">
    <p class="description"><?php echo esc_html__('Must select PNG images only', 'super-progressive-web-apps'); ?> </p><br/>
    <?php
        $a = 'style="display:none"';$src = '';
        if(isset($iosScreenSetting['ios_splash_icon']) && !empty($iosScreenSetting['ios_splash_icon'])){
            $a = '';
            $src = end($iosScreenSetting['ios_splash_icon']);
        } 
    ?>
    <p id="aft_img_gen"> </p>
    <img src="<?php echo $src; ?>" id="thumbnail" title="<?php echo esc_attr__('Currently selected splash screen', 'super-progressive-web-apps'); ?>"  width="100">

    <script id="iosScreen-data" type="application/json"><?php echo json_encode($splashIconsScreens);?></script>
    <br/>
    <?php
}

function superpwa_apple_icons_splash_with_centre_screen_cb() {
    $splashIcons = superpwa_apple_icons_get_settings();
    echo '<input type="checkbox" id="center-mode" name="mode" value="center" name="superpwa_apple_icons_settings[screen_centre_icon]" '.(isset( $splashIcons['screen_centre_icon']) && $splashIcons['screen_centre_icon']=='center'? 'checked': '') .'/>';
}

/**
 * Splash Screen Pro 
 *
 * @since 	2.1.7
 */
function superpwa_apple_icons_splash_color_screen_cb() {
    ?>
    <input type="text" name="superpwa_apple_icons_settings[background_color]"  class="superpwa-colorpicker" id="ios-splash-color" value="<?php echo isset($splashIcons['screen_icon'])? $splashIcons['screen_icon']: '#cdcdcd' ?>">
    <?php
}

/**
 * Apple Touch Icon & splash screen require tags data
 *
 * @since 1.7
 */ 
function apple_splashscreen_files_data(){
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
            );
    return $iosSplashData;
}

function superpwa_load_admin_scripts($hooks){
    if( !in_array($hooks, array('superpwa_page_superpwa-apple-icons', 'super-pwa_page_superpwa-apple-icons')) ) {
        return false;
    }
    wp_enqueue_media();
    wp_register_script('superpwa-admin-apple-script',SUPERPWA_PATH_SRC .'/admin/js/jszip.min.js', array('superpwa-main-js'), SUPERPWA_VERSION, true);
    wp_enqueue_script('superpwa-admin-apple-script'); 
    wp_localize_script( 'superpwa-admin-apple-script', 'superpwaIosScreen', 
                        array('nonce'=> wp_create_nonce( 'superpwaIosScreenSecurity' )) );


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
	$settings['background_color'] = sanitize_text_field( $settings['background_color'] ) == '' ? '' : sanitize_text_field( $settings['background_color'] );
    if($settings['ios_splash_icon']){
        print_r($settings['ios_splash_icon']);die;;
    }

    return $settings;
}
/**
 * Apple Touch Icon & splash screen UI renderer
 *
 * @since 1.7
 */ 
function superpwa_apple_icons_interface_render() {
	
	// Authentication
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	
	// Handing save settings
	if ( isset( $_GET['settings-updated'] ) ) {
		
		// Add settings saved message with the class of "updated"
		add_settings_error( 'superpwa_settings_group', 'superpwa_apple_icons_settings_saved_message', __( 'Settings Saved.', 'super-progressive-web-apps' ), 'updated' );
		
		// Show Settings Saved Message
		settings_errors( 'superpwa_settings_group' );
	}
	// Get add-on info
	$addon_utm_tracking = superpwa_get_addons( 'apple_touch_icons' );
	
	?>
	
	<div class="wrap">
    <!--Duplicate h1 To show saved settings message above h1 tag -->
        <h1 style="display: none" ><?php _e( 'Apple touch icons & Splash Screen', 'super-progressive-web-apps' ); ?> <small>(<a href="<?php echo esc_url($addon_utm_tracking['link']) . '?utm_source=superpwa-plugin&utm_medium=utm-tracking-settings'?>"><?php echo esc_html__( 'Docs', 'super-progressive-web-apps' ); ?></a>)</small></h1>	
		<h1><?php _e( 'Apple touch icons & Splash Screen', 'super-progressive-web-apps' ); ?> <small>(<a href="<?php echo esc_url($addon_utm_tracking['link']) . '?utm_source=superpwa-plugin&utm_medium=utm-tracking-settings'?>"><?php echo esc_html__( 'Docs', 'super-progressive-web-apps' ); ?></a>)</small></h1>
		
		<form action="options.php" method="post" enctype="multipart/form-data">		
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
	<?php
}

function superpwa_splashscreen_uploader(){

    // Authentication
    if ( ! current_user_can( 'manage_options' ) ) {
       return;
    }

    if( (!isset($_POST['security_nonce'])) || (isset($_POST['security_nonce']) && !wp_verify_nonce( $_POST['security_nonce'], 'superpwaIosScreenSecurity' )) ) {
        echo json_encode(array('status'=>400, 'message'=>'security nonce not matched'));die;
    }
    if(isset($_FILES['file']['type']) && $_FILES['file']['type']!='application/zip'){
        echo json_encode(array('status'=>500, 'message'=>'file type not matched'));die;
    }
    if(isset($_FILES['file']['error']) && $_FILES['file']['error']!='0'){
        echo json_encode(array('status'=>500, 'message'=>'file contains error'));die;
    }

    $upload = wp_upload_dir();
    $path =  $upload['basedir']."/superpwa-splashIcons/";
    $subpath = $upload['basedir']."/superpwa-splashIcons/super_splash_screens/";
    wp_mkdir_p($path);
    file_put_contents($path.'/index.html','');
    file_put_contents($subpath.'/index.html','');
    WP_Filesystem();
    $zipFileName = $path."/splashScreen.zip";
    $moveFile = move_uploaded_file($_FILES['file']['tmp_name'], $zipFileName);
    if($moveFile && spwa_zip_allowed_extensions($zipFileName,['png'])){
        $result = unzip_file($zipFileName, $path);
        unlink($zipFileName);    
    }else{
        echo json_encode(array('status'=>500, 'message'=>'Files are not uploading'));die;
    }

    $pathURL = $upload['baseurl']."/superpwa-splashIcons/super_splash_screens/";
    $iosScreenData = apple_splashscreen_files_data(); 
    $iosScreenSetting = (array)get_option( 'superpwa_apple_icons_uploaded' ) ;
    foreach ($iosScreenData as $key => $value) {
         $iosScreenSetting['ios_splash_icon'][$key] = $pathURL.$value['file'];
    }
    update_option( 'superpwa_apple_icons_uploaded', $iosScreenSetting ) ;
	
	echo json_encode(array("status"=>200, "message"=> "Splash screen uploaded successfully"));
	 	  die;
}
function spwa_zip_allowed_extensions($zip_path, array $allowed_extensions) {
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