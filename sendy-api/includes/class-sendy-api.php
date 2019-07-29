<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://dervinenyakundi.com
 * @since      1.0.0
 *
 * @package    Sendy_Api
 * @subpackage Sendy_Api/includes
 */
class Sendy_Api {
	protected $loader;
	protected $plugin_name;
	protected $version;

	public function __construct() {
		if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
			$this->version = PLUGIN_NAME_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'sendy-api';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sendy-api-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sendy-api-i18n.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-sendy-api-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-sendy-api-public.php';

		$this->loader = new Sendy_Api_Loader();

	}

	private function set_locale() {

		$plugin_i18n = new Sendy_Api_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	private function define_admin_hooks() {

		$plugin_admin = new Sendy_Api_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );


    }

	private function define_public_hooks() {

		$plugin_public = new Sendy_Api_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	public function run() {
		$this->loader->run();
	}

	public function get_plugin_name() {
		return $this->plugin_name;
	}

	public function get_loader() {
		return $this->loader;
	}

	public function get_version() {
		return $this->version;
	}

}
