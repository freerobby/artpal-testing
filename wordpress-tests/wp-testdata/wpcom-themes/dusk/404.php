<?php get_header(); ?>

	<div id="content">
	
		<h2 class="pagetitle"><?php _e('File Not Found', 'dusk'); ?></h2>
		
		<p><?php _e('Sorry, but the page you requested cannot be found.', 'dusk'); ?></p>
				
		<?php include (TEMPLATEPATH . '/searchform.php'); ?>

	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>