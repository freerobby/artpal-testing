<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes() ?>>
<head profile="http://gmpg.org/xfn/11">
	<title><?php wp_title(); ?> <?php bloginfo('name'); ?></title>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>

<?php 
wp_enqueue_script( 'jquery' );
wp_enqueue_script( 'jquery-ui-core' );
wp_enqueue_script( 'accordion', get_bloginfo( 'template_directory' ) . '/ui.accordion.js', array('jquery', 'jquery-ui-core') );
?>
<link type="text/css" href="<?php echo get_bloginfo( 'template_directory' ); ?>/ui-lightness/jquery-ui-1.7.2.custom.css" rel="stylesheet" />
	<?php wp_head() ?>

<script type="text/javascript" src="<?php echo get_bloginfo( 'template_directory' ); ?>/scripts.js"></script>

</head>

<body class="<?php sandbox_body_class() ?>">

<div id="wrapper" class="hfeed">

	<div id="header">
		<h1 id="blog-title"><span><a href="<?php bloginfo('home') ?>/" title="<?php echo wp_specialchars( get_bloginfo('name'), 1 ) ?>" rel="home"><?php bloginfo('name') ?></a></span></h1>
		<div id="blog-description"><?php bloginfo('description') ?></div>
		<div class="clearer"></div>

	</div><!--  #header -->

<div id="search-question-wrap">
<div id="search-box">
<div id="searchbox">
<h2>Search Answers</h2>
	<form id="searchform" class="blog-search" method="get" action="<?php bloginfo('home') ?>">
		<div>
			<input type="text" value="<?php the_search_query(); ?>" name="s" id="s" /><br />
			<input type="submit" class="button" value="Search" tabindex="2" />
		</div>
	</form>
</div><!-- #searchbox -->
</div><!-- #search-box -->

<div id="question-box">
<?php 
require_once dirname( __FILE__ ) . '/post-form.php'; 
?>
</div><!-- #question-box -->
<div class="clearer"></div>

</div><!-- #search-question-wrap -->
