<?php
/**
 * @package WordPress
 * @subpackage Titan
 */

$content_width = 497;

//Set language folder and load textdomain
if ( file_exists( STYLESHEETPATH . '/languages' ) ) {
	$language_folder = ( STYLESHEETPATH . '/languages' );
} else {
	$language_folder = ( TEMPLATEPATH . '/languages' );
	load_theme_textdomain( 'titan', $language_folder );
}

//Add support for post thumbnails (added in 2.9)
if ( function_exists( 'add_theme_support' ) )
	add_theme_support( 'post-thumbnails' );

// Required functions
if ( is_file( STYLESHEETPATH . '/functions/comments.php' ) )
	require_once( STYLESHEETPATH . '/functions/comments.php' );
else
	require_once( TEMPLATEPATH . '/functions/comments.php' );

if ( is_file( STYLESHEETPATH . '/functions/titan-extend.php' ) )
	require_once( STYLESHEETPATH . '/functions/titan-extend.php' );
else
	require_once( TEMPLATEPATH . '/functions/titan-extend.php' );

// Enable widgets
if ( function_exists( 'register_sidebar_widget' ) ) {
	register_sidebar(array(
		'name'=> __( 'Sidebar', 'titan' ),
		'id' => 'normal_sidebar',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2 class="widgettitle">',
		'after_title' => '</h2>',
	) );
}

if ( function_exists( 'register_sidebar_widget' ) ) {
	register_sidebar(array(
		'name'=> __( 'Footer', 'vigilance' ),
		'id' => 'footer_sidebar',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2 class="widgettitle">',
		'after_title' => '</h2>',
	) );
}

?>