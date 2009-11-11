# Kohana-PHPUnit integration

This module integrates PHPUnit with Kohana.  It's kinda obvious, but I'll say it anyway -


*_This is NOT compatible with kohana-unittest_*


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

## Writing tests

If you're writing a test for your application, place it in "application/phpunit_tests".  Similarly, if you're writing a test for a module place it in modules/[modulefolder]/phpunit_tests

*Note:* The 'phpunit_tests' folder is used to avoid conflict with kohana-unittest, however if you want to use the tests folder instead then feel free to change tests.php to reflect this.

### Grouping tests

To allow users to selectively run tests you need to organise your tests into groups.  Here's an example test showing how to do this:


	<?php
		
		/**
   		 * This is a description for my testcase
 		 *
		 * @group somegroup
		 * @group somegroup.morespecific
		 */
		Class SomeReallyCool_Test extends PHPUnit_Framework_TestCase
		{
			
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