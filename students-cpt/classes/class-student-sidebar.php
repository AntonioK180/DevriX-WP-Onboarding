<?php

/**
 * The students sidebar class - initializes the sidebar
 */
class Student_Sidebar {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'widgets_init', array( $this, 'students_sidebar' ) );
	}

	/**
	 * Registers the students custom sidebar
	 */
	public function students_sidebar() {
		register_sidebar(
			array(
				'id'          => 'students_sidebar',
				'name'        => __( 'Students Sidebar' ),
				'description' => __( 'A sidebar, that will display the students widget.' ),
			)
		);
	}

}
