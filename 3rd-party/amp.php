<?php
/**
 * AMP Support Implementation
 *
 * @since 2.2
 */

// Exit if accessed directly
if ( ! defined('ABSPATH') ) exit;

class SUPERPWA_AMP_SW{

        public $is_amp = false; 
     	/**
         * Initialize whole front end system
         */
        public function __construct() {
            /* Check & initialize AMP is Activated or not*/
            $this->superpwa_is_amp_activated();

            // Change service worker filename to match OneSignal's service worker
			add_filter( 'superpwa_sw_filename', array($this, 'superpwa_amp_sw_filename') );

            add_action('pre_amp_render_post', array($this, 'superpwa_amp_entry_point'));
            //Automattic AMP will be done here
            add_action('wp', array($this, 'superpwa_automattic_amp_entry_point'));

        }

        public function superpwa_amp_sw_filename( $sw_filename ) {
			return 'superpwa-amp-sw' . superpwa_multisite_filename_postfix() . '.js';
        }

        public function superpwa_amp_entry_point(){  
            
            add_action('amp_post_template_footer',array($this, 'superpwa_amp_service_worker'), 15);
            add_filter('amp_post_template_data',array($this, 'superpwa_service_worker_script'),35);
            add_action('amp_post_template_head',array($this, 'superpwa_amp_manifest_json_relink'),1);
            
        }

        public function superpwa_automattic_amp_entry_point(){  
            if ( superpwa_is_automattic_amp() ) {

                add_action('wp_footer',array($this, 'superpwa_amp_service_worker'));
                add_filter('amp_post_template_data',array($this, 'superpwa_service_worker_script'),35);
                //add_action('wp_head',array($this, 'pwaforwp_paginated_post_add_homescreen_amp'),1); 
            }
            
        }

	    public function superpwa_amp_service_worker(){ 

	                $url = superpwa_site_url();
	                $home_url = superpwa_home_url();
	                $swhtml            = $url.'superpwa-amp-sw'.superpwa_multisite_filename_postfix().'.html';
	                $swjs_path_amp     = $url.'superpwa-amp-sw'.superpwa_multisite_filename_postfix().'.js';
	                ?>
	            <amp-install-serviceworker data-scope="<?php echo trailingslashit($home_url); ?>" 
	                        src="<?php echo esc_url_raw($swjs_path_amp); ?>" 
	                        data-iframe-src="<?php echo esc_url_raw($swhtml); ?>"  
	                        layout="nodisplay">
				</amp-install-serviceworker>

			<?php    
		}

		public function superpwa_amp_manifest_json_relink(){  


				$tags  = '<!-- Manifest added by SuperPWA - Progressive Web Apps Plugin For WordPress -->' . PHP_EOL; 
				$tags .= '<link rel="manifest" href="'. parse_url( superpwa_manifest( 'src' ), PHP_URL_PATH ) . '">' . PHP_EOL;

				// theme-color meta tag 
				if ( apply_filters( 'superpwa_add_theme_color', true ) ) {

				// Get Settings
					$settings = superpwa_get_settings();
					$tags .= '<meta name="theme-color" content="'. $settings['theme_color'] .'">' . PHP_EOL;
				}

				$tags  = apply_filters( 'superpwa_wp_head_tags', $tags );

				$tags .= '<!-- / SuperPWA.com -->' . PHP_EOL; 

			echo $tags;

	    }


		public function superpwa_service_worker_script( $data ){
	            
			if ( empty( $data['amp_component_scripts']['amp-install-serviceworker'] ) ) {
				$data['amp_component_scripts']['amp-install-serviceworker'] = 'https://cdn.ampproject.org/v0/amp-install-serviceworker-0.1.js';
			}
			return $data;
	                
		}
        
        public function superpwa_is_amp_activated() {    
		
        if ( function_exists( 'ampforwp_is_amp_endpoint' ) || function_exists( 'is_amp_endpoint' ) ) {
                $this->is_amp = true;
            }
		  
        }     

}

// Initiate SUPERPWA_AMP_SW Class
function superpwa_amp_sw_init() {

     new SUPERPWA_AMP_SW();
}
add_action( 'init', 'superpwa_amp_sw_init' );