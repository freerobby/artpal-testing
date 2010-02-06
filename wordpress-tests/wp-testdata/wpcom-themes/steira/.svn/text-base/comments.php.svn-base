<?php
// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<p class="nocomments">This post is password protected. Enter the password to view comments.</p>
	<?php
		return;
	}
?>

<?php /* You can start editing here */ ?>

<?php if ( have_comments() ) : ?>
	<h3 id="comments"><span class="more">
		<a href="#newcomment">
	<?php if (comments_open()) { ?>
		<label for="author"><?php _e('Write a new comment', 'theme'); ?></label>
	<?php } ?>
		</a>
		</span>
		<span class="title"><?php comments_number('No comments', '1 comment', '% comments' );?></span>
	</h3>

<?php $total_pages = get_comment_pages_count(); if ( $total_pages > 1 ) : /* are there comments to navigate through? */ ?>
	<div class="navigation">
		<div class="nav-previous"><?php previous_comments_link( __('&lsaquo; Older Comments', 'theme') ) ?></div>
		<div class="nav-next"><?php next_comments_link( __('Newer Comments &rsaquo;', 'theme') ) ?></div>
	</div>
<?php endif; // check for comment navigation ?>								

	<ol class="commentlist">
		<?php wp_list_comments('callback=steira_comments'); ?>
	</ol>

<?php $total_pages = get_comment_pages_count(); if ( $total_pages > 1 ) : /* are there comments to navigate through? */ ?>
	<div class="navigation">
		<div class="nav-previous"><?php previous_comments_link( __('&lsaquo; Older Comments', 'theme') ) ?></div>
		<div class="nav-next"><?php next_comments_link( __('Newer Comments &rsaquo;', 'theme') ) ?></div>
	</div>
	
<?php endif; /* check for comment navigation */ ?>								

<?php else : /* this is displayed if there are no comments so far */ ?>

<?php if ( comments_open() ) : /* If comments are open, but there are no comments */ ?>

	 <?php else : /* comments are closed */ ?>
		<p class="nocomments">Comments are closed.</p>

	<?php endif; ?>
<?php endif; ?>


<?php if ( comments_open() ) : ?>

<div id="respond">	
	
	<h3><?php comment_form_title( 'Leave a Reply', 'Leave a Reply to %s' ); ?></h3>
	
	<div class="cancel-comment-reply"><?php cancel_comment_reply_link(); ?></div>
	
	<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
	<p>You must be <a href="<?php echo wp_login_url( get_permalink() ); ?>">logged in</a> to post a comment.</p>
	<?php else : ?>
	
	<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
	
	<?php if ( is_user_logged_in() ) : ?>
	
	<p><?php _e('Logged in as ', 'theme'); ?><a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php _e('Log out of this account', 'theme'); ?>">Log out &rsaquo;</a></p>
	
	<?php else : ?>
	
	<p class="for-input">
		<label class="for-text" for="author"><?php _e('Name', 'theme'); ?></label>	
		<input class="text" type="text" name="author" id="author" value="<?php echo esc_attr($comment_author); ?>" size="22" tabindex="1" />
	</p>
	
	<p class="for-input">
		<label class="for-text" for="email"><?php _e('Email', 'theme'); ?></label>
		<input class="text" type="text" name="email" id="email" value="<?php echo esc_attr($comment_author_email); ?>" size="22" tabindex="2" />
	</p>
	
	<p class="for-input">
		<label class="for-text" for="url"><?php _e('Website ', 'theme'); ?><small><?php _e('optional', 'theme'); ?></small></label>
		<input class="text" type="text" name="url" id="url" value="<?php echo esc_attr($comment_author_url); ?>" size="22" tabindex="3" />
	</p>
	
	<?php endif; ?>	

	<p class="for-input">
		<label class="for-text" for="message"><?php _e('Message', 'theme'); ?></label>
		<textarea name="comment" id="comment" cols="58" rows="10" tabindex="4"></textarea>
	</p>
	
	<p class="buttons">
		<input class="submit" name="submit" type="submit" id="submit" tabindex="5" value="Submit" />
	<?php comment_id_fields(); ?>
	</p>
	<?php do_action('comment_form', $post->ID); ?>
	
	</form>
	
	<?php endif; /* If registration required and not logged in */ ?>

</div><!-- #respond -->

<?php endif; /* if you delete this bad luck will follow you for seven years */ ?>
