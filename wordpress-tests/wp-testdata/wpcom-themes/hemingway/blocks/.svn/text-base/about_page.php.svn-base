
<?php query_posts('pagename=about'); ?>
<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>
<h2><?php _e('About','hemingway'); ?> <?php edit_post_link('Edit', '<small>(', ')</small>'); ?></h2>
<?php the_content(); ?>
<?php endwhile; ?>
<?php endif; ?>
