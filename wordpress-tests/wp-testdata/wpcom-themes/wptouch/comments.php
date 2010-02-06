<?php
// Do not delete these lines**********
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die( __('Please do not load this page directly. Thanks!', 'wptouch') );
		if (!empty($post->post_password)) {
			// if there's a password
			if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {
			// and it doesn't match the cookie ?>	
		<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.', 'wptouch'); ?></p>
<?php return; 	} }
/* This variable is for alternating comment background */
$oddcomment = 'alt'; 
?>

<!-- You can start editing below here... but make a backup first!  -->

<div id="comment_wrapper">
<?php comments_number( '', '<h3 onclick="bnc_showhide_coms_toggle();" id="com-head"><img id="com-arrow" src="' . get_stylesheet_directory_uri() . '/core/core-images/com_arrow.png" alt="arrow" />'.__('1 Comment', 'wptouch').'</h3>', '<h3 onclick="bnc_showhide_coms_toggle();" id="com-head"><img id="com-arrow" src="' . get_stylesheet_directory_uri() . '/core/core-images/com_arrow.png" alt="arrow" />'.__('% Comments', 'wptouch').'</h3>' ); ?>

	<ol class="commentlist" id="commentlist">
		<?php wp_list_comments(); ?>
	</ol> 
  <div id="respond">
	<div id="cancel-comment-reply">
		<small><?php cancel_comment_reply_link() ?></small></div>
  	<?php if ('open' == $post->comment_status) : ?>
		<?php if (get_option('comment_registration') && !$user_ID) : ?>
			<center>
			<h1>
				<?php sprintf( __( 'You must %slogin</a> or %sregister</a> to comment', 'wptouch' ), '<a href="' . get_option('wpurl') . '/wp-login.php">', '<a href="' . get_option('wpurl') . '"/wp-register.php">') ; ?>
			</h1>
			</center>

	<?php else : ?>
  	
	<?php
	 $filename = ABSPATH . 'wp-load.php';
	 if ( file_exists($filename) ) { ?>

		<div id="refresher" style="display:none;">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/good.png" alt="checkmark" />
			<h3><?php _e( "Comment added.", "wptouch" ); ?></h3>
			<?php _e('&raquo; <a href="javascript:this.location.reload();">Refresh the page</a> to post a new comment.', 'wptouch'); ?>
		</div>
			<form id="commentform" action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" onsubmit="$wptouch('#loading').fadeIn(100);var list = $wptouch('#commentlist'); var html = list.html(); var param = $wptouch('form').serialize(); $wptouch.ajax({url: '<?php echo wptouch_ajax_url(); ?>&' + param, success: function(data, status){ list.append(data); commentAdded(); }, type: 'get' }); return false;">

	<?php } else { ?>
		<div>
		<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

	<?php } ?>

	<?php if ($user_ID) : ?>

		<p class="logged"><?php printf( __('Logged in as %s:', 'wptouch' ), '<a href="'.get_bloginfo('wpurl').'/wp-admin/profile.php">'.$user_identity.'</a>' ); ?></p>
	
	<?php else : ?>
	
		<h3><?php _e( "Leave A Comment", "wptouch" ); ?></h3>
		<p>
			<input type="text" name="author" id="author" value="<?php echo esc_attr($comment_author); ?>" size="22" tabindex="1" />
			<label for="author"><?php _e( 'Name', 'wptouch' ); ?> <?php if ($req) echo "*"; ?></label>
		</p>

		<p>
			<input type="text" name="email" id="email" value="<?php echo esc_attr($comment_author_email);?>" size="22" tabindex="2" />
			<label for="email"><?php _e( 'Mail (unpublished)', 'wptouch' ); ?> <?php if ($req) echo "*"; ?></label>
		</p>
	
		<p>
			<input type="text" name="url" id="url" value="<?php echo esc_attr($comment_author_url); ?>" size="22" tabindex="3" />
			<label for="url"><?php _e( 'Website', 'wptouch' ); ?></label>
		</p>

	<?php endif; ?>
		<?php do_action('comment_form', $post->ID); ?>
		<p><textarea name="comment" id="comment" tabindex="4"></textarea></p>
		
		<p>
		<input name="submit" type="submit" id="submit" tabindex="5" value="<?php esc_attr_e( 'Publish' ); ?>" />
			<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />		
			<div id="loading" style="display:none">
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/core/core-images/comment-ajax-loader.gif" alt="" />
			</div>
		</p>
		<div id="errors" style="display:none">
			<?php _e( "There was an error. Please refresh the page and try again.", "wptouch" ); ?>
		</div>
		<?php comment_id_fields(); ?>				
		</form>
		</div>
	<?php endif; // If registration required and not logged in ?>

  </div><!--textinputwrap div-->
</div><!-- comment_wrapper -->

<?php endif; // if you delete this the sky will fall on your head ?>
