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

	/**
	 * Tests that Security::xss_clean() removes null bytes
	 * 
	 *
	 * @test
	 * @covers Security::xss_clean
	 * @ticket 2676
	 * @see http://www.hakipedia.com/index.php/Poison_Null_Byte#Perl_PHP_Null_Byte_Injection
	 */
	function testXssCleanRemovesNullBytes()
	{
		$input = "<\0script>alert('XSS');<\0/script>";

		$this->assertSame("alert('XSS');", Security::xss_clean($input));
	}
}
