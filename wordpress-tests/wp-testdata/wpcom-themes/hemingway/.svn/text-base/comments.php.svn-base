<?php // Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die (__('Please do not load this page directly. Thanks!', 'hemingway'));
	if ( post_password_required() ) {
	?>
	<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.', 'hemingway'); ?></p>
	<?php
		return;
	}

function hemingway_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>
<li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<div id="div-comment-<?php comment_ID() ?>">
		<cite class="comment-author vcard comment-meta commentmetadata">
              		<span class="avatarspan"><?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?></span>
			<span class="author fn"><?php comment_author_link() ?></span>
			<span class="date"><?php comment_date('n.j.y') ?> / <?php comment_date('ga') ?></span>
		</cite>
		<div class="content">
			<?php if ($comment->comment_approved == '0') : ?>
			<em><?php _e('Your comment is awaiting moderation.', 'hemingway'); ?></em>
			<?php endif; ?>
			<?php comment_text() ?>
		</div>
		<div class="reply">
			<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
		</div>
		<div class="clear"></div>
	</div>
<?php
}

if ( comments_open() ) {
	// Comments are open ?>
	<div class="comment-head">
		<h2><?php comments_number(__('No comments yet', 'hemingway'), __('1 Comment', 'hemingway'), __('% Comments', 'hemingway')); ?></h2>
		<span class="details"><a href="#comment-form"><?php _e('Jump to comment form', 'hemingway'); ?></a> | <a href="<?php echo get_post_comments_feed_link(); ?>"><?php _e('comment rss', 'hemingway'); ?> <?php _e('[?]', 'hemingway'); ?></a> <?php if ( pings_open() ): ?>| <a href="<?php trackback_url(true); ?>"><?php _e('trackback uri', 'hemingway'); ?></a> <a href="#what-is-trackback" class="help"><?php _e('[?]', 'hemingway'); ?></a><?php endif; ?></span>
	</div>
<?php } elseif ( have_comments() && !comments_open() ) {
	// Neither Comments, nor Pings are open ?>
	<div class="comment-head">
		<h2><?php _e('Comments are closed','hemingway'); ?></h2>
		<span class="details"><?php _e('Comments are currently closed on this entry.', 'hemingway'); ?></span>
	</div>
<?php }

if ( have_comments() ) : ?>
	<ol id="comments" class="commentlist">
	<?php wp_list_comments(array('callback'=>'hemingway_comment', 'style'=>'ol')); ?>
	</ol>

	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
<?php endif; ?>

<?php if ( comments_open() ) : ?>
<div id="respond">
<div id="comment-form">
	<h3 class="formhead"><?php comment_form_title( __('Have your say', 'hemingway'), __('Have your say to %s', 'hemingway') ); ?></h3>
	<div id="cancel-comment-reply"><small><?php cancel_comment_reply_link() ?></small></div>
	<p><small><?php printf(__('<strong>XHTML:</strong> You can use these tags: %s', 'hemingway'), allowed_tags()); ?></small></p>
	<?php if ( get_option('comment_registration') && !$user_ID ) : ?>

		<p><?php _e('You must be','hemingway'); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>"><?php _e('logged in','hemingway'); ?></a> <?php _e('to post a comment.','hemingway'); ?></p>
	<?php else : ?>
		<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
		<?php if ( $user_ID ) : ?>
			<p><?php _e('Logged in as','hemingway'); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="<?php __('Log out of this account', 'hemingway'); ?>"><?php _e('Logout &raquo;','hemingway'); ?></a></p>
		<?php else : ?>
			<input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" class="textfield" tabindex="1" /><label class="text"><?php _e('Name', 'hemingway'); ?> <?php if ($req) echo _e('(required)', 'hemingway'); ?></label><br />
			<input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" class="textfield" tabindex="2" /><label class="text"><?php _e('Email', 'hemingway'); ?> <?php if ($req) echo _e('(required)', 'hemingway'); ?></label><br />
			<input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" class="textfield" tabindex="3" /><label class="text"><?php _e('Website', 'hemingway'); ?></label><br />
		<?php endif; ?>
				
		<textarea name="comment" id="comment" class="commentbox" tabindex="4"></textarea>
		<div class="formactions">
			<span style="visibility:hidden">Safari hates me</span>
			<input type="submit" name="submit" tabindex="5" class="submit" value="Add your comment" />
		</div>
		<?php comment_id_fields(); ?>
		<?php do_action('comment_form', $post->ID); ?>
		</form>
	<?php endif; // If registration required and not logged in ?>
</div></div>
<?php endif; // if you delete this the sky will fall on your head ?>
