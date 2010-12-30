# Installing PHPUnit

Before the Unittest module will work, you need to install PHPUnit.

## Installing on Windows/XAMPP

**(written by bitshift, see [this forum post](http://forum.kohanaframework.org/discussion/7346))**

Assuming xampp is installed to `C:\xampp`, and that you have pear installed, do the following:

1. Open a command prompt and go to C:\xampp\php
2. Type `pear update-channels` (updates channel definitions)
3. Type `pear upgrade` (upgrades all existing packages and pear)
4. Type `pear channel-discover components.ez.no` (This is needed for PHPUnit)
5. Type `pear channel-discover pear.symfony-project.com` (Also needed by PHPUnit)
6. Type `pear channel-discover pear.phpunit.de` (This is the phpunit channel)
7. Type `pear install --alldeps phpunit/PHPUnit` (This actually installs PHPUnit and all dependencies)

[!!] You may have to edit `memory_limit` in your `php.ini` if you get some sort of memory error, just set it to something really large, then back when your done.

Please see [this forum post](http://forum.kohanaframework.org/discussion/7346) for more information.

## Installing on *nix

If any of these fail, try again with sudo, most of them require root priveledges.

 1. Install the 'php-pear' package.
     - On Ubuntu/Debian type `sudo apt-get install php-pear`
 2. Make sure Pear is up-to-date
     - Type `pear update-channels`
	 - Type `pear upgrade`
 3. Add the required channels
     - Type `pear channel-discover components.ez.no`
	 - Type `pear channel-discover pear.symfony-project.com`
	 - Type `pear channel-discover pear.phpunit.de`
 4. Install PHPUnit itself
     - Type `pear install --alldeps phpunit/PHPUnit`

## Class 'PHPUnit_Framework_TestSuite' not found

If you get this error than it means... I don't even know.