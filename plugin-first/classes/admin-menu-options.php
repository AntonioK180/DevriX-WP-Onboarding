<?php

/**
 * A class for the admin menu options
 */
class Admin_Menu_Options {

	/**
	 *  Default constructor of the admin menu class
	 */
	public function __construct() {
		register_activation_hook( __FILE__, array( $this, 'add_custom_options' ) );
		add_action( 'admin_menu', array( $this, 'my_plugin_admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'plugin_first_load_javascript' ) );
		add_action( 'wp_ajax_add_filters_action', array( $this, 'add_filters_action' ) );

		$f_enabled = 0;
	}

	/**
	 * Loads the javascript
	 */
	public function plugin_first_load_javascript() {
		wp_register_script( 'my_first_ajax_calls', plugins_url( '..\assets\js\enable_filters_ajax.js', __FILE__ ), array( 'jquery' ), false, true );

		wp_enqueue_script( 'my_first_ajax_calls' );
	}

	/**
	 * Adds an option
	 */
	public function add_custom_options() {
		add_option( 'activate_filters', 0 );
	}

	public function my_plugin_admin_options() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		if ( 1 == get_option( 'activate_filters' ) ) {
			echo '<input type="checkbox" id="filters-enabled" name="filters-enabled" value="yes" checked>';
		} else {
			echo '<input type="checkbox" id="filters-enabled" name="filters-enabled" value="yes">';
		}

		echo '<label for="filters-enabled">Filters Enabled</label>';
		echo '</div>';
	}

	/**
	 * Add a custom menu page
	 */
	public function my_plugin_admin_menu() {
		add_menu_page(
			'Onboarding Plugin',
			'Onboarding Plugin',
			'manage_options',
			'myplugin/onboarding-plugin-admin.php',
			array( $this, 'my_plugin_admin_options' ),
		);
	}

	/**
	 * This is the function that the AJAX calls
	 */
	public function add_filters_action() {
		if ( isset( $_POST['filters_enabled'] ) ) {
			$activate_filters = $_POST['filters_enabled'];
		}

		$is_activated = get_option( 'activate_filters' );

		if ( 1 == $is_activated ) {
			update_option( 'activate_filters', 0 );
		} else {
			update_option( 'activate_filters', 1 );
		}

		wp_die();
	}

}
