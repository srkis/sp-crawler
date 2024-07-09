<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://srdjan.icodes.rocks
 * @since      1.0.0
 *
 * @package    SP_Crawler
 * @subpackage SP_Crawler/includes
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    SP_Crawler
 * @subpackage SP_Crawler/inludes
 * @author     Srki <stojanovicsrdjan27@gmail.com>
 */
class SP_Crawler_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'seo-performance-crawler',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
