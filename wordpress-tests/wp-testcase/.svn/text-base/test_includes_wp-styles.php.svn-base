<?php
class TestWP_Styles extends WPTestCase {
	var $old_wp_styles;
	
	function setUp() {
		parent::setUp();
		$this->old_wp_styles = $GLOBALS['wp_styles'];
		remove_action( 'wp_default_styles', 'wp_default_styles' );
		$GLOBALS['wp_styles'] = new WP_Styles();
		$GLOBALS['wp_styles']->default_version = get_bloginfo( 'version' );
		
	}
	
	function tearDown() {
		$GLOBALS['wp_styles'] = $this->old_wp_styles;
		add_action( 'wp_default_styles', 'wp_default_styles' );
		parent::tearDown();
	}
	
	// Test versioning
	function test_wp_enqueue_style() {
		$this->knownWPBug(11315);
		wp_enqueue_style('no-deps-no-version', 'example.com', array());
		wp_enqueue_style('empty-deps-no-version', 'example.com' );
		wp_enqueue_style('empty-deps-version', 'example.com', array(), 1.2);
		wp_enqueue_style('empty-deps-null-version', 'example.com', array(), null);
		$ver = get_bloginfo( 'version' );
		$expected = "<link rel='stylesheet' id='no-deps-no-version-css'  href='http://example.com?ver=$ver' type='text/css' media='' />
<link rel='stylesheet' id='empty-deps-no-version-css'  href='http://example.com?ver=$ver' type='text/css' media='' />
<link rel='stylesheet' id='empty-deps-version-css'  href='http://example.com?ver=1.2' type='text/css' media='' />
<link rel='stylesheet' id='empty-deps-null-version-css'  href='http://example.com' type='text/css' media='' />
";
		$this->assertEquals($expected, get_echo('wp_print_styles'));

		// No styles left to print
		$this->assertEquals("", get_echo('wp_print_styles'));
	}
}
?>