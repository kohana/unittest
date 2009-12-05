<?php

/**
 * Tests Num
 *
 * @covers Num
 * @group kohana
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
}