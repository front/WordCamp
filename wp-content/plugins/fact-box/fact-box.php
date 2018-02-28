<?php

/**
 * The plugin bootstrap file
 *
 * @link              https://www.frontkom.no/
 * @since             1.0.0
 * @package           Fact_Box
 *
 * @wordpress-plugin
 * Plugin Name:       Fact Box
 * Plugin URI:        https://www.frontkom.no/
 * Description:       You can create re-usable facts and add them to the posts.
 * Version:           1.0.0
 * Author:            Foad Yousefi
 * Author URI:        https://www.frontkom.no/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       fact-box
 * Domain Path:       /lang
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define constants
 */
if ( ! defined( 'FACT_BOX_PATH' ) ) {
	define( 'FACT_BOX_PATH', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'FACT_BOX_URL' ) ) {
	define( 'FACT_BOX_URL', plugin_dir_url( __FILE__ ) );
}
if ( ! defined( 'FACT_BOX_BASE' ) ) {
	define( 'FACT_BOX_BASE', plugin_basename( __FILE__ ) );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-fact-box-activator.php
 */
function activate_fact_box() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-fact-box-activator.php';
	Fact_Box_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-fact-box-deactivator.php
 */
function deactivate_fact_box() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-fact-box-deactivator.php';
	Fact_Box_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_fact_box' );
register_deactivation_hook( __FILE__, 'deactivate_fact_box' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-fact-box.php';


/**
* Add plugin action link.
*/
add_filter( 'plugin_action_links', 'fact_box_actions_link', 10, 5 );
function fact_box_actions_link( $actions, $plugin_file )
{
	static $plugin;

	if (!isset($plugin))
		$plugin = plugin_basename(__FILE__);
	if ($plugin == $plugin_file) {
		$settings = array('settings' => '<a href="edit.php?post_type=fact_box&page=edit.php%3Fpost_type%3Dfact_box-fact-box">' . __('Settings', 'newsfront-core') . '</a>');

		$actions = array_merge($settings, $actions);
	}
	return $actions;
}

/**
* Get options from TitanFramework::getInstance
*
* @return  $option
* @param   option stored in database
*/

function fact_options($option) {
	$titan = TitanFramework::getInstance( 'newsfront_core' );
	$option = $titan->getOption( $option );
	return $option;
}


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_fact_box() {

	$plugin = new Fact_Box();
	$plugin->run();

}
run_fact_box();
