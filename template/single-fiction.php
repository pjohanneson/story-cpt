<?php 

/**
 * Template Name: Single Story
 */

get_header();
echo '<div id="primary" class="content-area">';
echo '<main id="main" class="site-main" role="main">';

if( have_posts() ) {
	echo '<div class="page-content">' . PHP_EOL;
	echo '<article>' . PHP_EOL;
	while( have_posts() ) {
		the_post();
		the_title( '<h1>', '</h1>' . PHP_EOL );
		if ( function_exists( 'pjs_get_publication_data' ) ) {
			echo pjs_get_publication_data( get_the_ID () );
		}
		the_content();
	}
	echo '</article>' . PHP_EOL;
	echo '</div> <!-- .page-content -->' . PHP_EOL;

	echo '</main><!-- #main -->';
	echo '</div><!-- #primary -->';
}
get_sidebar();
get_footer();
