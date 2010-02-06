<?php 
get_header();
?>

	<div id="container">
		<div id="content">

			<div id="nav-above" class="navigation">
				<div class="nav-previous"><?php next_posts_link(__( '<span class="meta-nav">&laquo;</span> Older posts', 'sandbox' )) ?></div>
				<div class="nav-next"><?php previous_posts_link(__( 'Newer posts <span class="meta-nav">&raquo;</span>', 'sandbox' )) ?></div>
			</div>

<?php query_posts( 'category_name=' . GURUQ_CAT . '&posts_per_page=20' ); ?>

<div id="accordion-wrap">
<div id="accordion2">
<div id="accordion2box">
<h2><?php echo __( 'Featured' );?></h2>
<?php
$do_not_duplicate = array();

$featured = new WP_Query( 'category_name=' . GURUQ_FEAT_CAT .'&posts_per_page=10&orderby=date&order=ASC' );
while ($featured->have_posts()) : $featured->the_post();
$do_not_duplicate[] = $post->ID;
?>
<h3 class="entry-title"><a href="<?php the_permalink() ?>" title="<?php printf( __('Permalink to %s', 'sandbox'), the_title_attribute('echo=0') ) ?>" rel="bookmark"><?php the_title() ?></a></h3>
<div>
	<?php the_content( __( 'Read More <span class="meta-nav">&raquo;</span>', 'sandbox' ) ) ?>
	
	<div class="entry-date"><?php unset($previousday); printf( __( '%1$s &#8211; %2$s', 'sandbox' ), the_date( '', '', '', false ), get_the_time() ) ?> | <a href="<?php the_permalink() ?>" title="<?php printf( __('Permalink to %s', 'sandbox'), the_title_attribute('echo=0') ) ?>" rel="bookmark">Permalink</a></div>
</div>
<?php endwhile; ?>
</div><!-- #accordion2box -->
</div><!-- #accordion2 -->

<div id="accordion1">
<div id="accordion1box">
<h2><?php echo __( 'Recently Answered' );?></h2>
<?php
query_posts( array( 'post__not_in' => $do_not_duplicate, 'category_name' => GURUQ_CAT, 'posts_per_page' => 10, 'orderby' => 'date', 'order' => 'ASC' ) );
if (have_posts()) : while (have_posts()) : the_post();
	if ( in_array( $post->ID, $do_not_duplicate ) ) continue;
update_post_caches($posts);
?>
<h3 class="entry-title"><a href="<?php the_permalink() ?>" title="<?php printf( __('Permalink to %s', 'sandbox'), the_title_attribute('echo=0') ) ?>" rel="bookmark"><?php the_title() ?></a></h3>
<div>
	<?php the_content( __( 'Read More <span class="meta-nav">&raquo;</span>', 'sandbox' ) ) ?>
	
	<div class="entry-date"><?php unset($previousday); printf( __( '%1$s &#8211; %2$s', 'sandbox' ), the_date( '', '', '', false ), get_the_time() ) ?> | <a href="<?php the_permalink() ?>" title="<?php printf( __('Permalink to %s', 'sandbox'), the_title_attribute('echo=0') ) ?>" rel="bookmark">Permalink</a></div>
</div><!-- #accordion1box -->

<?php endwhile; endif; ?>
</div><!-- #accordion1 -->
</div><!-- #accordion-wrap -->


<div class="clearer"></div>

<!--
<div id="nav-below" class="navigation">
	<div class="nav-previous"><?php next_posts_link(__( '<span class="meta-nav">&laquo;</span> Older posts', 'sandbox' )) ?></div>
	<div class="nav-next"><?php previous_posts_link(__( 'Newer posts <span class="meta-nav">&raquo;</span>', 'sandbox' )) ?></div>
</div>
-->

		</div><!-- #content -->
	</div><!-- #container -->

<?php //get_sidebar() ?>
<?php get_footer() ?>