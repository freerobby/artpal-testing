<?php
// Test functions that fetch stuff from the theme directory
class TestPageTemplates extends _WPEmptyBlog {
	function setUp() {
		parent::setUp();
		$this->theme_root = realpath(DIR_TESTROOT.'/'.DIR_TESTDATA.'/themedir1');

		add_filter('theme_root', array(&$this, '_theme_root'));
		
		// clear caches
		unset($GLOBALS['wp_themes']);
		unset($GLOBALS['wp_broken_themes']);
		
	}

	function tearDown() {
		remove_filter('theme_root', array(&$this, '_theme_root'));
		parent::tearDown();
	}

	// replace the normal theme root dir with our premade test dir
	function _theme_root($dir) {
		return $this->theme_root;
	}
	
	function test_page_templates() {
		$this->knownWPBug(10959);
		$themes = get_themes();

		$theme = $themes['Page Template Theme'];
		$this->assertFalse( empty($theme) );

		switch_theme($theme['Template'], $theme['Stylesheet']);
		
		$templates = get_page_templates();
		$this->assertEquals(1 , count($templates));
		$this->assertEquals("template-top-level.php", $templates['Top Level']);
	}

	function test_page_templates_subdir() {
		$this->knownWPBug(11216);
		$themes = get_themes();

		$theme = $themes['Page Template Theme'];
		$this->assertFalse( empty($theme) );

		switch_theme($theme['Template'], $theme['Stylesheet']);
		
		$templates = get_page_templates();
		$this->assertEquals(2 , count($templates));
		$this->assertEquals("template-top-level.php", $templates['Top Level']);
		$this->assertEquals("subdir/template-sub-dir.php", $templates['Sub Dir']);
	}
}
?>