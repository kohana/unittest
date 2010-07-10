# Kohana-PHPUnit integration

This module integrates PHPUnit with Kohana.

If you look through any of the tests provided in this module you'll probably notice all theHorribleCamelCase.
I've chosen to do this because it's part of the PHPUnit coding conventions and is required for certain features such as auto documentation.

## Requirements

* [PHPUnit](http://www.phpunit.de/) >= 3.4

### Optional extras

* The [Archive module](http://github.com/BRMatt/kohana-archive) is required if you want to download code coverage reports from the web ui, however you can also view them without downloading.

## Installation

**Step 0**: Download this module!

To get it from git execute the following command in the root of your project:

	$ git submodule add git://github.com/kohana/unittest.git modules/unittest

And watch the gitorious magic...

Of course, you can always download the code from the [github project](http://github.com/kohana/unittest) as an archive.

The following instructions will assume you've moved it to `modules/unittest`, if you haven't then you should update all paths accordingly.

**Step 1**: Enable this module in your bootstrap file:

	/**
	 * Enable modules. Modules are referenced by a relative or absolute path.
	 */
	Kohana::modules(array(
		'unittest' => MODPATH.'unittest',  // PHPUnit integration
		));

**Step 2**: In your app's bootstrap file modify the lines where the request is handled, which by default looks like:

	/**
	 * Execute the main request using PATH_INFO. If no URI source is specified,
	 * the URI will be automatically detected.
	 */
	echo Request::instance($_SERVER['PATH_INFO'])
		->execute()
		->send_headers()
		->response;

To:

	if ( ! defined('SUPPRESS_REQUEST'))
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

**Step 3**: Create a folder called `unittest` in your app's cache dir (`APPPATH/cache`). If you don't want to use this path for storing generated reports, skip this step and change the config file.

Note: make sure the settings in `config/unittest.php` are correct for your environment. If they aren't, copy the file to `application/config/unittest.php` and change the values accordingly.

**Step 4**: Start testing!

You can find more info and tutorials in the [guide/](http://github.com/kohana/unittest/tree/master/guide/) directory.
