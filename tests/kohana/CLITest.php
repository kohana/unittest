<?php

/**
 * Unit tests for CLI
 *
 * Based on the Kohana-unittest test
 *
 * @group kohana
 * @group kohana.cli
 * @see CLI
 * @author BRMatt <matthew@sigswitch.com>
 * @author Shadowhand 
 */
Class Kohana_CLI_Test extends PHPUnit_Framework_TestCase
{
	
	/**
	 * Tell PHPUnit to isolate globals during tests 
	 * @var boolean 
	 */
	protected $backupGlobals = TRUE;

	/**
	 * An array of arguments to put in $_SERVER['argv']
	 * @var array 
	 */
	protected $options = array(
							'--uri' => 'test/something',
							'--we_are_cool',
							'invalid option',
							'--version' => '2.23'
						);

	/**
	 * Setup the enviroment for each test
	 *
	 * PHPUnit automatically backups up & restores global variables
	 */
	function setUp()
	{
		$_SERVER['argv'] = array('index.php');

		foreach($this->options as $option => $value)
		{
			if(is_string($option))
			{
				$_SERVER['argv'][] = $option.'='.$value;
			}
			else
			{
				$_SERVER['argv'][] = $value;
			}
		}

		$_SERVER['argc'] = count($_SERVER['argv']);
	}

	/**
	 * Tests CLI::options()
	 *
	 * Options should only parse arguments requested
	 *
	 * @test
	 */
	function testOptionsOnlyParsesWantedArguments()
	{
		$options = CLI::options('uri');

		$this->assertSame(1, count($options));
		
		$this->assertArrayHasKey('uri', $options);
		$this->assertSame($options['uri'], $this->options['--uri']);
	}

	/**
	 * Tests CLI::options()
	 *
	 * Options should not parse invalid arguments (i.e. not starting with --_
	 *
	 * @test
	 */
	function testOptionsDoesNotParseInvalidArguments()
	{
		$options = CLI::options('uri', 'invalid');
		
		$this->assertSame(1, count($options));
		$this->assertArrayHasKey('uri', $options);
		$this->assertArrayNotHasKey('invalid', $options);
	}

	/**
	 * Tests CLI::options()
	 *
	 * Options should parse multiple arguments & values correctly
	 *
	 * @test
	 */
	function testOptionsParsesMultipleArguments()
	{
		$options = CLI::options('uri', 'version');

		$this->assertSame(2, count($options));
		$this->assertArrayHasKey('uri', $options);
		$this->assertArrayHasKey('version', $options);
		$this->assertSame($this->options['--uri'], $options['uri']);
		$this->assertSame($this->options['--version'], $options['version']);
	}

	/**
	 * Tests CLI::options()
	 *
	 * Options should parse arguments without specified values as NULL
	 *
	 * @test
	 */
	function testOptionsParsesArgumentsWithoutValueAsNull()
	{
		$options = CLI::options('uri', 'we_are_cool');

		$this->assertSame(2, count($options));
		$this->assertSame(NULL, $options['we_are_cool']);
	}
}