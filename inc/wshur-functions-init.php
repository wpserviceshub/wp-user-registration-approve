<?php
/**
 * @link       https://wordpress.org/plugins/wpservicehub-user-registration
 * @since      1.0.0
 * @package    wpservicehub-user-registration
 * @subpackage wpservicehub-user-registration/inc
 */

/**
 * Fired during plugin run.
 *
 * This class defines all code necessary to run during the plugin's features.
 *
 * @since      1.0.0
 * @package    wpservicehub-user-registration
 * @subpackage wpservicehub-user-registration/inc
 * @author     Ashaduzzaman Mukul <wpserviceshub.cse@gmail.com>
 */


 if (!defined('ABSPATH')) {
    exit;
}

class WSHUR_functions_init {
    protected $loader;
    protected $plugin_name;
    protected $plugin_version;

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
        if(defined('WSHUR_VERSION')){
            $this->plugin_version = WSHUR_VERSION;
        } else {
            $this->plugin_version = '1.0.0';
        }

        $this->plugin_name = 'wpservicehub-user-registration';
        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     * Include the following files that make up the plugin.
     * 
     * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
     * 
	 * @since    1.0.0
	 * @access   private
     * 
     */
    private function load_dependencies() {
        /*
        * The class responsible for orchestrating the actions and filters of the
        * core plugin.
        */
        require_once WSHUR_DIR . 'inc/wshur-class-loader.php';


        /*
        * The class responsible for orchestrating the actions and filters of the
        * core plugin.
        */
        require_once WSHUR_DIR . 'inc/admin/wshur-admin-functions.php';

        /*
        * The class responsible for orchestrating the actions and filters of the
        * core plugin.
        */
        require_once WSHUR_DIR . 'inc/public/wshur-public-functions.php';

        $this->loader = new WSHUR_loader();
    }

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
    protected function define_admin_hooks() {
        $plugin_admin = new WSHUR_admin_functions( $this->get_plugin_name(), $this->get_version() );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'wshur_admin_enqueue_styles' );
    }
	

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
    public function define_public_hooks(){
        $plugin_public = new WSHUR_public_functions( $this->get_plugin_name(), $this->get_version() );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'wshur_frontend_enqueue_styles' );
    }


	/**
	 * Run the loader to execute all of the hooks with WordPress.
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
	 * @return    WFC_loader Orchestrates the hooks of the plugin.
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
		return $this->plugin_version;
	}
}

