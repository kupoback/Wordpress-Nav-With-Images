<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://cliquestudios.com/
 * @since      1.0.0
 *
 * @package    Cs_Custom_Navigation
 * @subpackage Cs_Custom_Navigation/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Cs_Custom_Navigation
 * @subpackage Cs_Custom_Navigation/includes
 * @author     Nick Makris | Clique Studios <nmakris@cliquestudios.com>
 */
class Cs_Custom_Navigation_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'cs-custom-navigation',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
