<?php

/**
 * Tests the Arr lib that's shipped with kohana
 *
 * @group kohana
 *
 * @package    Unittest
 * @author     Kohana Team
 * @author     BRMatt <matthew@sigswitch.com>
 * @copyright  (c) 2008-2009 Kohana Team
 * @license    http://kohanaphp.com/license
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
			array('kohana::appify(this)', array(array('kohana', 'appify'), array('this'))),
			// Spaces are preserved in parameters
			array('deal::make(me, my mate )', array(array('deal', 'make'), array('me', ' my mate ')))
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

		$this->assertSame(2, count($result));
		$this->assertSame($expected, $result);
	}

	/**
	 * Provides test data for testExtract
	 *
	 * @return array
	 */
	function providerExtract()
	{
		return array(
			array(
				array('kohana' => 'awesome', 'blueflame' => 'was'),
				array('kohana', 'cakephp', 'symfony'),
				NULL,
				array('kohana' => 'awesome', 'cakephp' => NULL, 'symfony' => NULL)
			),
			// I realise noone should EVER code like this in real life,
			// but unit testing is very very very very boring
			array(
				array('chocolate cake' => 'in stock', 'carrot cake' => 'in stock'),
				array('carrot cake', 'humble pie'),
				'not in stock',
				array('carrot cake' => 'in stock', 'humble pie' => 'not in stock'),
			),
		);
	}

	/**
	 * Tests Arr::extract()
	 *
	 * @test
	 * @dataProvider providerExtract
	 * @param array $array
	 * @param array $keys
	 * @param mixed $default
	 * @param array $expected
	 */
	function testExtract(array $array, array $keys, $default, $expected)
	{
		$array = Arr::extract($array, $keys, $default);

		$this->assertSame(count($expected), count($array));
		$this->assertSame($expected, $array);
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

	function providerMerge()
	{
		return array(
			array(
				array('name' => 'mary', 'children' => array('fred', 'paul', 'sally', 'jane')),
				array('name' => 'john', 'children' => array('fred', 'paul', 'sally', 'jane')),
				array('name' => 'mary', 'children' => array('jane')),
			),
		);	
	}

	/**
	 *
	 * @test
	 * @dataProvider providerMerge
	 */
	function testMerge($expected, $array1, $array2)
	{
		$this->assertSame(
			$expected,
			Arr::merge($array1,$array2)
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

	/**
	 * Provides test data for testRange()
	 * 
	 * @return array
	 */
	function providerRange()
	{
		return array(
			array(1, 2),
			array(1, 100),
			array(25, 10),
		);
	}
	
	/**
	 * Tests Arr::range()
	 *
	 * @dataProvider providerRange
	 * @param integer $step  The step between each value in the array
	 * @param integer $max   The max value of the range (inclusive)
	 */
	function testRange($step, $max)
	{
		$range = Arr::range($step, $max);

		$this->assertSame((int) floor($max / $step), count($range));

		$current = $step;

		foreach($range as $key => $value)
		{
			$this->assertSame($key, $value);
			$this->assertSame($current, $key);
			$this->assertLessThanOrEqual($max, $key);
			$current += $step;
		}
	}

	/**
	 * Provides test data for testUnshift()
	 *
	 * @return array
	 */
	function providerUnshift()
	{
		return array(
			array(array('one' => '1', 'two' => '2',), 'zero', '0'),
			array(array('step 1', 'step 2', 'step 3'), 'step 0', 'wow')
		);
	}

	/**
	 * Tests Arr::unshift()
	 *
	 * @test
	 * @dataProvider providerUnshift
	 * @param array $array
	 * @param string $key
	 * @param mixed $value
	 */
	function testUnshift(array $array, $key, $value)
	{
		$original = $array;

		Arr::unshift($array, $key, $value);

		$this->assertNotSame($original, $array);
		$this->assertSame(count($original) + 1, count($array));
		$this->assertArrayHasKey($key, $array);

		$this->assertSame($value, reset($array));
		$this->assertSame(key($array), $key);
	}
}
