<?php 

/**
 * Template Name: Single Story
 */

get_header();
if( have_posts() ) {
	echo( '<div class="page-content">' . PHP_EOL );
	while( have_posts() ) {
		the_post();
		the_title( '<h1>', '</h1>' . PHP_EOL );
		the_content();
	}
	echo( '</div> <!-- .page-content -->' . PHP_EOL );
}
get_sidebar();
get_footer();