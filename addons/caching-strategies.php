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
				'precaching_automatic'		=> '0',
				'precaching_manual'		=> '0',
				'precaching_automatic_post'		=> '0',
				'precaching_automatic_page'		=> '0',
				'precaching_post_count'		=> '5',
				'precaching_urls'		=> '',
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
 * Adding Pre Cache Urls in Service Worker Js
 * @since	2.1.17
 */
function superpwa_pre_caching_urls_sw( $files_to_cache ) {
	
	$settings = superpwa_caching_strategies_get_settings();
       $pre_cache_urls = $manual_urls = '';
	  if(isset($settings['precaching_manual']) && $settings['precaching_manual'] == '1' && !empty($settings['precaching_urls'])){
	  	  $manual_urls = str_replace(',', '\',\'', $settings['precaching_urls']);
	  	  $files_to_cache = '\''.$manual_urls.'\','.$files_to_cache;
	  }


	     $store_post_id = array();
         $store_post_id = json_decode(get_transient('superpwa_pre_cache_post_ids'));
                
            if(!empty($store_post_id) && isset($settings['precaching_automatic']) && $settings['precaching_automatic']==1){
                    $files_to_cache .= ',';
                    foreach ($store_post_id as $post_id){
                        
                       $files_to_cache .= "'".trim(get_permalink($post_id))."',\n"; 
                                                                                                                                            
                    }
            }
	
	return $files_to_cache;
}
add_filter( 'superpwa_sw_files_to_cache', 'superpwa_pre_caching_urls_sw' );

/**
 * Getting Post Ids for Automatic Pre-Caching
 * @since	2.1.17
 */

add_action( 'publish_post', 'superpwa_store_latest_post_ids', 10, 2 );
add_action( 'publish_page', 'superpwa_store_latest_post_ids', 10, 2 );

 function superpwa_store_latest_post_ids(){
       
       if ( ! current_user_can( 'edit_posts' ) ) {
             return;
       }
       
       $post_ids = array();           
       $settings = superpwa_caching_strategies_get_settings();
       
       if(isset($settings['precaching_automatic']) && $settings['precaching_automatic']==1){
       
            $post_count = 10;
            
            if(isset($settings['precaching_post_count']) && $settings['precaching_post_count'] !=''){
               $post_count =$settings['precaching_post_count']; 
            }                
            $post_args = array( 'numberposts' => $post_count, 'post_status'=> 'publish', 'post_type'=> 'post'  );                      
            $page_args = array( 'number'       => $post_count, 'post_status'=> 'publish', 'post_type'=> 'page' );
                                    
            if(isset($settings['precaching_automatic_post']) && $settings['precaching_automatic_post']==1){
                $postslist = get_posts( $post_args );
                if($postslist){
                    foreach ($postslist as $post){
                     $post_ids[] = $post->ID;
                   }
                }
            }else{
                 delete_transient('superpwa_pre_cache_post_ids');
            }
            
            if(isset($settings['precaching_automatic_page']) && $settings['precaching_automatic_page']==1){
                $pageslist = get_pages( $page_args );
                if($pageslist){
                    foreach ($pageslist as $post){
                     $post_ids[] = $post->ID;
                   }               
                }         
            }else{
            	delete_transient('superpwa_pre_cache_post_ids');
            }   
            $previousIds = get_transient('superpwa_pre_cache_post_ids');
            if($post_ids){
                if($previousIds){
                    $previousIds = json_decode($previousIds);
                    if(array_diff($post_ids, $previousIds)){
                        set_transient('superpwa_pre_cache_post_ids', json_encode($post_ids));
                    }
                }else{
                    set_transient('superpwa_pre_cache_post_ids', json_encode($post_ids));
                }
            }

                          
       }                                  
    }

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
		// Pre Caching Feature
		add_settings_field(
			'superpwa_caching_strategies_pre_caching',						// ID
			__('Pre Caching', 'super-progressive-web-apps'),	// Title
			'superpwa_caching_strategies_pre_caching_cb',					// CB
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
 * Pre Caching Callback function
 *
 * @since 2.1.17
 */
function superpwa_caching_strategies_pre_caching_cb() {
	$cachingSettings = superpwa_caching_strategies_get_settings();

	$settings = superpwa_caching_strategies_get_settings(); 
        
        $arrayOPT = array(                    
                        'automatic'=>'Automatic',
                        'manual'=>'Manual',            
                     );
	?>

	<style type="text/css">.pre-manual-suboption span {display: table-cell;margin-bottom: 9px;padding: 15px 10px;line-height: 1.3;vertical-align: middle;}.show{display: block;}.hide{display: none}
	</style>
			
		<div class="pre-cache-main">
            <div class="pre-cache-automatic">
               <div class="pre-automatic-checkbox" style="margin-bottom: 10px;"> 
                  <input type="checkbox" name="superpwa_caching_strategies_settings[precaching_automatic]" id="precaching_automatic" class="" <?php echo (isset( $settings['precaching_automatic'] ) &&  $settings['precaching_automatic'] == 1 ? 'checked="checked"' : ''); ?> data-uncheck-val="0" value="1">

                  <strong><?php echo esc_html__('Automatic', 'super-progressive-web-apps'); ?></strong>   
                  <!-- <span class="pwafw-tooltip"><i class="dashicons dashicons-editor-help"></i> 
                    <span class="pwafw-help-subtitle"><a href="https://pwa-for-wp.com/docs/article/setting-up-precaching-in-pwa/"><?php //echo esc_html__('For details click here', 'pwa-for-wp'); ?></a></span>
                </span> -->
                 </div>
                 <div id="pre-automatic-suboption" class="pre-automatic-suboption <?php echo (isset( $settings['precaching_automatic'] ) &&  $settings['precaching_automatic'] == 1 ? ' show' : ' hide'); ?>" style="margin-bottom: 30px;margin-left: 40px;">  
                <table class="pre-automatic-cache-table" style="margin-bottom: 12px;">
                     <tr>
                         <td>
                          <?php echo esc_html__('Post', 'super-progressive-web-apps') ?>                             
                         </td>
                         <td>                         
                         <input type="checkbox" name="superpwa_caching_strategies_settings[precaching_automatic_post]" id="superpwa_settings_precaching_automatic_post" class="" <?php echo (isset( $settings['precaching_automatic_post'] ) &&  $settings['precaching_automatic_post'] == 1 ? 'checked="checked"' : ''); ?> data-uncheck-val="0" value="1">     
                         </td>
                         <td>
                         <?php echo esc_html__('Page', 'super-progressive-web-apps') ?>   
                         </td>
                         <td>
                         <input type="checkbox" name="superpwa_caching_strategies_settings[precaching_automatic_page]" id="superpwa_settings_precaching_automatic_page" class="" <?php echo (isset( $settings['precaching_automatic_page'] ) &&  $settings['precaching_automatic_page'] == 1 ? 'checked="checked"' : ''); ?> data-uncheck-val="0" value="1">         
                         </td>
                         <td>                          
                         <?php echo esc_html__('Custom Post', 'super-progressive-web-apps') ?>   
                         </td>
                         <td>
                         <input type="checkbox" name="superpwa_caching_strategies_settings[precaching_automatic_custom_post]" id="superpwa_settings_precaching_automatic_custom_post" class="" <?php echo (isset( $settings['precaching_automatic_custom_post'] ) &&  $settings['precaching_automatic_custom_post'] == 1 ? 'checked="checked"' : ''); ?> data-uncheck-val="0" value="1">         
                         </td>                     
                     </tr>
                     
                    </table>

                    <span style="margin-left: 12px;"><strong><?php echo esc_html__('Enter Post Count', 'super-progressive-web-apps'); ?></strong></span>
                   <span style="margin-left: 14px;">
                       <input id="superpwa_settings_precaching_post_count" name="superpwa_caching_strategies_settings[precaching_post_count]" value="<?php if(isset($settings['precaching_post_count'])){ echo esc_attr($settings['precaching_post_count']);} ?>" type="number" min="0">   
                   </span>
                   </div>
            </div> <!-- automatic wrap ends here -->



            <div class="pre-cache-manual" style="margin-top: 20px;">
                <div class="pre-manual-checkbox" style="margin-bottom: 10px;">  
	              <input type="checkbox" name="superpwa_caching_strategies_settings[precaching_manual]" id="precaching_manual" class="" <?php echo (isset( $settings['precaching_manual'] ) &&  $settings['precaching_manual'] == 1 ? 'checked="checked"' : ''); ?> data-uncheck-val="0" value="1">

	              <strong><?php echo esc_html__('Manual', 'super-progressive-web-apps'); ?></strong>   
	              <!-- <span class="pwafw-tooltip"><i class="dashicons dashicons-editor-help"></i> 
	                <span class="pwafw-help-subtitle"><a href="https://pwa-for-wp.com/docs/article/setting-up-precaching-in-pwa/"><?php //echo esc_html__('For details click here', 'pwa-for-wp'); ?></a></span>
	            </span> -->

               </div> 
	             <div id="pre-manual-suboption" class="pre-manual-suboption <?php echo (isset( $settings['precaching_manual'] ) &&  $settings['precaching_manual'] == 1 ? ' show' : ' hide'); ?>" style="margin-left: 45px;">    
                    <span class="pre-manual-label"> <strong> <?php echo esc_html__('Enter Urls To Be Cached', 'super-progressive-web-apps'); ?> </strong></span>
                   <span class="pre-manual-textarea">
                       <label><textarea placeholder="https://example.com/2019/06/06/hello-world/, https://example.com/2019/06/06/hello-world-2/ "  rows="4" cols="50" id="superpwa_settings_precaching_urls" name="superpwa_caching_strategies_settings[precaching_urls]"><?php if(isset($settings['precaching_urls'])){ echo esc_attr($settings['precaching_urls']);} ?></textarea></label>
                       <p><?php echo esc_html__('Note: Seperate the URLs using Comma(,)', 'super-progressive-web-apps'); ?></p>
                       <p><?php echo esc_html__('Place the list of URLs which you want to pre cache by service worker', 'super-progressive-web-apps'); ?></p>
                   </span>
                </div>

            </div> <!-- Manual wrap ends here -->
		</div> <!-- Main wrap ends here -->

		
	
	<?php

}

/**
 * Caching Strategies Admin Scripts
 *
 * @since 2.1.17
 */ 

function superpwa_precache_load_admin_scripts($hooks){
    if( !in_array($hooks, array('superpwa_page_superpwa-caching-strategies', 'super-pwa_page_superpwa-caching-strategies')) ) {
        return false;
    }

    wp_register_script('superpwa-admin-precache-script',SUPERPWA_PATH_SRC .'/admin/js/pre-cache.js', array('superpwa-main-js'), SUPERPWA_VERSION, true);
    
    wp_enqueue_script('superpwa-admin-precache-script'); 

}
add_action( 'admin_enqueue_scripts', 'superpwa_precache_load_admin_scripts' );

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