<?php get_header();

if ( isset($_GET['pages-list']) ) :
	include ('pages-list.php');
elseif ( isset($_GET['archives-list']) ) :
	include ('archives-list.php');
else :
$i = 0;

if ( is_search() ) {
?>
	<h2><?php _e('Search Results for:'); ?> <?php the_search_query(); ?></h2>
<?php
}


if (have_posts()) :
	echo '<ul>';
	while (have_posts()) :
		the_post();
			$i++;
?>
	<li><a<?php if ( $GLOBALS['accesskey'] < '10' ) { echo ' accesskey="'.$GLOBALS['accesskey'].'"'; $GLOBALS['accesskey']++; } ?> href="<?php the_permalink(); ?>"><?php the_title(); ?></a> <span class="wmp-thetime"><?php echo(get_the_time(get_option('date_format'))); ?></span> - <span class="wpm-commentsnumber">(<?php comments_number('0','1','%'); ?>)</span> <?php if ('1' == $i) the_excerpt();?></li>
<?php
	endwhile;
?>
	</ul>
	<p><?php next_posts_link( __('&laquo; Older') ); ?>  <?php previous_posts_link( __('Newer &raquo;') ); ?></p>
<?php
	else:
		?>	
			<h2><?php _e('Not Found'); ?></h2>
		<?php
endif;
endif;

get_footer(); ?>
