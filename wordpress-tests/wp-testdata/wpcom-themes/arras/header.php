<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php arras_document_title() ?></title>
<meta name="description" content="<?php bloginfo('description') ?>" />

<?php arras_alternate_style() ?>

<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php
wp_enqueue_script('jquery-ui-tabs', null, array('jquery', 'jquery-ui-core'), null, false);

wp_enqueue_script('jquery-cycle', get_template_directory_uri() . '/js/jquery.cycle.all.min.js', 'jquery', null, false);
wp_enqueue_script('jquery-validate', get_template_directory_uri() . '/js/jquery.validate.min.js', 'jquery', null, false);

wp_enqueue_script('hoverintent', get_template_directory_uri() . '/js/superfish/hoverIntent.js', 'jquery', null, false);
wp_enqueue_script('superfish', get_template_directory_uri() . '/js/superfish/superfish.js', 'jquery', null, false);

if ( is_singular() ) {
	wp_enqueue_script('comment-reply');
}

wp_enqueue_script('arras-base', get_template_directory_uri() . '/js/base.js', 'jquery', null, false);

wp_head();

?>
<script type="text/javascript">
/* <![CDATA[ */
	jQuery(document).ready(function($) {
		$('.sf-menu').superfish({autoArrows: false, speed: 'fast'});
	});
/* ]]> */	
</script>

<!--[if IE 6]>
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/ie6.css" type="text/css" media="screen, projector" />
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/jquery.supersleight.min.js"></script>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$('.blog-name').supersleight( {shim: '<?php bloginfo('template_url') ?>/images/x.gif'} );
	$('#controls').supersleight( {shim: '<?php bloginfo('template_url') ?>/images/x.gif'} );
	$('.featured-article').supersleight( {shim: '<?php bloginfo('template_url') ?>/images/x.gif'} );
});
</script>
<![endif]-->
</head>

<body <?php body_class() ?>>
<div id="wrapper">

    <div id="header">
    	<div id="branding" class="clearfix">
        <div class="logo clearfix">
        	<?php if ( is_home() || is_front_page() ) : ?>
            <h1 class="blog-name"><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></h1>
            <h2 class="blog-description"><?php bloginfo('description'); ?></h2>
            <?php else: ?>
            <span class="blog-name"><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></span>
            <span class="blog-description"><?php bloginfo('description'); ?></span>
            <?php endif ?>
        </div>
        <div id="searchbar">
            <?php include (TEMPLATEPATH . '/searchform.php'); ?>  
        </div>
        </div><!-- #branding -->
    </div><!-- #header -->
	
    <div id="nav">
    	<div id="nav-content" class="clearfix">
			<ul class="sf-menu menu clearfix">
				<li><a href="<?php bloginfo('url') ?>"><?php echo arras_get_option('topnav_home') ?></a></li>
				<?php 
				if (arras_get_option('topnav_display') == 'pages') {
					wp_list_pages('sort_column=menu_order&title_li=');
				} else if (arras_get_option('topnav_display') == 'linkcat') {
					wp_list_bookmarks('category='.arras_get_option('topnav_linkcat').'&hierarchical=0&show_private=1&hide_invisible=0&title_li=&categorize=0&orderby=id'); 
				} else {
					wp_list_categories('number=11&hierarchical=1&orderby=id&hide_empty=1&title_li=');	
				}
				?>
			</ul>
			<ul class="rss clearfix">
					<li><a href="<?php bloginfo('rss2_url'); ?>"><?php _e('Posts', 'arras') ?></a></li>
					<li><a href="<?php bloginfo('comments_rss2_url'); ?>"><?php _e('Comments', 'arras') ?></a></li>
			</ul>
		</div><!-- #nav-content -->
    </div><!-- #nav -->
    
	<div id="main">
    <div id="container" class="clearfix">
