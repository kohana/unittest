# Kohana-PHPUnit integration

This module integrates PHPUnit with Kohana.  

If you look through any of the tests provided in this module you'll probably notice all theHorribleCamelCase. 
I've chosen to do this because it's part of the PHPUnit coding conventions and is required for certain features such as auto documentation.

## Requirements

* PHPUnit >= 3.4
* The [Archive module](http://github.com/BMatt/kohana-archive) is required for downloading code coverage reports

## Installation

Step 0: Download this module!

To get it from git execute the following command in the root of your project:

	$ git submodule add git://github.com/kohana/unittest.git modules/unittest

And watch the gitorious magic...

Of course you can always download the code from the [github project](http://github.com/kohana/unittest) as an archive.

The following instructions will assume you've moved it to `modules/unittest`, if you haven't then you should update all paths accordingly.

Step 1: Enable this module in your bootstrap file

	/**
	 * Enable modules. Modules are referenced by a relative or absolute path.
	 */
	Kohana::modules(array(
		'unittest'	=> MODPATH.'unittest'	 // PHPUnit integration
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

Step 3: Create a folder called phpunit in your app's cache dir (APPPATH/cache).  If you don't want to use this path for storing generated reports then skip this step and change the config file (see Step 3.5)

Step 3.5: Make sure the settings in config/unittest.php are correct for your enviroment.

If they aren't, then copy the file to application/config/unittest.php and change the values accordingly

Step 4: Start testing!

You can find more info && tutorials in the guide/ directory
