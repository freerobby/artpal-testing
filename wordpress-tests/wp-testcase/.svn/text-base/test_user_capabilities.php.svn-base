<?php

// Test roles and capabilities via the WP_User class

class WPTestUserCapabilities extends _WPEmptyBlog {
	var $user_ids = array();

	function setUp() {
		parent::setUp();
		// keep track of users we create
		$user_ids = array();
		$this->_flush_roles();

		$this->orig_users = get_users_of_blog();
	}

	function tearDown() {
		parent::tearDown();
		// delete any users that were created during tests
		foreach ($this->user_ids as $id)
			wp_delete_user($id);
			
	}

	function _flush_roles() {
		// we want to make sure we're testing against the db, not just in-memory data
		// this will flush everything and reload it from the db
		unset($GLOBALS['wp_user_roles']);
		#$GLOBALS['wp_roles'] = new WP_Roles();
	}

	// test the default roles

	function test_user_administrator() {
		$id = $this->_make_user('administrator');
		$user = new WP_User($id);

		// make sure the role name is correct
		$this->assertEquals(array('administrator'), $user->roles);

		// check a few of the main capabilities
		$this->assertTrue($user->has_cap('switch_themes'));
		$this->assertTrue($user->has_cap('edit_users'));
		$this->assertTrue($user->has_cap('manage_options'));
		$this->assertTrue($user->has_cap('level_10'));
	}

	function test_user_editor() {
		$id = $this->_make_user('editor');
		$user = new WP_User($id);

		// make sure the role name is correct
		$this->assertEquals(array('editor'), $user->roles);

		// check a few of the main capabilities
		$this->assertTrue($user->has_cap('moderate_comments'));
		$this->assertTrue($user->has_cap('manage_categories'));
		$this->assertTrue($user->has_cap('upload_files'));
		$this->assertTrue($user->has_cap('level_7'));

		// and a few capabilities this user doesn't have
		$this->assertFalse($user->has_cap('switch_themes'));
		$this->assertFalse($user->has_cap('edit_users'));
		$this->assertFalse($user->has_cap('level_8'));
	}

	function test_user_author() {
		$id = $this->_make_user('author');
		$user = new WP_User($id);

		// make sure the role name is correct
		$this->assertEquals(array('author'), $user->roles);

		// check a few of the main capabilities
		$this->assertTrue($user->has_cap('edit_posts'));
		$this->assertTrue($user->has_cap('edit_published_posts'));
		$this->assertTrue($user->has_cap('upload_files'));
		$this->assertTrue($user->has_cap('level_2'));

		// and a few capabilities this user doesn't have
		$this->assertFalse($user->has_cap('moderate_comments'));
		$this->assertFalse($user->has_cap('manage_categories'));
		$this->assertFalse($user->has_cap('level_3'));
	}
	
	function test_user_contributor() {
		$id = $this->_make_user('contributor');
		$user = new WP_User($id);

		// make sure the role name is correct
		$this->assertEquals(array('contributor'), $user->roles);

		// check a few of the main capabilities
		$this->assertTrue($user->has_cap('edit_posts'));
		$this->assertTrue($user->has_cap('read'));
		$this->assertTrue($user->has_cap('level_1'));
		$this->assertTrue($user->has_cap('level_0'));

		// and a few capabilities this user doesn't have
		$this->assertFalse($user->has_cap('upload_files'));
		$this->assertFalse($user->has_cap('edit_published_posts'));
		$this->assertFalse($user->has_cap('level_2'));
	}

	function test_user_subscriber() {
		$id = $this->_make_user('subscriber');
		$user = new WP_User($id);

		// make sure the role name is correct
		$this->assertEquals(array('subscriber'), $user->roles);

		// check a few of the main capabilities
		$this->assertTrue($user->has_cap('read'));
		$this->assertTrue($user->has_cap('level_0'));

		// and a few capabilities this user doesn't have
		$this->assertFalse($user->has_cap('upload_files'));
		$this->assertFalse($user->has_cap('edit_posts'));
		$this->assertFalse($user->has_cap('level_1'));
	}

	// a role that doesn't exist
	function test_bogus_role() {
		_disable_wp_die();
		$id = $this->_make_user(rand_str());
		$user = new WP_User($id);

		// user has no role and no capabilities
		$this->assertEquals(array(), $user->roles);
		$this->assertFalse($user->has_cap('level_0'));
		_enable_wp_die();
	}

	// a user with multiple roles
	function test_user_subscriber_contributor() {
		$id = $this->_make_user('subscriber');
		$user = new WP_User($id);
		$user->add_role('contributor');
		
		// nuke and re-fetch the object to make sure it was stored
		$user = NULL;
		$user = new WP_User($id);

		// user should have two roles now
		$this->assertEquals(array('subscriber', 'contributor'), $user->roles);

		// with contributor capabilities
		$this->assertTrue($user->has_cap('edit_posts'));
		$this->assertTrue($user->has_cap('read'));
		$this->assertTrue($user->has_cap('level_1'));
		$this->assertTrue($user->has_cap('level_0'));

		// but not these
		$this->assertFalse($user->has_cap('upload_files'));
		$this->assertFalse($user->has_cap('edit_published_posts'));
		$this->assertFalse($user->has_cap('level_2'));
	}
	
	function test_add_empty_role() {
		// add_role($role, $display_name, $capabilities = '')
		// randomly named role with no capabilities
		global $wp_roles;
		$role_name = rand_str();
		add_role($role_name, 'Janitor', array());
		$this->_flush_roles();
		$this->assertTrue($wp_roles->is_role($role_name));
		
		$id = $this->_make_user($role_name);
		
		$user = new WP_User($id);
		
		$this->assertEquals(array($role_name), $user->roles);
		
		// user shouldn't have any capabilities; test a quick sample
		$this->assertFalse($user->has_cap('upload_files'));
		$this->assertFalse($user->has_cap('edit_published_posts'));
		$this->assertFalse($user->has_cap('level_1'));
		$this->assertFalse($user->has_cap('level_0'));

		// clean up
		remove_role($role_name);
		$this->_flush_roles();
		$this->assertFalse($wp_roles->is_role($role_name));
	}
	
	
	function test_add_role() {
		// add_role($role, $display_name, $capabilities = '')
		// randomly named role with a few capabilities
		global $wp_roles;
		$role_name = rand_str();
		add_role($role_name, 'Janitor', array('edit_posts'=>true, 'edit_pages'=>true, 'level_0'=>true, 'level_1'=>true, 'level_2'=>true));
		$this->_flush_roles();
		$this->assertTrue($wp_roles->is_role($role_name));
		
		$id = $this->_make_user($role_name);
		
		$user = new WP_User($id);
		
		$this->assertEquals(array($role_name), $user->roles);
		
		// the user should have all the above caps
		$this->assertTrue($user->has_cap($role_name));
		$this->assertTrue($user->has_cap('edit_posts'));
		$this->assertTrue($user->has_cap('edit_pages'));
		$this->assertTrue($user->has_cap('level_0'));
		$this->assertTrue($user->has_cap('level_1'));
		$this->assertTrue($user->has_cap('level_2'));
		
		// shouldn't have any other caps
		$this->assertFalse($user->has_cap('upload_files'));
		$this->assertFalse($user->has_cap('edit_published_posts'));
		$this->assertFalse($user->has_cap('upload_files'));
		$this->assertFalse($user->has_cap('level_3'));

		// clean up
		remove_role($role_name);
		$this->_flush_roles();
		$this->assertFalse($wp_roles->is_role($role_name));
	}
	
	function test_role_add_cap() {
		// change the capabilites associated with a role and make sure the change is reflected in has_cap()
		
		global $wp_roles;
		$role_name = rand_str();
		add_role( $role_name, 'Janitor', array('level_1'=>true) );
		$this->_flush_roles();
		$this->assertTrue( $wp_roles->is_role($role_name) );
		
		// assign a user to that role
		$id = $this->_make_user($role_name);
		
		// now add a cap to the role
		$wp_roles->add_cap($role_name, 'sweep_floor');
		$this->_flush_roles();

		$user = new WP_User($id);
		$this->assertEquals(array($role_name), $user->roles);

		// the user should have all the above caps
		$this->assertTrue($user->has_cap($role_name));
		$this->assertTrue($user->has_cap('level_1'));
		$this->assertTrue($user->has_cap('sweep_floor'));
		
		// shouldn't have any other caps
		$this->assertFalse($user->has_cap('upload_files'));
		$this->assertFalse($user->has_cap('edit_published_posts'));
		$this->assertFalse($user->has_cap('upload_files'));
		$this->assertFalse($user->has_cap('level_4'));

		// clean up
		remove_role($role_name);
		$this->_flush_roles();
		$this->assertFalse($wp_roles->is_role($role_name));
		
	}
	
	function test_role_remove_cap() {
		// change the capabilites associated with a role and make sure the change is reflected in has_cap()
		
		global $wp_roles;
		$role_name = rand_str();
		add_role( $role_name, 'Janitor', array('level_1'=>true, 'sweep_floor'=>true, 'polish_doorknobs'=>true) );
		$this->_flush_roles();
		$this->assertTrue( $wp_roles->is_role($role_name) );
		
		// assign a user to that role
		$id = $this->_make_user($role_name);
		
		// now remove a cap from the role
		$wp_roles->remove_cap($role_name, 'polish_doorknobs');
		$this->_flush_roles();

		$user = new WP_User($id);
		$this->assertEquals(array($role_name), $user->roles);

		// the user should have all the above caps
		$this->assertTrue($user->has_cap($role_name));
		$this->assertTrue($user->has_cap('level_1'));
		$this->assertTrue($user->has_cap('sweep_floor'));
		
		// shouldn't have the removed cap
		$this->assertFalse($user->has_cap('polish_doorknobs'));

		// clean up
		remove_role($role_name);
		$this->_flush_roles();
		$this->assertFalse($wp_roles->is_role($role_name));
		
	}

	function test_user_add_cap() {
		// add an extra capability to a user
		
		// there are two contributors
		$id_1 = $this->_make_user('contributor');
		$id_2 = $this->_make_user('contributor');
		
		// user 1 has an extra capability
		$user_1 = new WP_User($id_1);
		$user_1->add_cap('publish_posts');
		
		// re-fetch both users from the db
		$user_1 = new WP_User($id_1);
		$user_2 = new WP_User($id_2);

		// make sure they're both still contributors
		$this->assertEquals(array('contributor'), $user_1->roles);
		$this->assertEquals(array('contributor'), $user_2->roles);

		// check the extra cap on both users
		$this->assertTrue($user_1->has_cap('publish_posts'));
		$this->assertFalse($user_2->has_cap('publish_posts'));
		
		// make sure the other caps didn't get messed up
		$this->assertTrue($user_1->has_cap('edit_posts'));
		$this->assertTrue($user_1->has_cap('read'));
		$this->assertTrue($user_1->has_cap('level_1'));
		$this->assertTrue($user_1->has_cap('level_0'));
		$this->assertFalse($user_1->has_cap('upload_files'));
		$this->assertFalse($user_1->has_cap('edit_published_posts'));
		$this->assertFalse($user_1->has_cap('level_2'));

	}

	function test_user_remove_cap() {
		// add an extra capability to a user then remove it
		
		// there are two contributors
		$id_1 = $this->_make_user('contributor');
		$id_2 = $this->_make_user('contributor');
		
		// user 1 has an extra capability
		$user_1 = new WP_User($id_1);
		$user_1->add_cap('publish_posts');
		
		// now remove the extra cap
		$user_1->remove_cap('publish_posts');

		// re-fetch both users from the db
		$user_1 = new WP_User($id_1);
		$user_2 = new WP_User($id_2);

		// make sure they're both still contributors
		$this->assertEquals(array('contributor'), $user_1->roles);
		$this->assertEquals(array('contributor'), $user_2->roles);

		// check the removed cap on both users
		$this->assertFalse($user_1->has_cap('publish_posts'));
		$this->assertFalse($user_2->has_cap('publish_posts'));		

	}
		
	function test_user_level_update() {
		// make sure the user_level is correctly set and changed with the user's role
		
		// user starts as an author
		$id = $this->_make_user('author');
		$user = new WP_User($id);
		
		// author = user level 2
		$this->assertEquals( 2, $user->user_level );
		
		// they get promoted to editor - level should get bumped to 7
		$user->set_role('editor');
		$this->assertEquals( 7, $user->user_level );
		
		// demoted to contributor - level is reduced to 1
		$user->set_role('contributor');
		$this->assertEquals( 1, $user->user_level );
		
		// if they have two roles, user_level should be the max of the two
		$user->add_role('editor');
		$this->assertEquals(array('contributor', 'editor'), $user->roles);
		$this->assertEquals( 7, $user->user_level );
	}
	
	function test_user_remove_all_caps() {
		// user starts as an author
		$id = $this->_make_user('author');
		$user = new WP_User($id);
		
		// add some extra capabilities
		$user->add_cap('make_coffee');
		$user->add_cap('drink_coffee');

		// re-fetch
		$user = new WP_User($id);
		
		$this->assertTrue($user->has_cap('make_coffee'));
		$this->assertTrue($user->has_cap('drink_coffee'));
		
		// all caps are removed
		$user->remove_all_caps();
		
		// re-fetch
		$user = new WP_User($id);

		// capabilities for the author role should be gone
#		$this->assertFalse($user->has_cap('edit_posts'));
#		$this->assertFalse($user->has_cap('edit_published_posts'));
#		$this->assertFalse($user->has_cap('upload_files'));
#		$this->assertFalse($user->has_cap('level_2'));

		// the extra capabilities should be gone
		$this->assertFalse($user->has_cap('make_coffee'));
		$this->assertFalse($user->has_cap('drink_coffee'));

		// user level should be empty
		$this->assertNull( $user->user_level );
		
		
	}

	function test_post_meta_caps() {
		// simple tests for some common meta capabilities
		
		// make a [pst
		$this->_insert_quick_posts(1);
		$post = end($this->post_ids);

		// the author of the post
		$author = new WP_User($this->author->ID);
		$author->set_role('author');
		
		// add some other users
		$admin = new WP_User($this->_make_user('administrator'));
		$author_2 = new WP_User($this->_make_user('author'));
		$editor = new WP_User($this->_make_user('editor'));
		$contributor = new WP_User($this->_make_user('contributor'));
		
		// administrators, editors and the post owner can edit it
		$this->assertTrue($admin->has_cap('edit_post', $post));
		$this->assertTrue($author->has_cap('edit_post', $post));
		$this->assertTrue($editor->has_cap('edit_post', $post));
		// other authors and contributors can't
		$this->assertFalse($author_2->has_cap('edit_post', $post));
		$this->assertFalse($contributor->has_cap('edit_post', $post));
		
		// administrators, editors and the post owner can delete it
		$this->assertTrue($admin->has_cap('delete_post', $post));
		$this->assertTrue($author->has_cap('delete_post', $post));
		$this->assertTrue($editor->has_cap('delete_post', $post));
		// other authors and contributors can't
		$this->assertFalse($author_2->has_cap('delete_post', $post));
		$this->assertFalse($contributor->has_cap('delete_post', $post));
	}

	function test_page_meta_caps() {
		// simple tests for some common meta capabilities
		
		// make a page
		$this->_insert_quick_pages(1);
		$page = end($this->post_ids);

		// the author of the page
		$author = new WP_User($this->author->ID);
		$author->set_role('author');
		
		// add some other users
		$admin = new WP_User($this->_make_user('administrator'));
		$author_2 = new WP_User($this->_make_user('author'));
		$editor = new WP_User($this->_make_user('editor'));
		$contributor = new WP_User($this->_make_user('contributor'));
		
		// administrators, editors and the post owner can edit it
		$this->assertTrue($admin->has_cap('edit_page', $page));
		$this->assertTrue($editor->has_cap('edit_page', $page));
		// other authors and contributors can't
		$this->assertFalse($author->has_cap('edit_page', $page));
		$this->assertFalse($author_2->has_cap('edit_page', $page));
		$this->assertFalse($contributor->has_cap('edit_page', $page));
		
		// administrators, editors and the post owner can delete it
		$this->assertTrue($admin->has_cap('delete_page', $page));
		$this->assertTrue($editor->has_cap('delete_page', $page));
		// other authors and contributors can't
		$this->assertFalse($author->has_cap('delete_page', $page));
		$this->assertFalse($author_2->has_cap('delete_page', $page));
		$this->assertFalse($contributor->has_cap('delete_page', $page));
	}
	
	function test_usermeta_caps() {

		$this->knownWPBug(5541);

		// make sure an old style usermeta capabilities entry is still recognized by the new code
		
		$id = $this->_make_user('author');
		$user = new WP_User($id);
		
		global $wpdb;
		if (!empty($wpdb->user_role))
			$wpdb->query("DELETE FROM {$wpdb->user_role} WHERE user_id = {$id}");
		
		update_usermeta($id, $user->cap_key, array('editor' => true));

		$user = new WP_User($id);

		// check a few of the main capabilities
		$this->assertEquals(array('editor'), $user->roles);
		$this->assertTrue($user->has_cap('moderate_comments'));
		$this->assertTrue($user->has_cap('manage_categories'));
		$this->assertTrue($user->has_cap('upload_files'));
		$this->assertTrue($user->has_cap('level_7'));

		// and a few capabilities this user doesn't have
		$this->assertFalse($user->has_cap('switch_themes'));
		$this->assertFalse($user->has_cap('edit_users'));
		$this->assertFalse($user->has_cap('level_8'));
	}

	function test_upgrade() {
		
		// only relevant with this patch
		$this->knownWPBug(5540);
		
		if ( !is_callable('upgrade_user_roles') )
			$this->markTestSkipped('depends on patch #5540');
		
		global $wpdb, $blog_id;
		
		// make some users with old style usermeta roles and caps
		$id = array();
		for ($i=0; $i<5; $i++) {
			$id[$i] = $this->_make_user('');
			$wpdb->query("DELETE FROM {$wpdb->user_role} WHERE user_id = {$id[$i]}");
		}

		// regular users
		$user = new WP_User($id[0]);
		update_usermeta($id[0], $user->cap_key, array('administrator' => true));
		update_usermeta($id[1], $user->cap_key, array('editor' => true));
		update_usermeta($id[2], $user->cap_key, array('subscriber' => true));
		// a user with 2 roles
		update_usermeta($id[3], $user->cap_key, array('contributor' => true, 'author' => true));
		// a user with per-user capabilities
		update_usermeta($id[4], $user->cap_key, array('subscriber' => true, 'edit_posts' => true, 'upload_files' => true));
		
		upgrade_user_roles($wpdb->prefix, $blog_id);
		
		// make sure the upgrade did insert user_role rows
		foreach ( $id as $user_id ) {
			$this->assertTrue( $wpdb->get_row("SELECT user_role_id FROM {$wpdb->user_role} WHERE user_id = {$user_id}") > 0 );
		}

		// test each user's role and capabilities, and make sure the usermeta data is what we expect
		$user_0 = new WP_User($id[0]);
		$this->assertEquals(array('administrator'), $user_0->roles);
		$this->assertTrue($user_0->has_cap('switch_themes'));
		$this->assertTrue($user_0->has_cap('edit_users'));
		$this->assertTrue($user_0->has_cap('manage_options'));
		$this->assertTrue($user_0->has_cap('level_10'));
		$old_caps = get_usermeta($id[0], $user_0->cap_key);
		$this->assertEquals( array(), $old_caps );

		$user_1 = new WP_User($id[1]);
		$this->assertEquals(array('editor'), $user_1->roles);
		$this->assertTrue($user_1->has_cap('moderate_comments'));
		$this->assertTrue($user_1->has_cap('manage_categories'));
		$this->assertTrue($user_1->has_cap('upload_files'));
		$this->assertTrue($user_1->has_cap('level_7'));
		$this->assertFalse($user_1->has_cap('switch_themes'));
		$this->assertFalse($user_1->has_cap('edit_users'));
		$this->assertFalse($user_1->has_cap('level_8'));
		$old_caps = get_usermeta($id[1], $user_1->cap_key);
		$this->assertEquals( array(), $old_caps );
		
		$user_2 = new WP_User($id[2]);
		$this->assertEquals(array('subscriber'), $user_2->roles);
		$this->assertTrue($user_2->has_cap('read'));
		$this->assertTrue($user_2->has_cap('level_0'));
		$this->assertFalse($user_2->has_cap('upload_files'));
		$this->assertFalse($user_2->has_cap('edit_posts'));
		$this->assertFalse($user_2->has_cap('level_1'));
		$old_caps = get_usermeta($id[2], $user_2->cap_key);
		$this->assertEquals( array(), $old_caps );
		
		// user 3 has two roles
		$user_3 = new WP_User($id[3]);
		$this->assertEquals(array('contributor', 'author'), $user_3->roles);
		$this->assertTrue($user_3->has_cap('edit_posts'));
		$this->assertTrue($user_3->has_cap('edit_published_posts'));
		$this->assertTrue($user_3->has_cap('upload_files'));
		$this->assertTrue($user_3->has_cap('level_2'));
		$this->assertFalse($user_3->has_cap('moderate_comments'));
		$this->assertFalse($user_3->has_cap('manage_categories'));
		$this->assertFalse($user_3->has_cap('level_3'));
		$old_caps = get_usermeta($id[3], $user_3->cap_key);
		$this->assertEquals( array(), $old_caps );
		
		// user 4 is a subscriber with some extra per-user caps
		$user_4 = new WP_User($id[4]);
		$this->assertEquals(array('subscriber'), $user_4->roles);
		$this->assertTrue($user_4->has_cap('read'));
		$this->assertTrue($user_4->has_cap('level_0'));
		$this->assertFalse($user_4->has_cap('edit_users'));
		$this->assertFalse($user_4->has_cap('level_1'));
		// extra caps
		$this->assertTrue($user_4->has_cap('edit_posts'));
		$this->assertTrue($user_4->has_cap('upload_files'));
		// the extra caps should still be stored in usermeta
		$old_caps = get_usermeta($id[4], $user_4->cap_key);
		$this->assertEquals( array('edit_posts' => true, 'upload_files' => true), $old_caps );
		
		// quick test for get_users_of_blog
		$users = get_users_of_blog();
		#dmp('orig users', $this->orig_users);
		#dmp('now users', $users);
		$this->assertEquals( 5, count($users) - count($this->orig_users) );
		
		#dmp('get_roles_with_cap edit_posts', get_roles_with_cap('edit_posts'));
		#dmp('get_users_of_blog', get_users_of_blog());
		#dmp('get_users_with_cap edit_posts', get_users_with_cap('edit_posts'));
	}

	function _test_generate_role_thingy() {
		global $wp_roles;
		foreach (array_keys($wp_roles->roles) as $role) {
			$obj = $wp_roles->role_objects[$role];
			
			echo "\nadd_role('{$role}', '{$obj->name}', ".var_export($obj->capabilities, true)."\n";
			echo ")\n";
		}
	}
}

?>
