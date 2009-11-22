<?php

/**
 * Tests the Arr lib that's shipped with kohana
 *
 * @group kohana
 */
Class Kohana_ArrTest extends PHPUnit_Framework_TestCase
{
	protected $traversable =	array(
									'foobar' =>	array(
													'definition' => 'lost'
												),
									'kohana' => 'awesome'
								);


	/**
	 * Provides test data for testCallback()
	 *
	 * @return array
	 */
	function providerCallback()
	{
		return array(
			// Tests....
			// That no parameters returns null
			array('function', array('function', NULL)),
			// That we can get an array of parameters values
			array('function(1,2,3)', array('function', array('1', '2', '3'))),
			// That it's not just using the callback "function"
			array('different_name(harry,jerry)', array('different_name', array('harry', 'jerry'))),
			// That static callbacks are parsed into arrays
			array('kohana::appify(this)', array(array('kohana', 'appify'), array('this')))
			// TODO: add more cases
		);
	}

	/**
	 * Tests Arr::callback()
	 *
	 * @test
	 * @dataProvider providerCallback
	 * @param string $str       String to parse
	 * @param array  $expected  Callback and its parameters
	 */
	function testCallback($str, $expected)
	{
		$result = Arr::callback($str);

		$this->assertSame(
			2,
			count($result)
		);
		$this->assertSame(
			$expected,
			$result
		);
	}


	/**
	 * Provides test data for testGet()
	 *
	 * @return array
	 */
	function providerGet()
	{
		return array(
			array(array('uno', 'dos', 'tress'), 1, NULL, 'dos'),
			array(array('we' => 'can', 'make' => 'change'), 'we', NULL, 'can'),

			array(array('uno', 'dos', 'tress'), 10, NULL, NULL),
			array(array('we' => 'can', 'make' => 'change'), 'he', NULL, NULL),
			array(array('we' => 'can', 'make' => 'change'), 'he', 'who', 'who'),
			array(array('we' => 'can', 'make' => 'change'), 'he', array('arrays'), array('arrays')),
		);
	}

	/**
	 * Tests Arr::get()
	 *
	 * @test
	 * @dataProvider providerGet()
	 * @param array          $array      Array to look in
	 * @param string|integer $key        Key to look for
	 * @param mixed          $default    What to return if $key isn't set
	 * @param mixed          $expected   The expected value returned
	 */
	function testGet(array $array, $key, $default, $expected)
	{
		$this->assertSame(
			$expected,
			Arr::get($array, $key, $default)
		);
	}

	/**
	 * Provides test data for testIsAssoc()
	 *
	 * @return array
	 */
	function providerIsAssoc()
	{
		return array(
			array(array('one', 'two', 'three'), FALSE),
			array(array('one' => 'o clock', 'two' => 'o clock', 'three' => 'o clock'), TRUE),
		);
	}

	/**
	 * Tests Arr::is_assoc()
	 *
	 * @test
	 * @dataProvider providerIsAssoc
	 * @param array   $array     Array to check
	 * @param boolean $expected  Is $array assoc
	 */
	function testIsAssoc(array $array, $expected)
	{
		$this->assertSame(
			$expected,
			Arr::is_assoc($array)
		);
	}

	/**
	 * Provides test data for testGet()
	 *
	 * @return array
	 */
	function providerPath()
	{
		return array(
			array('foobar',  NULL, $this->traversable['foobar']),
			array('kohana',  NULL, $this->traversable['kohana']),
			array('foobar.definition',  NULL, $this->traversable['foobar']['definition']),
			array('foobar.alternatives',  NULL, NULL),
			array('kohana.alternatives',  NULL, NULL),
			array('kohana.alternatives',  'nothing', 'nothing'),
			array('cheese.origins',  array('far', 'wide'), array('far', 'wide')),
		);
	}

	/**
	 * Tests Arr::get()
	 *
	 * Uses $this->traversable
	 * @test
	 * @dataProvider providerPath
	 * @param string  $path      The path to follow
	 * @param mixed   $default   The value to return if dnx
	 * @param boolean $expected  The expected value
	 */
	function testPath($path, $default, $expected)
	{
		$this->assertSame(
			$expected,
			Arr::path($this->traversable, $path, $default)
		);
	}
}