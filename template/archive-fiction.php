<?php
/**
 * Template Name: Single Story
 *
 * @package pj_story
 */

get_header();
if ( have_posts() ) {
	echo '<div class="page-content">' . PHP_EOL;
	while ( have_posts() ) {
		the_post();
		the_title( '<a href="' . get_permalink() . '">', '</a><br />' . PHP_EOL );
	}
	echo '</div> <!-- .page-content -->' . PHP_EOL;
}
get_sidebar();
get_footer();
