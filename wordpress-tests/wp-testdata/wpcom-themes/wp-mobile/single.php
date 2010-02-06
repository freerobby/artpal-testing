<?php get_header();

if (have_posts()) :
	while (have_posts()) :
		the_post();
?>

<h2><?php the_title(); ?></h2>
<p><?php the_time(get_option('date_format') . ' '); the_time(); ?></p>

<?php the_content(); ?>

<p><?php _e('Posted by'); ?> <?php the_author(); ?></p>
<p><?php _e('Categories:'); echo ' '; the_category(', '); ?></p>
<p><?php _e('Tags:'); the_tags(' ', ', '); ?></p>

<p><?php previous_post_link('%link', __('&laquo; Older')); ?> <?php next_post_link('%link',  __('Newer &raquo;') ); ?></p>
<?php
		comments_template();
	endwhile;
else:
?>	
<h2><?php _e('Not Found'); ?></h2>
<?php
endif;

get_footer();
