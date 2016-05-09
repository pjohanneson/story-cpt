<?php
/**
 * Class file for the Story custom post type.
 */
 
/**
 * The Fiction class.
 */
class PJ_Story {

	const POST_TYPE = 'pj_story';
	const PREFIX = '_pjs_';

	/**
	 * Constructor for the Fiction class
	 */
	function __construct() {
		add_action( 'init', array( $this, 'post_type' ) );
		add_filter( 'cmb_meta_boxes', array( $this, 'metaboxes' ) );
		add_shortcode( 'fiction', array( $this, 'fiction_shortcode_cb' ) );
		add_filter( 'template_include', array( $this, 'template_selector' ) );

	}

	/**
	 * Register the 'fiction' post type
	 */
	static public function post_type() {

		$labels = array(
			'name'                => _x( 'Stories', 'Post Type General Name', 'pj-story' ),
			'singular_name'       => _x( 'Story', 'Post Type Singular Name', 'pj-story' ),
			'menu_name'           => __( 'Story', 'pj-story' ),
			'name_admin_bar'      => __( 'Story', 'pj-story' ),
			'parent_item_colon'   => __( 'Parent Item:', 'pj-story' ),
			'all_items'           => __( 'All Stories', 'pj-story' ),
			'add_new_item'        => __( 'Add New Story', 'pj-story' ),
			'add_new'             => __( 'Add New', 'pj-story' ),
			'new_item'            => __( 'New Story', 'pj-story' ),
			'edit_item'           => __( 'Edit Story', 'pj-story' ),
			'update_item'         => __( 'Update Story', 'pj-story' ),
			'view_item'           => __( 'View Story', 'pj-story' ),
			'search_items'        => __( 'Search Stories', 'pj-story' ),
			'not_found'           => __( 'Not found', 'pj-story' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'pj-story' ),
		);
		$rewrite = array(
			'slug'                => 'story',
			'with_front'          => true,
			'pages'               => true,
			'feeds'               => true,
		);
		$args = array(
			'label'               => __( self::POST_TYPE, 'pj-story' ),
			'description'         => __( 'Stories', 'pj-story' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'trackbacks', 'revisions', 'page-attributes', ),
			'taxonomies'          => array( 'story_tag', 'story_category' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 20,
			'menu_icon'           => 'dashicons-edit',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => 'stories',
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'rewrite'             => $rewrite,
			'capability_type'     => 'post',
		);
		register_post_type( self::POST_TYPE, $args );

	}

	/**
	 * Generate the meta boxes for the 'fiction' post type
	 * @param array $metaboxes Array of extant metaboxes
	 * @return array The filtered metaboxes
	 */
	public static function metaboxes( $metaboxes = array() ) {

		// reviews
		// group:
		// 	date
		// 	reviewer
		// 	url
		// 	text
		$review_fields = array(
			array(
				'id' => self::PREFIX . 'review_date',
				'name' => __( 'Date', 'pj-story' ),
				'type' => 'date_unix',
				),
			array(
				'id' => self::PREFIX . 'reviewer',
				'name' => __( 'Reviewer', 'pj-story' ),
				'type' => 'text',
				'cols' => 6, 
				),
			array(
				'id' => self::PREFIX . 'reviewer_url',
				'name' => __( 'URL', 'pj-story' ),
				'type' => 'url',
				'cols' => 6,
				),
			array(
				'id' => self::PREFIX . 'review',
				'name' => __( 'Review', 'pj-story' ),
				'type' => 'wysiwyg',
				),
			);
		$review = array(
			'id' => self::PREFIX . 'review_group',
			'name' => __( 'Reviews', 'pj-story' ),
			'type' => 'group',
			'repeatable' => true,
			'fields' => $review_fields,
			);

		// publication details
		//	date
		//	publication
		//	URL

		$publication_fields = array(
			array(
				'id' => self::PREFIX . 'publication',
				'name' => __( 'Publication', 'pj-story' ),
				'type' => 'text',
				'cols' => 6,
				),
			array(
				'id' => self::PREFIX . 'publication_url',
				'name' => __( 'URL', 'pj-story' ),
				'type' => 'url',
				'cols' => 6,
				),
			array(
				'id' => self::PREFIX . 'publication_date',
				'name' => __( 'Publication Date', 'pj-story' ),
				'type' => 'date_unix',
				),
			);
		$publication = array(
			'id' => self::PREFIX . 'publication_group',
			'name' => __( 'Publication Details', 'pj-story' ),
			'type' => 'group',
			'fields' => $publication_fields,
			);

		// Meta box area
		$story_boxes = array(
			'id' => self::PREFIX . 'story_boxes',
			'title' => __( 'Story Details', 'pj-story' ),
			'pages' => array( self::POST_TYPE ),
			'fields' =>  array( 
				$review, 
				$publication, 
				), 
			'priority' => 'high',
			);

		$metaboxes[] = $story_boxes;

		return $metaboxes;
	}

	public static function fiction_shortcode_cb( $_atts ) {
		$defaults = array(
			'front_page' => is_front_page(),
			'max' => 5,
		);
		$atts = shortcode_atts( $defaults, $_atts );

		$content = '';

		$args = array(
			'post_type' => self::POST_TYPE,
			'posts_per_page' => $atts['max'],
			'post_status' => 'publish',
			'perm' => 'readable',
			'orderby' => 'post_title',
			'order' => 'ASC',
		);
		$stories = new WP_Query( $args );
		if( $stories->have_posts() ) {
			while( $stories->have_posts() ) {
				$stories->the_post();
				$content .= '<a href="' . get_the_permalink() . '">' . get_the_title() . '</a><br />' . PHP_EOL;
			}	
		}
		wp_reset_postdata();

		if( 0 < strlen( $content ) ) {
			$content = '<h2><a href="/fiction/">Fiction</a></h2>' . PHP_EOL . '<p>' . PHP_EOL . $content . '</p>' . PHP_EOL;
		}

		return $content;
	}
	
	function template_selector( $template ) {
		if( is_singular( self::POST_TYPE ) ) {
			$template = plugin_dir_path( __FILE__ ) . 'template/single-fiction.php';
		}
		return $template;
	}
}