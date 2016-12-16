<?php
/**
 * Plugin loader file.
 *
 * @package pj_story
 */

/*
Plugin Name: Story Custom Post Type
Plugin URI: http://patj.ca/wp/plugins/story-cpt
Description: Generates the "Fiction" CPT and the assorted metaboxes & tools to go with it
Version: 1.0.0
Author: Patrick Johanneson
Author URI: http://patrickjohanneson.com/
License: GPL v2 or later
*/

/**
 * Copyright (c) 2015 Patrick Johanneson. All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * **********************************************************************
 */

// Initializes the Custom Meta Box library, if necessary.
if ( ! function_exists( 'cmb_init' ) ) {
	require_once( 'lib/cmb/custom-meta-boxes.php' );
}

// Loads the required files.
require_once( 'class-pj-story.php' );
require_once( 'class-pj-taxonomies.php' );
require_once( 'class-pj-story-list-widget.php' );

register_activation_hook( __FILE__, function() {
	PJ_Story::post_type();
	flush_rewrite_rules();
} );

register_deactivation_hook( __FILE__, function() {
	flush_rewrite_rules();
} );

new PJ_Story();
