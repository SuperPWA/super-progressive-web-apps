<?php
/**
 * Plugin Name: Super Progressive Web Apps
 * Plugin URI: https://superpwa.com/?utm_source=superpwa-plugin&utm_medium=plugin-uri
 * Description: Convert your WordPress website into a Progressive Web App
 * Author: SuperPWA
 * Author URI: https://profiles.wordpress.org/superpwa/
 * Contributors: SuperPWA
 * Version: 2.1.5
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
 * /addons/ 					- Bundled add-ons
 * /admin/					- Plugin backend.
 * /functions/					- Functions and utilites.
 * /includes/					- External third party classes and libraries.
 * /languages/					- Translation files go here. 
 * /public/ 					- Front end files go here.
 * index.php					- Dummy file.
 * license.txt					- GPL v2
 * loader.php					- Loads everything.
 * superpwa.php					- Main plugin file.
 * README.MD					- Readme for GitHub.
 * readme.txt					- Readme for WordPress plugin repository.
 * uninstall.php				- Fired when the plugin is uninstalled. 
 */

// Exit if accessed directly
if ( ! defined('ABSPATH') ) exit;

/**
 * SuperPWA current version
 *
 * @since 1.0
 */
if ( ! defined( 'SUPERPWA_VERSION' ) ) {
	define( 'SUPERPWA_VERSION'	, '2.1.5' ); 
}

/**
 * Absolute path to the plugin directory. 
 * eg - /var/www/html/wp-content/plugins/super-progressive-web-apps/
 *
 * @since 1.0
 */
if ( ! defined( 'SUPERPWA_PATH_ABS' ) ) {
	define( 'SUPERPWA_PATH_ABS'	, plugin_dir_path( __FILE__ ) ); 
}

/**
 * Link to the plugin folder. 
 * eg - https://example.com/wp-content/plugins/super-progressive-web-apps/
 *
 * @since 1.0
 */
if ( ! defined( 'SUPERPWA_PATH_SRC' ) ) {
	define( 'SUPERPWA_PATH_SRC'	, plugin_dir_url( __FILE__ ) ); 
}

/**
 * Full path to the plugin file. 
 * eg - /var/www/html/wp-content/plugins/Super-Progressive-Web-Apps/superpwa.php
 *
 * @since 2.0
 */
if ( ! defined( 'SUPERPWA_PLUGIN_FILE' ) ) {
	define( 'SUPERPWA_PLUGIN_FILE', __FILE__ ); 
}

// Load everything
require_once( SUPERPWA_PATH_ABS . 'loader.php' );
