<?php

// extend these classes to start with common test cases
// don't forget to call parent::setUp() and parent::tearDown() if you override them


// an empty blog

class _WPEmptyBlog extends WPTestCase {
	var $post_ids = array();
	var $_setup_check = false;

	function setUp() {
		parent::setUp();
		$this->author = get_userdatabylogin(WP_USER_NAME);
		$this->_delete_all_posts();
		update_option('home', 'http://example.com');
		update_option('siteurl', 'http://example.com');
		delete_option('permalink_structure');
		// clear out some caching stuff that's likely to cause unexpected results
		unset($GLOBALS['cache_lastpostmodified']);
		$this->_setup_check = true;
	}

	function test_setup_check() {
		// it's easy to forget to call parent::setUp() when extending a base test class
		// this checks to make sure the base class setUp() was run
		$this->assertTrue($this->_setup_check, get_class($this).' did not call parent::setUp()');
	}


}

// a blog with 10 posts
class _WPSmallBlog extends _WPEmptyBlog {
	function setUp() {
		parent::setUp();
		$this->_insert_quick_posts(10);
	}
}

// a blog with 25 posts and 5 pages
class _WPMediumBlog extends _WPEmptyBlog {
	function setUp() {
		parent::setUp();
		$this->_insert_quick_posts(25);
	}
}

// a quick way to construct a blog from a WXR export file
class _WPImportBlog extends _WPEmptyBlog {
	var $import_filename = NULL;

	function setUp() {
		parent::setUp();
		include_once(ABSPATH.'/wp-admin/import/wordpress.php');
		$this->_import_wp(DIR_TESTDATA.'/'.$this->import_filename);
	}
}

// a faster way to get the asdftestblog1 dataset than using the importer
class _WPDataset1 extends _WPEmptyBlog {
	
	function setUp() {
		$_start = time();
		parent::setUp();

		$this->_nuke_main_tables();
			
		$this->_load_sql_dump(DIR_TESTDATA.'/export/asdftestblog1.2007-11-23.sql');
	}
	
	function tearDown() {
		$_start = time();
		$this->_nuke_main_tables();
		parent::tearDown();
		if ($id = get_profile('ID', 'User A'))
			wp_delete_user($id);
		if ($id = get_profile('ID', 'User B'))
			wp_delete_user($id);
	}
	
	function _nuke_main_tables() {
		global $wpdb;
		
		// crude but effective: make sure there's no residual data in the main tables
		foreach ( array('posts', 'postmeta', 'comments', 'terms', 'term_taxonomy', 'term_relationships', 'users', 'usermeta') as $table)
			$wpdb->query("DELETE FROM {$wpdb->$table}");
	}
	
}

?>
