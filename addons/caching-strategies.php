<?php
/**
 * Caching Strategies Icons
 *
 * @since 2.1.7
 * 
 * @function	superpwa_caching_strategies_get_settings()	Settings of cache strategies
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Get Caching Strategies settings
 *
 * @since 2.1.7
 */
function superpwa_caching_strategies_get_settings() {
	
	$defaults = array(
				'caching_type'		=> 'network_first',
			);
	
	return get_option( 'superpwa_caching_strategies_settings', $defaults );
}

function superpwa_caching_strategies_sw_template($file_string){
	$settings = superpwa_caching_strategies_get_settings();
	$caching_type = isset($settings['caching_type'])? $settings['caching_type'] : 'network_first';
	if($caching_type=='network_first'){ return $file_string; }
	$script = '';
	switch($caching_type){
		case 'network_first':
			$script = ''; //already working with network first, so no need to edit
			break;
		case 'cache_first':
			$script	= 	'e.respondWith(
			caches.open(cacheName)
				.then(function(cache) {
					cache.match(e.request)
						.then( function(cacheResponse) {
							if(cacheResponse)
								return cacheResponse
							else
								return fetch(e.request)
									.then(function(networkResponse) {
										cache.put(e.request, networkResponse.clone())
										return networkResponse
									})
						})
				}).catch(function(){
					return fetch(e.request).then(function(response) {
						return caches.open(cacheName).then(function(cache) {
							cache.put(e.request, response.clone());
							return response;
						});  
					})
				})
		);';
			break;
		case 'steal_while_revalidate':
			$script = 'e.respondWith(
			caches.open(cacheName)
				.then(function(cache) {
					cache.match(e.request)
						.then( function(cacheResponse) {
							fetch(e.request)
								.then(function(networkResponse) {
									cache.put(e.request, networkResponse)
								})
							return cacheResponse || networkResponse
						})
				})
		);';
			break;
		case 'cache_only':
			$script = 	'e.respondWith(
			caches.open(cacheName).then(function(cache) {
				cache.match(e.request).then(function(cacheResponse) {
					return cacheResponse;
				})
			})
		);';
			break;
		case 'network_only':
			$script = 	'e.respondWith(
							fetch(event.request).then(function(networkResponse) {
								return networkResponse
							})
						);';
			break;
	}
	if(!empty($script)){
		$replaceContent = 'e.respondWith(
			fetch(e.request).then(function(response) {
				return caches.open(cacheName).then(function(cache) {
					cache.put(e.request, response.clone());
					return response;
				});  
			})
		);';
		$file_string = str_replace($replaceContent, $script, $file_string);
	}
    return $file_string;
}
add_filter( 'superpwa_sw_template', 'superpwa_caching_strategies_sw_template', 10, 1 );

/**
 * Todo list after saving caching_strategies settings
 *
 * Regenerate Service Worker when settings are saved. 
 * Also used when add-on is activated and deactivated.
 *
 * @since	1.7
 */
function superpwa_caching_strategies_save_settings_todo() {
	
	// Regenerate manifest
	superpwa_generate_sw();
}
add_action( 'add_option_superpwa_caching_strategies_settings', 'superpwa_caching_strategies_save_settings_todo' );
add_action( 'update_option_superpwa_caching_strategies_settings', 'superpwa_caching_strategies_save_settings_todo' );
add_action( 'superpwa_addon_activated_caching_strategies', 'superpwa_caching_strategies_save_settings_todo' );

/**
 * Deactivation Todo
 * 
 * Unhook the filter and regenerate manifest
 * 
 * @since 1.7
 */
function superpwa_caching_strategies_deactivate_todo() {
	
	// Unhook the UTM tracking params filter
	remove_filter( 'superpwa_manifest_start_url', 'superpwa_utm_tracking_for_start_url' );
	
	// Regenerate Service Worker
	superpwa_generate_sw();
}
add_action( 'superpwa_addon_deactivated_caching_strategies', 'superpwa_caching_strategies_deactivate_todo' );

/**
 * Register Caching Strategies settings
 *
 * @since 	2.1.7
 */
function superpwa_caching_strategies_settings(){
    // Register Setting
	register_setting( 
		'superpwa_caching_strategies_settings_group',		 // Group name
		'superpwa_caching_strategies_settings', 			// Setting name = html form <input> name on settings form
		'superpwa_caching_strategies_validater_sanitizer'	// Input validator and sanitizer
	);

    // UTM Tracking
    add_settings_section(
        'superpwa_caching_strategies_section',				// ID
        __return_false(),								// Title
        'superpwa_caching_strategies_section_cb',				// Callback Function
        'superpwa_caching_strategies_section'					// Page slug
    );
        // Caching Strategies type
		add_settings_field(
			'superpwa_caching_strategies_caching_type',						// ID
			__('Caching Strategies Type', 'super-progressive-web-apps'),	// Title
			'superpwa_caching_strategies_caching_type_cb',					// CB
			'superpwa_caching_strategies_section',						// Page slug
			'superpwa_caching_strategies_section'							// Settings Section ID
		);
}
add_action( 'admin_init', 'superpwa_caching_strategies_settings' );


/**
 * Validate and sanitize user input
 *
 * @since 2.1.7
 */
function superpwa_caching_strategies_validater_sanitizer( $settings ) {
    
    // Sanitize and validate campaign source. Campaign source cannot be empty.
	$settings['caching_type'] = sanitize_text_field( $settings['caching_type'] ) == '' ? 'network_first' : sanitize_text_field( $settings['caching_type'] );

    return  $settings;
}

/**
 * Callback function for Caching Strategies section
 *
 * @since 1.7
 */
function superpwa_caching_strategies_section_cb() {

	printf( '<p>' . __( 'Caching strategies will help your users to get connected and display content, perform function, in bad network conditions and even when the user is completely offline.', 'super-progressive-web-apps' ) . '</p>');
}

/**
 * Current Start URL
 *
 * @since 1.7
 */
function superpwa_caching_strategies_caching_type_cb() {
	$cachingSettings = superpwa_caching_strategies_get_settings();

	echo '<p><label class="label"><input type="radio" name="superpwa_caching_strategies_settings[caching_type]" value="network_first" '.(isset($cachingSettings['caching_type']) && $cachingSettings['caching_type']=='network_first'? 'checked': '').'> Network first, then Cache </label></p>
    <p><label><input type="radio" name="superpwa_caching_strategies_settings[caching_type]" value="cache_first" '.(isset($cachingSettings['caching_type']) && $cachingSettings['caching_type']=='cache_first'? 'checked': '').'> Cache first, then Network </label>
    </p> 
    <p><label><input type="radio" name="superpwa_caching_strategies_settings[caching_type]" value="steal_while_revalidate" '.(isset($cachingSettings['caching_type']) && $cachingSettings['caching_type']=='steal_while_revalidate'? 'checked': '').'> Stale While Revalidate </label></p>
    <p><label><input type="radio" name="superpwa_caching_strategies_settings[caching_type]" value="cache_only" '.(isset($cachingSettings['caching_type']) && $cachingSettings['caching_type']=='cache_only'? 'checked': '').'> Cache only </label></p>
    <p><label><input type="radio" name="superpwa_caching_strategies_settings[caching_type]" value="network_only" '.(isset($cachingSettings['caching_type']) && $cachingSettings['caching_type']=='network_only'? 'checked': '').'> Network only </label></p>
    ';
}

/**
 * Caching Strategies UI renderer
 *
 * @since 2.1.7
 */ 
function superpwa_caching_strategies_interface_render() {
	
	// Authentication
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	
	// Handing save settings
	if ( isset( $_GET['settings-updated'] ) ) {
		
		// Add settings saved message with the class of "updated"
		add_settings_error( 'superpwa_settings_group', 'superpwa_caching_strategies_settings_saved_message', __( 'Settings saved.', 'super-progressive-web-apps' ), 'updated' );
		
		// Show Settings Saved Message
		settings_errors( 'superpwa_settings_group' );
	}
	// Get add-on info
	$addon_utm_tracking = superpwa_get_addons( 'caching_strategies' );

	superpwa_setting_tabs_styles();
	?>
	
	<div class="wrap">	
		<h1><?php _e( 'Caching Strategies', 'super-progressive-web-apps' ); ?> <small>(<a href="<?php echo esc_url($addon_utm_tracking['link']) . '?utm_source=superpwa-plugin&utm_medium=utm-tracking-settings'?>"><?php echo esc_html__( 'Docs', 'super-progressive-web-apps' ); ?></a>)</small></h1>
		
		<?php superpwa_setting_tabs_html(); ?>

		<form action="options.php" method="post" class="form-table" enctype="multipart/form-data">		
			<?php
			// Output nonce, action, and option_page fields for a settings page.
			settings_fields( 'superpwa_caching_strategies_settings_group' );
			
			// Status
			do_settings_sections( 'superpwa_caching_strategies_section' );	// Page slug
			
			// Output save settings button
			submit_button( __('Save Settings', 'super-progressive-web-apps') );
			?>
		</form>
	</div>
	<?php superpwa_newsletter_form(); ?>
	<?php
}