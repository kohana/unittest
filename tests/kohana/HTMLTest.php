<?php

/**
 * Tests HTML
 * 
 * @group kohana
 */
Class Kohana_HTMLTest extends PHPUnit_Framework_TestCase
{
	
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
}