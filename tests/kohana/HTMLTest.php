<?php

/**
 * Tests HTML
 * 
 * @group kohana
 *
 * @package    Unittest
 * @author     Kohana Team
 * @author     BRMatt <matthew@sigswitch.com>
 * @copyright  (c) 2008-2009 Kohana Team
 * @license    http://kohanaphp.com/license
 */
Class Kohana_HTMLTest extends PHPUnit_Framework_TestCase
{	
	protected $optionBackups = array();

	protected $optionDefaults = array(
			'HTTP_HOST'	=> 'kohanaframework.org',
		);

	function setUp()
	{
		// We don't need to backup HTTP_HOST as phpunit
		// automatically backs-up / restores globals
		$this->optionBackups = array(
			'windowed_urls' => HTML::$windowed_urls,
			'charset'		=> Kohana::$charset,
			'protocol'		=> Request::$protocol,
			'index_file'	=> Kohana::$index_file,
		);

		$this->setOptions($this->optionDefaults);
	}

	function tearDown()
	{
		$this->setOptions($this->optionBackups);
	}

	function setOptions(array $options)
	{
		if(isset($options['windowed_urls']))
		{
			HTML::$windowed_urls = $options['windowed_urls'];
		}

		if(isset($options['charset']))
		{
			Kohana::$charset = $options['charset'];
		}

		if(isset($options['protocol']))
		{
			Request::$protocol = $options['protocol'];
		}

		if(isset($options['index_file']))
		{
			Kohana::$index_file = $options['index_file'];
		}

		if(isset($options['HTTP_HOST']))
		{
			$_SERVER['HTTP_HOST'] = $options['HTTP_HOST'];
		}
	}

	/**
	 * Provides test data for testAttributes()
	 * 
	 * @return array
	 */
	function providerAttributes()
	{
		return array(
			array(
				array('name' => 'field', 'random' => 'not_quite', 'id' => 'unique_field'),
				' id="unique_field" name="field" random="not_quite"'
			),
			array(
				array('invalid' => NULL),
				''
			),
			array(
				array(),
				''
			)
		);
	}

	/**
	 * Tests HTML::attributes()
	 *
	 * @test
	 * @dataProvider providerAttributes
	 * @covers HTML::attributes
	 * @param array  $attributes  Attributes to use
	 * @param string $expected    Expected output
	 */
	function testAttributes($attributes, $expected)
	{
		$this->assertSame(
			$expected,
			HTML::attributes($attributes)
		);
	}

	/**
	 * Provides test data for testScript
	 *
	 * @return array Array of test data
	 */
	function providerScript()
	{
		return array(
			array(
				'<script type="text/javascript" src="http://google.com/script.js"></script>',
				'http://google.com/script.js',
			),
		);
	}

	/**
	 * Tests HTML::script()
	 *
	 * @test
	 * @covers        HTML::script
	 * @dataProvider  providerScript
	 * @param string  $expected       Expected output
	 * @param string  $file           URL to script
	 * @param array   $attributes     HTML attributes for the anchor
	 * @param bool    $index          Should the index file be included in url?
	 */
	function testScript($expected, $file, array $attributes = NULL, $index = FALSE)
	{
		$this->assertSame(
			$expected,
			HTML::script($file, $attributes, $index)
		);
	}

	/**
	 * Data provider for the style test
	 *
	 * @return array Array of test data
	 */
	function providerStyle()
	{
		return array(
			array(
				'<link type="text/css" href="http://google.com/style.css" rel="stylesheet" />',
				'http://google.com/style.css',
				array(),
				FALSE
			),
		);
	}

	/**
	 * Tests HTML::style()
	 *
	 * @test
	 * @dataProvider  providerStyle
	 * @covers        HTML::style
	 * @param string  $expected     The expected output
	 * @param string  $file         The file to link to
	 * @param array   $attributes   Any extra attributes for the link
	 * @param bool    $index        Whether the index file should be added to the link
	 */
	function testStyle($expected, $file, array $attributes = NULL, $index = FALSE)
	{
		$this->assertSame(
			$expected,
			HTML::style($file, $attributes, $index)
		);
	}

	/**
	 * Provides test data for testObfuscate
	 *
	 * @return array Array of test data
	 */
	function providerObfuscate()
	{
		return array(
			array('something crazy'),
			array('me@google.com'),
		);
	}

	/**
	 * Tests HTML::obfuscate
	 *
	 * @test
	 * @dataProvider   providerObfuscate
	 * @covers         HTML::obfuscate
	 * @param string   $string            The string to obfuscate
	 */
	function testObfuscate($string)
	{
		$this->assertNotSame(
			$string,
			HTML::obfuscate($string)
		);
	}

	/**
	 * Provides test data for testAnchor
	 *
	 * @return array Test data
	 */
	function providerAnchor()
	{
		return array(
			array(
				'<a href="http://kohanaframework.org">Kohana</a>',
				array(),
				'http://kohanaframework.org',
				'Kohana',
			),
			array(
				'<a href="http://google.com" target="_blank">GOOGLE</a>',
				array(),
				'http://google.com',
				'GOOGLE',
				array('target' => '_blank'),
			),
		);
	}

	/**
	 * Tests HTML::anchor
	 *
	 * @test
	 * @dataProvider providerAnchor
	 */
	function testAnchor($expected, array $options, $uri, $title = NULL, array $attributes = NULL, $protocol = NULL)
	{
		$this->setOptions($options);

		$this->assertSame(
			$expected,
			HTML::anchor($uri, $title, $attributes, $protocol)
		);
	}

	/**
	 * Data provider for testFileAnchor
	 *
	 * @return array
	 */
	function providerFileAnchor()
	{
		return array(
			array(
				'<a href="/kohana/mypic.png">My picture file</a>',
				array(),
				'mypic.png',
				'My picture file',
			)
		);
	}

	/**
	 * Test for HTML::file_anchor()
	 *
	 * @test
	 * @covers HTML::file_anchor
	 * @dataProvider providerFileAnchor
	 */
	function testFileAnchor($expected, array $options, $file, $title = NULL, array $attributes = NULL, $protocol = NULL)
	{
		$this->assertSame(
			$expected,
			HTML::file_anchor($file, $title, $attributes, $protocol)
		);
	}
}
