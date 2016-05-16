<?php 

// Creating the widget 
class PJ_Story_List_Widget extends WP_Widget {

function __construct() {
	parent::__construct(
	// Base ID of your widget
	'pj_story_list_widget', 

	// Widget name will appear in UI
	__('Story List', 'pj-story'), 

	// Widget description
	array( 'description' => __( 'List of short fiction', 'pj-story' ), ) 
	);
}

// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
	$title = apply_filters( 'widget_title', $instance['title'] );
	// before and after widget arguments are defined by themes
	echo $args['before_widget'];
	if ( ! empty( $title ) ) {
		echo $args['before_title'] . $title . $args['after_title'];
	}

	// Get a random story (refresh daily)
	$s_id = get_transient( PJ_Story::PREFIX . 'random_story_id' );
	$random_stories = false;
	if( ! $s_id ) {
		$_args = array(
			'post_type' => PJ_Story::POST_TYPE,
			'posts_per_page' => 1,
			'orderby' => 'rand',
		);
		$random_stories = get_posts( $_args );
		$s_id = $random_stories[0]->ID;
		set_transient( PJ_Story::PREFIX . 'random_story_id', $s_id, DAY_IN_SECONDS );
	}
	$random_stories = false;
	if( ! $random_stories ) {
		$_args = array(
			'post_type' => PJ_Story::POST_TYPE,
			'posts_per_page' => 1,
			'include' => $s_id,
		);
		$random_stories = get_posts( $_args );
	}
	if( $random_stories && is_array( $random_stories ) ) {
		$r = $random_stories[0];
		echo( "<h3>Today's featured story</h3>" . PHP_EOL );
		echo( '<p><strong><a href="' . get_permalink( $r->ID ) . '">' . get_the_title( $r->ID ) . '</a></strong>&mdash;' . wp_trim_words( $r->post_content, 20, '&mdash;<a href="' . get_permalink( $r->ID ) . '">Read more &raquo;</a>' ) . PHP_EOL );
	}
	// and the list of stories
	$_args = array(
		'post_type' => PJ_Story::POST_TYPE,
		'posts_per_page' => 100,
		'exclude' => absint( $s_id ),
		'orderby' => 'post_title',
		'order' => 'ASC',
	);
	$stories = get_posts( $_args );
	if( $stories ) {
		echo( "<h3>More Stories</h3>" . PHP_EOL );
		echo( '<p>' . PHP_EOL . '<ul>' . PHP_EOL );
		foreach( $stories as $s ) {
			if( 0 == strlen( $s->post_password ) ) {
				echo( '<li><a href="' . get_permalink( $s->ID ) . '">' . $s->post_title . '</a></li>' . PHP_EOL );
			}
		}
		echo( '</ul>' . PHP_EOL . '</p>' . PHP_EOL );
	}


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

} // Class PJ_Story_List_Widget ends here

// Register and load the widget
function pj_story_load_widget() {
	register_widget( 'pj_story_list_widget' );
}
add_action( 'widgets_init', 'pj_story_load_widget' );
