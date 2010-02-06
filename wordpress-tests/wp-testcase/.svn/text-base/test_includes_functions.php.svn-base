<?php

class TestFunctions extends WPTestCase {
	var $cache = NULL;

	function setUp() {
		parent::setUp();
	}

	function tearDown() {
		parent::tearDown();
	}

	function test_wp_parse_args_object() {
		$x = new MockClass;
		$x->_baba = 5;
		$x->yZ = "baba";
		$x->a = array(5, 111, 'x');
		$this->assertEquals(array('_baba' => 5, 'yZ' => 'baba', 'a' => array(5, 111, 'x')), wp_parse_args($x));
		$y = new MockClass;
		$this->assertEquals(array(), wp_parse_args($y));
	}
	function test_wp_parse_args_array()  {
		// arrays
		$a = array();
		$this->assertEquals(array(), wp_parse_args($x));
		$b = array('_baba' => 5, 'yZ' => 'baba', 'a' => array(5, 111, 'x'));
		$this->assertEquals(array('_baba' => 5, 'yZ' => 'baba', 'a' => array(5, 111, 'x')), wp_parse_args($b));
	}
	function test_wp_parse_args_defaults() {
		$x = new MockClass;
		$x->_baba = 5;
		$x->yZ = "baba";
		$x->a = array(5, 111, 'x');
		$d = array('pu' => 'bu');
		$this->assertEquals(array('pu' => 'bu', '_baba' => 5, 'yZ' => 'baba', 'a' => array(5, 111, 'x')), wp_parse_args($x, $d));
		$e = array('_baba' => 6);
		$this->assertEquals(array('_baba' => 5, 'yZ' => 'baba', 'a' => array(5, 111, 'x')), wp_parse_args($x, $e));
	}
	function test_wp_parse_args_other() {
		$b = true;
		wp_parse_str($b, $s);
		$this->assertEquals($s, wp_parse_args($b));
		$q = 'x=5&_baba=dudu&';
		wp_parse_str($q, $ss);
		$this->assertEquals($ss, wp_parse_args($q));
	}
	function test_size_format() {
		$this->knownWPBug(5246);
		$kb = 1024;
		$mb = $kb*1024;
		$gb = $mb*1024;
		$tb = $gb*1024;
		// test if boundaries are correct
		$this->assertEquals('1 GB', size_format($gb, 0));
		$this->assertEquals('1 MB', size_format($mb, 0));
		$this->assertEquals('1 kB', size_format($kb, 0));
		// now some values around
		// add some bytes to make sure the result isn't 1.4999999
		$this->assertEquals('1.5 TB', size_format($tb + $tb/2 + $mb, 1));
		$this->assertEquals('1,023.999 GB', size_format($tb-$mb-$kb, 3));
		// edge
		$this->assertFalse(size_format(-1));
		$this->assertFalse(size_format(0));
		$this->assertFalse(size_format('baba'));
		$this->assertFalse(size_format(array()));
	}
	
	function test_path_is_absolute() {
		if ( !is_callable('path_is_absolute') )
			$this->markTestSkipped();
			
		$absolute_paths = array(
			'/',
			'/foo/',
			'/foo',
			'/FOO/bar',
			'/foo/bar/',
			'/foo/../bar/',
			'\\WINDOWS',
			'C:\\',
			'C:\\WINDOWS',
			'\\\\sambashare\\foo',
			);
		foreach ($absolute_paths as $path)
			$this->assertTrue( path_is_absolute($path), "path_is_absolute('$path') should return true" );
	}

	function test_path_is_not_absolute() {
		if ( !is_callable('path_is_absolute') )
			$this->markTestSkipped();

		$relative_paths = array(
			'',
			'.',
			'..',
			'../foo',
			'../',
			'../foo.bar',
			'foo/bar',
			'foo',
			'FOO',
			'..\\WINDOWS',
			);
		foreach ($relative_paths as $path)
			$this->assertFalse( path_is_absolute($path), "path_is_absolute('$path') should return false" );
	}


	function test_wp_unique_filename() {
		$this->knownWPBug(6294);	
		
		/* this test requires:
		   - that you have dir + file 'wp-testdata/images/test-image.png', 
		   - and that this dir is writeable
		   - there is an image 'test-image.png' that will be used to test unique filenames
		
		   NB: there is a hardcoded dependency that the testing file is '.png'; however,
		       this limitation is arbitary, so change it if you like.
		*/
		$testdir = realpath('.') . '/wp-testdata/images/';
		$testimg = 'test-image.png';
		$this->assertTrue( file_exists($testdir) );
		$this->assertTrue( is_writable($testdir) );
		$this->assertTrue( file_exists($testdir . $testimg) );
		
		$cases = array(
				// null case
				'null' . $testimg,
			
				// edge cases: '.png', 'abc.', 'abc',
				// 'abc0', 'abc1', 'abc0.png', 'abc1.png' (num @ end)
				'.png',
				'abc',
				'abc.',
				'abc0',
				'abc1',
				'abc0.png',
				'abc1.png',
				
			  // replacing # with _
				str_replace('-', '#', $testimg), // test#image.png
				str_replace('-', '##', $testimg), // test##image.png
				str_replace(array('-', 'e'), '#', $testimg), // t#st#imag#.png
				str_replace(array('-', 'e'), '##', $testimg), // t##st##imag##.png
				
				// replacing \ or ' with nothing
				str_replace('-', '\\', $testimg), // test\image.png
				str_replace('-', '\\\\', $testimg), // test\\image.png
				str_replace(array('-', 'e'), '\\', $testimg), // t\st\imag\.png
				str_replace(array('-', 'e'), '\\\\', $testimg), // t\\st\\imag\\.png
				str_replace('-', "'", $testimg), // test'image.png
				str_replace('-', "'", $testimg), // test''image.png
				str_replace(array('-', 'e'), "'", $testimg), // t'st'imag'.png
				str_replace(array('-', 'e'), "''", $testimg), // t''st''imag''.png
				str_replace('-', "\'", $testimg), // test\'image.png
				str_replace('-', "\'\'", $testimg), // test\'\'image.png
				str_replace(array('-', 'e'), "\'", $testimg), // t\'st\'imag\'.png
				str_replace(array('-', 'e'), "\'\'", $testimg), // t\'\'st\'\'imag\'\'.png
				
				// sanitize_title_with_dashes
				// incomplete coverage; attempts to cover the most common cases
				str_replace('-', '%', $testimg), // test%image.png
				
				'test' . str_replace('e', 'é', $testimg), // tést-imagé.png
				
				'12%af34567890~!@#$..%^&*()_+qwerty  uiopasd fghjkl zxcvbnm<>?:"{}".png', // kitchen sink
				$testdir.'test-image-with-path.png',
			);
		
		// what we expect the replacements will do
		$expected = array(
				'null' . $testimg,
				
				'.png',
				'abc',
				'abc',
				'abc0',
				'abc1',
				'abc0.png',
				'abc1.png',
				
				'testimage.png',
				'testimage.png',
				'tstimag.png',
				'tstimag.png',
				
				'testimage.png',
				'testimage.png',
				'tstimag.png',
				'tstimag.png',
				'testimage.png',
				'testimage.png',
				'tstimag.png',
				'tstimag.png',
				'testimage.png',
				'testimage.png',
				'tstimag.png',
				'tstimag.png',
				
				'testimage.png',
				
				'testtest-image.png',
				
				'12%af34567890_qwerty-uiopasd-fghjkl-zxcvbnm.png',
				'test-image-with-path.png',
			);
		
		foreach ($cases as $key => $case)
		{
			// make sure expected file doesn't exist already
			// happens when tests fail and the unlinking doesn't happen
			if( $expected[$key] !== $testimg && file_exists($testdir . $expected[$key]) )
				unlink($testdir . $expected[$key]);

			// -- TEST 1: the replacement is as expected
			
			$this->assertEquals( $expected[$key], wp_unique_filename($testdir, $case, NULL), $case );
			
			// -- end TEST 1
			
			
			
			// -- TEST 2: the renaming will produce a unique name 
			
			// create the expected file
			copy($testdir . $testimg, $testdir . $expected[$key]);
			
			// test that wp_unique_filename actually returns a unique filename
			$this->assertFileNotExists( $testdir . wp_unique_filename($testdir, $case, NULL) );
			
			// -- end TEST 2
			
			
			
			// cleanup
			if( $expected[$key] !== $testimg &&  file_exists($testdir . $expected[$key]) )
				unlink($testdir . $expected[$key]);
		}
	}
	
	function test_is_serialized() {
		$this->knownWPBug(9930);
		$cases = array(
					   serialize("a\nb"),
					   serialize(-25),
					   serialize(25),
					   serialize(false),
					   serialize(null),
					   serialize(array()),
					   serialize(1.1),
					   serialize(2.1E+200),
					   serialize( (object)array('test' => true, '3', 4) )
					   );

		foreach ( $cases as $case )
			$this->assertTrue( is_serialized($case), "Serialized data: $case" );
			
		$not_serialized = array(
									"a string",
									"garbage:a:0:garbage;"
								);
								
		foreach ( $not_serialized as $case )
			$this->assertFalse( is_serialized($case), "Test data: $case" );
	}
	
}

class TestHTTPFunctions extends WPTestCase {

	function test_head_request() {
		// this url give a direct 200 response
		$url = 'http://asdftestblog1.files.wordpress.com/2007/09/2007-06-30-dsc_4700-1.jpg';
		
		$headers = wp_get_http_headers($url);
		
		$this->assertTrue( is_array($headers) );
		$this->assertEquals( 'image/jpeg', $headers['content-type'] );
		$this->assertEquals( '40148', $headers['content-length'] );
		$this->assertEquals( '200', $headers['response'] );
	}
	
	function test_head_redirect() {
		// this url will 302 redirect
		$url = 'http://asdftestblog1.wordpress.com/files/2007/09/2007-06-30-dsc_4700-1.jpg';
		
		$headers = wp_get_http_headers($url);
		
		$this->assertTrue( is_array($headers) );
		$this->assertEquals( 'image/jpeg', $headers['content-type'] );
		$this->assertEquals( '40148', $headers['content-length'] );
		$this->assertEquals( '200', $headers['response'] );
	}
	
	function test_head_redirect_limit_exceeded() {
		// this url will 302 redirect
		$url = 'http://asdftestblog1.wordpress.com/files/2007/09/2007-06-30-dsc_4700-1.jpg';
		
		// pretend we've already done 5 redirects
		$result = wp_get_http_headers($url, 6);
		
		$this->assertFalse($result);
	}
	
	function test_head_404() {
		$url = 'http://asdftestblog1.wordpress.com/files/2007/09/asdfasdfasdf.jpg';
		
		$headers = wp_get_http_headers($url);
		
		$this->assertTrue( is_array($headers) );
		$this->assertEquals( '404', $headers['response'] );
	}
		

	function test_get_request() {
		$url = 'http://asdftestblog1.files.wordpress.com/2007/09/2007-06-30-dsc_4700-1.jpg';
		$file = tempnam('/tmp', 'testfile');
		
		$headers = wp_get_http($url, $file);
		
		// should return the same headers as a head request
		$this->assertTrue( is_array($headers) );
		$this->assertEquals( 'image/jpeg', $headers['content-type'] );
		$this->assertEquals( '40148', $headers['content-length'] );
		$this->assertEquals( '200', $headers['response'] );
		
		// make sure the file is ok
		$this->assertEquals( 40148, filesize($file) );
		$this->assertEquals( 'b0371a0fc575fcf77f62cd298571f53b', md5_file($file) );
	}

	function test_get_redirect() {
		// this will redirect to asdftestblog1.files.wordpress.com
		$url = 'http://asdftestblog1.wordpress.com/files/2007/09/2007-06-30-dsc_4700-1.jpg';
		$file = tempnam('/tmp', 'testfile');
		
		$headers = wp_get_http($url, $file);
		
		// should return the same headers as a head request
		$this->assertTrue( is_array($headers) );
		$this->assertEquals( 'image/jpeg', $headers['content-type'] );
		$this->assertEquals( '40148', $headers['content-length'] );
		$this->assertEquals( '200', $headers['response'] );
		
		// make sure the file is ok
		$this->assertEquals( 40148, filesize($file) );
		$this->assertEquals( 'b0371a0fc575fcf77f62cd298571f53b', md5_file($file) );
	}

	function test_get_redirect_limit_exceeded() {
		// this will redirect to asdftestblog1.files.wordpress.com
		$url = 'http://asdftestblog1.wordpress.com/files/2007/09/2007-06-30-dsc_4700-1.jpg';
		$file = tempnam('/tmp', 'testfile');
		
		// pretent we've already redirected 5 times
		$headers = wp_get_http($url, $file, 6);
		$this->assertFalse($headers);
		
	}


}

?>
