<?php


Class Tests
{
	
	static function suite()
	{
		if( ! class_exists('Kohana'))
		{
			throw new Exception('Please include the kohana bootstrap file (see README.markdown)');
		}
		
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
				if(is_file($file) AND ! strpos($file, 'Base_Test'.EXT))
				{
					PHPUnit_Util_Filter::addFileToFilter($file);
					$suite->addTestFile($file);
				}
			}
		}
	}

}
