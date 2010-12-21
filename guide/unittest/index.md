# Unittest

Unittest is a module that provides unittesting for Kohana using [PHPUnit](http://www.phpunit.de/).

### Running from the command line

	$ phpunit --bootstrap=index.php modules/unittest/tests.php

Of course, you'll need to make sure the path to the tests.php file is correct.  If you want you can copy it to a more accessible location.

### Running from the web

Just navigate to `http://example.com/unittest`. You may need to use `http://example.com/index.php/unittest` if you have not enabled url rewriting in your `.htaccess`.
