<?php

// test wp-includes/theme.php


class TestDefaultThemes extends WPTestCase {
	function setUp() {
		parent::setUp();
		unset($GLOBALS['wp_themes']);
		unset($GLOBALS['wp_broken_themes']);
	}

	function test_get_themes_default() {
		$themes = get_themes();

		// two themes are included by default: Classic and Default
		$this->assertTrue(is_array($themes['WordPress Classic']));
		if (TEST_MU) {
			$this->assertTrue(is_array($themes['WordPress mu Default']));
			$this->assertTrue(is_array($themes['WordPress mu Default/home']));
		}
		else
			$this->assertTrue(is_array($themes['WordPress Default']));
	}

	function test_get_themes_contents() {
		$themes = get_themes();

		// Generic tests that should hold true for any theme

		foreach ($themes as $k=>$theme) {
			$this->assertEquals($theme['Name'], $k);
			$this->assertTrue(!empty($theme['Title']));

			// important attributes should all be set
			$default_headers = array( 
				'Title' => 'Theme Title', 
				'Version' => 'Version', 
				'Parent Theme' => 'Parent Theme', 
				'Template Dir' => 'Template Dir', 
				'Stylesheet Dir' => 'Stylesheet Dir',
				'Template' => 'Template', 
				'Stylesheet' => 'Stylesheet', 
				'Screenshot' => 'Screenshot', 
				'Description' => 'Description',
				'Author' => 'Author',
				'Tags' => 'Tags'
//	Introduced in WordPress 2.9 so tests commented out for now
//				'Theme Root' => 'Theme Root',
//				'Theme Root URI' => 'Theme Root URI'
				);
			foreach ($default_headers as $name => $value) {
				$this->assertTrue(isset($theme[$name]));
			}

			// Make the tests work both for WordPress 2.8.5 and WordPress 2.9-rare 
			$dir = isset($theme['Theme Root']) ? '' : WP_CONTENT_DIR;

			// important attributes should all not be empty as well
			$this->assertTrue(!empty($theme['Description']));
			$this->assertTrue(!empty($theme['Author']));
			$this->assertTrue(is_numeric($theme['Version']));
			$this->assertTrue(!empty($theme['Template']));
			$this->assertTrue(!empty($theme['Stylesheet']));
			
			// template files should all exist
			$this->assertTrue(is_array($theme['Template Files']));
			$this->assertTrue(count($theme['Template Files']) > 0);
			foreach ($theme['Template Files'] as $file) {
				$this->assertTrue(is_file($dir . $file));
				$this->assertTrue(is_readable($dir . $file));
			}

			// css files should all exist
			$this->assertTrue(is_array($theme['Stylesheet Files']));
			$this->assertTrue(count($theme['Stylesheet Files']) > 0);
			foreach ($theme['Stylesheet Files'] as $file) {
				$this->assertTrue(is_file($dir . $file));
				$this->assertTrue(is_readable($dir . $file));
			}

			$this->assertTrue(is_dir($dir . $theme['Template Dir']));
			$this->assertTrue(is_dir($dir . $theme['Stylesheet Dir']));

			$this->assertEquals('publish', $theme['Status']);

			$this->assertTrue(is_file($dir . $theme['Stylesheet Dir'] . '/' . $theme['Screenshot']));
			$this->assertTrue(is_readable($dir . $theme['Stylesheet Dir'] . '/' . $theme['Screenshot']));
		}
	}

	function test_get_theme() {
		$themes = get_themes();

		foreach (array_keys($themes) as $name) {
			$theme = get_theme($name);
			$this->assertTrue(is_array($theme));
			$this->assertEquals($theme, $themes[$name]);
		}
	}

	function test_switch_theme() {

		$themes = get_themes();

		// switch to each theme in sequence
		// do it twice to make sure we switch to the first theme, even if it's our starting theme
		for ($i=0; $i<2; $i++) {
			foreach ($themes as $name=>$theme) {
				// switch to this theme
				switch_theme($theme['Template'], $theme['Stylesheet']);

				$this->assertEquals($name, get_current_theme());

				// make sure the various get_* functions return the correct values
				$this->assertEquals($theme['Template'], get_template());
				$this->assertEquals($theme['Stylesheet'], get_stylesheet());
				
				$root_fs = get_theme_root();
				$this->assertTrue(is_dir($root_fs));

				$root_uri = get_theme_root_uri();
				$this->assertTrue(!empty($root_uri));

				$this->assertEquals($root_fs . '/' . get_stylesheet(), get_stylesheet_directory());
				$this->assertEquals($root_uri . '/' . get_stylesheet(), get_stylesheet_directory_uri());
				$this->assertEquals($root_uri . '/' . get_stylesheet() . '/style.css', get_stylesheet_uri());
#				$this->assertEquals($root_uri . '/' . get_stylesheet(), get_locale_stylesheet_uri());

				$this->assertEquals($root_fs . '/' . get_template(), get_template_directory());
				$this->assertEquals($root_uri . '/' . get_template(), get_template_directory_uri());

				//get_query_template

				// template file that doesn't exist
				$this->assertEquals('', get_query_template(rand_str()));

				// template files that do exist
				foreach ($theme['Template Files'] as $path) {
					$file = basename($path, '.php');
					// FIXME: untestable because get_query_template uses TEMPLATEPATH
					$this->assertEquals('', get_query_template($file));
				}

				// these are kind of tautologies but at least exercise the code
				$this->assertEquals(get_404_template(), get_query_template('404'));
				$this->assertEquals(get_archive_template(), get_query_template('archive'));
				$this->assertEquals(get_author_template(), get_query_template('author'));
				$this->assertEquals(get_category_template(), get_query_template('category'));
				$this->assertEquals(get_date_template(), get_query_template('date'));
				$this->assertEquals(get_home_template(), get_query_template('home'));
				$this->assertEquals(get_page_template(), get_query_template('page'));
				$this->assertEquals(get_paged_template(), get_query_template('paged'));
				$this->assertEquals(get_search_template(), get_query_template('search'));
				$this->assertEquals(get_single_template(), get_query_template('single'));
				$this->assertEquals(get_attachment_template(), get_query_template('attachment'));

				// this one doesn't behave like the others
				if (get_query_template('comments-popup'))
					$this->assertEquals(get_comments_popup_template(), get_query_template('comments-popup'));
				else
					$this->assertEquals(get_comments_popup_template(), ABSPATH.'wp-content/themes/default/comments-popup.php');

				// not in MU?
				if (is_callable('get_tag_template'))
					$this->assertEquals(get_tag_template(), get_query_template('tag'));

				// nb: this probably doesn't run because WP_INSTALLING is defined
				$this->assertTrue(validate_current_theme());
			}
		}
	}

	function test_switch_theme_bogus() {
		// try switching to a theme that doesn't exist

		$template = rand_str();
		$style = rand_str();
		update_option('template', $template);
		update_option('stylesheet', $style);

		$this->assertEquals('WordPress Default', get_current_theme());

		// these return the bogus name - perhaps not ideal behaviour?
		$this->assertEquals($template, get_template());
		$this->assertEquals($style, get_stylesheet());
	}
}

include_once(DIR_TESTDATA . '/sample_blogs.php');

// Test functions that fetch stuff from the theme directory
class TestThemeDir extends _WPEmptyBlog {
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
	
	function test_theme_default() {
		$themes = get_themes();

		$theme = $themes['WordPress Default'];
		$this->assertFalse( empty($theme) );

		#echo gen_tests_array('theme', $theme);

		$this->assertEquals( 'WordPress Default', $theme['Name'] );
		$this->assertEquals( 'WordPress Default', $theme['Title'] );
		$this->assertEquals( 'The default WordPress theme based on the famous <a href="http://binarybonsai.com/kubrick/">Kubrick</a>.', $theme['Description'] );
		$this->assertEquals( '<a href="http://binarybonsai.com/" title="Visit author homepage">Michael Heilemann</a>', $theme['Author'] );
		$this->assertEquals( '1.6', $theme['Version'] );
		$this->assertEquals( 'default', $theme['Template'] );
		$this->assertEquals( 'default', $theme['Stylesheet'] );
		$this->assertEquals( $this->theme_root.'/default/functions.php', $theme['Template Files'][0] );
		$this->assertEquals( $this->theme_root.'/default/index.php', $theme['Template Files'][1] );

		$this->assertEquals( $this->theme_root.'/default/style.css', $theme['Stylesheet Files'][0] );

		$this->assertEquals( $this->theme_root.'/default', $theme['Template Dir'] );
		$this->assertEquals( $this->theme_root.'/default', $theme['Stylesheet Dir'] );
		$this->assertEquals( 'publish', $theme['Status'] );
		$this->assertEquals( '', $theme['Parent Theme'] );

	}

	function test_theme_sandbox() {
		$themes = get_themes();

		$theme = $themes['Sandbox'];
		$this->assertFalse( empty($theme) );

		#echo gen_tests_array('theme', $theme);
		
		$this->assertEquals( 'Sandbox', $theme['Name'] );
		$this->assertEquals( 'Sandbox', $theme['Title'] );
		$this->assertEquals( 'A theme with powerful, semantic CSS selectors and the ability to add new skins.', $theme['Description'] );
		$this->assertEquals( '<a href="http://andy.wordpress.com/">Andy Skelton</a> &amp; <a href="http://www.plaintxt.org/">Scott Allan Wallick</a>', $theme['Author'] );
		$this->assertEquals( '0.6.1-wpcom', $theme['Version'] );
		$this->assertEquals( 'sandbox', $theme['Template'] );
		$this->assertEquals( 'sandbox', $theme['Stylesheet'] );
		$this->assertEquals( $this->theme_root.'/sandbox/functions.php', $theme['Template Files'][0] );
		$this->assertEquals( $this->theme_root.'/sandbox/index.php', $theme['Template Files'][1] );
		
		$this->assertEquals( $this->theme_root.'/sandbox/style.css', $theme['Stylesheet Files'][0] );
		
		$this->assertEquals( $this->theme_root.'/sandbox', $theme['Template Dir'] );
		$this->assertEquals( $this->theme_root.'/sandbox', $theme['Stylesheet Dir'] );
		$this->assertEquals( 'publish', $theme['Status'] );
		$this->assertEquals( '', $theme['Parent Theme'] );
		
	}

	// a css only theme
	function test_theme_stylesheet_only() {
		$themes = get_themes();

		$theme = $themes['Stylesheet Only'];
		$this->assertFalse( empty($theme) );

		#echo gen_tests_array('theme', $theme);
		
		$this->assertEquals( 'Stylesheet Only', $theme['Name'] );
		$this->assertEquals( 'Stylesheet Only', $theme['Title'] );
		$this->assertEquals( 'A three-column widget-ready theme in dark blue.', $theme['Description'] );
		$this->assertEquals( '<a href="http://www.example.com/" title="Visit author homepage">Henry Crun</a>', $theme['Author'] );
		$this->assertEquals( '1.0', $theme['Version'] );
		$this->assertEquals( 'sandbox', $theme['Template'] );
		$this->assertEquals( 'stylesheetonly', $theme['Stylesheet'] );
		$this->assertEquals( $this->theme_root.'/sandbox/functions.php', $theme['Template Files'][0] );
		$this->assertEquals( $this->theme_root.'/sandbox/index.php', $theme['Template Files'][1] );

		$this->assertEquals( $this->theme_root.'/stylesheetonly/style.css', $theme['Stylesheet Files'][0] );
		
		$this->assertEquals( $this->theme_root.'/sandbox', $theme['Template Dir'] );
		$this->assertEquals( $this->theme_root.'/stylesheetonly', $theme['Stylesheet Dir'] );
		$this->assertEquals( 'publish', $theme['Status'] );
		$this->assertEquals( 'Sandbox', $theme['Parent Theme'] );
		
	}

	function test_theme_list() {
		$themes = get_themes();
		$theme_names = array_keys($themes);
		$expected = array(
			'WordPress Default',
			'Sandbox',
			'Stylesheet Only',
			'My Theme',
			'My Theme/theme1', // duplicate theme should be given a unique name
			'My Subdir Theme',// theme in a subdirectory should work
			'Page Template Theme', // theme with page templates for other test code
		);
		
		sort($theme_names);
		sort($expected);

		$this->assertEquals($expected, $theme_names);
	}
	
	function test_broken_themes() {
		global $wp_broken_themes;
		$themes = get_themes();
		$expected = array('broken-theme' => array('Name' => 'broken-theme', 'Title' => 'broken-theme', 'Description' => __('Stylesheet is missing.')));
		
		$this->assertEquals($expected, $wp_broken_themes);
	}
	
	function test_page_templates() {
		$themes = get_themes();
		
		$theme = $themes['Page Template Theme'];
		$this->assertFalse( empty($theme) );
				
		$templates = $theme['Template Files'];
		$this->assertEquals( 3, count( $templates ) );
		$this->assertTrue( in_array( $this->theme_root . '/page-templates/template-top-level.php', $templates));
		$this->assertTrue( in_array( $this->theme_root . '/page-templates/subdir/template-sub-dir.php', $templates));
	}
}
class TestLargeThemeDir extends _WPEmptyBlog {
	
	function setUp() {
		parent::setUp();
		$this->theme_root = realpath(DIR_TESTROOT.'/'.DIR_TESTDATA.'/wpcom-themes');

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
	function test_theme_list() {
		$themes = get_themes();
		$theme_names = array_keys($themes);
		$this->assertEquals(87, count($theme_names));
		//2.9 pre [12226]
		$this->assertLessThanOrEqual(387283, strlen(serialize($themes)));
		//2.8.5
		$this->assertLessThanOrEqual(368319, strlen(serialize($themes)));
		//2.9 post [12226]
		$this->assertLessThanOrEqual(261998, strlen(serialize($themes)));
	}
}
?>
