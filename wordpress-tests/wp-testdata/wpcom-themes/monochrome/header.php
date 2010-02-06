<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php wp_title(); ?> <?php bloginfo('name'); ?></title>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />

<?php if (strtoupper(get_locale()) == 'JA') ://to fix the font-size for japanese font ?>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/japanese.css" type="text/css" media="screen" />
<?php endif; ?>
<!--[if lt IE 7]>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/ie6.css" type="text/css" media="screen" />
<![endif]--> 
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php 
if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
wp_head(); 
?>

</head>

<body <?php body_class(); ?>>
<div id="wrapper">

 <div id="header">

  <div id="header_top"> 
   <div id="logo">
    <a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a>
    <h1><?php bloginfo('description'); ?></h1>
   </div>
   <div id="header_menu">
    <ul class="menu" id="menu">
     <li class="<?php if (!is_paged() && is_home()) { ?>current_page_item<?php } else { ?>page_item<?php } ?>"><a href="<?php echo get_settings('home'); ?>/"><?php _e('HOME','monochrome'); ?></a></li>
     <?php
         $options = get_option('mc_options');
         if($options['header_menu_type'] == 'pages') {
         wp_list_pages('sort_column=menu_order&depth=0&title_li=&exclude=' . $options['exclude_pages']);
         } else {
         wp_list_categories('depth=0&title_li=&exclude=' . $options['exclude_category']);
         }
     ?>
    </ul>
   </div>
  </div>

  </div><!-- #header end -->