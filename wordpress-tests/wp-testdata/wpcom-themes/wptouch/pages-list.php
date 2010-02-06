<?php global $is_ajax; $is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']); if (!$is_ajax) get_header(); ?>
<?php $wptouch_settings = bnc_wptouch_get_settings(); ?>
 	<div class="post content" id="post-<?php the_ID(); ?>">
	 <div class="page">
		<div class="page-title-icon">		
			<img class="pageicon" src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon-pool/Default.png" alt="pageicon" />
		</div>

		<h2><?php _e('Pages'); ?></h2>
	</div>
	      
	<div class="clearer"></div>

	<div id="entry-pageslist" class="pageentry <?php echo $wptouch_settings['style-text-size']; ?> <?php echo $wptouch_settings['style-text-justify']; ?>">
		<ul><?php wp_list_pages('title_li='); ?></ul>
		</div>
	</div>   
           		
<!-- If it's ajax, we're not bringing in footer.php -->
<?php global $is_ajax; if (!$is_ajax) get_footer(); ?>
