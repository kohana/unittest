<?php

/**
 * Tests URL
 *
 * @group kohana
 * @group kohana.url
 *
 * @package    Unittest
 * @author     Kohana Team
 * @author     BRMatt <matthew@sigswitch.com>
 * @copyright  (c) 2008-2009 Kohana Team
 * @license    http://kohanaphp.com/license
 */
Class Kohana_URLTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Tell PHPUnit to isolate globals during tests
	 * @var boolean
	 */
	protected $backupGlobals = TRUE;

	/**
	 * Backup of Kohana::$base_url
	 * @var string
	 */
	protected $_base_url = '';

	/**
	 * Backup of Request::$protocol
	 * @var string
	 */
	protected $_protocol = '';

	/**
	 * Backup of Kohana::$index_file
	 * @var string
	 */
	protected $_index_file = '';

	/**
	 * Default values to set for base_url, index_file, protocol and HTTP_HOST
	 * @var array
	 */
	protected $_defaults =	array(
								'base_url'	=> '/kohana/',
								'index_file'=> 'index.php',
								'protocol'	=> 'http',
								'HTTP_HOST' => 'example.com',
							);
	/**
	 * Sets up the enviroment for each test, loads default enviroment values
	 */
	function setUp()
	{
		$this->_base_url = Kohana::$base_url;
		$this->_protocol = Request::$protocol;
		$this->_index_file = Kohana::$index_file;

		$this->setEnviroment($this->_defaults);
	}

	/**
	 * Resets the enviroment after each test
	 */
	function tearDown()
	{
		Kohana::$base_url = $this->_base_url;
		Request::$protocol = $this->_protocol;
		Kohana::$index_file = $this->_index_file;
	}

	/**
	 * Changes certain aspects of the enviroment
	 *
	 * @param array $vars
	 * @return boolean
	 */
	function setEnviroment(array $vars)
	{
		if(empty($vars))
		{
			return FALSE;
		}

		if(isset($vars['base_url']))
		{
			Kohana::$base_url = $vars['base_url'];
		}

		if(isset($vars['protocol']))
		{
			Request::$protocol = $vars['protocol'];
		}

		if(isset($vars['index_file']))
		{
			Kohana::$index_file = $vars['index_file'];
		}

		if(isset($vars['HTTP_HOST']))
		{
			$_SERVER['HTTP_HOST'] = $vars['HTTP_HOST'];
		}

		return TRUE;
	}

	/**
	 * Provides test data for testBase()
	 * 
	 * @return array
	 */
	function providerBase()
	{
		return array(
			// $index, $protocol, $expected, $enviroment
			//
			// Test with different combinations of parameters for max code coverage
			array(FALSE, FALSE,  '/kohana/'),
			array(FALSE, TRUE,   'http://example.com/kohana/'),
			array(TRUE,  FALSE,  '/kohana/index.php/'),
			array(TRUE,  FALSE,  '/kohana/index.php/'),
			array(TRUE,  TRUE,   'http://example.com/kohana/index.php/'),
			array(TRUE,  'http', 'http://example.com/kohana/index.php/'),
			array(TRUE,  'https','https://example.com/kohana/index.php/'),
			array(TRUE,  'ftp',  'ftp://example.com/kohana/index.php/'),

			//
			// These tests make sure that the protocol changes when the global setting changes
			array(TRUE,   TRUE,   'https://example.com/kohana/index.php/', array('protocol' => 'https')),
			array(FALSE,  TRUE,   'https://example.com/kohana/', array('protocol' => 'https')),

			// Change base url
			array(FALSE, 'https', 'https://example.com/kohana/', array('base_url' => 'omglol://example.com/kohana/'))
		);
	}

	/**
	 * Tests URL::base()
	 *
	 * @test
	 * @dataProvider providerBase
	 * @param boolean $index       Parameter for Url::base()
	 * @param boolean $protocol    Parameter for Url::base()
	 * @param string  $expected    Expected url
	 * @param array   $enviroment  Array of enviroment vars to change @see Kohana_URLTest::setEnviroment()
	 */
	function testBase($index, $protocol, $expected, array $enviroment = array())
	{
		$this->setEnviroment($enviroment);

		$this->assertSame(
			$expected,
			URL::base($index, $protocol)
		);
	}

	/**
	 * Provides test data for testSite()
	 * 
	 * @return array
	 */
	function providerSite()
	{
		return array(
			array('', FALSE,		'/kohana/index.php/'),
			array('', TRUE,			'http://example.com/kohana/index.php/'),

			array('my/site', FALSE, '/kohana/index.php/my/site'),
			array('my/site', TRUE,  'http://example.com/kohana/index.php/my/site'),

			array('my/site?var=asd&kohana=awesome', FALSE,  '/kohana/index.php/my/site?var=asd&kohana=awesome'),
			array('my/site?var=asd&kohana=awesome', TRUE,  'http://example.com/kohana/index.php/my/site?var=asd&kohana=awesome'),

			array('?kohana=awesome&life=good', FALSE, '/kohana/index.php/?kohana=awesome&life=good'),
			array('?kohana=awesome&life=good', TRUE, 'http://example.com/kohana/index.php/?kohana=awesome&life=good'),

			array('?kohana=awesome&life=good#fact', FALSE, '/kohana/index.php/?kohana=awesome&life=good#fact'),
			array('?kohana=awesome&life=good#fact', TRUE, 'http://example.com/kohana/index.php/?kohana=awesome&life=good#fact'),

			array('some/long/route/goes/here?kohana=awesome&life=good#fact', FALSE, '/kohana/index.php/some/long/route/goes/here?kohana=awesome&life=good#fact'),
			array('some/long/route/goes/here?kohana=awesome&life=good#fact', TRUE, 'http://example.com/kohana/index.php/some/long/route/goes/here?kohana=awesome&life=good#fact'),

			array('/route/goes/here?kohana=awesome&life=good#fact', 'https', 'https://example.com/kohana/index.php/route/goes/here?kohana=awesome&life=good#fact'),
			array('/route/goes/here?kohana=awesome&life=good#fact', 'ftp', 'ftp://example.com/kohana/index.php/route/goes/here?kohana=awesome&life=good#fact'),
		);
	}

	/**
	 * Tests URL::site()
	 *
	 * @test
	 * @dataProvider providerSite
	 * @param string          $uri         URI to use
	 * @param boolean|string  $protocol    Protocol to use
	 * @param string          $expected    Expected result
	 * @param array           $enviroment  Array of enviroment vars to set
	 */
	function testSite($uri, $protocol, $expected, array $enviroment = array())
	{
		$this->setEnviroment($enviroment);

		$this->assertSame(
			$expected,
			URL::site($uri, $protocol)
		);
	}

	/**
	 * Provides test data for testTitle()
	 * @return array
	 */
	function providerTitle()
	{
		return array(
			// Tests that..
			// Title is converted to lowercase
			array('WE SHALL NOT BE MOVED', '-', 'we-shall-not-be-moved'),
			// Excessive white space is removed and replaced with 1 char
			array('THISSSSSS         IS       IT  ', '-', 'thissssss-is-it'),
			// separator is either - (dash) or _ (underscore) & others are converted to underscores
			array('some title', '-', 'some-title'),
			array('some title', '_', 'some_title'),
			array('some title', '!', 'some_title'),
			array('some title', NULL, 'some_title'),
			array('some title', ':', 'some_title'),
			// Numbers are preserved
			array('99 Ways to beat apple', '-', '99-ways-to-beat-apple'),
			// ... with lots of spaces & caps
			array('99    ways   TO beat      APPLE', '_', '99_ways_to_beat_apple'),
			array('99    ways   TO beat      APPLE', '-', '99-ways-to-beat-apple'),
			// Invalid characters are removed
			array('Each GBP(£) is now worth 32 USD($)', '-', 'each-gbp-is-now-worth-32-usd'),
			// ... inc. separator
			array('Is it reusable or re-usable?', '-', 'is-it-reusable-or-re-usable'),
		);
	}

	/**
	 * Tests URL::title()
	 *
	 * @test
	 * @dataProvider providerTitle
	 * @param string $title        Input to convert
	 * @param string $separator    Seperate to replace invalid characters with
	 * @param string $expected     Expected result
	 */
	function testTitle($title, $separator, $expected)
	{
		$this->assertSame(
			$expected,
			URL::title($title, $separator)
		);
	}
}