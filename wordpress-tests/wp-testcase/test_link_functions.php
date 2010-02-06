<?php

// tests for link-template.php and related URL functions


class TestSSLLinks extends WPTestCase {
	var $_old_server;
	function setUp() {
		$this->_old_server = $_SERVER;
	}
	
	function tearDown() {
		$_SERVER = $this->_old_server;
	}
	
	function test_is_ssl_positive() {
		$this->knownWPBug(7001);
		
		$_SERVER['HTTPS'] = 'on';
		$this->assertTrue( is_ssl() );
		
		$_SERVER['HTTPS'] = 'ON';
		$this->assertTrue( is_ssl() );
	}
	
	function test_is_ssl_negative() {
		$this->knownWPBug(7001);

		$_SERVER['HTTPS'] = 'off';
		$this->assertFalse( is_ssl() );
		
		$_SERVER['HTTPS'] = 'OFF';
		$this->assertFalse( is_ssl() );
		
		unset($_SERVER['HTTPS']);
		$this->assertFalse( is_ssl() );
	}
	
	function test_admin_url_valid() {
		$this->knownWPBug(7001);
		
		$siteurl = get_option('siteurl');
		
		$paths = array(
				'' => "/wp-admin/",
				'foo' => "/wp-admin/foo",
				'/foo' => "/wp-admin/foo",
				'/foo/' => "/wp-admin/foo/",
				'foo.php' => "/wp-admin/foo.php",
				'/foo.php' => "/wp-admin/foo.php",
				'/foo.php?bar=1' => "/wp-admin/foo.php?bar=1",
			);
		$https = array('on', 'off');
		
		foreach ($https as $val) {
			$_SERVER['HTTPS'] = $val;
			$siteurl = get_option('siteurl');
			if ( $val == 'on' )
				$siteurl = str_replace('http://', 'https://', $siteurl);
				
			foreach ($paths as $in => $out) {
				$this->assertEquals( $siteurl.$out, admin_url($in), "admin_url('{$in}') should equal '{$siteurl}{$out}'");
			}
		}
	}

	function test_admin_url_invalid() {
		$this->knownWPBug(7001);
		
		$siteurl = get_option('siteurl');
		
		$paths = array(
				null => "/wp-admin/",
				0 => "/wp-admin/",
				-1 => "/wp-admin/",
				'../foo/' => "/wp-admin/",
				' ' => "/wp-admin/",
			);
		$https = array('on', 'off');
		
		foreach ($https as $val) {
			$_SERVER['HTTPS'] = $val;
			$siteurl = get_option('siteurl');
			if ( $val == 'on' )
				$siteurl = str_replace('http://', 'https://', $siteurl);
				
			foreach ($paths as $in => $out) {
				$this->assertEquals( $siteurl.$out, admin_url($in), "admin_url('{$in}') should equal '{$siteurl}{$out}'");
			}
		}
	}
}

?>