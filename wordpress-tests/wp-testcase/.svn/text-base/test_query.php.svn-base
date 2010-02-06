<?php

// test the is_*() functions in query.php across the URL structure

// this exercises both query.php and rewrite.php: urls are fed through the rewrite code,
// then we test the effects of each url on the wp_query object.

class TestWPQueryVars extends _WPDataset1 {
	var $use_verbose_page_rules = true;

	function setUp() {
		parent::setUp();
		global $wp_rewrite;
		$wp_rewrite->set_permalink_structure('/%year%/%monthnum%/%day%/%postname%/');
		$wp_rewrite->flush_rules();
		$wp_rewrite->use_verbose_page_rules = $this->use_verbose_page_rules;

	}

	function tearDown() {
		global $wp_rewrite;
		$wp_rewrite->set_permalink_structure('');
		parent::tearDown();
	}

	function _get_post_id_by_name($name) {
		global $wpdb;
		$name = $wpdb->escape($name);
		$page_id = $wpdb->get_var("SELECT ID from {$wpdb->posts} WHERE post_name = '{$name}' LIMIT 1");
		assert(is_numeric($page_id));
		return $page_id;
	}

	function _all_post_ids($type='post') {
		global $wpdb;
		$type = $wpdb->escape($type);
		return $wpdb->get_col("SELECT ID FROM {$wpdb->posts} WHERE post_type='{$type}' and post_status='publish'");
	}

	// this will check each of the wp_query is_* functions/properties
	// any that are listed by name as parameters will be asserted as True; any others will be asserted False
	// e.g. assertQueryTrue('is_single', 'is_feed') means is_single() and is_feed() must be true, and
	// everything else must be false
	function assertQueryTrue(/* .. */) {
		$all = array(
			'is_admin', 'is_archive', 'is_attachment', 'is_author', 'is_category', 'is_tag', 'is_comments_popup', 'is_date',
			'is_day', 'is_feed', 'is_home', 'is_month', 'is_page', 'is_paged', 'is_plugin_page', 'is_preview', 'is_robots',
			'is_search', 'is_single', 'is_time', 'is_trackback', 'is_year', 'is_404', 'is_comment_feed',
			);

		$true = func_get_args();

		global $wp_query;
		foreach ($all as $query_thing) {
			if (is_callable($query_thing))
				$result = call_user_func($query_thing);
			else
				$result = $wp_query->$query_thing;

			if (in_array($query_thing, $true))
				$this->assertTrue($result, "$query_thing should be true");
			else
				$this->assertFalse($result, "$query_thing should be false");
		}
	}

	function test_home() {
		$this->http('/');

		$this->assertQueryTrue('is_home');
	}

	function test_404() {
		$this->http('/'.rand_str());

		$this->assertQueryTrue('is_404');
	}

	function test_permalink() {
		$this->http( get_permalink($this->_get_post_id_by_name('hello-world')) );

		$this->assertQueryTrue('is_single', 'is_singular');
	}

	function test_post_comments_feed() {
		$this->http(get_post_comments_feed_link($this->_get_post_id_by_name('hello-world')));

		$this->assertQueryTrue('is_feed', 'is_single', 'is_singular', 'is_comment_feed');
	}

	function test_page() {
		$page_id = $this->_get_post_id_by_name('about');
		$this->http(get_permalink($page_id));

		$this->assertQueryTrue('is_page');
	}

	function test_parent_page() {
		$page_id = $this->_get_post_id_by_name('parent-page');
		$this->http(get_permalink($page_id));

		$this->assertQueryTrue('is_page');
	}

	function test_child_page_1() {
		$page_id = $this->_get_post_id_by_name('child-page-1');
		$this->http(get_permalink($page_id));

		$this->assertQueryTrue('is_page');
	}

	function test_child_page_2() {
		$page_id = $this->_get_post_id_by_name('child-page-2');
		$this->http(get_permalink($page_id));

		$this->assertQueryTrue('is_page');
	}

	// '(about)/trackback/?$' => 'index.php?pagename=$matches[1]&tb=1'
	function test_page_trackback() {
		$pages = array('about', 'lorem-ipsum', 'parent-page', 'child-page-1', 'child-page-2');
		foreach ($pages as $name) {
			$page_id = $this->_get_post_id_by_name($name);
			$url = get_permalink($page_id);
			$this->http("{$url}trackback/");

			// make sure the correct wp_query flags are set
			$this->assertQueryTrue('is_page', 'is_trackback');

			// make sure the correct page was fetched
			global $wp_query;
			$this->assertEquals( $page_id, $wp_query->get_queried_object()->ID );
		}
	}

	//'(about)/feed/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?pagename=$matches[1]&feed=$matches[2]'
	function test_page_feed() {
		$pages = array('about', 'lorem-ipsum', 'parent-page', 'child-page-1', 'child-page-2');
		foreach ($pages as $name) {
			$page_id = $this->_get_post_id_by_name($name);
			$url = get_permalink($page_id);
			$this->http("{$url}feed/");

			// make sure the correct wp_query flags are set
			$this->assertQueryTrue('is_page', 'is_feed', 'is_comment_feed');

			// make sure the correct page was fetched
			global $wp_query;
			$this->assertEquals( $page_id, $wp_query->get_queried_object()->ID );
		}

	}

	// '(about)/feed/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?pagename=$matches[1]&feed=$matches[2]'
	function test_page_feed_atom() {
		$pages = array('about', 'lorem-ipsum', 'parent-page', 'child-page-1', 'child-page-2');
		foreach ($pages as $name) {
			$page_id = $this->_get_post_id_by_name($name);
			$url = get_permalink($page_id);
			$this->http("{$url}feed/atom/");

			// make sure the correct wp_query flags are set
			$this->assertQueryTrue('is_page', 'is_feed', 'is_comment_feed');

			// make sure the correct page was fetched
			global $wp_query;
			$this->assertEquals( $page_id, $wp_query->get_queried_object()->ID );
		}
	}

	// '(about)/page/?([0-9]{1,})/?$' => 'index.php?pagename=$matches[1]&paged=$matches[2]'
	function test_page_page_2() {
		$pages = array('about', 'lorem-ipsum', 'parent-page', 'child-page-1', 'child-page-2');
		foreach ($pages as $name) {
			$page_id = $this->_get_post_id_by_name($name);
			$url = get_permalink($page_id);
			$this->http("{$url}page/2/");

			// make sure the correct wp_query flags are set
			$this->assertQueryTrue('is_page', 'is_paged');

			// make sure the correct page was fetched
			global $wp_query;
			$this->assertEquals( $page_id, $wp_query->get_queried_object()->ID );
		}
	}

	// FIXME: what is this for?
	// '(about)(/[0-9]+)?/?$' => 'index.php?pagename=$matches[1]&page=$matches[2]'
	function test_page_page_2_short() {
		return $this->markTestSkipped();
		// identical to /about/page/2/ ?
		$this->http('/about/2/');

		$this->assertQueryTrue('is_page', 'is_paged');
	}

	// FIXME: no tests for these yet
	// 'about/attachment/([^/]+)/?$' => 'index.php?attachment=$matches[1]',
	// 'about/attachment/([^/]+)/trackback/?$' => 'index.php?attachment=$matches[1]&tb=1',
	// 'about/attachment/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?attachment=$matches[1]&feed=$matches[2]',
	// 'about/attachment/([^/]+)/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?attachment=$matches[1]&feed=$matches[2]',

	// 'feed/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?&feed=$matches[1]',
	// '(feed|rdf|rss|rss2|atom)/?$' => 'index.php?&feed=$matches[1]',
	function test_main_feed_2() {
		$feeds = array('feed', 'rdf', 'rss', 'rss2', 'atom');

		// long version
		foreach ($feeds as $feed) {
			$this->http("/feed/{$feed}/");

			$this->assertQueryTrue('is_feed');
		}

		// short version
		foreach ($feeds as $feed) {
			$this->http("/{$feed}/");

			$this->assertQueryTrue('is_feed');
		}

	}

	function test_main_feed() {

		$types = array('rss2', 'rss', 'atom');
		foreach ($types as $type) {
			$this->http(get_feed_link($type));
			$this->assertQueryTrue('is_feed');
		}
	}


	// 'page/?([0-9]{1,})/?$' => 'index.php?&paged=$matches[1]',
	function test_paged() {
		for ($i=1; $i<4; $i++) {
			$this->http("/page/{$i}/");
			$this->assertQueryTrue('is_home', 'is_paged');
		}
	}

	// 'comments/feed/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?&feed=$matches[1]&withcomments=1',
	// 'comments/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?&feed=$matches[1]&withcomments=1',
	function test_main_comments_feed() {
		// check the url as generated by get_post_comments_feed_link()
		$this->http(get_post_comments_feed_link($this->_get_post_id_by_name('hello-world')));
		$this->assertQueryTrue('is_feed', 'is_single', 'is_singular', 'is_comment_feed');

		// check the long form
		$types = array('feed', 'rdf', 'rss', 'rss2', 'atom');
		foreach ($types as $type) {
				$this->http("/comments/feed/{$type}");
				$this->assertQueryTrue('is_feed', 'is_comment_feed');
		}

		// check the short form
		$types = array('feed', 'rdf', 'rss', 'rss2', 'atom');
		foreach ($types as $type) {
				$this->http("/comments/{$type}");
				$this->assertQueryTrue('is_feed', 'is_comment_feed');
		}

	}

	// 'comments/page/?([0-9]{1,})/?$' => 'index.php?&paged=$matches[1]',
	function test_comments_page() {
		$this->http('/comments/page/2/');
		$this->assertQueryTrue('is_home', 'is_paged');
	}


	// 'search/(.+)/feed/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?s=$matches[1]&feed=$matches[2]',
	// 'search/(.+)/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?s=$matches[1]&feed=$matches[2]',
	function test_search_feed() {
		// check the long form
		$types = array('feed', 'rdf', 'rss', 'rss2', 'atom');
		foreach ($types as $type) {
				$this->http("/search/test/feed/{$type}");
				$this->assertQueryTrue('is_feed', 'is_search');
		}

		// check the short form
		$types = array('feed', 'rdf', 'rss', 'rss2', 'atom');
		foreach ($types as $type) {
				$this->http("/search/test/{$type}");
				$this->assertQueryTrue('is_feed', 'is_search');
		}
	}

	// 'search/(.+)/page/?([0-9]{1,})/?$' => 'index.php?s=$matches[1]&paged=$matches[2]',
	function test_search_paged() {
		$this->http('/search/test/page/2/');
		$this->assertQueryTrue('is_search', 'is_paged');
	}

	// 'search/(.+)/?$' => 'index.php?s=$matches[1]',
	function test_search() {
		$this->http('/search/test/');
		$this->assertQueryTrue('is_search');
	}

	// 'category/(.+?)/feed/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?category_name=$matches[1]&feed=$matches[2]',
	// 'category/(.+?)/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?category_name=$matches[1]&feed=$matches[2]',
	function test_category_feed() {
		// check the long form
		$types = array('feed', 'rdf', 'rss', 'rss2', 'atom');
		foreach ($types as $type) {
				$this->http("/category/cat-a/feed/{$type}");
				$this->assertQueryTrue('is_archive', 'is_feed', 'is_category');
		}

		// check the short form
		$types = array('feed', 'rdf', 'rss', 'rss2', 'atom');
		foreach ($types as $type) {
				$this->http("/category/cat-a/{$type}");
				$this->assertQueryTrue('is_archive', 'is_feed', 'is_category');
		}
	}

	// 'category/(.+?)/page/?([0-9]{1,})/?$' => 'index.php?category_name=$matches[1]&paged=$matches[2]',
	function test_category_paged() {
		$this->http('/category/cat-a/page/1/');
		$this->assertQueryTrue('is_archive', 'is_category', 'is_paged');
	}

	// 'category/(.+?)/?$' => 'index.php?category_name=$matches[1]',
	function test_category() {
		$this->http('/category/cat-a/');
		$this->assertQueryTrue('is_archive', 'is_category');
	}

	// 'tag/(.+?)/feed/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?tag=$matches[1]&feed=$matches[2]',
	// 'tag/(.+?)/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?tag=$matches[1]&feed=$matches[2]',
	function test_tag_feed() {
		// check the long form
		$types = array('feed', 'rdf', 'rss', 'rss2', 'atom');
		foreach ($types as $type) {
				$this->http("/tag/tag-a/feed/{$type}");
				$this->assertQueryTrue('is_archive', 'is_feed', 'is_tag');
		}

		// check the short form
		$types = array('feed', 'rdf', 'rss', 'rss2', 'atom');
		foreach ($types as $type) {
				$this->http("/tag/tag-a/{$type}");
				$this->assertQueryTrue('is_archive', 'is_feed', 'is_tag');
		}
	}

	// 'tag/(.+?)/page/?([0-9]{1,})/?$' => 'index.php?tag=$matches[1]&paged=$matches[2]',
	function test_tag_paged() {
		$this->http('/tag/tag-a/page/1/');
		$this->assertQueryTrue('is_archive', 'is_tag', 'is_paged');
	}

	// 'tag/(.+?)/?$' => 'index.php?tag=$matches[1]',
	function test_tag() {
		$this->http('/tag/tag-a/');
		$this->assertQueryTrue('is_archive', 'is_tag');
	}

	// 'author/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?author_name=$matches[1]&feed=$matches[2]',
	// 'author/([^/]+)/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?author_name=$matches[1]&feed=$matches[2]',
	function test_author_feed() {
		// check the long form
		$types = array('feed', 'rdf', 'rss', 'rss2', 'atom');
		foreach ($types as $type) {
				$this->http("/author/user-a/feed/{$type}");
				$this->assertQueryTrue('is_archive', 'is_feed', 'is_author');
		}

		// check the short form
		$types = array('feed', 'rdf', 'rss', 'rss2', 'atom');
		foreach ($types as $type) {
				$this->http("/author/user-a/{$type}");
				$this->assertQueryTrue('is_archive', 'is_feed', 'is_author');
		}
	}

	// 'author/([^/]+)/page/?([0-9]{1,})/?$' => 'index.php?author_name=$matches[1]&paged=$matches[2]',
	function test_author_paged() {
		$this->http('/author/user-a/page/2/');
		$this->assertQueryTrue('is_archive', 'is_author', 'is_paged');
	}

	// 'author/([^/]+)/?$' => 'index.php?author_name=$matches[1]',
	function test_author() {
		$this->http('/author/user-a/');
		$this->assertQueryTrue('is_archive', 'is_author');
	}

	// '([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/feed/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&feed=$matches[4]',
	// '([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&feed=$matches[4]',
	function test_ymd_feed() {
		// check the long form
		$types = array('feed', 'rdf', 'rss', 'rss2', 'atom');
		foreach ($types as $type) {
				$this->http("/2007/09/04/feed/{$type}");
				$this->assertQueryTrue('is_archive', 'is_feed', 'is_day', 'is_date');
		}

		// check the short form
		$types = array('feed', 'rdf', 'rss', 'rss2', 'atom');
		foreach ($types as $type) {
				$this->http("/2007/09/04/{$type}");
				$this->assertQueryTrue('is_archive', 'is_feed', 'is_day', 'is_date');
		}
	}

	// '([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/page/?([0-9]{1,})/?$' => 'index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&paged=$matches[4]',
	function test_ymd_paged() {
		$this->http('/2007/09/04/page/2/');
		$this->assertQueryTrue('is_archive', 'is_day', 'is_date', 'is_paged');
	}

	// '([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/?$' => 'index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]',
	function test_ymd() {
		$this->http('/2007/09/04/');
		$this->assertQueryTrue('is_archive', 'is_day', 'is_date');
	}
	
	// '([0-9]{4})/([0-9]{1,2})/feed/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?year=$matches[1]&monthnum=$matches[2]&feed=$matches[3]',
	// '([0-9]{4})/([0-9]{1,2})/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?year=$matches[1]&monthnum=$matches[2]&feed=$matches[3]',
	function test_ym_feed() {
		// check the long form
		$types = array('feed', 'rdf', 'rss', 'rss2', 'atom');
		foreach ($types as $type) {
				$this->http("/2007/09/feed/{$type}");
				$this->assertQueryTrue('is_archive', 'is_feed', 'is_month', 'is_date');
		}

		// check the short form
		$types = array('feed', 'rdf', 'rss', 'rss2', 'atom');
		foreach ($types as $type) {
				$this->http("/2007/09/{$type}");
				$this->assertQueryTrue('is_archive', 'is_feed', 'is_month', 'is_date');
		}
	}

	// '([0-9]{4})/([0-9]{1,2})/page/?([0-9]{1,})/?$' => 'index.php?year=$matches[1]&monthnum=$matches[2]&paged=$matches[3]',
	function test_ym_paged() {
		$this->http('/2007/09/page/2/');
		$this->assertQueryTrue('is_archive', 'is_date', 'is_month', 'is_paged');
	}

	// '([0-9]{4})/([0-9]{1,2})/?$' => 'index.php?year=$matches[1]&monthnum=$matches[2]',
	function test_ym() {
		$this->http('/2007/09/');
		$this->assertQueryTrue('is_archive', 'is_date', 'is_month');
	}

	// '([0-9]{4})/feed/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?year=$matches[1]&feed=$matches[2]',
	// '([0-9]{4})/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?year=$matches[1]&feed=$matches[2]',
	function test_y_feed() {
		// check the long form
		$types = array('feed', 'rdf', 'rss', 'rss2', 'atom');
		foreach ($types as $type) {
				$this->http("/2007/feed/{$type}");
				$this->assertQueryTrue('is_archive', 'is_feed', 'is_year', 'is_date');
		}

		// check the short form
		$types = array('feed', 'rdf', 'rss', 'rss2', 'atom');
		foreach ($types as $type) {
				$this->http("/2007/{$type}");
				$this->assertQueryTrue('is_archive', 'is_feed', 'is_year', 'is_date');
		}
	}

	// '([0-9]{4})/page/?([0-9]{1,})/?$' => 'index.php?year=$matches[1]&paged=$matches[2]',
	function test_y_paged() {
		$this->http('/2007/page/2/');
		$this->assertQueryTrue('is_archive', 'is_date', 'is_year', 'is_paged');
	}

	// '([0-9]{4})/?$' => 'index.php?year=$matches[1]',
	function test_y() {
		$this->http('/2007/');
		$this->assertQueryTrue('is_archive', 'is_date', 'is_year');
	}


	// '([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/([^/]+)/trackback/?$' => 'index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&name=$matches[4]&tb=1',
	function test_post_trackback() {
		foreach ($this->_all_post_ids() as $id) {
			$permalink = get_permalink($id);
			$this->http("{$permalink}trackback/");
			$this->assertQueryTrue('is_single', 'is_trackback');
		}
	}

	// '([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&name=$matches[4]&feed=$matches[5]',
	// '([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/([^/]+)/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&name=$matches[4]&feed=$matches[5]',
	function test_post_comment_feed() {
		foreach ($this->_all_post_ids() as $id) {
			$permalink = get_permalink($id);
			
			$types = array('feed', 'rdf', 'rss', 'rss2', 'atom');
			foreach ($types as $type) {
					$this->http("{$permalink}feed/{$type}");
					$this->assertQueryTrue('is_single', 'is_feed', 'is_comment_feed');
			}

			// check the short form
			$types = array('feed', 'rdf', 'rss', 'rss2', 'atom');
			foreach ($types as $type) {
					$this->http("{$permalink}{$type}");
					$this->assertQueryTrue('is_single', 'is_feed', 'is_comment_feed');
			}

		}
	}

	// '([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/([^/]+)/page/?([0-9]{1,})/?$' => 'index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&name=$matches[4]&paged=$matches[5]',
	function test_post_paged_long() {
		// the long version
		$this->http('/2007/09/04/a-post-with-multiple-pages/page/2/');
		// should is_paged be true also?
		$this->assertQueryTrue('is_single');
	}

	// '([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/([^/]+)(/[0-9]+)?/?$' => 'index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&name=$matches[4]&page=$matches[5]',
	function test_post_paged_short() {
		// and the short version
		$this->http('/2007/09/04/a-post-with-multiple-pages/2/');
		// should is_paged be true also?
		$this->assertQueryTrue('is_single');
		
	}
	
	// '[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/([^/]+)/?$' => 'index.php?attachment=$matches[1]',
	function test_post_attachment() {
		$permalink = get_attachment_link(8);
		$this->http($permalink);
		$this->assertQueryTrue('is_attachment');
	}
	
	// '[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/([^/]+)/trackback/?$' => 'index.php?attachment=$matches[1]&tb=1',
	// '[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?attachment=$matches[1]&feed=$matches[2]',
	// '[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/([^/]+)/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?attachment=$matches[1]&feed=$matches[2]',
	// '[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/attachment/([^/]+)/?$' => 'index.php?attachment=$matches[1]',
	// '[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/attachment/([^/]+)/trackback/?$' => 'index.php?attachment=$matches[1]&tb=1',
	// '[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/attachment/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?attachment=$matches[1]&feed=$matches[2]',
	// '[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/attachment/([^/]+)/(feed|rdf|rss|rss2|atom)/?$' => 'index.php?attachment=$matches[1]&feed=$matches[2]',
	//



}

class TestWPQueryShortPageRules extends TestWPQueryVars {
	var $use_verbose_page_rules = false;
}


?>