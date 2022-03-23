<?php
/**
 * Operations and common functions of SuperPWA
 *
 * @since 1.0
 * 
 * @function	superpwa_is_amp()				Check if any AMP plugin is installed
 * @function 	superpwa_get_start_url()		Return Start Page URL
 * @function	superpwa_httpsify()				Convert http URL to https
 * @function	superpwa_is_pwa_ready()			Check if PWA is ready
 * @function 	superpwa_file_exists()			Check if file exists
 * @function	superpwa_is_static()			Check if service worker or manifest is static or dynamic
 * @function	superpwa_get_bloginfo()			Returns WordPress URL v/s Site URL depending on the status of the file. 
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Check if any AMP plugin is installed
 * 
 * @return (string|bool) AMP page url on success, false otherwise
 * 
 * @author Arun Basil Lal
 * @author Maria Daniel Deepak <daniel@danieldeepak.com>
 * 
 * @since 1.2
 * @since 1.9 Added support for tagDiv AMP
 * @since 2.0 require wp-admin/includes/plugin.php if is_plugin_active isn't defined
 */
function superpwa_is_amp() {
	
	if ( ! function_exists( 'is_plugin_active' ) ) {
		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
	}

	// AMP for WordPress - https://wordpress.org/plugins/amp
	if ( is_plugin_active( 'amp/amp.php' ) ) {
		return defined( 'AMP_QUERY_VAR' ) ? AMP_QUERY_VAR . '/' : 'amp/';
	}

	// AMP for WP - https://wordpress.org/plugins/accelerated-mobile-pages/
	if ( is_plugin_active( 'accelerated-mobile-pages/accelerated-moblie-pages.php' ) ) {
		return defined( 'AMPFORWP_AMP_QUERY_VAR' ) ? AMPFORWP_AMP_QUERY_VAR . '/' : 'amp/';
	}

	// Better AMP - https://wordpress.org/plugins/better-amp/
	if ( is_plugin_active( 'better-amp/better-amp.php' ) ) {
		return 'amp/';
	}

	// AMP Supremacy - https://wordpress.org/plugins/amp-supremacy/
	if ( is_plugin_active( 'amp-supremacy/amp-supremacy.php' ) ) {
		return 'amp/';
	}

	// WP AMP - https://wordpress.org/plugins/wp-amp-ninja/
	if ( is_plugin_active( 'wp-amp-ninja/wp-amp-ninja.php' ) ) {
		return '?wpamp';
	}

	// tagDiv AMP - http://forum.tagdiv.com/tagdiv-amp/
	if ( is_plugin_active( 'td-amp/td-amp.php' ) ) {
		return defined( 'AMP_QUERY_VAR' ) ? AMP_QUERY_VAR . '/' : 'amp/';
	}

	return false;
}

/**
 * Return Start Page URL
 *
 * @param $rel (boolean) False by default. Set to true to return a relative URL (for use in manifest)
 * 
 * @return (string) URL to be set as the start_url in manifest and startPage in service worker
 *
 * @since 1.2
 * @since 1.3.1 Force HTTPS by replacing http:// with https://
 * @since 1.6 Use superpwa_httpsify() to force HTTPS. 
 * @since 1.6 Removed forcing of trailing slash and added dot (.) to the beginning.
 * @since 1.7 Added filter superpwa_manifest_start_url when $rel = true, for use with manifest. First ever filter in SuperPWA.
 */
function superpwa_get_start_url( $rel = false ) {
	
	// Get Settings
	$settings = superpwa_get_settings();
	
	// Start Page
	$start_url = get_permalink( $settings['start_url'] ) ? get_permalink( $settings['start_url'] ) : superpwa_get_bloginfo( 'sw' );
	
	// Force HTTPS
	$start_url = superpwa_httpsify( $start_url );
	
	// AMP URL
	if ( superpwa_is_amp() !== false && isset( $settings['start_url_amp'] ) && $settings['start_url_amp'] == 1 ) {
		$start_url = trailingslashit( $start_url ) . superpwa_is_amp();
	}
	
	// Relative URL for manifest
	if ( $rel === true ) {
		
		// Make start_url relative for manifest
		$start_url = ( parse_url( $start_url, PHP_URL_PATH ) == '' ) ? '.' : parse_url( $start_url, PHP_URL_PATH );
		
		return apply_filters( 'superpwa_manifest_start_url', $start_url );
	}
	
	return $start_url;
}

/**
 * Convert http URL to https
 *
 * @param $url (string) The URL to convert to https
 * 
 * @return (string) Returns the converted URL
 *
 * @since 1.6
 */
function superpwa_httpsify( $url ) {
	return str_replace( 'http://', 'https://', $url );
}

/**
 * Check if PWA is ready
 * 
 * Check for HTTPS.
 * Check if manifest is generated.
 * Check if service worker is generated. 
 * 
 * @return (bool) True if PWA is ready. False otherwise
 * 
 * @author Arun Basil Lal
 * 
 * @since 1.8.1
 * @since 2.0.1 replaced superpwa_get_contents() with superpwa_file_exists() to accommodate dynamic files. 
 */
function superpwa_is_pwa_ready() {
	
	if ( 
		is_ssl() && 
		superpwa_file_exists( superpwa_manifest( 'src' ) ) && 
		superpwa_file_exists( superpwa_sw( 'src' ) ) 
	) {
		return apply_filters( 'superpwa_is_pwa_ready', true );
	}
	
	return false; 
}

/**
 * Check if file exists
 * 
 * Not to be confused with file_exists PHP function. 
 * In SuperPWA context, file exists if the response code is 200. 
 * 
 * @param $file (string) URL to check
 * 
 * @return (bool) True, if file exists. False otherwise. 
 * 
 * @author Arun Basil Lal
 * @author Maria Daniel Deepak <daniel@danieldeepak.com>
 * 
 * @since 2.0.1
 */
function superpwa_file_exists( $file ) {
	
	$response 		= wp_remote_head( $file, array( 'sslverify' => false ) );
	$response_code 	= wp_remote_retrieve_response_code( $response );
	
	if ( 200 === $response_code ) {
		return true;
	}
	
	return false;
}

/**
 * Check if service worker or manifest is static or dynamic
 * 
 * @param (string) $file keyword 'manifest' to test manifest and 'sw' to test service worker. 
 *
 * @return (bool) True if the file is static. False otherwise. 
 * 
 * @author Arun Basil Lal
 * 
 * @since 2.0.1
 */
function superpwa_is_static( $file = 'manifest' ) {
	
	// Get Settings
	$settings = superpwa_get_settings();
	
	switch ( $file ) {
		
		case 'sw':
			
			if ( $settings['is_static_sw'] === 1 ) {
				return true;
			}
			
			return false;
			break;
		
		case 'manifest':
		default: 
			
			if ( $settings['is_static_manifest'] === 1 ) {
				return true;
			}
		
			return false;
			break;
	}
}

/**
 * Returns WordPress URL v/s Site URL depending on the status of the file. 
 * 
 * Static files are generated in the root directory of WordPress. So if static 
 * files are used, the WordPress URL will be needed for many use cases, like
 * offline page, start_url etc. 
 * 
 * The status of the service worker is mostly relevant since the service worker 
 * can work on the folder it is located and its sub folders. Not the folders above
 * its own directory. 
 * 
 * @param (string) $file keyword 'manifest' to test manifest and 'sw' to test service worker. 
 * 
 * @return (string) get_bloginfo( 'wpurl' ) if file is static. get_bloginfo( 'url' ) otherwise. 
 * 
 * @author Arun Basil Lal
 * 
 * @since 2.0.1
 */
function superpwa_get_bloginfo( $file = 'sw' ) {
	
	if ( superpwa_is_static( $file ) ) {
		return get_bloginfo( 'wpurl' );
	}
	
	return get_bloginfo( 'url' );
}

/**
* only for Automattic amp Support
* When user enabled Standard & Transitional mode 
* it will check and give respective values
*/

function superpwa_is_automattic_amp($case=null){
    //Check if current theme support amp
    switch ($case) {
        case 'amp_support':
            if(class_exists('AMP_Theme_Support')){
                return current_theme_supports( AMP_Theme_Support::SLUG );
            }
            break;
        default:
            if ( current_theme_supports( 'amp' ) && function_exists('is_amp_endpoint') && is_amp_endpoint() ) {
                return true;
            }
            break;
    }
    return false;
}

function superpwa_home_url(){
    
        if ( is_multisite() ) {
            $link = get_site_url();              
        }
        else {
            $link = home_url();
        }    
            $link = superpwa_httpsify($link);
    
        return trailingslashit($link);
}
function superpwa_site_url(){
    
        if (is_multisite() ) {
            
           $link = get_site_url();   
           
        }
        else {
            $link = site_url();
        }    
            $link = superpwa_httpsify($link);
            
        return trailingslashit($link);
}

/**
 * Reset Settings Ajax Callback
 */
function superpwa_reset_all_settings(){ 
    
        if ( ! isset( $_POST['superpwa_security_nonce'] ) ){
           return; 
        }
        if ( !wp_verify_nonce( $_POST['superpwa_security_nonce'], 'superpwa_ajax_check_nonce' ) ){
           return;  
        }  
        if ( ! current_user_can( 'manage_options' ) ) {
           return;
        }
        
        $default = superpwa_get_default_settings();
                     
        $result  = update_option('superpwa_settings', $default);
       // delete_transient('pwaforwp_restapi_check');   
        
        if($result){    
            
            echo json_encode(array('status'=>'t'));            
        
        }else{
            
            echo json_encode(array('status'=>'f'));            
        
        }        
        wp_die();           
}

add_action('wp_ajax_superpwa_reset_all_settings', 'superpwa_reset_all_settings');

/**
 * Returns Superpwa setting tabs html
 */
function superpwa_setting_tabs_html(){

 $general_settings = admin_url( 'admin.php?page=superpwa#general-settings');
 $advance_settings = admin_url( 'admin.php?page=superpwa#advance-settings');
 $support_settings = admin_url( 'admin.php?page=superpwa#support-settings');
 $license_settings = admin_url( 'admin.php?page=superpwa#license-settings');
 $addon_page = admin_url( 'admin.php?page=superpwa-addons');
 if( $_GET['page'] == 'superpwa-upgrade' ) {
 	$license_settings_class = $addon_page_class =  '' ;
 	if(  isset( $_GET['page'] ) && $_GET['page'] == 'superpwa-upgrade' ) {
 		$license_settings_class = 'active';
 	}else{
 		$addon_page_class = 'active';
 	}
 }
 
 	$license_settings_class = $addon_page_class =  '' ;
 	if(  isset( $_GET['page'] ) && $_GET['page'] == 'superpwa-upgrade' ) {
 		$license_settings_class = 'active';
 	}else{
 		$addon_page_class = 'active';
 	}
?>
			<div class="spwa-tab">
				  <a id="spwa-default" class="spwa-tablinks" href="<?php echo esc_url_raw($general_settings); ?>" data-href="yes">Settings</a>
				  <a class="spwa-tablinks <?php echo $addon_page_class; ?>" href="<?php echo esc_url_raw($addon_page); ?>" data-href="yes">Features (Addons)</a>
				  <a class="spwa-tablinks" href="<?php echo esc_url_raw($advance_settings); ?>" data-href="yes">Advanced</a>
				  <a class="spwa-tablinks" href="<?php echo esc_url_raw($support_settings); ?>" data-href="yes">Help & Support</a>
				  <?php if( defined('SUPERPWA_PRO_VERSION') &&  $_GET['page'] !== 'superpwa-upgrade' ) { 
                     $expiry_warning = superpwa_license_expire_warning();
				  	?>
				  <a class="spwa-tablinks" href="<?php echo esc_url_raw($license_settings); ?>" data-href="yes">License <?php echo $expiry_warning; ?></a>
				  <?php } ?>
				  <?php if( $_GET['page'] == 'superpwa-upgrade' ) { ?>
				  <a class="spwa-tablinks <?php echo $license_settings_class; ?>  " href="<?php echo esc_url_raw($license_settings); ?>" data-href="yes">License</a>
				<?php } ?>
				  
				</div>
 <?php
}

/**
 * Returns Warning Icon When License Key is Expired
 */
function superpwa_license_expire_warning(){

	$license_alert ='';
		if( defined('SUPERPWA_PRO_VERSION') ){

			$license_info = get_option("superpwa_pro_upgrade_license");
			if ($license_info) {

			$license_exp = date('Y-m-d', strtotime($license_info['pro']['license_key_expires']));
			$license_info_lifetime = $license_info['pro']['license_key_expires'];
			$today = date('Y-m-d');
			$exp_date = $license_exp;

	        $license_alert = $today > $exp_date ? "<span class='superpwa_pro_icon dashicons dashicons-warning superpwa_pro_alert' style='color: #ffb229;left: 3px;position: relative;'></span>": "" ;
	        }
	    }
    return $license_alert;
}


/**
 * Returns Superpwa Setting tabs Styles
 */
function superpwa_setting_tabs_styles(){
	?>
	<style type="text/css">.spwa-tab {overflow: hidden;border: 1px solid #ccc;background-color: #fff;margin-top: 15px;margin-bottom: 6px;}.spwa-tab a {background-color: inherit;text-decoration: none;float: left;border: none;outline: none;cursor: pointer;padding: 14px 16px;transition: 0s;font-size: 15px;color: #2271b1;}.spwa-tab a:hover {color: #0a4b78;}.spwa-tab a.active {box-shadow: none;border-bottom: 4px solid #646970;color: #1d2327;}.spwa-tab a:focus {box-shadow: none;outline: none;}.spwa-tabcontent {display: none;padding: 6px 12px;border-top: none; animation: fadeEffect 1s; } @keyframes fadeEffect { from {opacity: 0;} to {opacity: 1;} }</style>
	<?php
}
/**
 * Returns Superpwa Setting Newsletter Forms
 */
function superpwa_newsletter_form(){
	
	$hide_form = get_option('superpwa_hide_newsletter');

	// Newsletter marker. Set this to false once newsletter subscription is displayed.
		$superpwa_newsletter = true;

	if ( $superpwa_newsletter === true && $hide_form !== 'yes') { ?>
	  <div class="superpwa-newsletter-wrapper">
		<div class="plugin-card plugin-card-superpwa-newsletter" style="background: #fdfc35 url('<?php echo SUPERPWA_PATH_SRC . 'admin/img/email.png'; ?>') no-repeat right top;">
						
					<div class="plugin-card-top" style="min-height: 135px;">
					     <span class="dashicons dashicons-dismiss superpwa_newsletter_hide" style="float: right;cursor: pointer;"></span>
					    <span style="clear:both;"></span>
						<div class="name column-name" style="margin: 0px 10px;">
							<h3><?php _e( 'SuperPWA Newsletter', 'super-progressive-web-apps' ); ?></h3>
						</div>
						<div class="desc column-description" style="margin: 0px 10px;">
							<p><?php _e( 'Learn more about Progressive Web Apps and get latest updates about SuperPWA', 'super-progressive-web-apps' ); ?></p>
						</div>
						
						<div class="superpwa-newsletter-form" style="margin: 18px 10px 0px;">
						
							<form method="post" action="https://superpwa.com/newsletter/" target="_blank" id="superpwa_newsletter">
								<fieldset>
									<input name="newsletter-email" value="<?php $user = wp_get_current_user(); echo esc_attr( $user->user_email ); ?>" placeholder="<?php _e( 'Enter your email', 'super-progressive-web-apps' ); ?>" style="width: 60%; margin-left: 0px;" type="email">		
									<input name="source" value="superpwa-plugin" type="hidden">
									<input type="submit" class="button" value="<?php _e( 'Subscribe', 'super-progressive-web-apps' ); ?>" style="background: linear-gradient(to right, #fdfc35, #ffe258) !important; box-shadow: unset;">
									<span class="superpwa_newsletter_hide" style="box-shadow: unset;cursor: pointer;margin-left: 10px;">
									<?php _e( 'No thanks', 'super-progressive-web-apps' ); ?>
									</span>
									<small style="display:block; margin-top:8px;"><?php _e( 'we\'ll share our <code>root</code> password before we share your email with anyone else.', 'super-progressive-web-apps' ); ?></small>
									
								</fieldset>
							</form>
							
						</div>
						
					</div>
								
				</div>
		</div>
	<?php }
			// Set newsletter marker to false
			  $superpwa_newsletter = false;
}