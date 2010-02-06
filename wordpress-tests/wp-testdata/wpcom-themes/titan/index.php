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
				<div class="date"><?php the_time( __( 'M j', 'titan' ) ); ?> <span><?php the_time( 'y' ); ?></span></div>
				<h2><a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
				<div class="author"><?php printf( __( 'by %s', 'titan' ), get_the_author() ); ?></div>
			</div>

			<div class="entry clear">
				<?php if ( function_exists( 'add_theme_support' ) ) the_post_thumbnail( array(250,9999), array( 'class' => ' alignleft border' ) ); ?>
				<?php the_content( __( 'read more...', 'titan' ) ); ?>
				<?php edit_post_link( __( 'Edit This','<p>','</p>', 'titan' ) ); ?>
				<?php wp_link_pages(); ?>
			</div>

			<div class="post-footer">
				<div class="comments"><?php comments_popup_link( __( 'Leave a comment', 'titan' ), __( '1 Comment', 'titan' ), _n ( '% Comment', '% Comments', get_comments_number (),'titan' ) ); ?></div>
			</div>
		</div><!-- /post-->

		<?php endwhile; ?>

		<div class="navigation index">
			<div class="alignleft"><?php next_posts_link( __( '&laquo; Older Entries', 'titan' ) ); ?></div>
			<div class="alignright"><?php previous_posts_link( __( 'Newer Entries &raquo;', 'titan' ) ); ?></div>
		</div>

		<?php else : ?>
		<?php endif; ?>
	</div><!-- /content-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>