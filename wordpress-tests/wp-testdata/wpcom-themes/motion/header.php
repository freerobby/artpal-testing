<?php
/**
 * @package WordPress
 * @subpackage Motion
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php wp_title(); ?> <?php bloginfo('name'); ?></title>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>" type="text/css" media="screen" />

<!--[if lt IE 7]>
<link href="<?php bloginfo( 'template_url' ); ?>/ie6.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="<?php bloginfo( 'template_url' ); ?>/js/unitpngfix.js"></script>
<![endif]-->

<?php wp_enqueue_script( 'sfhover', get_template_directory_uri() . '/js/sfhover.js' ); ?>
<?php wp_head(); ?>
</head>

<body>
<div id="wrapper">

<div id="top">
	<div id="topmenu">
		<ul>
			<?php wp_list_pages( 'depth=1&title_li=0&sort_column=menu_order' ); ?>
			<li><a class="rss" href="<?php bloginfo( 'rss2_url' ); ?>">RSS</a></li>
		</ul>
	</div>

	<div id="search">
		<form method="get" id="searchform" action="<?php bloginfo( 'url' ); ?>/">
			<p>
				<input type="text" value="Search this site" onfocus="if (this.value == 'Search this site' ) { this.value = ''; }" onblur="if (this.value == '' ) { this.value = 'Search this site...'; }" name="s" id="searchbox" />
				<input type="submit" class="submitbutton" value="GO" />
			</p>
		</form>
	</div>
</div><!-- /top -->

<div id="header">
	<div id="logo">
		<a href="<?php echo get_option( 'home' ); ?>"><img src="<?php bloginfo( 'template_directory' ); ?>/images/genericlogo.png" alt="<?php bloginfo( 'name' ); ?>" /></a>
		<h1><a href="<?php echo get_option( 'home' ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<div id="desc"><?php bloginfo( 'description' ); ?></div>
	</div><!-- /logo -->

	<?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar( 'header' ) ) : ?>
	<div id="headerbanner">
		<p>Hey there! Thanks for dropping by <?php bloginfo( 'name' ); ?>! Take a look around and grab the <a href="<?php bloginfo( 'rss2_url' ); ?>">RSS feed</a> to stay updated. See you around!</p>
	</div>
	<?php endif; ?>
</div><!-- /header -->

<div id="catnav">
	<ul id="nav">
		<li><a href="<?php echo get_option( 'home' ); ?>">Home</a></li>
		<?php wp_list_categories( 'orderby=name&title_li=&depth=2' ); ?>
	</ul>
</div><!-- /catnav -->