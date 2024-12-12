<?php
/**
 * Manifest related functions of SuperPWA
 *
 * @since 1.0
 * 
 * @function	superpwa_manifest()						Manifest filename, absolute path and link
 * @function	superpwa_generate_manifest()			Generate and write manifest
 * @function	superpwa_add_manifest_to_wp_head()		Add manifest to header (wp_head)
 * @function	superpwa_register_service_worker()		Register service worker in the footer (wp_footer)
 * @function	superpwa_delete_manifest()				Delete manifest
 * @function 	superpwa_get_pwa_icons()				Get PWA Icons
 * @function	superpwa_get_scope()					Get navigation scope of PWA
 * @function	superpwa_get_orientation()				Get orientation of PWA
 * @function	superpwa_get_display()					Get display of PWA
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Returns the Manifest filename.
 *
 * @since 2.0
 *
 * @return string
 */
function superpwa_get_manifest_filename() {
	return 'superpwa-manifest' . superpwa_multisite_filename_postfix() . '.json';
}

/**
 * Manifest filename, absolute path and link
 *
 * For Multisite compatibility. Used to be constants defined in superpwa.php
 * On a multisite, each sub-site needs a different manifest file.
 *
 * @uses  superpwa_get_manifest_filename()
 *
 * @param $arg    filename for manifest filename (replaces SUPERPWA_MANIFEST_FILENAME)
 *                abs for absolute path to manifest (replaces SUPERPWA_MANIFEST_ABS)
 *                src for link to manifest (replaces SUPERPWA_MANIFEST_SRC). Default value
 *
 * @return String filename, absolute path or link to manifest.
 *
 * @since 1.6
 * @since 2.0 src uses home_url instead of network_site_url since manifest is no longer in the root folder.
 */
function superpwa_manifest( $arg = 'src' ) {

	$manifest_filename = superpwa_get_manifest_filename();

	switch ( $arg ) {
		// TODO: Case `filename` can be deprecated in favor of @see superpwa_get_manifest_filename().
		// Name of Manifest file
		case 'filename':
			return $manifest_filename;
			break;

		/**
		* Absolute path to manifest. 
		* 
		* Note: @since 2.0 manifest is no longer a physical file and absolute path doesn't make sense. 
		* Also using home_url instead of network_site_url in "src" in 2.0 changes the apparent location of the file. 
		* However, absolute path is preserved at the "old" location, so that phyiscal files can be deleted when upgrading from pre-2.0 versions.
		* 
		* Since static files are being used in conditions where dynamic files are not possible, this path 
		* pointing to the root folder of WordPress is still useful. 
		*/
		case 'abs':
			if (function_exists('get_home_path')) {
				$filepath = trailingslashit( ABSPATH ) . $manifest_filename;
				if(!file_exists($filepath)){
					$filepath = trailingslashit( get_home_path() ). $manifest_filename;
				}
				return $filepath;
				break;
			}

		// Link to manifest
		case 'src':
		default:
		
			// Get Settings
			$superpwa_settings = superpwa_get_settings();
			
			/**
			 * For static file, return site_url and network_site_url
			 * 
			 * Static files are generated in the root directory. 
			 * The site_url template tag retrieves the site url for the 
			 * current site (where the WordPress core files reside).
			 */
			if ( $superpwa_settings['is_static_manifest'] === 1 ) {
				return trailingslashit( network_site_url() ) . $manifest_filename;
			}
			
			// For dynamic files, return the home_url
			return home_url( '/' ) . $manifest_filename;
			
			break;
	}
}

/**
 * Returns the Manifest template.
 *
 * @author Maria Daniel Deepak <daniel@danieldeepak.com>
 *
 * @return array
 * 
 * @since 2.0 Replaces superpwa_generate_manifest()
 * @since 2.0 Added display
 */
function superpwa_manifest_template( $pageid = null ) {
	
	// Get Settings
	$superpwa_settings = superpwa_get_settings();

	$manifest               = array();
	$id_url = wp_parse_url(site_url());
	$manifest['id']         = isset($id_url['host'])?$id_url['host']:wp_rand(0,9999999);
	$manifest['name']       = $superpwa_settings['app_name'];
	$manifest['short_name'] = $superpwa_settings['app_short_name'];

	// Description
	if ( isset( $superpwa_settings['description'] ) && ! empty( $superpwa_settings['description'] ) ) {
		$manifest['description'] = $superpwa_settings['description'];
	}

	$manifest['icons']            = superpwa_get_pwa_icons();
	$screenshots=superpwa_get_pwa_screenshots();
	if($screenshots)
	{
		$manifest['screenshots']      = $screenshots;
	}
	$manifest['background_color'] = $superpwa_settings['background_color'];
	$manifest['theme_color']      = $superpwa_settings['theme_color'];
	$manifest['display']          = superpwa_get_display();
	$manifest['dir']          	  = superpwa_get_text_dir();
	$manifest['orientation']      = superpwa_get_orientation();
	$manifest['start_url']        = strlen( superpwa_get_start_url( true ) )>2?user_trailingslashit(superpwa_get_start_url( true )) : superpwa_get_start_url( true );
	if(isset($superpwa_settings['startpage_type']) && $superpwa_settings['startpage_type'] == 'active_url' && function_exists('superpwa_pro_init'))
	{
		if ($pageid) {
			$permalink = get_permalink($pageid);
			$title  = get_the_title($pageid);
			if($permalink){
				$manifest['start_url']       = $permalink;
			}
			if($title){
				$stripped_title = wp_strip_all_tags($title);
				$trimmed_title = mb_substr($stripped_title, 0, 75);
				$manifest['name']       = $trimmed_title;
				$manifest['short_name'] = $trimmed_title;
			}
		}  
	}
	if(isset($superpwa_settings['app_category']) && !empty($superpwa_settings['app_category']))
	{
		$manifest['categories']       = [$superpwa_settings['app_category']];
	}
	$manifest['scope']            = strlen(superpwa_get_scope())>2? user_trailingslashit(superpwa_get_scope()) : superpwa_get_scope();

	// if(isset($settings['shortcut_url']) && $settings['shortcut_url']!=0){
		$shortcut_url = !empty($superpwa_settings['shortcut_url']) ? get_permalink( $superpwa_settings['shortcut_url'] ) : '';
		$shortcut_url = superpwa_httpsify( $shortcut_url );
		// AMP URL
		if ( superpwa_is_amp() !== false && isset( $settings['start_url_amp'] ) && $superpwa_settings['start_url_amp'] == 1 ) {
			$shortcut_url = trailingslashit( $shortcut_url ) . superpwa_is_amp();
		}
		if(function_exists('superpwa_utm_tracking_for_start_url')){
			$shortcut_url = superpwa_utm_tracking_for_start_url($shortcut_url);
		}


		$manifest['shortcuts'] = array(
									array(
										'name'=>$superpwa_settings['app_short_name'],
										'url'=>user_trailingslashit( wp_parse_url( trailingslashit( $shortcut_url ), PHP_URL_PATH ) ),
									)
								);
		

		if ( isset( $superpwa_settings['description'] ) && ! empty( $superpwa_settings['description'] ) ) {
			$manifest['shortcuts'][0]['description'] = $superpwa_settings['description'];
		}

		if ( isset( $superpwa_settings['icon'] ) && ! empty( $superpwa_settings['icon'] ) ) {
			$manifest['shortcuts'][0]['icons'] = array(array('src'=>$superpwa_settings['icon'], 'sizes'=>'192x192'));
		}
		if( isset( $superpwa_settings['prefer_related_applications'] ) && $superpwa_settings['prefer_related_applications'] == true){
			$related_applications = array();
			if (isset($superpwa_settings['related_applications']) && $superpwa_settings['related_applications']) {
				$related_applications[] = array('id' =>$superpwa_settings['related_applications'],
							'platform' => 'play',
							'url' => 'https://play.google.com/store/apps/details?id='.$superpwa_settings['related_applications'] );
			}
			if (isset($superpwa_settings['related_applications_ios']) && $superpwa_settings['related_applications_ios']) {
				$related_applications[] = array('id' =>$superpwa_settings['related_applications_ios'],
							'platform' => 'itunes',
							'url' => 'https://apps.apple.com/app/'.$superpwa_settings['related_applications_ios'] );
			}

			if (count($related_applications) > 0) {
				$manifest['prefer_related_applications']       = true;
				$manifest['related_applications']       = $related_applications;
			}
		}

		$is_any_multilang_enable = false;
		$active_addons = get_option( 'superpwa_active_addons', array() );
		if (in_array('wpml_for_superpwa',$active_addons)) {
			$wpml_settings = get_option( 'superpwa_wpml_settings');
			if (isset($wpml_settings['enable_wpml']) && $wpml_settings['enable_wpml'] == 1) {
				$current_language = superpwa_get_language_shortcode();
				$start_url = superpwa_home_url().$current_language;
				$manifest['start_url'] = $start_url;
				$manifest['scope'] = "/";
				$is_any_multilang_enable = true;
			}
		}
		if (in_array('wp_multilang_for_superpwa',$active_addons)) {
			$wpmultilang_settings = get_option( 'superpwa_wp_multilang_settings');
			if (isset($wpmultilang_settings['enable_wp_multilang']) && $wpmultilang_settings['enable_wp_multilang'] == 1 && function_exists("wpm_get_languages")) {
				$current_language = wpm_get_language();
				$start_url = superpwa_home_url().$current_language;
				$manifest['start_url'] = $start_url;
				$manifest['scope'] = "/";
				$is_any_multilang_enable = true;
			}
		}
		if (in_array('polylang_for_superpwa',$active_addons)) {
			$polylang_settings = get_option( 'superpwa_polylang_settings');
			if (isset($polylang_settings['enable_polylang']) && $polylang_settings['enable_polylang'] == 1 && function_exists("pll_default_language")) {
				$current_language = pll_current_language();
				$start_url = superpwa_home_url().$current_language;
				$manifest['start_url'] = $start_url;
				$manifest['scope'] = "/";
				$is_any_multilang_enable = true;
			}
		}
		if (in_array('translatepress_for_superpwa',$active_addons)) {
			$translatepress_settings = get_option( 'superpwa_translatepress_settings');
			if (isset($translatepress_settings['enable_translatepress']) && $translatepress_settings['enable_translatepress'] == 1 && class_exists('TRP_Translate_Press')) {
				$homeUrl = superpwa_home_url();
				global $TRP_LANGUAGE;
				$trp = TRP_Translate_Press::get_trp_instance();
				$start_url = $trp->get_component('url_converter')->get_url_for_language($TRP_LANGUAGE, $homeUrl);
				$manifest['start_url'] = $homeUrl;
				$manifest['scope'] = "/";
				$is_any_multilang_enable = true;
			}
		}
		if ($is_any_multilang_enable) {
			superpwa_delete_manifest();
			$superpwa_settings['is_any_multilang_enable'] = 1;
			update_option( 'superpwa_settings', $superpwa_settings );
		}
		$launch_handler = ['client_mode'=>'auto'];
		$manifest['launch_handler']  = $launch_handler;
		$manifest['handle_links']  = 'preferred';
		

	// }

	/**
	 * Values that go in to Manifest JSON.
	 *
	 * The Web app manifest is a simple JSON file that tells the browser about your web application.
	 *
	 * @param array $manifest
	 */
	return apply_filters( 'superpwa_manifest', $manifest );
}

/**
 * Generate and write manifest into WordPress root folder
 * 
 * Starting with 2.0, files are only generated if dynamic files are not possible. 
 * Some webserver configurations does not load WordPress and attempts to server files directly
 * from the server. This returns 404 when files do not exist physically. 
 *
 * @return (boolean) true on success, false on failure.
 * 
 * @author Arun Basil Lal
 * @author Maria Daniel Deepak <daniel@danieldeepak.com>
 *
 * @since 1.0
 * @since 1.3 Added support for 512x512 icon.
 * @since 1.4 Added orientation and scope.
 * @since 1.5 Added gcm_sender_id
 * @since 1.6 Added description
 * @since 1.8 Removed gcm_sender_id and introduced filter superpwa_manifest. gcm_sender_id is added in /3rd-party/onesignal.php
 * @since 2.0 Deprecated since Manifest is generated on the fly {@see superpwa_generate_sw_and_manifest_on_fly()}.
 * @since 2.0.1 No longer deprecated since physical files are now generated in certain cases. See funtion description. 
 */
function superpwa_generate_manifest() {
	
	// Delete manifest if it exists.
	superpwa_delete_manifest();

	// check if manifest is already generated. To avoid generation loop on settings page when superpwa_file_exists returns false.
	global $superpwa_manifest_generated;

	if($superpwa_manifest_generated){
		return true;
	}
	
	// Get Settings
	$superpwa_settings = superpwa_get_settings();
	
	// Return true if dynamic file returns a 200 response.
	if ( superpwa_file_exists( home_url( '/' ) . superpwa_get_manifest_filename() ) && defined( 'WP_CACHE' ) && ! WP_CACHE ) {
		
		// set file status as dynamic file in database.
		$superpwa_settings['is_static_manifest'] = 0;
		
		// Write settings back to database.
		update_option( 'superpwa_settings', $superpwa_settings );
		
		return true;
	}
	$dynamic_check = (isset($superpwa_settings['startpage_type']) && $superpwa_settings['startpage_type'] =='active_url' && function_exists('superpwa_pro_init'))?false:true;
	// Write the manfiest to disk.
	if ( $dynamic_check  && superpwa_put_contents( superpwa_manifest( 'abs' ), wp_json_encode( superpwa_manifest_template() ) ) ) {
		
		// set file status as satic file in database.
		$superpwa_settings['is_static_manifest'] = 1;
		
		// Write settings back to database.
		update_option( 'superpwa_settings', $superpwa_settings );

		// set manifest generated to true.
		$superpwa_manifest_generated = true;
		
		return true;
	}
	
	return false;
}

/**
 * Add manifest to header (wp_head)
 *
 * @since 1.0
 * @since 1.8 Introduced filter superpwa_wp_head_tags
 * @since 1.9 Introduced filter superpwa_add_theme_color
 */
function superpwa_add_manifest_to_wp_head() {
	// Get Settings
	
	$superpwa_settings = superpwa_get_settings();
	$tags  = '<!-- Manifest added by SuperPWA - Progressive Web Apps Plugin For WordPress -->' . PHP_EOL;
	$is_display_manifest = 1;
	if (function_exists('superpwa_display_status')) {
		$is_display_manifest = superpwa_display_status();
	}
	if ($is_display_manifest) {
		$manifest_url = superpwa_add_manifest_variables(superpwa_manifest( 'src' ));
		$tags .= '<link rel="manifest" href="'. esc_url($manifest_url) . '">' . PHP_EOL;
	}
	if(isset( $superpwa_settings['prefetch_manifest'] )){
		if($superpwa_settings['prefetch_manifest'] == 1){  
			$tags .= '<link rel="prefetch" href="'. esc_url(wp_parse_url( superpwa_manifest( 'src' ), PHP_URL_PATH )) . '">' . PHP_EOL;
		}
	}
	// theme-color meta tag 
	if ( apply_filters( 'superpwa_add_theme_color', true ) && isset($superpwa_settings['theme_color'])) {
		
		$tags .= '<meta name="theme-color" content="'. esc_attr($superpwa_settings['theme_color']) .'">' . PHP_EOL;
	}
	
	$tags  = apply_filters( 'superpwa_wp_head_tags', $tags );
	
	$tags .= '<!-- / SuperPWA.com -->' . PHP_EOL; 
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo $tags;
}

function superpwa_image_extension($image_url = '')
{
    $image_extension = 'image/png';
    if(!empty($image_url)){
        $valid_extensions = array('png', 'webp');
        $explode_url = explode('.', $image_url);
        if(!empty($explode_url) && is_array($explode_url)){
            $explode_count = count($explode_url);
            $img_extension = strtolower(sanitize_text_field($explode_url[$explode_count - 1]));
            if(!empty($img_extension)){
                if(in_array($img_extension, $valid_extensions)){
                    $image_extension = 'image/'.$img_extension;
                }
            }
        }
    }
    return $image_extension;
}

$show_manifest_icon = 0;
$current_page_url = home_url( $_SERVER['REQUEST_URI'] );
if(isset($superpwa_settings['excluded_urls']) && !empty($superpwa_settings['excluded_urls'])){
	$excluded_urls = explode(",", $superpwa_settings['excluded_urls']);
	if(!empty($excluded_urls)){
		foreach($excluded_urls as $excluded_page_url) {
			if(trim($excluded_page_url) == trim($current_page_url)){
				$show_manifest_icon = 1;
			}
		}
	}
}


if($show_manifest_icon == 0){
	add_action( 'wp_head', 'superpwa_add_manifest_to_wp_head', 0 );
}

/**
 * Delete manifest
 *
 * @return (boolean) true on success, false on failure
 * 
 * @author Arun Basil Lal
 *
 * @since 1.0
 */
function superpwa_delete_manifest() {
	return superpwa_delete( superpwa_manifest( 'abs' ) );
}

/**
 * Get PWA Icons
 *
 * @return	array	An array of icons to be used as the application icons and splash screen icons
 *
 * @author Jose Varghese
 * @since	1.3
 * @since	2.1.1 Added support for Maskable Icons
 *
 */
function superpwa_get_pwa_icons() {
	
	// Get settings
	$superpwa_settings = superpwa_get_settings();
	
	// Application icon
	$icons_array[] = array(
		'src' 	=> esc_url($superpwa_settings['icon']),
		'sizes'	=> '192x192', // must be 192x192. Todo: use getimagesize($settings['icon'])[0].'x'.getimagesize($settings['icon'])[1] in the future
		'type'	=> 'image/png', // must be image/png. Todo: use getimagesize($settings['icon'])['mime']
		'purpose'=> 'any', // any maskable to support adaptive icons
	);
	if ( isset($superpwa_settings['app_maskable_icon'] ) && ! empty( $superpwa_settings['app_maskable_icon'] ) ) {
		$icons_array[] = array(
			'src' 	=> esc_url($superpwa_settings['app_maskable_icon']),
			'sizes'	=> '192x192', 
			'type'	=> 'image/png',
			'purpose'=> 'maskable',
		);
	}
	
	// Splash screen icon - Added since 1.3
	if ( isset($superpwa_settings['splash_icon']) && ! empty( $superpwa_settings['splash_icon'] ) ) {
		$icons_array[] = array(
			'src' 	=> esc_url($superpwa_settings['splash_icon']),
			'sizes'	=> '512x512', // must be 512x512.
			'type'	=> 'image/png', // must be image/png
			'purpose'=> 'any',
		);
	}

	if ( isset($superpwa_settings['splash_maskable_icon']) && ! empty( $superpwa_settings['splash_maskable_icon'] ) ) {
		$icons_array[] = array(
			'src' 	=> esc_url($superpwa_settings['splash_maskable_icon']),
			'sizes'	=> '512x512', // must be 512x512.
			'type'	=> 'image/png', // must be image/png
			'purpose'=> 'maskable',
		);
	}

	if ( isset($superpwa_settings['monochrome_icon']) && ! empty( $superpwa_settings['monochrome_icon'] ) ) {
		$icons_array[] = array(
			'src' 	=> esc_url($superpwa_settings['monochrome_icon']),
			'sizes'	=> '512x512', // must be 512x512.
			'type'	=> 'image/png', // must be image/png
			'purpose'=> 'monochrome',
		);
	}
	
	return $icons_array;
}

/**
 * Get PWA Screenshot
 *
 * @return	array	An array of images to be used as the screenshot
 *
 * @since	 2.2.8
 *
 */
function superpwa_get_pwa_screenshots() {
	
	// Get settings
	$superpwa_settings = superpwa_get_settings();

	// Screenshots - Added since 2.2.8

	$screenshot_array=null;

	if ( @$superpwa_settings['screenshots'] != '' ) {
		
		$tmp_arr=explode(',',$superpwa_settings['screenshots']);
		
		if(!empty($tmp_arr)){
			foreach($tmp_arr as $item){
				if(function_exists('getimagesize')){
					list($width, $height) =  @getimagesize($item);
					$file_type = superpwa_image_extension($item);
					if($width && $height){

						$form_factor = '';
						if (isset($superpwa_settings['form_factor']) && !empty($superpwa_settings['form_factor'])) {
							$form_factor = $superpwa_settings['form_factor'];
						}
						$screenshot = array(
							'src' 	=> $item,
							'sizes' => $width.'x'.$height, 
							'type'	=> $file_type, 
							"label"=> "Homescreen of Superpwa App"
						);
						if(!empty($form_factor)){
							$screenshot['form_factor'] = $form_factor;
						}
						$screenshot_array[] = $screenshot;
					}
				}
			}
		}
		if (isset($superpwa_settings['screenshots_multiple']) && !empty($superpwa_settings['screenshots_multiple'])) {
			foreach ($superpwa_settings['screenshots_multiple'] as $key => $screenshots_multiple) {
				if (!empty($screenshots_multiple)) {
					list($width, $height) =  @getimagesize($screenshots_multiple);
					$file_type = superpwa_image_extension($screenshots_multiple);

					$form_factor_multiple = 'wide';
					if (isset($superpwa_settings['form_factor_multiple'][$key]) && !empty($superpwa_settings['form_factor_multiple'][$key])) {
							$form_factor_multiple = $superpwa_settings['form_factor_multiple'][$key];
					}

					$screenshot_array[] = array(
						'src' 	=> esc_url($screenshots_multiple),
						'sizes' => $width.'x'.$height, 
						'type'	=> $file_type, 
						"form_factor"=> $form_factor_multiple,
						"label"=> "Homescreen of Superpwa App"
					);
				}
			}
		}
	}
	
	return $screenshot_array;
}

/**
 * Get navigation scope of PWA
 *
 * @return	string	Relative path to the folder where WordPress is installed. Same folder as manifest and wp-config.php
 * @since	1.4
 */
function superpwa_get_scope() {
	return wp_parse_url( trailingslashit( superpwa_get_bloginfo( 'sw' ) ), PHP_URL_PATH );
}

/**
 * Get orientation of PWA
 *
 * @return	string	Orientation of PWA as set in the plugin settings. 
 * @since	1.4
 */
function superpwa_get_orientation() {
	
	// Get Settings
	$superpwa_settings = superpwa_get_settings();
	
	$orientation = isset( $superpwa_settings['orientation'] ) ? $superpwa_settings['orientation'] : 0;
	
	switch ( $orientation ) {
		
		case 0:
			return 'any';
			break;
			
		case 1:
			return 'portrait';
			break;
			
		case 2:
			return 'landscape';
			break;
			
		default: 
			return 'any';
	}
}

/**
 * Get display of PWA
 *
 * @return (string) Display of PWA as set in the plugin settings.
 * 
 * @author Jose Varghese
 * 
 * @since 2.0
 */
function superpwa_get_display() {
	
	// Get Settings
	$superpwa_settings = superpwa_get_settings();
	
	$display = isset( $superpwa_settings['display'] ) ? $superpwa_settings['display'] : 1;
	
	switch ( $display ) {
		
		case 0:
			return 'fullscreen';
			break;
			
		case 1:
			return 'standalone';
			break;
			
		case 2:
			return 'minimal-ui';
			break;

		case 3:
			return 'browser';
			break;
			
		default: 
			return 'standalone';
	}
}


/**
 * Get display of PWA
 *
 * @return (string) Display of PWA as set in the plugin settings.
 * 
 * @author Jose Varghese
 * 
 * @since 2.0
 */
function superpwa_get_text_dir() {
	
	// Get Settings
	$superpwa_settings = superpwa_get_settings();
	
	$display = isset( $superpwa_settings['text_dir'] ) ? $superpwa_settings['text_dir'] : 0;
	
	switch ( $display ) {
		
		case 0:
			return 'ltr';
			break;
			
		case 1:
			return 'rtl';
			break;
			
		default: 
			return 'ltr';
	}
}

function superpwa_add_manifest_variables($url) {
	$settings = superpwa_get_settings();
    if ( isset( $settings['startpage_type'] ) && $settings['startpage_type'] == 'active_url' && function_exists('superpwa_pro_init')) {
		$parsedUrl = wp_parse_url( $url );
		global $post;
		$cache_version = SUPERPWA_VERSION;
		if(isset($settings['force_update_sw_setting']) && $settings['force_update_sw_setting'] !=''){
		  $cache_version =   $settings['force_update_sw_setting'];
		  if(!version_compare($cache_version,SUPERPWA_VERSION, '>=') ){
			$cache_version = SUPERPWA_VERSION;
		  }
		}
		// Extract the query string parameters
		$queryParams = [];
		if (isset($parsedUrl['query'])) {
			parse_str($parsedUrl['query'], $queryParams);
		}
	
		if (!isset($queryParams['superpwa_mid'])) {
			$queryParams['superpwa_mid'] = $post->ID;
		}
		if (!isset($queryParams['v'])) {
			$queryParams['v'] = $cache_version;
		}
	
		// Rebuild the query string
		$newQueryString = http_build_query($queryParams);
	
		if (isset($parsedUrl['path'])) {
			$newUrl = $parsedUrl['path'] . '?' . $newQueryString;
		} else {
			$newUrl = '?' . $newQueryString;
		}	
		return $newUrl;
	}
	return wp_parse_url( $url, PHP_URL_PATH ) ;
}

