<?php
/**
 * Plugin Name: Super Progressive Web Apps
 * Plugin URI: https://superpwa.com
 * Description: Convert your WordPress website into a Progressive Web App
 * Author: SuperPWA
 * Author URI: https://superpwa.com
 * Contributors: Arun Basil Lal, Jose Varghese
 * Version: 1.6
 * Text Domain: super-progressive-web-apps
 * Domain Path: /languages
 * License: GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * ~ Directory Structure ~
 *
 * Based on the WordPress starter plugin template
 * @link https://github.com/arunbasillal/WordPress-Starter-Plugin
 *
 * /3rd-party/					- Functions for compatibility with 3rd party plugins and hosts.
 * /admin/ 						- Plugin backend.
 * /functions/					- Functions and utilites.
 * /includes/					- External third party classes and libraries.
 * /languages/					- Translation files go here. 
 * /public/						- Front end files go here.
 * index.php					- Dummy file.
 * license.txt					- GPL v2
 * loader.php					- Loads everything.
 * superpwa.php					- Main plugin file.
 * README.MD					- Readme for GitHub.
 * readme.txt					- Readme for WordPress plugin repository.
 * uninstall.php				- Fired when the plugin is uninstalled. 
 */

/**
 * ~ Release TODO ~
 *
 * Update SUPERPWA_VERSION
 * Spellcheck readme.txt
 */

// Exit if accessed directly
if ( ! defined('ABSPATH') ) exit;

/**
 * Define constants
 *
 * @since 1.0
 * @since 1.6 Depreciated constants: SUPERPWA_MANIFEST_FILENAME, SUPERPWA_MANIFEST_ABS, SUPERPWA_MANIFEST_SRC
 * @since 1.6 Depreciated constants: SUPERPWA_SW_FILENAME, SUPERPWA_SW_ABS, SUPERPWA_SW_SRC
 */
if ( ! defined('SUPERPWA_VERSION') ) 	define('SUPERPWA_VERSION'	, '1.6'); // SuperPWA current version
if ( ! defined('SUPERPWA_PATH_ABS ') ) 	define('SUPERPWA_PATH_ABS'	, plugin_dir_path( __FILE__ )); // Absolute path to the plugin directory. eg - /var/www/html/wp-content/plugins/super-progressive-web-apps/
if ( ! defined('SUPERPWA_PATH_SRC') ) 	define('SUPERPWA_PATH_SRC'	, plugin_dir_url( __FILE__ )); // Link to the plugin folder. eg - http://example.com/wp/wp-content/plugins/super-progressive-web-apps/

// Load everything
require_once( SUPERPWA_PATH_ABS . 'loader.php');

// Register activation hook (this has to be in the main plugin file.)
register_activation_hook( __FILE__, 'superpwa_activate_plugin' );

// Register deactivatation hook
register_deactivation_hook( __FILE__, 'superpwa_deactivate_plugin' );