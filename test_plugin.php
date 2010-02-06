<?php

class TestPlugin extends WPTestCase {
  function test_callable() {
    $this->assertEquals("true", "true");
  }
}

?>