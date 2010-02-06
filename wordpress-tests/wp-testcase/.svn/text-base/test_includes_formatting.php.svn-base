<?php

class TestMakeClickable extends WPTestCase {
	function test_mailto_xss() {
		$in = 'testzzz@"STYLE="behavior:url(\'#default#time2\')"onBegin="alert(\'refresh-XSS\')"';
		$this->assertEquals($in, make_clickable($in));
	}
	
	function test_valid_mailto() {
		
		$valid_emails = array(
			'foo@example.com',
			'foo.bar@example.com',
			'Foo.Bar@a.b.c.d.example.com',
			'0@example.com',
			'foo@example-example.com',
			);
		foreach ($valid_emails as $email) {
			$this->assertEquals('<a href="mailto:'.$email.'">'.$email.'</a>', make_clickable($email));
		}
		
	}
	
	function test_invalid_mailto() {
		
		$invalid_emails = array(
			'foo',
			'foo@',
			'foo@@example.com',
			'@example.com',
			'foo @example.com',
			'foo@example',
			);
		foreach ($invalid_emails as $email) {
			$this->assertEquals($email, make_clickable($email));
		}
		
	}
	
	// tests that make_clickable will not link trailing periods, commas and 
	// (semi-)colons in URLs with protocol (i.e. http://wordpress.org)
	function test_strip_trailing_with_protocol() {
		$urls_before = array(
			'http://wordpress.org/hello.html',
			'There was a spoon named http://wordpress.org. Alice!',
			'There was a spoon named http://wordpress.org, said Alice.',
			'There was a spoon named http://wordpress.org; said Alice.',
			'There was a spoon named http://wordpress.org: said Alice.',
			'There was a spoon named (http://wordpress.org) said Alice.'
			);
		$urls_expected = array(
			'<a href="http://wordpress.org/hello.html" rel="nofollow">http://wordpress.org/hello.html</a>',
			'There was a spoon named <a href="http://wordpress.org" rel="nofollow">http://wordpress.org</a>. Alice!',
			'There was a spoon named <a href="http://wordpress.org" rel="nofollow">http://wordpress.org</a>, said Alice.',
			'There was a spoon named <a href="http://wordpress.org" rel="nofollow">http://wordpress.org</a>; said Alice.',
			'There was a spoon named <a href="http://wordpress.org" rel="nofollow">http://wordpress.org</a>: said Alice.',
			'There was a spoon named (<a href="http://wordpress.org" rel="nofollow">http://wordpress.org</a>) said Alice.'
			);

		foreach ($urls_before as $key => $url) {
			$this->assertEquals($urls_expected[$key], make_clickable($url));
		}
	}

	// tests that make_clickable will not link trailing periods, commas and 
	// (semi-)colons in URLs with protocol (i.e. http://wordpress.org)
	function test_strip_trailing_with_protocol_nothing_afterwards() {
		$urls_before = array(
			'http://wordpress.org/hello.html',
			'There was a spoon named http://wordpress.org.',
			'There was a spoon named http://wordpress.org,',
			'There was a spoon named http://wordpress.org;',
			'There was a spoon named http://wordpress.org:',
			'There was a spoon named (http://wordpress.org)'
			);
		$urls_expected = array(
			'<a href="http://wordpress.org/hello.html" rel="nofollow">http://wordpress.org/hello.html</a>',
			'There was a spoon named <a href="http://wordpress.org" rel="nofollow">http://wordpress.org</a>.',
			'There was a spoon named <a href="http://wordpress.org" rel="nofollow">http://wordpress.org</a>,',
			'There was a spoon named <a href="http://wordpress.org" rel="nofollow">http://wordpress.org</a>;',
			'There was a spoon named <a href="http://wordpress.org" rel="nofollow">http://wordpress.org</a>:',
			'There was a spoon named (<a href="http://wordpress.org" rel="nofollow">http://wordpress.org</a>)'
			);

		foreach ($urls_before as $key => $url) {
			$this->assertEquals($urls_expected[$key], make_clickable($url));
		}
	}	
	
	// tests that make_clickable will not link trailing periods, commas and 
	// (semi-)colons in URLs without protocol (i.e. www.wordpress.org)
	function test_strip_trailing_without_protocol() {
		$urls_before = array(
			'www.wordpress.org',
			'There was a spoon named www.wordpress.org. Alice!',
			'There was a spoon named www.wordpress.org, said Alice.',
			'There was a spoon named www.wordpress.org; said Alice.',
			'There was a spoon named www.wordpress.org: said Alice.',
			'There was a spoon named www.wordpress.org) said Alice.'
			);
		$urls_expected = array(
			'<a href="http://www.wordpress.org" rel="nofollow">http://www.wordpress.org</a>',
			'There was a spoon named <a href="http://www.wordpress.org" rel="nofollow">http://www.wordpress.org</a>. Alice!',
			'There was a spoon named <a href="http://www.wordpress.org" rel="nofollow">http://www.wordpress.org</a>, said Alice.',
			'There was a spoon named <a href="http://www.wordpress.org" rel="nofollow">http://www.wordpress.org</a>; said Alice.',
			'There was a spoon named <a href="http://www.wordpress.org" rel="nofollow">http://www.wordpress.org</a>: said Alice.',
			'There was a spoon named <a href="http://www.wordpress.org" rel="nofollow">http://www.wordpress.org</a>) said Alice.'
			);

		foreach ($urls_before as $key => $url) {
			$this->assertEquals($urls_expected[$key], make_clickable($url));
		}
	}

	// tests that make_clickable will not link trailing periods, commas and 
	// (semi-)colons in URLs without protocol (i.e. www.wordpress.org)
	function test_strip_trailing_without_protocol_nothing_afterwards() {
		$urls_before = array(
			'www.wordpress.org',
			'There was a spoon named www.wordpress.org.',
			'There was a spoon named www.wordpress.org,',
			'There was a spoon named www.wordpress.org;',
			'There was a spoon named www.wordpress.org:',
			'There was a spoon named www.wordpress.org)'
			);
		$urls_expected = array(
			'<a href="http://www.wordpress.org" rel="nofollow">http://www.wordpress.org</a>',
			'There was a spoon named <a href="http://www.wordpress.org" rel="nofollow">http://www.wordpress.org</a>.',
			'There was a spoon named <a href="http://www.wordpress.org" rel="nofollow">http://www.wordpress.org</a>,',
			'There was a spoon named <a href="http://www.wordpress.org" rel="nofollow">http://www.wordpress.org</a>;',
			'There was a spoon named <a href="http://www.wordpress.org" rel="nofollow">http://www.wordpress.org</a>:',
			'There was a spoon named <a href="http://www.wordpress.org" rel="nofollow">http://www.wordpress.org</a>)'
			);

		foreach ($urls_before as $key => $url) {
			$this->assertEquals($urls_expected[$key], make_clickable($url));
		}
	}	
	
	function test_iri() {
		$this->knownWPBug(4570);
		$urls_before = array(
			'http://www.詹姆斯.com/',
			'http://bg.wikipedia.org/Баба',
			'http://example.com/?a=баба&b=дядо',
		);
		$urls_expected = array(
			'<a href="http://www.詹姆斯.com/" rel="nofollow">http://www.詹姆斯.com/</a>',
			'<a href="http://bg.wikipedia.org/Баба" rel="nofollow">http://bg.wikipedia.org/Баба</a>',
			'<a href="http://example.com/?a=баба&#038;b=дядо" rel="nofollow">http://example.com/?a=баба&#038;b=дядо</a>',
		);
		foreach ($urls_before as $key => $url) {
			$this->assertEquals($urls_expected[$key], make_clickable($url));
		}
	}
	
	function test_brackets_in_urls() {
			$urls_before = array(
			'http://en.wikipedia.org/wiki/PC_Tools_(Central_Point_Software)',
			'(http://en.wikipedia.org/wiki/PC_Tools_(Central_Point_Software))',
			'blah http://en.wikipedia.org/wiki/PC_Tools_(Central_Point_Software) blah',
			'blah (http://en.wikipedia.org/wiki/PC_Tools_(Central_Point_Software)) blah',
			);
		$urls_expected = array(
			'<a href="http://en.wikipedia.org/wiki/PC_Tools_(Central_Point_Software)" rel="nofollow">http://en.wikipedia.org/wiki/PC_Tools_(Central_Point_Software)</a>',
			'(<a href="http://en.wikipedia.org/wiki/PC_Tools_(Central_Point_Software)" rel="nofollow">http://en.wikipedia.org/wiki/PC_Tools_(Central_Point_Software)</a>)',
			'blah <a href="http://en.wikipedia.org/wiki/PC_Tools_(Central_Point_Software)" rel="nofollow">http://en.wikipedia.org/wiki/PC_Tools_(Central_Point_Software)</a> blah',
			'blah (<a href="http://en.wikipedia.org/wiki/PC_Tools_(Central_Point_Software)" rel="nofollow">http://en.wikipedia.org/wiki/PC_Tools_(Central_Point_Software)</a>) blah',
		);
		foreach ($urls_before as $key => $url) {
			$this->assertEquals($urls_expected[$key], make_clickable($url));
		}
	}

	// Based on a real comments which were incorrectly linked.
	function test_real_world_examples() {
		$this->knownWPBug(11211);
		$urls_before = array(
			'Example: WordPress, test (some text), I love example.com (http://example.org), it is brilliant',
			'Example: WordPress, test (some text), I love example.com (http://example.com), it is brilliant',
			'Some text followed by a bracketed link with a trailing elipsis (http://example.com)...'
		);
		$urls_expected = array(
			'Example: WordPress, test (some text), I love example.com (<a href="http://example.org" rel="nofollow">http://example.org</a>), it is brilliant',
			'Example: WordPress, test (some text), I love example.com (<a href="http://example.com" rel="nofollow">http://example.com</a>), it is brilliant',
			'Some text followed by a bracketed link with a trailing elipsis (<a href="http://example.com" rel="nofollow">http://example.com</a>)...'
		);
		foreach ($urls_before as $key => $url) {
			$this->assertEquals($urls_expected[$key], make_clickable($url));
		}
	}

}

class TestJSEscape extends WPTestCase {
	function test_js_escape_simple() {
		$out = js_escape('foo bar baz();');
		$this->assertEquals('foo bar baz();', $out);
	}
	
	function test_js_escape_quotes() {
		$out = js_escape('foo "bar" \'baz\'');
		// does it make any sense to change " into &quot;?  Why not \"?
		$this->assertEquals("foo &quot;bar&quot; \'baz\'", $out);
	}
	
	function test_js_escape_backslash() {
		$bs = '\\';
		$out = js_escape('foo '.$bs.'t bar '.$bs.$bs.' baz');
		// \t becomes t - bug?
		$this->assertEquals('foo t bar '.$bs.$bs.' baz', $out);
	}
	
	function test_js_escape_amp() {
		$out = js_escape('foo & bar &baz;');
		$this->assertEquals("foo &#038; bar &baz;", $out);
	}
	
	function test_js_escape_quote_entity() {
		$out = js_escape('foo &#x27; bar &#39; baz &#x26;');
		$this->assertEquals("foo \\' bar \\' baz &#x26;", $out);
	}

	function test_js_no_carriage_return() {
		$out = js_escape("foo\rbar\nbaz\r");
		// \r is stripped
		$this->assertequals("foobar\\nbaz", $out);
	}

	function test_js_escape_rn() {
		$out = js_escape("foo\r\nbar\nbaz\r\n");
		// \r is stripped
		$this->assertequals("foo\\nbar\\nbaz\\n", $out);
	}
}

class TestHtmlExcerpt extends WPTestCase {
	function test_simple() {
		$this->assertEquals("Baba", wp_html_excerpt("Baba told me not to come", 4));
	}
	function test_html() {
		$this->assertEquals("Baba", wp_html_excerpt("<a href='http://baba.net/'>Baba</a> told me not to come", 4));
	}
	function test_entities() {
		$this->assertEquals("Baba ", wp_html_excerpt("Baba &amp; Dyado", 8));
		$this->assertEquals("Baba ", wp_html_excerpt("Baba &#038; Dyado", 8));
		$this->assertEquals("Baba &amp; D", wp_html_excerpt("Baba &amp; Dyado", 12));
		$this->assertEquals("Baba &amp; Dyado", wp_html_excerpt("Baba &amp; Dyado", 100));
	}

}

class TestSanitizeOrderby extends WPTestCase {
	
	function test_empty() {
		if ( !is_callable('sanitize_orderby') )
			$this->markTestSkipped();

		$cols = array('a' => 'a');
		$this->assertEquals( '', sanitize_orderby('', $cols) );
		$this->assertEquals( '', sanitize_orderby('  ', $cols) );
		$this->assertEquals( '', sanitize_orderby("\t", $cols) );
		$this->assertEquals( '', sanitize_orderby(null, $cols) );
		$this->assertEquals( '', sanitize_orderby(0, $cols) );
		$this->assertEquals( '', sanitize_orderby('+', $cols) );
		$this->assertEquals( '', sanitize_orderby('-', $cols) );
	}
	
	function test_unknown_column() {
		if ( !is_callable('sanitize_orderby') )
			$this->markTestSkipped();

		$cols = array('name' => 'post_name', 'date' => 'post_date');
		$this->assertEquals( '', sanitize_orderby('unknown_column', $cols) );
		$this->assertEquals( '', sanitize_orderby('+unknown_column', $cols) );
		$this->assertEquals( '', sanitize_orderby('-unknown_column', $cols) );
		$this->assertEquals( '', sanitize_orderby('-unknown1,+unknown2,unknown3', $cols) );
		$this->assertEquals( 'post_name ASC', sanitize_orderby('name,unknown_column', $cols) );
		$this->assertEquals( '', sanitize_orderby('!@#$%^&*()_=~`\'",./', $cols) );
	}

	function test_valid() {
		if ( !is_callable('sanitize_orderby') )
			$this->markTestSkipped();

		$cols = array('name' => 'post_name', 'date' => 'post_date', 'random' => 'rand()');
		$this->assertEquals( 'post_name ASC', sanitize_orderby('name', $cols) );
		$this->assertEquals( 'post_name ASC', sanitize_orderby('+name', $cols) );
		$this->assertEquals( 'post_name DESC', sanitize_orderby('-name', $cols) );
		$this->assertEquals( 'post_date ASC, post_name ASC', sanitize_orderby('date,name', $cols) );
		$this->assertEquals( 'post_date ASC, post_name ASC', sanitize_orderby(' date , name ', $cols) );
		$this->assertEquals( 'post_name DESC, post_date ASC', sanitize_orderby('-name,date', $cols) );
		$this->assertEquals( 'post_name ASC, post_date ASC', sanitize_orderby('name ,+ date', $cols) );
		$this->assertEquals( 'rand() ASC', sanitize_orderby('random', $cols) );
	}

}

class TestWPTexturize extends WPTestCase {
	
	function test_dashes() {
		$this->assertEquals('Hey &#8212; boo?', wptexturize('Hey -- boo?'));
		$this->assertEquals('<a href="http://xx--xx">Hey &#8212; boo?</a>', wptexturize('<a href="http://xx--xx">Hey -- boo?</a>'));
	}
	
	function test_disable() {
		$this->assertEquals('<pre>---</pre>', wptexturize('<pre>---</pre>'));
		$this->assertEquals('[a]a&#8211;b[code]---[/code]a&#8211;b[/a]', wptexturize('[a]a--b[code]---[/code]a--b[/a]'));
		$this->assertEquals('<pre><code></code>--</pre>', wptexturize('<pre><code></code>--</pre>'));
	
		$this->assertEquals('<code>---</code>', wptexturize('<code>---</code>'));
		
		$this->assertEquals('<code>href="baba"</code> &#8220;baba&#8221;', wptexturize('<code>href="baba"</code> "baba"'));
		
		$enabled_tags_inside_code = '<code>curl -s <a href="http://x/">baba</a> | grep sfive | cut -d "\"" -f 10 &gt; topmp3.txt</code>';
		$this->assertEquals($enabled_tags_inside_code, wptexturize($enabled_tags_inside_code));
			
		$double_nest = '<pre>"baba"<code>"baba"<pre></pre></code>"baba"</pre>';
		$this->assertEquals($double_nest, wptexturize($double_nest));
		
		$invalid_nest = '<pre></code>"baba"</pre>';
		$this->assertEquals($invalid_nest, wptexturize($invalid_nest));

	}
	
	//WP Ticket #1418
	function test_bracketed_quotes_1418() {
		$this->assertEquals('(&#8220;test&#8221;)', wptexturize('("test")'));
		$this->assertEquals('(&#8216;test&#8217;)', wptexturize("('test')"));
		$this->assertEquals('(&#8217;twas)', wptexturize("('twas)"));
	}

	//WP Ticket #3810
	function test_bracketed_quotes_3810() {
		$this->assertEquals('A dog (&#8220;Hubertus&#8221;) was sent out.', wptexturize('A dog ("Hubertus") was sent out.'));
	}
	
	function test_quotes() {
		$this->knownWPBug(4539);
		$this->assertEquals('&#8220;Quoted String&#8221;', wptexturize('"Quoted String"'));
		$this->assertEquals('Here is &#8220;<a href="http://example.com">a test with a link</a>&#8221;', wptexturize('Here is "<a href="http://example.com">a test with a link</a>"'));
		$this->assertEquals('Here is &#8220;<a href="http://example.com">a test with a link and a period </a>&#8221;.', wptexturize('Here is "<a href="http://example.com">a test with a link and a period</a>".'));
		$this->assertEquals('Here is &#8220;<a href="http://example.com">a test with a link</a>&#8221; and a space.', wptexturize('Here is "<a href="http://example.com">a test with a link</a>" and a space.'));
		$this->assertEquals('Here is &#8220;<a href="http://example.com">a test with a link</a> and some text quoted&#8221;', wptexturize('Here is "<a href="http://example.com">a test with a link</a> and some text quoted"'));
		$this->assertEquals('Here is &#8220;<a href="http://example.com">a test with a link</a>&#8221;, and a comma.', wptexturize('Here is "<a href="http://example.com">a test with a link</a>", and a comma.'));
		$this->assertEquals('Here is &#8220;<a href="http://example.com">a test with a link</a>&#8221;; and a semi-colon.', wptexturize('Here is "<a href="http://example.com">a test with a link</a>"; and a semi-colon.'));
		$this->assertEquals('Here is &#8220;<a href="http://example.com">a test with a link</a>&#8221;- and a dash.', wptexturize('Here is "<a href="http://example.com">a test with a link</a>"- and a dash.'));
		$this->assertEquals('Here is &#8220;<a href="http://example.com">a test with a link</a>&#8221;... and ellipses.', wptexturize('Here is "<a href="http://example.com">a test with a link</a>"... and ellipses.'));
		$this->assertEquals('Here is &#8220;a test <a href="http://example.com">with a link</a>&#8221;.', wptexturize('Here is "a test <a href="http://example.com">with a link</a>".'));
		$this->assertEquals('Here is &#8220;<a href="http://example.com">a test with a link</a>&#8221;and a work stuck to the end.', wptexturize('Here is "<a href="http://example.com">a test with a link</a>"and a work stuck to the end.'));
		$this->assertEquals('A test with a finishing number, &#8220;like 23&#8221;.', wptexturize('A test with a finishing number, "like 23".'));
		$this->assertEquals('A test with a number, &#8220;like 62&#8221;, is nice to have.', wptexturize('A test with a number, "like 62", is nice to have.'));
	}
	
	//WP Ticket #1258
	function test_quotes_before_s() {
		$this->knownWPBug(4539);
		$this->assertEquals('test&#8217;s', wptexturize("test's"));
		$this->assertEquals('&#8216;test&#8217;s', wptexturize("'test's"));
		$this->assertEquals('&#8216;test&#8217;s&#8217;', wptexturize("'test's'"));
		$this->assertEquals('&#8216;string&#8217;', wptexturize("'string'"));
		$this->assertEquals('&#8216;string&#8217;s&#8217;', wptexturize("'string's'"));
	}

	//WP Ticket #4539
	function test_quotes_before_numbers() {
		$this->knownWPBug(4539);
		$this->assertEquals('Class of &#8217;99', wptexturize("Class of '99"));
		$this->assertEquals('&#8216;Class of &#8217;99&#8217;', wptexturize("'Class of '99'"));
	}
}

class TestCleanUrl extends WPTestCase {
	function test_spaces() {
		$this->assertEquals('http://example.com/MrWordPress', clean_url('http://example.com/Mr WordPress'));
		$this->assertEquals('http://example.com/Mr%20WordPress', clean_url('http://example.com/Mr%20WordPress'));
	}
	
	function test_bad_characters() {
		$this->assertEquals('http://example.com/watchthelinefeedgo', clean_url('http://example.com/watchthelinefeed%0Ago'));
		$this->assertEquals('http://example.com/watchthelinefeedgo', clean_url('http://example.com/watchthelinefeed%0ago'));
		$this->assertEquals('http://example.com/watchthecarriagereturngo', clean_url('http://example.com/watchthecarriagereturn%0Dgo'));
		$this->assertEquals('http://example.com/watchthecarriagereturngo', clean_url('http://example.com/watchthecarriagereturn%0dgo'));
		//Nesting Checks
		$this->assertEquals('http://example.com/watchthecarriagereturngo', clean_url('http://example.com/watchthecarriagereturn%0%0ddgo'));
		$this->assertEquals('http://example.com/watchthecarriagereturngo', clean_url('http://example.com/watchthecarriagereturn%0%0DDgo'));
		$this->assertEquals('http://example.com/', clean_url('http://example.com/%0%0%0DAD'));
		$this->assertEquals('http://example.com/', clean_url('http://example.com/%0%0%0ADA'));
		$this->assertEquals('http://example.com/', clean_url('http://example.com/%0%0%0DAd'));
		$this->assertEquals('http://example.com/', clean_url('http://example.com/%0%0%0ADa'));
	}

	function test_relative() {
		$this->assertEquals('/example.php', clean_url('/example.php'));
		$this->assertEquals('example.php', clean_url('example.php'));
	}
	
	function test_protocol() {
		$this->assertEquals('http://example.com', clean_url('http://example.com'));
		$this->assertEquals('', clean_url('nasty://example.com/'));
	}
	
	function test_display_extras() {
		$this->assertEquals('http://example.com/&#039;quoted&#039;', clean_url('http://example.com/\'quoted\''));
		$this->assertEquals('http://example.com/\'quoted\'', clean_url('http://example.com/\'quoted\'',null,'notdisplay'));
	}
	
	function test_non_ascii() {
		$this->assertEquals( 'http://example.org/баба', clean_url( 'http://example.org/баба' ) );
		$this->assertEquals( 'http://баба.org/баба', clean_url( 'http://баба.org/баба' ) );
		$this->assertEquals( 'http://müller.com/', clean_url( 'http://müller.com/' ) );
	}
}

class TestAutop extends WPTestCase {
	//From ticket http://core.trac.wordpress.org/ticket/11008
	function test_first_post() {
		$expected = '<p>Welcome to WordPress!  This post contains important information.  After you read it, you can make it private to hide it from visitors but still have the information handy for future reference.</p>
<p>First things first:</p>
<ul>
<li><a href="%1$s" title="Subscribe to the WordPress mailing list for Release Notifications">Subscribe to the WordPress mailing list for release notifications</a></li>
</ul>
<p>As a subscriber, you will receive an email every time an update is available (and only then).  This will make it easier to keep your site up to date, and secure from evildoers.<br />
When a new version is released, <a href="%2$s" title="If you are already logged in, this will take you directly to the Dashboard">log in to the Dashboard</a> and follow the instructions.<br />
Upgrading is a couple of clicks!</p>
<p>Then you can start enjoying the WordPress experience:</p>
<ul>
<li>Edit your personal information at <a href="%3$s" title="Edit settings like your password, your display name and your contact information">Users &#8250; Your Profile</a></li>
<li>Start publishing at <a href="%4$s" title="Create a new post">Posts &#8250; Add New</a> and at <a href="%5$s" title="Create a new page">Pages &#8250; Add New</a></li>
<li>Browse and install plugins at <a href="%6$s" title="Browse and install plugins at the official WordPress repository directly from your Dashboard">Plugins &#8250; Add New</a></li>
<li>Browse and install themes at <a href="%7$s" title="Browse and install themes at the official WordPress repository directly from your Dashboard">Appearance &#8250; Add New Themes</a></li>
<li>Modify and prettify your website&#8217;s links at <a href="%8$s" title="For example, select a link structure like: http://example.com/1999/12/post-name">Settings &#8250; Permalinks</a></li>
<li>Import content from another system or WordPress site at <a href="%9$s" title="WordPress comes with importers for the most common publishing systems">Tools &#8250; Import</a></li>
<li>Find answers to your questions at the <a href="%10$s" title="The official WordPress documentation, maintained by the WordPress community">WordPress Codex</a></li>
</ul>
<p>To keep this post for reference, <a href="%11$s" title="Click to edit the content and settings of this post">click to edit it</a>, go to the Publish box and change its Visibility from Public to Private.</p>
<p>Thank you for selecting WordPress.  We wish you happy publishing!</p>
<p>PS.  Not yet subscribed for update notifications?  <a href="%1$s" title="Subscribe to the WordPress mailing list for Release Notifications">Do it now!</a></p>
';
		$test_data = '
Welcome to WordPress!  This post contains important information.  After you read it, you can make it private to hide it from visitors but still have the information handy for future reference.

First things first:
<ul>
<li><a href="%1$s" title="Subscribe to the WordPress mailing list for Release Notifications">Subscribe to the WordPress mailing list for release notifications</a></li>
</ul>
As a subscriber, you will receive an email every time an update is available (and only then).  This will make it easier to keep your site up to date, and secure from evildoers.
When a new version is released, <a href="%2$s" title="If you are already logged in, this will take you directly to the Dashboard">log in to the Dashboard</a> and follow the instructions.
Upgrading is a couple of clicks!

Then you can start enjoying the WordPress experience:
<ul>
<li>Edit your personal information at <a href="%3$s" title="Edit settings like your password, your display name and your contact information">Users &#8250; Your Profile</a></li>
<li>Start publishing at <a href="%4$s" title="Create a new post">Posts &#8250; Add New</a> and at <a href="%5$s" title="Create a new page">Pages &#8250; Add New</a></li>
<li>Browse and install plugins at <a href="%6$s" title="Browse and install plugins at the official WordPress repository directly from your Dashboard">Plugins &#8250; Add New</a></li>
<li>Browse and install themes at <a href="%7$s" title="Browse and install themes at the official WordPress repository directly from your Dashboard">Appearance &#8250; Add New Themes</a></li>
<li>Modify and prettify your website&#8217;s links at <a href="%8$s" title="For example, select a link structure like: http://example.com/1999/12/post-name">Settings &#8250; Permalinks</a></li>
<li>Import content from another system or WordPress site at <a href="%9$s" title="WordPress comes with importers for the most common publishing systems">Tools &#8250; Import</a></li>
<li>Find answers to your questions at the <a href="%10$s" title="The official WordPress documentation, maintained by the WordPress community">WordPress Codex</a></li>
</ul>
To keep this post for reference, <a href="%11$s" title="Click to edit the content and settings of this post">click to edit it</a>, go to the Publish box and change its Visibility from Public to Private.

Thank you for selecting WordPress.  We wish you happy publishing!

PS.  Not yet subscribed for update notifications?  <a href="%1$s" title="Subscribe to the WordPress mailing list for Release Notifications">Do it now!</a>
';
		$this->assertEquals($expected, wpautop($test_data));
	}
}

class TestLikeEscape extends WPTestCase {
	function test_like_escape() {
		$this->knownWPBug(10041);

		$inputs = array(
			'howdy%', //Single Percent
			'howdy_', //Single Underscore
			'howdy\\', //Single slash
			'howdy\\howdy%howdy_', //The works
		);
		$expected = array(
			"howdy\\%",
			'howdy\\_',
			'howdy\\\\',
			'howdy\\\\howdy\\%howdy\\_'
		);
		
		foreach ($inputs as $key => $input) {
			$this->assertEquals($expected[$key], like_escape($input));
		}
	}

}

class TestSanitizeTextField extends WPTestCase {
	function test_sanitize_text_field() {
		$this->knownWPBug(11528);
		$inputs = array(
			'оРангутанг', //Ensure UTF8 text is safe the Р is D0 A0 and A0 is the non-breaking space.
			'САПР', //Ensure UTF8 text is safe the Р is D0 A0 and A0 is the non-breaking space.
			'one is < two',
			'tags <span>are</span> <em>not allowed</em> here',
			' we should trim leading and trailing whitespace ',
			'we  also  trim  extra  internal  whitespace',
			'tabs 	get removed too',
			'newlines are not welcome 
			here',
			'We also %AB remove %ab octets',
			'We don\'t need to wory about %A
			B removing %a
			b octets even when %a	B they are obscured by whitespace',
			'%AB%BC%DE', //Just octets
			'Invalid octects remain %II',
			'Nested octects %%%ABABAB %A%A%ABBB',
		);
		$expected = array(
			'оРангутанг',
			'САПР',
			'one is &lt; two',
			'tags are not allowed here',
			'we should trim leading and trailing whitespace',
			'we also trim extra internal whitespace',
			'tabs get removed too',
			'newlines are not welcome here',
			'We also remove octets',
			'We don\'t need to wory about %A B removing %a b octets even when %a B they are obscured by whitespace',
			'', //Emtpy as we strip all the octets out
			'Invalid octects remain %II',
			'Nested octects',
		);
		
		foreach ($inputs as $key => $input) {
			$this->assertEquals($expected[$key], sanitize_text_field($input));
		}
	}
}

?>
