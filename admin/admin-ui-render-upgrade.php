<?php
/**
 * Upgrade to pro Settings UI
 *
 * @since 1.7
 * 
 * @function	superpwa_upgread_pro_interface_render()	Add-Ons UI renderer
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

function superpwa_upgread_pro_interface_render(){
	// Authentication
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?php _e( 'Upgrade for', 'super-progressive-web-apps' ); ?> SuperPWA <sup><?php echo SUPERPWA_VERSION; ?></sup></h1>
		<p><?php _e( 'Upgrade the functionality of SuperPWA.', 'super-progressive-web-apps' ); ?></p>
		<!-- Add-Ons UI -->
		<div class="wp-list-table widefat addon-install">
			
			<div id="the-list">
				<?php do_action("admin_upgrade_license_page"); ?>
			</div>
		</div>
	</div>
	<?php
}