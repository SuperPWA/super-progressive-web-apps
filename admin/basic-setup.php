<?php 
/**
 * Basic setup functions for the plugin
 *
 * @since 1.0
 * 
 * @function	superpwa_activate_plugin()			Plugin activatation todo list
 * @function	superpwa_activation_redirect()		Redirect to SuperPWA UI on plugin activation
 * @function	superpwa_admin_notices()			Admin notices
 * @function	superpwa_network_admin_notices()	Network Admin notices
 * @function	superpwa_upgrader()					Plugin upgrade todo list
 * @function	superpwa_deactivate_plugin()		Plugin deactivation todo list
 * @function	superpwa_load_plugin_textdomain()	Load plugin text domain
 * @function	superpwa_settings_link()			Print direct link to plugin settings in plugins list in admin
 * @function	superpwa_plugin_row_meta()			Add donate and other links to plugins list
 */

// Exit if accessed directly
if ( ! defined('ABSPATH') ) exit;
 
/**
 * Plugin activation todo list
 *
 * This function runs when user activates the plugin. Used in register_activation_hook()
 * On multisites, during network activation, this is fired only for the main site.
 * For the rest of the sites, superpwa_upgrader() handles generation of manifest and service worker. 
 *
 * @param $network_active (Boolean) True if the plugin is network activated, false otherwise. 
 * 
 * @author Arun Basil Lal
 * 
 * @link https://www.alexgeorgiou.gr/network-activated-wordpress-plugins/ (Thanks Alex!)
 * 
 * @since 1.0
 * @since 1.6 register_activation_hook() moved to this file (basic-setup.php) from main plugin file (superpwa.php).
 * @since 1.6 Added checks for multisite compatibility.
 */
function superpwa_activate_plugin( $network_active ) {
    
    // Adding option to show/hide newsletter form
	add_option( 'superpwa_hide_newsletter', 'no' );
	
	// Not network active i.e. plugin is activated on a single install (normal WordPress install) or a single site on a multisite network
	if ( ! $network_active ) {
		
		// Set transient for single site activation notice
		set_transient( 'superpwa_admin_notice_activation', true, 60 );
		
		return;
	}
		
	// If we are here, then plugin is network activated on a multisite. Set transient for activation notice on network admin.
	set_transient( 'superpwa_network_admin_notice_activation', true, 60 );

}
register_activation_hook( SUPERPWA_PATH_ABS . 'superpwa.php', 'superpwa_activate_plugin' );

/**
 * Redirect to SuperPWA UI on plugin activation.
 *
 * Will redirect to SuperPWA settings page when plugin is activated.
 * Will not redirect if multiple plugins are activated at the same time.
 * Will not redirect when activated network wide on multisite. Network admins know their way.
 * 
 * @param (string) $plugin Path to the main plugin file from plugins directory.
 * @param (bool) $network_wide True when network activated on multisites. False otherwise. 
 * 
 * @author Arun Basil Lal
 *
 * @since 2.0
 * @since 2.1 Added a check to see if WP_Plugins_List_Table class is available. 
 */
function superpwa_activation_redirect( $plugin, $network_wide ) {
	
	// Return if not SuperPWA or if plugin is activated network wide.
	if ( $plugin !== plugin_basename( SUPERPWA_PLUGIN_FILE ) || $network_wide === true ) {
		return false;
	}
	
	if ( ! class_exists( 'WP_Plugins_List_Table' ) ) {
		return false;
	}

	/**
	 * An instance of the WP_Plugins_List_Table class.
	 *
	 * @link https://core.trac.wordpress.org/browser/tags/4.9.8/src/wp-admin/plugins.php#L15
	 */
	$wp_list_table_instance = new WP_Plugins_List_Table();
	$current_action         = $wp_list_table_instance->current_action();

	// When only one plugin is activated, the current_action() method will return activate.
	if ( $current_action !== 'activate' ) {
		return false;
	}

	// Redirect to SuperPWA settings page. 
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	exit( wp_safe_redirect( esc_url(admin_url( 'admin.php?page=superpwa' )) ) );
}
add_action( 'activated_plugin', 'superpwa_activation_redirect', PHP_INT_MAX, 2 );

/**
 * Admin Notices
 *
 * @since 1.2 Admin notice on plugin activation
 */
function superpwa_admin_notices() {
	
	// Notices only for admins
	if ( ! current_user_can( superpwa_current_user_can() ) ) {
        return;
    }
 
    // Admin notice on plugin activation
	if ( get_transient( 'superpwa_admin_notice_activation' ) ) {
	
		$superpwa_is_ready = superpwa_is_pwa_ready() ? esc_html__( 'Your app is ready with the default settings. ', 'super-progressive-web-apps' ) : '';
		
		// Do not display link to settings UI if we are already in the UI.
		$screen = get_current_screen();
		
		echo '<div class="updated notice is-dismissible"><p>' . esc_html__( 'Thank you for installing <strong>Super Progressive Web Apps!</strong> ', 'super-progressive-web-apps' ) . esc_html($superpwa_is_ready) .( strpos( $screen->id, 'superpwa' ) === false ) ? '<a href="'.esc_url(admin_url( 'admin.php?page=superpwa' )).'">'.esc_html__( 'Customize your app', 'super-progressive-web-apps' ).' &rarr;</a>' : '' . '</p></div>';
		
		// Delete transient
		delete_transient( 'superpwa_admin_notice_activation' );
	}
	
	// Admin notice on plugin upgrade
	if ( get_transient( 'superpwa_admin_notice_upgrade_complete' ) ) {
		echo '<div class="updated notice is-dismissible"><p><strong>'.esc_html__('SuperPWA', 'super-progressive-web-apps').':</strong>' .esc_html__('Successfully updated to version', 'super-progressive-web-apps' ).'&nbsp;'.esc_html(SUPERPWA_VERSION) .'&nbsp;'.esc_html__('Thank you!', 'super-progressive-web-apps' ).'<a href="'.esc_url('https://superpwa.com/category/release-notes/latest/?utm_source=superpwa-plugin&utm_medium=update-success-notice').'" target="_blank">'.esc_html__('Discover new features and read the story', 'super-progressive-web-apps').'&rarr;</a></p></div>';
		
		// Delete transient
		delete_transient( 'superpwa_admin_notice_upgrade_complete' );
	}
}
add_action( 'admin_notices', 'superpwa_admin_notices' );

/**
 * Network Admin notices
 *
 * @since 1.6 Admin notice on multisite network activation
 */
function superpwa_network_admin_notices() {
	
	// Notices only for admins
	if ( ! current_user_can( superpwa_current_user_can() ) ) {
        return;
    }
 
    // Network admin notice on multisite network activation
	if ( get_transient( 'superpwa_network_admin_notice_activation' ) ) {
	
		$superpwa_is_ready = superpwa_is_pwa_ready() ? esc_html__('Your app is ready on the main website with the default settings. ', 'super-progressive-web-apps') : '';
		 
		echo '<div class="updated notice is-dismissible"><p>'.esc_html__( 'Thank you for installing', 'super-progressive-web-apps').'&nbsp;<strong>'.esc_html__( 'Super Progressive Web Apps!', 'super-progressive-web-apps').'</strong> '.esc_html( $superpwa_is_ready).'<a href="'.esc_url(admin_url( 'admin.php?page=superpwa' )).'">'.esc_html__( 'Customize your app', 'super-progressive-web-apps').' &rarr;</a><br/>'.esc_html__( 'Note: manifest and service worker for the individual websites will be generated on the first visit to the respective WordPress admin.', 'super-progressive-web-apps'). '</p></div>';
		
		// Delete transient
		delete_transient( 'superpwa_network_admin_notice_activation' );
	}
	
	// Network admin notice on plugin upgrade
	if ( get_transient( 'superpwa_admin_notice_upgrade_complete' ) ) {

		echo '<div class="updated notice is-dismissible"><p><strong>'.esc_html__('SuperPWA', 'super-progressive-web-apps').':</strong>' .esc_html__('Successfully updated to version', 'super-progressive-web-apps' ).'&nbsp;'.esc_html(SUPERPWA_VERSION) .'&nbsp;'.esc_html__('Thank you!', 'super-progressive-web-apps' ).'<a href="'.esc_url('https://superpwa.com/category/release-notes/latest/?utm_source=superpwa-plugin&utm_medium=update-success-notice-multisite').'" target="_blank">'.esc_html__('Discover new features and read the story', 'super-progressive-web-apps').'&rarr;</a></p></div>';
		
		// Delete transient
		delete_transient( 'superpwa_admin_notice_upgrade_complete' );
	}
}
add_action( 'network_admin_notices', 'superpwa_network_admin_notices' );

/**
 * Plugin upgrade todo list
 *
 * @since 1.3.1
 * @since 1.4 Added orientation setting and theme_color to database when upgrading from pre 1.4 versions.
 * @since 1.6 Added multisite compatibility.
 */
function superpwa_upgrader() {
	
	$current_ver = get_option( 'superpwa_version' );
	
	// Return if we have already done this todo
	if ( version_compare( $current_ver, SUPERPWA_VERSION, '==' ) ) {
		return;
	}
	
	/**
	 * Todo list for fresh install.
	 *
	 * On a multisite, during network activation, the activation hook (and activation todo) is not fired.
	 * Manifest and service worker is generated the first time the wp-admin is loaded (when admin_init is fired).
	 */
	if ( $current_ver === false ) {
		
		// Generate manifest
		superpwa_generate_manifest();
		
		// Generate service worker
		superpwa_generate_sw();
		
		if ( is_multisite() ) {
			
			// For multisites, save the activation status of current blog.
			superpwa_multisite_activation_status( true );
		}
		
		// Save SuperPWA version to database.
		add_option( 'superpwa_version', SUPERPWA_VERSION );
		
		return;
	}
	
	/**
	 * Add orientation and theme_color to database when upgrading from pre 1.4 versions.
	 * 
	 * Until 1.4, there was no UI for orientation and theme_color.
	 * In the manifest, orientation was hard coded as 'natural'.
	 * background_color had UI and this value was used for both background_color and theme_color in the manifest.
	 * 
	 * @since 1.4
	 */
	if ( version_compare( $current_ver, '1.3.1', '<=' ) ) {
		
		// Get settings
		$settings = superpwa_get_settings();
		
		// Orientation was set as 'natural' until version 1.4. Set it as 1, which is 'portrait'.
		$settings['orientation'] = 1;
		
		// theme_color was same as background_color until version 1.4
		$settings['theme_color'] = $settings['background_color'];
		
		// Write settings back to database
		update_option( 'superpwa_settings', $settings );
	}
	
	/**
	 * Delete existing service worker for single sites that use OneSignal.
	 * 
	 * For OneSignal compatibility, in version 1.8 the service worker filename is renamed. 
	 * If OneSignal is active, by this point, the new filename will be filtered in. 
	 * This upgrade routine restores the defaul service worker filename and deletes the existing service worker. 
	 * Also adds back the filter for new filename. OneSignal compatibility for multisites is not available at this point.
	 * 
	 * @since 1.8
	 */
	if ( version_compare( $current_ver, '1.7.1', '<=' ) && class_exists( 'OneSignal' ) && ! is_multisite() ) {
		
		// Restore the default service worker filename of SuperPWA.
		remove_filter( 'superpwa_sw_filename', 'superpwa_onesignal_sw_filename' );

		// Delete service worker
		superpwa_delete_sw();
		
		// Change service worker filename to match OneSignal's service worker.
		add_filter( 'superpwa_sw_filename', 'superpwa_onesignal_sw_filename' );
	}
	
	/**
	 * Add display to database when upgrading from pre 2.0 versions.
	 * Delete manifest and service worker files. 
	 * 
	 * Until 2.0, there was no UI for display.
	 * In the manifest, display was hard coded as 'standalone'.
	 * 
	 * Starting with 2.0, manifest and service worker files are dynamic and no longer static. 
	 * 
	 * @since 2.0
	 */
	if ( version_compare( $current_ver, '1.9', '<=' ) ) {
		
		// Get settings
		$settings = superpwa_get_settings();
		
		// Display was set as 'standalone' until version 2.0. Set it as 1, which is 'standalone'.
		$settings['display'] = 1;
		
		// Write settings back to database
		update_option( 'superpwa_settings', $settings );
	}
	
	/**
	 * Add file state variables to database
	 * 
	 * @since 2.0.1
	 */
	if ( version_compare( $current_ver, '2.0', '<=' ) ) {
		
		// Get settings
		$settings = superpwa_get_settings();
		
		// 1 for static files, 0 for dynamic files (default).
		$settings['is_static_manifest'] = 0;
		$settings['is_static_sw'] = 0;
		
		// Write settings back to database
		update_option( 'superpwa_settings', $settings );
	}
	
	// Re-generate manifest
	superpwa_generate_manifest();
	
	// Re-generate service worker
	superpwa_generate_sw();

	// Add current version to database
	update_option( 'superpwa_version', SUPERPWA_VERSION );
	
	// For multisites, save the activation status of current blog.
	superpwa_multisite_activation_status( true );
	
	// Set transient for upgrade complete notice
	set_transient( 'superpwa_admin_notice_upgrade_complete', true, 60 );
}
add_action( 'admin_init', 'superpwa_upgrader' );

/**
 * Plugin deactivation todo list
 *
 * Runs during deactivation. 
 * During uninstall uninstall.php is also executed.
 *
 * @param $network_active (Boolean) True if the plugin is network activated, false otherwise. 
 * @link https://www.alexgeorgiou.gr/network-activated-wordpress-plugins/ (Thanks Alex!)
 * 
 * @since 1.0
 * @since 1.6 register_deactivation_hook() moved to this file (basic-setup.php) from main plugin file (superpwa.php)
 */
function superpwa_deactivate_plugin( $network_active ) {

	// Delete manifest
	superpwa_delete_manifest();

	// Delete service worker
	superpwa_delete_sw();
	
	// For multisites, save the de-activation status of current blog.
	superpwa_multisite_activation_status( false );
	
	// Run the network deactivator during network deactivation
	if ( $network_active === true ) {
		superpwa_multisite_network_deactivator();
	}
}
register_deactivation_hook( SUPERPWA_PATH_ABS . 'superpwa.php', 'superpwa_deactivate_plugin' );

/**
 * Load plugin text domain
 *
 * @since 1.0
 */
function superpwa_load_plugin_textdomain() {
	load_plugin_textdomain( 'super-progressive-web-apps', false, '/super-progressive-web-apps/languages/' );
}
add_action( 'plugins_loaded', 'superpwa_load_plugin_textdomain' );

/**
 * Print direct link to plugin settings in plugins list in admin
 *
 * @since 1.0
 */
function superpwa_settings_link( $links ) {

	$pro_plugin = 'super-progressive-web-apps-pro/super-progressive-web-apps-pro.php';
			// True means, add-on is installed and active
			if ( is_plugin_active( $pro_plugin ) ) {
				// True means, add-on is installed and active
				return array_merge(
					array(
						'settings' => '<a href="' . esc_url(admin_url( 'admin.php?page=superpwa' )) . '">' . __( 'Settings', 'super-progressive-web-apps' ) . '</a>',
					),
					$links
				);
			}else{
				return array_merge(
					array(
						'settings' => '<a href="' .  esc_url(admin_url( 'admin.php?page=superpwa' )) . '">' . __( 'Settings', 'super-progressive-web-apps' ) . '</a>',
						'upgrade' => '<a href="' .  esc_url(admin_url( 'admin.php?page=superpwa-upgrade' )) . '">' . __( 'Upgrade to Pro', 'super-progressive-web-apps' ) . '</a>'
					),
					$links
				);
			
			}

}
add_filter( 'plugin_action_links_' . plugin_basename( SUPERPWA_PLUGIN_FILE ), 'superpwa_settings_link' );

/**
 * Add donate and other links to plugins list
 *
 * @since 1.0
 */
function superpwa_plugin_row_meta( $links, $file ) {
	
	if ( strpos( $file, 'superpwa.php' ) !== false ) {
		$new_links = array(
				'demo' 	=> '<a href="https://superpwa.com/?utm_source=superpwa-plugin&utm_medium=plugin_row_meta" target="_blank">' . __( 'Demo', 'super-progressive-web-apps' ) . '</a>',
				);
		$links = array_merge( $links, $new_links );
	}
	
	return $links;
}
add_filter( 'plugin_row_meta', 'superpwa_plugin_row_meta', 10, 2 );

/**
 * Adds rewrite rules to handle request to SW javascript and Manifest json.
 *
 * @since 2.0
 *
 * @uses superpwa_get_sw_filename()
 * @uses superpwa_get_manifest_filename()
 */
function superpwa_add_rewrite_rules() {
	$sw_filename = superpwa_get_sw_filename();
	add_rewrite_rule( "^/{$sw_filename}$",
		"index.php?{$sw_filename}=1"
	);

	$manifest_filename = superpwa_get_manifest_filename();
	add_rewrite_rule( "^/{$manifest_filename}$",
		"index.php?{$manifest_filename}=1"
	);
}

/**
 * Generates SW and Manifest on the fly.
 *
 * This way no physical files have to be placed on WP root folder. Hallelujah!
 * 
 * @author Maria Daniel Deepak <daniel@danieldeepak.com>
 *
 * @uses  superpwa_get_sw_filename()
 * @uses  superpwa_get_manifest_filename()
 * @uses  superpwa_manifest_template()
 * @uses  superpwa_sw_template()
 * 
 * @since 2.0
 * @since 2.1 uses http_build_query() instead of implode() to convert query_vars to string.
 */
function superpwa_generate_sw_and_manifest_on_fly( $query ) {
	if ( ! property_exists( $query, 'query_vars' ) || ! is_array( $query->query_vars ) ) {
		return;
	}
	$query_vars_as_string = http_build_query( $query->query_vars );
	$manifest_filename    = superpwa_get_manifest_filename();
	$sw_filename          = superpwa_get_sw_filename();
	$amphtml_filename    = 'superpwa-amp-sw.html';
	
	if ( strpos( $query_vars_as_string, $manifest_filename ) !== false ) {
		// Generate manifest from Settings and send the response w/ header.
		$pagemid =  isset($query->query_vars['superpwa_mid'])? $query->query_vars['superpwa_mid'] : null;
		header( 'Content-Type: application/json' );
		echo wp_json_encode( superpwa_manifest_template($pagemid) );
		exit();
	}
	$superpwa_settings = superpwa_get_settings();
	if(isset($superpwa_settings['is_any_multilang_enable']) && $superpwa_settings['is_any_multilang_enable'] == 1){
		superpwa_generate_manifest();
	}
    // Needed new query_vars of pagename for Wp Fastest Cache 
	if(class_exists('WpFastestCache')){
		$query_vars_as_string = isset($query->query_vars['pagename']) ? $query->query_vars['pagename'] : false;
		if($query_vars_as_string == false){
		$query_vars_as_string = isset($query->query_vars['name']) ? $query->query_vars['name'] : '';
	    }
    }

	if ( strpos( $query_vars_as_string, $sw_filename ) !== false ) {
		header( 'Content-Type: text/javascript' );
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo superpwa_sw_template();
		exit();
	}
	if ( strpos( $query_vars_as_string, $amphtml_filename ) !== false ) {
		header( 'Content-Type: text/html' );
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo amp_service_worker_template();
		exit();
	}
}

/**
 * Sets up the hooks once.
 *
 * Possibly put in the same order as execution for better understanding.
 *
 * @link  https://codex.wordpress.org/Plugin_API/Action_Reference Actions run during a typical Request.
 * @link  https://codex.wordpress.org/Plugin_API/Action_Reference/plugins_loaded
 *
 * @since 2.0
 */
function superpwa_setup_hooks() {
	add_action( 'init', 'superpwa_add_rewrite_rules' );
	add_action( 'parse_request', 'superpwa_generate_sw_and_manifest_on_fly' );
}
add_action( 'plugins_loaded', 'superpwa_setup_hooks' );

 

function superpwa_get_select2_data(){
        if ( ! isset( $_GET['superpwa_security_nonce'] ) ){
          return; 
        }
		if ( ! current_user_can( superpwa_current_user_can() ) ) {
			return; 
		}
        
        if ( (wp_verify_nonce( $_GET['superpwa_security_nonce'], 'superpwa_ajax_check_nonce' ) )){

			$search        = isset( $_GET['q'] ) ? sanitize_text_field( $_GET['q'] ) : '';                                    
			$type          = isset( $_GET['type'] ) ? sanitize_text_field( $_GET['type'] ) : '';

			$arg['post_type']      = $type;
            $arg['posts_per_page'] = 50;  
            $arg['post_status']    = 'any'; 

            if(!empty($search)){
              $arg['s']              = $search;
            }
			$result = array();
			$posts  = get_posts( $arg );
			if(!empty($posts)){
				foreach($posts as $post){  
					$result[] = array('id' => $post->ID, 'text' => $post->post_title);
				}
			}
          	wp_send_json(['results' => $result] );            

        }else{
          return;  
        }                
        
        wp_die();
}

add_action( 'wp_ajax_superpwa_get_select2_data', 'superpwa_get_select2_data');

function superpwa_enqueue_superpwa_select2_js( $hook ) {
	if($hook  == 'toplevel_page_superpwa'){

		wp_dequeue_script( 'select2-js' );   
		wp_dequeue_script( 'select2' );
		wp_deregister_script( 'select2' );
		//conflict with jupitor theme fixed starts here
		wp_dequeue_script( 'mk-select2' );
		wp_deregister_script( 'mk-select2' );                
		//conflict with jupitor theme fixed ends here                
		wp_dequeue_script( 'wds-shared-ui' );
		wp_deregister_script( 'wds-shared-ui' );
		wp_dequeue_script( 'pum-admin-general' );
		wp_deregister_script( 'pum-admin-general' );
		//Hide vidoe pro select2 on schema type dashboard
		wp_dequeue_script( 'cmb-select2' );
		wp_deregister_script( 'cmb-select2' );
	
		wp_enqueue_style('superpwa-select2-style', SUPERPWA_PATH_SRC. 'admin/css/select2.min.css' , false, SUPERPWA_VERSION);
		wp_enqueue_script('select2', SUPERPWA_PATH_SRC. 'admin/js/select2.min.js', array( 'jquery'), SUPERPWA_VERSION, true);
		wp_enqueue_script('select2-extended-script', SUPERPWA_PATH_SRC. 'admin/js/select2-extended.min.js', array( 'jquery' ), SUPERPWA_VERSION, true);
	  }
	
}
add_action( 'admin_enqueue_scripts', 'superpwa_enqueue_superpwa_select2_js',9999 );

add_filter('query_vars', 'superpwa_manifest_query_vars');

function superpwa_manifest_query_vars($vars) {
    $vars[] = 'superpwa_mid';
    return $vars;
}

/*
* Fix for nginx server when sw.js does not return 200 status code
*/
function superpwa_nginx_server_fix( $sw_url ) {
    if (isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'], 'nginx') !== false) {
		$response = wp_remote_head( $sw_url );
		if ( is_wp_error( $response ) ) {
			return '';
		}
		$response_code = wp_remote_retrieve_response_code( $response );
        return ( 200 !== $response_code ) ? '/':'';
    }
    return '';
}