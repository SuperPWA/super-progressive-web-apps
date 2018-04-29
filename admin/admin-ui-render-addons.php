<?php
/**
 * Add-Ons Settings UI
 *
 * @since 1.7
 * 
 * @function	superpwa_addons_interface_render()		Add-Ons UI renderer
 * @function	superpwa_addons_status()				Find add-on status
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

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
	if ( isset( $_GET['activate'] ) ) {
		
		// Add settings saved message with the class of "updated"
		add_settings_error( 'superpwa_settings_group', 'superpwa_addon_activated_message', __( 'Settings saved.', 'super-progressive-web-apps' ), 'updated' );
		
		// Show Settings Saved Message
		settings_errors( 'superpwa_settings_group' );
	}
	
	// Add-Ons array
	$addons = array(
		array(
			'name'					=> __( 'UTM Tracking', 'super-progressive-web-apps' ),
			'slug'					=> 'utm_tracking',
			'type'					=> 'bundled',
			'description'			=> __( 'Track visits from your app by adding UTM tracking parameters to the Start Page URL.', 'super-progressive-web-apps' ),
			'icon'					=> 'superpwa-128x128.png',
			'button_text'			=> __( 'Activate', 'super-progressive-web-apps' ),
			'button_link'			=> wp_nonce_url( admin_url( 'admin.php?page=superpwa-addons' ), 'activate', 'superpwa_addon_activate_nonce' ),
			'more_details'			=> 'https://superpwa.com/',
			'superpwa_min_version'	=> '1.7',
		),
	);
	
	?>
	
	<div class="wrap">
		<h1>Super Progressive Web Apps <sup><?php echo SUPERPWA_VERSION; ?></sup></h1>
		
		<p><?php _e( 'Add-Ons extend and expand the functionality of SuperPWA.', 'super-progressive-web-apps' ); ?></p>
		
		<!-- Add-Ons UI -->
		<form id="plugin-filter" method="post">
			<div class="wp-list-table widefat plugin-install">
			
				<h2 class="screen-reader-text">Add-Ons for SuperPWA</h2>	
				
				<div id="the-list">
				
					<?php foreach( $addons as $key => $addon ) { ?>
				
						<div class="plugin-card plugin-card-akismet">
						
							<div class="plugin-card-top">
							
								<div class="name column-name">
									<h3>
										<a href="<?php echo $addon['more_details']; ?>">
											<?php echo $addon['name']; ?>
											<img src="<?php echo SUPERPWA_PATH_SRC . 'admin/img/' . $addon['icon']; ?>" class="plugin-icon" alt="">
										</a>
									</h3>
								</div>
								
								<div class="action-links">
									<ul class="plugin-action-buttons">
										<li>
											<a class="button activate-now button-primary" data-slug="" href="<?php echo $addon['button_link']; ?>" aria-label<?php echo $addon['button_text'] . ' ' . $addon['name'] . ' now'; ?>" data-name="<?php echo $addon['name']; ?>"><?php echo $addon['button_text']; ?></a>
										</li>
										<li>
											<a href="<?php echo $addon['more_details']; ?>" aria-label="More information about <?php echo $addon['name']; ?>" data-title="<?php echo $addon['name']; ?>">More Details</a>
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
 * @param $slug this is the slug used in the $addons array in superpwa_addons_interface_render().
 * 		For add-ons installed as a separate plugin, this will be plugin-folder/main-plugin-file.php
 *
 * @return (string) one of the statuses as described above.
 *
 * @since 1.7
 */
function superpwa_addons_status( $slug, $type ) {
	
}