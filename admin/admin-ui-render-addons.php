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
		'pull_to_refresh' => array(
							'name'					=> __( 'Pull To Refresh', 'super-progressive-web-apps' ),
							'description'			=> __( 'Pull To Refresh to refresh your page', 'super-progressive-web-apps' ),
							'type'					=> 'bundled',
							'icon'					=> 'pull-to-refresh.png',
							'link'					=> 'https://superpwa.com/docs/',
							'admin_link'			=> admin_url( 'admin.php?page=superpwa-pull-to-refresh' ),
							'admin_link_text'		=> __( 'Customize Settings &rarr;', 'super-progressive-web-apps' ),
							'admin_link_target'		=> 'admin',
							'superpwa_min_version'	=> '1.0',
						),
		'utm_tracking' => array(
							'name'					=> __( 'UTM Tracking', 'super-progressive-web-apps' ),
							'description'			=> __( 'Track visits from your app by adding UTM tracking parameters to the Start Page URL.', 'super-progressive-web-apps' ),
							'type'					=> 'bundled',
							'icon'					=> 'utm-action.png',
							'link'					=> 'https://superpwa.com/addons/utm-tracking/',
							'admin_link'			=> admin_url( 'admin.php?page=superpwa-utm-tracking' ),
							'admin_link_text'		=> __( 'Customize Settings &rarr;', 'super-progressive-web-apps' ),
							'admin_link_target'		=> 'admin',
							'superpwa_min_version'	=> '1.7',
						),
		'apple_touch_icons' => array(
							'name'					=> __( 'Apple Touch Icons & Splash Screen', 'super-progressive-web-apps' ),
							'description'			=> __( 'Set the Application Icon and Splash Screen Icon as Apple Touch Icons for compatibility with iOS devices.', 'super-progressive-web-apps' ),
							'type'					=> 'bundled',
							'icon'					=> 'apple-touch.png',
							'link'					=> 'https://superpwa.com/addons/apple-touch-icons/',
							'admin_link'			=> admin_url( 'admin.php?page=superpwa-apple-icons' ),
							'admin_link_text'		=> __( 'More Details & Customize Settings &rarr;', 'super-progressive-web-apps' ),
							'admin_link_target'		=> 'admin',
							'superpwa_min_version'	=> '2.1.7',//1.8
						),
		'caching_strategies' => array(
							'name'					=> __( 'Caching Strategies', 'super-progressive-web-apps' ),
							'description'			=> __( 'To serve content from the cache and make your app available offline you need to intercept network requests and respond with files stored in the cache. ', 'super-progressive-web-apps' ),
							'type'					=> 'bundled',
							'icon'					=> 'caching-strategy.png',
							'link'					=> 'https://superpwa.com/addons/caching-strategies/',
							'admin_link'			=> admin_url( 'admin.php?page=superpwa-caching-strategies' ),
							'admin_link_text'		=> __( 'More Details &rarr;', 'super-progressive-web-apps' ),
							'admin_link_target'		=> 'admin',
							'superpwa_min_version'	=> '2.1.6',
						),
		'call_to_action' => array(
							'name'					=> __( 'Call To Action', 'super-progressive-web-apps' ),
							'description'			=> __( 'Easily gives notification banner your users to Add to Homescreen on website.', 'super-progressive-web-apps' ),
							'type'					=> 'addon_pro',
							'icon'					=> 'call-to-action.png',
							'link'					=> 'https://superpwa.com/doc/call-to-action-cta-add-on-for-superpwa/',
							'more_link'					=> 'https://superpwa.com/doc/call-to-action-cta-add-on-for-superpwa/',
							'admin_link'			=>  admin_url('admin.php?page=superpwa-call-to-action'),
							'admin_link_text'		=> __( 'Customize Settings &rarr;', 'super-progressive-web-apps' ),
							'admin_link_target'		=> 'admin',
							'superpwa_min_version'	=> '2.1.2',
						),
		'android_apk_app_generator' => array(
							'name'					=> __( 'Android APK APP Generator', 'super-progressive-web-apps' ),
							'description'			=> __( 'Easily generate APK APP of your current PWA website.', 'super-progressive-web-apps' ),
							'type'					=> 'addon_pro',
							'icon'					=> 'android-apk-app.png',
							'link'					=> 'https://superpwa.com/doc/android-apk-app-generator-add-on-for-superpwa/',
							'more_link'					=> 'https://superpwa.com/doc/android-apk-app-generator-add-on-for-superpwa/',
							'admin_link'			=> admin_url('admin.php?page=superpwa-android-apk-app'),
							'admin_link_text'		=> __( 'Customize Settings &rarr;', 'super-progressive-web-apps' ),
							'admin_link_target'		=> 'admin',
							'superpwa_min_version'	=> '2.1.2',
						),
		'app_shortcut' => array(
							'name'					=> __( 'APP Shortcuts', 'super-progressive-web-apps' ),
							'description'			=> __( 'APP shortcuts give quick access to a handful of common actions that users need frequently.', 'super-progressive-web-apps' ),
							'type'					=> 'addon_pro',
							'icon'					=> 'app-shortcut.png',
							'link'					=> 'https://superpwa.com/doc/app-shortcut-add-on-for-superpwa/',
							'more_link'					=> 'https://superpwa.com/doc/app-shortcut-add-on-for-superpwa/',
							'admin_link'			=> admin_url('admin.php?page=superpwa-app-shortcut'),
							'admin_link_text'		=> __( 'Customize Settings &rarr;', 'super-progressive-web-apps' ),
							'admin_link_target'		=> 'admin',
							'superpwa_min_version'	=> '2.1.6',
						),
		'data_analytics' => array(
							'name'					=> __( 'Data Analytics', 'super-progressive-web-apps' ),
							'description'			=> __( 'Data analytics allows you to see how many customers interact with your website using Super Progressive Web App.', 'super-progressive-web-apps' ),
							'type'					=> 'addon_pro',
							'icon'					=> 'data-analytics.png',
							'link'					=> 'https://superpwa.com/doc/data-analytics-add-on-for-superpwa/',
							'more_link'					=> 'https://superpwa.com/doc/data-analytics-add-on-for-superpwa/',
							'admin_link'			=> admin_url('admin.php?page=superpwa-data-analytics'),
							'admin_link_text'		=> __( 'Customize Settings &rarr;', 'super-progressive-web-apps' ),
							'admin_link_target'		=> 'admin',
							'superpwa_min_version'	=> '2.1.8',
						),
		'pre_loader' => array(
							'name'					=> __( 'PreLoader', 'super-progressive-web-apps' ),
							'description'			=> __( 'Set the Pre-Loading Feature and provide your user an eye catchy loading functionality to the site.', 'super-progressive-web-apps' ),
							'type'					=> 'addon_pro',
							'icon'					=> 'spinner-of-dots.png',
							'link'					=> 'https://superpwa.com/doc/preloader-add-on-for-superpwa/',
							'admin_link'			=> admin_url( 'admin.php?page=superpwa-pre-loader' ),
							'admin_link_text'		=> __( 'More Details & Customize Settings &rarr;', 'super-progressive-web-apps' ),
							'admin_link_target'		=> 'admin',
							'superpwa_min_version'	=> '2.1.18',//1.8
						),
		'qr_code_generator' => array(
							'name'					=> __( 'QR Code Generator', 'super-progressive-web-apps' ),
							'description'			=> __( 'Provides you with Install App QR Code which you can download and share with your users to give them easy to use and engaging user experience.', 'super-progressive-web-apps' ),
							'type'					=> 'addon_pro',
							'icon'					=> 'qr-code.png',
							'link'					=> 'https://superpwa.com/doc/qr-code-generator-add-on-for-superpwa/',
							'more_link'					=> 'https://superpwa.com/doc/qr-code-generator-add-on-for-superpwa/',
							'admin_link'			=>  admin_url('admin.php?page=superpwa-qr-code-generator'),
							'admin_link_text'		=> __( 'Customize Settings &rarr;', 'super-progressive-web-apps' ),
							'admin_link_target'		=> 'admin',
							'superpwa_min_version'	=> '2.2.3',
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
	<style type="text/css">.spwa-tab {overflow: hidden;border: 1px solid #ccc;background-color: #fff;margin-top: 15px;}.spwa-tab a {background-color: inherit;text-decoration: none;float: left;border: none;outline: none;cursor: pointer;padding: 14px 16px;transition: 0s;font-size: 15px;color: #2271b1;}.spwa-tab a:hover {color: #0a4b78;}.spwa-tab a.active {box-shadow: none;border-bottom: 4px solid #646970;color: #1d2327;}.spwa-tab a:focus {box-shadow: none;outline: none;}.spwa-tabcontent {display: none;padding: 6px 12px;border-top: none; animation: fadeEffect 1s; } @keyframes fadeEffect { from {opacity: 0;} to {opacity: 1;} }</style>
	<div class="wrap">
	<h1>Super Progressive Web Apps <sup><?php echo SUPERPWA_VERSION; ?></sup></h1>
       <?php superpwa_setting_tabs_html(); ?>
		<p><?php _e( 'Add-Ons extend the functionality of SuperPWA.', 'super-progressive-web-apps' ); ?></p>
		<style>.compatibility-compatible i:before{font-size: 16px; POSITION: RELATIVE;top: 3px;width: 15px;}</style>
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
									<a href="<?php echo $addon['link'] . (($addon['admin_link_target'] === 'external')? '?utm_source=superpwa-plugin&utm_medium=addon-card': '') ; ?>" target="_blank">
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
									<?php if ( superpwa_addons_status( $slug ) == 'active' ) { 
										printf( __( '<li class="compatibility-compatible"><a class="button activate-now button-secondary" href="%s"%s style="padding-left: 7px;"><i class="dashicons-before dashicons-admin-generic" style="vertical-align: sub;font-size: 8px;"></i> %s</a></li>', 'super-progressive-web-apps' ), $addon['admin_link'], $link_target, __('Settings','super-progressive-web-apps') ); 
									 }else{ ?>
									<li>
										<?php
                                            $link = $addon['link'] . (($addon['admin_link_target'] === 'external')?'?utm_source=superpwa-plugin&utm_medium=addon-card': '');
                                        if (isset($addon['more_link'])) {
                                            $link = $addon['more_link'] . (($addon['admin_link_target'] === 'external')?'?utm_source=superpwa-plugin&utm_medium=addon-card': '');
                                        }
                                        ?>
										<a href="<?php echo $link; ?>" target="_blank" aria-label="<?php printf(__('More information about %s', 'super-progressive-web-apps'), $addon['name']); ?>" data-title="<?php echo $addon['name']; ?>"><?php _e('More Details', 'super-progressive-web-apps'); ?></a>
									</li>
									<?php } ?>
								</ul>	
							</div>
							
							<div class="desc column-description">
								<p><?php echo $addon['description']; ?></p>
							</div>
							
						</div>
						
						<?php /*<div class="plugin-card-bottom">
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
								}  ?>
							</div>
						</div>*/ ?> 
						
					</div>
				<?php } ?>
			</div>
		</div>
		<details id="superpwa-ocassional-pop-up-container">
<summary class="superpwa-ocassional-pop-up-open-close-button"><?php esc_html_e('40% OFF - Limited Time Only', 'super-progressive-web-apps')?><svg fill="#fff" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 288.359 288.359" style="enable-background:new 0 0 288.359 288.359;" xml:space="preserve"><g><path d="M283.38,4.98c-3.311-3.311-7.842-5.109-12.522-4.972L163.754,3.166c-4.334,0.128-8.454,1.906-11.52,4.972L4.979,155.394   c-6.639,6.639-6.639,17.402,0,24.041L108.924,283.38c6.639,6.639,17.402,6.639,24.041,0l147.256-147.256   c3.065-3.065,4.844-7.186,4.972-11.52l3.159-107.103C288.49,12.821,286.691,8.291,283.38,4.98z M247.831,130.706L123.128,255.407   c-1.785,1.785-4.679,1.785-6.464,0l-83.712-83.712c-1.785-1.785-1.785-4.679,0-6.464L157.654,40.529   c1.785-1.785,4.679-1.785,6.464,0l83.713,83.713C249.616,126.027,249.616,128.921,247.831,130.706z M263.56,47.691   c-6.321,6.322-16.57,6.322-22.892,0c-6.322-6.321-6.322-16.57,0-22.892c6.321-6.322,16.569-6.322,22.892,0   C269.882,31.121,269.882,41.37,263.56,47.691z"/><path d="M99.697,181.278c-5.457,2.456-8.051,3.32-10.006,1.364c-1.592-1.591-1.5-4.411,1.501-7.412   c1.458-1.458,2.927-2.52,4.26-3.298c1.896-1.106,2.549-3.528,1.467-5.438l-0.018-0.029c-0.544-0.96-1.455-1.658-2.522-1.939   c-1.067-0.279-2.202-0.116-3.147,0.453c-1.751,1.054-3.64,2.48-5.587,4.428c-7.232,7.23-7.595,15.599-2.365,20.829   c4.457,4.457,10.597,3.956,17.463,0.637c5.004-2.364,7.55-2.729,9.46-0.819c2.002,2.002,1.638,5.004-1.545,8.186   c-1.694,1.694-3.672,3.044-5.582,4.06c-0.994,0.528-1.728,1.44-2.027,2.525c-0.3,1.085-0.139,2.245,0.443,3.208l0.036,0.06   c1.143,1.889,3.575,2.531,5.503,1.457c2.229-1.241,4.732-3.044,6.902-5.215c8.412-8.412,8.002-16.736,2.864-21.875   C112.475,178.141,107.109,177.868,99.697,181.278z"/><path d="M150.245,157.91l-31.508-16.594c-1.559-0.821-3.47-0.531-4.716,0.714l-4.897,4.898c-1.25,1.25-1.537,3.169-0.707,4.73   l16.834,31.654c0.717,1.347,2.029,2.274,3.538,2.5c1.509,0.225,3.035-0.278,4.114-1.357c1.528-1.528,1.851-3.89,0.786-5.771   l-3.884-6.866l8.777-8.777l6.944,3.734c1.952,1.05,4.361,0.696,5.928-0.871c1.129-1.129,1.654-2.726,1.415-4.303   C152.63,160.023,151.657,158.653,150.245,157.91z M125.621,165.632c0,0-7.822-13.37-9.187-15.644l0.091-0.092   c2.274,1.364,15.872,8.959,15.872,8.959L125.621,165.632z"/><path d="M173.694,133.727c-1.092,0-2.139,0.434-2.911,1.205l-9.278,9.278l-21.352-21.352c-0.923-0.923-2.175-1.441-3.479-1.441   s-2.557,0.519-3.479,1.441c-1.922,1.922-1.922,5.037,0,6.958l24.331,24.332c1.57,1.569,4.115,1.569,5.685,0l13.395-13.395   c1.607-1.607,1.607-4.213,0-5.821C175.833,134.16,174.786,133.727,173.694,133.727z"/><path d="M194.638,111.35l-9.755,9.755l-7.276-7.277l8.459-8.458c1.557-1.558,1.557-4.081-0.001-5.639   c-1.557-1.557-4.082-1.557-5.639,0l-8.458,8.458l-6.367-6.366l9.117-9.117c1.57-1.57,1.57-4.115,0-5.686   c-0.754-0.755-1.776-1.179-2.843-1.179c-1.066,0-2.089,0.424-2.843,1.178l-13.234,13.233c-0.753,0.754-1.177,1.776-1.177,2.843   c0,1.066,0.424,2.089,1.178,2.843l24.968,24.968c1.57,1.569,4.115,1.569,5.685,0l13.87-13.87c1.57-1.57,1.57-4.115,0-5.686   C198.752,109.78,196.208,109.78,194.638,111.35z"/></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg></summary>
<span class="superpwa-promotion-close-btn">  &times;  </span>
<div class="superpwa-ocassional-pop-up-contents">

	<img src="<?php echo SUPERPWA_PATH_SRC.'public/images/offers.png'?>" class="superpwa-promotion-surprise-icon" />
	<p class="superpwa-ocassional-pop-up-headline"><?php esc_html_e('40% OFF on ', 'super-progressive-web-apps')?><span><?php esc_html_e('SuperPWA PRO', 'super-progressive-web-apps')?></span></p>
	<p class="superpwa-ocassional-pop-up-second-headline"><?php esc_html_e('Upgrade the PRO version during this festive season and get our biggest discount of all time on New Purchases, Renewals &amp; Upgrades', 'super-progressive-web-apps')?></p>
	<a class="superpwa-ocassional-pop-up-offer-btn" href="<?php echo esc_url('https://superpwa.com/november-deal/')?>" target="_blank"><?php esc_html_e('Get This Offer Now', 'super-progressive-web-apps')?></a>
	<p class="superpwa-ocassional-pop-up-last-line"><?php esc_html_e('Black Friday, Cyber Monday, Christmas &amp; New year are the only times we offer discounts this big.', 'super-progressive-web-apps')?></p>
</div>

</details>
<style>details#superpwa-ocassional-pop-up-container{position:fixed;right:1rem;bottom:1rem;margin-top:2rem;color:#6b7280;display:flex;flex-direction:column;z-index:99999}details#superpwa-ocassional-pop-up-container div.superpwa-ocassional-pop-up-contents{background-color:#1e1e27;box-shadow:0 5px 10px rgba(0,0,0,.15);padding:25px 25px 10px;border-radius:8px;position:absolute;max-height:calc(100vh - 100px);width:350px;max-width:calc(100vw - 2rem);bottom:calc(100% + 1rem);right:0;overflow:auto;transform-origin:100% 100%;color:#95a3b9;margin-bottom:44px}details#superpwa-ocassional-pop-up-container div.superpwa-ocassional-pop-up-contents::-webkit-scrollbar{width:15px;background-color:#1e1e27}details#superpwa-ocassional-pop-up-container div.superpwa-ocassional-pop-up-contents::-webkit-scrollbar-thumb{width:5px;border-radius:99em;background-color:#95a3b9;border:5px solid #1e1e27}details#superpwa-ocassional-pop-up-container div.superpwa-ocassional-pop-up-contents>*+*{margin-top:.75em}details#superpwa-ocassional-pop-up-container div.superpwa-ocassional-pop-up-contents p>code{font-size:1rem;font-family:monospace}details#superpwa-ocassional-pop-up-container div.superpwa-ocassional-pop-up-contents pre{white-space:pre-line;border:1px solid #95a3b9;border-radius:6px;font-family:monospace;padding:.75em;font-size:.875rem;color:#fff}details#superpwa-ocassional-pop-up-container[open] div.superpwa-ocassional-pop-up-contents{bottom:0;-webkit-animation:.25s superpwa_ocassional_pop_up_scale;animation:.25s superpwa_ocassional_pop_up_scale}details#superpwa-ocassional-pop-up-container span.superpwa-promotion-close-btn{font-weight:400;font-size:20px;background:#37474f;font-family:sans-serif;border-radius:30px;color:#fff;position:absolute;right:-10px;z-index:99999;padding:0 8px;top:-311px;cursor:pointer;line-height:28px}details#superpwa-ocassional-pop-up-container div.superpwa-ocassional-pop-up-contents img.superpwa-promotion-surprise-icon{width:40px;float:left;margin-right:10px}details#superpwa-ocassional-pop-up-container div.superpwa-ocassional-pop-up-contents p.superpwa-ocassional-pop-up-headline{font-size:21px;margin:0;line-height:47px;font-weight:500;color:#fff}details#superpwa-ocassional-pop-up-container div.superpwa-ocassional-pop-up-contents p.superpwa-ocassional-pop-up-headline span{color:#F04720;font-weight:700}details#superpwa-ocassional-pop-up-container div.superpwa-ocassional-pop-up-contents p.superpwa-ocassional-pop-up-second-headline{font-size:16px;color:#fff}details#superpwa-ocassional-pop-up-container div.superpwa-ocassional-pop-up-contents a.superpwa-ocassional-pop-up-offer-btn{background:#1EB7E7;padding:13px 38px 14px;color:#fff;text-align:center;border-radius:60px;font-size:18px;display:inline-flex;align-items:center;margin:0 auto 15px;text-decoration:none;line-height:1.2;transform:perspective(1px) translateZ(0);box-shadow:0 0 20px 5px rgb(0 0 0 / 6%);transition:.3s ease-in-out;box-shadow:3px 5px .65em 0 rgb(0 0 0 / 15%);display:inherit}details#superpwa-ocassional-pop-up-container div.superpwa-ocassional-pop-up-contents p.superpwa-ocassional-pop-up-last-line{font-size:12px;color:#a6a6a6}details#superpwa-ocassional-pop-up-container summary{display:inline-flex;margin-left:auto;margin-right:auto;justify-content:center;align-items:center;font-weight:600;padding:.5em 1.25em;border-radius:99em;color:#fff;background-color:#185adb;box-shadow:0 5px 15px rgba(0,0,0,.1);list-style:none;text-align:center;cursor:pointer;transition:.15s;position:relative;font-size:.9rem;z-index:99999}details#superpwa-ocassional-pop-up-container summary::-webkit-details-marker{display:none}details#superpwa-ocassional-pop-up-container summary:hover,summary:focus{background-color:#1348af}details#superpwa-ocassional-pop-up-container summary svg{width:25px;margin-left:5px;vertical-align:baseline}@-webkit-keyframes superpwa_ocassional_pop_up_scale{0%{transform:superpwa_ocassional_pop_up_scale(0)}100%{transform:superpwa_ocassional_pop_up_scale(1)}}@keyframes superpwa_ocassional_pop_up_scale{0%{transform:superpwa_ocassional_pop_up_scale(0)}100%{transform:superpwa_ocassional_pop_up_scale(1)}}</style>
<script>function superpwa_set_admin_occasional_ads_pop_up_cookie(){var o=new Date;o.setFullYear(o.getFullYear()+1),document.cookie="superpwa_hide_admin_occasional_ads_pop_up_cookie_feedback=1; expires="+o.toUTCString()+"; path=/"}function superpwa_delete_admin_occasional_ads_pop_up_cookie(){document.cookie="superpwa_hide_admin_occasional_ads_pop_up_cookie_feedback=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;"}function superpwa_get_admin_occasional_ads_pop_up_cookie(){for(var o="superpwa_hide_admin_occasional_ads_pop_up_cookie_feedback=",a=decodeURIComponent(document.cookie).split(";"),e=0;e<a.length;e++){for(var c=a[e];" "==c.charAt(0);)c=c.substring(1);if(0==c.indexOf(o))return c.substring(o.length,c.length)}return""}jQuery(function(o){var a=superpwa_get_admin_occasional_ads_pop_up_cookie();0==a&&""==a&&jQuery("details#superpwa-ocassional-pop-up-container").attr("open",1);void 0!==a&&""!==a&&o("details#superpwa-ocassional-pop-up-container").attr("open",!1),o("details#superpwa-ocassional-pop-up-container span.superpwa-promotion-close-btn").click(function(a){o("details#superpwa-ocassional-pop-up-container summary").click()}),o("details#superpwa-ocassional-pop-up-container summary").click(function(a){var e=o(this).parents("details#superpwa-ocassional-pop-up-container"),c=o(e).attr("open");void 0!==c&&!1!==c?superpwa_set_admin_occasional_ads_pop_up_cookie():superpwa_delete_admin_occasional_ads_pop_up_cookie()})});</script>		       

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
				return 'upgrade';
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
			return __( 'Upgrade to PRO', 'super-progressive-web-apps' );
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

/**
 * Handle newsletter submit
 *
 *
 * @since 2.1.5
 */
function superpwa_newsletter_submit(){
	global $current_user;
	$api_url = 'http://magazine3.company/wp-json/api/central/email/subscribe';
    $api_params = array(
        'name' => esc_attr($current_user->display_name),
        'email'=> sanitize_text_field($_POST['email']),
        'website'=> sanitize_text_field( get_site_url() ),
        'type'=> 'superpwa'
    );
    $response = wp_remote_post( $api_url, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
    $response = wp_remote_retrieve_body( $response );
    echo json_encode(array('status'=>200, 'message'=>'Submitted ', 'response'=> $response));
    die;
}
add_action( 'wp_ajax_superpwa_newsletter_submit', 'superpwa_newsletter_submit' );
add_action( 'wp_ajax_nopriv_superpwa_newsletter_submit', 'superpwa_newsletter_submit' );

function superpwa_newsletter_hide_form(){
	
    $hide_newsletter  = get_option('superpwa_hide_newsletter');
    if($hide_newsletter == false){
    	add_option( 'superpwa_hide_newsletter', 'no' );
    }
	update_option( 'superpwa_hide_newsletter', 'yes' );
    echo json_encode(array('status'=>200, 'message'=>'Submitted '));
    die;
}
add_action( 'wp_ajax_superpwa_newsletter_hide_form', 'superpwa_newsletter_hide_form' );
add_action( 'wp_ajax_nopriv_superpwa_newsletter_hide_form', 'superpwa_newsletter_hide_form' );