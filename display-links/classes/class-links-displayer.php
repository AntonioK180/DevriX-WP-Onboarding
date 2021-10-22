<?php

/**
 * All of the plugin link displaying logic is here.
 */
class Links_Displayer {

	/**
	 * Constructor
	 */
	public function __construct() {
		$transient_duration = 3600;
		add_action( 'admin_enqueue_scripts', array( $this, 'load_javascript' ) );
		add_action( 'wp_ajax_display_input_link', array( $this, 'display_input_link' ) );
		add_action( 'wp_ajax_display_cached_html', array( $this, 'display_cached_html' ) );
	}

	/**
	 * Adds JavaScript to admin page
	 */
	public function load_javascript() {
		wp_register_script( 'first_separate_js', plugins_url( '..\assets\js\first_separate_js.js', __FILE__ ), array( 'jquery' ), 1, true );

		wp_enqueue_script( 'first_separate_js' );
	}

	/**
	 * Retrieves the HTML body from the provided URL and caches it
	 */
	public function display_input_link() {
		$html_body = wp_remote_retrieve_body( wp_remote_get( sanitize_text_field( $_POST['link_value'] ) ) );

		if ( null !== ( wp_unslash( $_POST['cache_duration'] ) ) ) {
			$transient_duration = $_POST['cache_duration'];
		}

		set_transient( 'cached_html', $html_body, $transient_duration );

		echo $html_body;

		wp_die();
	}

	/**
	 * Displays HTML if one is cached, otherwise displays nothing
	 */
	public function display_cached_html() {
		$cached_html_body = get_transient( 'cached_html' );

		if ( false === $cached_html_body ) {
			echo '';
		}

		echo $cached_html_body;

		wp_die();
	}

}
