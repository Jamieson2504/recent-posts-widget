<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://mike-jamieson.co.uk
 * @since             1.0.0
 * @package           My_Posts_Widget
 *
 * @wordpress-plugin
 * Plugin Name:       My Posts Widget
 * Plugin URI:        https://test.local
 * Description:       This is a description of the plugin.
 * Version:           1.0.0
 * Author:            Michael Jamieson
 * Author URI:        https://mike-jamieson.co.uk/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mpw
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
define( 'MY_POSTS_WIDGET_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/mpw-activator.php
 */
function activate_my_posts_widget() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/mpw-activator.php';
	My_Posts_Widget_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/mpw-deactivator.php
 */
function deactivate_my_posts_widget() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/mpw-deactivator.php';
	My_Posts_Widget_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_my_posts_widget' );
register_deactivation_hook( __FILE__, 'deactivate_my_posts_widget' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/mpw-setup.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_my_posts_widget() {

	$plugin = new My_Posts_Widget();
	$plugin->run();

}
run_my_posts_widget();
