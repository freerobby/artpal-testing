<?php

// test functions in wp-includes/user.php

class TestWPUser extends _WPEmptyBlog {

	var $user_ids = array();

	function setUp() {
		parent::setUp();
		// keep track of users we create
		$user_ids = array();
	}

	function tearDown() {
		parent::tearDown();
		// delete any users that were created during tests
		foreach ($this->user_ids as $id)
			wp_delete_user($id);
	}

	function test_get_users_of_blog() {
		// add one of each user role
		$user_role = array();
		foreach (array('administrator', 'editor', 'author', 'contributor', 'subscriber') as $role) {
			$id = $this->_make_user($role);
			$user_role[$id] = $role;
		}

		$user_list = get_users_of_blog();

		// find the role of each user as returned by get_users_of_blog
		$found = array();
		foreach ($user_list as $user) {
			// only include the users we just created - there might be some others that existed previously
			if (isset($user_role[$user->user_id])) {
				$roles = array_keys(unserialize($user->meta_value));
				$found[$user->user_id] = $roles[0];
			}
		}

		// make sure every user we created was returned
		$this->assertEquals($user_role, $found);

	}

	// simple get/set tests for user_option functions
	function test_user_option() {

		$key = rand_str();
		$val = rand_str();

		$user_id = $this->_make_user('author');

		// get an option that doesn't exist
		$this->assertFalse(get_user_option($key, $user_id));

		// set and get
		update_user_option( $user_id, $key, $val );
		$this->assertEquals( $val, get_user_option($key, $user_id) );

		// change and get again
		$val2 = rand_str();
		update_user_option( $user_id, $key, $val2 );
		$this->assertEquals( $val2, get_user_option($key, $user_id) );

	}

	// simple tests for usermeta functions
	function test_usermeta() {

		$key = rand_str();
		$val = rand_str();

		$user_id = $this->_make_user('author');

		// get a meta key that doesn't exist
		$this->assertEquals( '', get_usermeta($user_id, $key) );

		// set and get
		update_usermeta( $user_id, $key, $val );
		$this->assertEquals( $val, get_usermeta($user_id, $key) );

		// change and get again
		$val2 = rand_str();
		update_usermeta( $user_id, $key, $val2 );
		$this->assertEquals( $val2, get_usermeta($user_id, $key) );

		// delete and get
		delete_usermeta( $user_id, $key );
		$this->assertEquals( '', get_usermeta($user_id, $key) );

		// delete by key AND value
		update_usermeta( $user_id, $key, $val );
		// incorrect key: key still exists
		delete_usermeta( $user_id, $key, rand_str() );
		$this->assertEquals( $val, get_usermeta($user_id, $key) );
		// correct key: deleted
		delete_usermeta( $user_id, $key, $val );
		$this->assertEquals( '', get_usermeta($user_id, $key) );

	}

	// test usermeta functions in array mode
	function test_usermeta_array() {

		// some values to set
		$vals = array(
			rand_str() => 'val-'.rand_str(),
			rand_str() => 'val-'.rand_str(),
			rand_str() => 'val-'.rand_str(),
		);

		$user_id = $this->_make_user('author');

		// there is already some stuff in the array
		$this->assertTrue(is_array(get_usermeta($user_id)));
		
		foreach ($vals as $k=>$v)
			update_usermeta( $user_id, $k, $v );
		
		// get the complete usermeta array
		$out = get_usermeta($user_id);

		// for reasons unclear, the resulting array is indexed numerically; meta keys are not included anywhere.
		// so we'll just check to make sure our values are included somewhere.
		foreach ($vals as $v)
			$this->assertTrue(in_array($v, $out));
			
		// delete one key and check again
		$key_to_delete = array_pop(array_keys($vals));
		delete_usermeta($user_id, $key_to_delete);
		$out = get_usermeta($user_id);
		// make sure that key is excluded from the results
		foreach ($vals as $k=>$v) {
			if ($k == $key_to_delete)
				$this->assertFalse(in_array($v, $out));
			else
				$this->assertTrue(in_array($v, $out));
		}
	}

	// simple test for user dropdown
	function test_wp_dropdown_users() {
		// mu doesn't have this function?
		if (!is_callable('wp_dropdown_users'))
			return $this->markTestSkipped();

		// add some users
		foreach (array('administrator', 'editor', 'author', 'contributor', 'subscriber') as $role) {
			$id = $this->_make_user($role, "test-{$role}");
			$user[] = $id;
		}

		$expected = <<<EOF
<select name='user' id='user' class=''>
<option value='1'>{$this->author->display_name}</option>
<option value='{$user[0]}'>test-administrator</option>
<option value='{$user[1]}'>test-editor</option>
<option value='{$user[2]}'>test-author</option>
<option value='{$user[3]}'>test-contributor</option>
<option value='{$user[4]}'>test-subscriber</option>
</select>
EOF;

		$out = wp_dropdown_users('echo=0&orderby=ID');

		$this->assertEquals(strip_ws($expected), strip_ws($out));
	}
}

?>
