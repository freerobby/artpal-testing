<?php if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); } ?>
<?php include 'functions.php'; ?>

<div class="wrap">

<div class="clearfix">
<h2 id="arras-header"><?php _e('Arras Theme Options', 'arras') ?></h2>
</div>

<?php echo $notices ?>


<form id="arras-settings-form" method="post" action="themes.php?page=arras-options&_wpnonce=<?php echo $nonce ?>">

<div class="clearfix arras-options-wrapper">

<?php include 'arras-categories.php' ?>
<?php include 'arras-navigation.php' ?>
<?php include 'arras-layout.php' ?>
<?php include 'arras-design.php' ?>

<div id="remove" class="padding-content">
	<h3><?php _e('Revert to Default Settings', 'arras') ?></h3>
	<p class="submit">
	<input class="button-secondary" type="submit" name="reset" value="<?php _e('Reset Theme Settings', 'arras') ?>" />
	</p>
</div>

</form>
</div><!-- .wrap -->

<?php
/* End of file options_page.php */
/* Location: ./library/admin/templates/options_page.php */
