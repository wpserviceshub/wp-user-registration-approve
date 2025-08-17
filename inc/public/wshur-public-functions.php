<?php
/**
 * Fired during plugin activation
 *
 * @link       https://wordpress.org/plugins/wpservicehub-user-registration
 * @since      1.0.0
 * @package    wpservicehub-user-registration
 * @subpackage wpservicehub-user-registration/inc
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    wpservicehub-user-registration
 * @subpackage wpservicehub-user-registration/inc
 * @author     Ashaduzzaman Mukul <wpserviceshub.cse@gmail.com>
 */

 if (!defined('ABSPATH')) {
    exit;
}

class WSHUR_public_functions {

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
	 * @var      string    $plugin_version    The current version of this plugin.
	 */
	private $plugin_version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $plugin_version    The version of this plugin.
	 */
    public function __construct( $plugin_name, $plugin_version) {
        $this->plugin_name = $plugin_name;
        $this->plugin_version = $plugin_version;
    }

    public function wshur_frontend_enqueue_styles() {
	    wp_register_script('wshur-main', WSHUR_DIR_URL . 'assets/js/wshur-main.js', array( 'jquery' ), $this->plugin_version, false);

	    // Enqueued script with localized data.
	    wp_enqueue_script( 'wshur-main' );
    }
}