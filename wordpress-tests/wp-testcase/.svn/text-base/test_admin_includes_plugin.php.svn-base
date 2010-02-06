<?php
class TestPluginData extends WPTestCase {

	function test_get_plugin_data() {
		$data = get_plugin_data(DIR_TESTDATA . '/plugins/hello.php');

		$default_headers = array(	'Name' => 'Hello Dolly', 
									'Title' => '<a href="http://wordpress.org/#" title="Visit plugin homepage">Hello Dolly</a>', 
									'PluginURI' => 'http://wordpress.org/#', 
									'Description' => 'This is not just a plugin, it symbolizes the hope and enthusiasm of an entire generation summed up in two words sung most famously by Louis Armstrong: Hello, Dolly. When activated you will randomly see a lyric from Hello, Dolly in the upper right of your admin screen on every page. By <a href="http://ma.tt/" title="Visit author homepage">Matt Mullenweg</a>.',
									'Author' => '<a href="http://ma.tt/" title="Visit author homepage">Matt Mullenweg</a>',
									'AuthorURI' => 'http://ma.tt/',
									'Version' => '1.5.1', 
									'TextDomain' => 'hello-dolly',
									'DomainPath' => '' 
								); 
		
		$this->assertTrue(is_array($data));
		
		foreach ($default_headers as $name => $value) {
			$this->assertTrue(isset($data[$name]));
			$this->assertEquals($value, $data[$name]);
		}
	}
}
?>