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
    global $pagenow;

    return ( 'plugins.php' === $pagenow );
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

    if( isset( $_POST['data'] ) ) {
        parse_str( $_POST['data'], $form );
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

    wp_enqueue_script( 'superpwa-make-better-js', SUPERPWA_PATH_SRC . 'admin/make-better-admin.js', array( 'jquery' ), SUPERPWA_VERSION);

    wp_enqueue_style( 'superpwa-make-better-css', SUPERPWA_PATH_SRC . 'admin/make-better-admin.css', false , SUPERPWA_VERSION);
}

if( is_admin() && superpwa_is_plugins_page()) {
    add_filter('admin_footer', 'superpwa_add_deactivation_feedback_modal');
}


