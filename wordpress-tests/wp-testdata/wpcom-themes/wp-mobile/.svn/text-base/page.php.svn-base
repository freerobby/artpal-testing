<?php get_header();
if ( isset($_GET['pages-list']) ) :
	include ('pages-list.php');
elseif ( isset($_GET['archives-list']) ) :
	include ('archives-list.php');
else :
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
?>

<h2><?php the_title(); ?></h2>

<?php
		the_content();

		comments_template();

	endwhile;
else:
?>	
			<h2><?php _e('Not Found'); ?></h2>
<?php
endif;
endif;

get_footer(); ?>
