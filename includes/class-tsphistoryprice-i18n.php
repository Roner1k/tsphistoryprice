<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://tsppilot.sitepreview.app/
 * @since      1.0.0
 *
 * @package    Tsphistoryprice
 * @subpackage Tsphistoryprice/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Tsphistoryprice
 * @subpackage Tsphistoryprice/includes
 * @author     Next Level <roner1kk@gmail.com>
 */
class Tsphistoryprice_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'tsphistoryprice',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
