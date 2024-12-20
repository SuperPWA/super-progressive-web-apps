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
							'category'				=> 'subtab-all subtab-usability',
						),
		'push_notification_for_superpwa' => array(
							'name'					=> __( 'Push Notification', 'super-progressive-web-apps' ),
							'description'			=> __( 'Push notification provides you to send push notification.', 'super-progressive-web-apps' ),
							'type'					=> 'bundled',
							'icon'					=> 'notification.jpg',
							'link'					=> 'https://superpwa.com/doc/push-notification-for-superpwa/',
							'more_link'				=> 'https://superpwa.com/doc/push-notification-for-superpwa/',
							'admin_link'			=>  admin_url('admin.php?page=superpwa-push-notification'),
							'admin_link_text'		=> __( 'Customize Settings &rarr;', 'super-progressive-web-apps' ),
							'admin_link_target'		=> 'admin',
							'superpwa_min_version'	=> '2.2.19',
							'category'				=> 'subtab-all subtab-popular',
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
							'superpwa_min_version'	=> '2.1.7',//1.8,
							'category'				=> 'subtab-all subtab-popular subtab-usability',
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
							'category'				=> 'subtab-all subtab-popular',
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
							'category'				=> 'subtab-all subtab-popular',
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
							'category'				=> 'subtab-all subtab-app',
						),
		'apple_ipa_app_generator' => array(
							'name'					=> __( 'Apple App Package Generator', 'super-progressive-web-apps' ),
							'description'			=> __( 'Easily generate package and then IPA APP of your current PWA website.', 'super-progressive-web-apps' ),
							'type'					=> 'addon_pro',
							'icon'					=> 'apple-ipa.png',
							'link'					=> 'https://superpwa.com/doc',
							'more_link'				=> 'https://superpwa.com/doc',
							'admin_link'			=> admin_url('admin.php?page=superpwa-apple-ipa-app'),
							'admin_link_text'		=> __( 'Customize Settings &rarr;', 'super-progressive-web-apps' ),
							'admin_link_target'		=> 'admin',
							'superpwa_min_version'	=> '2.1.2',
							'category'				=> 'subtab-all subtab-app',
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
							'category'				=> 'subtab-all subtab-app',
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
							'category'				=> 'subtab-all subtab-analytics',
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
							'category'				=> 'subtab-all subtab-usability',
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
							'category'				=> 'subtab-all subtab-usability',
						),
		'wpml_for_superpwa' => array(
							'name'					=> __( 'WPML', 'super-progressive-web-apps' ),
							'description'			=> __( 'Provides you to install pwa on WPML site based on selected language.', 'super-progressive-web-apps' ),
							'type'					=> 'addon_pro',
							'icon'					=> 'wpml.png',
							'link'					=> 'https://superpwa.com/docs/article/how-to-enable-superpwa-on-wpml-multilingual-site/',
							'more_link'					=> 'https://superpwa.com/docs/article/how-to-enable-superpwa-on-wpml-multilingual-site/',
							'admin_link'			=>  admin_url('admin.php?page=superpwa-wpml'),
							'admin_link_text'		=> __( 'Customize Settings &rarr;', 'super-progressive-web-apps' ),
							'admin_link_target'		=> 'admin',
							'superpwa_min_version'	=> '2.2.18',
							'category'				=> 'subtab-all subtab-multilingual',
						),
		'wp_multilang_for_superpwa' => array(
							'name'					=> __( 'WP Multilang', 'super-progressive-web-apps' ),
							'description'			=> __( 'Provides you to install pwa on WP-Multilang site based on selected language.', 'super-progressive-web-apps' ),
							'type'					=> 'addon_pro',
							'icon'					=> 'wp-multilang.png',
							'link'					=> 'https://superpwa.com/docs/',
							'more_link'					=> 'https://superpwa.com/docs/',
							'admin_link'			=>  admin_url('admin.php?page=superpwa-wp_multilang'),
							'admin_link_text'		=> __( 'Customize Settings &rarr;', 'super-progressive-web-apps' ),
							'admin_link_target'		=> 'admin',
							'superpwa_min_version'	=> '2.2.27',
							'category'				=> 'subtab-all subtab-multilingual',
						),
		'polylang_for_superpwa' => array(
							'name'					=> __( 'Polylang', 'super-progressive-web-apps' ),
							'description'			=> __( 'Provides you to install pwa on Polylang site based on selected language.', 'super-progressive-web-apps' ),
							'type'					=> 'addon_pro',
							'icon'					=> 'polylang.png',
							'link'					=> 'https://superpwa.com/docs/',
							'more_link'					=> 'https://superpwa.com/docs/',
							'admin_link'			=>  admin_url('admin.php?page=superpwa-polylang'),
							'admin_link_text'		=> __( 'Customize Settings &rarr;', 'super-progressive-web-apps' ),
							'admin_link_target'		=> 'admin',
							'superpwa_min_version'	=> '2.2.27',
							'category'				=> 'subtab-all subtab-multilingual',
						),
		'translatepress_for_superpwa' => array(
							'name'					=> __( 'Translatepress', 'super-progressive-web-apps' ),
							'description'			=> __( 'Provides you to install pwa on translatepress site based on selected language.', 'super-progressive-web-apps' ),
							'type'					=> 'addon_pro',
							'icon'					=> 'translatepress.png',
							'link'					=> 'https://superpwa.com/docs/',
							'more_link'					=> 'https://superpwa.com/docs/',
							'admin_link'			=>  admin_url('admin.php?page=superpwa-translatepress'),
							'admin_link_text'		=> __( 'Customize Settings &rarr;', 'super-progressive-web-apps' ),
							'admin_link_target'		=> 'admin',
							'superpwa_min_version'	=> '2.2.27',
							'category'				=> 'subtab-all subtab-multilingual',
						),
		'navigation_bar_for_superpwa' => array(
							'name'					=> __( 'Navigation Bar', 'super-progressive-web-apps' ),
							'description'			=> __( 'Navigate should be a high priority for almost every app. The easier your product is for people to use, the more likely they’ll be to use it..', 'super-progressive-web-apps' ),
							'type'					=> 'addon_pro',
							'icon'					=> 'navigation-bar.png',
							'link'					=> 'https://superpwa.com/doc/navigation-bar-for-super-pwa/',
							'more_link'					=> 'https://superpwa.com/doc/navigation-bar-for-super-pwa/',
							'admin_link'			=>  admin_url('admin.php?page=superpwa-navigation-bar'),
							'admin_link_text'		=> __( 'Customize Settings &rarr;', 'super-progressive-web-apps' ),
							'admin_link_target'		=> 'admin',
							'superpwa_min_version'	=> '2.2.20',
							'category'				=> 'subtab-all subtab-navigation',
						),
		'visibility_for_superpwa' => array(
							'name'					=> __( 'Visibility', 'super-progressive-web-apps' ),
							'description'			=> __( 'Visibility allows you to include or exclude pages/posts from installation/displaying of PWA', 'super-progressive-web-apps' ),
							'type'					=> 'addon_pro',
							'icon'					=> 'visibility.png',
							'link'					=> 'https://superpwa.com/docs/',
							'more_link'					=> 'https://superpwa.com/docs/',
							'admin_link'			=>  admin_url('admin.php?page=superpwa-visibility'),
							'admin_link_text'		=> __( 'Customize Settings &rarr;', 'super-progressive-web-apps' ),
							'admin_link_target'		=> 'admin',
							'superpwa_min_version'	=> '2.2.26',
							'category'				=> 'subtab-all subtab-usability',
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
							'category'				=> 'subtab-all subtab-analytics',
						),
		'offline_form_for_superpwa' => array(
							'name'					=> __( 'Offline Form', 'super-progressive-web-apps' ),
							'description'			=> __( 'Store your forms data when you are offline and sync data when you are online with one click.', 'super-progressive-web-apps' ),
							'type'					=> 'addon_pro',
							'icon'					=> 'offline.png',
							'link'					=> 'https://superpwa.com/docs',
							'more_link'					=> 'https://superpwa.com/docs',
							'admin_link'			=>  admin_url('admin.php?page=superpwa-offline-form'),
							'admin_link_text'		=> __( 'Customize Settings &rarr;', 'super-progressive-web-apps' ),
							'admin_link_target'		=> 'admin',
							'superpwa_min_version'	=> '2.2.32',
							'category'				=> 'subtab-all subtab-usability',
						),
	
	);
	
	if ( $slug === false ) {
		ksort($addons);
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
	if ( ! current_user_can( superpwa_current_user_can() ) ) {
        return;
    }

	// Add-on activation todo
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Reason: We are not processing form information.
	if ( isset( $_GET['activated'] ) && isset( $_GET['addon'] ) ) {
		
		// Add-on activation action. Functions defined in the add-on file are loaded by now. 
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Reason: We are not processing form information.
		do_action( 'superpwa_addon_activated_' . sanitize_title($_GET['addon'] ));
		
		// Get add-on info
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Reason: We are not processing form information.
		$addon = superpwa_get_addons( sanitize_title($_GET['addon']) );
		
		// Add UTM Tracking to admin_link_text if its not an admin page.
		if ( $addon['admin_link_target'] === 'external' ) {
			$addon['admin_link'] .= '?utm_source=superpwa-plugin&utm_medium=addon-activation-notice';
		}
		
		
		if ( $addon !== false ) {
			// Add-on activation notice
			// phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText
			echo '<div class="updated notice is-dismissible"><p><strong>'.esc_html__("Add-On activated:", "super-progressive-web-apps").$addon['name'].'</strong> <a href="'.esc_url($addon['admin_link']).'"'.( $addon['admin_link_target'] === 'external' ) ? 'target="_blank"' : "".'>'.esc_html__($addon['admin_link_text'],"super-progressive-web-apps"	).'</a></p></div>';	
		}
	}
	
	// Add-on de-activation notice
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Reason: We are not processing form information.
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
	<h1><?php esc_html_e('Super Progressive Web Apps', 'super-progressive-web-apps' ); ?> <sup class="superpwa_version"><?php echo esc_html(SUPERPWA_VERSION); ?></sup></h1>
       <?php superpwa_setting_tabs_html(); ?>
		<p class="superpwa-addons-headp"><?php esc_html_e( 'Add-Ons extend the functionality of SuperPWA.', 'super-progressive-web-apps' ); ?></p>
		<div class="superpwa-sub-tab-headings">
			<span data-tab-id="subtab-all" class="selected"><?php esc_html_e( 'All', 'super-progressive-web-apps' ); ?></span>&nbsp;|&nbsp;
			<span data-tab-id="subtab-popular"><?php esc_html_e( 'Popular', 'super-progressive-web-apps' ); ?></span>&nbsp;|&nbsp;
			<span data-tab-id="subtab-analytics"><?php esc_html_e( 'Analytics', 'super-progressive-web-apps' ); ?></span>&nbsp;|&nbsp;
			<span data-tab-id="subtab-app"><?php esc_html_e( 'App generation', 'super-progressive-web-apps' ); ?></span>&nbsp;|&nbsp;
			<span data-tab-id="subtab-navigation"><?php esc_html_e( 'Navigation', 'super-progressive-web-apps' ); ?></span>&nbsp;|&nbsp;
			<span data-tab-id="subtab-multilingual"><?php esc_html_e( 'Multilingual', 'super-progressive-web-apps' ); ?></span>&nbsp;|&nbsp;
			<span data-tab-id="subtab-usability"><?php esc_html_e( 'Usability', 'super-progressive-web-apps' ); ?></span>
		</div>
		<br/>
		<style>.compatibility-compatible i:before{font-size: 16px; POSITION: RELATIVE;top: 3px;width: 15px;}</style>
		<!-- Add-Ons UI -->
		<div class="wp-list-table widefat addon-install">
			
			<div id="the-list">
				
				<?php 
				// Newsletter marker. Set this to false once newsletter subscription is displayed.
				$superpwa_newsletter = true;
				$superpwa_upgrade_class_addons ='';
				// Looping over each add-on
				foreach( $addons as $slug => $addon ) { 
				
					// Add UTM Tracking to admin_link_text if its not an admin page.
					if ( $addon['admin_link_target'] === 'external' ) {
						$addon['admin_link'] .= '?utm_source=superpwa-plugin&utm_medium=addon-card';
					}
					
					// Set link target attribute so that external links open in a new tab.
					$link_target = ( $addon['admin_link_target'] === 'external' ) ? 'target="_blank"' : '';
					
					?>
			
					<div class="plugin-card plugin-card-<?php echo esc_attr($slug); ?> <?php echo esc_attr($addon['category']);?>">
					
						<div class="plugin-card-top">
						
							<div class="name column-name">
								<h3>
									<a href="<?php echo esc_url($addon['link']) . (($addon['admin_link_target'] === 'external')? '?utm_source=superpwa-plugin&utm_medium=addon-card': '') ; ?>" target="_blank">
										<?php
										// phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText
										echo esc_html__($addon['name'], 'super-progressive-web-apps'); ?>
										<img src="<?php echo esc_attr(SUPERPWA_PATH_SRC . 'admin/img/' . $addon['icon']); ?>" class="plugin-icon" alt="">
									</a>
									<span class="<?php echo esc_attr($addon['type'])?>"><?php if($addon['type']=='addon_pro'){ esc_html_e( 'Pro', 'super-progressive-web-apps');}else{ esc_html_e( 'Free', 'super-progressive-web-apps'); }?></span>
								</h3>
							</div>
							
							<div class="action-links">
								<ul class="plugin-action-buttons">

									<li>
										<?php if($slug=='push_notification_for_superpwa'){ 
						
											if(superpwa_push_notification_status()=='installed'){
												
												//plugin deactivated
												$class = 'pushnotification';
												$plugin = 'push-notification/push-notification.php';
												$action = 'activate';
												if ( strpos( $plugin, '/' ) ) {
													$plugin = str_replace( '\/', '%2F', $plugin );
												}
												$url = sprintf( admin_url( 'plugins.php?action=' . $action . '&plugin=%s&plugin_status=all&paged=1&s' ), $plugin );
												$activate_url = wp_nonce_url( $url, $action . '-plugin_' . $plugin );
												?>
										<a class="button button-primary"  href="<?php echo esc_url($activate_url)?>" aria-label="<?php echo esc_attr(superpwa_addons_button_text( $slug ) . ' ' . $addon['name'] . ' now'); ?>" data-name="<?php echo esc_attr($addon['name']); ?>">
											<?php echo esc_html__('Activate','super-progressive-web-apps'); ?>
										</a>
											 <?php } else if(superpwa_push_notification_status()=='not_installed'){?>
												
										<a class="button button-primary superpwa-install-require-plugin not-exist" data-secure="<?php echo esc_attr(wp_create_nonce('verify_request'))?>" id="pushnotification"  aria-label<?php echo esc_attr(superpwa_addons_button_text( $slug ) . ' ' . $addon['name'] . ' now'); ?>" data-name="<?php echo esc_attr($addon['name']); ?>">
											<?php echo esc_html__('Activate','super-progressive-web-apps'); ?>
										</a>
										<?php } } else{ ?>
											<a class="button activate-now button-<?php echo superpwa_addons_button_text( $slug ) == __( 'Deactivate', 'super-progressive-web-apps' ) ? 'secondary' : 'primary '.esc_attr (superpwa_addons_status($slug));  ?>" data-slug="<?php echo esc_attr($slug); ?>" href="<?php echo esc_url(superpwa_addons_button_link( $slug )); ?>" aria-label ="<?php echo esc_attr(superpwa_addons_button_text( $slug ) . ' ' . $addon['name'] . ' now'); ?>" data-name="<?php echo esc_attr($addon['name']); ?>">
											<?php echo esc_html(superpwa_addons_button_text( $slug ) ); ?>
											</a>
										<?php } ?>
									</li>
									<?php
										 if($slug=='push_notification_for_superpwa'){ 
											if(superpwa_push_notification_status()=='active'){
												echo'<li class="compatibility-compatible"><a class="button activate-now button-secondary" href="'.esc_url(admin_url('/admin.php?page=push-notification')).'"'.esc_attr($link_target).' style="padding-left: 7px;"><i class="dashicons-before dashicons-admin-generic" style="vertical-align: sub;font-size: 8px;"></i> '.esc_html__("Settings","super-progressive-web-apps").'</a></li>';
											}
										 }else{
											if(superpwa_addons_status( $slug ) == 'active'){
												echo'<li class="compatibility-compatible"><a class="button activate-now button-secondary" href="'.esc_url($addon['admin_link']).'"'.esc_attr($link_target).' style="padding-left: 7px;"><i class="dashicons-before dashicons-admin-generic" style="vertical-align: sub;font-size: 8px;"></i> '.esc_html__("Settings","super-progressive-web-apps").'</a></li>';
											}
										}
									?>
									<li>
										<?php
                                            $link = $addon['link'] . (($addon['admin_link_target'] === 'external')?'?utm_source=superpwa-plugin&utm_medium=addon-card': '');
                                        if (isset($addon['more_link'])) {
                                            $link = $addon['more_link'] . (($addon['admin_link_target'] === 'external')?'?utm_source=superpwa-plugin&utm_medium=addon-card': '');
                                        }
                                        ?>
										<?php if($slug!='push_notification_for_superpwa'){  ?>
										<a href="<?php echo esc_url($link); ?>" target="_blank" aria-label="<?php echo esc_html__('More information about', 'super-progressive-web-apps'); echo ' '. esc_html($addon['name']); ?>" data-title="<?php echo esc_attr($addon['name']); ?>"><?php esc_html_e('More Details', 'super-progressive-web-apps'); ?></a>
										<?php } ?>
									</li>
						
								</ul>	
							</div>
							
							<div class="desc column-description">
								<p><?php
									// phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText
									echo esc_html__($addon['description'], 'super-progressive-web-apps'); ?>
								</p>
							</div>
							
						</div>
					</div>
				<?php } ?>
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
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Reason: We are not processing form information.
	$addon_status = superpwa_addons_status( sanitize_title($_GET['addon']) );
	
	// Authentication	
	if ( 
		! current_user_can( superpwa_current_user_can() ) ||
		// ! current_user_can( 'manage_options' ) || 
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
	$active_addons[] = sanitize_text_field($_GET['addon']);
	
	// Write settings back to database
	update_option( 'superpwa_active_addons', $active_addons );
		
	// Redirect back to add-ons sub-menu
	wp_redirect( admin_url( 'admin.php?page=superpwa-addons&activated=1&addon=' . sanitize_title($_GET['addon'] )) );
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
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Reason: We are not processing form information.
	$addon_status = superpwa_addons_status( sanitize_title($_GET['addon']) );
	// Authentication
	if ( 
		! current_user_can( superpwa_current_user_can() ) ||
		// ! current_user_can( 'manage_options' ) || 
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
	unset( $active_addons[ sanitize_title($_GET['addon']) ] );
	$active_addons = array_flip( $active_addons );
		
	// Write settings back to database
	update_option( 'superpwa_active_addons', $active_addons );
		
	// Add-on deactivation action. Functions defined in the add-on file are still availalbe at this point. 
	do_action( 'superpwa_addon_deactivated_' . sanitize_title($_GET['addon']) );
	
	// Redirect back to add-ons sub-menu
	wp_redirect( admin_url( 'admin.php?page=superpwa-addons&deactivated=1&addon=' . sanitize_title($_GET['addon'] )) );
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
	
	if (isset( $_REQUEST['superpwa_security_nonce'] ) && current_user_can( superpwa_current_user_can()) && (wp_verify_nonce( $_REQUEST['superpwa_security_nonce'], 'superpwa_ajax_check_nonce' ) )){
	global $current_user;
	$api_url = 'http://magazine3.company/wp-json/api/central/email/subscribe';
    $api_params = array(
        'name' => sanitize_text_field($current_user->display_name),
        'email'=> sanitize_email($_POST['email']),
        'website'=> sanitize_url( get_site_url() ),
        'type'=> 'superpwa'
    );
    $response = wp_remote_post( $api_url, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
	if ( !is_wp_error( $response ) ) {
		$response = wp_remote_retrieve_body( $response );
		echo wp_json_encode(array('status'=>200, 'message'=>esc_html__('Submitted ','super-progressive-web-apps'), 'response'=> $response));
	}else{
		echo wp_json_encode(array('status'=>500, 'message'=>esc_html__('No response from API','super-progressive-web-apps')));	
	}
}
else{
	echo wp_json_encode(array('status'=>403, 'message'=>esc_html__('Unauthorized Request','super-progressive-web-apps')));
}
    die;
}
add_action( 'wp_ajax_superpwa_newsletter_submit', 'superpwa_newsletter_submit' );

function superpwa_newsletter_hide_form(){   
	  if (isset( $_REQUEST['superpwa_security_nonce'] ) && current_user_can( superpwa_current_user_can()) && (wp_verify_nonce( $_REQUEST['superpwa_security_nonce'], 'superpwa_ajax_check_nonce' ) )){
			$hide_newsletter  = get_option('superpwa_hide_newsletter');
			if($hide_newsletter == false){
				add_option( 'superpwa_hide_newsletter', 'no');
			}
			update_option( 'superpwa_hide_newsletter', 'yes' , false);
			echo wp_json_encode(array('status'=>200, 'message'=>esc_html__('Submitted ','super-progressive-web-apps')));
    }else{
		echo wp_json_encode(array('status'=>403, 'message'=>esc_html__('Unauthorized Request','super-progressive-web-apps')));
	}
    die;
}
add_action( 'wp_ajax_superpwa_newsletter_hide_form', 'superpwa_newsletter_hide_form' );

function superpwa_push_notification_status(){
	$status='';
	if(file_exists( SUPERPWA_PATH_ABS."/../push-notification/push-notification.php"))
	{
		if(!function_exists('is_plugin_active')){
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		if(is_plugin_active('push-notification/push-notification.php'))
		{
			$status='active';
		}
		else{
			$status='installed';
		}

	}
	else{
		$status = 'not_installed';
	}
	return $status;
}