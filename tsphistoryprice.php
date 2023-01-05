<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.tsppilot.com/
 * @since             1.0.0
 * @package           Tsphistoryprice
 *
 * @wordpress-plugin
 * Plugin Name:       TSP Price history
 * Plugin URI:        https://www.tsppilot.com/
 * Description:       Import prices from www.tsp.gov and calculate
 * Version:           1.0.23
 * Author:            Next Level
 * Author URI:        https://nextlevelwebsolutions.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tsphistoryprice
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('TSPHISTORYPRICE_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-tsphistoryprice-activator.php
 */
function activate_tsphistoryprice()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-tsphistoryprice-activator.php';
    Tsphistoryprice_Activator::activate();

    // install DB Table on Plugin Activation
    // register_activation_hook(__FILE__, array($this, 'tsphp_install_DB'));

}




/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-tsphistoryprice-deactivator.php
 */
function deactivate_tsphistoryprice()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-tsphistoryprice-deactivator.php';
    Tsphistoryprice_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_tsphistoryprice');
register_deactivation_hook(__FILE__, 'deactivate_tsphistoryprice');


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-tsphistoryprice.php';

require plugin_dir_path(__FILE__) . 'includes/class-tsphp_import_build.php';
require plugin_dir_path(__FILE__) . 'includes/class-tsphp_new_import.php';
require plugin_dir_path(__FILE__) . 'includes/class-tsphp_math_table.php';
require plugin_dir_path(__FILE__) . 'includes/class-tsphp_alert_dates.php';
require plugin_dir_path(__FILE__) . 'includes/class-tsphp_log_build.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_tsphistoryprice()
{

    $plugin = new Tsphistoryprice();
    $plugin->run();

}

//run_tsphistoryprice();
add_action('init', 'run_tsphistoryprice');
