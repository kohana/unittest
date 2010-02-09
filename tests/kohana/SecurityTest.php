<?php

/**
 * Tests Kohana_Security
 *
 * @group kohana
 */

Class Kohana_SecurityTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Provides test data for testEnvodePHPTags()
	 *
	 * @return array Test data sets
	 */
	function providerEncodePHPTags()
	{
		return array
			(	
				array("&lt;?php echo 'helloo'; ?&gt;", "<?php echo 'helloo'; ?>"),
			);
	}

	/**
	 * Tests Security::encode_php_tags()
	 *
	 * @test
	 * @dataProvider providerEncodePHPTags
	 * @covers Security::encode_php_tags
	 */
	function testEncodePHPTags($expected, $input)
	{
		$this->assertSame($expected, Security::encode_php_tags($input));
	}
}
