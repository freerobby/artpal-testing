<?php get_header(); ?>
			
			<div id="body">
				
				<div id="content">
							
<?php the_post(); ?>

					<div <?php post_class() ?> id="post-<?php the_ID(); ?>">

						<h2 class="haslink">
							<a href="<?php echo get_permalink($post->post_parent) ?>" title="<?php printf( __( 'Return to %s', 'theme' ), wp_specialchars( get_the_title($post->post_parent), 1 ) ) ?>" rev="attachment">
								<span class="posted"><?php the_time('d/m/y'); ?></span>
								<span class="title"><strong>&lsaquo; </strong><?php echo get_the_title($post->post_parent) ?></span>
							</a>
						</h2>
						
						<div class="contentblock">
	<?php if ( wp_attachment_is_image( $post->id ) ) : $att_image = wp_get_attachment_image_src( $post->id, "medium"); ?>
							<p class="attachment"><a href="<?php echo wp_get_attachment_url($post->id); ?>" title="<?php the_title(); ?>" rel="attachment"><img src="<?php echo $att_image[0];?>" width="<?php echo $att_image[1];?>" height="<?php echo $att_image[2];?>"  class="attachment-medium" alt="<?php $post->post_excerpt; ?>" /></a>
							</p>
	<?php else : ?>  
							<a href="<?php echo wp_get_attachment_url($post->ID) ?>" title="<?php echo wp_specialchars( get_the_title($post->ID), 1 ) ?>" rel="attachment"><?php echo basename($post->guid) ?></a>  
	<?php endif; ?>  
							<div class="entry-caption"><?php if ( !empty($post->post_excerpt) ) the_excerpt() ?></div>

	<?php the_content(''); ?>
	<?php wp_link_pages('before=<p class="page-link">' . __( 'Pages:', 'theme' ) . '&after=</p>') ?>
							<p><?php the_tags(__( 'Tags: ', 'theme' ), ', ', ''); ?></p>
							
						</div><!-- contentblock -->
						
						<ul class="postdetails">
							<li class="comments"><?php comments_popup_link(__('No comments', 'theme'), __('1 comment', 'theme'), '%' . __(' comments', 'theme')); ?></li>
							<li class="categories">Posted under <?php the_category(', ') ?><?php edit_post_link('Edit', ' | ', ''); ?></li>
						</ul><!-- postdetails -->
					
					</div><!-- #post-<?php the_ID(); ?> -->				

				</div><!-- content -->
				
<?php get_sidebar(); ?>
				
			</div><!-- body -->
			
<?php get_footer(); ?>