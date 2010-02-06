<?php

require_once 'artpal/artpal.php';

class TestPlugin extends WPTestCase {
  /*
    Verify that we have successfully defined this function in PHP4.
  */
  function test_function_htmlspecialchars_decode() {
    $this->assertEquals(htmlspecialchars_decode("This&gt;that"), "This>that");
  }
}

?>