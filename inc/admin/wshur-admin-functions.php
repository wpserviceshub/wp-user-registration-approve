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


class WSHUR_admin_functions {

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
		
		add_action( 'admin_menu', array($this, 'wshur_plugins_admin_menu_init') );
		add_shortcode( 'wshur_registration_form', array( $this, 'wshur_registration_form_init' ) );
		add_action( 'init', array( $this, 'wshur_registration_process_form' ) );
		add_filter( 'authenticate', array( $this, 'wshur_check_user_active_status' ), 30, 3 );
    }

	// Add scripts and style for admin
    public function wshur_admin_enqueue_styles() {
	    wp_register_script('wshur-admin', WSHUR_DIR_URL . 'assets/js/wshur-admin.js', array( 'jquery' ), $this->plugin_version, false);
	    $siteurl = array(
	        'siteurl' 	=> admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('ajax-nonce')
	    );
	    wp_localize_script( 'wshur-admin', 'object_wshur', $siteurl );
	    // Enqueued script with localized data.
	    wp_enqueue_script( 'wshur-admin' );
    }

	// Add plugin menu for settings
	public function wshur_plugins_admin_menu_init(){
		add_menu_page(
			__( 'WSHUR Settings', 'wshur' ), // Page title
			__( 'WSHUR Settings', 'wshur' ),          // Menu title
			'manage_options',                                   // Capability
			'wshur-settings',                               // Menu slug
			array( $this, 'wshur_plugin_settings_page_html' ),                     // Callback function
			'dashicons-admin-generic',                          // Icon
			20                                                  // Position
		);
	}

	
    /**
     * WSHUR settings page
     */
	public function wshur_plugin_settings_page_html(){
		// Security check
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wshur-dashboard-settings-wrapper">
			<h1><?php esc_html_e( 'WSHUR Settings', 'wshur' ); ?></h1>
			<h3><?php esc_html_e( 'WSHUR Shortcodes', 'wshur' ); ?></h3>
			<p><?php esc_html_e( '[wshur_registration_form]', 'wshur' ); ?></p>
		</div>
		<?php
	}

	
    /**
     * WSHUR registration shortcode
     */
	public function wshur_registration_form_init(){
		ob_start();

        if ( isset( $_GET['reg_success'] ) && $_GET['reg_success'] === '1' ) {
            echo '<p style="color:green;">' . esc_html__( 'Registration successful! You can now log in.', 'wshur' ) . '</p>';
        }
        ?>
        <form method="post">
            <?php wp_nonce_field( 'wshur_reg_form_nonce', 'wshur_reg_form_nonce_field' ); ?>
            <p>
                <label><?php esc_html_e( 'First Name', 'wshur' ); ?></label><br>
                <input type="text" name="firstname" required>
            </p>

            <p>
                <label><?php esc_html_e( 'Last Name', 'wshur' ); ?></label><br>
                <input type="text" name="lastname" required>
            </p>

            <p>
                <label><?php esc_html_e( 'Username', 'wshur' ); ?></label><br>
                <input type="text" name="username" required>
            </p>

            <p>
                <label><?php esc_html_e( 'Email', 'wshur' ); ?></label><br>
                <input type="email" name="email" required>
            </p>

            <p>
                <label><?php esc_html_e( 'Password', 'wshur' ); ?></label><br>
                <input type="password" name="password" required>
            </p>

            <p>
                <input type="submit" name="wshur_reg_submit" value="<?php esc_attr_e( 'Register', 'wshur' ); ?>">
            </p>
        </form>
        <?php
        return ob_get_clean();
	}



	/**
     * WSHUR Process form submission
     */
    public function wshur_registration_process_form() {
        if ( isset( $_POST['wshur_reg_submit'] ) ) {

            // Verify nonce
            if ( ! isset( $_POST['wshur_reg_form_nonce_field'] ) || ! wp_verify_nonce( $_POST['wshur_reg_form_nonce_field'], 'wshur_reg_form_nonce' ) ) {
                wp_die( __( 'Security check failed', 'wshur' ) );
            }

            $fname = sanitize_text_field( $_POST['firstname'] );
            $lname = sanitize_text_field( $_POST['lastname'] );
            $username = sanitize_user( $_POST['username'] );
            $email    = sanitize_email( $_POST['email'] );
            $password = sanitize_text_field( $_POST['password'] );

            // Validate
            if ( username_exists( $username ) || email_exists( $email ) ) {
                add_action( 'wp_footer', function() {
                    echo '<p style="color:red;">' . esc_html__( 'Username or email already exists.', 'wshur' ) . '</p>';
                });
                return;
            }

            if ( empty( $fname ) || empty( $lname ) || empty( $username ) || empty( $email ) || empty( $password ) ) {
                add_action( 'wp_footer', function() {
                    echo '<p style="color:red;">' . esc_html__( 'All fields are required.', 'wshur' ) . '</p>';
                });
                return;
            }

            // Create user
            $user_id = wp_create_user( $username, $password, $email );

            if ( is_wp_error( $user_id ) ) {
                add_action( 'wp_footer', function() use ( $user_id ) {
                    echo '<p style="color:red;">' . esc_html( $user_id->get_error_message() ) . '</p>';
                });
                return;
            } else {
				wp_update_user([
					'ID' => $user_id,
					'first_name' => $fname,
					'last_name' => $lname,
					'display_name' => esc_html($fname.' '.$lname) // Optional: set display name
				]);
				add_user_meta( $user_id, 'wshur_user_active', 0);
			}

            // Redirect after success
            wp_safe_redirect( add_query_arg( 'reg_success', '1', wp_get_referer() ) );
            exit;
        }
    }



	/**
     * WSHUR authentication status of user login
     */
	public function wshur_check_user_active_status( $user, $username, $password ){
		if ( is_a( $user, 'WP_User' ) ) {
			
			// Allow admins always
			if ( in_array( 'administrator', (array) $user->roles, true ) ) {
				return $user;
			}

			// Check user_active meta (default inactive)
			$is_active = get_user_meta( $user->ID, 'wshur_user_active', true );

			if ( $is_active !== '1' ) {
				return new WP_Error(
					'inactive_account',
					__( '<strong>ERROR</strong>: Your account is not active. Please contact support.', 'wshur' )
				);
			}
		}
		return $user;
	}
	
}