<?php

/**
 * Tests Num
 *
 * @package    Unittest
 * @author     Kohana Team
 * @author     BRMatt <matthew@sigswitch.com>
 * @copyright  (c) 2008-2009 Kohana Team
 * @license    http://kohanaphp.com/license
 */
Class Kohana_NumTest extends PHPUnit_Framework_TestCase
{

	/**
	 * Provides test data for testOrdinal()
	 * @return array
	 */
	function providerOrdinal()
	{
		return array(
			array(0, 'th'),
			array(1, 'st'),
			array(21, 'st'),
			array(112, 'th'),
		);
	}

	/**
	 *
	 * @test
	 * @dataProvider providerOrdinal
	 * @covers Num::ordinal
	 * @param integer $number
	 * @param <type> $expected
	 */
	function testOrdinal($number, $expected)
	{
		$this->assertSame($expected, Num::ordinal($number));
	}

	/**
	 * Provides test data for testFormat()
	 * @return array
	 */
	function providerFormat()
	{
		return array(
			// English
			array(10000, 2, FALSE, '10,000.00'),
			array(10000, 2, TRUE, '10,000.00'),

			// Additional dp's should be removed
			array(123.456, 2, FALSE, '123.46'),
			array(123.456, 2, TRUE, '123.46'),
		);
	}

	/**
	 * @todo test locales
	 * @test
	 * @dataProvider providerFormat
	 * @covers Num::format
	 * @param integer $number
	 * @param integer $places
	 * @param boolean $monetary
	 * @param string $expected
	 */
	function testFormat($number, $places, $monetary, $expected)
	{
		$this->assertSame($expected, Num::format($number, $places, $monetary));
	}
}