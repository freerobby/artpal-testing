<?php

class TestAuthFunctions extends WPTestCase {
	
	function test_auth_cookie_valid() {
		$cookie = wp_generate_auth_cookie(1, time() + 3600, 'auth');
		$this->assertEquals( 1, wp_validate_auth_cookie($cookie, 'auth') );
	}

	function test_auth_cookie_invalid() {
		// expired
		$cookie = wp_generate_auth_cookie(1, time() - 3600, 'auth');
		$this->assertEquals( false, wp_validate_auth_cookie($cookie, 'auth') );

		// wrong auth scheme
		$cookie = wp_generate_auth_cookie(1, time() + 3600, 'auth');
		$this->assertEquals( false, wp_validate_auth_cookie($cookie, 'logged_in') );

		// altered
		$cookie = wp_generate_auth_cookie(1, time() + 3600, 'auth');
		list($a, $b, $c) = explode('|', $cookie);
		$cookie = $a . '|' . ($b + 1) . '|' . $c;
		$this->assertEquals( false, wp_validate_auth_cookie($cookie, 'auth') );
	}
	
	function test_auth_cookie_scheme() {
		// arbitrary scheme name
		$cookie = wp_generate_auth_cookie(1, time() + 3600, 'foo');
		$this->assertEquals( 1, wp_validate_auth_cookie($cookie, 'foo') );

		// wrong scheme name - should fail
		$cookie = wp_generate_auth_cookie(1, time() + 3600, 'foo');
		$this->assertEquals( false, wp_validate_auth_cookie($cookie, 'bar') );
		
	}

}

class TestMailFunctions extends WPTestCase {
	
	function test_wp_mail_custom_boundaries() {
		$to = 'user@example.com';
		$subject = 'Test email with custom boundaries';
		$headers = '
MIME-Version: 1.0
Content-Type: multipart/mixed; boundary="----=_Part_4892_25692638.1192452070893"
';
		$body = '
------=_Part_4892_25692638.1192452070893
Content-Type: text/plain; charset=ISO-8859-1
Content-Transfer-Encoding: 7bit
Content-Disposition: inline

Here is a message with an attachment of a binary file.

------=_Part_4892_25692638.1192452070893
Content-Type: image/x-icon; name=favicon.ico
Content-Transfer-Encoding: base64
Content-Disposition: attachment; filename=favicon.ico

AAABAAEAEBAAAAAAAABoBQAAFgAAACgAAAAQAAAAIAAAAAEACAAAAAAAAAAAAAAAAAAAAAAAAAAA
AAAAAAAAAAAAAACAAACAAAAAgIAAgAAAAIAAgACAgAAAwMDAAICAgAAAAP8AAP8AAAD//wD/AAAA
/wD/AP//AAD///8A//3/AP39/wD6/f8A+P3/AP/8/wD9/P8A+vz/AP/7/wD/+v8A/vr/APz6/wD4
+v8A+/n/APP5/wD/+P8A+vj/AO/4/wDm+P8A2fj/AP/3/wD/9v8A9vb/AP/1/wD69f8A9PT/AO30
/wD/8/8A//L/APnx/wD28P8A///+APj//gD2//4A9P/+AOP//gD//f4A6f/9AP///AD2//wA8//8
APf9/AD///sA/v/7AOD/+wD/+vsA9/X7APr/+gDv/voA///5AP/9+QD/+/kA+e35AP//+ADm//gA
4f/4AP/9+AD0+/gA///3APv/9wDz//cA8f/3AO3/9wD/8fcA//32AP369gDr+vYA8f/1AOv/9QD/
+/UA///0APP/9ADq//QA///zAP/18wD///IA/fzyAP//8QD///AA9//wAPjw8AD//+8A8//vAP//
7gD9/+4A9v/uAP/u7gD//+0A9v/tAP7/6wD/+eoA///pAP//6AD2/+gA//nnAP/45wD38eYA/fbl
AP/25AD29uQA7N/hAPzm4AD/690AEhjdAAAa3AAaJdsA//LXAC8g1gANH9YA+dnTAP/n0gDh5dIA
DyjSABkk0gAdH9EABxDRAP/l0AAAJs4AGRTOAPPczQAAKs0AIi7MAA4UywD56soA8tPKANTSygD/
18kA6NLHAAAjxwDj28QA/s7CAP/1wQDw3r8A/9e8APrSrwDCtqoAzamjANmPiQDQj4YA35mBAOme
fgDHj3wA1qR6AO+sbwDpmm8A2IVlAKmEYgCvaFoAvHNXAEq2VgA5s1UAPbhQAFWtTwBStU0ARbNN
AEGxTQA7tEwAObZIAEq5RwDKdEYAULhDANtuQgBEtTwA1ls3ALhgMQCxNzEA2FsvAEC3LQB0MCkA
iyYoANZTJwDLWyYAtjMlALE6JACZNSMAuW4iANlgIgDoWCEAylwgAMUuIAD3Vh8A52gdALRCHQCx
WhwAsEkcALU4HACMOBwA0V4bAMYyGgCPJRoA218ZAJM7FwC/PxYA0msVAM9jFQD2XBUAqioVAIAf
FQDhYRQAujMTAMUxEwCgLBMAnxIPAMsqDgCkFgsA6GMHALE2BAC9JQAAliIAAFYTAAAAAAAAAAAA
AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAD/
//8AsbGxsbGxsbGxsbGxsbGxd7IrMg8PDw8PDw8PUBQeJXjQYE9PcKPM2NfP2sWhcg+BzTE7dLjb
mG03YWaV4JYye8MPbsLZlEouKRRCg9SXMoW/U53enGRAFzCRtNO7mTiAyliw30gRTg9VbJCKfYs0
j9VmuscfLTFbIy8SOhA0Inq5Y77GNBMYIxQUJzM2Vxx2wEmfyCYWMRldXCg5MU0aicRUms58SUVe
RkwjPBRSNIfBMkSgvWkyPxVHFIaMSx1/0S9nkq7WdWo1a43Jt2UqgtJERGJ5m6K8y92znpNWIYS1
UQ89Mmg5cXNaX0EkGyyI3KSsp6mvpaqosaatq7axsQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=
------=_Part_4892_25692638.1192452070893--
';
		
		unset($GLOBALS['phpmailer']->mock_sent);
		wp_mail($to, $subject, $body, $headers);
		
		// We need some better assertions here but these catch the failure for now.
		$this->assertEquals($body, $GLOBALS['phpmailer']->mock_sent[0]['body']);
		$this->assertTrue(strpos($GLOBALS['phpmailer']->mock_sent[0]['header'], 'boundary="----=_Part_4892_25692638.1192452070893"') > 0);
		$this->assertTrue(strpos($GLOBALS['phpmailer']->mock_sent[0]['header'], 'charset=""') > 0);
	}
	
}

class TestRedirectFunctions extends WPTestCase {
	function test_wp_sanitize_redirect() {
		$this->assertEquals('http://example.com/watchthelinefeedgo', wp_sanitize_redirect('http://example.com/watchthelinefeed%0Ago'));
		$this->assertEquals('http://example.com/watchthelinefeedgo', wp_sanitize_redirect('http://example.com/watchthelinefeed%0ago'));
		$this->assertEquals('http://example.com/watchthecarriagereturngo', wp_sanitize_redirect('http://example.com/watchthecarriagereturn%0Dgo'));
		$this->assertEquals('http://example.com/watchthecarriagereturngo', wp_sanitize_redirect('http://example.com/watchthecarriagereturn%0dgo'));
		//Nesting checks
		$this->assertEquals('http://example.com/watchthecarriagereturngo', wp_sanitize_redirect('http://example.com/watchthecarriagereturn%0%0ddgo'));
		$this->assertEquals('http://example.com/watchthecarriagereturngo', wp_sanitize_redirect('http://example.com/watchthecarriagereturn%0%0DDgo'));
	}
	
}
?>