<?php
/**
 * PHPUnit testsuite for kohana application
 */
class Kohana_Tests
{
	static function suite()
	{		
		$files = Kohana::list_files('phpunit_tests');

		$suite = new PHPUnit_Framework_TestSuite();

		PHPUnit_Util_Filter::addDirectoryToFilter(MODPATH);
		PHPUnit_Util_Filter::addDirectoryToFilter(SYSPATH);
		self::addTests($suite, $files);

		return $suite;
	}
	
	static function addTests($suite, $files)
	{
		foreach($files as $file)
		{
			if(is_array($file))
			{
				self::addTests($suite, $file);
			} 
			else 
			{		
				if(is_file($file))
				{
					// The default PHPUnit TestCase extension
					if(! strpos($file, 'TestCase'.EXT))
					{			
						$suite->addTestFile($file);
					}
					else
					{
						require_once($file);
					}

					PHPUnit_Util_Filter::addFileToFilter($file);
				}
			}
		}
	}

}
