<?php

get_header();

// if ( isset( $_POST['posts-per-page'] ) ) {
// $posts_per_students_page = $_POST['posts-per-page'];
// } else {
// $posts_per_students_page = 3;
// }

$posts_per_students_page = $_POST['posts-per-page'] ?? 3;

// if ( isset( $_POST['display_students'] ) ) {
// $display_students = $_POST['display_students'];
// } else {
// $display_students = 'active';
// }

$display_students = $_POST['display_students'] ?? 'active';

$args = array(
	'post_type'      => 'student',
	'posts_per_page' => $posts_per_students_page,
	'paged'          => $paged,
	'meta_key'       => 'student_active',
	'meta_value'     => $display_students,
);

$fetching_query = new WP_Query( $args );

if ( $fetching_query->have_posts() ) {

	while ( $fetching_query->have_posts() ) {

		?>

	<div style="text-align:center; border-style: solid; width:60%; margin-left: auto; margin-right: auto;">
  
		<?php

		$fetching_query->the_post();

		$active = get_post_meta( get_the_ID(), 'student_active', true );

		echo '<a href="' . get_the_permalink() . '"><h2>' . get_the_title() . '</h2></a>';

		the_excerpt();

		the_post_thumbnail(
			$size = array(
				150,
				150,
			)
		);

		?>

	</div>
	
		<?php

	}
} else {
	echo 'No Students found.';
}

?>

	<div style="text-align:center; margin-left: auto; margin-right: auto;">

<?php

echo paginate_links(
	array(
		'total'     => $fetching_query->max_num_pages,
		'format'    => '?paged=%#%',
		'show_all'  => false,
		'end_size'  => 2,
		'mid_size'  => 1,
		'prev_next' => true,
		'add_args'  => false,
	)
);

?>
	</div>

<?php

dynamic_sidebar( 'students_sidebar' );

get_footer();
