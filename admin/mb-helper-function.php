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
        die( esc_html__( 'Security check', 'super-progressive-web-apps' ) ); 
    }
    if ( ! current_user_can( superpwa_current_user_can() ) ) {
        die( esc_html__( 'Unauthorised Access', 'super-progressive-web-apps' ) ); 
    }
    $text = '';
    if( isset( $form['superpwa_disable_text'] ) && is_array( $form['superpwa_disable_text'] ) ) {
        $text = implode( "\n\r", $form['superpwa_disable_text'] );
    }

    $reason = isset( $form['superpwa_disable_reason'] ) ? $form['superpwa_disable_reason'] : '';
    $allowed_reasons = array( 'missing', 'technical', 'other' );
    $text = trim( $text );
    $word_count = ( '' === $text ) ? 0 : count( preg_split( '/\s+/', $text ) );

    // Only email when reason is missing, technical, or other, and the comment has at least 3 words.
    if ( ! in_array( $reason, $allowed_reasons, true ) || $word_count < 3 ) {
        die();
    }

    $headers = array();

    $from = ! empty( $form['superpwa_disable_from'] ) ? sanitize_email( $form['superpwa_disable_from'] ) : '';
    if( $from ) {
        $headers[] = "From: $from";
        $headers[] = "Reply-To: $from";
    }

    $subject = $reason.' - Super Progressive Web Apps';

    if( $reason === 'technical' ){
        $text = 'technical issue description: '.$text;
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

    wp_enqueue_script( 'superpwa-make-better-js', SUPERPWA_PATH_SRC . 'admin/make-better-admin.js', array( 'jquery' ), SUPERPWA_VERSION.'2',true);

    wp_enqueue_style( 'superpwa-make-better-css', SUPERPWA_PATH_SRC . 'admin/make-better-admin.css', array( 'list-tables' ), SUPERPWA_VERSION);
}

add_filter('admin_footer', 'superpwa_add_deactivation_feedback_modal');