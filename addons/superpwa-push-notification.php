<?php
/**
 * PUSH NOTIFICATION
 *
 * @since 1.33
 *
 * @Class SPWAP_PUSH_NOTIFICATION()			Add all features of PUSH NOTIFICATION
 *      @function
 */
// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

/**
  * Features of Data Analytics
 */
class SPWAP_PUSH_NOTIFICATION
{
	 /**
     * Get the unique instance of the class
     *
     * @var SPWAP_PUSH_NOTIFICATION
     */
    private static $_instance;
    /**
     * Get the unique instance of the class
     *
     * @var settings
     */
    /**
     * Constructor
     */
      public function __construct()
    {
        if (is_admin()) {
            add_action("wp_ajax_superpwa_enable_modules_upgread", array($this, 'enable_modules') );           
        }
    }
    /**
     * Gets an instance of our SPWAP_WPML class.
     *
     * @return SPWAP_PUSH_NOTIFICATION Object
     */
    public static function get_instance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    
public function enable_modules(){
    if(!wp_verify_nonce( $_REQUEST['verify_nonce'], 'verify_request' ) ) {
        echo wp_json_encode(array("status"=>300,"message"=>esc_html__('Request not valid','super-progressive-web-apps')));
        exit();
    }
    // Exit if the user does not have proper permissions
    if(! current_user_can( 'install_plugins' ) ) {
        echo wp_json_encode(array("status"=>300,"message"=>esc_html__('User Request not valid','super-progressive-web-apps')));
        exit();
    }

    $plugins = array();
    $redirectSettingsUrl = '';
    $currentActivateModule = sanitize_text_field( wp_unslash($_REQUEST['activate']));
    switch($currentActivateModule){
        case 'pushnotification': 
            $nonceUrl = add_query_arg(
                                    array(
                                        'action'        => 'activate',
                                        'plugin'        => 'push-notification',
                                        'plugin_status' => 'all',
                                        'paged'         => '1',
                                        '_wpnonce'      => wp_create_nonce( 'activate-plugin_push-notification' ),
                                    ),
                        esc_url(network_admin_url( 'plugins.php' ))
                        );
            $plugins[] = array(
                            'name' => 'push-notification',
                            'path_' => 'https://downloads.wordpress.org/plugin/push-notification.zip',
                            'path' => $nonceUrl,
                            'install' => 'push-notification/push-notification.php',
                        );
            $redirectSettingsUrl = admin_url('admin.php?page=push-notification&reference=superpwa');
        break;
    }

    if(count($plugins)>0){
       echo wp_json_encode( array( "status"=>200, "message"=>esc_html__('Module successfully Added','super-progressive-web-apps'),'redirect_url'=>esc_url($redirectSettingsUrl) , "slug"=>esc_html($plugins[0]['name']), 'path'=> esc_html($plugins[0]['path'] )) );
    }else{
        echo wp_json_encode(array("status"=>300, "message"=>esc_html__('Modules not Found','super-progressive-web-apps')));
    }
    wp_die();

}


}

function superpwapro_push_notification(){
	return SPWAP_PUSH_NOTIFICATION::get_instance();
}
superpwapro_push_notification();