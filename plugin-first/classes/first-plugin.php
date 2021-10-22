<?php

class First_Plugin {

	/**
	 * Default constructor
	 */
	public function __construct() {
		add_filter( 'the_content', array( $this, 'my_new_content_filter' ) );
		add_filter( 'wp_nav_menu_items', array( $this, 'my_new_navbar' ) );
		add_action( 'edit_user_profile_update', array( $this, 'send_email_on_update' ) );
		add_filter( 'the_content', array( $this, 'add_categories_to_single_student' ) );
	}


	/**
	 * Appends a red div element to all posts of type student.
	 */
	public function my_new_content_filter( $content ) {

		if ( is_singular( 'student' ) ) {
			if ( 1 == get_option( 'activate_filters' ) ) {

				$prepend = '<h3> Onboarding Filter: </h3>';
				$append  = "<p style='text-decoration: underline;'> by Antonio </p>";

				$content_with_div = substr_replace( $content, '<div style="background-color: red;">test test</div>', strpos( $content, '</p>' ), 0 );

				return $prepend . $content_with_div . $append;
			}
		}

		return $content;

	}


	public function my_new_navbar( $items ) {
		if ( is_user_logged_in() ) {
			$settings_item = '<li> <a href="http://localhost/wordpress/wp-admin/profile.php"> Profile settings </a> </li>';
			$items         = $items . $settings_item;
		}

		return $items;
	}

	public function send_email_on_update() {
		global $current_user;
		get_currentuserinfo();

		$message = $current_user->display_name . ' just edited their profile.';

		$email = get_bloginfo( 'admin_email' );

		wp_mail( $email, 'User Updated Details', $message );
	}

	public function add_categories_to_single_student( $content ) {
		global $post;

		if ( is_single() ) {
			if ( $post->post_type == 'student' ) {
				$categories = get_the_category( $post->ID );

				echo '<ul>';
				foreach ( $categories as $category ) {
					echo '<li>' . esc_html( $category->name ) . '</li>';
				}
				echo '</ul>';

			}
		}
		return $content;
	}

}
