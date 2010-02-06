<?php

require_once 'artpal/artpal.php';

class TestPlugin extends WPTestCase {
  /*
    Verify that we have successfully defined this function in PHP4.
  */
  function test_function_htmlspecialchars_decode() {
    $this->assertEquals(htmlspecialchars_decode("This&gt;that"), "This>that");
  }
  
  /*
    We've defined all our constants.
  */
  function test_constants_defined() {
    $constants = array(
      ds_ap_CFPRICE,
      ds_ap_CFSHIPPING,
      ds_ap_TAGINSERT,
      ds_ap_TAGIPN
    );
    foreach($constants as $constant) {
      echo $constant;
      $this->assertTrue($constant != null);
      $this->assertTrue($constant != "");
    }
  }
}

?>