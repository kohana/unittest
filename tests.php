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

		foreach($files as $file)
		{
			if(is_file($file) AND ! strpos($file, 'Base_Test'.EXT))
			{
				PHPUnit_Util_Filter::addFileToFilter($file);
				$suite->addTestFile($file);
			}
		}

		return $suite;
	}

}
