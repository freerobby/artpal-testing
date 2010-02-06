<?php get_header(); ?>
			
			<div id="body">
				
				<div id="content">

<?php the_post(); ?>			
			
				<h2 class="page-title"><?php _e( 'Tag Archives:', 'your-theme' ) ?> <strong><?php single_tag_title() ?></strong></h2>

<?php rewind_posts(); ?>
				
<?php global $wp_query; $total_pages = $wp_query->max_num_pages; if ( $total_pages > 1 ) { ?>
				<div id="nav-above" class="navigation">
					<div class="nav-previous"><?php next_posts_link(__( '<span class="meta-nav">&lsaquo;</span> Older posts', 'theme' )) ?></div>
					<div class="nav-next"><?php previous_posts_link(__( 'Newer posts <span class="meta-nav">&rsaquo;</span>', 'theme' )) ?></div>
				</div><!-- #nav-above -->
<?php } ?>											
							
<?php $count == 0; while (have_posts()) : the_post(); $count++; ?>			

					<div <?php post_class() ?> id="post-<?php the_ID(); ?>">

						<h2 class="haslink">
							<a href="<?php the_permalink(); ?>">
								<span class="posted"><?php the_time('d/m/y'); ?></span>
								<span class="title"><?php the_title(); ?></span>
							</a>
						</h2>
						
						<div class="contentblock">
					
	<?php the_excerpt('<p class="more">' . __('Continue reading this article &rsaquo;', 'theme') . '</p>'); ?>
							<p><?php the_tags(__( 'Tags: ', 'theme' ), ', ', ''); ?></p>
							
						</div><!-- contentblock -->
						
						<ul class="postdetails">
							<li class="comments"><?php comments_popup_link(__('No comments', 'theme'), __('1 comment', 'theme'), '%' . __(' comments', 'theme')); ?></li>
							<li class="categories">Posted under <?php the_category(', ') ?><?php edit_post_link('Edit', ' | ', ''); ?></li>
						</ul><!-- postdetails -->
					
					</div><!-- #post-<?php the_ID(); ?> -->
					
<?php endwhile; ?>

<?php global $wp_query; $total_pages = $wp_query->max_num_pages; if ( $total_pages > 1 ) { ?>
				<div id="nav-below" class="navigation">
					<div class="nav-previous"><?php next_posts_link(__( '<span class="meta-nav">&lsaquo;</span> Older posts', 'theme' )) ?></div>
					<div class="nav-next"><?php previous_posts_link(__( 'Newer posts <span class="meta-nav">&rsaquo;</span>', 'theme' )) ?></div>
				</div><!-- #nav-below -->
<?php } ?>																	
		
				</div><!-- content -->
				
<?php get_sidebar(); ?>
				
			</div><!-- body -->
			
<?php get_footer(); ?>