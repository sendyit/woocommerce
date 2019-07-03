<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://dervinenyakundi.com
 * @since      1.0.0
 *
 * @package    Sendy_Api
 * @subpackage Sendy_Api/public
 */

/**
 * The public-facing functionality of the plugin.
 */
class Sendy_Api_Public {
	private $plugin_name;

	private $version;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Sendy_Api_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Sendy_Api_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sendy-api-public.css', array(), $this->version, 'all' );

	}
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cookie.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sendy-api-public.js', array( 'jquery' ), $this->version, false );

	}

}
