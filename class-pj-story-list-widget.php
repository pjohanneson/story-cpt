<?php
/**
 * Story list widget class.
 *
 * @package pj_story
 * @subpackage widget
 */

/**
 * Widget class.
 *
 * @since 1.0.0
 */
class PJ_Story_List_Widget extends WP_Widget {

	/**
	 * Class constructor.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	function __construct() {
		parent::__construct(
			// Base ID of your widget.
			'pj_story_list_widget',
			// Widget name (this will appear in the UI).
			__( 'Story List', 'pj-story' ),
			// Widget description.
			array( 'description' => __( 'List of short fiction', 'pj-story' ) )
		);
	}

	/**
	 * Output the widget content.
	 *
	 * @param array $args Array of arguments passed to the function.
	 * @param array $instance Instance(s) of the widget (I guess?).
	 * @return void
	 * @since 1.0.0
	 */
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $args['before_widget'];
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		// Gets a random story.
		$post_args = array(
			'post_type' => PJ_Story::POST_TYPE,
			'posts_per_page' => 1,
			'orderby' => 'rand',
			'has_password' => false,
		);
		$random_story = get_posts( $post_args );
		$story = $random_story[0];
		echo '<p>';
		echo '<strong><a href="' . get_permalink( $story->ID ) . '">' . $story->post_title . '</a></strong>&mdash;';
		echo $story->post_excerpt;
		echo ' <a href="' . get_permalink( $story ) . '">Read more &raquo;</a>';
		echo '</p>';


		echo '<p><a href="' . get_post_type_archive_link( PJ_Story::POST_TYPE ) . '">All Stories</a></p>';

		echo $args['after_widget'];
	}
			
	// Widget Backend
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else {
			$title = __( 'Stories', 'pj-story' );
		}
		// Widget admin form
		?>
	<p>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
	</p>
	<?php
	}
		
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}

} // End of the class.

/**
 * Registers the widget and the widget area on the front page.
 *
 * @return void
 */
function pj_story_load_widget() {
	register_widget( 'PJ_Story_List_Widget' );
	$args = array(
		'name'          => __( 'Front Page widgets', 'pj-story' ),
		'id'            => 'front_page_widgets',
		'before_widget' => '<div class="front-page-widget-area">',
		'after_widget'  => '</div> <!-- .front-page-widget-area -->',
		'before_title'  => '<h2>',
		'after_title'   => '</h2>',
	);
	register_sidebar( $args );
}
add_action( 'widgets_init', 'pj_story_load_widget' );
