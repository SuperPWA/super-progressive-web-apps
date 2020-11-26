<?php
/**
 * Add-Ons Settings UI
 *
 * @since 1.7
 * 
 * @function 	superpwa_get_addons()					Add-ons of SuperPWA
 * @function	superpwa_addons_interface_render()		Add-Ons UI renderer
 * @function	superpwa_addons_status()				Find add-on status
 * @function	superpwa_addons_button_text()			Button text based on add-on status
 * @function 	superpwa_addons_button_link() 			Action URL based on add-on status
 * @function	superpwa_addons_handle_activation()		Handle add-on activation
 * @function	superpwa_addons_handle_deactivation()	Handle add-on deactivation
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Add-ons of SuperPWA
 * 
 * An associative array containing all the add-ons of SuperPWA. 
 * 		array(
 *			'addon-slug'	=> 	array(
 *									'name'					=> 'Add-On Name',
 * 									'description'			=> 'Add-On description',
 * 									'type'					=> 'bundled | addon',
 * 									'icon'					=> 'icon-for-addon-128x128.png',
 * 									'link'					=> 'https://superpwa.com/addons/details-page-of-addon',
 * 									'admin_link'			=> admin_url( 'admin.php?page=superpwa-addon-admin-page' ),
 * 									'admin_link_text'		=> __( 'Customize settings | More Details &rarr;', 'super-progressive-web-apps' ),
 * 									'admin_link_target'		=> 'admin | external',
 * 									'superpwa_min_version'	=> '1.7' // min version of SuperPWA required to use the add-on.
 *								)
 *		);
 *
 * @param (string) addon-slug to retrieve the details about a specific add-on. False by default and then returns all add-ons.
 * 
 * @return (array|boolean) an associative array containing all the info about the requested add-on. False if add-on not found.
 * 
 * @since 1.7
 * @since 1.8 Returns false of $slug isn't found.
 */
function superpwa_get_addons( $slug = false ) {
	
	// Add-Ons array
	$addons = array(
		'utm_tracking' => array(
							'name'					=> __( 'UTM Tracking', 'super-progressive-web-apps' ),
							'description'			=> __( 'Track visits from your app by adding UTM tracking parameters to the Start Page URL.', 'super-progressive-web-apps' ),
							'type'					=> 'bundled',
							'icon'					=> 'superpwa-128x128.png',
							'link'					=> 'https://superpwa.com/addons/utm-tracking/',
							'admin_link'			=> admin_url( 'admin.php?page=superpwa-utm-tracking' ),
							'admin_link_text'		=> __( 'Customize Settings &rarr;', 'super-progressive-web-apps' ),
							'admin_link_target'		=> 'admin',
							'superpwa_min_version'	=> '1.7',
						),
		'apple_touch_icons' => array(
							'name'					=> __( 'Apple Touch Icons', 'super-progressive-web-apps' ),
							'description'			=> __( 'Set the Application Icon and Splash Screen Icon as Apple Touch Icons for compatibility with iOS devices.', 'super-progressive-web-apps' ),
							'type'					=> 'bundled',
							'icon'					=> 'superpwa-128x128.png',
							'link'					=> 'https://superpwa.com/addons/apple-touch-icons/',
							'admin_link'			=> 'https://superpwa.com/addons/apple-touch-icons/',
							'admin_link_text'		=> __( 'More Details &rarr;', 'super-progressive-web-apps' ),
							'admin_link_target'		=> 'external',
							'superpwa_min_version'	=> '1.8',
						),
		'call_to_action' => array(
							'name'					=> __( 'Call To Action', 'super-progressive-web-apps' ),
							'description'			=> __( 'Easily gives notification banner your users to Add to Homescreen on website.', 'super-progressive-web-apps' ),
							'type'					=> 'addon_pro',
							'icon'					=> 'superpwa-128x128.png',
							'link'					=> admin_url('admin.php?page=superpwa-upgrade'),
							'admin_link'			=>  admin_url('admin.php?page=superpwa-call-to-action'),
							'admin_link_text'		=> __( 'Customize Settings &rarr;', 'super-progressive-web-apps' ),
							'admin_link_target'		=> 'admin',
							'superpwa_min_version'	=> '1.8',
						),
		'android_apk_app_generator' => array(
							'name'					=> __( 'Android APK APP Generator', 'super-progressive-web-apps' ),
							'description'			=> __( 'Easily generate APK APP of your current PWA website.', 'super-progressive-web-apps' ),
							'type'					=> 'addon_pro',
							'icon'					=> 'superpwa-128x128.png',
							'link'					=> admin_url('admin.php?page=superpwa-upgrade'),
							'admin_link'			=> 'https://superpwa.com/addons/call-to-action/',
							'admin_link_text'		=> __( 'More Details &rarr;', 'super-progressive-web-apps' ),
							'admin_link_target'		=> 'admin',
							'superpwa_min_version'	=> '1.8',
						),
	);
	
	if ( $slug === false ) {
		return $addons;
	}
	
	if ( ! isset( $addons[$slug] ) ) {
		return false;
	}
	
	return $addons[$slug];
}

/**
 * Add-Ons UI renderer
 *
 * @since 1.7
 */ 
function superpwa_addons_interface_render() {
	
	// Authentication
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// Add-on activation todo
	if ( isset( $_GET['activated'] ) && isset( $_GET['addon'] ) ) {
		
		// Add-on activation action. Functions defined in the add-on file are loaded by now. 
		do_action( 'superpwa_addon_activated_' . $_GET['addon'] );
		
		// Get add-on info
		$addon = superpwa_get_addons( $_GET['addon'] );
		
		// Add UTM Tracking to admin_link_text if its not an admin page.
		if ( $addon['admin_link_target'] === 'external' ) {
			$addon['admin_link'] .= '?utm_source=superpwa-plugin&utm_medium=addon-activation-notice';
		}
		
		// Set link target attribute so that external links open in a new tab.
		$link_target = ( $addon['admin_link_target'] === 'external' ) ? 'target="_blank"' : '';
		
		if ( $addon !== false ) {
			
			// Add-on activation notice
			echo '<div class="updated notice is-dismissible"><p>' . sprintf( __( '<strong>Add-On activated: %s.</strong> <a href="%s"%s>%s</a>', 'super-progressive-web-apps' ), $addon['name'], $addon['admin_link'], $link_target, $addon['admin_link_text'] ) . '</p></div>';	
		}
	}
	
	// Add-on de-activation notice
	if ( isset( $_GET['deactivated'] ) ) {
			
		// Add settings saved message with the class of "updated"
		add_settings_error( 'superpwa_settings_group', 'superpwa_addon_deactivated_message', __( 'Add-On deactivated', 'super-progressive-web-apps' ), 'updated' );
		
		// Show Settings Saved Message
		settings_errors( 'superpwa_settings_group' );
	}
	
	// Get add-ons array
	$addons = superpwa_get_addons();
	
	?>
	
	<div class="wrap">
		<h1><?php _e( 'Add-ons for', 'super-progressive-web-apps' ); ?> SuperPWA <sup><?php echo SUPERPWA_VERSION; ?></sup></h1>
		
		<p><?php _e( 'Add-Ons extend the functionality of SuperPWA.', 'super-progressive-web-apps' ); ?></p>
		
		<!-- Add-Ons UI -->
		<div class="wp-list-table widefat addon-install">
			
			<div id="the-list">
			
				<?php 
				// Newsletter marker. Set this to false once newsletter subscription is displayed.
				$superpwa_newsletter = true;
				
				// Looping over each add-on
				foreach( $addons as $slug => $addon ) { 
				
					// Add UTM Tracking to admin_link_text if its not an admin page.
					if ( $addon['admin_link_target'] === 'external' ) {
						$addon['admin_link'] .= '?utm_source=superpwa-plugin&utm_medium=addon-card';
					}
					
					// Set link target attribute so that external links open in a new tab.
					$link_target = ( $addon['admin_link_target'] === 'external' ) ? 'target="_blank"' : '';
					
					?>
			
					<div class="plugin-card plugin-card-<?php echo $slug; ?>">
					
						<div class="plugin-card-top">
						
							<div class="name column-name">
								<h3>
									<a href="<?php echo $addon['link'] . '?utm_source=superpwa-plugin&utm_medium=addon-card'; ?>" target="_blank">
										<?php echo $addon['name']; ?>
										<img src="<?php echo SUPERPWA_PATH_SRC . 'admin/img/' . $addon['icon']; ?>" class="plugin-icon" alt="">
									</a>
								</h3>
							</div>
							
							<div class="action-links">
								<ul class="plugin-action-buttons">
									<li>
										<a class="button activate-now button-<?php echo superpwa_addons_button_text( $slug ) == __( 'Deactivate', 'super-progressive-web-apps' ) ? 'secondary' : 'primary';  ?>" data-slug="<?php echo $slug; ?>" href="<?php echo superpwa_addons_button_link( $slug ); ?>" aria-label<?php echo superpwa_addons_button_text( $slug ) . ' ' . $addon['name'] . ' now'; ?>" data-name="<?php echo $addon['name']; ?>">
											<?php echo superpwa_addons_button_text( $slug ); ?>
										</a>
									</li>
									<li>
										<a href="<?php echo $addon['link'] . '?utm_source=superpwa-plugin&utm_medium=addon-card'; ?>" target="_blank" aria-label="<?php printf( __( 'More information about %s', 'super-progressive-web-apps' ), $addon['name'] ); ?>" data-title="<?php echo $addon['name']; ?>"><?php _e( 'More Details', 'super-progressive-web-apps' ); ?></a>
									</li>
								</ul>	
							</div>
							
							<div class="desc column-description">
								<p><?php echo $addon['description']; ?></p>
							</div>
							
						</div>
						
						<div class="plugin-card-bottom">
							<div class="column-compatibility">
								<?php 
								if ( superpwa_addons_status( $slug ) == 'active' ) {
									printf( __( '<span class="compatibility-compatible"><strong>Add-On active.</strong> <a href="%s"%s>%s</a></span>', 'super-progressive-web-apps' ), $addon['admin_link'], $link_target, $addon['admin_link_text'] ); 
								} 
								else if ( version_compare( SUPERPWA_VERSION, $addon['superpwa_min_version'], '>=' ) ) {
									_e( '<span class="compatibility-compatible"><strong>Compatible</strong> with your version of SuperPWA</span>', 'super-progressive-web-apps' ); 
								} 
								else { 
									_e( '<span class="compatibility-incompatible"><strong>Please upgrade</strong> to the latest version of SuperPWA</span>', 'super-progressive-web-apps' );
								} ?>
							</div>
						</div>
						
					</div>
					
					<?php if ( $superpwa_newsletter === true ) { ?>
					
						<div class="plugin-card plugin-card-superpwa-newsletter" style="background: #fdfc35 url('<?php echo SUPERPWA_PATH_SRC . 'admin/img/email.png'; ?>') no-repeat right top;">
					
							<div class="plugin-card-top" style="min-height: 178px;">
							
								<div class="name column-name" style="margin: 0px 10px;">
									<h3><?php _e( 'SuperPWA Newsletter', 'super-progressive-web-apps' ); ?></h3>
								</div>
								
								<div class="desc column-description" style="margin: 0px 10px;">
									<p><?php _e( 'Learn more about Progressive Web Apps<br>and get latest updates about SuperPWA', 'super-progressive-web-apps' ); ?></p>
								</div>
								
								<div class="superpwa-newsletter-form" style="margin: 18px 10px 0px;">
								
									<form method="post" action="https://superpwa.com/newsletter/" target="_blank">
										<fieldset>
											
											<input name="newsletter-email" value="<?php $user = wp_get_current_user(); echo esc_attr( $user->user_email ); ?>" placeholder="<?php _e( 'Enter your email', 'super-progressive-web-apps' ); ?>" style="width: 60%; margin-left: 0px;" type="email">		
											<input name="source" value="superpwa-plugin" type="hidden">
											<input type="submit" class="button" value="<?php _e( 'Subscribe', 'super-progressive-web-apps' ); ?>" style="background: linear-gradient(to right, #fdfc35, #ffe258) !important; box-shadow: unset;">
											
											<small style="display:block; margin-top:8px;"><?php _e( 'we\'ll share our <code>root</code> password before we share your email with anyone else.', 'super-progressive-web-apps' ); ?></small>
											
										</fieldset>
									</form>
									
								</div>
								
							</div>
							
						</div>
						
						<?php 
					
						// Set newsletter marker to false
						$superpwa_newsletter = false;
					}
				} ?>
				
			</div>
		</div>
		
	</div>
	<?php
}

/**
 * Find add-on status
 *
 * Returns one of these statuses:
 *		active 			when the add-on is installed and active.
 *		inactive		when the add-on is installed but not activated.
 *		uninstalled		when the add-on is not installed and not available.
 * 
 * @param $slug this is the $key used in the $addons array in superpwa_get_addons().
 * 		For add-ons installed as a separate plugin, this will be plugin-directory/main-plugin-file.php
 *
 * @return (string) one of the statuses as described above. False if $slug is not a valid add-on.
 *
 * @since 1.7
 */
function superpwa_addons_status( $slug ) {
	
	// Get add-on details
	$addon = superpwa_get_addons( $slug );
	
	// A security check to make sure that the add-on under consideration exist.
	if ( $addon === false ) {
		return false;
	}
	
	// Get active add-ons
	$active_addons = get_option( 'superpwa_active_addons', array() );
	
	switch( $addon['type'] ) {
		
		// Bundled add-ons ships with SuperPWA and need not be installed separately.
		case 'bundled': 
			
			// True means, add-on is installed and active
			if ( in_array( $slug, $active_addons ) ) {
				return 'active';
			}
			
			// add-on is installed, but inactive
			return 'inactive';
			
			break;
			
		// Add-ons installed as a separate plugin
		case 'addon':
			
			// True means, add-on is installed and active
			if ( is_plugin_active( $slug ) ) {
				return 'active';
			}
			
			// Add-on is inactive, check if add-on is installed
			if ( file_exists( WP_PLUGIN_DIR . '/' . $slug ) ) {
				return 'inactive';
			}
			
			// If we are here, add-on is not installed and not active
			return 'uninstalled';
			
			break;
		// Add-ons pro installed as a separate plugin
		case 'addon_pro':
			$pro_plugin = 'super-progressive-web-apps-pro/super-progressive-web-apps-pro.php';
			// True means, add-on is installed and active
			if ( is_plugin_active( $pro_plugin ) ) {
				// True means, add-on is installed and active
				if ( in_array( $slug, $active_addons ) ) {
					return 'active';
				}
				return 'inactive';
			}
			
			
			// Add-on is inactive, check if add-on is installed
			if ( file_exists( WP_PLUGIN_DIR . '/' . $pro_plugin ) ) {
				return 'inactive';
			}

			// If we are here, add-on is not installed and not active
			return 'upgrade';
			
			
			
			break;
			
		default:
			return false;
			break;
	}
}

/**
 * Button text based on add-on status
 *
 * @param $slug this is the $key used in the $addons array in superpwa_get_addons().
 * 		For add-ons installed as a separate plugin, this will be plugin-directory/main-plugin-file.php
 * 
 * @return (string)	'Activate', if plugin status is 'inactive'
 * 					'Deactivate', if plugin status is 'active'
 * 					'Install', if plugin status is 'uninstalled'
 *
 * @since 1.7
 */
function superpwa_addons_button_text( $slug ) {
	
	// Get the add-on status
	$addon_status = superpwa_addons_status( $slug );
	
	switch( $addon_status ) {
		
		case 'inactive':
			return __( 'Activate', 'super-progressive-web-apps' );
			break;
			
		case 'active': 
			return __( 'Deactivate', 'super-progressive-web-apps' );
			break;
			
		case 'upgrade':
			return __( 'Upgrade', 'super-progressive-web-apps' );
			break;
		case 'uninstalled':
		default: // Safety net for edge cases if any.
			return __( 'Install', 'super-progressive-web-apps' );
			break;
	}
}

/**
 * Action URL based on add-on status
 *
 * @param $slug this is the $key used in the $addons array in superpwa_get_addons().
 * 		For add-ons installed as a separate plugin, this will be plugin-directory/main-plugin-file.php
 * 
 * @return (string) activation / deactivation / install url with nonce as necessary
 *
 * @since 1.7
 */
function superpwa_addons_button_link( $slug ) {
	
	// Get the add-on status
	$addon_status = superpwa_addons_status( $slug );
	
	// Get add-on details
	$addon = superpwa_get_addons( $slug );
	
	switch( $addon_status ) {
		
		// Add-on inactive, send activation link.
		case 'inactive':
			
			// Plugin activation link for add-on plugins that are installed separately.
			if ( $addon['type'] == 'addon' ) {
				wp_nonce_url( admin_url( 'plugins.php?action=activate&plugin=' . $slug ), 'activate-plugin_' . $slug );
			}
			
			// Activation link for bundled add-ons.
			return wp_nonce_url( admin_url( 'admin-post.php?action=superpwa_activate_addon&addon=' . $slug ), 'activate', 'superpwa_addon_activate_nonce' );
			
			break;
			
		// Add-on active, send deactivation link.
		case 'active': 
		
			// Plugin deactivation link for add-on plugins that are installed separately.
			if ( $addon['type'] == 'addon' ) {
				wp_nonce_url( admin_url( 'plugins.php?action=deactivate&plugin=' . $slug ), 'deactivate-plugin_' . $slug );
			}
			
			// Deactivation link for bundled add-ons.
			return wp_nonce_url( admin_url( 'admin-post.php?action=superpwa_deactivate_addon&addon=' . $slug ), 'deactivate', 'superpwa_addon_deactivate_nonce' );
			
			break;
		
		// If add-on is not installed and for edge cases where $addon_status is false, we use the add-on link.
		case 'uninstalled':
		case 'upgrade':
		default:
			return $addon['link'];
			break;
	}
}

/**
 * Handle add-on activation
 * 
 * Verifies that the activation request is valid and then redirects the page back to the add-ons page.
 * Hooked onto admin_post_superpwa_activate_addon action hook
 *
 * @since 1.7
 * @since 1.8 Handles only activation. Used to handle both activation and deactivation.
 * @since 1.8 Hooked onto admin_post_superpwa_activate_addon. Was hooked to load-superpwa_page_superpwa-addons before. 
 */
function superpwa_addons_handle_activation() {
	
	// Get the add-on status
	$addon_status = superpwa_addons_status( $_GET['addon'] );
	
	// Authentication
	if ( 
		! current_user_can( 'manage_options' ) || 
		! isset( $_GET['addon'] ) || 
		! ( isset( $_GET['superpwa_addon_activate_nonce'] ) && wp_verify_nonce( $_GET['superpwa_addon_activate_nonce'], 'activate' ) ) || 
		! ( $addon_status == 'inactive' ) 
	) {
		
		// Return to referer if authentication fails.
		wp_redirect( admin_url( 'admin.php?page=superpwa-addons' ) );
		exit;
	}
		
	// Get active add-ons
	$active_addons = get_option( 'superpwa_active_addons', array() );
	
	// Add the add-on to the list of active add-ons
	$active_addons[] = $_GET['addon'];
	
	// Write settings back to database
	update_option( 'superpwa_active_addons', $active_addons );
		
	// Redirect back to add-ons sub-menu
	wp_redirect( admin_url( 'admin.php?page=superpwa-addons&activated=1&addon=' . $_GET['addon'] ) );
	exit;
}
add_action( 'admin_post_superpwa_activate_addon', 'superpwa_addons_handle_activation' );

/**
 * Handle add-on deactivation
 * 
 * Verifies that the deactivation request is valid and then redirects the page back to the add-ons page.
 * Hooked onto admin_post_superpwa_deactivate_addon action hook.
 *
 * @since 1.8
 */
function superpwa_addons_handle_deactivation() {
	
	// Get the add-on status
	$addon_status = superpwa_addons_status( $_GET['addon'] );
	
	// Authentication
	if ( 
		! current_user_can( 'manage_options' ) || 
		! isset( $_GET['addon'] ) || 
		! ( isset( $_GET['superpwa_addon_deactivate_nonce'] ) && wp_verify_nonce( $_GET['superpwa_addon_deactivate_nonce'], 'deactivate' ) ) || 
		! ( $addon_status == 'active' ) 
	) {
		
		// Return to referer if authentication fails.
		wp_redirect( admin_url( 'admin.php?page=superpwa-addons' ) );
		exit;
	}
	
	// Get active add-ons
	$active_addons = get_option( 'superpwa_active_addons', array() );
	
	// Delete the add-on from the active_addons array in SuperPWA settings.
	$active_addons = array_flip( $active_addons );
	unset( $active_addons[ $_GET['addon'] ] );
	$active_addons = array_flip( $active_addons );
		
	// Write settings back to database
	update_option( 'superpwa_active_addons', $active_addons );
		
	// Add-on deactivation action. Functions defined in the add-on file are still availalbe at this point. 
	do_action( 'superpwa_addon_deactivated_' . $_GET['addon'] );
	
	// Redirect back to add-ons sub-menu
	wp_redirect( admin_url( 'admin.php?page=superpwa-addons&deactivated=1&addon=' . $_GET['addon'] ) );
	exit;
}
add_action( 'admin_post_superpwa_deactivate_addon', 'superpwa_addons_handle_deactivation' );