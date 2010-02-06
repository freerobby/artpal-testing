<?php

class TestObjectCache extends WPTestCase {
	var $cache = NULL;

	function setUp() {
		parent::setUp();
		// create two cache objects with a shared cache dir
		// this simulates a typical cache situation, two separate requests interacting
		$this->cache =& $this->init_cache();
	}

	function tearDown() {
		parent::tearDown();
		wp_cache_flush();
	}

	function &init_cache() {
		$cache = new WP_Object_cache();
		return $cache;
	}

	function test_miss() {
		$this->assertEquals(NULL, $this->cache->get(rand_str()));
	}

	function test_add_get() {
		$key = rand_str();
		$val = rand_str();

		$this->cache->add($key, $val);
		$this->assertEquals($val, $this->cache->get($key));
	}

	function test_add_get_0() {
		$key = rand_str();
		$val = 0;

		// you can store zero in the cache
		$this->cache->add($key, $val);
		$this->assertEquals($val, $this->cache->get($key));
	}

	function test_add_get_null() {
		$key = rand_str();
		$val = null;

		// you can't store null in the cache
		$this->assertFalse($this->cache->add($key, $val));
		$this->assertFalse($this->cache->get($key));
	}

	function test_add() {
		$key = rand_str();
		$val1 = rand_str();
		$val2 = rand_str();

		// add $key to the cache
		$this->assertTrue($this->cache->add($key, $val1));
		$this->assertEquals($val1, $this->cache->get($key));
		// $key is in the cache, so reject new calls to add()
		$this->assertFalse($this->cache->add($key, $val2));
		$this->assertEquals($val1, $this->cache->get($key));
	}

	function test_replace() {
		$key = rand_str();
		$val = rand_str();

		// memcached rejects replace() if the key does not exist
		$this->assertFalse($this->cache->replace($key, $val));
		$this->assertFalse($this->cache->get($key));
	}

	function test_set() {
		$key = rand_str();
		$val = rand_str();

		// memcached accepts set() if the key does not exist
		$this->assertTrue($this->cache->set($key, $val));
		$this->assertEquals($val, $this->cache->get($key));
	}

	function test_flush() {
		$key = rand_str();
		$val = rand_str();

		$this->cache->add($key, $val);
		// item is visible to both cache objects
		$this->assertEquals($val, $this->cache->get($key));
		$this->cache->flush();
		// If there is no value get returns false.
		$this->assertFalse($this->cache->get($key));
	}

}

?>
