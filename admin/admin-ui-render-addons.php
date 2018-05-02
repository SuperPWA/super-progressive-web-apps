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
 * @function 	superpwa_addons_activator()				Do bundled add-on activation and deactivation
 * @function	superpwa_addons_handle_activation()		Handle add-on activation and deactivation
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
 * 									'type'					=> 'bundled | addon',
 * 									'description'			=> 'Add-On description',
 * 									'icon'					=> 'icon-for-addon-128x128.png',
 * 									'link'					=> 'https://superpwa.com/addons/details-page-of-addon',
 * 									'superpwa_min_version'	=> '1.7' // min version of SuperPWA required to use the add-on.
 *								)
 *		);
 * 
 * @return (array) an associative array containing all the info about SuperPWA add-ons.
 * 
 * @since 1.7
 */
function superpwa_get_addons() {
	
	// Add-Ons array
	$addons = array(
		'utm_tracking' => array(
							'name'					=> __( 'UTM Tracking', 'super-progressive-web-apps' ),
							'type'					=> 'bundled',
							'description'			=> __( 'Track visits from your app by adding UTM tracking parameters to the Start Page URL.', 'super-progressive-web-apps' ),
							'icon'					=> 'superpwa-128x128.png',
							'link'					=> 'https://superpwa.com/addons/utm-tracking/',
							'superpwa_min_version'	=> '1.7',
						),
	);
	
	return $addons;
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

	// Add-on activation notice
	if ( isset( $_GET['activated'] ) ) {
			
		// Add settings saved message with the class of "updated"
		add_settings_error( 'superpwa_settings_group', 'superpwa_addon_activated_message', __( 'Add-On activated.', 'super-progressive-web-apps' ), 'updated' );
		
		// Show Settings Saved Message
		settings_errors( 'superpwa_settings_group' );		
	}
	
	// Add-on de-activation notice
	if ( isset( $_GET['deactivated'] ) ) {
			
		// Add settings saved message with the class of "updated"
		add_settings_error( 'superpwa_settings_group', 'superpwa_addon_deactivated_message', __( 'Add-On deactivated.', 'super-progressive-web-apps' ), 'updated' );
		
		// Show Settings Saved Message
		settings_errors( 'superpwa_settings_group' );
	}
	
	// Get add-ons array
	$addons = superpwa_get_addons();
	
	?>
	
	<div class="wrap">
		<h1>Super Progressive Web Apps <sup><?php echo SUPERPWA_VERSION; ?></sup></h1>
		
		<p><?php _e( 'Add-Ons extend the functionality of SuperPWA.', 'super-progressive-web-apps' ); ?></p>
		
		<!-- Add-Ons UI -->
		<div class="wp-list-table widefat addon-install">
			
			<div id="the-list">
			
				<?php 
				// Newsletter marker. Set this to false once newsletter subscription is displayed.
				$superpwa_newsletter = true;
				
				// Looping over each add-on
				foreach( $addons as $slug => $addon ) { ?>
			
					<div class="plugin-card plugin-card-<?php echo $slug; ?>">
					
						<div class="plugin-card-top">
						
							<div class="name column-name">
								<h3>
									<a href="<?php echo $addon['link']; ?>">
										<?php echo $addon['name']; ?>
										<img src="<?php echo SUPERPWA_PATH_SRC . 'admin/img/' . $addon['icon']; ?>" class="plugin-icon" alt="">
									</a>
								</h3>
							</div>
							
							<div class="action-links">
								<ul class="plugin-action-buttons">
									<li>
										<a class="button activate-now button-<?php echo superpwa_addons_button_text( $slug ) == 'Deactivate' ? 'secondary' : 'primary';  ?>" data-slug="<?php echo $slug; ?>" href="<?php echo superpwa_addons_button_link( $slug ); ?>" aria-label<?php echo superpwa_addons_button_text( $slug ) . ' ' . $addon['name'] . ' now'; ?>" data-name="<?php echo $addon['name']; ?>">
											<?php echo superpwa_addons_button_text( $slug ); ?>
										</a>
									</li>
									<li>
										<a href="<?php echo $addon['link']; ?>" aria-label="More information about <?php echo $addon['name']; ?>" data-title="<?php echo $addon['name']; ?>">More Details</a>
									</li>
								</ul>	
							</div>
							
							<div class="desc column-description">
								<p><?php echo $addon['description']; ?></p>
							</div>
							
						</div>
						
						<div class="plugin-card-bottom">
							<div class="column-compatibility">
								<?php if ( version_compare( SUPERPWA_VERSION, $addon['superpwa_min_version'], '>=' ) ) { ?>
									<span class="compatibility-compatible"><strong>Compatible</strong> with your version of SuperPWA</span>
								<?php } else { ?>
									<span class="compatibility-incompatible"><strong>Please upgrade</strong> to the latest version of SuperPWA</span>
								<?php } ?>
							</div>
						</div>
						
					</div>
					
					<?php if ( $superpwa_newsletter === true ) { ?>
					
						<div class="plugin-card plugin-card-superpwa-newsletter" style="background: #fdfc35 url('<?php echo SUPERPWA_PATH_SRC . 'admin/img/email.png'; ?>') no-repeat right top;">
					
							<div class="plugin-card-top" style="min-height: 178px;">
							
								<div class="name column-name" style="margin: 0px 10px;">
									<h3>SuperPWA Newsletter</h3>
								</div>
								
								<div class="desc column-description" style="margin: 0px 10px;">
									<p>Learn more about Progressive Web Apps<br>and get latest updates about SuperPWA</p>
								</div>
								
								<div class="superpwa-newsletter-form" style="margin: 18px 10px 0px;">
								
									<form method="post" action="https://superpwa.com/newsletter/" target="_blank">
										<fieldset>
											
											<input name="newsletter-email" value="<?php $user = wp_get_current_user(); echo esc_attr( $user->user_email ); ?>" placeholder="Enter your email" style="width: 60%; margin-left: 0px;" type="email">		
											<input name="source" value="superpwa-plugin" type="hidden">
											<input type="submit" class="button" value="Subscribe" style="background: linear-gradient(to right, #fdfc35, #ffe258) !important; box-shadow: unset;">
											
											<small style="display:block; margin-top:8px;">we'll share our <code>root</code> password before we share your email with anyone else.</small>
											
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
	
	// Get add-ons array
	$addons = superpwa_get_addons();
	
	// A security check to make sure that the add-on under consideration exist.
	if ( ! isset( $addons[$slug] ) ) {
		return false;
	}
	
	// Get active add-ons
	$active_addons = get_option( 'superpwa_active_addons', array() );
	
	switch( $addons[$slug]['type'] ) {
		
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
	
	// Get add-ons array
	$addons = superpwa_get_addons();
	
	switch( $addon_status ) {
		
		// Add-on inactive, send activation link.
		case 'inactive':
			
			// Plugin activation link for add-on plugins that are installed separately.
			if ( $addons[$slug]['type'] == 'addon' ) {
				wp_nonce_url( admin_url( 'plugins.php?action=activate&plugin=' . $slug ), 'activate-plugin_' . $slug );
			}
			
			// Activation link for bundled add-ons.
			return wp_nonce_url( admin_url( 'admin.php?page=superpwa-addons&addon=' . $slug ), 'activate', 'superpwa_addon_activate_nonce' );
			
			break;
			
		// Add-on active, send deactivation link.
		case 'active': 
		
			// Plugin deactivation link for add-on plugins that are installed separately.
			if ( $addons[$slug]['type'] == 'addon' ) {
				wp_nonce_url( admin_url( 'plugins.php?action=deactivate&plugin=' . $slug ), 'deactivate-plugin_' . $slug );
			}
			
			// Deactivation link for bundled add-ons.
			return wp_nonce_url( admin_url( 'admin.php?page=superpwa-addons&addon=' . $slug ), 'deactivate', 'superpwa_addon_deactivate_nonce' );
			
			break;
		
		// If add-on is not installed and for edge cases where $addon_status is false, we use the add-on link.
		case 'uninstalled':
		default:
			return $addons[$slug]['link'];
			break;
	}
}

/**
 * Do add-on activation and deactivation
 * 
 * Adds/removes the Add-On slug from the $settings['active_addons'] in SuperPWA settings.
 * 
 * @param $slug (string) this is the $key used in the $addons array in superpwa_get_addons().
 * @param $status (boolean) True to activate, False to deactivate.
 * 
 * @return (boolean) True on success, false otherwise. 
 *		Success = intended action, i.e. if deactivation is the intend, then success means successful deactivation.
 * 
 * @since 1.7
 */
function superpwa_addons_activator( $slug, $status ) {
	
	// Get the add-on status
	$addon_status = superpwa_addons_status( $slug );
	
	// Check if its a valid add-on
	if ( ! $addon_status ) {
		return false;
	}
	
	// Get active add-ons
	$active_addons = get_option( 'superpwa_active_addons', array() );
	
	// Activate add-on
	if ( ( $status === true ) && ( $addon_status == 'inactive' ) ) {
		
		// Add the add-on to the list of active add-ons
		$active_addons[] = $slug;
		
		// Write settings back to database
		update_option( 'superpwa_active_addons', $active_addons );
		
		return true;
	}
	
	// De-activate add-on
	if ( ( $status === false ) && ( $addon_status == 'active' ) ) {
		
		// Delete the add-on from the active_addons array in SuperPWA settings.
		$active_addons = array_flip( $active_addons );
		unset( $active_addons[$slug] );
		$active_addons = array_flip( $active_addons );
		
		// Write settings back to database
		update_option( 'superpwa_active_addons', $active_addons );
		
		// Deactivation action hook
		do_action( 'superpwa_addon_deactivated_' . $slug );
		
		return true;
	}
	
	return false;
}

/**
 * Handle add-on activation and deactivation
 * 
 * Verifies that the activation / deactivation request is valid and calls superpwa_addons_activator()
 * then redirects the page back to the add-ons page.
 * 
 * Hooked onto load-superpwa_page_superpwa-addons action hook and is called every time the add-ons page is loaded
 * 
 * @param void
 * @return void
 *
 * @since 1.7
 */
function superpwa_addons_handle_activation() {
	
	// Authentication
	if ( ! isset( $_GET['addon'] ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// Handing add-on activation
	if ( isset( $_GET['superpwa_addon_activate_nonce'] ) && isset( $_GET['addon'] ) && wp_verify_nonce( $_GET['superpwa_addon_activate_nonce'], 'activate' ) ) {
		
		// Handling activation
		if ( superpwa_addons_activator( $_GET['addon'], true ) === true ) {
			
			// Redirect to add-ons sub-menu
			wp_redirect( admin_url( 'admin.php?page=superpwa-addons&activated=1&addon=' . $_GET['addon'] ) );
			exit;
		}		
	}
	
	// Handing add-on de-activation
	if ( isset( $_GET['superpwa_addon_deactivate_nonce'] ) && isset( $_GET['addon'] ) && wp_verify_nonce( $_GET['superpwa_addon_deactivate_nonce'], 'deactivate' ) ) {
		
		// Handling deactivation
		if ( superpwa_addons_activator( $_GET['addon'], false ) === true ) {
			
			wp_redirect( admin_url( 'admin.php?page=superpwa-addons&deactivated=1&addon=' . $_GET['addon'] ) );
			exit;
		}
	}
}
add_action( 'load-superpwa_page_superpwa-addons', 'superpwa_addons_handle_activation' );