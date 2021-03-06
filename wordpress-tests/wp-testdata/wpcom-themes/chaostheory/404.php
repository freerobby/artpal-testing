<?php get_header() ?>

	<div id="container">
		<div id="content">
			<div id="post-0" class="post">
				<h2 class="post-title"><?php _e('Not Found', 'chaostheory') ?></h2>
				<div class="post-entry">
					<p><?php _e('Apologies, but we were unable to find what you were looking for. Perhaps the search box will help.', 'chaostheory') ?></p>
				</div>
			</div><!-- .post -->
			<form id="searchform" method="get" action="<?php bloginfo('home') ?>">
				<div>
					<input id="s" name="s" type="text" value="<?php echo wp_specialchars(stripslashes($_GET['s']), true); ?>" tabindex="1" size="40" />
					<input id="searchsubmit" name="searchsubmit" type="submit" value="<?php _e('Find &raquo;', 'chaostheory') ?>" tabindex="2" />
				</div>
			</form>
		</div><!-- #content .hfeed -->
	</div><!-- #container -->

<?php get_sidebar() ?>
<?php get_footer() ?>