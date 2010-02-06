<?php

#include_once(DIR_TESTROOT.'/wp-testlib/wp-profiler.php');
class TestImportWP extends _WPEmptyBlog {

	var $posts = NULL;

	function setUp() {
		parent::setUp();
		include_once(ABSPATH.'/wp-admin/import/wordpress.php');
		if ( !defined('WP_IMPORTING') )
			define('WP_IMPORTING', true);

#		$this->record_queries();


#		echo $this->_generate_post_content_test($this->posts, false);

		@unlink(ABSPATH.'wp-content/uploads/2007/12/yue-04-juan_manuel_fangio.mp3');
		@unlink(ABSPATH.'wp-content/uploads/2007/09/2007-06-30-dsc_4700-1.jpg');
		@unlink(ABSPATH.'wp-content/uploads/2007/09/2007-06-30-dsc_4711-900px.jpg');
		@unlink(ABSPATH.'wp-content/uploads/2007/09/2007-06-30-dsc_4711-300px.jpg');
	}

	function tearDown() {
		parent::tearDown();
		if ($id = get_profile('ID', 'User A'))
			wp_delete_user($id);
		if ($id = get_profile('ID', 'User B'))
			wp_delete_user($id);

		@unlink(ABSPATH.'wp-content/uploads/2007/12/yue-04-juan_manuel_fangio.mp3');
		@unlink(ABSPATH.'wp-content/uploads/2007/09/2007-06-30-dsc_4700-1.jpg');
		@unlink(ABSPATH.'wp-content/uploads/2007/09/2007-06-30-dsc_4711-900px.jpg');
		@unlink(ABSPATH.'wp-content/uploads/2007/09/2007-06-30-dsc_4711-300px.jpg');
	}

	function test_is_wxr_file_positive() {
		$importer = new WP_Import();
		$importer->file = realpath(DIR_TESTDATA.'/export/asdftestblog1.2007-11-23.xml');
		// should return true because that's a valid export file
		$result = $importer->get_entries();
		$this->assertTrue($result);
	}

	function test_is_wxr_file_negative() {
		$importer = new WP_Import();
		$importer->file = realpath(DIR_TESTDATA.'/export/asdftestblog1.2007-11-23.sql');
		// should return false because it's not a wxr file
		$result = $importer->get_entries();
		$this->assertFalse($result);
	}

	function test_select_authors() {

		$users = get_users_of_blog();

		$importer = new WP_Import();
		$importer->file = realpath(DIR_TESTDATA.'/export/asdftestblog1.2007-11-23.xml');

		$form = get_echo(array(&$importer, 'select_authors'));
		$form = mask_input_value($form);

		$expected = <<<EOF
<h2>Assign Authors</h2>
<p>To make it easier for you to edit and save the imported posts and drafts, you may want to change the name of the author of the posts. For example, you may want to import all the entries as <code>admin</code>s entries.</p>
<p>If a new user is created by WordPress, a password will be randomly generated. Manually change the user's details if necessary.</p>
<ol id="authors"><form action="?import=wordpress&amp;step=2&amp;id=" method="post"><input type="hidden" id="_wpnonce" name="_wpnonce" value="***" /><input type="hidden" name="_wp_http_referer" value="wp-test.php" /><li>Import author: <strong>Alex Shiels</strong><br />Create user  <input type="text" value="Alex Shiels" name="user_create[0]" maxlength="30"> <br /> or map to existing<input type="hidden" name="author_in[0]" value="Alex Shiels" /><select name="user_select[0]">

	<option value="0">- Select -</option>
	<option value="1">{$this->author->user_login}</option>	</select>
	</li><li>Import author: <strong>tellyworthtest2</strong><br />Create user  <input type="text" value="tellyworthtest2" name="user_create[1]" maxlength="30"> <br /> or map to existing<input type="hidden" name="author_in[1]" value="tellyworthtest2" /><select name="user_select[1]">
	<option value="0">- Select -</option>
	<option value="1">{$this->author->user_login}</option>	</select>
	</li></ol>
<h2>Import Attachments</h2>
<p>
	<input type="checkbox" value="1" name="attachments" id="import-attachments" />
	<label for="import-attachments">Download and import file attachments</label>
</p>

<input type="submit" value="Submit"><br /></form>



EOF;

		$this->assertEquals( strip_ws($expected), strip_ws($form) );

		// make sure the importer didn't add users yet
		$this->assertEquals( $users, get_users_of_blog() );

	}

	function test_big_import() {
		#$this->_import_wp(DIR_TESTDATA.'/export/big-export.xml', array('User A'));
		$html_output = get_echo( array(&$this, '_import_wp'), array( DIR_TESTDATA.'/export/big-export.xml', array('User A') ) );

		// check that the tag counts are correct
		$this->assertEquals(500, $this->_tag_count('Tag A'));
		$this->assertEquals(500, $this->_tag_count('Tag B'));
		$this->assertEquals(500, $this->_tag_count('Tag C'));

		$posts = get_posts('numberposts=500&post_type=&post_status=&orderby=ID');
		$this->assertEquals( 500, count($posts) );
		
		// every post has 3 comments
		foreach ($posts as $post) {
			$this->assertEquals($post->comment_count, 3);
		}
	}

	function _test_dump() {
		$this->_import_wp(DIR_TESTDATA.'/export/asdftestblog1.2007-11-23.xml', array('User A', 'User B'));
		$this->_dump_tables('wp_posts', 'wp_postmeta', 'wp_comments', 'wp_terms', 'wp_term_taxonomy', 'wp_term_relationships', 'wp_users', 'wp_usermeta');

	}

	// use this to generate the tests seen in test_all_posts()
	function _test_generate() {
		$this->_import_wp(DIR_TESTDATA.'/export/asdftestblog1.2008-04-01.xml', array('Alex Shiels'=>'User A', 'tellyworthtest2'=>'User B'));
		$this->posts = get_posts('numberposts=500&post_type=&post_status=&orderby=post_date DESC,ID');
		echo $this->_generate_post_content_test($this->posts, false);
	}

	function test_all_posts() {
		global $wpdb;
		#$this->_import_wp(DIR_TESTDATA.'/export/asdftestblog1.2008-04-01.xml', array('Alex Shiels'=>'User A', 'tellyworthtest2'=>'User B'));
		$html_output = get_echo( array(&$this, '_import_wp'), array( DIR_TESTDATA.'/export/asdftestblog1.2007-12-14.xml', array('Alex Shiels'=>'User A', 'tellyworthtest2'=>'User B') ) );

		// check the tag and category counts
		$this->assertEquals( 4, $this->_category_count('Cat A') );
		$this->assertEquals( 4, $this->_category_count('Cat B') );
		$this->assertEquals( 4, $this->_category_count('Cat C') );
		$this->assertEquals( 1, $this->_category_count('Parent') );
		$this->assertEquals( 1, $this->_category_count('Child 1') );
		$this->assertEquals( 1, $this->_category_count('Child 2') );
		$this->assertEquals( 35, $this->_category_count('Uncategorized') );
		$this->assertEquals( 4, $this->_tag_count('Tag A') );
		$this->assertEquals( 4, $this->_tag_count('Tag B') );
		$this->assertEquals( 4, $this->_tag_count('Tag C') );

		// check that the category structure is preserved
		$parent = get_term_by('name', 'Parent', 'category');
		$child1 = get_term_by('name', 'Child 1', 'category');
		$child2 = get_term_by('name', 'Child 2', 'category');
		$this->assertEquals( 0, $parent->parent );
		$this->assertEquals( $parent->term_id, $child1->parent );
		$this->assertEquals( $child1->term_id, $child2->parent );

		$this->posts = get_posts('numberposts=500&post_type=&post_status=&orderby=post_date DESC,ID');

		$post = $this->posts[0];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2027-09-04 10:03:02", $post->post_date);
		$this->assertEquals("2027-09-04 00:03:02", $post->post_date_gmt);
		$this->assertEquals("September 04, 2027.", $post->post_content);
		$this->assertEquals("Future post", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("future", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("future-post", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2027-09-04 10:03:02", $post->post_modified);
		$this->assertEquals("2027-09-04 00:03:02", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2027/09/04/future-post/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[1];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-12-11 16:25:40", $post->post_date);
		$this->assertEquals("2007-12-11 06:25:40", $post->post_date_gmt);
		$this->assertEquals("Level 1 of the reverse hierarchy test.  This is to make sure the importer correctly assigns parents and children even when the children come first in the export file.", $post->post_content);
		$this->assertEquals("Level 1", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("level-1", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-12-11 16:25:40", $post->post_modified);
		$this->assertEquals("2007-12-11 06:25:40", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/level-1/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("page", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[2];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-12-11 16:23:33", $post->post_date);
		$this->assertEquals("2007-12-11 06:23:33", $post->post_date_gmt);
		$this->assertEquals("Level 2 of the reverse hierarchy test.", $post->post_content);
		$this->assertEquals("Level 2", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("level-2", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-12-11 16:23:33", $post->post_modified);
		$this->assertEquals("2007-12-11 06:23:33", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals($this->posts[1]->ID, $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/level-2/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("page", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[3];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-12-11 16:23:16", $post->post_date);
		$this->assertEquals("2007-12-11 06:23:16", $post->post_date_gmt);
		$this->assertEquals("Level 3 of the reverse hierarchy test.", $post->post_content);
		$this->assertEquals("Level 3", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("level-3", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-12-11 16:23:16", $post->post_modified);
		$this->assertEquals("2007-12-11 06:23:16", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals($this->posts[2]->ID, $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/level-3/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("page", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[4];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-12-10 19:53:13", $post->post_date);
		$this->assertEquals("2007-12-10 09:53:13", $post->post_date_gmt);
		$this->assertEquals("This post is in category Parent/Foo A, which clashes with the category named Foo A (no parent).", $post->post_content);
		$this->assertEquals("Category name clash", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("category-name-clash", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-12-10 19:53:13", $post->post_modified);
		$this->assertEquals("2007-12-10 09:53:13", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/12/10/category-name-clash/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Foo A', $cats[0]->name);
		$this->assertEquals('foo-a', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[5];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-12-03 08:07:28", $post->post_date);
		$this->assertEquals("2007-12-02 22:07:28", $post->post_date_gmt);
		$this->assertEquals("Here's an mp3 file that was uploaded as an attachment:\n\n<a href=\"http://example.com/wp-content/uploads/2007/12/yue-04-juan_manuel_fangio.mp3\" title=\"Juan Manuel Fangio by Yue\">Juan Manuel Fangio by Yue</a>\n\nAnd here's a link to an external mp3 file:\n\n<a href=\"http://generalfuzz.net/mp3/Cool%20Aberrations/acclimate.mp3\"> Acclimate by General Fuzz </a>\n\nBoth are CC licensed.", $post->post_content);
		$this->assertEquals("Test with enclosures", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("test-with-enclosures", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-12-03 08:07:28", $post->post_modified);
		$this->assertEquals("2007-12-02 22:07:28", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/12/03/test-with-enclosures/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$this->assertEquals(array (
  0 => "http://generalfuzz.net/mp3/Cool%20Aberrations/acclimate.mp3\n4800512\naudio/mpeg",
  1 => "http://example.com/wp-content/uploads/2007/12/yue-04-juan_manuel_fangio.mp3\n5277824\naudio/mpeg",
), get_post_meta($post->ID, 'enclosure', false));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[6];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-12-03 07:59:03", $post->post_date);
		$this->assertEquals("2007-12-02 21:59:03", $post->post_date_gmt);
		$this->assertEquals("A new single from the Yue, dedicated to the legendary F1 driver. Enjoy the brand new electronic arrangement, drum'n'bass and 80's influences and the beautiful female voices of Giulia Pedroni and Sara Menozzi.\n\nLicense: Attribution-Share Alike 2.5 Generic", $post->post_content);
		$this->assertEquals("Juan Manuel Fangio by Yue", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("inherit", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("juan-manuel-fangio-by-yue", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-12-03 07:59:03", $post->post_modified);
		$this->assertEquals("2007-12-02 21:59:03", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals($this->posts[5]->ID, $post->post_parent);
		$this->assertEquals(wp_get_attachment_url($post->ID), $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("attachment", $post->post_type);
		$this->assertEquals("audio/mpeg", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$this->assertEquals(array (
  0 => '/home/alex/dev/public_html/wp-content/blogs.dir/8de/1641616/files/2007/12/yue-04-juan_manuel_fangio.mp3',
  1 => '/Users/alex/Documents/dev/wordpress-tests/wordpress/wp-content/uploads/2007/12/yue-04-juan_manuel_fangio.mp3',
), get_post_meta($post->ID, '_wp_attached_file', false));
		$this->assertEquals(array (
  0 => 's:6:"a:0:{}";',
  1 => 'a:0:{}',
), get_post_meta($post->ID, '_wp_attachment_metadata', false));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[7];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-11-30 13:48:59", $post->post_date);
		$this->assertEquals("2007-11-30 03:48:59", $post->post_date_gmt);
		$this->assertEquals("Some block quote tests:\n\n<blockquote>Here's a one line quote.</blockquote>\n\nThis part isn't quoted.  Here's a much longer quote:\n\n<blockquote> Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In dapibus. In pretium pede. Donec molestie facilisis ante. Ut a turpis ut ipsum pellentesque tincidunt. Morbi blandit sapien in mauris. Nulla lectus lorem, varius aliquet, auctor vitae, bibendum et, nisl. Fusce pulvinar, risus non euismod varius, ante tortor facilisis lorem, non condimentum diam nisl vel lectus. Nullam vulputate, urna rutrum vulputate molestie, sapien dolor adipiscing nisi, eu malesuada ipsum lectus quis est. Nulla facilisi. Mauris a diam in eros pretium elementum. Vivamus lacinia nisl non orci. Duis ut dolor. Sed sollicitudin cursus libero.\n\nProin et lorem. Quisque odio. Ut gravida, pede sed convallis facilisis, magna dolor egestas dolor, non pulvinar metus magna in velit. Morbi vitae sem sit amet arcu vehicula gravida. Morbi placerat, est id pulvinar sollicitudin, ante augue vestibulum turpis, eu ultrices pede metus sit amet justo. Suspendisse metus. Mauris convallis mattis risus. Nullam et ipsum eu magna hendrerit pellentesque. Ut malesuada turpis nec orci. Donec at urna imperdiet libero tincidunt lacinia. Phasellus mollis leo pharetra velit. Quisque tortor. Nam pharetra est vel felis. Maecenas tincidunt, purus ac ultrices vehicula, ante magna ultrices orci, ac malesuada lectus purus sit amet odio. Vivamus id libero. Vivamus enim nisi, egestas aliquam, tincidunt malesuada, semper at, turpis. Vestibulum risus dolor, placerat quis, adipiscing sed, scelerisque a, enim. Vestibulum posuere.\n\nMauris felis. Vivamus ornare. Maecenas mi. Mauris quis nunc. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Suspendisse potenti. In at lorem. Aliquam sed ligula eu erat ultrices congue. Aenean interdum semper purus. Phasellus eget lorem.</blockquote>\n\nAnd some trailing text.", $post->post_content);
		$this->assertEquals("Block quotes", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("block-quotes", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-11-30 13:48:59", $post->post_modified);
		$this->assertEquals("2007-11-30 03:48:59", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/11/30/block-quotes/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("2", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(2, count($comments));
		$this->assertEquals('Alex Shiels', $comments[0]->comment_author);
		$this->assertEquals('tellyworth@gmail.com', $comments[0]->comment_author_email);
		$this->assertEquals('http://flightpath.wordpress.com/', $comments[0]->comment_author_url);
		$this->assertEquals('59.167.145.61', $comments[0]->comment_author_IP);
		$this->assertEquals('2007-11-30 13:50:39', $comments[0]->comment_date);
		$this->assertEquals('2007-11-30 03:50:39', $comments[0]->comment_date_gmt);
		$this->assertEquals('0', $comments[0]->comment_karma);
		$this->assertEquals('1', $comments[0]->comment_approved);
		$this->assertEquals('', $comments[0]->comment_agent);
		$this->assertEquals('', $comments[0]->comment_type);
		$this->assertEquals('0', $comments[0]->comment_parent);
		$this->assertEquals('', $comments[0]->comment_user_id);
		$this->assertEquals('Alex Shiels', $comments[1]->comment_author);
		$this->assertEquals('tellyworth@gmail.com', $comments[1]->comment_author_email);
		$this->assertEquals('http://flightpath.wordpress.com/', $comments[1]->comment_author_url);
		$this->assertEquals('59.167.145.61', $comments[1]->comment_author_IP);
		$this->assertEquals('2007-11-30 13:49:59', $comments[1]->comment_date);
		$this->assertEquals('2007-11-30 03:49:59', $comments[1]->comment_date_gmt);
		$this->assertEquals('0', $comments[1]->comment_karma);
		$this->assertEquals('1', $comments[1]->comment_approved);
		$this->assertEquals('', $comments[1]->comment_agent);
		$this->assertEquals('', $comments[1]->comment_type);
		$this->assertEquals('0', $comments[1]->comment_parent);
		$this->assertEquals('', $comments[1]->comment_user_id);


		$post = $this->posts[8];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-11-24 06:19:03", $post->post_date);
		$this->assertEquals("2007-11-23 20:19:03", $post->post_date_gmt);
		$this->assertEquals("This post has far too many categories.", $post->post_content);
		$this->assertEquals("Many categories", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("many-categories", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-11-24 06:19:03", $post->post_modified);
		$this->assertEquals("2007-11-23 20:19:03", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/11/24/many-categories/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(40, count($cats));
		$this->assertEquals('aciform', $cats[0]->name);
		$this->assertEquals('aciform', $cats[0]->slug);
		$this->assertEquals('antiquarianism', $cats[1]->name);
		$this->assertEquals('antiquarianism', $cats[1]->slug);
		$this->assertEquals('arrangement', $cats[2]->name);
		$this->assertEquals('arrangement', $cats[2]->slug);
		$this->assertEquals('asmodeus', $cats[3]->name);
		$this->assertEquals('asmodeus', $cats[3]->slug);
		$this->assertEquals('broder', $cats[4]->name);
		$this->assertEquals('broder', $cats[4]->slug);
		$this->assertEquals('buying', $cats[5]->name);
		$this->assertEquals('buying', $cats[5]->slug);
		$this->assertEquals('championship', $cats[6]->name);
		$this->assertEquals('championship', $cats[6]->slug);
		$this->assertEquals('chastening', $cats[7]->name);
		$this->assertEquals('chastening', $cats[7]->slug);
		$this->assertEquals('clerkship', $cats[8]->name);
		$this->assertEquals('clerkship', $cats[8]->slug);
		$this->assertEquals('disinclination', $cats[9]->name);
		$this->assertEquals('disinclination', $cats[9]->slug);
		$this->assertEquals('disinfection', $cats[10]->name);
		$this->assertEquals('disinfection', $cats[10]->slug);
		$this->assertEquals('dispatch', $cats[11]->name);
		$this->assertEquals('dispatch', $cats[11]->slug);
		$this->assertEquals('echappee', $cats[12]->name);
		$this->assertEquals('echappee', $cats[12]->slug);
		$this->assertEquals('enphagy', $cats[13]->name);
		$this->assertEquals('enphagy', $cats[13]->slug);
		$this->assertEquals('equipollent', $cats[14]->name);
		$this->assertEquals('equipollent', $cats[14]->slug);
		$this->assertEquals('fatuity', $cats[15]->name);
		$this->assertEquals('fatuity', $cats[15]->slug);
		$this->assertEquals('gaberlunzie', $cats[16]->name);
		$this->assertEquals('gaberlunzie', $cats[16]->slug);
		$this->assertEquals('illtempered', $cats[17]->name);
		$this->assertEquals('illtempered', $cats[17]->slug);
		$this->assertEquals('insubordination', $cats[18]->name);
		$this->assertEquals('insubordination', $cats[18]->slug);
		$this->assertEquals('lender', $cats[19]->name);
		$this->assertEquals('lender', $cats[19]->slug);
		$this->assertEquals('monosyllable', $cats[20]->name);
		$this->assertEquals('monosyllable', $cats[20]->slug);
		$this->assertEquals('packthread', $cats[21]->name);
		$this->assertEquals('packthread', $cats[21]->slug);
		$this->assertEquals('palter', $cats[22]->name);
		$this->assertEquals('palter', $cats[22]->slug);
		$this->assertEquals('papilionaceous', $cats[23]->name);
		$this->assertEquals('papilionaceous', $cats[23]->slug);
		$this->assertEquals('personable', $cats[24]->name);
		$this->assertEquals('personable', $cats[24]->slug);
		$this->assertEquals('propylaeum', $cats[25]->name);
		$this->assertEquals('propylaeum', $cats[25]->slug);
		$this->assertEquals('pustule', $cats[26]->name);
		$this->assertEquals('pustule', $cats[26]->slug);
		$this->assertEquals('quartern', $cats[27]->name);
		$this->assertEquals('quartern', $cats[27]->slug);
		$this->assertEquals('scholarship', $cats[28]->name);
		$this->assertEquals('scholarship', $cats[28]->slug);
		$this->assertEquals('selfconvicted', $cats[29]->name);
		$this->assertEquals('selfconvicted', $cats[29]->slug);
		$this->assertEquals('showshoe', $cats[30]->name);
		$this->assertEquals('showshoe', $cats[30]->slug);
		$this->assertEquals('sloyd', $cats[31]->name);
		$this->assertEquals('sloyd', $cats[31]->slug);
		$this->assertEquals('sublunary', $cats[32]->name);
		$this->assertEquals('sublunary', $cats[32]->slug);
		$this->assertEquals('tamtam', $cats[33]->name);
		$this->assertEquals('tamtam', $cats[33]->slug);
		$this->assertEquals('weakhearted', $cats[34]->name);
		$this->assertEquals('weakhearted', $cats[34]->slug);
		$this->assertEquals('ween', $cats[35]->name);
		$this->assertEquals('ween', $cats[35]->slug);
		$this->assertEquals('wellhead', $cats[36]->name);
		$this->assertEquals('wellhead', $cats[36]->slug);
		$this->assertEquals('wellintentioned', $cats[37]->name);
		$this->assertEquals('wellintentioned', $cats[37]->slug);
		$this->assertEquals('whetstone', $cats[38]->name);
		$this->assertEquals('whetstone', $cats[38]->slug);
		$this->assertEquals('years', $cats[39]->name);
		$this->assertEquals('years', $cats[39]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[9];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-11-24 06:09:34", $post->post_date);
		$this->assertEquals("2007-11-23 20:09:34", $post->post_date_gmt);
		$this->assertEquals("This post has far too many tags.", $post->post_content);
		$this->assertEquals("Many tags", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("many-tags", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-11-24 06:09:34", $post->post_modified);
		$this->assertEquals("2007-11-23 20:09:34", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/11/24/many-tags/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(36, count($tags));
		$this->assertEquals('chattels', $tags[0]->name);
		$this->assertEquals('chattels', $tags[0]->slug);
		$this->assertEquals('cienaga', $tags[1]->name);
		$this->assertEquals('cienaga', $tags[1]->slug);
		$this->assertEquals('claycold', $tags[2]->name);
		$this->assertEquals('claycold', $tags[2]->slug);
		$this->assertEquals('crushing', $tags[3]->name);
		$this->assertEquals('crushing', $tags[3]->slug);
		$this->assertEquals('dinarchy', $tags[4]->name);
		$this->assertEquals('dinarchy', $tags[4]->slug);
		$this->assertEquals('doolie', $tags[5]->name);
		$this->assertEquals('doolie', $tags[5]->slug);
		$this->assertEquals('energumen', $tags[6]->name);
		$this->assertEquals('energumen', $tags[6]->slug);
		$this->assertEquals('ephialtes', $tags[7]->name);
		$this->assertEquals('ephialtes', $tags[7]->slug);
		$this->assertEquals('eudiometer', $tags[8]->name);
		$this->assertEquals('eudiometer', $tags[8]->slug);
		$this->assertEquals('figuriste', $tags[9]->name);
		$this->assertEquals('figuriste', $tags[9]->slug);
		$this->assertEquals('habergeon', $tags[10]->name);
		$this->assertEquals('habergeon', $tags[10]->slug);
		$this->assertEquals('hapless', $tags[11]->name);
		$this->assertEquals('hapless', $tags[11]->slug);
		$this->assertEquals('hartshorn', $tags[12]->name);
		$this->assertEquals('hartshorn', $tags[12]->slug);
		$this->assertEquals('hostility impregnability', $tags[13]->name);
		$this->assertEquals('hostility-impregnability', $tags[13]->slug);
		$this->assertEquals('impropriation', $tags[14]->name);
		$this->assertEquals('impropriation', $tags[14]->slug);
		$this->assertEquals('knave', $tags[15]->name);
		$this->assertEquals('knave', $tags[15]->slug);
		$this->assertEquals('misinformed', $tags[16]->name);
		$this->assertEquals('misinformed', $tags[16]->slug);
		$this->assertEquals('moil', $tags[17]->name);
		$this->assertEquals('moil', $tags[17]->slug);
		$this->assertEquals('mornful', $tags[18]->name);
		$this->assertEquals('mornful', $tags[18]->slug);
		$this->assertEquals('outlaw', $tags[19]->name);
		$this->assertEquals('outlaw', $tags[19]->slug);
		$this->assertEquals('pamphjlet', $tags[20]->name);
		$this->assertEquals('pamphjlet', $tags[20]->slug);
		$this->assertEquals('pneumatics', $tags[21]->name);
		$this->assertEquals('pneumatics', $tags[21]->slug);
		$this->assertEquals('portly portreeve', $tags[22]->name);
		$this->assertEquals('portly-portreeve', $tags[22]->slug);
		$this->assertEquals('precipitancy', $tags[23]->name);
		$this->assertEquals('precipitancy', $tags[23]->slug);
		$this->assertEquals('privation', $tags[24]->name);
		$this->assertEquals('privation', $tags[24]->slug);
		$this->assertEquals('programme', $tags[25]->name);
		$this->assertEquals('programme', $tags[25]->slug);
		$this->assertEquals('psychological', $tags[26]->name);
		$this->assertEquals('psychological', $tags[26]->slug);
		$this->assertEquals('puncher', $tags[27]->name);
		$this->assertEquals('puncher', $tags[27]->slug);
		$this->assertEquals('ramose', $tags[28]->name);
		$this->assertEquals('ramose', $tags[28]->slug);
		$this->assertEquals('renegade', $tags[29]->name);
		$this->assertEquals('renegade', $tags[29]->slug);
		$this->assertEquals('retrocede', $tags[30]->name);
		$this->assertEquals('retrocede', $tags[30]->slug);
		$this->assertEquals('stagnation unhorsed', $tags[31]->name);
		$this->assertEquals('stagnation-unhorsed', $tags[31]->slug);
		$this->assertEquals('thunderheaded', $tags[32]->name);
		$this->assertEquals('thunderheaded', $tags[32]->slug);
		$this->assertEquals('unculpable', $tags[33]->name);
		$this->assertEquals('unculpable', $tags[33]->slug);
		$this->assertEquals('withered brandnew', $tags[34]->name);
		$this->assertEquals('withered-brandnew', $tags[34]->slug);
		$this->assertEquals('xanthopsia', $tags[35]->name);
		$this->assertEquals('xanthopsia', $tags[35]->slug);
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[10];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-11-09 11:39:38", $post->post_date);
		$this->assertEquals("2007-11-09 01:39:38", $post->post_date_gmt);
		$this->assertEquals("Post with Tag A and Tag C", $post->post_content);
		$this->assertEquals("Tags A and C", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("tags-a-and-c", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-11-09 11:39:38", $post->post_modified);
		$this->assertEquals("2007-11-09 01:39:38", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/11/09/tags-a-and-c/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(2, count($tags));
		$this->assertEquals('Tag A', $tags[0]->name);
		$this->assertEquals('tag-a', $tags[0]->slug);
		$this->assertEquals('Tag C', $tags[1]->name);
		$this->assertEquals('tag-c', $tags[1]->slug);
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[11];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-11-09 11:39:16", $post->post_date);
		$this->assertEquals("2007-11-09 01:39:16", $post->post_date_gmt);
		$this->assertEquals("Post with Tag B and Tag C", $post->post_content);
		$this->assertEquals("Tags B and C", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("tags-b-and-c", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-11-09 11:39:16", $post->post_modified);
		$this->assertEquals("2007-11-09 01:39:16", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/11/09/tags-b-and-c/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(2, count($tags));
		$this->assertEquals('Tag B', $tags[0]->name);
		$this->assertEquals('tag-b', $tags[0]->slug);
		$this->assertEquals('Tag C', $tags[1]->name);
		$this->assertEquals('tag-c', $tags[1]->slug);
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[12];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-11-09 11:38:56", $post->post_date);
		$this->assertEquals("2007-11-09 01:38:56", $post->post_date_gmt);
		$this->assertEquals("Post with Tag A and Tag B", $post->post_content);
		$this->assertEquals("Tags A and B", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("tags-a-and-b", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-11-09 11:38:56", $post->post_modified);
		$this->assertEquals("2007-11-09 01:38:56", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/11/09/tags-a-and-b/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(2, count($tags));
		$this->assertEquals('Tag A', $tags[0]->name);
		$this->assertEquals('tag-a', $tags[0]->slug);
		$this->assertEquals('Tag B', $tags[1]->name);
		$this->assertEquals('tag-b', $tags[1]->slug);
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[13];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-11-09 11:38:16", $post->post_date);
		$this->assertEquals("2007-11-09 01:38:16", $post->post_date_gmt);
		$this->assertEquals("Post with Tag C", $post->post_content);
		$this->assertEquals("Tag C", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("tag-c", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-11-09 11:38:16", $post->post_modified);
		$this->assertEquals("2007-11-09 01:38:16", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/11/09/tag-c/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(1, count($tags));
		$this->assertEquals('Tag C', $tags[0]->name);
		$this->assertEquals('tag-c', $tags[0]->slug);
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[14];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-11-09 11:37:57", $post->post_date);
		$this->assertEquals("2007-11-09 01:37:57", $post->post_date_gmt);
		$this->assertEquals("Post with Tag B", $post->post_content);
		$this->assertEquals("Tag B", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("tag-b", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-11-09 11:37:57", $post->post_modified);
		$this->assertEquals("2007-11-09 01:37:57", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/11/09/tag-b/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(1, count($tags));
		$this->assertEquals('Tag B', $tags[0]->name);
		$this->assertEquals('tag-b', $tags[0]->slug);
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[15];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-11-09 11:37:39", $post->post_date);
		$this->assertEquals("2007-11-09 01:37:39", $post->post_date_gmt);
		$this->assertEquals("Post with Tag A", $post->post_content);
		$this->assertEquals("Tag A", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("tag-a", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-11-09 11:37:39", $post->post_modified);
		$this->assertEquals("2007-11-09 01:37:39", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/11/09/tag-a/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(1, count($tags));
		$this->assertEquals('Tag A', $tags[0]->name);
		$this->assertEquals('tag-a', $tags[0]->slug);
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[16];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-15 16:51:47", $post->post_date);
		$this->assertEquals("2007-09-15 06:51:47", $post->post_date_gmt);
		$this->assertEquals("Simple tag test.", $post->post_content);
		$this->assertEquals("Tags A, B, C", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("tags-a-b-c", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-15 16:51:47", $post->post_modified);
		$this->assertEquals("2007-09-15 06:51:47", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/09/15/tags-a-b-c/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(3, count($tags));
		$this->assertEquals('Tag A', $tags[0]->name);
		$this->assertEquals('tag-a', $tags[0]->slug);
		$this->assertEquals('Tag B', $tags[1]->name);
		$this->assertEquals('tag-b', $tags[1]->slug);
		$this->assertEquals('Tag C', $tags[2]->name);
		$this->assertEquals('tag-c', $tags[2]->slug);
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[17];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-05 14:41:08", $post->post_date);
		$this->assertEquals("2007-09-05 04:41:08", $post->post_date_gmt);
		$this->assertEquals("All the HTML tags listed in the <a href=\"http://faq.wordpress.com/2006/06/08/allowed-html-tags/\">FAQ</a>:\n\n<address>an address</address>\n<a href=\"http://example.com\">a link</a>\n<abbr title=\"abbreviation\">abbr.</abbr>\n<acronym title=\"acronym\">acr.</acronym>\n<b>bold<b>\n<big>big</big>\n<blockquote>a blockquote</blockquote>\nline<br />break\n<cite>a citation</cite>\n\"class\" - eh?\n<code>some code</code>\n<del>deleted text</del>\n<div class=\"myclass\">a div</div>\n<em>emphasis</em>\n<font>font tags are bad</font>\n<h1>heading 1</h1>\n<h2>heading 2</h2>\n<h3>heading 3</h3>\n<h4>heading 4</h4>\n<h5>heading 5</h5>\n<h6>heading 6</h6>\n<i>italic</i>\n<img src=\"http://example.com/wp-content/uploads/2007/09/2007-06-30-dsc_4711-300px.jpg\" />\n<ins>inserted text</ins>\n<kbd>keyboard text</kbd>\n<p>a paragraph</p>\n<pre>pre-\nformatted\ntext</pre>\n<q>a quote</q>\n<s>strike</s>\n<strong>strong</strong>\n<sub>subtext</sub>\n<sup>supertext</sup>\n<tt>teletype text</tt>\n<var>variable</var>\n\n\nList tags:\n\n<dl>\n<dt>term</dt>\n<dd>definition</dd>\n<dt>term 2</dt>\n<dd>definition 2</dd>\n</dl>\n\n<ul>\n<li>item 1</li>\n<li>item 2</li>\n</ul>\n\n<ol>\n<li>item 1</li>\n<li>item 2</li>\n</ol>\n\n\nTable tags:\n\n<table border=\"1\">\n<caption>table caption</caption>\n<col />\n<col />\n<thead>\n<tr>\n<th>heading 1</th>\n<th>heading 2</th>\n</tr>\n</thead>\n<tbody>\n<tr>\n<td>Cell 1</td>\n<td>Cell 2</td>\n</tr>\n</tbody>\n<tfoot>\n<tr>\n<td>footer 1</td>\n<td>footer 2</td>\n</tr>\n</tfoot>\n</table>", $post->post_content);
		$this->assertEquals("Raw HTML code", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("raw-html-code", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-05 14:41:08", $post->post_modified);
		$this->assertEquals("2007-09-05 04:41:08", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/09/05/raw-html-code/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[18];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-04 12:11:23", $post->post_date);
		$this->assertEquals("2007-09-04 02:11:23", $post->post_date_gmt);
		$this->assertEquals("Simple markup inserted using the visual editor:\n\n<strong>Bold</strong>, <em>italic</em>, <strike>strike</strike>.\n<ul>\n\t<li>Bullet 1</li>\n\t<li>Bullet 2\n<ul>\n\t<li>Bullet 3\n<ul>\n\t<li>Bullet 4</li>\n</ul>\n</li>\n</ul>\n</li>\n\t<li>Bullet 5</li>\n</ul>\n<ol>\n\t<li>List 1</li>\n\t<li>List 2\n<ol>\n\t<li>List 3\n<ol>\n\t<li>List 4</li>\n</ol>\n</li>\n\t<li>List 5</li>\n</ol>\n</li>\n\t<li>List 6</li>\n</ol>\nLeft align:\n\nLorem ipsum dolor sit amet, consectetuer adipiscing elit. Sed odio nibh, tincidunt adipiscing, pretium nec, tincidunt id, enim. Fusce scelerisque nunc vitae nisl. Quisque quis urna in velit dictum pellentesque. Vivamus a quam. Curabitur eu tortor id turpis tristique adipiscing. Morbi blandit. Maecenas vel est. Nunc aliquam, orci at accumsan commodo, libero nibh euismod augue, a ullamcorper velit dui et purus. Aenean volutpat, ipsum ac imperdiet fermentum, dui dui suscipit arcu, vitae dictum purus diam ac ligula. Praesent enim nunc, pretium eget, tincidunt in, semper at, mauris. Etiam nec ligula. Aenean purus pede, sagittis at, blandit a, dignissim nec, elit. Etiam nunc. Praesent molestie consectetuer leo. Etiam blandit leo mollis velit. Aenean varius. Maecenas in magna nec justo ornare feugiat. Mauris elit. Nunc volutpat lectus fermentum nibh.\n\nCenter:\n<p align=\"center\">Aenean a turpis eu augue luctus vulputate. Ut nonummy arcu in est. Nulla facilisi. Fusce at est sollicitudin pede gravida luctus. Sed ut dolor non nulla luctus aliquam. Phasellus sodales dapibus turpis. Nulla malesuada. In sed quam. Donec sollicitudin convallis nisl. Donec nunc. Suspendisse malesuada libero in nisi. Etiam vitae metus non arcu gravida tincidunt. Duis accumsan purus et orci. Curabitur volutpat. Nulla quis purus id enim dapibus malesuada. Nam egestas luctus arcu. Praesent iaculis massa.</p>\n<p align=\"left\">Right:</p>\n<p align=\"right\">Aenean tempor, risus nec eleifend tristique, sem orci aliquam urna, eget iaculis tortor mauris ut lorem. Aenean eu tellus. Sed at mauris at nisl ultricies lobortis. Vivamus lacinia, lorem vel congue facilisis, leo leo sodales leo, vitae euismod velit ante a ligula. Vivamus sit amet turpis ut eros molestie porttitor. Nam erat lacus, auctor vel, dictum a, suscipit sed, orci. Quisque est lorem, facilisis consequat, sagittis a, ullamcorper at, ante. Nullam ultricies gravida dui. Nunc mauris. Quisque neque. Quisque eu sem.</p>\n<p align=\"left\">Blockquote:</p>\n\n<blockquote>\n<p align=\"left\">Said Hamlet to Ophelia,\nI'll draw a sketch of thee,\nWhat kind of pencil shall I use?\n2B or not 2B?</blockquote>\n<p align=\"left\"><a href=\"http://example.com/\">Link 1</a>, <a href=\"http://example.com/\" target=\"_blank\">Link 2 (new window)</a>, <a href=\"http://example.com/\">Link 3 (title)</a>.</p>\n<p align=\"left\"> <img src=\"http://wordpress.org/about/images/black-120x90.png\" alt=\"wordpress logo\" height=\"90\" width=\"120\" /> <img src=\"http://wordpress.org/about/images/black-120x90.png\" align=\"right\" height=\"90\" width=\"120\" /></p>\n<p align=\"left\"> Paragraph</p>\n\n<address>Address 1</address> <address>Address 2</address>\n<pre> Pre 1</pre>\n<pre>Pre 2</pre>\n<pre>Pre 3</pre>\n<h1>Heading 1</h1>\n<h2>Heading 2</h2>\n<h3>Heading 3</h3>\n<h4>Heading 4</h4>\n<h5>Heading 5</h5>\n<h6>Heading 6</h6>\n<u>Underline</u>\n\nJustified:\n<p align=\"justify\"> Vivamus volutpat, arcu sed venenatis consequat, nulla pede blandit neque, quis ultrices ligula mauris ut leo. Proin iaculis. Pellentesque vulputate magna at lectus. Etiam semper aliquet lectus. Nullam turpis. Vivamus sed lacus. Integer metus arcu, adipiscing sed, vehicula et, vulputate sit amet, massa. Sed lobortis tempus lectus. In lacus. Duis nibh. Donec molestie libero ut neque. In sollicitudin aliquam felis. Sed molestie libero ac mi. Curabitur magna nunc, feugiat sed, sodales vitae, pretium a, leo. Sed ut ante. Integer turpis ante, facilisis sed, dignissim vitae, consectetuer sed, dui. Sed ultricies.</p>\n<p align=\"left\"><font color=\"#ff0000\">Red </font> <font color=\"#0000ff\">blue <font color=\"#00ff00\">green <font color=\"#000000\">black <font color=\"#ffffff\">white</font></font></font></font></p>\n<p align=\"left\">€  ≥ ° ψ</p>\n<p align=\"left\">&nbsp;</p>", $post->post_content);
		$this->assertEquals("Simple markup test", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("simple-markup-test", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-04 12:11:23", $post->post_modified);
		$this->assertEquals("2007-09-04 02:11:23", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/09/04/simple-markup-test/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[19];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-04 11:53:10", $post->post_date);
		$this->assertEquals("2007-09-04 01:53:10", $post->post_date_gmt);
		$this->assertEquals("Posted as per the instructions in the FAQ.\n\n[youtube=http://www.youtube.com/watch?v=FCXlCkY4Y5g]\n\n[googlevideo=http://video.google.com/videoplay?docid=-184752788325410734]", $post->post_content);
		$this->assertEquals("Embedded video", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("embedded-video", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-04 11:53:10", $post->post_modified);
		$this->assertEquals("2007-09-04 01:53:10", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/09/04/embedded-video/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[20];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-04 10:48:10", $post->post_date);
		$this->assertEquals("2007-09-04 00:48:10", $post->post_date_gmt);
		$this->assertEquals("No comments here.", $post->post_content);
		$this->assertEquals("Page with comments disabled", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("closed", $post->comment_status);
		$this->assertEquals("closed", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("page-with-comments-disabled", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-04 10:48:10", $post->post_modified);
		$this->assertEquals("2007-09-04 00:48:10", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/page-with-comments-disabled/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("page", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$this->assertEquals(array (
  0 => 'default',
), get_post_meta($post->ID, '_wp_page_template', false));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[21];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-04 10:47:47", $post->post_date);
		$this->assertEquals("2007-09-04 00:47:47", $post->post_date_gmt);
		$this->assertEquals("This page has comments.", $post->post_content);
		$this->assertEquals("Page with comments", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("page-with-comments", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-04 10:47:47", $post->post_modified);
		$this->assertEquals("2007-09-04 00:47:47", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/page-with-comments/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("page", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("3", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$this->assertEquals(array (
  0 => 'default',
), get_post_meta($post->ID, '_wp_page_template', false));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(3, count($comments));
		$this->assertEquals('Anon', $comments[0]->comment_author);
		$this->assertEquals('anon@example.com', $comments[0]->comment_author_email);
		$this->assertEquals('', $comments[0]->comment_author_url);
		$this->assertEquals('59.167.157.3', $comments[0]->comment_author_IP);
		$this->assertEquals('2007-09-04 10:49:28', $comments[0]->comment_date);
		$this->assertEquals('2007-09-04 00:49:28', $comments[0]->comment_date_gmt);
		$this->assertEquals('0', $comments[0]->comment_karma);
		$this->assertEquals('1', $comments[0]->comment_approved);
		$this->assertEquals('', $comments[0]->comment_agent);
		$this->assertEquals('', $comments[0]->comment_type);
		$this->assertEquals('0', $comments[0]->comment_parent);
		$this->assertEquals('', $comments[0]->comment_user_id);
		$this->assertEquals('tellyworthtest2', $comments[1]->comment_author);
		$this->assertEquals('tellyworth+test2@gmail.com', $comments[1]->comment_author_email);
		$this->assertEquals('', $comments[1]->comment_author_url);
		$this->assertEquals('59.167.157.3', $comments[1]->comment_author_IP);
		$this->assertEquals('2007-09-04 10:49:03', $comments[1]->comment_date);
		$this->assertEquals('2007-09-04 00:49:03', $comments[1]->comment_date_gmt);
		$this->assertEquals('0', $comments[1]->comment_karma);
		$this->assertEquals('1', $comments[1]->comment_approved);
		$this->assertEquals('', $comments[1]->comment_agent);
		$this->assertEquals('', $comments[1]->comment_type);
		$this->assertEquals('0', $comments[1]->comment_parent);
		$this->assertEquals('', $comments[1]->comment_user_id);
		$this->assertEquals('Alex Shiels', $comments[2]->comment_author);
		$this->assertEquals('tellyworth@gmail.com', $comments[2]->comment_author_email);
		$this->assertEquals('http://flightpath.wordpress.com/', $comments[2]->comment_author_url);
		$this->assertEquals('59.167.157.3', $comments[2]->comment_author_IP);
		$this->assertEquals('2007-09-04 10:48:51', $comments[2]->comment_date);
		$this->assertEquals('2007-09-04 00:48:51', $comments[2]->comment_date_gmt);
		$this->assertEquals('0', $comments[2]->comment_karma);
		$this->assertEquals('1', $comments[2]->comment_approved);
		$this->assertEquals('', $comments[2]->comment_agent);
		$this->assertEquals('', $comments[2]->comment_type);
		$this->assertEquals('0', $comments[2]->comment_parent);
		$this->assertEquals('', $comments[2]->comment_user_id);


		$post = $this->posts[22];
		$this->assertEquals(get_profile('ID', 'User B'), $post->post_author);
		$this->assertEquals("2007-09-04 10:39:56", $post->post_date);
		$this->assertEquals("2007-09-04 00:39:56", $post->post_date_gmt);
		$this->assertEquals("I'm just a lowly contributor.  My posts must be approved by the editor.", $post->post_content);
		$this->assertEquals("Contributor post, approved", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("contributor-post-approved", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-04 10:39:56", $post->post_modified);
		$this->assertEquals("2007-09-04 00:39:56", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/09/04/contributor-post-approved/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[23];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-04 10:25:29", $post->post_date);
		$this->assertEquals("2007-09-04 00:25:29", $post->post_date_gmt);
		$this->assertEquals("A post with a single comment.", $post->post_content);
		$this->assertEquals("One comment", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("one-comment", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-04 10:25:29", $post->post_modified);
		$this->assertEquals("2007-09-04 00:25:29", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/09/04/one-comment/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("1", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(1, count($comments));
		$this->assertEquals('Alex Shiels', $comments[0]->comment_author);
		$this->assertEquals('tellyworth@gmail.com', $comments[0]->comment_author_email);
		$this->assertEquals('http://flightpath.wordpress.com/', $comments[0]->comment_author_url);
		$this->assertEquals('59.167.157.3', $comments[0]->comment_author_IP);
		$this->assertEquals('2007-09-06 15:12:08', $comments[0]->comment_date);
		$this->assertEquals('2007-09-06 05:12:08', $comments[0]->comment_date_gmt);
		$this->assertEquals('0', $comments[0]->comment_karma);
		$this->assertEquals('1', $comments[0]->comment_approved);
		$this->assertEquals('', $comments[0]->comment_agent);
		$this->assertEquals('', $comments[0]->comment_type);
		$this->assertEquals('0', $comments[0]->comment_parent);
		$this->assertEquals('', $comments[0]->comment_user_id);


		$post = $this->posts[24];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-04 10:21:15", $post->post_date);
		$this->assertEquals("2007-09-04 00:21:15", $post->post_date_gmt);
		$this->assertEquals("Comments are disabled.", $post->post_content);
		$this->assertEquals("No comments", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("closed", $post->comment_status);
		$this->assertEquals("closed", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("no-comments", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-04 10:21:15", $post->post_modified);
		$this->assertEquals("2007-09-04 00:21:15", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/09/04/no-comments/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[25];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-04 10:17:18", $post->post_date);
		$this->assertEquals("2007-09-04 00:17:18", $post->post_date_gmt);
		#$this->assertEquals("This post has many trackbacks.<span style=\"font-family:Arial;font-size:11px;line-height:normal;\" class=\"Apple-style-span\">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Cras ligula. Vivamus urna diam, mollis nec, pellentesque et, semper nec, lorem. Nam lobortis, eros a feugiat porttitor, nibh mi imperdiet nulla, eu venenatis diam enim non eros. Duis consectetuer augue a ante. Vivamus adipiscing orci et ipsum. Ut consectetuer lacinia magna. Etiam id orci. Vestibulum pede magna, feugiat et, adipiscing vitae, tincidunt non, mauris. Curabitur auctor diam non nibh. Fusce nec diam. Praesent laoreet blandit turpis. Phasellus et eros. Nulla venenatis nulla ut magna. Nunc porttitor eros sed quam. Morbi id nisi ut sem faucibus tempus.</span> ", $post->post_content);
		$this->assertEquals("Many Trackbacks", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("many-trackbacks", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-04 10:17:18", $post->post_modified);
		$this->assertEquals("2007-09-04 00:17:18", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/09/04/many-trackbacks/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("4", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(4, count($comments));
		$this->assertEquals('Ping 4 &laquo; What&#8217;s a tellyworth?', $comments[0]->comment_author);
		$this->assertEquals('', $comments[0]->comment_author_email);
		$this->assertEquals('http://tellyworth.wordpress.com/2007/11/21/ping-4/', $comments[0]->comment_author_url);
		$this->assertEquals('72.232.101.12', $comments[0]->comment_author_IP);
		$this->assertEquals('2007-11-21 11:39:25', $comments[0]->comment_date);
		$this->assertEquals('2007-11-21 01:39:25', $comments[0]->comment_date_gmt);
		$this->assertEquals('0', $comments[0]->comment_karma);
		$this->assertEquals('1', $comments[0]->comment_approved);
		$this->assertEquals('', $comments[0]->comment_agent);
		$this->assertEquals('pingback', $comments[0]->comment_type);
		$this->assertEquals('0', $comments[0]->comment_parent);
		$this->assertEquals('', $comments[0]->comment_user_id);
		$this->assertEquals('Ping 3 &laquo; What&#8217;s a tellyworth?', $comments[1]->comment_author);
		$this->assertEquals('', $comments[1]->comment_author_email);
		$this->assertEquals('http://tellyworth.wordpress.com/2007/11/21/ping-3/', $comments[1]->comment_author_url);
		$this->assertEquals('72.232.101.12', $comments[1]->comment_author_IP);
		$this->assertEquals('2007-11-21 11:38:22', $comments[1]->comment_date);
		$this->assertEquals('2007-11-21 01:38:22', $comments[1]->comment_date_gmt);
		$this->assertEquals('0', $comments[1]->comment_karma);
		$this->assertEquals('1', $comments[1]->comment_approved);
		$this->assertEquals('', $comments[1]->comment_agent);
		$this->assertEquals('pingback', $comments[1]->comment_type);
		$this->assertEquals('0', $comments[1]->comment_parent);
		$this->assertEquals('', $comments[1]->comment_user_id);
		$this->assertEquals('Ping 2 with a much longer title than the previous ping, which was called Ping 1 &laquo; What&#8217;s a tellyworth?', $comments[2]->comment_author);
		$this->assertEquals('', $comments[2]->comment_author_email);
		$this->assertEquals('http://tellyworth.wordpress.com/2007/11/21/ping-2-with-a-much-longer-title-than-the-previous-ping-which-was-called-ping-1/', $comments[2]->comment_author_url);
		$this->assertEquals('72.232.101.12', $comments[2]->comment_author_IP);
		$this->assertEquals('2007-11-21 11:35:47', $comments[2]->comment_date);
		$this->assertEquals('2007-11-21 01:35:47', $comments[2]->comment_date_gmt);
		$this->assertEquals('0', $comments[2]->comment_karma);
		$this->assertEquals('1', $comments[2]->comment_approved);
		$this->assertEquals('', $comments[2]->comment_agent);
		$this->assertEquals('pingback', $comments[2]->comment_type);
		$this->assertEquals('0', $comments[2]->comment_parent);
		$this->assertEquals('', $comments[2]->comment_user_id);
		$this->assertEquals('Ping 1 &laquo; What&#8217;s a tellyworth?', $comments[3]->comment_author);
		$this->assertEquals('', $comments[3]->comment_author_email);
		$this->assertEquals('http://tellyworth.wordpress.com/2007/11/21/ping-1/', $comments[3]->comment_author_url);
		$this->assertEquals('72.232.101.12', $comments[3]->comment_author_IP);
		$this->assertEquals('2007-11-21 11:31:12', $comments[3]->comment_date);
		$this->assertEquals('2007-11-21 01:31:12', $comments[3]->comment_date_gmt);
		$this->assertEquals('0', $comments[3]->comment_karma);
		$this->assertEquals('1', $comments[3]->comment_approved);
		$this->assertEquals('', $comments[3]->comment_agent);
		$this->assertEquals('pingback', $comments[3]->comment_type);
		$this->assertEquals('0', $comments[3]->comment_parent);
		$this->assertEquals('', $comments[3]->comment_user_id);


		$post = $this->posts[26];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-04 10:15:26", $post->post_date);
		$this->assertEquals("2007-09-04 00:15:26", $post->post_date_gmt);
		$this->assertEquals("A post with a single trackback.", $post->post_content);
		$this->assertEquals("One trackback", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("one-trackback", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-04 10:15:26", $post->post_modified);
		$this->assertEquals("2007-09-04 00:15:26", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/09/04/one-trackback/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("1", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(1, count($comments));
		$this->assertEquals('Another ping test &laquo; What&#8217;s a tellyworth?', $comments[0]->comment_author);
		$this->assertEquals('', $comments[0]->comment_author_email);
		$this->assertEquals('http://tellyworth.wordpress.com/2007/09/13/another-ping-test/', $comments[0]->comment_author_url);
		$this->assertEquals('72.232.101.12', $comments[0]->comment_author_IP);
		$this->assertEquals('2007-09-13 18:47:43', $comments[0]->comment_date);
		$this->assertEquals('2007-09-13 08:47:43', $comments[0]->comment_date_gmt);
		$this->assertEquals('0', $comments[0]->comment_karma);
		$this->assertEquals('1', $comments[0]->comment_approved);
		$this->assertEquals('', $comments[0]->comment_agent);
		$this->assertEquals('pingback', $comments[0]->comment_type);
		$this->assertEquals('0', $comments[0]->comment_parent);
		$this->assertEquals('', $comments[0]->comment_user_id);


		$post = $this->posts[27];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-04 10:11:37", $post->post_date);
		$this->assertEquals("2007-09-04 00:11:37", $post->post_date_gmt);
		$this->assertEquals("Here's a post with some comments.", $post->post_content);
		$this->assertEquals("Comment test", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("comment-test", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-04 10:11:37", $post->post_modified);
		$this->assertEquals("2007-09-04 00:11:37", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/09/04/comment-test/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("10", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(12, count($comments));
		$this->assertEquals('Joseph Scott', $comments[0]->comment_author);
		$this->assertEquals('joseph@randomnetworks.com', $comments[0]->comment_author_email);
		$this->assertEquals('http://joseph.randomnetworks.com/', $comments[0]->comment_author_url);
		$this->assertEquals('63.226.101.77', $comments[0]->comment_author_IP);
		$this->assertEquals('2007-12-08 07:24:25', $comments[0]->comment_date);
		$this->assertEquals('2007-12-07 21:24:25', $comments[0]->comment_date_gmt);
		$this->assertEquals('0', $comments[0]->comment_karma);
		$this->assertEquals('0', $comments[0]->comment_approved);
		$this->assertEquals('', $comments[0]->comment_agent);
		$this->assertEquals('', $comments[0]->comment_type);
		$this->assertEquals('0', $comments[0]->comment_parent);
		$this->assertEquals('', $comments[0]->comment_user_id);
		$this->assertEquals('pinging like a microwave &laquo; no kubrick allowed', $comments[1]->comment_author);
		$this->assertEquals('', $comments[1]->comment_author_email);
		$this->assertEquals('http://ntutest.wordpress.com/2007/11/19/pinging-like-a-microwave/', $comments[1]->comment_author_url);
		$this->assertEquals('72.232.131.29', $comments[1]->comment_author_IP);
		$this->assertEquals('2007-11-20 03:44:15', $comments[1]->comment_date);
		$this->assertEquals('2007-11-19 17:44:15', $comments[1]->comment_date_gmt);
		$this->assertEquals('0', $comments[1]->comment_karma);
		$this->assertEquals('0', $comments[1]->comment_approved);
		$this->assertEquals('', $comments[1]->comment_agent);
		$this->assertEquals('pingback', $comments[1]->comment_type);
		$this->assertEquals('0', $comments[1]->comment_parent);
		$this->assertEquals('', $comments[1]->comment_user_id);
		$this->assertEquals('mdawaffe', $comments[2]->comment_author);
		$this->assertEquals('wpcom@blogwaffe.com', $comments[2]->comment_author_email);
		$this->assertEquals('http://blogwaffe.com', $comments[2]->comment_author_url);
		$this->assertEquals('71.80.169.225', $comments[2]->comment_author_IP);
		$this->assertEquals('2007-09-04 16:51:33', $comments[2]->comment_date);
		$this->assertEquals('2007-09-04 06:51:33', $comments[2]->comment_date_gmt);
		$this->assertEquals('0', $comments[2]->comment_karma);
		$this->assertEquals('1', $comments[2]->comment_approved);
		$this->assertEquals('', $comments[2]->comment_agent);
		$this->assertEquals('', $comments[2]->comment_type);
		$this->assertEquals('0', $comments[2]->comment_parent);
		$this->assertEquals('', $comments[2]->comment_user_id);
		$this->assertEquals('Alex Shiels', $comments[3]->comment_author);
		$this->assertEquals('tellyworth@gmail.com', $comments[3]->comment_author_email);
		$this->assertEquals('http://flightpath.wordpress.com/', $comments[3]->comment_author_url);
		$this->assertEquals('59.167.157.3', $comments[3]->comment_author_IP);
		$this->assertEquals('2007-09-04 13:26:26', $comments[3]->comment_date);
		$this->assertEquals('2007-09-04 03:26:26', $comments[3]->comment_date_gmt);
		$this->assertEquals('0', $comments[3]->comment_karma);
		$this->assertEquals('1', $comments[3]->comment_approved);
		$this->assertEquals('', $comments[3]->comment_agent);
		$this->assertEquals('', $comments[3]->comment_type);
		$this->assertEquals('0', $comments[3]->comment_parent);
		$this->assertEquals('', $comments[3]->comment_user_id);
		$this->assertEquals('test test', $comments[4]->comment_author);
		$this->assertEquals('', $comments[4]->comment_author_email);
		$this->assertEquals('http://tellyworthtest.wordpress.com/2007/10/15/ping-test-2/', $comments[4]->comment_author_url);
		$this->assertEquals('72.232.101.12', $comments[4]->comment_author_IP);
		$this->assertEquals('2007-09-04 11:04:01', $comments[4]->comment_date);
		$this->assertEquals('2007-09-04 01:04:01', $comments[4]->comment_date_gmt);
		$this->assertEquals('0', $comments[4]->comment_karma);
		$this->assertEquals('1', $comments[4]->comment_approved);
		$this->assertEquals('', $comments[4]->comment_agent);
		$this->assertEquals('trackback', $comments[4]->comment_type);
		$this->assertEquals('0', $comments[4]->comment_parent);
		$this->assertEquals('', $comments[4]->comment_user_id);
		$this->assertEquals('tellyworthtest2', $comments[5]->comment_author);
		$this->assertEquals('tellyworth+test2@gmail.com', $comments[5]->comment_author_email);
		$this->assertEquals('', $comments[5]->comment_author_url);
		$this->assertEquals('59.167.157.3', $comments[5]->comment_author_IP);
		$this->assertEquals('2007-09-04 10:45:21', $comments[5]->comment_date);
		$this->assertEquals('2007-09-04 00:45:21', $comments[5]->comment_date_gmt);
		$this->assertEquals('0', $comments[5]->comment_karma);
		$this->assertEquals('1', $comments[5]->comment_approved);
		$this->assertEquals('', $comments[5]->comment_agent);
		$this->assertEquals('', $comments[5]->comment_type);
		$this->assertEquals('0', $comments[5]->comment_parent);
		$this->assertEquals('', $comments[5]->comment_user_id);
		$this->assertEquals('tellyworthtest1', $comments[6]->comment_author);
		$this->assertEquals('tellyworth+test1@gmail.com', $comments[6]->comment_author_email);
		$this->assertEquals('', $comments[6]->comment_author_url);
		$this->assertEquals('59.167.157.3', $comments[6]->comment_author_IP);
		$this->assertEquals('2007-09-04 10:35:33', $comments[6]->comment_date);
		$this->assertEquals('2007-09-04 00:35:33', $comments[6]->comment_date_gmt);
		$this->assertEquals('0', $comments[6]->comment_karma);
		$this->assertEquals('1', $comments[6]->comment_approved);
		$this->assertEquals('', $comments[6]->comment_agent);
		$this->assertEquals('', $comments[6]->comment_type);
		$this->assertEquals('0', $comments[6]->comment_parent);
		$this->assertEquals('', $comments[6]->comment_user_id);
		$this->assertEquals('Lloyd Budd', $comments[7]->comment_author);
		$this->assertEquals('foolswisdom@gmail.com', $comments[7]->comment_author_email);
		$this->assertEquals('http://foolswisdom.com', $comments[7]->comment_author_url);
		$this->assertEquals('24.68.153.74', $comments[7]->comment_author_IP);
		$this->assertEquals('2007-09-04 10:23:10', $comments[7]->comment_date);
		$this->assertEquals('2007-09-04 00:23:10', $comments[7]->comment_date_gmt);
		$this->assertEquals('0', $comments[7]->comment_karma);
		$this->assertEquals('1', $comments[7]->comment_approved);
		$this->assertEquals('', $comments[7]->comment_agent);
		$this->assertEquals('', $comments[7]->comment_type);
		$this->assertEquals('0', $comments[7]->comment_parent);
		$this->assertEquals('', $comments[7]->comment_user_id);
		$this->assertEquals('tellyworthtest', $comments[8]->comment_author);
		$this->assertEquals('tellyworth+test@gmail.com', $comments[8]->comment_author_email);
		$this->assertEquals('http://', $comments[8]->comment_author_url);
		$this->assertEquals('59.167.157.3', $comments[8]->comment_author_IP);
		$this->assertEquals('2007-09-04 10:18:04', $comments[8]->comment_date);
		$this->assertEquals('2007-09-04 00:18:04', $comments[8]->comment_date_gmt);
		$this->assertEquals('0', $comments[8]->comment_karma);
		$this->assertEquals('1', $comments[8]->comment_approved);
		$this->assertEquals('', $comments[8]->comment_agent);
		$this->assertEquals('', $comments[8]->comment_type);
		$this->assertEquals('0', $comments[8]->comment_parent);
		$this->assertEquals('', $comments[8]->comment_user_id);
		$this->assertEquals('Matt', $comments[9]->comment_author);
		$this->assertEquals('m@mullenweg.com', $comments[9]->comment_author_email);
		$this->assertEquals('http://photomatt.net/', $comments[9]->comment_author_url);
		$this->assertEquals('59.167.157.3', $comments[9]->comment_author_IP);
		$this->assertEquals('2007-09-04 10:15:32', $comments[9]->comment_date);
		$this->assertEquals('2007-09-04 00:15:32', $comments[9]->comment_date_gmt);
		$this->assertEquals('0', $comments[9]->comment_karma);
		$this->assertEquals('1', $comments[9]->comment_approved);
		$this->assertEquals('', $comments[9]->comment_agent);
		$this->assertEquals('', $comments[9]->comment_type);
		$this->assertEquals('0', $comments[9]->comment_parent);
		$this->assertEquals('', $comments[9]->comment_user_id);
		$this->assertEquals('Anon', $comments[10]->comment_author);
		$this->assertEquals('nobody@example.com', $comments[10]->comment_author_email);
		$this->assertEquals('', $comments[10]->comment_author_url);
		$this->assertEquals('59.167.157.3', $comments[10]->comment_author_IP);
		$this->assertEquals('2007-09-04 10:14:19', $comments[10]->comment_date);
		$this->assertEquals('2007-09-04 00:14:19', $comments[10]->comment_date_gmt);
		$this->assertEquals('0', $comments[10]->comment_karma);
		$this->assertEquals('1', $comments[10]->comment_approved);
		$this->assertEquals('', $comments[10]->comment_agent);
		$this->assertEquals('', $comments[10]->comment_type);
		$this->assertEquals('0', $comments[10]->comment_parent);
		$this->assertEquals('', $comments[10]->comment_user_id);
		$this->assertEquals('Alex Shiels', $comments[11]->comment_author);
		$this->assertEquals('tellyworth@gmail.com', $comments[11]->comment_author_email);
		$this->assertEquals('http://flightpath.wordpress.com/', $comments[11]->comment_author_url);
		$this->assertEquals('59.167.157.3', $comments[11]->comment_author_IP);
		$this->assertEquals('2007-09-04 10:12:13', $comments[11]->comment_date);
		$this->assertEquals('2007-09-04 00:12:13', $comments[11]->comment_date_gmt);
		$this->assertEquals('0', $comments[11]->comment_karma);
		$this->assertEquals('1', $comments[11]->comment_approved);
		$this->assertEquals('', $comments[11]->comment_agent);
		$this->assertEquals('', $comments[11]->comment_type);
		$this->assertEquals('0', $comments[11]->comment_parent);
		$this->assertEquals('', $comments[11]->comment_user_id);


		$post = $this->posts[28];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-04 09:55:04", $post->post_date);
		$this->assertEquals("2007-09-03 23:55:04", $post->post_date_gmt);
		$this->assertEquals("Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Maecenas sapien. Quisque suscipit tincidunt ipsum. Pellentesque ac nisi blandit tellus eleifend vulputate. Donec fermentum dolor nec pede. Phasellus pede. Sed ut odio. Etiam pharetra neque auctor sapien. Ut dolor lacus, pharetra vitae, dignissim sit amet, fringilla eu, tellus. Etiam vestibulum. Cras risus felis, interdum ac, ullamcorper id, euismod id, augue. Quisque tristique risus quis arcu. Pellentesque id nisl sed turpis dapibus eleifend. Aliquam non urna. In dictum commodo felis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Fusce hendrerit. Nullam ligula nunc, placerat a, ultrices in, pellentesque at, tortor. Nunc volutpat justo vestibulum sem. Aenean tincidunt sem sed nibh.Nullam commodo, diam sodales porta aliquet, metus erat interdum massa, vel tristique lacus orci et arcu. Aliquam erat volutpat. Aliquam est orci, varius quis, dictum ut, egestas sed, tellus. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec sodales dictum enim. In lorem sem, ultricies vel, eleifend eu, rhoncus a, nibh. In hac habitasse platea dictumst. Nam aliquam porta quam. Suspendisse id magna. Sed pulvinar ante eget erat. Nunc mauris tortor, venenatis hendrerit, blandit et, dapibus ut, enim. Etiam est diam, nonummy at, porta at, hendrerit in, ligula. Maecenas ut arcu vitae lacus gravida tristique. Phasellus venenatis. Suspendisse tortor augue, accumsan et, dapibus sed, tempor non, urna. Integer eget magna.<!--more-->Ut sit amet nisl. Suspendisse potenti. Praesent at nisl sit amet lacus suscipit fermentum. Integer non purus at nisi elementum mattis. Mauris eleifend. Donec viverra varius lectus. Nullam erat. Nam vestibulum purus. Curabitur vel dolor sed odio egestas cursus. Nam justo nisl, dictum semper, pharetra vel, dapibus sed, arcu.Mauris at nisi. Praesent imperdiet ante id nisi. Donec sed metus eu magna pulvinar convallis. Duis at arcu sed nulla lobortis dictum. Maecenas egestas. Vivamus vulputate tellus eget sapien. Vestibulum quis lectus in felis ultricies bibendum. Aenean tincidunt. Cras mollis. Maecenas auctor. Pellentesque nonummy. Sed odio ante, consequat vitae, auctor auctor, euismod sit amet, augue. Fusce ullamcorper sollicitudin urna.<!--nextpage-->Suspendisse cursus metus in velit. Aliquam nulla odio, posuere in, fermentum at, eleifend at, felis. Sed velit quam, vehicula ut, nonummy vel, vulputate hendrerit, nibh. Mauris aliquam sapien at odio. Maecenas turpis ipsum, condimentum a, dictum at, convallis et, magna. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Praesent posuere. Nunc ac dui eget tellus vehicula lobortis. Vestibulum molestie neque vel nisi.Etiam adipiscing est luctus neque. Ut lorem ante, molestie a, vestibulum ut, consequat sed, ipsum. Mauris in lorem a ligula tincidunt dapibus. Nunc feugiat. Nullam a nisl. Sed iaculis, dolor vitae tincidunt scelerisque, erat elit volutpat est, eget elementum arcu pede vitae sapien. Nunc iaculis, velit vitae convallis vehicula, neque dolor ornare pede, sed fermentum nisl nulla nonummy lorem. Praesent erat leo, porttitor nec, adipiscing eu, pellentesque a, erat. Aenean facilisis, nisi ac placerat varius, nibh pede nonummy nisl, vitae interdum felis urna at sem. Vestibulum eu dui non pede tempus feugiat. Nunc sed pede. Pellentesque rutrum lobortis mi. Nulla nulla mauris, feugiat porta, tempor nec, accumsan vel, nisi. Integer tincidunt. Praesent orci. Nulla facilisi. Morbi velit arcu, tristique in, porttitor sit amet, consectetuer ac, sapien. Ut tincidunt eros et augue.<!--nextpage-->Proin eu nisi. Sed suscipit mollis elit. Phasellus et tortor. Etiam nisi dui, suscipit sed, imperdiet ac, iaculis ac, dui. Donec hendrerit. Suspendisse laoreet condimentum ipsum. In vestibulum, metus sed malesuada sagittis, justo mauris aliquam mi, a dignissim erat odio eleifend eros. Ut accumsan, eros nec tempus tincidunt, turpis risus egestas nunc, eget porttitor libero sem id neque. Proin aliquet diam quis est. Proin euismod fermentum dolor. Ut imperdiet scelerisque nulla. Maecenas massa risus, aliquet vel, sagittis ac, convallis at, quam. Phasellus ac nisi a tellus aliquam lacinia. Ut ut mauris. Etiam malesuada turpis ut lacus. Sed pretium posuere eros. Mauris dapibus, augue a dictum imperdiet, tellus sem consequat dui, in viverra arcu justo quis justo. Donec ultrices commodo tellus. Fusce vitae pede. Pellentesque vel lectus ac quam fermentum rutrum.Donec varius nunc ac nunc. Duis tortor. Pellentesque imperdiet est at ante. Morbi felis eros, sollicitudin sodales, sodales vitae, mattis vitae, lorem. Donec odio sapien, venenatis at, molestie id, hendrerit a, quam. Sed suscipit justo eget augue. Praesent ornare, nisi ac blandit mollis, mauris leo ultricies nunc, quis tincidunt mi pede in metus. Suspendisse potenti. Nunc aliquet. Donec eu nibh. Sed libero ipsum, sagittis eget, commodo eu, lacinia eu, ante. Cras felis enim, convallis ornare, consectetuer vel, dapibus at, diam.<!--nextpage-->Donec venenatis, eros eget molestie adipiscing, quam massa pretium nulla, in semper nunc justo consequat velit. Duis vitae nisl ac arcu tristique tristique. Etiam enim quam, tempus nec, placerat a, faucibus sed, nisl. Quisque quis sem ac sapien vulputate aliquet. Nunc bibendum odio a leo. Cras dignissim egestas tortor. Pellentesque sagittis, velit eu ultricies luctus, turpis erat condimentum velit, vitae dictum est lorem quis nibh. In mi dolor, mollis eget, lobortis et, convallis non, erat. Morbi rutrum, ligula eu viverra eleifend, mauris massa dapibus mi, sed varius urna ante ut arcu. Praesent et erat.Nullam egestas. Pellentesque rutrum, elit ac ultricies accumsan, leo ipsum blandit metus, ac adipiscing turpis ligula nec metus. Phasellus posuere. Nunc venenatis. Cras consequat dui ac tellus. Vestibulum ultrices dapibus nunc. Sed ut nisl in elit tincidunt faucibus. Nulla arcu. Nunc pulvinar. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. In sit amet erat eu ipsum consectetuer vehicula. Nulla iaculis ligula vitae odio. Donec nunc. Etiam vel ante. Quisque mollis erat a leo. Ut eget dui. Cras quis orci egestas ante aliquam fermentum. In vehicula dapibus sem. Sed ultrices odio non dui laoreet viverra.", $post->post_content);
		$this->assertEquals("A post with multiple pages", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("a-post-with-multiple-pages", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-04 09:55:04", $post->post_modified);
		$this->assertEquals("2007-09-03 23:55:04", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/09/04/a-post-with-several-more-tags/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$this->assertEquals(array (
  0 => 'a-post-with-several-more-tags',
), get_post_meta($post->ID, '_wp_old_slug', false));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[29];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-04 09:53:18", $post->post_date);
		$this->assertEquals("2007-09-03 23:53:18", $post->post_date_gmt);
		$this->assertEquals("Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec mollis. Quisque convallis libero in sapien pharetra tincidunt. Aliquam elit ante, malesuada id, tempor eu, gravida id, odio. Maecenas suscipit, risus et eleifend imperdiet, nisi orci ullamcorper massa, et adipiscing orci velit quis magna. Praesent sit amet ligula id orci venenatis auctor. Phasellus porttitor, metus non tincidunt dapibus, orci pede pretium neque, sit amet adipiscing ipsum lectus et libero. Aenean bibendum. Curabitur mattis quam id urna. Vivamus dui. Donec nonummy lacinia lorem. Cras risus arcu, sodales ac, ultrices ac, mollis quis, justo. Sed a libero. Quisque risus erat, posuere at, tristique non, lacinia quis, eros.\n\n<!--more-->\n\nCras volutpat, lacus quis semper pharetra, nisi enim dignissim est, et sollicitudin quam ipsum vel mi. Sed commodo urna ac urna. Nullam eu tortor. Curabitur sodales scelerisque magna. Donec ultricies tristique pede. Nullam libero. Nam sollicitudin felis vel metus. Nullam posuere molestie metus. Nullam molestie, nunc id suscipit rhoncus, felis mi vulputate lacus, a ultrices tortor dolor eget augue. Aenean ultricies felis ut turpis. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Suspendisse placerat tellus ac nulla. Proin adipiscing sem ac risus. Maecenas nisi. Cras semper.\n\nPraesent interdum mollis neque. In egestas nulla eget pede. Integer eu purus sed diam dictum scelerisque. Morbi cursus velit et felis. Maecenas faucibus aliquet erat. In aliquet rhoncus tellus. Integer auctor nibh a nunc fringilla tempus. Cras turpis urna, dignissim vel, suscipit pulvinar, rutrum quis, sem. Ut lobortis convallis dui. Sed nonummy orci a justo. Morbi nec diam eget eros eleifend tincidunt.\n\nCurabitur non elit. Pellentesque iaculis, nisl non aliquet adipiscing, purus urna aliquet orci, sed sodales pede neque at massa. Pellentesque laoreet, enim eget varius mollis, sapien erat suscipit metus, sit amet iaculis nulla sapien id felis. Aliquam erat volutpat. Nam congue nulla a ligula. Morbi tempor hendrerit erat. Curabitur augue. Vestibulum nulla est, commodo et, fringilla quis, bibendum eget, ipsum. Suspendisse pulvinar iaculis ante. Mauris dignissim ante quis nisi. Aliquam ante mi, aliquam et, pellentesque ac, dapibus et, enim. In vulputate justo vel magna. Phasellus imperdiet justo. Proin odio orci, dapibus id, porta a, pellentesque id, erat. Aliquam erat volutpat. Mauris nonummy varius libero. Sed dolor ipsum, tempor non, aliquet et, pulvinar quis, dui. Pellentesque mauris diam, lobortis id, varius varius, facilisis at, nulla.\n\nCras pede. Nullam id velit sit amet turpis tincidunt sagittis. Nunc malesuada. Nunc consequat scelerisque odio. Donec eu leo. Nunc pellentesque felis sed odio. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Vivamus lobortis metus in lectus. Cras mollis quam eget sapien. Pellentesque non lorem sit amet sem lacinia euismod.\n\nNulla eget diam eget leo imperdiet consequat. Morbi nunc magna, pellentesque eu, porta at, ultricies ut, neque. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; In tincidunt. Praesent ut orci id eros congue ultrices. Mauris non neque. Donec nulla ante, molestie sit amet, fermentum nec, blandit sit amet, purus. Fusce eget diam eu odio iaculis mollis. Phasellus consectetuer pede quis nisi. Proin non sem ut elit pulvinar faucibus. In a turpis nec augue fringilla elementum.\n\nNullam felis. Donec in nulla. Suspendisse sodales, turpis in suscipit ullamcorper, enim nunc sagittis risus, eu auctor velit tortor ut turpis. Mauris id augue at neque aliquam eleifend. Sed eget augue. Nunc faucibus ligula sed massa. Etiam non nulla. Etiam accumsan ullamcorper nisl. In pharetra massa at nunc. Nunc elementum. Duis sodales enim nec libero. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Praesent dapibus eros sodales urna. Duis magna nisi, lobortis quis, tincidunt rutrum, posuere non, ipsum.\n\nAliquam convallis neque vitae diam. In diam. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Duis fermentum arcu in tortor. Sed nibh leo, rhoncus eu, fermentum et, scelerisque ac, massa. Cras id turpis. Etiam commodo sem luctus lorem. Morbi at mi. In rutrum. Aenean luctus pede euismod tortor. Phasellus dictum. Cras neque justo, venenatis sit amet, tristique et, vulputate in, dui. Etiam sed mi gravida sapien imperdiet dictum. Aliquam gravida orci a tortor. Donec tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Vivamus risus ante, pellentesque vitae, luctus eget, scelerisque sed, libero. Donec massa.\n\nDonec libero mauris, volutpat at, convallis vel, laoreet euismod, augue. In accumsan malesuada risus. Mauris metus magna, condimentum in, nonummy non, ornare eu, velit. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Proin posuere. Proin rhoncus rutrum lorem. Phasellus dignissim massa non libero volutpat tincidunt. In hac habitasse platea dictumst. Phasellus eget eros. Nulla in nulla. Vivamus quis mauris. Maecenas pharetra rhoncus tellus. Sed sit amet lacus.\n\nQuisque interdum felis a tellus. Aliquam sed diam ac velit aliquam rutrum. Morbi commodo, risus a pulvinar adipiscing, tortor pede posuere risus, ac ornare tellus massa nec lectus. Vivamus mollis metus ac sapien. Nam sed est a libero ullamcorper dapibus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Aenean a erat ac nibh accumsan volutpat. Phasellus pulvinar consequat turpis. Curabitur ante metus, tempus ut, consequat eu, sollicitudin sit amet, justo. Duis ut libero.", $post->post_content);
		$this->assertEquals("An article with a More tag", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("lorem-ipsum", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-04 09:53:18", $post->post_modified);
		$this->assertEquals("2007-09-03 23:53:18", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/09/04/lorem-ipsum/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[30];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-04 09:52:50", $post->post_date);
		$this->assertEquals("2007-09-03 23:52:50", $post->post_date_gmt);
		$this->assertEquals("Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec mollis. Quisque convallis libero in sapien pharetra tincidunt. Aliquam elit ante, malesuada id, tempor eu, gravida id, odio. Maecenas suscipit, risus et eleifend imperdiet, nisi orci ullamcorper massa, et adipiscing orci velit quis magna. Praesent sit amet ligula id orci venenatis auctor. Phasellus porttitor, metus non tincidunt dapibus, orci pede pretium neque, sit amet adipiscing ipsum lectus et libero. Aenean bibendum. Curabitur mattis quam id urna. Vivamus dui. Donec nonummy lacinia lorem. Cras risus arcu, sodales ac, ultrices ac, mollis quis, justo. Sed a libero. Quisque risus erat, posuere at, tristique non, lacinia quis, eros.\n\nCras volutpat, lacus quis semper pharetra, nisi enim dignissim est, et sollicitudin quam ipsum vel mi. Sed commodo urna ac urna. Nullam eu tortor. Curabitur sodales scelerisque magna. Donec ultricies tristique pede. Nullam libero. Nam sollicitudin felis vel metus. Nullam posuere molestie metus. Nullam molestie, nunc id suscipit rhoncus, felis mi vulputate lacus, a ultrices tortor dolor eget augue. Aenean ultricies felis ut turpis. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Suspendisse placerat tellus ac nulla. Proin adipiscing sem ac risus. Maecenas nisi. Cras semper.\n\nPraesent interdum mollis neque. In egestas nulla eget pede. Integer eu purus sed diam dictum scelerisque. Morbi cursus velit et felis. Maecenas faucibus aliquet erat. In aliquet rhoncus tellus. Integer auctor nibh a nunc fringilla tempus. Cras turpis urna, dignissim vel, suscipit pulvinar, rutrum quis, sem. Ut lobortis convallis dui. Sed nonummy orci a justo. Morbi nec diam eget eros eleifend tincidunt.\n\nCurabitur non elit. Pellentesque iaculis, nisl non aliquet adipiscing, purus urna aliquet orci, sed sodales pede neque at massa. Pellentesque laoreet, enim eget varius mollis, sapien erat suscipit metus, sit amet iaculis nulla sapien id felis. Aliquam erat volutpat. Nam congue nulla a ligula. Morbi tempor hendrerit erat. Curabitur augue. Vestibulum nulla est, commodo et, fringilla quis, bibendum eget, ipsum. Suspendisse pulvinar iaculis ante. Mauris dignissim ante quis nisi. Aliquam ante mi, aliquam et, pellentesque ac, dapibus et, enim. In vulputate justo vel magna. Phasellus imperdiet justo. Proin odio orci, dapibus id, porta a, pellentesque id, erat. Aliquam erat volutpat. Mauris nonummy varius libero. Sed dolor ipsum, tempor non, aliquet et, pulvinar quis, dui. Pellentesque mauris diam, lobortis id, varius varius, facilisis at, nulla.\n\nCras pede. Nullam id velit sit amet turpis tincidunt sagittis. Nunc malesuada. Nunc consequat scelerisque odio. Donec eu leo. Nunc pellentesque felis sed odio. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Vivamus lobortis metus in lectus. Cras mollis quam eget sapien. Pellentesque non lorem sit amet sem lacinia euismod.\n\nNulla eget diam eget leo imperdiet consequat. Morbi nunc magna, pellentesque eu, porta at, ultricies ut, neque. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; In tincidunt. Praesent ut orci id eros congue ultrices. Mauris non neque. Donec nulla ante, molestie sit amet, fermentum nec, blandit sit amet, purus. Fusce eget diam eu odio iaculis mollis. Phasellus consectetuer pede quis nisi. Proin non sem ut elit pulvinar faucibus. In a turpis nec augue fringilla elementum.\n\nNullam felis. Donec in nulla. Suspendisse sodales, turpis in suscipit ullamcorper, enim nunc sagittis risus, eu auctor velit tortor ut turpis. Mauris id augue at neque aliquam eleifend. Sed eget augue. Nunc faucibus ligula sed massa. Etiam non nulla. Etiam accumsan ullamcorper nisl. In pharetra massa at nunc. Nunc elementum. Duis sodales enim nec libero. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Praesent dapibus eros sodales urna. Duis magna nisi, lobortis quis, tincidunt rutrum, posuere non, ipsum.\n\nAliquam convallis neque vitae diam. In diam. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Duis fermentum arcu in tortor. Sed nibh leo, rhoncus eu, fermentum et, scelerisque ac, massa. Cras id turpis. Etiam commodo sem luctus lorem. Morbi at mi. In rutrum. Aenean luctus pede euismod tortor. Phasellus dictum. Cras neque justo, venenatis sit amet, tristique et, vulputate in, dui. Etiam sed mi gravida sapien imperdiet dictum. Aliquam gravida orci a tortor. Donec tempor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Vivamus risus ante, pellentesque vitae, luctus eget, scelerisque sed, libero. Donec massa.\n\nDonec libero mauris, volutpat at, convallis vel, laoreet euismod, augue. In accumsan malesuada risus. Mauris metus magna, condimentum in, nonummy non, ornare eu, velit. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Proin posuere. Proin rhoncus rutrum lorem. Phasellus dignissim massa non libero volutpat tincidunt. In hac habitasse platea dictumst. Phasellus eget eros. Nulla in nulla. Vivamus quis mauris. Maecenas pharetra rhoncus tellus. Sed sit amet lacus.\n\nQuisque interdum felis a tellus. Aliquam sed diam ac velit aliquam rutrum. Morbi commodo, risus a pulvinar adipiscing, tortor pede posuere risus, ac ornare tellus massa nec lectus. Vivamus mollis metus ac sapien. Nam sed est a libero ullamcorper dapibus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Aenean a erat ac nibh accumsan volutpat. Phasellus pulvinar consequat turpis. Curabitur ante metus, tempus ut, consequat eu, sollicitudin sit amet, justo. Duis ut libero.", $post->post_content);
		$this->assertEquals("Lorem Ipsum", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("lorem-ipsum", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-04 09:52:50", $post->post_modified);
		$this->assertEquals("2007-09-03 23:52:50", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/lorem-ipsum/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("page", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$this->assertEquals(array (
  0 => 'default',
), get_post_meta($post->ID, '_wp_page_template', false));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[31];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-04 09:52:18", $post->post_date);
		$this->assertEquals("2007-09-03 23:52:18", $post->post_date_gmt);
		$this->assertEquals("This page has a parent.", $post->post_content);
		$this->assertEquals("Child page 2", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("child-page-2", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-04 09:52:18", $post->post_modified);
		$this->assertEquals("2007-09-03 23:52:18", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals($this->posts[32]->ID, $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/parent-page/child-page-1/child-page-2/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("page", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$this->assertEquals(array (
  0 => 'default',
), get_post_meta($post->ID, '_wp_page_template', false));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[32];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-04 09:51:50", $post->post_date);
		$this->assertEquals("2007-09-03 23:51:50", $post->post_date_gmt);
		$this->assertEquals("This page has a parent and child.", $post->post_content);
		$this->assertEquals("Child page 1", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("child-page-1", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-04 09:51:50", $post->post_modified);
		$this->assertEquals("2007-09-03 23:51:50", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals($this->posts[33]->ID, $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/parent-page/child-page-1/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("page", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$this->assertEquals(array (
  0 => 'default',
), get_post_meta($post->ID, '_wp_page_template', false));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[33];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-04 09:51:09", $post->post_date);
		$this->assertEquals("2007-09-03 23:51:09", $post->post_date_gmt);
		$this->assertEquals("This page has children.", $post->post_content);
		$this->assertEquals("Parent page", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("parent-page", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-04 09:51:09", $post->post_modified);
		$this->assertEquals("2007-09-03 23:51:09", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/parent-page/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("page", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$this->assertEquals(array (
  0 => 'default',
), get_post_meta($post->ID, '_wp_page_template', false));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[34];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-04 09:48:39", $post->post_date);
		$this->assertEquals("2007-09-03 23:48:39", $post->post_date_gmt);
		$this->assertEquals("", $post->post_content);
		$this->assertEquals("", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("inherit", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("25", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-04 09:48:39", $post->post_modified);
		$this->assertEquals("2007-09-03 23:48:39", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals($this->posts[61]->ID, $post->post_parent);
		$this->assertEquals(wp_get_attachment_url($post->ID), $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("attachment", $post->post_type);
		$this->assertEquals("image/jpeg", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$this->assertEquals(array (
  0 => '/home/wpcom/public_html/wp-content/blogs.dir/8de/1641616/files/2007/09/2007-06-30-dsc_4700-1.jpg',
  1 => '/Users/alex/Documents/dev/wordpress-tests/wordpress/wp-content/uploads/2007/09/2007-06-30-dsc_4700-1.jpg',
), get_post_meta($post->ID, '_wp_attached_file', false));
		$this->assertEquals(array (
  0 => 's:580:"a:6:{s:5:"width";i:199;s:6:"height";i:300;s:14:"hwstring_small";s:22:"height=\'96\' width=\'63\'";s:4:"file";s:96:"/home/wpcom/public_html/wp-content/blogs.dir/8de/1641616/files/2007/09/2007-06-30-dsc_4700-1.jpg";s:5:"thumb";s:35:"2007-06-30-dsc_4700-1.thumbnail.jpg";s:10:"image_meta";a:10:{s:8:"aperture";i:0;s:6:"credit";s:0:"";s:6:"camera";s:9:"NIKON D70";s:7:"caption";s:0:"";s:17:"created_timestamp";i:1183237109;s:9:"copyright";s:0:"";s:12:"focal_length";i:0;s:3:"iso";i:200;s:13:"shutter_speed";d:0.0013333333333333332593184650249895639717578887939453125;s:5:"title";s:0:"";}}";',
  1 => 'a:6:{s:5:"width";i:199;s:6:"height";i:300;s:14:"hwstring_small";s:22:"height=\'96\' width=\'63\'";s:4:"file";s:104:"/Users/alex/Documents/dev/wordpress-tests/wordpress/wp-content/uploads/2007/09/2007-06-30-dsc_4700-1.jpg";s:5:"sizes";a:1:{s:9:"thumbnail";a:3:{s:4:"file";s:33:"2007-06-30-dsc_4700-1-150x150.jpg";s:5:"width";i:150;s:6:"height";i:150;}}s:10:"image_meta";a:10:{s:8:"aperture";i:0;s:6:"credit";s:0:"";s:6:"camera";s:9:"NIKON D70";s:7:"caption";s:0:"";s:17:"created_timestamp";i:1183201109;s:9:"copyright";s:0:"";s:12:"focal_length";i:0;s:3:"iso";i:200;s:13:"shutter_speed";d:0.00133333333333333328880876411659528457676060497760772705078125;s:5:"title";s:0:"";}}',
), get_post_meta($post->ID, '_wp_attachment_metadata', false));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[35];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-04 09:44:53", $post->post_date);
		$this->assertEquals("2007-09-03 23:44:53", $post->post_date_gmt);
		$this->assertEquals("Category permutations: C", $post->post_content);
		$this->assertEquals("Cat C", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("cat-c", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-04 09:44:53", $post->post_modified);
		$this->assertEquals("2007-09-03 23:44:53", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/09/04/cat-c/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Cat C', $cats[0]->name);
		$this->assertEquals('cat-c', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[36];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-04 09:44:14", $post->post_date);
		$this->assertEquals("2007-09-03 23:44:14", $post->post_date_gmt);
		$this->assertEquals("Category permutations: B", $post->post_content);
		$this->assertEquals("Cat B", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("cat-b", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-04 09:44:14", $post->post_modified);
		$this->assertEquals("2007-09-03 23:44:14", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/09/04/cat-b/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Cat B', $cats[0]->name);
		$this->assertEquals('cat-b', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[37];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-04 09:43:47", $post->post_date);
		$this->assertEquals("2007-09-03 23:43:47", $post->post_date_gmt);
		$this->assertEquals("Category permutations: A", $post->post_content);
		$this->assertEquals("Cat A", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("cat-a", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-04 09:43:47", $post->post_modified);
		$this->assertEquals("2007-09-03 23:43:47", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/09/04/cat-a/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Cat A', $cats[0]->name);
		$this->assertEquals('cat-a', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[38];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-04 09:43:14", $post->post_date);
		$this->assertEquals("2007-09-03 23:43:14", $post->post_date_gmt);
		$this->assertEquals("Category permutations: A, C.", $post->post_content);
		$this->assertEquals("Cats A and C", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("cats-a-and-c", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-04 09:43:14", $post->post_modified);
		$this->assertEquals("2007-09-03 23:43:14", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/09/04/cats-a-and-c/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(2, count($cats));
		$this->assertEquals('Cat A', $cats[0]->name);
		$this->assertEquals('cat-a', $cats[0]->slug);
		$this->assertEquals('Cat C', $cats[1]->name);
		$this->assertEquals('cat-c', $cats[1]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[39];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-04 09:42:29", $post->post_date);
		$this->assertEquals("2007-09-03 23:42:29", $post->post_date_gmt);
		$this->assertEquals("Category permutations: B, C.", $post->post_content);
		$this->assertEquals("Cats B and C", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("cats-b-and-c", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-04 09:42:29", $post->post_modified);
		$this->assertEquals("2007-09-03 23:42:29", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/09/04/cats-b-and-c/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(2, count($cats));
		$this->assertEquals('Cat B', $cats[0]->name);
		$this->assertEquals('cat-b', $cats[0]->slug);
		$this->assertEquals('Cat C', $cats[1]->name);
		$this->assertEquals('cat-c', $cats[1]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[40];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-04 09:41:51", $post->post_date);
		$this->assertEquals("2007-09-03 23:41:51", $post->post_date_gmt);
		$this->assertEquals("Category permutations: A, B.", $post->post_content);
		$this->assertEquals("Cats A and B", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("cats-a-and-b", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-04 09:41:51", $post->post_modified);
		$this->assertEquals("2007-09-03 23:41:51", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/09/04/cats-a-and-b/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(2, count($cats));
		$this->assertEquals('Cat A', $cats[0]->name);
		$this->assertEquals('cat-a', $cats[0]->slug);
		$this->assertEquals('Cat B', $cats[1]->name);
		$this->assertEquals('cat-b', $cats[1]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[41];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-04 09:41:17", $post->post_date);
		$this->assertEquals("2007-09-03 23:41:17", $post->post_date_gmt);
		$this->assertEquals("Category permutations: A, B, C.", $post->post_content);
		$this->assertEquals("Cats A, B, C", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("cats-a-b-c", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-04 09:41:17", $post->post_modified);
		$this->assertEquals("2007-09-03 23:41:17", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/09/04/cats-a-b-c/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(3, count($cats));
		$this->assertEquals('Cat A', $cats[0]->name);
		$this->assertEquals('cat-a', $cats[0]->slug);
		$this->assertEquals('Cat B', $cats[1]->name);
		$this->assertEquals('cat-b', $cats[1]->slug);
		$this->assertEquals('Cat C', $cats[2]->name);
		$this->assertEquals('cat-c', $cats[2]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[42];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-04 09:39:56", $post->post_date);
		$this->assertEquals("2007-09-03 23:39:56", $post->post_date_gmt);
		$this->assertEquals("", $post->post_content);
		$this->assertEquals("This post has no body", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("this-post-has-no-body", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-04 09:39:56", $post->post_modified);
		$this->assertEquals("2007-09-03 23:39:56", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/09/04/this-post-has-no-body/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[43];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-04 09:39:23", $post->post_date);
		$this->assertEquals("2007-09-03 23:39:23", $post->post_date_gmt);
		$this->assertEquals("This post has no title.", $post->post_content);
		$this->assertEquals("", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("14", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-04 09:39:23", $post->post_modified);
		$this->assertEquals("2007-09-03 23:39:23", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/09/04/14/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[44];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-04 09:38:54", $post->post_date);
		$this->assertEquals("2007-09-03 23:38:54", $post->post_date_gmt);
		$this->assertEquals("Here's the body.  There's an optional excerpt too.", $post->post_content);
		$this->assertEquals("Protected: Test with secret password and excerpt", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("test-with-secret-password-and-excerpt", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-04 09:38:54", $post->post_modified);
		$this->assertEquals("2007-09-03 23:38:54", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/09/04/test-with-secret-password-and-excerpt/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[45];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-04 09:38:05", $post->post_date);
		$this->assertEquals("2007-09-03 23:38:05", $post->post_date_gmt);
		$this->assertEquals("The password is secret.", $post->post_content);
		$this->assertEquals("Protected: Test with secret password", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("test-with-secret-password", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-04 09:38:05", $post->post_modified);
		$this->assertEquals("2007-09-03 23:38:05", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/09/04/test-with-secret-password/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[46];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-04 09:37:59", $post->post_date);
		$this->assertEquals("2007-09-03 23:37:59", $post->post_date_gmt);
		$this->assertEquals("Align right:\n\n<img SRC=\"http://example.com/wp-content/uploads/2007/09/2007-06-30-dsc_4711-300px.jpg\" ALT=\"2007-06-30-dsc_4711-300px.jpg\" ALIGN=\"right\" />\n\nAlign left:\n\n<img SRC=\"http://example.com/wp-content/uploads/2007/09/2007-06-30-dsc_4711-300px.jpg\" ALT=\"2007-06-30-dsc_4711-300px.jpg\" />", $post->post_content);
		$this->assertEquals("Image align test", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("image-align-test", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-04 09:37:59", $post->post_modified);
		$this->assertEquals("2007-09-03 23:37:59", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/09/04/image-align-test/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[47];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-04 09:36:28", $post->post_date);
		$this->assertEquals("2007-09-03 23:36:28", $post->post_date_gmt);
		$this->assertEquals("Three thumbs with links:\n\n<a TITLE=\"2007-06-30-dsc_4700-1.jpg\" HREF=\"http://example.com/wp-content/uploads/2007/09/2007-06-30-dsc_4700-1.jpg\"><img ALT=\"2007-06-30-dsc_4700-1.jpg\" SRC=\"http://example.com/wp-content/uploads/2007/09/2007-06-30-dsc_4700-1-150x150.jpg\" /></a><a TITLE=\"2007-06-30-dsc_4711-900px.jpg\" HREF=\"http://example.com/wp-content/uploads/2007/09/2007-06-30-dsc_4711-900px.jpg\"><img ALT=\"2007-06-30-dsc_4711-900px.jpg\" SRC=\"http://example.com/wp-content/uploads/2007/09/2007-06-30-dsc_4711-900px-150x150.jpg\" /></a><a TITLE=\"2007-06-30-dsc_4711-300px.jpg\" HREF=\"http://example.com/wp-content/uploads/2007/09/2007-06-30-dsc_4711-300px.jpg\"><img ALT=\"2007-06-30-dsc_4711-300px.jpg\" SRC=\"http://example.com/wp-content/uploads/2007/09/2007-06-30-dsc_4711-300px-150x150.jpg\" /></a>", $post->post_content);
		$this->assertEquals("Test with thumbnails", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("test-with-thumbnails", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-04 09:36:28", $post->post_modified);
		$this->assertEquals("2007-09-03 23:36:28", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/09/04/test-with-thumbnails/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[48];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-04 09:35:23", $post->post_date);
		$this->assertEquals("2007-09-03 23:35:23", $post->post_date_gmt);
		$this->assertEquals("Image is 900x598px, resized in editor to 329x222.\n\n<a href=\"http://asdftestblog1.wordpress.com/2007/09/04/test-with-wide-image/9/\" title=\"2007-06-30-dsc_4711-900px.jpg\" rel=\"attachment wp-att-9\"><img src=\"http://example.com/wp-content/uploads/2007/09/2007-06-30-dsc_4711-900px.jpg\" alt=\"2007-06-30-dsc_4711-900px.jpg\" height=\"222\" width=\"329\" /></a>", $post->post_content);
		$this->assertEquals("Test with wide image resized", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("test-with-wide-image-resized", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-04 09:35:23", $post->post_modified);
		$this->assertEquals("2007-09-03 23:35:23", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/09/04/test-with-wide-image-resized/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[49];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-04 09:31:31", $post->post_date);
		$this->assertEquals("2007-09-03 23:31:31", $post->post_date_gmt);
		$this->assertEquals("Image is 900x598px, not resized in editor.\n\n<a TITLE=\"2007-06-30-dsc_4711-900px.jpg\" REL=\"attachment wp-att-9\" HREF=\"http://asdftestblog1.wordpress.com/?attachment_id=9\"><img ALT=\"2007-06-30-dsc_4711-900px.jpg\" SRC=\"http://example.com/wp-content/uploads/2007/09/2007-06-30-dsc_4711-900px.jpg\" /></a>", $post->post_content);
		$this->assertEquals("Test with wide image", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("test-with-wide-image", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-04 09:31:31", $post->post_modified);
		$this->assertEquals("2007-09-03 23:31:31", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/09/04/test-with-wide-image/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[50];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-04 09:31:12", $post->post_date);
		$this->assertEquals("2007-09-03 23:31:12", $post->post_date_gmt);
		$this->assertEquals("", $post->post_content);
		$this->assertEquals("", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("inherit", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("9", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-04 09:31:12", $post->post_modified);
		$this->assertEquals("2007-09-03 23:31:12", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals($this->posts[49]->ID, $post->post_parent);
		$this->assertEquals(wp_get_attachment_url($post->ID), $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("attachment", $post->post_type);
		$this->assertEquals("image/jpeg", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$this->assertEquals(array (
  0 => '/home/wpcom/public_html/wp-content/blogs.dir/8de/1641616/files/2007/09/2007-06-30-dsc_4711-900px.jpg',
  1 => '/Users/alex/Documents/dev/wordpress-tests/wordpress/wp-content/uploads/2007/09/2007-06-30-dsc_4711-900px.jpg',
), get_post_meta($post->ID, '_wp_attached_file', false));
		$this->assertEquals(array (
  0 => 's:590:"a:6:{s:5:"width";i:900;s:6:"height";i:598;s:14:"hwstring_small";s:23:"height=\'85\' width=\'128\'";s:4:"file";s:100:"/home/wpcom/public_html/wp-content/blogs.dir/8de/1641616/files/2007/09/2007-06-30-dsc_4711-900px.jpg";s:5:"thumb";s:39:"2007-06-30-dsc_4711-900px.thumbnail.jpg";s:10:"image_meta";a:10:{s:8:"aperture";i:0;s:6:"credit";s:0:"";s:6:"camera";s:9:"NIKON D70";s:7:"caption";s:0:"";s:17:"created_timestamp";i:1183237219;s:9:"copyright";s:0:"";s:12:"focal_length";i:0;s:3:"iso";i:200;s:13:"shutter_speed";d:0.0013333333333333332593184650249895639717578887939453125;s:5:"title";s:0:"";}}";',
  1 => 'a:6:{s:5:"width";i:900;s:6:"height";i:598;s:14:"hwstring_small";s:23:"height=\'85\' width=\'128\'";s:4:"file";s:108:"/Users/alex/Documents/dev/wordpress-tests/wordpress/wp-content/uploads/2007/09/2007-06-30-dsc_4711-900px.jpg";s:5:"sizes";a:2:{s:9:"thumbnail";a:3:{s:4:"file";s:37:"2007-06-30-dsc_4711-900px-150x150.jpg";s:5:"width";i:150;s:6:"height";i:150;}s:6:"medium";a:3:{s:4:"file";s:37:"2007-06-30-dsc_4711-900px-300x199.jpg";s:5:"width";i:300;s:6:"height";i:199;}}s:10:"image_meta";a:10:{s:8:"aperture";i:0;s:6:"credit";s:0:"";s:6:"camera";s:9:"NIKON D70";s:7:"caption";s:0:"";s:17:"created_timestamp";i:1183201219;s:9:"copyright";s:0:"";s:12:"focal_length";i:0;s:3:"iso";i:200;s:13:"shutter_speed";d:0.00133333333333333328880876411659528457676060497760772705078125;s:5:"title";s:0:"";}}',
), get_post_meta($post->ID, '_wp_attachment_metadata', false));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[51];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-04 09:30:02", $post->post_date);
		$this->assertEquals("2007-09-03 23:30:02", $post->post_date_gmt);
		$this->assertEquals("Image is 300x199, not resized in editor.\n\n<a TITLE=\"2007-06-30-dsc_4711-300px.jpg\" REL=\"attachment wp-att-7\" HREF=\"http://asdftestblog1.wordpress.com/?attachment_id=7\"><img ALT=\"2007-06-30-dsc_4711-300px.jpg\" SRC=\"http://example.com/wp-content/uploads/2007/09/2007-06-30-dsc_4711-300px.jpg\" /></a>", $post->post_content);
		$this->assertEquals("Test with image", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("test-with-image", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-04 09:30:02", $post->post_modified);
		$this->assertEquals("2007-09-03 23:30:02", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/09/04/test-with-image/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[52];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-04 09:29:34", $post->post_date);
		$this->assertEquals("2007-09-03 23:29:34", $post->post_date_gmt);
		$this->assertEquals("", $post->post_content);
		$this->assertEquals("", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("inherit", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("7", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-04 09:29:34", $post->post_modified);
		$this->assertEquals("2007-09-03 23:29:34", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals($this->posts[51]->ID, $post->post_parent);
		$this->assertEquals(wp_get_attachment_url($post->ID), $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("attachment", $post->post_type);
		$this->assertEquals("image/jpeg", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$this->assertEquals(array (
  0 => '/home/wpcom/public_html/wp-content/blogs.dir/8de/1641616/files/2007/09/2007-06-30-dsc_4711-300px.jpg',
  1 => '/Users/alex/Documents/dev/wordpress-tests/wordpress/wp-content/uploads/2007/09/2007-06-30-dsc_4711-300px.jpg',
), get_post_meta($post->ID, '_wp_attached_file', false));
		$this->assertEquals(array (
  0 => 's:590:"a:6:{s:5:"width";i:300;s:6:"height";i:199;s:14:"hwstring_small";s:23:"height=\'84\' width=\'128\'";s:4:"file";s:100:"/home/wpcom/public_html/wp-content/blogs.dir/8de/1641616/files/2007/09/2007-06-30-dsc_4711-300px.jpg";s:5:"thumb";s:39:"2007-06-30-dsc_4711-300px.thumbnail.jpg";s:10:"image_meta";a:10:{s:8:"aperture";i:0;s:6:"credit";s:0:"";s:6:"camera";s:9:"NIKON D70";s:7:"caption";s:0:"";s:17:"created_timestamp";i:1183237219;s:9:"copyright";s:0:"";s:12:"focal_length";i:0;s:3:"iso";i:200;s:13:"shutter_speed";d:0.0013333333333333332593184650249895639717578887939453125;s:5:"title";s:0:"";}}";',
  1 => 'a:6:{s:5:"width";i:300;s:6:"height";i:199;s:14:"hwstring_small";s:23:"height=\'84\' width=\'128\'";s:4:"file";s:108:"/Users/alex/Documents/dev/wordpress-tests/wordpress/wp-content/uploads/2007/09/2007-06-30-dsc_4711-300px.jpg";s:5:"sizes";a:1:{s:9:"thumbnail";a:3:{s:4:"file";s:37:"2007-06-30-dsc_4711-300px-150x150.jpg";s:5:"width";i:150;s:6:"height";i:150;}}s:10:"image_meta";a:10:{s:8:"aperture";i:0;s:6:"credit";s:0:"";s:6:"camera";s:9:"NIKON D70";s:7:"caption";s:0:"";s:17:"created_timestamp";i:1183201219;s:9:"copyright";s:0:"";s:12:"focal_length";i:0;s:3:"iso";i:200;s:13:"shutter_speed";d:0.00133333333333333328880876411659528457676060497760772705078125;s:5:"title";s:0:"";}}',
), get_post_meta($post->ID, '_wp_attachment_metadata', false));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[53];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-04 09:22:14", $post->post_date);
		$this->assertEquals("2007-09-03 23:22:14", $post->post_date_gmt);
		$this->assertEquals("Parent, Child 1 and Child2.", $post->post_content);
		$this->assertEquals("Post with categories", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("post-with-categories", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-04 09:22:14", $post->post_modified);
		$this->assertEquals("2007-09-03 23:22:14", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/09/04/post-with-categories/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(3, count($cats));
		$this->assertEquals('Child 1', $cats[0]->name);
		$this->assertEquals('child-1', $cats[0]->slug);
		$this->assertEquals('Child 2', $cats[1]->name);
		$this->assertEquals('child-2', $cats[1]->slug);
		$this->assertEquals('Parent', $cats[2]->name);
		$this->assertEquals('parent', $cats[2]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[54];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-04 09:18:44", $post->post_date);
		$this->assertEquals("2007-09-03 23:18:44", $post->post_date_gmt);
		$this->assertEquals("Timezone offset is +10, timestamp is 04 Sep 2007 @ 9:18am", $post->post_content);
		$this->assertEquals("GMT+10 test post", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("gmt10-test-post", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-04 09:18:44", $post->post_modified);
		$this->assertEquals("2007-09-03 23:18:44", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/09/04/gmt10-test-post/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[55];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-03 23:15:10", $post->post_date);
		$this->assertEquals("2007-09-03 23:15:10", $post->post_date_gmt);
		$this->assertEquals("TZ offset is 0, post time is 03 Sep 2007 @ 11.14pm.", $post->post_content);
		$this->assertEquals("GMT test post", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("gmt-test-post", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-03 23:15:10", $post->post_modified);
		$this->assertEquals("2007-09-03 23:15:10", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/09/03/gmt-test-post/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[56];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-03 23:08:37", $post->post_date);
		$this->assertEquals("2007-09-03 23:08:37", $post->post_date_gmt);
		$this->assertEquals("This is an example of a WordPress page, you could edit this to put information about yourself or your site so readers know where you are coming from. You can create as many pages like this one or sub-pages as you like and manage all of your content inside of WordPress.\n\nMinor edit.\n\nMinor edit 2.", $post->post_content);
		$this->assertEquals("About", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("about", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-03 23:08:37", $post->post_modified);
		$this->assertEquals("2007-09-03 23:08:37", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://example.com/?page_id={$post->ID}", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("page", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$this->assertEquals(array (
  0 => 'default',
), get_post_meta($post->ID, '_wp_page_template', false));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[57];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-03 23:08:37", $post->post_date);
		$this->assertEquals("2007-09-03 23:08:37", $post->post_date_gmt);
		$this->assertEquals("Welcome to <a href=\"http://wordpress.com/\">WordPress.com</a>. This is your first post. Edit or delete it and start blogging!", $post->post_content);
		$this->assertEquals("Hello world!", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("hello-world", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-03 23:08:37", $post->post_modified);
		$this->assertEquals("2007-09-03 23:08:37", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://example.com/?p={$post->ID}", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("1", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(1, count($comments));
		$this->assertEquals('Mr WordPress', $comments[0]->comment_author);
		$this->assertEquals('', $comments[0]->comment_author_email);
		$this->assertEquals('http://wordpress.com/', $comments[0]->comment_author_url);
		$this->assertEquals('127.0.0.1', $comments[0]->comment_author_IP);
		$this->assertEquals('2007-09-03 23:08:37', $comments[0]->comment_date);
		$this->assertEquals('2007-09-03 23:08:37', $comments[0]->comment_date_gmt);
		$this->assertEquals('0', $comments[0]->comment_karma);
		$this->assertEquals('1', $comments[0]->comment_approved);
		$this->assertEquals('', $comments[0]->comment_agent);
		$this->assertEquals('', $comments[0]->comment_type);
		$this->assertEquals('0', $comments[0]->comment_parent);
		$this->assertEquals('', $comments[0]->comment_user_id);


		$post = $this->posts[58];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("2007-09-03 17:17:01", $post->post_date);
		$this->assertEquals("2007-09-03 23:17:01", $post->post_date_gmt);
		$this->assertEquals("Timezone offset is -6, time is 03 Sep 2007 @ 5.16pm", $post->post_content);
		$this->assertEquals("GMT-6 test post", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("gmt-6-test-post", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("2007-09-03 17:17:01", $post->post_modified);
		$this->assertEquals("2007-09-03 23:17:01", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/2007/09/03/gmt-6-test-post/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[59];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("1982-01-01 11:11:15", $post->post_date);
		$this->assertEquals("1982-01-01 01:11:15", $post->post_date_gmt);
		$this->assertEquals("", $post->post_content);
		$this->assertEquals("Article in the distant past", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("publish", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("article-in-the-distant-past", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
		$this->assertEquals("1982-01-01 11:11:15", $post->post_modified);
		$this->assertEquals("1982-01-01 01:11:15", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/1982/01/01/article-in-the-distant-past/", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[60];
		$this->assertEquals(get_profile('ID', 'User B'), $post->post_author);
		$this->assertEquals("0000-00-00 00:00:00", $post->post_date);
		$this->assertEquals("0000-00-00 00:00:00", $post->post_date_gmt);
		$this->assertEquals("This post is awaiting review.", $post->post_content);
		$this->assertEquals("Contributor post, pending approval", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("pending", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("contributor-post-pending-approval", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
#		$this->assertEquals("2008-01-30 16:35:51", $post->post_modified);
#		$this->assertEquals("2008-01-30 05:35:51", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/?p=36", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[61];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("0000-00-00 00:00:00", $post->post_date);
		$this->assertEquals("0000-00-00 00:00:00", $post->post_date_gmt);
		$this->assertEquals("Image file attached, 300px high.\n\n<a TITLE=\"2007-06-30-dsc_4700-1.jpg\" REL=\"attachment wp-att-25\" HREF=\"http://asdftestblog1.wordpress.com/?attachment_id=25\"><img ALT=\"2007-06-30-dsc_4700-1.jpg\" SRC=\"http://example.com/wp-content/uploads/2007/09/2007-06-30-dsc_4700-1.jpg\" /></a>", $post->post_content);
		$this->assertEquals("Draft post with file attached", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("draft", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
#		$this->assertEquals("2008-01-30 16:35:51", $post->post_modified);
#		$this->assertEquals("2008-01-30 05:35:51", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/?p=24", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));


		$post = $this->posts[62];
		$this->assertEquals(get_profile('ID', 'User A'), $post->post_author);
		$this->assertEquals("0000-00-00 00:00:00", $post->post_date);
		$this->assertEquals("0000-00-00 00:00:00", $post->post_date_gmt);
		$this->assertEquals("Just a draft post.", $post->post_content);
		$this->assertEquals("Draft post", $post->post_title);
		$this->assertEquals("0", $post->post_category);
		$this->assertEquals("", $post->post_excerpt);
		$this->assertEquals("draft", $post->post_status);
		$this->assertEquals("open", $post->comment_status);
		$this->assertEquals("open", $post->ping_status);
		$this->assertEquals("", $post->post_password);
		$this->assertEquals("", $post->post_name);
		$this->assertEquals("", $post->to_ping);
		$this->assertEquals("", $post->pinged);
#		$this->assertEquals("2008-01-30 16:35:51", $post->post_modified);
#		$this->assertEquals("2008-01-30 05:35:51", $post->post_modified_gmt);
		$this->assertEquals("", $post->post_content_filtered);
		$this->assertEquals("0", $post->post_parent);
		$this->assertEquals("http://asdftestblog1.wordpress.com/?p=23", $post->guid);
		$this->assertEquals("0", $post->menu_order);
		$this->assertEquals("post", $post->post_type);
		$this->assertEquals("", $post->post_mime_type);
		$this->assertEquals("0", $post->comment_count);
		$cats = wp_get_post_categories($post->ID, array("fields"=>"all"));
		$this->assertEquals(1, count($cats));
		$this->assertEquals('Uncategorized', $cats[0]->name);
		$this->assertEquals('uncategorized', $cats[0]->slug);
		$tags = wp_get_post_tags($post->ID);
		$this->assertEquals(0, count($tags));
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d ORDER BY comment_date DESC", $post->ID));
		$this->assertEquals(0, count($comments));

	}


}

class TestImportWP_PostMeta extends _WPEmptyBlog {

	var $posts = NULL;

	function setUp() {
		parent::setUp();
		include_once(ABSPATH.'/wp-admin/import/wordpress.php');
		if ( !defined('WP_IMPORTING') )
			define('WP_IMPORTING', true);
	}

	function tearDown() {
		parent::tearDown();
		if ($id = get_profile('ID', 'User A'))
			wp_delete_user($id);
	}
	
	function test_serialized_postmeta_no_cdata() {
		$this->knownWPBug(10619);
		$html_output = get_echo( array(&$this, '_import_wp'), array( DIR_TESTDATA.'/export/test-serialized-postmeta-no-cdata.xml', array('User A') ) );
		$expected["special_post_title"] = "A special title";
		$expected["is_calendar"]= "";
		$this->assertEquals($expected, get_post_meta(122, 'post-options', true));
	}
}

class TestImportWP_PostMetaCDATA extends _WPEmptyBlog {

	var $posts = NULL;

	function setUp() {
		parent::setUp();
		include_once(ABSPATH.'/wp-admin/import/wordpress.php');
		if ( !defined('WP_IMPORTING') )
			define('WP_IMPORTING', true);
	}

	function tearDown() {
		parent::tearDown();
		if ($id = get_profile('ID', 'User A'))
			wp_delete_user($id);
	}

	//Relates to WP Bug 9633 - this tests the importer against the fix not the exporter
	function test_serialized_postmeta_with_cdata() {
		$html_output = get_echo( array(&$this, '_import_wp'), array( DIR_TESTDATA.'/export/test-serialized-postmeta-with-cdata.xml', array('User A') ) );

		//HTML in the CDATA should work with old WordPress versions
		$this->assertEquals('<pre>some html</pre>', get_post_meta(10, 'contains-html', true));

		//Serialised will only work with 3.0 onwards.
		$expected["special_post_title"] = "A special title";
		$expected["is_calendar"]= "";
		$this->assertEquals($expected, get_post_meta(10, 'post-options', true));
		
	}

	function test_serialized_postmeta_with_evil_stuff_in_cdata() {
		$this->knownWPBug(11574);
		$html_output = get_echo( array(&$this, '_import_wp'), array( DIR_TESTDATA.'/export/test-serialized-postmeta-with-cdata.xml', array('User A') ) );

		//Evil content in the CDATA
		//<wp:meta_value>evil</wp:meta_value>
		$this->assertEquals('<wp:meta_value>evil</wp:meta_value>', get_post_meta(10, 'evil', true));
	}

}
?>
