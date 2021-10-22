<?php

/**
 * Class for the custom admin menu which displays the results of the URL provided
 */
class Custom_Admin_Menu {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'sanitized_links_admin_menu' ) );
	}

	/**
	 * Displays the form through wchih URLs are submitted
	 */
	public function sanitized_links_options() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html( 'You do not have sufficient permissions to access this page.' ) );
		}

		$html_form = file_get_contents( plugins_url( '..\assets\Templates\links_input_form.html', __FILE__ ) );

		echo $html_form;
	}

	/**
	 * Adds the admin menu page to the sidebar
	 */
	public function sanitized_links_admin_menu() {
		add_menu_page(
			'Display Links Plugin',
			'Display Links',
			'manage_options',
			'myplugin/sanitized-links-admin.php',
			array( $this, 'sanitized_links_options' ),
		);
	}

}
