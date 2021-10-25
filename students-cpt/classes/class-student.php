<?php
/**
 * The Student CPT is described here.
 */

if ( ! defined( 'S_CPT_URL' ) ) {
	define( 'S_CPT_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * Describes the Student Custom Post Type.
 */
class Student_CPT {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_student_type' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_student_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_meta_boxes' ) );
		add_filter( 'manage_student_posts_columns', array( $this, 'student_add_default_column' ) );
		add_action( 'manage_student_posts_custom_column', array( $this, 'student_columns_content' ), 10, 2 );
		add_shortcode( 'student', array( $this, 'display_student_shortcode' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'student_cpt_load_javascript' ) );
		add_action( 'wp_ajax_toggle_student_activated', array( $this, 'toggle_student_activated' ) );
		add_action( 'widgets_init', array( $this, 'student_load_widget' ) );
		// add_filter( 'page_template', array( $this, 'add_student_archive_page' ) );  This breaks the special page template .
	}

	/**
	 * Describes the student CPT.
	 */
	public function register_student_type() {
		$labels = array(
			'name'               => _x( 'Students', 'Post type general name', 'student' ),
			'singular_name'      => _x( 'Student', 'Post type singular name', 'student' ),
			'menu_name'          => _x( 'Students', 'Admin Menu text', 'student' ),
			'name_admin_bar'     => _x( 'Student', 'Add New on Toolbar', 'student' ),
			'add_new'            => __( 'Add New', 'student' ),
			'add_new_item'       => __( 'Add New Student', 'student' ),
			'new_item'           => __( 'New Student', 'student' ),
			'edit_item'          => __( 'Edit Student', 'student' ),
			'view_item'          => __( 'View Student', 'student' ),
			'all_items'          => __( 'All Students', 'student' ),
			'search_items'       => __( 'Search Students', 'student' ),
			'parent_item_colon'  => __( 'Parent Students:', 'student' ),
			'not_found'          => __( 'No stundets found.', 'student' ),
			'not_found_in_trash' => __( 'No students found in Trash.', 'student' ),
		);

		$args = array(
			'label'             => 'student',
			'labels'            => $labels,
			'description'       => 'A student from some school.',
			'public'            => true,
			'menu_position'     => 3,
			'supports'          => array( 'title', 'thumbnail', 'excerpt', 'category', 'editor' ),
			'has_archive'       => true,
			'show_in_admin_bar' => true,
			'show_in_rest'      => true,
			'taxonomies'        => array( 'category' ),
		);

		register_post_type( 'student', $args );
	}

	/**
	 * The callback which displays the input box for the city meta.
	 *
	 * @param student $post is provided, because the ID is needed for the ge_post_meta().
	 */
	public function city_meta_box_html( $post ) {
		$value = get_post_meta( $post->ID, 'student_city', true );

		?>
			<label for="city"> City: </label>
			<input type="text" name="city" value=" <?php echo ( esc_html( isset( $value ) ? $value : '' ) ); ?>">
		<?php
	}

	/**
	 * The callback which displays the input box for the address meta.
	 *
	 *  @param student $post is provided, because the ID is needed for the ge_post_meta().
	 */
	public function address_meta_box_html( $post ) {
		$value = get_post_meta( $post->ID, 'student_address', true );
		?>
			<label for="address"> Address: </label>
			<input type="text" name="address" style="width:60%;" value="<?php echo ( esc_html( isset( $value ) ? $value : '' ) ); ?>">
		<?php
	}

	/**
	 * The callback which displays the input box for the city meta.
	 *
	 *  @param student $post is provided, because the ID is needed for the ge_post_meta().
	 */
	public function birthdate_meta_box_html( $post ) {
		$value = get_post_meta( $post->ID, 'student_birthdate', true );
		?>
			<label for="birthdate"> Birth Date: </label>
			<input type="date" name="birthdate" style="width:60%;" value="<?php echo ( esc_html( isset( $value ) ? $value : '' ) ); ?>">
		<?php
	}

	/**
	 * The callback which displays the input box for the student grade meta.
	 *
	 *  @param student $post is provided, because the ID is needed for the ge_post_meta().
	 */
	public function student_grade_meta_box_html( $post ) {
		$value = get_post_meta( $post->ID, 'student_grade', true );
		?>
			<label for="grade">Grade: </label>
			<select name="grade">
				<option value="">Select something...</option>
				<option value="8"  <?php selected( $value, '8' ); ?>>  8</option>
				<option value="9"  <?php selected( $value, '9' ); ?>>  9</option>
				<option value="10" <?php selected( $value, '10' ); ?>>10</option>
				<option value="11" <?php selected( $value, '11' ); ?>>11</option>
				<option value="12" <?php selected( $value, '12' ); ?>>12</option>
			</select>
		<?php
	}


	/**
	 * Adds ALL student meta boxes.
	 * The functions that output the HTML fields, can become anonymous functions.
	 */
	public function add_student_meta_boxes() {
		add_meta_box(
			'student_city',
			'City',
			array( $this, 'city_meta_box_html' ),
			'student'
		);

		add_meta_box(
			'student_address',
			'Address',
			array( $this, 'address_meta_box_html' ),
			'student'
		);

		add_meta_box(
			'student_birthdate',
			'Birth Date',
			array( $this, 'birthdate_meta_box_html' ),
			'student'
		);

		add_meta_box(
			'student_grade',
			'Grade',
			array( $this, 'student_grade_meta_box_html' ),
			'student'
		);
	}

	/**
	 * Describes how the meta data from the meta boxes will be saved.
	 *
	 * @param number $post_id specifies the ID of the post that is being saved.
	 */
	public function save_meta_boxes( $post_id ) {
		if ( array_key_exists( 'city', $_POST ) ) {
			update_post_meta(
				$post_id,
				'student_city',
				sanitize_text_field( $_POST['city'] ),
			);
		}

		if ( array_key_exists( 'address', $_POST ) ) {
			update_post_meta(
				$post_id,
				'student_address',
				sanitize_text_field( $_POST['address'] ),
			);
		}

		if ( array_key_exists( 'birthdate', $_POST ) ) {
			update_post_meta(
				$post_id,
				'student_birthdate',
				sanitize_text_field( $_POST['birthdate'] ),
			);
		}

		if ( array_key_exists( 'grade', $_POST ) ) {
			update_post_meta(
				$post_id,
				'student_grade',
				sanitize_text_field( $_POST['grade'] ),
			);
		}
	}

	/**
	 * Adds the Active checkbox to the student CPT admin panel.
	 *
	 * @param array $defaults an array containing the default admin panel columns.
	 */
	public function student_add_default_column( $defaults ) {
		$defaults['active'] = 'Active';

		return $defaults;
	}

	/**
	 * The callback for the Active checkbox.
	 */
	public function student_columns_content( $column_name, $post_ID ) {
		if ( 'active' == $column_name ) {
			if ( get_post_meta( $post_ID, 'student_active', true ) == 'active' ) {
				echo '<input type="checkbox" name="active_student_checkbox" class="active_student_checkbox" id=" ' . $post_ID . '" checked>';
			} else {
				echo '<input type="checkbox" name="active_student_checkbox" class="active_student_checkbox" id=" ' . $post_ID . '"  >';
			}
		}
	}

	/**
	 * Loads scripts
	 */
	public function student_cpt_load_javascript() {
		wp_register_script( 'student_ajax_calls', S_CPT_URL . '../assets/js/student_ajax_calls.js', array( 'jquery' ), 1.0, true );

		wp_enqueue_script( 'student_ajax_calls' );
	}

	/**
	 * Student CPT shortcode.
	 *
	 * @param array $atts practically accepts only one attribute and it is a Student's ID.
	 */
	public function display_student_shortcode( $atts ) {
		$student_display = '';

		$student = shortcode_atts(
			array(
				'student_id' => 10,
			),
			$atts
		);

		$query_args = array(
			'post_type' => 'student',
			'p'         => $student['student_id'],
		);

		$get_single = new WP_Query( $query_args );

		if ( $get_single->have_posts() ) {
			while ( $get_single->have_posts() ) {
				$get_single->the_post();

				$student_display = '<div style="border: 2px solid black;" class="' . get_post_meta( get_the_ID(), 'student_active', true ) . '">';

				$student_display = $student_display . '<h2>' . get_the_title() . '</h2>';

				$student_display = $student_display . '<h3> Grade: ' . get_post_meta( get_the_ID(), $key = 'student_grade', true ) . '</h3>';
				$student_display = $student_display . '<h3> Status: ' . get_post_meta( get_the_ID(), $key = 'student_active', true ) . '</h3>';
			}
		} else {
			$student_display = $student_display . '<h2> Students with the specified ID were not found. </h2>';
		}

		return $student_display . '</div>';
	}

	/**
	 * Changes the student CPT meta from active to inactive and vice versa
	 */
	public function toggle_student_activated() {
		if ( isset( $_POST['student_id'] ) ) {
			$student_id = $_POST['student_id'];
		}

		$is_active = get_post_meta( $student_id, 'student_active', true );

		if ( 'active' == $is_active ) {
			update_post_meta( $student_id, 'student_active', 'no' );
		} else {
			update_post_meta( $student_id, 'student_active', 'active' );
		}

		wp_die();
	}

	/**
	 * Registers the student widget
	 */
	public function student_load_widget() {
		register_widget( 'student_widget' );
	}

	/**
	 * Assigns a template for the students archive page (NOT HOOKED)
	 *
	 * Works, but breaks the custom template for page-special, that I have in my theme.
	 */
	public function add_student_archive_page() {
		if ( is_page( 'students-archives' ) ) {
			$page_template = S_CPT_DIR . '../assets/Templates/archive-student.php';
			return $page_template;
		}
	}

}
