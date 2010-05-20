<?php

/**
 * Tests Date class
 * 
 * @group kohana
 *
 * @package    Unittest
 * @author     Kohana Team
 * @author     BRMatt <matthew@sigswitch.com>
 * @copyright  (c) 2008-2009 Kohana Team
 * @license    http://kohanaphp.com/license
 */
Class Kohana_DateTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Provides test data for testDate()
	 *
	 * @return array
	 */
	function providerAmPm()
	{
		return array(
			// All possible values
			array(0, 'AM'),
			array(1, 'AM'),
			array(2, 'AM'),
			array(3, 'AM'),
			array(4, 'AM'),
			array(5, 'AM'),
			array(6, 'AM'),
			array(7, 'AM'),
			array(8, 'AM'),
			array(9, 'AM'),
			array(10, 'AM'),
			array(11, 'AM'),
			array(12, 'PM'),
			array(13, 'PM'),
			array(14, 'PM'),
			array(15, 'PM'),
			array(16, 'PM'),
			array(17, 'PM'),
			array(18, 'PM'),
			array(19, 'PM'),
			array(20, 'PM'),
			array(21, 'PM'),
			array(22, 'PM'),
			array(23, 'PM'),
			array(24, 'PM'),
			// ampm doesn't validate the hour, so I don't think we should test it..
			// test strings are converted
			array('0', 'AM'),
			array('12', 'PM'),
		);
	}

	/**
	 * Tests Date::ampm()
	 * 
	 * @test
	 * @covers Date::ampm
	 * @dataProvider providerAmPm
	 * @param <type> $hour
	 * @param <type> $expected
	 */
	function testAmPm($hour, $expected)
	{
		$this->assertSame(
			$expected,
			Date::ampm($hour)
		);
	}

	/**
	 * Provides test data for testAdjust()
	 *
	 * @return array
	 */
	function providerAdjust()
	{
		return array(
			// Might as well test all possibilities
			array(1,  'am', '01'),
			array(2,  'am', '02'),
			array(3,  'am', '03'),
			array(4,  'am', '04'),
			array(5,  'am', '05'),
			array(6,  'am', '06'),
			array(7,  'am', '07'),
			array(8,  'am', '08'),
			array(9,  'am', '09'),
			array(10, 'am', '10'),
			array(11, 'am', '11'),
			array(12, 'am', '00'),
			array(1,  'pm', '13'),
			array(2,  'pm', '14'),
			array(3,  'pm', '15'),
			array(4,  'pm', '16'),
			array(5,  'pm', '17'),
			array(6,  'pm', '18'),
			array(7,  'pm', '19'),
			array(8,  'pm', '20'),
			array(9,  'pm', '21'),
			array(10, 'pm', '22'),
			array(11, 'pm', '23'),
			array(12, 'pm', '12'),
			// It should also work with strings instead of ints
			array('10', 'pm', '22'),
			array('10', 'am', '10'),
		);
	}

	/**
	 * Tests Date::ampm()
	 *
	 * @test
	 * @covers Date::ampm
	 * @dataProvider providerAdjust
	 * @param integer $hour       Hour in 12 hour format
	 * @param string  $ampm       Either am or pm
	 * @param string  $expected   Expected result
	 */
	function testAdjust($hour, $ampm, $expected)
	{
		$this->assertSame(
			$expected,
			Date::adjust($hour, $ampm)
		);
	}

	/**
	 * Provides test data for testDays()
	 *
	 * @return array
	 */
	function providerDays()
	{
		return array(
			// According to "the rhyme" these should be the same every year
			array(9, FALSE, 30),
			array(4, FALSE, 30),
			array(6, FALSE, 30),
			array(11, FALSE, 30),
			array(1, FALSE, 31),
			array(3, FALSE, 31),
			array(5, FALSE, 31),
			array(7, FALSE, 31),
			array(8, FALSE, 31),
			array(10, FALSE, 31),
			// February is such a pain
			array(2, 2001, 28),
			array(2, 2000, 29),
			array(2, 2012, 29),
		);
	}

	/**
	 * Tests Date::days()
	 *
	 * @test
	 * @covers Date::days
	 * @dataProvider providerDays
	 * @param integer $month
	 * @param integer $year
	 * @param integer $expected
	 */
	function testDays($month, $year, $expected)
	{
		$days = Date::days($month, $year);

		$this->assertSame(
			$expected,
			count($days)
		);

		// This should be a mirrored array, days => days
		for($i = 1; $i <= $expected; ++$i)
		{
			$this->assertArrayHasKey($i, $days);
			// Combining the type check into this saves about 400-500 assertions!
			$this->assertSame((string) $i, $days[$i]);
		}
	}

	/**
	 * Tests Date::months()
	 * 
	 * @test
	 * @covers Date::months
	 */
	function testMonths()
	{
		$months = Date::months();

		$this->assertSame(12, count($months));

		for($i = 1; $i <= 12; ++$i)
		{
			$this->assertArrayHasKey($i, $months);
			$this->assertSame((string) $i, $months[$i]);
		}
	}

	/**
	 * Provides test data for testSpan()
	 *
	 * @return array
	 */
	function providerSpan()
	{
		$time = time();
		return array(
			// Test that it must specify an output format
			array(
				$time,
				$time,
				'',
				FALSE
			),
			// Random tests
			array(
				$time - 30,
				$time,
				'years,months,weeks,days,hours,minutes,seconds',
				array('years' => 0, 'months' => 0, 'weeks' => 0, 'days' => 0, 'hours' => 0, 'minutes' => 0, 'seconds' => 30),
			),
			array(
				$time - (60 * 60 * 24 * 782) + (60 * 25),
				$time,
				'years,months,weeks,days,hours,minutes,seconds',
				array('years' => 2, 'months' => 1, 'weeks' => 3, 'days' => 0, 'hours' => 1, 'minutes' => 28, 'seconds' => 24),
			),
			// Should be able to compare with the future & that it only uses formats specified
			array(
				$time + (60 * 60 * 24 * 15) + (60 * 5),
				$time,
				'weeks,days,hours,minutes,seconds',
				array('weeks' => 2, 'days' => 1, 'hours' => 0, 'minutes' => 5, 'seconds' => 0),
			),
		);
	}

	/**
	 * Tests Date::span()
	 *
	 * @test
	 * @covers Date::span
	 * @dataProvider providerSpan
	 * @param integer $time1     Time in the past
	 * @param integer $time2     Time to compare against
	 * @param string  $output    Units to output
	 * @param array   $expected  Array of $outputs => values
	 */
	function testSpan($time1, $time2, $output, $expected)
	{
		$this->assertSame(
			$expected,
			Date::span($time1, $time2, $output)
		);
	}

	
}
