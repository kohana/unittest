<?php
/**
 * TestCase for unittesting
 *
 * @package    Kohana/Unittest
 * @author     Kohana Team
 * @author     BRMatt <matthew@sigswitch.com>
 * @author	   Paul Banks
 * @copyright  (c) 2008-2009 Kohana Team
 * @license    http://kohanaphp.com/license
 */
Abstract Class Kohana_Unittest_TestCase extends PHPUnit_Framework_TestCase
{
	/**
	 * Make sure PHPUnit backs up globals
	 * @var boolean
	 */
	protected $backupGlobals = TRUE;

	/**
	 * A set of unittest helpers that are shared between normal / database
	 * testcases
	 * @var Kohana_Unittest_Helpers
	 */
	protected $_helpers = NULL;

	/**
	 * A default set of environment to be applied before each test
	 * @var array
	 */
	protected $environmentDefault = array();

	/**
	 * Creates a predefined environment using the default environment
	 *
	 * Extending classes that have their own setUp() should call
	 * parent::setUp()
	 */
	public function setUp()
	{
		$this->_helpers = new Kohana_Unittest_Helpers;

		$this->setEnvironment($this->environmentDefault);
	}

	/**
	 * Restores the original environment overriden with setEnvironment()
	 *
	 * Extending classes that have their own tearDown()
	 * should call parent::tearDown()
	 */
	public function tearDown()
	{
		$this->_helpers->restore_environment();
	}

	/**
	 * Removes all kohana related cache files in the cache directory
	 */
	public function cleanCacheDir()
	{
		return Kohana_Unittest_Helpers::clean_cache_dir();
	}

	/**
	 * Helper function that replaces all occurences of '/' with
	 * the OS-specific directory separator
	 *
	 * @param string $path The path to act on
	 * @return string
	 */
	public function dirSeparator($path)
	{
		return Kohana_Unittest_Helpers::dir_separator($path);
	}

	/**
	 * Allows easy setting & backing up of enviroment config
	 *
	 * Option types are checked in the following order:
	 *
	 * * Server Var
	 * * Static Variable
	 * * Config option
	 *
	 * @param array $environment List of environment to set
	 */
	public function setEnvironment(array $environment)
	{
		return $this->_helpers->set_environment($environment);
	}

	/**
	 * Check for internet connectivity
	 *
	 * @return boolean Whether an internet connection is available
	 */
	public function hasInternet()
	{
		return Kohana_Unittest_Helpers::has_internet();
	}
}