<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php wp_title(); ?> <?php bloginfo('name'); ?></title>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<!--[if lt IE 7]>		
		<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/ie6.css" media="screen" />	
	<![endif]-->
	<!--[if IE 7]>		
		<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/ie7.css" media="screen" />	
	<![endif]-->
	<!--[if IE 8]>		
		<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/ie8.css" media="screen" />	
	<![endif]-->

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<div id="wrapper">
	
	<p id="skiptocontent"><a href="#content">Skip to content</a></p>
	
	<div id="masthead">
	
		<ul id="navigation">
			<li class="<?php if ( is_front_page() ) { echo 'current_page_item'; } else { echo 'page_item'; } ?>">
				<a href="<?php echo get_option('home'); ?>">Homepage</a>
			</li>
			<?php wp_list_pages('title_li=&depth=1'); ?>
		</ul>
		
		<h1><a href="<?php echo get_option('home'); ?>"><?php bloginfo('title'); ?></a></h1>
		
	</div><!-- masthead -->
	
	<div id="subhead">		
		
		<?php get_search_form(); ?>
		
		<blockquote>
			
			<p><span class="quote">&ldquo;</span><?php bloginfo('description'); ?><span class="quote">&rdquo;</span></p>
			
		</blockquote>
		
	</div><!-- subhead -->

