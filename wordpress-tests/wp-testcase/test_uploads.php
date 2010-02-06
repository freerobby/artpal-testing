<?php

// test various functions used by the uploader

class TestUploadFunctions extends WPTestCase {
	
	var $siteurl = 'http://example.com/foo';
	
	function setUp() {
		update_option( 'siteurl', $this->siteurl );
		// system defaults
		update_option( 'upload_path', 'wp-content/uploads' );
		update_option( 'upload_url_path', '' );
		update_option( 'uploads_use_yearmonth_folders', 1 );
		
	}
	
	function test_upload_dir_default() {
		// wp_upload_dir() with default parameters
		$info = wp_upload_dir();
		$this->assertEquals( $this->siteurl . '/wp-content/uploads/' . gmstrftime('%Y/%m'), $info['url'] );
		$this->assertEquals( ABSPATH . 'wp-content/uploads/' . gmstrftime('%Y/%m'), $info['path'] );
		$this->assertEquals( gmstrftime('/%Y/%m'), $info['subdir'] );
		$this->assertEquals( '', $info['error'] );
	}
	
	function test_upload_dir_relative() {
		// wp_upload_dir() with a relative upload path
		update_option( 'upload_path', 'foo/bar' );
		$info = wp_upload_dir();
		$this->assertEquals( $this->siteurl . '/foo/bar/' . gmstrftime('%Y/%m'), $info['url'] );
		$this->assertEquals( ABSPATH . 'foo/bar/' . gmstrftime('%Y/%m'), $info['path'] );
		$this->assertEquals( gmstrftime('/%Y/%m'), $info['subdir'] );
		$this->assertEquals( '', $info['error'] );
	}
	
	function test_upload_dir_absolute() {
		$this->knownWPBug(5953);
		// wp_upload_dir() with an absolute upload path
		update_option( 'upload_path', '/tmp' );
		// doesn't make sense to use an absolute file path without setting the url path
		update_option( 'upload_url_path', '/baz' );
		$info = wp_upload_dir();
		$this->assertEquals( '/baz/' . gmstrftime('%Y/%m'), $info['url'] );
		$this->assertEquals( '/tmp/' . gmstrftime('%Y/%m'), $info['path'] );
		$this->assertEquals( gmstrftime('/%Y/%m'), $info['subdir'] );
		$this->assertEquals( '', $info['error'] );
	}
	
	function test_upload_dir_no_yearnum() {
		update_option( 'uploads_use_yearmonth_folders', 0 );
		$info = wp_upload_dir();
		$this->assertEquals( $this->siteurl . '/wp-content/uploads', $info['url'] );
		$this->assertEquals( ABSPATH . 'wp-content/uploads', $info['path'] );
		$this->assertEquals( '', $info['subdir'] );
		$this->assertEquals( '', $info['error'] );
	}
	
	function test_upload_path_absolute() {
		update_option( 'upload_url_path', 'http://example.org/asdf' );
		$info = wp_upload_dir();
		$this->assertEquals( 'http://example.org/asdf/' . gmstrftime('%Y/%m'), $info['url'] );
		$this->assertEquals( ABSPATH . 'wp-content/uploads/' . gmstrftime('%Y/%m'), $info['path'] );
		$this->assertEquals( gmstrftime('/%Y/%m'), $info['subdir'] );
		$this->assertEquals( '', $info['error'] );
	}
	
	function test_upload_dir_empty() {
		// upload path setting is empty - it should default to 'wp-content/uploads'
		update_option('upload_path', '');
		$info = wp_upload_dir();
		$this->assertEquals( $this->siteurl . '/wp-content/uploads/' . gmstrftime('%Y/%m'), $info['url'] );
		$this->assertEquals( ABSPATH . 'wp-content/uploads/' . gmstrftime('%Y/%m'), $info['path'] );
		$this->assertEquals( gmstrftime('/%Y/%m'), $info['subdir'] );
		$this->assertEquals( '', $info['error'] );
	}
	
}

?>