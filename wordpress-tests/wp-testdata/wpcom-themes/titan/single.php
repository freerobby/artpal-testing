<?php
/**
 * @package WordPress
 * @subpackage Titan
 */
get_header(); ?>

	<div id="content">

		<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="post-header">
				<div class="tags"><?php the_tags( '<span>Tags</span> <p>', ', ', '</p>' ); ?></div>
				<h1><?php the_title(); ?></h1>
				<div class="author"><?php printf( __( 'by %s on', 'titan' ), get_the_author() ); ?> <?php the_time( __( 'F jS, Y', 'titan' ) ); ?></div>
			</div>

			<div class="entry clear">
				<?php if ( function_exists( 'add_theme_support' ) ) the_post_thumbnail( array( 250, 9999 ), array( 'class' => ' alignleft border' ) ); ?>
				<?php the_content( __( 'read more...', 'titan' ) ); ?>
				<?php edit_post_link( __( 'Edit This','<p>','</p>', 'titan' ) ); ?>
				<?php wp_link_pages(); ?>
			</div>

			<div class="meta clear">
				<p><?php _e( 'From', 'titan' ); ?> &rarr; <?php the_category( ', ' ); ?></p>
			</div>
		</div><!-- /post-->

		<?php endwhile; ?>

		<?php comments_template( '', true ); ?>

		<?php else : ?>
		<?php endif; ?>
	</div><!-- /content-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>