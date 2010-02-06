<?php

// [footag foo="bar"]
function footag_func($atts) {
	return @"foo = {$atts['foo']}";
}
add_shortcode('footag', 'footag_func');

// [bartag foo="bar"]
function bartag_func($atts) {
	extract(shortcode_atts(array(
		'foo' => 'no foo',
		'baz' => 'default baz',
	), $atts));
	
	return "foo = {$foo}";
}
add_shortcode('bartag', 'bartag_func');

// [baztag]content[/baztag]
function baztag_func($atts, $content='') {
	return 'content = '.do_shortcode($content);
}
add_shortcode('baztag', 'baztag_func');

function dumptag_func($atts) {
	$out = '';
	foreach ($atts as $k=>$v)
		$out .= "$k = $v\n";
	return $out;
}
add_shortcode('dumptag', 'dumptag_func');

// suggested by markj for testing p-wrapping of shortcode output
function paragraph_func($atts, $content='') {
	extract(shortcode_atts(array(
		'class' => 'graf',
	), $atts));
	return "<p class='$class'>$content</p>\n";
}
add_shortcode('paragraph', 'paragraph_func');

class TestShortcode extends WPTestCase {

	function setUp() {
		parent::setUp();
		add_shortcode('test-shortcode-tag', array(&$this, '_shortcode_tag'));
#error_reporting(E_ALL);
#ini_set('display_errors', '1');
		$this->atts = null;
		$this->content = null;
		$this->tagname = null;
		
	}
	
	function _shortcode_tag($atts, $content=NULL, $tagname=NULL) {
		$this->atts = $atts;
		$this->content = $content;
		$this->tagname = $tagname;
	}
	
	function test_noatts() {
		do_shortcode('[test-shortcode-tag /]');
		$this->assertEquals( '', $this->atts );
		$this->assertEquals( 'test-shortcode-tag', $this->tagname );
	}
	
	function test_one_att() {
		do_shortcode('[test-shortcode-tag foo="asdf" /]');
		$this->assertEquals( array('foo' => 'asdf'), $this->atts );
		$this->assertEquals( 'test-shortcode-tag', $this->tagname );
	}
	
	function test_not_a_tag() {
		$out = do_shortcode('[not-a-shortcode-tag]');
		$this->assertEquals( '[not-a-shortcode-tag]', $out );
	}
	
	function test_two_atts() {
		do_shortcode('[test-shortcode-tag foo="asdf" bar="bing" /]');
		$this->assertEquals( array('foo' => 'asdf', 'bar' => 'bing'), $this->atts );
		$this->assertEquals( 'test-shortcode-tag', $this->tagname );
	}
	
	function test_noatts_enclosing() {
		do_shortcode('[test-shortcode-tag]content[/test-shortcode-tag]');
		$this->assertEquals( '', $this->atts );
		$this->assertEquals( 'content', $this->content );
		$this->assertEquals( 'test-shortcode-tag', $this->tagname );
	}
	
	function test_one_att_enclosing() {
		do_shortcode('[test-shortcode-tag foo="bar"]content[/test-shortcode-tag]');
		$this->assertEquals( array('foo' => 'bar'), $this->atts );
		$this->assertEquals( 'content', $this->content );
		$this->assertEquals( 'test-shortcode-tag', $this->tagname );
	}
	
	function test_two_atts_enclosing() {
		do_shortcode('[test-shortcode-tag foo="bar" baz="bing"]content[/test-shortcode-tag]');
		$this->assertEquals( array('foo' => 'bar', 'baz' => 'bing'), $this->atts );
		$this->assertEquals( 'content', $this->content );
		$this->assertEquals( 'test-shortcode-tag', $this->tagname );
	}

	function test_unclosed() {
		$out = do_shortcode('[test-shortcode-tag]');
		$this->assertEquals( '', $out );
		$this->assertEquals( '', $this->atts );
		$this->assertEquals( 'test-shortcode-tag', $this->tagname );
	}

	function test_positional_atts_num() {
		$out = do_shortcode('[test-shortcode-tag 123]');
		$this->assertEquals( '', $out );
		$this->assertEquals( array(0=>'123'), $this->atts );
		$this->assertEquals( 'test-shortcode-tag', $this->tagname );
	}

	function test_positional_atts_url() {
		$out = do_shortcode('[test-shortcode-tag http://www.youtube.com/watch?v=eBGIQ7ZuuiU]');
		$this->assertEquals( '', $out );
		$this->assertEquals( array(0=>'http://www.youtube.com/watch?v=eBGIQ7ZuuiU'), $this->atts );
		$this->assertEquals( 'test-shortcode-tag', $this->tagname );
	}

	function test_positional_atts_quotes() {
		$out = do_shortcode('[test-shortcode-tag "something in quotes" "something else"]');
		$this->assertEquals( '', $out );
		$this->assertEquals( array(0=>'something in quotes', 1=>'something else'), $this->atts );
		$this->assertEquals( 'test-shortcode-tag', $this->tagname );
	}

	function test_positional_atts_mixed() {
		$out = do_shortcode('[test-shortcode-tag 123 http://wordpress.com/ 0 "foo" bar]');
		$this->assertEquals( '', $out );
		$this->assertEquals( array(0=>'123', 1=>'http://wordpress.com/', 2=>'0', 3=>'foo', 4=>'bar'), $this->atts );
		$this->assertEquals( 'test-shortcode-tag', $this->tagname );
	}

	function test_positional_and_named_atts() {
		$out = do_shortcode('[test-shortcode-tag 123 url=http://wordpress.com/ foo bar="baz"]');
		$this->assertEquals( '', $out );
		$this->assertEquals( array(0=>'123', 'url' => 'http://wordpress.com/', 1=>'foo', 'bar' => 'baz'), $this->atts );
		$this->assertEquals( 'test-shortcode-tag', $this->tagname );
	}

	function test_footag_default() {
		$out = do_shortcode('[footag]');
		$this->assertEquals('foo = ', $out);
	}
	
	function test_footag_val() {
		$val = rand_str();
		$out = do_shortcode('[footag foo="'.$val.'"]');
		$this->assertEquals('foo = '.$val, $out);
	}
	
	function test_nested_tags() {
		$out = do_shortcode('[baztag][dumptag abc="foo" def=123 http://wordpress.com/][/baztag]');
		$expected = <<<EOF
content = abc = foo
def = 123
0 = http://wordpress.com

EOF;
		$this->assertEquals($expected, $out);
	}
	
	function test_tag_escaped() {
		$this->knownWPBug(6518);
		
		$out = do_shortcode('[[footag]] [[bartag foo="bar"]]');
		$this->assertEquals('[footag] [bartag foo="bar"]', $out);

		$out = do_shortcode('[[footag /]] [[bartag foo="bar" /]]');
		$this->assertEquals('[footag /] [bartag foo="bar" /]', $out);
		
		$out = do_shortcode('[[baztag foo="bar"]the content[/baztag]]');
		$this->assertEquals('[baztag foo="bar"]the content[/baztag]', $out);
		
		// double escaped
		$out = do_shortcode('[[[footag]]] [[[bartag foo="bar"]]]');
		$this->assertEquals('[[footag]] [[bartag foo="bar"]]', $out);
	}
	
	function test_tag_not_escaped() {
		// these have square brackets on either end but aren't actually escaped
		$out = do_shortcode('[[footag] [bartag foo="bar"]]');
		$this->assertEquals('[foo =  foo = bar]', $out);

		$out = do_shortcode('[[footag /] [bartag foo="bar" /]]');
		$this->assertEquals('[foo =  foo = bar]', $out);

		$out = do_shortcode('[[baztag foo="bar"]the content[/baztag]');
		$this->assertEquals('[content = the content', $out);
		
		$out = do_shortcode('[[not-a-tag]]');
		$this->assertEquals('[[not-a-tag]]', $out);

		$out = do_shortcode('[[[footag] [bartag foo="bar"]]]');
		$this->assertEquals('[[foo =  foo = bar]]', $out);
	}
	
	function test_mixed_tags() {
		$in = <<<EOF
So this is a post with [footag foo="some stuff"] and a bunch of tags.

[bartag]

[baztag]
Here's some content
on more than one line
[/baztag]

[bartag foo=1] [baztag] [footag foo="2"] [baztag]

[baztag]
more content
[/baztag]

EOF;
		$expected = <<<EOF
So this is a post with foo = some stuff and a bunch of tags.

foo = no foo

content = 
Here's some content
on more than one line


foo = 1 content =  foo = 2 content =
content =
more content

EOF;
		$out = do_shortcode($in);
		$this->assertEquals(strip_ws($expected), strip_ws($out));
	}
	
	//TODO Review this test as it may be incorrect
	function test_utf8_whitespace_1() {
		// see http://trac.wordpress.org/ticket/6562
		$this->knownWPBug(6562);
		do_shortcode("[test-shortcode-tag foo=\"bar\" \x00\xA0baz=\"123\"]");
		$this->assertEquals( array('foo' => 'bar', 'baz' => '123'), $this->atts );
		$this->assertEquals( '', $this->content );
	}

	//TODO Review this test as it may be incorrect
	function test_utf8_whitespace_2() {
		// see http://trac.wordpress.org/ticket/6562
		$this->knownWPBug(6562);
		do_shortcode("[test-shortcode-tag foo=\"bar\" \x20\x0babc=\"def\"]");
		$this->assertEquals( array('foo' => 'bar', 'abc' => 'def'), $this->atts );
		$this->assertEquals( '', $this->content );
	}
	
/*
enabled = the shortcode works as normal (default)
strip = the shortcode will be parsed and removed.  e.g. '[shortcode foo="bar"]' produces ''.  '[shortcode]foo[/shortcode]' produces 'foo'.
faux = the shortcode will be abbreviated.  e.g. '[shortcode foo="bar"]' products '[shortcode]'.  '[shortocde]foo[/shortcode]' produces '[shortcode]'
disabled = the shortcode is not parsed at all.  e.g. '[shortcode foo="bar"]' products '[shortcode foo="bar"]'
*/

	function test_shortcodes_enabled() {
		if ( !is_callable('set_all_shortcode_status') )
			$this->markTestSkipped();
		set_all_shortcode_status('enabled');
		$out = do_shortcode("[baztag]foo is [footag foo='bar'][/baztag]");
		$expected = 'content = foo is foo = bar';
		$this->assertEquals( $expected, $out );
	}
	
	function test_shortcodes_disabled() {
		if ( !is_callable('set_all_shortcode_status') )
			$this->markTestSkipped();
		set_all_shortcode_status('disabled');
		$out = do_shortcode("[baztag]foo is [footag foo='bar'][/baztag]");
		$expected = $out;
		$this->assertEquals( $expected, $out );
	}
	
	function test_shortcodes_strip() {
		if ( !is_callable('set_all_shortcode_status') )
			$this->markTestSkipped();
		set_all_shortcode_status('strip');
		$out = do_shortcode("[baztag]foo is [footag foo='bar'][/baztag]");
		$expected = 'foo is ';
		$this->assertEquals( $expected, $out );
	}
	
	function test_shortcodes_faux() {
		if ( !is_callable('set_all_shortcode_status') )
			$this->markTestSkipped();
		set_all_shortcode_status('faux');
		$out = do_shortcode("[baztag]foo is [footag foo='bar'][/baztag]");
		$expected = '[baztag]';
		$this->assertEquals( $expected, $out );
	}

}
//http://core.trac.wordpress.org/ticket/10326
class TestShortcodeStripping extends WPTestCase {
	function test_strip_shortcodes() {
		$this->assertEquals('before',strip_shortcodes('before[gallery]'));
		$this->assertEquals('after',strip_shortcodes('[gallery]after'));
		$this->assertEquals('beforeafter',strip_shortcodes('before[gallery]after'));
	}
}

?>