<?php get_header();

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
?>

<h2><a href="<?php echo get_permalink($post->post_parent); ?>" rev="attachment"><?php echo get_the_title($post->post_parent); ?></a> &raquo; <?php the_title(); ?></h2>

<p class="attachment"><a href="<?php echo wp_get_attachment_url($post->ID); ?>"><?php echo wp_get_attachment_image( $post->ID, array($content_width) ); ?></a></p>
<div class="caption"><?php if ( !empty($post->post_excerpt) ) the_excerpt(); ?></div>
<div class="image-description"><?php if ( !empty($post->post_content) ) the_content(); ?></div>

<p><?php previous_image_link(); ?> <?php next_image_link() ?></p>
<?php
		comments_template();
	endwhile;
else:
?>
	<h2><?php _e('Not Found'); ?></h2>
<?php
endif;

get_footer(); ?>
