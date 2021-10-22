<?php

/**
 * A custom widget class that extends the classic WP_Widget
 */
class Student_Widget extends WP_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct(
			// Base ID of your widget.
			'student_widget',
			// Widget name will appear in UI.
			__( 'Students Widget', 'student_widget_domain' ),
			// Widget description.
			array( 'description' => __( 'Sample widget based on WPBeginner Tutorial', 'student_widget_domain' ) )
		);
	}

	/**
	 * Adding widget elements
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
	 * Creating widget backend
	 */
	public function form( $instance ) {
		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		} else {
			$title = __( 'New title', 'student_widget_domain' );
		}
		// Widget admin form.
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php
	}

	/**
	 * Overriding the update function.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance          = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}

}
