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



// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}