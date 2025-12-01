<?php
// Exit if accessed directly
if( !defined( 'ABSPATH' ) )
    exit;

/**
 * Helper method to check if user is in the plugins page.
 *
 * @author 
 * @since  1.4.0
 *
 * @return bool
 */
function superpwa_is_plugins_page() {

    if(function_exists('get_current_screen')){
        $screen = get_current_screen();
            if(is_object($screen)){
                if($screen->id == 'plugins' || $screen->id == 'plugins-network'){
                    return true;
                }
            }
    }
    return false;
}

/**
 * display deactivation logic on plugins page
 * 
 * @since 1.4.0
 */


function superpwa_add_deactivation_feedback_modal() {
    
  
    if( !is_admin() && !superpwa_is_plugins_page()) {
        return;
    }

    $current_user = wp_get_current_user();
    if( !($current_user instanceof WP_User) ) {
        $email = '';
    } else {
        $email = trim( $current_user->user_email );
    }

    require_once SUPERPWA_PATH_ABS."admin/deactivate-feedback.php";
    
}

/**
 * send feedback via email
 * 
 * @since 1.4.0
 */
function superpwa_send_feedback() {
    // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Reason: its in form variable.
    if( isset( $_POST['data'] ) ) {
        // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Reason: its in form variable.
        parse_str( $_POST['data'], $form );
    }

    if ( ! wp_verify_nonce( $form['superpwa_deactivate_nonce'], 'superpwa-deactivate-nonce' ) ) {
        die( esc_html__( 'Security check', 'superpwa-for-wp' ) ); 
    }
    if ( ! current_user_can( superpwa_current_user_can() ) ) {
        die( esc_html__( 'Unauthorised Access', 'superpwa-for-wp' ) ); 
    }
    $text = '';
    if( isset( $form['superpwa_disable_text'] ) ) {
        $text = implode( "\n\r", $form['superpwa_disable_text'] );
    }

    $headers = array();

    $from = isset( $form['superpwa_disable_from'] ) ? $form['superpwa_disable_from'] : '';
    if( $from ) {
        $headers[] = "From: $from";
        $headers[] = "Reply-To: $from";
    }

    $subject = isset( $form['superpwa_disable_reason'] ) ? $form['superpwa_disable_reason'] : '(no reason given)';

    $subject = $subject.' - Super Progressive Web Apps';

    if($subject == 'technical - Super Progressive Web Apps'){

          $text = trim($text);

          if(!empty($text)){

            $text = 'technical issue description: '.$text;

          }else{

            $text = 'no description: '.$text;
          }
      
    }

    $success = wp_mail( 'team@magazine3.in', $subject, $text, $headers );

    die();
}
add_action( 'wp_ajax_superpwa_send_feedback', 'superpwa_send_feedback' );

add_action( 'admin_enqueue_scripts', 'superpwa_enqueue_makebetter_email_js' );

function superpwa_enqueue_makebetter_email_js(){
 
    if( !is_admin() && !superpwa_is_plugins_page()) {
        return;
    }

    wp_enqueue_script( 'superpwa-make-better-js', SUPERPWA_PATH_SRC . 'admin/make-better-admin.js', array( 'jquery' ), SUPERPWA_VERSION,true);

    wp_enqueue_style( 'superpwa-make-better-css', SUPERPWA_PATH_SRC . 'admin/make-better-admin.css', false , SUPERPWA_VERSION);
}

add_filter('admin_footer', 'superpwa_add_deactivation_feedback_modal');

/* * BFCM Banner Integration
 * Loads assets from assets/css and assets/js
 */
add_action('admin_enqueue_scripts', 'superpwa_enqueue_bfcm_assets');

function superpwa_enqueue_bfcm_assets($hook) { 
 
    
    if ( $hook !== 'toplevel_page_superpwa'
    && $hook !== 'superpwa_page_superpwa-addons' ) {
        return;
    }

    // 2. define settings
    $expiry_date_str = '2025-12-25 23:59:59'; 
    $offer_link      = 'https://superpwa.com/bfcm-25/';

    // 3. Expiry Check (Server Side)
    if ( current_time('timestamp') > strtotime($expiry_date_str) ) {
        return; 
    }

    // 4. Register & Enqueue CSS    
    wp_enqueue_style(
        'superpwa-bfcm-style', 
        SUPERPWA_PATH_SRC. 'admin/css/bfcm-style.css', 
        array(), 
        SUPERPWA_VERSION
    );

    // 5. Register & Enqueue JS
    wp_enqueue_script(
        'superpwa-bfcm-script', 
        SUPERPWA_PATH_SRC. 'admin/js/bfcm-script.js', 
        array('jquery'), // jQuery dependency
        SUPERPWA_VERSION, 
        true 
    );

    // 6. Data Pass (PHP to JS)
    wp_localize_script('superpwa-bfcm-script', 'bfcmData', array(
        'targetDate' => $expiry_date_str,
        'offerLink'  => $offer_link
    ));
}