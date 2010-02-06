<hr />
<form method="get" id="searchform" action="/">
	<fieldset>
		<input type="text" value="<?php the_search_query(); ?>" name="s" id="s" />
		<input type="submit" id="searchsubmit" value="<?php _e('Search'); ?>" />
	</fieldset>
</form>
<hr />
<p><?php _e("Mobile Site"); ?> | <a accesskey="0" href="<?php bloginfo('wpurl'); ?>/?ak_action=reject_mobile"><?php _e("Full Site"); ?></a></p>
<hr />
<p><?php _e('<a href="http://wordpress.com/" rel="generator">Blog at WordPress.com</a>'); ?>. WordPress Mobile Edition Theme <?php _e('by'); ?> <a href="http://alexking.org/">Alex King</a>.</p>
</body>
</html>
