<?php

/**
 * The main plugin file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/srkis
 * @since             1.0.0
 * @package           Seo_Performance_Crawler
 *
 * @wordpress-plugin
 * Plugin Name:       Seo Performance Crawler
 * Plugin URI:        https://github.com/srkis
 * Description:       Boost Your Performance and Rankings with Precision Crawling
 * Version:           1.0.0
 * Author:            Srki
 * Author URI:        https://srdjan.icodes.rocks/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       seo-performance-crawler
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'SP_CRAWLER_VERSION', '1.0.0' );
define('SP_CRAWLER_DIR', plugin_dir_url(__FILE__));
define('SP_CRAWLER_IMG_DATA', plugin_dir_path(__FILE__) . 'data/results.json');

function activate_sp_crawler() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sp-crawler-activator.php';
	SP_Crawler_Activator::activate();
}


function deactivate_sp_crawler() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sp-crawler-deactivator.php';
	SP_Crawler_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_sp_crawler' );
register_deactivation_hook( __FILE__, 'deactivate_sp_crawler' );


require plugin_dir_path( __FILE__ ) . 'includes/class-sp-crawler.php';


function run_sp_crawler() {

	$plugin = new SP_Crawler();
	$plugin->run();

}
run_sp_crawler();


