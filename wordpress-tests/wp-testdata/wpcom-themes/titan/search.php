<?php
/**
 * @package WordPress
 * @subpackage Titan
 */
get_header(); ?>

	<div id="content">

		<?php if ( have_posts() ) : ?>

		<h1 class="pagetitle"><?php printf( __( "Search results for '%s'", "titan" ), attribute_escape( get_search_query() ) ); ?></h1>

		<?php while ( have_posts() ) : the_post(); ?>

			<div class="post" id="post-<?php the_ID(); ?>">
				<div class="post-header">
					<div class="date"><?php the_time( __( 'M j', 'titan' ) ); ?> <span><?php the_time( 'y' ); ?></div>
					<h2><a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
					<div class="author"><?php printf( __( 'by %s', 'titan' ), get_the_author() ); ?></div>
				</div>

				<div class="entry clear">
					<?php the_excerpt( __( 'read more...', 'titan' ) ); ?>
					<?php edit_post_link( __( 'Edit This','<p>','</p>', 'titan' ) ); ?>
				</div>

				<div class="post-footer">
					<div class="comments"><?php comments_popup_link( 'Leave a comment', '1 Comment', '% Comments' ); ?></div>
				</div>
			</div><!-- /post-->

			<?php endwhile; ?>

			<div class="navigation index">
				<div class="alignleft"><?php next_posts_link( '&laquo; Older Entries' ); ?></div>
				<div class="alignright"><?php previous_posts_link( 'Newer Entries &raquo;' ); ?></div>
			</div>

		<?php else : ?>

		<h1 class="pagetitle"><?php printf( __( "Search results for '%s'", "titan" ), attribute_escape( get_search_query() ) ); ?></h1>
		<div class="entry page">
			<p><?php printf( __( 'Sorry your search for "%s" did not turn up any results. Please try again.', 'titan' ), attribute_escape( get_search_query() ) ); ?></p>
			<?php if ( is_file( STYLESHEETPATH . '/searchform.php' ) ) include ( STYLESHEETPATH . '/searchform.php' ); else include( TEMPLATEPATH . '/searchform.php' ); ?>
		</div>
		<?php endif; ?>
	</div><!-- /content-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>