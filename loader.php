<?php
/**
 * Loads the plugin files
 *
 * @since 1.0
 */

// Exit if accessed directly
if ( ! defined('ABSPATH') ) exit;

// Load admin
require_once( SUPERPWA_PATH_ABS . 'admin/basic-setup.php' );
require_once( SUPERPWA_PATH_ABS . 'admin/admin-ui-setup.php' );
require_once( SUPERPWA_PATH_ABS . 'admin/admin-ui-render-settings.php' );
require_once( SUPERPWA_PATH_ABS . 'admin/admin-ui-render-addons.php' );
require_once( SUPERPWA_PATH_ABS . 'admin/admin-ui-render-upgrade.php' );
require_once( SUPERPWA_PATH_ABS . 'admin/mb-helper-function.php' );

// 3rd party compatibility
require_once( SUPERPWA_PATH_ABS . '3rd-party/onesignal.php' );
require_once( SUPERPWA_PATH_ABS . '3rd-party/yandex.php' );
require_once( SUPERPWA_PATH_ABS . '3rd-party/amp.php' );
require_once( SUPERPWA_PATH_ABS . '3rd-party/wonderpush.php' );

// Load functions
require_once( SUPERPWA_PATH_ABS . 'functions/common.php' );
require_once( SUPERPWA_PATH_ABS . 'functions/filesystem.php' );
require_once( SUPERPWA_PATH_ABS . 'functions/multisite.php' );

// Public folder
require_once( SUPERPWA_PATH_ABS . 'public/manifest.php' );
require_once( SUPERPWA_PATH_ABS . 'public/sw.php' );
require_once( SUPERPWA_PATH_ABS . 'public/amphtml.php' );

// Load bundled add-ons
require_once( SUPERPWA_PATH_ABS . 'addons/utm-tracking.php' );
require_once( SUPERPWA_PATH_ABS . 'addons/apple-touch-icons.php' );
require_once( SUPERPWA_PATH_ABS . 'addons/caching-strategies.php' );
require_once( SUPERPWA_PATH_ABS . 'addons/pull-to-refresh.php' );

// superpwa-push-notification
require_once( SUPERPWA_PATH_ABS . 'addons/superpwa-push-notification.php' );