<?php
/**
 * The Template for displaying all single posts.
 *
 * @package _s
 */

get_header(); ?>

	<div id="primary" data-id="<?php the_ID(); ?>">
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>


		<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>