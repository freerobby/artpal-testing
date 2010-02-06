The short version:

1. Create a clean MySQL database and user.  DO NOT USE AN EXISTING DATABASE or you will lose data, guaranteed.

2. Copy wp-config-sample.php to wp-config.php, edit it and include your database name/user/password.

3. $ svn up

4. $ php wp-test.php

Notes:

Test cases live in the 'wp-testcase' subdirectory.  All files in that directory will be included by default.  Extend the WPTestCase class to ensure your test is run.

wp-test.php will initialize and install a (more or less) complete running copy of Wordpress each time it is run.  This makes it possible to run functional interface and module tests against a fully working database and codebase, as opposed to pure unit tests with mock objects and stubs.  Pure unit tests may be used also, of course.

The test database will be wiped clean with DROP TABLE statements once tests are finished, to ensure a clean start next time the tests are run.

wp-test.php is intended to run at the command line, not via a web server.

The wordpress and wordpress-mu trunks are included as svn externals.  By default wp-test.php will run tests against the wordpress branch.  To run the same tests against the wordpress-mu branch instead:

	$ php wp-test.php -v mu
