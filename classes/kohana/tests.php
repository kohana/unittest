<?php
/**
 * PHPUnit testsuite for kohana application
 *
 * @package    Unittest
 * @author     Kohana Team
 * @author     BRMatt <matthew@sigswitch.com>
 * @author	   Paul Banks
 * @copyright  (c) 2008-2009 Kohana Team
 * @license    http://kohanaphp.com/license
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
			self::whitelist($config);
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
	 * Sets the whitelist
	 * @param array $directories
	 */
	static public function whitelist(array $directories = NULL)
	{
		if(empty($directories))
		{
			$directories = self::get_config_whitelist();
		}

		if(count($directories))
		{
			foreach($directories as &$directory)
			{
				$directory = realpath($directory).'/';
			}

			// When the phpunit report is generated it includes all files, which can cause name conflicts
			// We therefore only whitelist the "top" files in the cascading filesystem
			// If you have a bone to pick with this, then simply whitelist the individual modules you're testing
			self::set_whitelist(Kohana::list_files('classes', $directories));
		}
	}

	/**
	 * Works out the whitelist from the config
	 */
	static public function get_config_whitelist()
	{
		$config = Kohana::config('phpunit');
		$directories = array();

		if($config->whitelist['app'])
		{
			$directories['k_app'] = APPPATH;
		}

		if($modules = $config->whitelist['modules'])
		{
			$k_modules = Kohana::modules();

			// Have to do this because kohana merges config...
			// If you want to include all modules & override defaults then TRUE must be the first
			// value in the modules array of your app/config/phpunit file
			if(array_search(TRUE, $modules, TRUE) === (count($modules) - 1))
			{
				$modules = $k_modules;
			}
			elseif(array_search(FALSE, $modules, TRUE) === FALSE)
			{
				$modules = array_intersect_key($k_modules, array_combine($modules, $modules));
			}
			else
			{
				// modules are disabled
				$modules = array();
			}

			$directories += $modules;
		}

		if($config->whitelist['system'])
		{
			$directories['k_sys'] = SYSPATH;
		}

		return $directories;
	}

	/**
	 * Recursively whitelists an array of files
	 *
	 * @param array $files Array of files to whitelist
	 */
	static protected function set_whitelist($files)
	{
		foreach($files as $file)
		{
			if(is_array($file))
			{
				self::set_whitelist($file);
			}
			else
			{
				PHPUnit_Util_Filter::addFileToWhitelist($file);
			}
		}
	}

}
