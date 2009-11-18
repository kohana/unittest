<?php

/**
 * Tests URL
 *
 * @group Kohana
 */
Class Kohana_URLTest extends PHPUnit_Framework_TestCase
{
	protected $_base_url = '';

	protected $_protocol = '';

	protected $_index_file = '';

	protected $_defaults =	array(
								'base_url'	=> '/kohana/',
								'index_file'=> 'index.php',
								'protocol'	=> 'http',
								'HTTP_HOST' => 'example.com',
							);
	function setUp()
	{
		$this->_base_url = Kohana::$base_url;
		$this->_protocol = Request::$protocol;
		$this->_index_file = Kohana::$index_file;

		$this->setEnviroment($this->_defaults);
	}

	function tearDown()
	{
		Kohana::$base_url = $this->_base_url;
		Request::$protocol = $this->_protocol;
		Kohana::$index_file = $this->_index_file;
	}

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
}