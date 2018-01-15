<?php
/**
 * Plugin Name: Super Progressive Web Apps
 * Plugin URI: https://superpwa.com
 * Description: Convert your WordPress website into a Progressive Web App
 * Author: SuperPWA
 * Contributors: Arun Basil Lal, Jose Varghese
 * Author URI: https://superpwa.com
 * Version: 1.0
 * Text Domain: super-progressive-web-apps
 * Domain Path: /languages
 * License: GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * This plugin was developed using the WordPress starter plugin template by Arun Basil Lal <arunbasillal@gmail.com>
 * Please leave this credit and the directory structure intact for future developers who might read the code. 
 * @Github https://github.com/arunbasillal/WordPress-superpwa
 */
 
/**
 * ~ Directory Structure ~
 *
 * /admin/ 						- Plugin backend stuff.
 * /includes/					- External third party classes and libraries.
 * /languages/					- Translation files go here. 
 * /public/						- Front end files go here.
 * index.php					- Dummy file.
 * license.txt					- GPL v2
 * superpwa.php					- File containing plugin name and other version info for WordPress.
 * readme.txt					- Readme for WordPress plugin repository. https://wordpress.org/plugins/files/2017/03/readme.txt
 * uninstall.php				- Fired when the plugin is uninstalled. 
 */
 
/**
 * ~ TODO ~
 *
 * - Plugin description
 * - Update uninstall.php
 * - Update readme.txt
 */

// Exit if accessed directly
if ( ! defined('ABSPATH') ) exit;

/**
 * Define constants
 *
 * @since 		1.0
 */

	/**
	 * The absolute path to the plugin directory without the trailing slash. Useful for using with includes
	 * eg - /var/www/html/wp-content/plugins/superpwa/
	 */
	if ( ! defined( 'SUPERPWA_PATH_ABS ') )
		define('SUPERPWA_PATH_ABS', plugin_dir_path( __FILE__ ));

	/**
	 * The url to the plugin folder. Useful for referencing src
	 * eg - http://localhost/wp/wp-content/plugins/superpwa/
	 */
	if ( ! defined('SUPERPWA_PATH_SRC') )
		define('SUPERPWA_PATH_SRC', plugin_dir_url( __FILE__ ));

	/**
	 * Plugin version constant
	 */
	if ( ! defined('SUPERPWA_VERSION') )
		define('SUPERPWA_VERSION', '1.0');

/**
 * Add plugin version to database
 *
 * @since 		1.0
 * @refer		https://codex.wordpress.org/Creating_Tables_with_Plugins#Adding_an_Upgrade_Function
 */
update_option('superpwa_version', SUPERPWA_VERSION);	// Change this to add_option if a release needs to check installed version.

// Load everything
require_once( SUPERPWA_PATH_ABS . 'admin/loader.php');

// Register activation hook (this has to be in the main plugin file.)
register_activation_hook( __FILE__, 'superpwa_activate_plugin' );

// Register deactivatation hook
register_deactivation_hook( __FILE__, 'superpwa_deactivate_plugin' );