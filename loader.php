<?php
/**
 * Loads the plugin files
 *
 * @since 1.0
 */

// Exit if accessed directly
if ( ! defined('ABSPATH') ) exit;

// Load admin
require_once( SUPERPWA_PATH_ABS . 'admin/basic-setup.php');
require_once( SUPERPWA_PATH_ABS . 'admin/admin-setup.php');
require_once( SUPERPWA_PATH_ABS . 'admin/admin-ui-render.php');

// 3rd party compatibility
require_once( SUPERPWA_PATH_ABS . '3rd-party/onesignal.php');

// Load functions
require_once( SUPERPWA_PATH_ABS . 'functions/common.php');
require_once( SUPERPWA_PATH_ABS . 'functions/filesystem.php');

// Public folder
require_once( SUPERPWA_PATH_ABS . 'public/manifest.php');
require_once( SUPERPWA_PATH_ABS . 'public/sw.php');