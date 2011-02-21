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

## Running the tests

         $ phpunit --bootstrap=modules/unittest/bootstrap.php {tests}

Where `{tests}` can either be a path to a folder of tests, or a path to the the `tests.php` (`modules/unittest/tests.php`)

Please see the guide pages for more info.  An example of how we run the tests for the kohana project can be found in the [phing build script](https://github.com/kohana/kohana/blob/3.1/master/build.xml#L172).

If you're looking for more info on running the core kohana tests then please see our [dev wiki](https://github.com/kohana/kohana/wiki/Unit-Testing-Kohana)