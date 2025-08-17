<?php
/**
 * Plugin Name: WP Services Hub User Registration
 * Plugin URI: https://wordpress.org/plugins/wpservicehub-user-registration
 * Description: A comprehensive user registration plugin with advanced features for managing user registrations on your WordPress site.
 * Version: 1.0.0
 * Author: Ashaduzzaman Mukul
 * Author URI: https://wpserviceshub.com/
 * Text Domain: wshur
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.3
 * Requires PHP: 7.4
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

 if (!defined('ABSPATH')) {
    exit;
}


/**
 * Currently plugin version.
 * Start at version 1.0.0 
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WSHUR_VERSION', '1.0.0' );
define( 'WSHUR_DIR', plugin_dir_path( __FILE__ ) );
define( 'WSHUR_DIR_URL', plugin_dir_url( __DIR__ ) );
define( 'WSHUR_URL', plugins_url( '/', __FILE__ ) );
define( 'WSHUR_PATH', plugin_basename( __FILE__ ) );



/**
 * Main WP Services Hub User Registration Class
 */
class WP_Services_Hub_User_Registration {
    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', array($this, 'init'));

        // Activation and deactivation hooks
        register_activation_hook(__FILE__, array($this, 'wshur_activate'));
        register_deactivation_hook(__FILE__, array($this, 'wshur_deactivate'));
    }

    /**
     * Initialize the plugin
     */
    public function init() {
        load_plugin_textdomain('wshur', false, dirname(WSHUR_PATH) . '/languages');

        
        /**
         * Enable user registration
            */
        update_option('users_can_register', 1);

        // Set default user role (optional, default is 'subscriber')
        // update_option('default_role', 'subscriber');

        /**
         * Include required files
         */
        $this->wshur_includes();

        
        /**
         * Run plugin
         */
        $this->wshur_user_registration_run();
    }

    /**
     * The code that runs during plugin activator.
     * This action is documented in inc/wshur-class-activator.php
     */
    public function wshur_activate() {
        require_once WSHUR_DIR . 'inc/wshur-class-activator.php';
        WSHUR_Activator::activate();
    }

    /**
     * The code that runs during plugin deactivation.
     * This action is documented in inc/wshur-class-deactivator.php
     */
    public function wshur_deactivate() {
        require_once WSHUR_DIR . 'inc/wshur-class-deactivator.php';
        WSHUR_Deactivator::deactivate();
    }

    /**
     * Include required files
     */
    public function wshur_includes() {
        require_once WSHUR_DIR . 'inc/wshur-functions-init.php';
    }

    /**
     * Run the plugin
     */
    public function wshur_user_registration_run(){
        // Initialize the plugin
        $plugin = new WSHUR_functions_init();
        $plugin->run();
    }
}

// Initialize the plugin
new WP_Services_Hub_User_Registration();