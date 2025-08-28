<?php
if (!function_exists('is_plugin_active')) {
    require_once(ABSPATH . 'wp-admin/includes/plugin.php');
}

if (is_plugin_active('pushengage/main.php')) {
    function superpwa_push_engage_sw($sw_js) {
        $sw_js = "importScripts('https://clientcdn.pushengage.com/sdks/service-worker.js');\n" . $sw_js;
        return $sw_js;
    }
    add_filter('superpwa_sw_template', 'superpwa_push_engage_sw');

    function superpwa_push_engage_activate_deactivate() {
        superpwa_generate_sw();
    }

    register_activation_hook('pushengage/main.php', 'superpwa_push_engage_activate_deactivate');
    register_deactivation_hook('pushengage/main.php', 'superpwa_push_engage_activate_deactivate');

}