<?php
/**
 * Class file for the Story custom post type.
 */
 
/**
 * The Fiction class.
 */
class PJ_Story {

	const POST_TYPE = 'pj_story';

	/**
	 * Constructor for the Fiction class
	 */
	function __construct() {
		add_action( 'init', array( $this, 'post_type' ) );

		add_filter( 'cmb_meta_boxes', array( $this, 'metaboxes' ) );
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
			'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'trackbacks', 'revisions', 'custom-fields', 'page-attributes', ),
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
	 */
	function metaboxes( $metaboxes = array() ) {

		return $metaboxes;
	}
}