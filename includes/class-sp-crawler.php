<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/srkis
 * @since      1.0.0
 *
 * @package    SP_Crawler
 * @subpackage SP_Crawler/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    SP_Crawler
 * @subpackage SP_Crawler/includes
 * @author     Srdjan Stojanovic <stojanovicsrdjan27@gmail.com>
 */
class SP_Crawler {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      SP_Crawler_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'SP_CRAWLER_VERSION' ) ) {
			$this->version = SP_CRAWLER_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'seo-performance-crawler';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Plugin_Name_Loader. Orchestrates the hooks of the plugin.
	 * - Plugin_Name_i18n. Defines internationalization functionality.
	 * - Plugin_Name_Admin. Defines all hooks for the admin area.
	 * - Plugin_Name_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sp-crawler-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sp-crawler-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-sp-crawler-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-sp-crawler-public.php';

		$this->loader = new SP_Crawler_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Plugin_Name_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new SP_Crawler_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new SP_Crawler_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new SP_Crawler_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action( 'wp_ajax_sp_crawler_fetch_images', $plugin_public, 'sp_crawler_fetch_images', 1);
		$this->loader->add_action( 'wp_ajax_nopriv_sp_crawler_fetch_images', $plugin_public, 'sp_crawler_fetch_images', 1);

		$this->loader->add_action( 'wp_ajax_sp_crawler_fetch_img_data', $plugin_public, 'sp_crawler_fetch_img_data', 1);
		$this->loader->add_action( 'wp_ajax_nopriv_sp_crawler_fetch_img_data', $plugin_public, 'sp_crawler_fetch_img_data', 1);

		$this->loader->add_action( 'wp_ajax_sp_crawler_fetch_meta', $plugin_public, 'sp_crawler_fetch_meta', 1);
		$this->loader->add_action( 'wp_ajax_nopriv_sp_crawler_fetch_meta', $plugin_public, 'sp_crawler_fetch_meta', 1);
	
		$this->loader->add_action( 'wp_ajax_sp_crawler_fetch_meta_data', $plugin_public, 'sp_crawler_fetch_meta_data', 1);
		$this->loader->add_action( 'wp_ajax_nopriv_sp_crawler_fetch_meta_data', $plugin_public, 'sp_crawler_fetch_meta_data', 1);
		
		$this->loader->add_action( 'wp_ajax_sp_crawler_fetch_broken_urls', $plugin_public, 'sp_crawler_fetch_broken_urls', 1);
		$this->loader->add_action( 'wp_ajax_nopriv_sp_crawler_fetch_broken_urls', $plugin_public, 'sp_crawler_fetch_broken_urls', 1);
	
		$this->loader->add_action( 'wp_ajax_sp_crawler_fetch_broken_urls_data', $plugin_public, 'sp_crawler_fetch_broken_urls_data', 1);
		$this->loader->add_action( 'wp_ajax_nopriv_sp_crawler_fetch_broken_urls_data', $plugin_public, 'sp_crawler_fetch_broken_urls_data', 1);

		$this->loader->add_action( 'wp_ajax_sp_crawler_fetch_url_length', $plugin_public, 'sp_crawler_fetch_url_length', 1);
		$this->loader->add_action( 'wp_ajax_nopriv_sp_crawler_fetch_url_length', $plugin_public, 'sp_crawler_fetch_url_length', 1);

		$this->loader->add_action( 'wp_ajax_sp_crawler_fetch_url_length_data', $plugin_public, 'sp_crawler_fetch_url_length_data', 1);
		$this->loader->add_action( 'wp_ajax_nopriv_sp_crawler_fetch_url_length_data', $plugin_public, 'sp_crawler_fetch_url_length_data', 1);

		$this->loader->add_action( 'wp_ajax_sp_crawler_fetch_speed_analysis', $plugin_public, 'sp_crawler_fetch_speed_analysis', 1);
		$this->loader->add_action( 'wp_ajax_nopriv_sp_crawler_fetch_speed_analysis', $plugin_public, 'sp_crawler_fetch_speed_analysis', 1);

		$this->loader->add_action( 'wp_ajax_sp_crawler_fetch_speed_analysis_data', $plugin_public, 'sp_crawler_fetch_speed_analysis_data', 1);
		$this->loader->add_action( 'wp_ajax_nopriv_sp_crawler_fetch_speed_analysis_data', $plugin_public, 'sp_crawler_fetch_speed_analysis_data', 1);

		$this->loader->add_action( 'wp_ajax_sp_crawler_fetch_header_structure', $plugin_public, 'sp_crawler_fetch_header_structure', 1);
		$this->loader->add_action( 'wp_ajax_nopriv_sp_crawler_fetch_header_structure', $plugin_public, 'sp_crawler_fetch_header_structure', 1);

		$this->loader->add_action( 'wp_ajax_sp_crawler_fetch_header_structure_data', $plugin_public, 'sp_crawler_fetch_header_structure_data', 1);
		$this->loader->add_action( 'wp_ajax_nopriv_sp_crawler_fetch_header_structure_data', $plugin_public, 'sp_crawler_fetch_header_structure_data', 1);

	}	

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Plugin_Name_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
