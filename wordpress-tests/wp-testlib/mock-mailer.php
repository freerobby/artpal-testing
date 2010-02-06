<?php

require_once(ABSPATH.'/wp-includes/class-phpmailer.php');

class MockPHPMailer extends PHPMailer {

	var $mock_sent = array();

	// override the Send function so it doesn't actually send anything
    function Send() {
        $header = "";
        $body = "";
        $result = true;

        if((count($this->to) + count($this->cc) + count($this->bcc)) < 1)
        {
            $this->SetError($this->Lang("provide_address"));
            return false;
        }

        // Set whether the message is multipart/alternative
        if(!empty($this->AltBody))
            $this->ContentType = "multipart/alternative";

        $this->error_count = 0; // reset errors
        $this->SetMessageType();
        $header .= $this->CreateHeader();
        $body = $this->CreateBody();

        if($body == "") { return false; }

        $this->mock_sent[] = array(
            'to' => $this->to,
            'cc' => $this->cc,
            'bcc' => $this->bcc,
            'header' => $header,
            'body' => $body,
        );

        return $result;
    }

}


?>
