<?php
/**
 * Fired during plugin deactivation
 *
 * @link       https://wordpress.org/plugins/wpservicehub-user-registration
 * @since      1.0.0
 * @package    wpservicehub-user-registration
 * @subpackage wpservicehub-user-registration/inc
 */

/**
 * Fired during plugin deactivation.
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

 class WSHUR_Deactivator {
    /**
     * The code that runs during plugin deactivation.
     */
    public static function deactivate() {
    }
}