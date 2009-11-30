<?php
/**
 * PHPUnit testsuite for kohana application
 */
class Kohana_Tests
{
	/**
	 * Controls whether the suite will automatically whitelist files
	 *
	 * Set to FALSE before calling suite() to prevent files from being whitelisted
	 *
	 * @var boolean
	 */
	static public $auto_whitelist = TRUE;

	/**
	 *
	 * @var <type>
	 */
	static public $auto_blacklist = TRUE;

	static function suite()
	{		
		$files = Kohana::list_files('tests');

		$suite = new PHPUnit_Framework_TestSuite();

		self::addTests($suite, $files);

		$config = Kohana::config('phpunit');

		// In case the web ui wants to whitelist files
		if(self::$auto_whitelist AND $config->use_whitelist)
		{
			$directories = array();

			if($config->whitelist['app'])
			{
				$directories[] = APPPATH;
			}

			if($modules = $config->whitelist['modules'])
			{
				$k_modules = Kohana::modules();
				
				if($modules === TRUE)
				{
					$modules = $k_modules;
				}
				else
				{
					$modules = array_intersect_key($k_modules, array_combine($modules, $modules));
				}

				$directories += array_values($modules);
			}

			if($config->whitelist['system'])
			{
				$directories[] = SYSPATH;
			}

			
			foreach($directories as &$directory)
			{
				$directory = realpath($directory).'/';
			}
			
			// When the phpunit report is generated it includes all files, which can cause name conflicts
			// We therefore only whitelist the "top" files in the cascading filesystem
			// If you have a bone to pick with this, then simply whitelist the individual modules you're testing
			self::whitelist(Kohana::list_files('classes', $directories));
		}

		if(count($config['blacklist']))
		{
			foreach($config->blacklist as $item)
			{
				if(is_dir($item))
				{
					PHPUnit_Util_Filter::addDirectoryToFilter($item);
				}
				else
				{
					PHPUnit_Util_Filter::addFileToFilter($item);
				}
			}
		}

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

	/**
	 * Recursively whitelists an array of files
	 *
	 * @param array $files Array of files to whitelist
	 */
	static protected function whitelist($files)
	{
		foreach($files as $file)
		{
			if(is_array($file))
			{
				self::whitelist($file);
			}
			else
			{
				PHPUnit_Util_Filter::addFileToWhitelist($file);
			}
		}
	}

}
