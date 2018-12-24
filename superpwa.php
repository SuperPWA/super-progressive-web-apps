<?php
/**
 * Plugin Name: Super Progressive Web Apps
 * Plugin URI: https://superpwa.com/?utm_source=superpwa-plugin&utm_medium=plugin-uri
 * Description: Convert your WordPress website into a Progressive Web App
 * Author: SuperPWA
 * Author URI: https://superpwa.com/?utm_source=superpwa-plugin&utm_medium=author-uri
 * Contributors: Arun Basil Lal, Jose Varghese
 * Version: 2.0
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
 * /admin/						- Plugin backend.
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
 * Define constants
 *
 * @since 1.0
 * @since 1.6 Depreciated constants for multisite compatibility: SUPERPWA_MANIFEST_FILENAME, SUPERPWA_MANIFEST_ABS, SUPERPWA_MANIFEST_SRC
 * @since 1.6 Depreciated constants for multisite compatibility: SUPERPWA_SW_FILENAME, SUPERPWA_SW_ABS, SUPERPWA_SW_SRC
 */
if ( ! defined( 'SUPERPWA_VERSION' ) )		define( 'SUPERPWA_VERSION'	, '1.9' ); // SuperPWA current version
if ( ! defined( 'SUPERPWA_PATH_ABS' ) ) 	define( 'SUPERPWA_PATH_ABS'	, plugin_dir_path( __FILE__ ) ); // Absolute path to the plugin directory. eg - /var/www/html/wp-content/plugins/super-progressive-web-apps/
if ( ! defined( 'SUPERPWA_PATH_SRC' ) ) 	define( 'SUPERPWA_PATH_SRC'	, plugin_dir_url( __FILE__ ) ); // Link to the plugin folder. eg - https://example.com/wp-content/plugins/super-progressive-web-apps/
if ( ! defined( 'SUPERPWA_PLUGIN_FILE' ) ) {
	define( 'SUPERPWA_PLUGIN_FILE', __FILE__ ); // Full path to the plugin file. eg - /var/www/html/wp-content/plugins/Super-Progressive-Web-Apps/superpwa.php
}
if ( ! defined( 'SUPERPWA_DEV_MODE' ) ) {
	/**
	 * Allows you to enable Dev mode when using SuperPWA plugin.
	 *
	 * To quickly enable Dev mode, use the following
	 * @example `add_filter( 'superpwa_dev_mode', '__return_true' );`
	 *
	 * @since   2.0
	 *
	 * @param bool Default false.
	 */
	$dev_mode = apply_filters( 'superpwa_dev_mode', false );
	$dev_mode = is_bool( $dev_mode ) ? $dev_mode : false;

	/**
	 * @const Global Set TRUE to enable Dev mode.
	 *
	 * Dev Mode disables SSL verification when using wp_remote_head()
	 * @see   superpwa_sw_status_cb()
	 * @see   superpwa_manifest_status_cb()
	 *
	 * @since 2.0
	 */
	define( 'SUPERPWA_DEV_MODE', $dev_mode );
}

// Load everything
require_once( SUPERPWA_PATH_ABS . 'loader.php' );