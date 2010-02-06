<?php
class TestWP_Scripts extends WPTestCase {
	var $old_wp_scripts;
	
	function setUp() {
		parent::setUp();
		$this->old_wp_scripts = $GLOBALS['wp_scripts'];
		remove_action( 'wp_default_scripts', 'wp_default_scripts' );
		$GLOBALS['wp_scripts'] = new WP_Scripts();
		$GLOBALS['wp_scripts']->default_version = get_bloginfo( 'version' );
		
	}
	
	function tearDown() {
		$GLOBALS['wp_scripts'] = $this->old_wp_scripts;
		add_action( 'wp_default_scripts', 'wp_default_scripts' );
		parent::tearDown();
	}
	
	// Test versioning
	function test_wp_enqueue_script() {
		$this->knownWPBug(11315);
		wp_enqueue_script('no-deps-no-version', 'example.com', array());
		wp_enqueue_script('empty-deps-no-version', 'example.com' );
		wp_enqueue_script('empty-deps-version', 'example.com', array(), 1.2);
		wp_enqueue_script('empty-deps-null-version', 'example.com', array(), null);
		$ver = get_bloginfo( 'version' );
		$expected = "<script type='text/javascript' src='http://example.com?ver=$ver'></script>
<script type='text/javascript' src='http://example.com?ver=$ver'></script>
<script type='text/javascript' src='http://example.com?ver=1.2'></script>
<script type='text/javascript' src='http://example.com'></script>
";
		$this->assertEquals($expected, get_echo('wp_print_scripts'));

		// No scripts left to print
		$this->assertEquals("", get_echo('wp_print_scripts'));
	}
}
?>