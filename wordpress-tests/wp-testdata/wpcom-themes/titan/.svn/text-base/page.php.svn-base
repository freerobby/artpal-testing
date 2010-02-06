<?php
/**
 * @package WordPress
 * @subpackage Titan
 */
get_header(); ?>

	<div id="content">

		<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>

			<h1 class="pagetitle"><?php the_title(); ?></h1>
			<div class="entry page clear">
				<?php the_content(); ?>
				<?php edit_post_link( __( 'Edit This','<p>','</p>', 'titan' ) ); ?>
				<?php wp_link_pages(); ?>
			</div><!-- /entry -->

		<?php endwhile; ?>

		<?php comments_template( '', true ); ?>

		<?php else : ?>
		<?php endif; ?>
	</div><!-- /content-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>