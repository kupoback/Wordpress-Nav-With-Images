<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://cliquestudios.com/
 * @since             1.0.0
 * @package           Cs_Custom_Navigation
 *
 * @wordpress-plugin
 * Plugin Name:       Custom Navigation
 * Plugin URI:        #
 * Description:       Custom Navigation build out for Alta Return that adds a media uploader to children items.
 * Version:           1.0.0
 * Author:            Nick Makris | Clique Studios
 * Author URI:        https://cliquestudios.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cs-custom-navigation
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CS_CUSTOM_NAVIGATION_VERSION', '1.0.0' );
define( 'CS_CUSTOM_NAVIGATION_FILE_PATH', plugin_dir_path(__FILE__) );
define( 'CS_CUSTOM_NAVIGATION_DIR_PATH', plugin_dir_path(dirname( __FILE__ ) ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cs-custom-navigation-activator.php
 */
function activate_cs_custom_navigation() {
	require_once CS_CUSTOM_NAVIGATION_FILE_PATH . 'includes/class-cs-custom-navigation-activator.php';
	Cs_Custom_Navigation_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cs-custom-navigation-deactivator.php
 */
function deactivate_cs_custom_navigation() {
	require_once CS_CUSTOM_NAVIGATION_FILE_PATH . 'includes/class-cs-custom-navigation-deactivator.php';
	Cs_Custom_Navigation_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_cs_custom_navigation' );
register_deactivation_hook( __FILE__, 'deactivate_cs_custom_navigation' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require CS_CUSTOM_NAVIGATION_FILE_PATH . 'includes/class-cs-custom-navigation.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cs_custom_navigation() {

	$plugin = new Cs_Custom_Navigation();
	$plugin->run();

}
run_cs_custom_navigation();
