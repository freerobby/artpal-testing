<?php global $is_ajax; $is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']); if (!$is_ajax) get_header(); ?>
<?php $wptouch_settings = bnc_wptouch_get_settings(); ?>
 	<div class="post content" id="post-<?php the_ID(); ?>">
	 <div class="page">
		<div class="page-title-icon">		
			<img class="pageicon" src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon-pool/Archives.png" alt="pageicon" />
		</div>

		<?php
			if ( 'tags' == $_GET['archives-type'] )
				echo '<h2>' . __('Tag Archives') . '</h2>';
			elseif ( 'cats' == $_GET['archives-type'] )
				echo '<h2>' . __('Category Archives') . '</h2>';
			elseif ( 'months' == $_GET['archives-type'] )
				echo '<h2>' . __('Monthly Archives') . '</h2>';
			else
				echo '<h2>' . __('Archives') . '</h2>';
		?>
	</div>
	      
	<div class="clearer"></div>

	<div id="entry-pageslist" class="pageentry <?php echo $wptouch_settings['style-text-size']; ?> <?php echo $wptouch_settings['style-text-justify']; ?>">
		<?php
			if ( 'tags' == $_GET['archives-type'] ) {
				echo '<ul>';
				$tags = get_tags('orderby=count&number=5&order=DESC');
				foreach ( (array) $tags as $tag )
					echo '<li><a href="' . get_tag_link ($tag->term_id) . '" rel="tag">' . $tag->name . '</a> (' . $tag->count . ')</li>';
				echo '</ul>';
			} elseif ( 'cats' == $_GET['archives-type'] ) {
				echo '<ul>';
				wp_list_categories('title_li=&show_count=1&orderby=count&order=DESC');
				echo '</ul>';
			} elseif ( 'months' == $_GET['archives-type'] ) {
				echo '<ul>';
				wp_get_archives('type=monthly');
				echo '</ul>';
			} else {
		?>
		<h3><?php _e('Popular Tags'); ?> <a href="<?php bloginfo('home'); ?>?archives-list&archives-type=tags">&raquo;</a></h3>
		<ul>
		<?php
			$tags = get_tags('orderby=count&number=5&order=DESC');
			foreach ( (array) $tags as $tag )
				echo '<li><a href="' . get_tag_link ($tag->term_id) . '" rel="tag">' . $tag->name . '</a> (' . $tag->count . ')</li>';
		?>
		</ul>
		<h3><?php _e('Popular Categories'); ?> <a href="<?php bloginfo('home'); ?>?archives-list&archives-type=cats">&raquo;</a></h3>
		<ul><?php wp_list_categories('orderby=count&number=5&title_li=&show_count=1&order=DESC'); ?></ul>

		<h3><?php _e('Monthly Archives'); ?> <a href="<?php bloginfo('home'); ?>?archives-list&archives-type=months">&raquo;</a></h3>
		<ul><?php wp_get_archives('type=monthly&limit=5'); ?></ul>
		<?php } ?>
	</div>
	</div>   
           		
<!-- If it's ajax, we're not bringing in footer.php -->
<?php global $is_ajax; if (!$is_ajax) get_footer(); ?>
