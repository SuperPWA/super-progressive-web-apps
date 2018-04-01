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

// Load Filesystem functions
require_once( SUPERPWA_PATH_ABS . 'admin/filesystem.php');

// Manifest Functions
require_once( SUPERPWA_PATH_ABS . 'public/manifest.php');

// Service Worker Functions
require_once( SUPERPWA_PATH_ABS . 'public/sw.php');

// Do plugin operations
require_once( SUPERPWA_PATH_ABS . 'admin/do.php');