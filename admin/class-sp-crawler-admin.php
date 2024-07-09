<?php
require_once plugin_dir_path( __FILE__ ) . 'partials/sp-crawler-admin-display.php';
require_once plugin_dir_path( __FILE__ ) . 'partials/sp-crawler-images.php';
require_once plugin_dir_path( __FILE__ ) . 'partials/sp-crawler-meta.php';
require_once plugin_dir_path( __FILE__ ) . 'partials/sp-crawler-broken-links.php';
require_once plugin_dir_path( __FILE__ ) . 'partials/sp-crawler-url-length.php';
require_once plugin_dir_path( __FILE__ ) . 'partials/sp-crawler-speed-analyze.php';
require_once plugin_dir_path( __FILE__ ) . 'partials/sp-crawler-header-structure.php';
/**
 * The admin-specific functionality of the plugin.
 * @link       https://srdjan.icodes.rocks
 * @since      1.0.0
 *
 * @package    SP_Crawler
 * @subpackage SP_Crawler/admin
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    SP_Crawler
 * @subpackage SP_Crawler/admin
 * @author     Srki <stojanovicsrdjan27@gmail.com>
 */
class SP_Crawler_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->init_sp_crawler_admin_page();

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sp-crawler-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
	//	wp_enqueue_script( 'toast', plugin_dir_url( __FILE__ ) . 'js/jquery.toast.min.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sp-crawler-admin.js', array( 'jquery' ), $this->version, false );

	}


	public function init_sp_crawler_admin_page() {

		add_action('admin_menu', array($this, 'sp_crawler_admin_page' ) );

  }


	public function sp_crawler_admin_page() {


		$icon_base64 = 'PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPHN2ZyB2ZXJzaW9uPSIxLjEiIHZpZXdCb3g9IjAgMCAyMDQ4IDIwNDgiIHdpZHRoPSIxMjgwIiBoZWlnaHQ9IjEyODAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxwYXRoIHRyYW5zZm9ybT0idHJhbnNsYXRlKDE1ODAsNzY4KSIgZD0ibTAgMGg0MGwzMCAzIDI2IDUgMjYgNyAyNSA5IDI0IDExIDIyIDEyIDIxIDE0IDEyIDkgMTMgMTEgMTAgOSA4IDcgMTYgMTcgMTQgMTcgMTQgMjAgMTIgMjAgMTIgMjMgMTAgMjQgOCAyNCA2IDI0IDQgMjMgMiAxOCAxIDE4djIwbC0xIDIxLTUgMzUtNiAyNS03IDIyLTEwIDI1LTEwIDIxLTE2IDI3LTEwIDE0LTEwIDEzLTEzIDE1LTI1IDI1LTExIDktMTcgMTMtMTcgMTEtMTcgMTAtMTUgOC0yMSA5LTIyIDgtMjkgOC0xMCAydjE5OGw5IDEgOSA0IDggOCA1IDEwIDEgNXYyMjdsLTIgMTgtNSAxMi03IDExLTEyIDEyLTE2IDgtMTEgMy0xMiAxaC02NWwtMTUtMi0xMy01LTEwLTctNy02LTktMTItNS0xMS0zLTE0di0yMzhsNC0xMCA3LTggMTItNiA4LTJ2LTE5NmwtNS0yLTI3LTctMjktMTAtMjQtMTEtMTktMTAtMjItMTQtMTctMTMtMTEtOS0xMi0xMS0yMS0yMS05LTExLTE0LTE4LTEzLTIwLTEwLTE4LTEzLTI3LTEwLTI4LTctMjUtNS0yNy0zLTI5di00MWwzLTI4IDUtMjcgNi0yMyA5LTI1IDEyLTI3IDgtMTQgNi0xMSAxNC0yMSAxMS0xNCAxMS0xMyAxMS0xMiAxMi0xMiA4LTcgMTYtMTMgMjAtMTQgMjAtMTIgMjMtMTIgMTktOCAzMC0xMCAyNS02IDMzLTV6IiBmaWxsPSIjMzEzQzVDIi8+CjxwYXRoIHRyYW5zZm9ybT0idHJhbnNsYXRlKDE3MDMsOTEpIiBkPSJtMCAwaDExbDQyIDggOTIgMTggNzUgMTUgMjMgNSAxMCA0IDUgNCA2IDkgMyA3djEzbC01IDE3LTE1IDQzLTI4IDgzLTE0IDQwLTEyIDM2LTUgMTItOSAxMC04IDQtMTMgMi0xMC0yLTktNi03LTgtMTUtMjYtMTYtMjgtMi0zLTYgMi0yOSAxNC00OCAyNC0yMyAxMS0yNiAxMy0yMyAxMS01MiAyNi0yMyAxMS0zOCAxOS0yMyAxMS00NCAyMi0yMyAxMS00MiAyMS0yMyAxMS00NCAyMi0yMyAxMS0zOCAxOS0yMyAxMS0yNCAxMi0xNiA2LTEyIDMtMTEgMWgtMTBsLTE1LTItMTMtNC0xNi04LTktNi0xMC05LTgtOS03LTExLTEyLTIzLTEwOS0yMTgtMjQgMTAtMjAgOS0yMDEgODgtODAgMzUtMzAgMTMtODIgMzYtMzkgMTctNDMgMTktMzAgMTMtNDEgMTgtMzIgMTQtMzAgMTMtNDMgMTktODAgMzUtMTkgOC0xMyA0LTYgMWgtMTNsLTE0LTMtMTYtOC0xMi0xMS05LTE0LTQtMTAtMi0xMXYtOTlsNC0xNiA2LTExIDktMTEgMTAtOCAxNi04IDQ4LTIwIDM1LTE1IDQzLTE4IDU0LTIzIDQ1LTE5IDc4LTMzIDE3NS03NCA0My0xOCA1NC0yMyA0My0xOCA1NC0yMyAyNi0xMSAzMS0xMyA1NC0yMyA0NS0xOSAyNS0xMCAxMS0zIDctMWgyM2wxNiAzIDE1IDYgMTUgOSAxMiAxMSA4IDkgOCAxMyAxMiAyMyA5IDE5IDkgMTcgMTEgMjMgOSAxNyAxMSAyMyA5IDE3IDEwIDIxIDEwIDE5IDkgMTkgMTAgMTkgOCAxNiAxIDQgMTQtNSAzMC0xMyAzNi0xNSAxMDEtNDMgMzYtMTUgNTQtMjMgODUtMzYgMTMwLTU1IDctMy0xLTQtMTItMjEtMTQtMjQtMTItMjEtNC0xMiAxLTExIDYtMTIgOS04eiIgZmlsbD0iI0ZFRDIyRCIvPgo8cGF0aCB0cmFuc2Zvcm09InRyYW5zbGF0ZSgyODgsNzY4KSIgZD0ibTAgMGgxMDFsMjEgMiAyMSA1IDIxIDggMTcgOSAxNyAxMiAxNCAxMiA5IDkgMTEgMTQgMTAgMTUgMTEgMjMgNiAxOCA0IDE5IDIgMjB2MTFsLTMgMTYtOCAxNi0xMSAxMi05IDctMTQgNi05IDJoLTIybC0xMy00LTEwLTUtMTAtOC03LTgtNy0xMi00LTEyLTMtMjQtNS0xMy02LTgtNy02LTEwLTUtOS0yaC05N2wtMTcgNC0xMyA3LTEwIDgtOCA5LTggMTQtNSAxNi0xIDE3IDIgMTMgNiAxNiA3IDExIDggOSAxMyA5IDE0IDYgMTcgMyA2NyAxIDIzIDIgMTkgNCAyMCA2IDE4IDggMTUgOCAxMiA4IDEzIDEwIDEzIDEyIDkgOSAxMSAxNCA3IDEwIDExIDE5IDEwIDI0IDYgMjEgMyAxNiAyIDIydjE1bC0zIDI3LTUgMjEtNiAxOC0xMiAyNS05IDE0LTkgMTItMTIgMTQtOSA5LTExIDktMTUgMTEtMTggMTAtMTcgOC0yMiA3LTI1IDUtMjUgMmgtODRsLTE5LTEtMTgtMy0xNS00LTE2LTYtMTYtOC0xMi03LTEzLTEwLTEyLTExLTExLTExLTEyLTE2LTExLTE5LTgtMTktNi0yMS0zLTE3LTEtMTN2LTEzbDMtMTQgOC0xNiA4LTEwIDEwLTggMTQtNyAxNC0zaDE0bDE0IDMgMTYgOCAxMiAxMSA3IDEwIDYgMTMgMiA5IDIgMTkgNCAxMSA2IDkgNSA1IDEwIDYgOSAzIDYgMWg4OWwxNC0yIDEzLTUgMTItNyAxMS0xMCA5LTE0IDUtMTIgMy0xNXYtMTJsLTMtMTYtNC05LTYtMTEtMTEtMTItMTEtOC0xMy02LTE0LTMtNjYtMS0yNy0yLTI0LTUtMTgtNi0yMC05LTE2LTktMTQtMTAtMTQtMTItMTctMTctMTMtMTctMTItMjAtOC0xNy04LTI0LTUtMjQtMi0yMnYtMTJsMi0yMiA1LTI1IDctMjEgOC0xOCAxMi0yMCAxMC0xMyAxMi0xNCAxMC0xMCAxMS05IDE0LTEwIDE1LTkgMTktOSAyNC04IDIwLTR6IiBmaWxsPSIjMjQyRTREIi8+CjxwYXRoIHRyYW5zZm9ybT0idHJhbnNsYXRlKDgzOCw3NjgpIiBkPSJtMCAwaDIxNmwxNiAzIDEyIDYgOSA3IDYgNSA4IDExIDYgMTIgMyAxMSAxIDd2OWwtMyAxNS01IDEyLTggMTItOSA5LTEzIDgtMTQgNS01IDEtMjEyIDEtMTMgMi0xMCA1LTkgOC02IDExLTIgMTUtMSA5NGgyNDlsMTIgMiAxMiA1IDExIDcgNyA2IDkgMTIgNyAxNSAzIDE1djlsLTMgMTUtNSAxMi03IDExLTExIDExLTE0IDgtMTYgNS0xMSAxaC0yNDNsMSA5NiAyIDEyIDUgMTAgNiA3IDEwIDYgMTEgMyAyMDYgMSAxNSAxIDEzIDQgMTIgNyAxMCA5IDcgOCA4IDE2IDMgMTAgMSAxNi0zIDE2LTggMTYtNyA5LTggOC0xMyA4LTE2IDUtMTMgMWgtMTk5bC0yNy0yLTIyLTUtMTktNy0yMy0xMi0xMS04LTEwLTgtMTItMTEtNy04LTEwLTEzLTktMTUtNy0xNC03LTE5LTUtMjItMi0yM3YtMzQybDItMTkgNS0yMiA4LTIyIDktMTcgOC0xMiA4LTEwIDEyLTEzIDctNyAxMy0xMCAxOC0xMSAxOS05IDE1LTUgMTgtNHoiIGZpbGw9IiMyNDJFNEQiLz4KPHBhdGggdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMTU4Myw4OTYpIiBkPSJtMCAwaDM0bDIxIDMgMjEgNSAxOCA2IDI1IDEyIDE2IDEwIDEzIDEwIDEzIDExIDEyIDEyIDEzIDE3IDcgMTAgOCAxNCA4IDE2IDggMjQgNSAyMSAzIDIydjMwbC0zIDIyLTUgMjEtNiAxOC05IDIwLTEwIDE3LTEyIDE3LTExIDEyLTcgOC04IDctMTUgMTItMTcgMTEtMTYgOC0xNiA3LTE5IDYtMjYgNS0xOSAyaC0xOWwtMjYtMy0yNS02LTE3LTYtMjItMTAtMTgtMTEtMTQtMTEtMTAtOS0xNy0xNy0xNS0yMC0xMi0yMS05LTIwLTctMjMtNS0yNi0xLTEwdi0zMWwzLTIyIDYtMjQgNy0yMCA4LTE2IDktMTYgOS0xMiA5LTExIDE0LTE1IDgtNyAxNS0xMiAxOC0xMSAyMy0xMSAyNC04IDIyLTV6IiBmaWxsPSIjMTZCMkZEIi8+CjxwYXRoIHRyYW5zZm9ybT0idHJhbnNsYXRlKDE2NjMsMTQ0NCkiIGQ9Im0wIDBoMXYxODhsOSAxIDkgNCA4IDggNSAxMCAxIDV2MjI3bC0yIDE4LTUgMTItNyAxMS0xMiAxMi0xNiA4LTExIDMtMTIgMWgtNjVsLTE1LTItMTMtNS0xMC03LTctNi05LTEyLTUtMTEtMy0xNHYtMjM4bDQtMTAgNy04IDEyLTYgOS0yIDkgMWgxMTh6IiBmaWxsPSIjMzEzQzVDIi8+CjxwYXRoIHRyYW5zZm9ybT0idHJhbnNsYXRlKDE2NTUsMTQzNSkiIGQ9Im0wIDBoOGwxIDEtMSAxOTYtMTIzIDEtNC0xdi0xOTVoMTVsMzQgMWgyNWwyOC0xeiIgZmlsbD0iIzI0MkU0RCIvPgo8cGF0aCB0cmFuc2Zvcm09InRyYW5zbGF0ZSgxNDcxLDEyNjkpIiBkPSJtMCAwIDUgMSA2IDUtMSAzdi0ybC01LTF6IiBmaWxsPSIjMjgzMjUyIi8+Cjwvc3ZnPgo=';
	$icon_data_uri = 'data:image/svg+xml;base64,' . $icon_base64;


		add_menu_page(
		'SP Crawler', 
		'SP Crawler ', 
		'manage_options',
		'sp-crawler',
		 function() { sp_crawler_page(1, 'sp-crawler'); },
		 //'music_player_option_page', 
		 $icon_data_uri
	);


	add_submenu_page(
		'sp-crawler-images', 
		'SP Crawler Images', 
		'SP Crawler Images', 
		'manage_options', 
		'sp-crawler-images',
		 function() { sp_crawler_images(1, 'sp-crawler-images'); },
	);

	add_submenu_page(
		'sp-crawler-meta', 
		'SP Crawler Meta Tags', 
		'SP Crawler Meta Tags', 
		'manage_options', 
		'sp-crawler-meta',
		 function() { sp_crawler_meta(1, 'sp-crawler-meta'); },
	);

	add_submenu_page(
		'sp-crawler-broken-links', 
		'SP Crawler Broken Links', 
		'SP Crawler Broken Links', 
		'manage_options', 
		'sp-crawler-broken-links',
		 function() { sp_crawler_broken_links(1, 'sp-crawler-broken-links'); },
	);

	add_submenu_page(
		'sp-crawler-url-length', 
		'SP Crawler URL Length', 
		'SP Crawler URL Length', 
		'manage_options', 
		'sp-crawler-url-length',
		 function() { sp_crawler_url_length(1, 'sp-crawler-url-length'); },
	);

	add_submenu_page(
		'sp-crawler-speed-analyze', 
		'SP Crawler Speed Analyze', 
		'SP Crawler Speed Analyze', 
		'manage_options', 
		'sp-crawler-speed-analyze',
		 function() { sp_crawler_speed_analyze(1, 'sp-crawler-speed-analyze'); },
	);

	add_submenu_page(
		'sp-crawler-header-structure', 
		'SP Crawler Header Structure', 
		'SP Crawler Header Structure', 
		'manage_options', 
		'sp-crawler-header-structure',
		 function() { sp_crawler_header_structure(1, 'sp-crawler-header-structure'); },
	);

	}

}

