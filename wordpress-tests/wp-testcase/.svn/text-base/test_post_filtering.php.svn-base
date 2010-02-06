<?php

// save and fetch posts to make sure content is properly filtered.
// these tests don't care what code is responsible for filtering or how it is called, just that it happens when a post is saved.


class TestPostFiltering extends WPTestCase {
	function setUp() {
		parent::setUp();
		update_option('use_balanceTags', 1);
		kses_init_filters();
		
	}
	
	function tearDown() {
		parent::tearDown();
		kses_remove_filters();
	}
	
	function _insert_quick_post($title, $content, $more=array()) {
		return $this->post_ids[] = wp_insert_post(array_merge(array(
			'post_author' => $this->author->ID,
			'post_status' => 'publish',
			'post_title' => $title,
			'post_content' => $content,
			), $more));
	}
	
	// a simple test to make sure unclosed tags are fixed
	function test_post_content_unknown_tag() {
		
		$content = <<<EOF
<foobar>no such tag</foobar>
EOF;

		$expected = <<<EOF
no such tag
EOF;

		$id = $this->_insert_quick_post(__FUNCTION__, $content);
		$post = get_post($id);
		
		$this->assertEquals( $expected, $post->post_content );
	}
	
	// a simple test to make sure unbalanced tags are fixed
	function test_post_content_unbalanced_tag() {
		
		$content = <<<EOF
<i>italics
EOF;

		$expected = <<<EOF
<i>italics</i>
EOF;

		$id = $this->_insert_quick_post(__FUNCTION__, $content);
		$post = get_post($id);
		
		$this->assertEquals( $expected, $post->post_content );
	}

	// make sure unbalanced tags are fixed when they span a --more-- tag
	function test_post_content_unbalanced_more() {
		
		$content = <<<EOF
<em>some text<!--more-->
that's continued after the jump</em>
EOF;

		$expected = <<<EOF
<em>some text</em><!--more-->
that's continued after the jump
EOF;

		$id = $this->_insert_quick_post(__FUNCTION__, $content);
		$post = get_post($id);
		
		$this->assertEquals( $expected, $post->post_content );
	}

	// make sure unbalanced tags are fixed when they span a --nextpage-- tag
	function test_post_content_unbalanced_nextpage() {
		
		$content = <<<EOF
<em>some text<!--nextpage-->
that's continued after the jump</em>
EOF;

		$expected = <<<EOF
<em>some text</em><!--nextpage-->
that's continued after the jump
EOF;

		$id = $this->_insert_quick_post(__FUNCTION__, $content);
		$post = get_post($id);
		
		$this->assertEquals( $expected, $post->post_content );
	}

	// make sure unbalanced tags are fixed when they span both --more-- and --nextpage-- tags (in that order)
	function test_post_content_unbalanced_more_nextpage() {
		
		$content = <<<EOF
<em>some text<!--more-->
that's continued after the jump</em>
<!--nextpage-->
<p>and the next page
<!--nextpage-->
breaks the graf</p>
EOF;

		$expected = <<<EOF
<em>some text</em><!--more-->
that's continued after the jump
<!--nextpage-->
<p>and the next page
</p><!--nextpage-->
breaks the graf
EOF;

		$id = $this->_insert_quick_post(__FUNCTION__, $content);
		$post = get_post($id);
		
		$this->assertEquals( $expected, $post->post_content );
	}
	
	// make sure unbalanced tags are fixed when they span both --nextpage-- and --more-- tags (in that order)
	function test_post_content_unbalanced_nextpage_more() {
		
		$content = <<<EOF
<em>some text<!--nextpage-->
that's continued after the jump</em>
<!--more-->
<p>and the next page
<!--nextpage-->
breaks the graf</p>
EOF;

		$expected = <<<EOF
<em>some text</em><!--nextpage-->
that's continued after the jump
<!--more-->
<p>and the next page
</p><!--nextpage-->
breaks the graf
EOF;

		$id = $this->_insert_quick_post(__FUNCTION__, $content);
		$post = get_post($id);
		
		$this->assertEquals( $expected, $post->post_content );
	}
	
	// make sure unbalanced tags are untouched when the balance option is off
	function test_post_content_nobalance_nextpage_more() {

		update_option('use_balanceTags', 0);
		
		$content = <<<EOF
<em>some text<!--nextpage-->
that's continued after the jump</em>
<!--more-->
<p>and the next page
<!--nextpage-->
breaks the graf</p>
EOF;

		$id = $this->_insert_quick_post(__FUNCTION__, $content);
		$post = get_post($id);
		
		$this->assertEquals( $content, $post->post_content );
	}		
}


?>