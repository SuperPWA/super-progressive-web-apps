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

	// Handing add-on activation
	if ( isset( $_GET['superpwa_addon_activate_nonce'] ) ) {
		
		// Handling activation
		
		wp_redirect( admin_url( 'admin.php?page=superpwa-addons&activated=1' ) );
		exit;
	}
	
	// Activation notice
	if ( isset( $_GET['activated'] ) ) {
		
		// Add settings saved message with the class of "updated"
		add_settings_error( 'superpwa_settings_group', 'superpwa_addon_activated_message', __( 'Add-On activated.', 'super-progressive-web-apps' ), 'updated' );
		
		// Show Settings Saved Message
		settings_errors( 'superpwa_settings_group' );
	}
	
	// Handing add-on de-activation
	if ( isset( $_GET['superpwa_addon_activate_nonce'] ) ) {
		
		// Handling deactivation
		
		wp_redirect( admin_url( 'admin.php?page=superpwa-addons&deactivated=1' ) );
		exit;
	}
	
	// De-activation notice
	if ( isset( $_GET['deactivated'] ) ) {
		
		// Add settings saved message with the class of "updated"
		add_settings_error( 'superpwa_settings_group', 'superpwa_addon_activated_message', __( 'Add-On deactivated.', 'super-progressive-web-apps' ), 'updated' );
		
		// Show Settings Saved Message
		settings_errors( 'superpwa_settings_group' );
	}
	
	// Get add-ons array
	$addons = superpwa_get_addons();
	
	?>
	
	<div class="wrap">
		<h1>Super Progressive Web Apps <sup><?php echo SUPERPWA_VERSION; ?></sup></h1>
		
		<p><?php _e( 'Add-Ons extend and expand the functionality of SuperPWA.', 'super-progressive-web-apps' ); ?></p>
		
		<!-- Add-Ons UI -->
		<form id="plugin-filter" method="post">
			<div class="wp-list-table widefat plugin-install">
			
				<h2 class="screen-reader-text">Add-Ons for SuperPWA</h2>	
				
				<div id="the-list">
				
					<?php 
					// Looping over each add-on
					foreach( $addons as $slug => $addon ) { ?>
				
						<div class="plugin-card plugin-card-akismet">
						
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
											<a class="button activate-now button-primary" data-slug="" href="<?php echo superpwa_addons_button_link( $slug ); ?>" aria-label<?php echo superpwa_addons_button_text( $slug ) . ' ' . $addon['name'] . ' now'; ?>" data-name="<?php echo $addon['name']; ?>"><?php echo superpwa_addons_button_text( $slug ); ?></a>
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
					
					<?php } ?>
					
				</div>
			</div>
		</form>
		
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
 * @return (string) one of the statuses as described above.
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
	
	// Get Settings
	$settings = superpwa_get_settings();
	
	switch( $addons[$slug]['type'] ) {
		
		// Bundled add-ons ships with SuperPWA and need not be installed separately.
		case 'bundled': 
			
			// True means, add-on is installed and active
			if ( isset( $settings['active_addons'] ) && in_array( $slug, $settings['active_addons'] ) ) {
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