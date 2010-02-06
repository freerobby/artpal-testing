<?php

// test the functions for fetching taxonomy meta data
class TestTaxonomyMeta extends _WPEmptyBlog {
	function test_get_post_taxonomies() {
		$this->assertEquals(array('category', 'post_tag'), get_object_taxonomies('post'));
	}
	
	function test_get_link_taxonomies() {
		$this->assertEquals(array('link_category'), get_object_taxonomies('link'));
	}
	
	function test_get_unknown_taxonomies() {
		// taxonomies for an unknown object type
		$this->knownWPBug(5417);
		$this->assertEquals( array(), get_object_taxonomies(rand_str()) );
		$this->assertEquals( array(), get_object_taxonomies('') );
		$this->assertEquals( array(), get_object_taxonomies(0) );
		$this->assertEquals( array(), get_object_taxonomies(NULL) );
	}
	
	function test_get_post_taxonomy() {
		foreach ( get_object_taxonomies('post') as $taxonomy ) {
			$tax = get_taxonomy($taxonomy);
			// should return an object with the correct taxonomy object type
			$this->assertTrue( is_object($tax) );
			$this->assertEquals( 'post', $tax->object_type );
		}
	}

	function test_get_link_taxonomy() {
		foreach ( get_object_taxonomies('link') as $taxonomy ) {
			$tax = get_taxonomy($taxonomy);
			// should return an object with the correct taxonomy object type
			$this->assertTrue( is_object($tax) );
			$this->assertEquals( 'link', $tax->object_type );
		}
	}

	function test_is_taxonomy_known() {
		$this->assertTrue( is_taxonomy('category') );
		$this->assertTrue( is_taxonomy('post_tag') );
		$this->assertTrue( is_taxonomy('link_category') );
	}
	
	function test_is_taxonomy_unknown() {
		$this->assertFalse( is_taxonomy(rand_str()) );
		$this->assertFalse( is_taxonomy('') );
		$this->assertFalse( is_taxonomy(0) );
		$this->assertFalse( is_taxonomy(NULL) );
	}
	
	function test_is_taxonomy_hierarchical() {
		$this->assertTrue( is_taxonomy_hierarchical('category') );
		$this->assertFalse( is_taxonomy_hierarchical('post_tag') );
		$this->assertFalse( is_taxonomy_hierarchical('link_category') );
	}
	
	function test_is_taxonomy_hierarchical_unknown() {
		$this->assertFalse( is_taxonomy_hierarchical(rand_str()) );
		$this->assertFalse( is_taxonomy_hierarchical('') );
		$this->assertFalse( is_taxonomy_hierarchical(0) );
		$this->assertFalse( is_taxonomy_hierarchical(NULL) );
	}
	
	function test_register_taxonomy() {
		
		// make up a new taxonomy name, and ensure it's unused
		$tax = rand_str();
		$this->assertFalse( is_taxonomy($tax) );
		
		register_taxonomy( $tax, 'post' );
		$this->assertTrue( is_taxonomy($tax) );
		$this->assertFalse( is_taxonomy_hierarchical($tax) );		
		
		// clean up
		unset($GLOBALS['wp_taxonomies'][$tax]);
	}
	
	function test_register_hierarchical_taxonomy() {
		
		// make up a new taxonomy name, and ensure it's unused
		$tax = rand_str();
		$this->assertFalse( is_taxonomy($tax) );
		
		register_taxonomy( $tax, 'post', array('hierarchical'=>true) );
		$this->assertTrue( is_taxonomy($tax) );
		$this->assertTrue( is_taxonomy_hierarchical($tax) );		
		
		// clean up
		unset($GLOBALS['wp_taxonomies'][$tax]);
	}
}

class TestTermAPI extends _WPEmptyBlog {
	var $taxonomy = 'category';
	
	function setUp() {
		parent::setUp();
		// insert one term into every post taxonomy
		// otherwise term_ids and term_taxonomy_ids might be identical, which could mask bugs
		$term = rand_str();
		foreach(get_object_taxonomies('post') as $tax)
			wp_insert_term( $term, $tax );
	}
	
	function test_wp_insert_delete_term() {
		// a new unused term
		$term = rand_str();
		$this->assertNull( is_term($term) );
		
		$initial_count = wp_count_terms( $this->taxonomy );
		
		$t = wp_insert_term( $term, $this->taxonomy );
		$this->assertTrue( is_array($t) );
		$this->assertFalse( is_wp_error($t) );
		$this->assertTrue( $t['term_id'] > 0 );
		$this->assertTrue( $t['term_taxonomy_id'] > 0 );
		$this->assertEquals( $initial_count + 1, wp_count_terms($this->taxonomy) );
		
		// make sure the term exists
		$this->assertTrue( is_term($term) > 0 );
		$this->assertTrue( is_term($t['term_id']) > 0 );
		
		// now delete it
		$this->assertTrue( wp_delete_term($t['term_id'], $this->taxonomy) );
		$this->assertNull( is_term($term) );
		$this->assertNull( is_term($t['term_id']) );
		$this->assertEquals( $initial_count, wp_count_terms($this->taxonomy) );
	}
	
	function test_is_term_known() {
		// insert a term
		$term = rand_str();
		$t = wp_insert_term( $term, $this->taxonomy );
		
		$this->assertEquals( $t['term_id'], is_term($t['term_id']) );
		$this->assertEquals( $t['term_id'], is_term($term) );
		
		// clean up
		$this->assertTrue( wp_delete_term($t['term_id'], $this->taxonomy) );
	}
	
	function test_is_term_unknown() {
		$this->assertNull( is_term(rand_str()) );
		$this->assertEquals( 0, is_term(0) );
		$this->assertEquals( 0, is_term('') );
		$this->assertEquals( 0, is_term(NULL) );
	}
	
	function test_is_term_type() {
		// insert a term
		$this->knownWPBug(5381);
		$term = rand_str();
		$t = wp_insert_term( $term, $this->taxonomy );

		$term_obj = get_term_by('name', $term, $this->taxonomy);
		$this->assertEquals( $t['term_id'], is_term($term_obj->term_id) );

		// clean up
		$this->assertTrue( wp_delete_term($t['term_id'], $this->taxonomy) );
	}
	
	function test_set_object_terms_by_id() {
		$this->_insert_quick_posts(5);
			
		$terms = array();
		for ($i=0; $i<3; $i++ ) {
			$term = rand_str();
			$result = wp_insert_term( $term, $this->taxonomy );
			$term_id[$term] = $result['term_id'];
		}
		
		foreach ($this->post_ids as $id) {
				$tt = wp_set_object_terms( $id, array_values($term_id), $this->taxonomy );
				// should return three term taxonomy ids
				$this->assertEquals( 3, count($tt) );
		}

		// each term should be associated with every post
		foreach ($term_id as $term=>$id) {
			$actual = get_objects_in_term($id, $this->taxonomy);
			$this->assertEquals( $this->post_ids, array_map('intval', $actual) );
		}
		
		// each term should have a count of 5
		foreach (array_keys($term_id) as $term) {
			$t = get_term_by('name', $term, $this->taxonomy);
			$this->assertEquals( 5, $t->count );
		}
	}
	
	function test_set_object_terms_by_name() {
		$this->_insert_quick_posts(5);
			
		$terms = array(
				rand_str(),
				rand_str(),
				rand_str());
				
		foreach ($this->post_ids as $id) {
				$tt = wp_set_object_terms( $id, $terms, $this->taxonomy );
				// should return three term taxonomy ids
				$this->assertEquals( 3, count($tt) );
				// remember which term has which term_id
				for ($i=0; $i<3; $i++) {
					$term = get_term_by('name', $terms[$i], $this->taxonomy);
					$term_id[$terms[$i]] = intval($term->term_id);
				}
		}

		// each term should be associated with every post
		foreach ($term_id as $term=>$id) {
			$actual = get_objects_in_term($id, $this->taxonomy);
			$this->assertEquals( $this->post_ids, array_map('intval', $actual) );
		}
		
		// each term should have a count of 5
		foreach ($terms as $term) {
			$t = get_term_by('name', $term, $this->taxonomy);
			$this->assertEquals( 5, $t->count );
		}
	}
	
	function test_change_object_terms_by_name() {
		// set some terms on an object; then change them while leaving one intact
		
		$this->_insert_quick_posts(1);
		$post_id = end($this->post_ids);

		$terms_1 = array('foo', 'bar', 'baz');
		$terms_2 = array('bar', 'bing');
		
		// set the initial terms
		$tt_1 = wp_set_object_terms( $post_id, $terms_1, $this->taxonomy );
		$this->assertEquals( 3, count($tt_1) );

		// make sure they're correct
		$terms = wp_get_object_terms($post_id, $this->taxonomy, array('fields' => 'names', 'orderby' => 't.term_id'));
		$this->assertEquals( $terms_1, $terms );
		
		// change the terms
		$tt_2 = wp_set_object_terms( $post_id, $terms_2, $this->taxonomy );
		$this->assertEquals( 2, count($tt_2) );

		// make sure they're correct
		$terms = wp_get_object_terms($post_id, $this->taxonomy, array('fields' => 'names', 'orderby' => 't.term_id'));
		$this->assertEquals( $terms_2, $terms );
		
		// make sure the tt id for 'bar' matches
		$this->assertEquals( $tt_1[1], $tt_2[0] );
		
	}

	function test_change_object_terms_by_id() {
		// set some terms on an object; then change them while leaving one intact
		
		$this->_insert_quick_posts(1);
		$post_id = end($this->post_ids);

		// first set: 3 terms
		$terms_1 = array();
		for ($i=0; $i<3; $i++ ) {
			$term = rand_str();
			$result = wp_insert_term( $term, $this->taxonomy );
			$terms_1[$i] = $result['term_id'];
		}

		// second set: one of the original terms, plus one new term
		$terms_2 = array();
		$terms_2[0] = $terms_1[1];
		
		$term = rand_str();
		$result = wp_insert_term( $term, $this->taxonomy );
		$terms_2[1] = $result['term_id'];

		
		// set the initial terms
		$tt_1 = wp_set_object_terms( $post_id, $terms_1, $this->taxonomy );
		$this->assertEquals( 3, count($tt_1) );

		// make sure they're correct
		$terms = wp_get_object_terms($post_id, $this->taxonomy, array('fields' => 'ids', 'orderby' => 't.term_id'));
		$this->assertEquals( $terms_1, $terms );
		
		// change the terms
		$tt_2 = wp_set_object_terms( $post_id, $terms_2, $this->taxonomy );
		$this->assertEquals( 2, count($tt_2) );

		// make sure they're correct
		$terms = wp_get_object_terms($post_id, $this->taxonomy, array('fields' => 'ids', 'orderby' => 't.term_id'));
		$this->assertEquals( $terms_2, $terms );
		
		// make sure the tt id for 'bar' matches
		$this->assertEquals( $tt_1[1], $tt_2[0] );
		
	}
	
	function test_set_object_terms_invalid() {
		$this->_insert_quick_posts(1);
		$post_id = end($this->post_ids);

		// bogus taxonomy
		$result = wp_set_object_terms( $post_id, array(rand_str()), rand_str() );
		$this->assertTrue( is_wp_error($result) );
	}
	
}

?>
