<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML Basic 1.1//EN" "http://www.w3.org/TR/xhtml-basic/xhtml-basic11.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title>
<?php 
	if ( isset($_GET['pages-list']) )
		echo __('Pages') . ' | ';
	elseif ( isset($_GET['archives-list']) )
		echo __('Archives') . ' | ';
	else
		wp_title(' | ',true,'right');

	bloginfo('name');
?>
</title>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<?php wp_head(); ?>
</head>

<body>

<h1><?php bloginfo('name'); ?></h1>

<hr />
<p>
	<?php
		if ( '/' == $_SERVER['REQUEST_URI'] )
			echo __('Home');
		else
			echo '<a accesskey="'.$GLOBALS['accesskey'].'" href="' . get_bloginfo('home') . '">' . __('Home') . '</a>';
		$num_pages = wp_count_posts('page');
		switch ( $num_pages->publish ) {
			case 0:
				break;
			case 1:
				echo ' | ';
				$pages = get_posts('post_type=page');
				$page_ID = $pages[0]->ID;
				if ( is_page($page_ID) )
					echo get_the_title($page_ID);
				else { 
					echo '<a accesskey="'.$GLOBALS['accesskey'].'" href="' . get_permalink($page_ID) . '">' . get_the_title($page_ID) . '</a>';
					$GLOBALS['accesskey']++;
				}
				break;
			default:
				echo ' | ';
				if ( isset($_GET['pages-list']) )
					_e('Pages');
				else { 
					echo '<a accesskey="'.$GLOBALS['accesskey'].'" href="' . get_bloginfo('home') . '?pages-list">' . __('Pages') . '</a>';
					$GLOBALS['accesskey']++;
				}
				break;
		}

		echo ' | ';
		if ( 1 == $_GET['archives-list'] )
			_e('Archives');
		else {
			echo '<a accesskey="'.$GLOBALS['accesskey'].'" href="' . get_bloginfo('home') . '?archives-list=1">' . __('Archives') . '</a>';
			$GLOBALS['accesskey']++;
		}
	?>
</p>

<hr />
