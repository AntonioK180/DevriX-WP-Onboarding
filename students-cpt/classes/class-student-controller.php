<?php
/**
 * The Student Controller class handles all custom REST API requests.
 */

/**
 * A Controller to manipulate the student CPT entries
 *
 * @var string $namespace is alywas /onboarding-plugin/v1.
 * @var string $resource_name is always students.
 */
class Student_Controller {

	private $namespace;
	private $resource_name;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->namespace     = '/onboarding-plugin/v1';
		$this->resource_name = 'students';

		add_action( 'rest_api_init', array( $this, 'register_endpoints' ) );
	}

	/**
	 * Handles the authentication, current authentication used is Basic Authentication (username, password)
	 */
	public function plugin_authenticate( $request ) {
		$permission_required = 'manage_options';

		$creds   = array();
		$headers = getallheaders();

		// Get username and password from the submitted headers.
		if ( array_key_exists( 'username', $headers ) && array_key_exists( 'password', $headers ) ) {
			$creds['user_login']    = $headers['username'];
			$creds['user_password'] = $headers['password'];
			$creds['remember']      = false;
			$user                   = wp_signon( $creds, false );  // Verify the user.

			if ( is_wp_error( $user ) ) {
				echo esc_html( $user->get_error_message() );
				return $user;
			}

			wp_set_current_user( $user->ID, $user->user_login );

			if ( ! current_user_can( $permission_required ) ) {
				return new WP_Error( 'rest_forbidden', 'You do not have permissions to view this data.', array( 'status' => 401 ) );
			}

			return 'ok';
		} else {
			return new WP_Error( 'invalid-method', 'You must specify a valid username and password.', array( 'status' => 400 /* Bad Request */ ) );
		}
	}

	/**
	 * Callback for getting all students
	 */
	public function get_all_student_data() {
		$posts = get_posts(
			array(
				'post_type'     => 'student',
				'post_per_page' => 3,
			)
		);

		return rest_ensure_response( $posts );
	}

	/**
	 * Callback for getting one student
	 */
	public function get_one_student_data( $request ) {
		$id = (int) $request['id'];

		$post = get_post( $id );

		if ( empty( $post ) ) {
			return rest_ensure_response( array() );
		}

		return rest_ensure_response( $post );
	}

	/**
	 * Callback for registering the update route.
	 */
	public function edit_student( $request ) {
		$post_id = wp_update_post( json_decode( $request->get_body() ) );

		return rest_ensure_response( $post_id );
	}

	/**
	 * Callback for adding a new student.
	 */
	public function add_new_student( $request ) {
		$post_id = wp_insert_post( json_decode( $request->get_body() ) );

		return rest_ensure_response( $post_id );
	}

	/**
	 * Callback for deleting a student by ID.
	 */
	public function delete_student_by_id( $request ) {
		$post = wp_delete_post( $request['id'] );

		return rest_ensure_response( $post );
	}

	/**
	 * Registers the custom endpoints for:
	 * - get all
	 * - get one
	 * - update one by ID
	 * - add new
	 * - delete one
	 */
	public function register_endpoints() {
		register_rest_route(
			$this->namespace,
			'/' . $this->resource_name,
			array(
				'methods'  => 'GET',
				'callback' => array( $this, 'get_all_student_data' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->resource_name . '/(?P<id>[\d]+)',
			array(
				'methods'  => 'GET',
				'callback' => array( $this, 'get_one_student_data' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->resource_name,
			array(
				'methods'             => 'PUT',
				'callback'            => array( $this, 'edit_student' ),
				'permission_callback' => array( $this, 'plugin_authenticate' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->resource_name,
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'add_new_student' ),
				'permission_callback' => array( $this, 'plugin_authenticate' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->resource_name . '/(?P<id>[\d]+)',
			array(
				'methods'             => 'DELETE',
				'callback'            => array( $this, 'delete_student_by_id' ),
				'permission_callback' => array( $this, 'plugin_authenticate' ),
			)
		);
	}
}
