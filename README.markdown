# Kohana-PHPUnit integration

This module integrates PHPUnit with Kohana.  It's kinda obvious, but I'll say it anyway -


*_Tests written for kohana-unittest are NOT compatible with this module, hence using the phpunit_tests folder and not tests_*


## Installation

Step 1: Enable this module in your bootstrap file

	/**
	 * Enable modules. Modules are referenced by a relative or absolute path.
	 */
	Kohana::modules(array(
		'phpunit'	=> MODPATH.'phpunit'	 // PHPUnit integration
		// 'database'   => MODPATH.'database',   // Database access
		// 'image'      => MODPATH.'image',      // Image manipulation
		// 'kodoc'      => MODPATH.'kodoc',      // Kohana documentation
		// 'orm'        => MODPATH.'orm',        // Object Relationship Mapping (not complete)
		// 'pagination' => MODPATH.'pagination', // Paging of results
		// 'paypal'     => MODPATH.'paypal',     // PayPal integration (not complete)
		// 'todoist'    => MODPATH.'todoist',    // Todoist integration
		// 'unittest'   => MODPATH.'unittest',   // Unit testing
		// 'codebench'  => MODPATH.'codebench',  // Benchmarking tool
		));
	

Step 2: in your app's bootstrap file modify the lines where the request is handled, which by default looks like:

	/**
	 * Execute the main request using PATH_INFO. If no URI source is specified,
	 * the URI will be automatically detected.
	 */
	echo Request::instance($_SERVER['PATH_INFO'])
        	->execute()
        	->send_headers()
	        ->response;

to read:

	if( ! defined('SUPPRESS_REQUEST'))
	{
        	/**
         	* Execute the main request using PATH_INFO. If no URI source is specified,
	         * the URI will be automatically detected.
	         */
	        echo Request::instance($_SERVER['PATH_INFO'])
	                ->execute()
	                ->send_headers()
        	        ->response;
	}

Step 3: Start testing!

	$ phpunit --bootstrap=index.php modules/phpunit/tests.php

Of course, you'll need to make sure the path to the tests.php file is correct.  If you want you can copy it to a more accessible location

If you want to run tests frm within an IDE then you need to include the kohana files within the test itself:

	<?php

	require_once 'path/to/kohanas/index.php';

	Class HalfEmptyTestCase extends PHPUnit_Framework_TestCase
	{
		// ...
	}


## Writing tests

If you're writing a test for your application, place it in "application/phpunit_tests".  Similarly, if you're writing a test for a module place it in modules/[modulefolder]/phpunit_tests

*Note:* The 'phpunit_tests' folder is used to avoid conflict with kohana-unittest, however if you want to use the tests folder instead then feel free to change tests.php to reflect this.

Rather than tell you how to write tests I'll point you in the direction of the [PHPUnit Manual](http://www.phpunit.de/manual/3.4/en/index.html) and tell you about a few of the awesome features in PHPUnit.

### Data Providers

Sometimes you want to be able to run a specific test with different sets of data to try and test every eventuality

Ordinarily you could use a foreach loop to iterate over an array of test data, however PHPUnit already can take care of this for us rather easily using "Data Providers".  A data provider is a function that returns an array of arguments that can be passed to a test.

	<?php

	Class ReallyCoolTest extends PHPUnit_Framework_TestCase
	{
		function providerStrLen()
		{
			return array(
				array('One set of testcase data', 24),
				array('This is a different one', 23),
			);
		}

		/**
		 * @dataProvider providerStrLen
		 */
		function testStrLen($string, $length)
		{
			$this->assertSame(
				$length,
				strlen($string)
			);
		}
	}

The key thing to notice is the `@dataProvider` tag in the doccomment, this is what tells PHPUnit to use a data provider.  The provider prefix is totally optional but it's a nice standard to identify providers.

For more info see:

* [Data Providers in PHPUnit 3.2](http://sebastian-bergmann.de/archives/702-Data-Providers-in-PHPUnit-3.2.html)
* [Data Providers](http://www.phpunit.de/manual/3.4/en/writing-tests-for-phpunit.html#writing-tests-for-phpunit.data-providers)


### Grouping tests

To allow users to selectively run tests you need to organise your tests into groups.  Here's an example test showing how to do this:


	<?php
		
	/**
	 * This is a description for my testcase
	 *
	 * @group somegroup
	 * @group somegroup.morespecific
	 */
	Class AnotherReallyCoolTest extends PHPUnit_Framework_TestCase
	{
		/**
		 * Tests can also be grouped too!
		 *
		 * @group annoyingstuff
		 * @group somegroup.annoyingstuff
		 * @group somegroup.morespecific.annoyingstuff
		 */
		function testSomeAnnoyingCase()
		{
			// CODE!!
		}
	}

To actually limit your testing to the "somegroup" group, use:

	$ phpunit --boostrap=index.php --group=somegroup modules/phpunit/tests.php

This functionality can be used to record which bug reports a test is for:

	/**
	 *
	 * @group bugs.1477
	 */
	function testAccountCannotGoBelowZero()
	{
		// Some arbitary code
	}

To see all groups that are available in your code run:

	$ phpunit --boostrap=index.php --list-groups modules/phpunit/tests.php

*Note:* the `--list-groups` switch should appear before the path to the test suite loader

For more info see:

* [Better PHPUnit Group Annotations](http://mikenaberezny.com/2007/09/04/better-phpunit-group-annotations/)
* [TestNG-style Grouping of Tests in PHPUnit 3.2] (http://sebastian-bergmann.de/archives/697-TestNG-style-Grouping-of-Tests.html)