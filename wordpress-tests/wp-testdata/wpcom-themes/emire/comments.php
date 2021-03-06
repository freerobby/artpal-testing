<?php // Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
?>
<p class="nocomments"><?php _e("This post is password protected. Enter the password to view comments.", 'emire'); ?></p>
<?php
	return;
}

function emire_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>
<li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<div id="div-comment-<?php comment_ID() ?>">
	<div class="comment-author vcard">
		<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
		<cite class="fn"><?php comment_author_link() ?></cite> <?php _e('Says:','emire'); ?>
	</div>
	<?php if ($comment->comment_approved == '0') : ?>
	<em><?php _e('Your comment is awaiting moderation.','emire'); ?></em>
	<?php endif; ?>
	<br />

	<small class="comment-meta commentmetadata"><a href="#comment-<?php comment_ID() ?>" title=""><?php printf(__('%1$s at %2$s','emire'), get_comment_date(), get_comment_time()) ?></a> <?php edit_comment_link(__('e','emire'),'',''); ?></small>

	<?php comment_text() ?>
	<div class="reply">
		<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	</div>
<?php
}

if (have_comments()) : ?>
	<h3 id="comments"><?php printf(__('%1$s to &#8220;%2$s&#8221;','emire'), comments_number(__('No Responses Yet','emire'), __('One Response','emire'), __('% Responses','emire')), get_the_title()) ?></h3>

	<ol class="commentlist">
	<?php wp_list_comments(array(
		'callback'=>'emire_comment',
	)); ?>
	</ol>
	
	<div class="comments-navigation">
		<div class="alignleft"><?php previous_comments_link(); ?></div>
		<div class="alignright"><?php next_comments_link(); ?></div>
	</div>
	<br />

	<?php if (!comments_open()) : ?> 
		<p class="nocomments"><?php _e('Comments are closed.','emire'); ?></p>
	<?php endif; ?>
<?php endif; ?>


<?php if (comments_open()) : ?>
<div id="respond">
<h3><?php comment_form_title( __('Leave a Reply','emire'), __('Leave a Reply to %s','emire') ); ?></h3>
<div id="cancel-comment-reply"><small><?php cancel_comment_reply_link() ?></small></div>

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p><?printf( __('You must be <a href="%s">logged in</a> to post a comment.','emire'), get_option('siteurl') . '/wp-login.php?redirect_to=' . get_permalink()) ?></p>
<?php else : ?>

<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

<?php if ( $user_ID ) : ?>

<p><?php printf( __('Logged in as %s.','emire'), '<a href="' . get_option('siteurl') . '/wp-admin/profile.php">' . $user_identity . '</a>') ?> <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="<?php _e('Log out of this account','emire'); ?>"><?php _e('Logout','emire'); ?> &raquo;</a></p>

<?php else : ?>

<p><input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" />
<label for="author"><small><?php _e('Name','emire'); ?> <?php if ($req) echo __('(required)','emire'); ?></small></label></p>

<p><input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" />
<label for="email"><small><?php _e('E-mail (will not be published)','emire'); ?> <?php if ($req) echo __('(required)','emire'); ?></small></label></p>

<p><input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />
<label for="url"><small><?php _e('Website','emire'); ?></small></label></p>

<?php endif; ?>

<!--<p><small><?php printf(__('<strong>XHTML:</strong> You can use these tags: %s', 'emire'), allowed_tags()) ?></small></p>-->

<p><textarea name="comment" id="comment" cols="100%" rows="10" tabindex="4"></textarea></p>

<p><input name="submit" type="submit" id="submit" tabindex="5" value="<?php _e('Submit Comment','emire'); ?>" />
<?php comment_id_fields(); ?>
</p>
<?php do_action('comment_form', $post->ID); ?>

</form>
<?php endif; // If registration required and not logged in ?>
</div>
<?php endif; // if you delete this the sky will fall on your head ?>
