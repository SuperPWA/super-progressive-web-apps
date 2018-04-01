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
 * @since 		1.0
 */
if ( ! defined('SUPERPWA_VERSION') ) 			define('SUPERPWA_VERSION'			, '1.6'); // Plugin version
if ( ! defined('SUPERPWA_PATH_ABS ') ) 			define('SUPERPWA_PATH_ABS'			, plugin_dir_path( __FILE__ )); // Absolute path to the plugin directory. eg - /var/www/html/wp-content/plugins/super-progressive-web-apps/
if ( ! defined('SUPERPWA_PATH_SRC') ) 			define('SUPERPWA_PATH_SRC'			, plugin_dir_url( __FILE__ )); // Link to the plugin folder. eg - http://example.com/wp/wp-content/plugins/super-progressive-web-apps/
if ( ! defined('SUPERPWA_MANIFEST_FILENAME') ) 	define('SUPERPWA_MANIFEST_FILENAME'	, 'superpwa-manifest.json'); // Name of Manifest file
if ( ! defined('SUPERPWA_MANIFEST_ABS') )		define('SUPERPWA_MANIFEST_ABS'		, trailingslashit( ABSPATH ) . SUPERPWA_MANIFEST_FILENAME); // Absolute path to manifest
if ( ! defined('SUPERPWA_MANIFEST_SRC') )		define('SUPERPWA_MANIFEST_SRC'		, trailingslashit( get_bloginfo('wpurl') ) . SUPERPWA_MANIFEST_FILENAME); // Link to manifest
if ( ! defined('SUPERPWA_SW_FILENAME') ) 		define('SUPERPWA_SW_FILENAME'		, 'superpwa-sw.js'); // Name of service worker file
if ( ! defined('SUPERPWA_SW_ABS') )				define('SUPERPWA_SW_ABS'			, trailingslashit( ABSPATH ) . SUPERPWA_SW_FILENAME); // Absolute path to service worker. SW must be in the root folder
if ( ! defined('SUPERPWA_SW_SRC') )				define('SUPERPWA_SW_SRC'			, trailingslashit( get_bloginfo('wpurl') ) . SUPERPWA_SW_FILENAME); // Link to service worker

// Load everything
require_once( SUPERPWA_PATH_ABS . 'loader.php');

// Register activation hook (this has to be in the main plugin file.)
register_activation_hook( __FILE__, 'superpwa_activate_plugin' );

// Register deactivatation hook
register_deactivation_hook( __FILE__, 'superpwa_deactivate_plugin' );