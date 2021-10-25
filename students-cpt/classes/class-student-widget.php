<?php
/**
 * Students Dislpaying Widget
 */

/**
 * A custom widget class that extends the classic WP_Widget
 */
class Student_Widget extends WP_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct(
			'student_widget',
			__( 'Students Widget', 'student_widget_domain' ),
			array( 'description' => __( 'Sample widget based on WPBeginner Tutorial', 'student_widget_domain' ) )
		);
	}

	/**
	 * Adding widget elements.
	 *
	 * Output cannot be scaped, because it is HTML.
	 */
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $args['before_widget'];

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		$html_form = file_get_contents( plugins_url( '..\assets\Templates\widget_form.html', __FILE__ ) );

		echo $html_form;

		echo $args['after_widget'];
	}

	/**
	 * Creates widget backend.
	 */
	public function form( $instance ) {
		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		} else {
			$title = __( 'New title', 'student_widget_domain' );
		}
		?>
		<p>
			<label for="<?php echo esc_html( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo esc_html( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_html( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php
	}

	/**
	 * Overrides the update function.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance          = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
		return $instance;
	}

}
