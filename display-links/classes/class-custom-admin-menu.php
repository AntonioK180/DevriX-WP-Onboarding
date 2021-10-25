<?php
/**
 * Here is described the Admin Menu which is appended to the standard WordPress amdin sidebar.
 */

if ( ! defined( 'HOME_URL' ) ) {
	define( 'HOME_URL', home_url() );
}

if ( ! defined( 'DL_ASSETS' ) ) {
	define( 'DL_ASSETS', WP_PLUGIN_DIR . '/display-links/assets' );
}

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

		$html_form = file_get_contents( DL_ASSETS . '/templates/links_input_form.html' );

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
