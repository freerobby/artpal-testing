<?php

// test the output of post template tags etc

class _WPTestSinglePost extends _WPEmptyBlog {

	var $post_title = NULL;
	var $post_content = NULL;

	var $the_title = NULL;
	var $the_content = NULL;

	function _do_post() {
		$post = array(
			'post_author' => $this->author->ID,
			'post_status' => 'publish',
			'post_content' => $this->post_content,
			'post_title' => $this->post_title,
		);

		// insert a post
		$this->post_id = $this->post_ids[] = wp_insert_post($post);

		// pretend we're on the single permlink page for that post
		$out = wp_get_single_post($this->post_id);
		$this->http(get_permalink($this->post_id));

		$this->assertTrue(is_single());
		$this->assertTrue(have_posts());
		$this->assertNull(the_post());
	}


}

class WPTestPostMoreVB extends _WPTestSinglePost {

	function setUp() {

		$this->post_content =<<<EOF
<i>This is the excerpt.</i>
<!--more-->
This is the <b>body</b>.
EOF;

		parent::setUp();
	}

	function test_the_content() {
		$this->_do_post();
		$the_content =<<<EOF
<p><i>This is the excerpt.</i><br />
<span id="more-{$this->post_id}"></span><br />
This is the <b>body</b>.</p>
EOF;

		$this->assertEquals(strip_ws($the_content), strip_ws(get_echo('the_content')));
	}

}

class WPTestShortcodeOutput1 extends _WPTestSinglePost {
	function setUp() {

		$this->post_content =<<<EOF
[dumptag foo="bar" baz="123"]

[dumptag foo=123 baz=bar]

[dumptag http://example.com]

EOF;

		parent::setUp();
	}

	function test_the_content() {
		$this->_do_post();
		$expected =<<<EOF
foo = bar
baz = 123
foo = 123
baz = bar
0 = http://example.com

EOF;

		$this->assertEquals(strip_ws($expected), strip_ws(get_echo('the_content')));
	}
}

class WPTestShortcodeOutputParagraph extends _WPTestSinglePost {
	function setUp() {

		$this->post_content =<<<EOF
Graf by itself:

[paragraph]my graf[/paragraph]

  [paragraph foo="bar"]another graf with whitespace[/paragraph]  

An [paragraph]inline graf[/paragraph], this doesn't make much sense.

A graf with a single EOL first:
[paragraph]blah[/paragraph]

EOF;

		parent::setUp();
	}

	function test_the_content() {
		$this->_do_post();
		$expected =<<<EOF
<p>Graf by itself:</p>
<p class='graf'>my graf</p>

  <p class='graf'>another graf with whitespace</p>

<p>An <p class='graf'>inline graf</p>
, this doesn&#8217;t make much sense.</p>
<p>A graf with a single EOL first:<br />
<p class='graf'>blah</p>
</p>

EOF;

		$this->assertEquals(strip_ws($expected), strip_ws(get_echo('the_content')));
	}
}

class WPTestGalleryPost extends _WPDataset1 {
	function setUp() {
		parent::setUp();
		global $wp_rewrite;
		$wp_rewrite->set_permalink_structure('/%year%/%monthnum%/%day%/%postname%/');
		$wp_rewrite->flush_rules();
	}
	
	function test_the_content() {
		// permalink page
		$this->http('/2008/04/01/simple-gallery-test/');
		the_post();
		// filtered output
		$out = get_echo('the_content');
		
		$expected = <<<EOF
<p>There are ten images attached to this post.  Here&#8217;s a gallery:</p>

		<style type='text/css'>
			.gallery {
				margin: auto;
			}
			.gallery-item {
				float: left;
				margin-top: 10px;
				text-align: center;
				width: 33%;			}
			.gallery img {
				border: 2px solid #cfcfcf;
			}
			.gallery-caption {
				margin-left: 0;
			}
		</style>
		<!-- see gallery_shortcode() in wp-includes/media.php -->
		<div class='gallery'><dl class='gallery-item'>
			<dt class='gallery-icon'>
				<a href='http://example.com/2008/04/01/simple-gallery-test/dsc20040724_152504_53/' title='dsc20040724_152504_53'><img src="http://example.com/wp-content/uploads/2008/04/dsc20040724_152504_537.jpg" class="attachment-thumbnail" alt="" /></a>
			</dt></dl><dl class='gallery-item'>
			<dt class='gallery-icon'>
				<a href='http://example.com/2008/04/01/simple-gallery-test/canola/' title='canola'><img src="http://example.com/wp-content/uploads/2008/04/canola3.jpg" class="attachment-thumbnail" alt="" /></a>
			</dt></dl><dl class='gallery-item'>
			<dt class='gallery-icon'>
				<a href='http://example.com/2008/04/01/simple-gallery-test/dsc20050315_145007_13/' title='dsc20050315_145007_13'><img src="http://example.com/wp-content/uploads/2008/04/dsc20050315_145007_134.jpg" class="attachment-thumbnail" alt="" /></a>
			</dt></dl><br style="clear: both" /><dl class='gallery-item'>
			<dt class='gallery-icon'>
				<a href='http://example.com/2008/04/01/simple-gallery-test/dsc20050604_133440_34/' title='dsc20050604_133440_34'><img src="http://example.com/wp-content/uploads/2008/04/dsc20050604_133440_343.jpg" class="attachment-thumbnail" alt="" /></a>
			</dt></dl><dl class='gallery-item'>
			<dt class='gallery-icon'>
				<a href='http://example.com/2008/04/01/simple-gallery-test/dsc20050831_165238_33/' title='dsc20050831_165238_33'><img src="http://example.com/wp-content/uploads/2008/04/dsc20050831_165238_333.jpg" class="attachment-thumbnail" alt="" /></a>
			</dt></dl><dl class='gallery-item'>
			<dt class='gallery-icon'>
				<a href='http://example.com/2008/04/01/simple-gallery-test/dsc20050901_105100_21/' title='dsc20050901_105100_21'><img src="http://example.com/wp-content/uploads/2008/04/dsc20050901_105100_213.jpg" class="attachment-thumbnail" alt="" /></a>
			</dt></dl><br style="clear: both" /><dl class='gallery-item'>
			<dt class='gallery-icon'>
				<a href='http://example.com/2008/04/01/simple-gallery-test/dsc20050813_115856_5/' title='dsc20050813_115856_5'><img src="http://example.com/wp-content/uploads/2008/04/dsc20050813_115856_54.jpg" class="attachment-thumbnail" alt="" /></a>
			</dt></dl><dl class='gallery-item'>
			<dt class='gallery-icon'>
				<a href='http://example.com/2008/04/01/simple-gallery-test/dsc20050720_123726_27/' title='dsc20050720_123726_27'><img src="http://example.com/wp-content/uploads/2008/04/dsc20050720_123726_274.jpg" class="attachment-thumbnail" alt="" /></a>
			</dt></dl><dl class='gallery-item'>
			<dt class='gallery-icon'>
				<a href='http://example.com/2008/04/01/simple-gallery-test/dsc20050727_091048_22/' title='Title: Seedlings'><img src="http://example.com/wp-content/uploads/2008/04/dsc20050727_091048_224.jpg" class="attachment-thumbnail" alt="" /></a>
			</dt></dl><br style="clear: both" /><dl class='gallery-item'>
			<dt class='gallery-icon'>
				<a href='http://example.com/2008/04/01/simple-gallery-test/dsc20050726_083116_18/' title='dsc20050726_083116_18'><img src="http://example.com/wp-content/uploads/2008/04/dsc20050726_083116_184.jpg" class="attachment-thumbnail" alt="" /></a>
			</dt></dl>
			<br style='clear: both;' />
		</div>

<p>It&#8217;s the simplest form of the gallery tag.  All images are from the public domain site burningwell.org.</p>
<p>The images have various combinations of titles, captions and descriptions.</p>
EOF;
		$this->assertEquals(strip_ws($expected), strip_ws($out));
	}

	function test_gallery_attributes() {
		// make sure the gallery shortcode attributes are parsed correctly
		
		$id = 575;
		$post = get_post($id);
		$post->post_content = '[gallery columns="1" size="medium"]';
		wp_update_post($post);
		
		// permalink page
		$this->http('/2008/04/01/simple-gallery-test/');
		the_post();
		// filtered output
		$out = get_echo('the_content');

		$expected = <<<EOF
		<style type='text/css'>
			.gallery {
				margin: auto;
			}
			.gallery-item {
				float: left;
				margin-top: 10px;
				text-align: center;
				width: 100%;			}
			.gallery img {
				border: 2px solid #cfcfcf;
			}
			.gallery-caption {
				margin-left: 0;
			}
		</style>
		<!-- see gallery_shortcode() in wp-includes/media.php -->
		<div class='gallery'><dl class='gallery-item'>
			<dt class='gallery-icon'>
				<a href='http://example.com/?attachment_id=565' title='dsc20040724_152504_53'><img src="http://example.com/wp-content/uploads/2008/04/dsc20040724_152504_537.jpg" class="attachment-medium" alt="" /></a>
			</dt></dl><br style="clear: both" /><dl class='gallery-item'>
			<dt class='gallery-icon'>
				<a href='http://example.com/?attachment_id=566' title='canola'><img src="http://example.com/wp-content/uploads/2008/04/canola3.jpg" class="attachment-medium" alt="" /></a>
			</dt></dl><br style="clear: both" /><dl class='gallery-item'>
			<dt class='gallery-icon'>
				<a href='http://example.com/?attachment_id=567' title='dsc20050315_145007_13'><img src="http://example.com/wp-content/uploads/2008/04/dsc20050315_145007_134.jpg" class="attachment-medium" alt="" /></a>
			</dt></dl><br style="clear: both" /><dl class='gallery-item'>
			<dt class='gallery-icon'>
				<a href='http://example.com/?attachment_id=568' title='dsc20050604_133440_34'><img src="http://example.com/wp-content/uploads/2008/04/dsc20050604_133440_343.jpg" class="attachment-medium" alt="" /></a>
			</dt></dl><br style="clear: both" /><dl class='gallery-item'>
			<dt class='gallery-icon'>
				<a href='http://example.com/?attachment_id=569' title='dsc20050831_165238_33'><img src="http://example.com/wp-content/uploads/2008/04/dsc20050831_165238_333.jpg" class="attachment-medium" alt="" /></a>
			</dt></dl><br style="clear: both" /><dl class='gallery-item'>
			<dt class='gallery-icon'>
				<a href='http://example.com/?attachment_id=570' title='dsc20050901_105100_21'><img src="http://example.com/wp-content/uploads/2008/04/dsc20050901_105100_213.jpg" class="attachment-medium" alt="" /></a>
			</dt></dl><br style="clear: both" /><dl class='gallery-item'>
			<dt class='gallery-icon'>
				<a href='http://example.com/?attachment_id=571' title='dsc20050813_115856_5'><img src="http://example.com/wp-content/uploads/2008/04/dsc20050813_115856_54.jpg" class="attachment-medium" alt="" /></a>
			</dt></dl><br style="clear: both" /><dl class='gallery-item'>
			<dt class='gallery-icon'>
				<a href='http://example.com/?attachment_id=572' title='dsc20050720_123726_27'><img src="http://example.com/wp-content/uploads/2008/04/dsc20050720_123726_274.jpg" class="attachment-medium" alt="" /></a>
			</dt></dl><br style="clear: both" /><dl class='gallery-item'>
			<dt class='gallery-icon'>
				<a href='http://example.com/?attachment_id=573' title='Title: Seedlings'><img src="http://example.com/wp-content/uploads/2008/04/dsc20050727_091048_224.jpg" class="attachment-medium" alt="" /></a>
			</dt></dl><br style="clear: both" /><dl class='gallery-item'>
			<dt class='gallery-icon'>
				<a href='http://example.com/?attachment_id=574' title='dsc20050726_083116_18'><img src="http://example.com/wp-content/uploads/2008/04/dsc20050726_083116_184.jpg" class="attachment-medium" alt="" /></a>
			</dt></dl><br style="clear: both" />
			<br style='clear: both;' />
		</div>

EOF;
		$this->assertEquals(strip_ws($expected), strip_ws($out));
	}

}



class WPTestAttributeFiltering extends _WPTestSinglePost {
	function setUp() {

		// http://bpr3.org/?p=87
		// the title attribute should make it through unfiltered
		$this->post_content =<<<EOF
<span class="Z3988" title="ctx_ver=Z39.88-2004&rft_val_fmt=info%3Aofi%2Ffmt%3Akev%3Amtx%3Ajournal&rft.aulast=Mariat&rft.aufirst=Denis&rft. au=Denis+Mariat&rft.au=Sead+Taourit&rft.au=G%C3%A9rard+Gu%C3%A9rin& rft.title=Genetics+Selection+Evolution&rft.atitle=&rft.date=2003&rft. volume=35&rft.issue=1&rft.spage=119&rft.epage=133&rft.genre=article& rft.id=info:DOI/10.1051%2Fgse%3A2002039"></span>Mariat, D., Taourit, S., GuÃ©rin, G. (2003). . <span style="font-style: italic;">Genetics Selection Evolution, 35</span>(1), 119-133. DOI: <a rev="review" href= "http://dx.doi.org/10.1051/gse:2002039">10.1051/gse:2002039</a>

EOF;

		parent::setUp();
	}

	function test_the_content() {
		$this->_do_post();
		$expected =<<<EOF
<p><span class="Z3988" title="ctx_ver=Z39.88-2004&rft_val_fmt=info%3Aofi%2Ffmt%3Akev%3Amtx%3Ajournal&rft.aulast=Mariat&rft.aufirst=Denis&rft. au=Denis+Mariat&rft.au=Sead+Taourit&rft.au=G%C3%A9rard+Gu%C3%A9rin& rft.title=Genetics+Selection+Evolution&rft.atitle=&rft.date=2003&rft. volume=35&rft.issue=1&rft.spage=119&rft.epage=133&rft.genre=article& rft.id=info:DOI/10.1051%2Fgse%3A2002039"></span>Mariat, D., Taourit, S., GuÃ©rin, G. (2003). . <span style="font-style: italic;">Genetics Selection Evolution, 35</span>(1), 119-133. DOI: <a rev="review" href= "http://dx.doi.org/10.1051/gse:2002039">10.1051/gse:2002039</a></p>

EOF;

		$this->assertEquals(strip_ws($expected), strip_ws(get_echo('the_content')));
	}
}

class WPTestAttributeColon extends _WPTestSinglePost {
	function setUp() {

		// http://bpr3.org/?p=87
		// the title attribute should make it through unfiltered
		$this->post_content =<<<EOF
<span title="My friends: Alice, Bob and Carol">foo</span>

EOF;

		parent::setUp();
	}

	function test_the_content() {
		$this->_do_post();
		$expected =<<<EOF
<p><span title="My friends: Alice, Bob and Carol">foo</span></p>

EOF;

		$this->assertEquals(strip_ws($expected), strip_ws(get_echo('the_content')));
	}
}

?>