<?php get_header(); ?>
			
			<div id="body">
				
				<div id="content">
							
<?php the_post(); ?>

<div id="nav-above" class="navigation">
	<div class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">&lsaquo;</span> %title' ) ?></div>
	<div class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">&rsaquo;</span>' ) ?></div>
</div><!-- #nav-above -->

					<div <?php post_class() ?> id="post-<?php the_ID(); ?>">

						<h2 class="haslink">
							<a href="<?php the_permalink(); ?>">
								<span class="posted"><?php the_time('d/m/y'); ?></span>
								<span class="title"><?php the_title(); ?></span>
							</a>
						</h2>
						
						<div class="contentblock">
						
	<?php if (has_excerpt()) : ?>
							<div id="intro">
		<?php the_excerpt(); ?>
							</div><!-- #intro -->
	<?php endif; ?>
					
	<?php the_content(''); ?>
	<?php wp_link_pages('before=<p class="page-link">' . __( 'Pages:', 'theme' ) . '&after=</p>') ?>
							<p><?php the_tags(__( 'Tags: ', 'theme' ), ', ', ''); ?></p>
							
						</div><!-- contentblock -->
						
						<ul class="postdetails">
							<?php if (comments_open()) { ?>
							<li class="comments"><?php comments_popup_link(__('No comments', 'theme'), __('1 comment', 'theme'), '%' . __(' comments', 'theme')); ?></li>
							<?php } else { ?>
							<li class="comments"><?php _e('Comments off', 'theme'); ?></li>
							<?php } ?>
							<li class="categories">Posted under <?php the_category(', ') ?><?php edit_post_link('Edit', ' | ', ''); ?></li>
						</ul><!-- postdetails -->
					
					</div><!-- #post-<?php the_ID(); ?> -->				

<div id="nav-below" class="navigation">
	<div class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">&lsaquo;</span> %title' ) ?></div>
	<div class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">&rsaquo;</span>' ) ?></div>
</div><!-- #nav-below -->			

				</div><!-- content -->
				
<?php get_sidebar(); ?>
				
			</div><!-- body -->
			
<?php get_footer(); ?>