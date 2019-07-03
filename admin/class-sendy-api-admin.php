<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://dervinenyakundi.com
 * @since      1.0.0
 *
 * @package    Sendy_Api
 * @subpackage Sendy_Api/admin
 */

/**
 * The admin-specific functionality of the plugin.
 */
class Sendy_Api_Admin {

	private $plugin_name;
	private $version;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sendy-api-admin.css', array(), $this->version, 'all' );

	}

	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sendy-api-admin.js', array( 'jquery' ), $this->version, true );

	}

}
