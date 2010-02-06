<?php
get_header();

if (have_posts()) :
	if ( is_category() ) { ?>
	<h2><?php _e('Posts in:'); ?> <?php single_cat_title(''); ?></h2>
<?php } elseif ( is_tag() ) { ?>
	<h2><?php _e('Posts tagged:'); ?> <?php single_tag_title(''); ?></h2>
<?php } else if ( is_day() ) { ?>
	<h2><?php _e('Posts on:'); ?> <?php the_time('l, F jS, Y'); ?></h2>
<?php } else if ( is_month() ) { ?>
	<h2><?php _e('Posts in:'); ?> <?php the_time('F, Y'); ?></h2>
<?php } else if ( is_year() ) { ?>
	<h2><?php _e('Posts in:'); ?> <?php the_time('Y'); ?></h2>
<?php }
	echo '<ul>';
	while (have_posts()) :
		the_post();
?>
		<li><a<?php if ( $GLOBALS['accesskey'] < '10' ) { echo ' accesskey="'.$GLOBALS['accesskey'].'"'; $GLOBALS['accesskey']++; } ?> href="<?php the_permalink(); ?>"><?php the_title(); ?></a> <span class="wmp-thetime"><?php echo(get_the_time(get_option('date_format'))); ?></span> - <span class="wpm-commentsnumber">(<?php comments_number('0','1','%'); ?>)</span></li>
<?php endwhile; ?>
	</ul>

	<p><?php next_posts_link( __('&laquo; Older') ); ?> <?php previous_posts_link( __('Newer &raquo;') ); ?></p>
<?php else: ?>	
	<h2><?php _e('Not Found'); ?></h2>
<?php
endif;

get_footer();
