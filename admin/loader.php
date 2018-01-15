<?php
/**
 * Loads the plugin files
 *
 * @since 1.0
 */

// Exit if accessed directly
if ( ! defined('ABSPATH') ) exit;

// Load basic setup. Plugin list links, text domain, footer links etc. 
require_once( SUPERPWA_PATH_ABS . 'admin/basic-setup.php');

// Load admin setup. Register menus and settings
require_once( SUPERPWA_PATH_ABS . 'admin/admin-setup.php');

// Render Admin UI
require_once( SUPERPWA_PATH_ABS . 'admin/admin-ui-render.php');

// Do plugin operations
require_once( SUPERPWA_PATH_ABS . 'admin/do.php');