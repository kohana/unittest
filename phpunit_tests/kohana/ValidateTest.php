<?php

/**
 * Tests the Validate lib that's shipped with Kohana
 *
 * @group kohana
 * @group kohana.validation
 */
Class Kohana_ValidateTest extends PHPUnit_Framework_TestCase
{

	/**
	 * Provides test data for testAlpha()
	 * @return array
	 */
	public function providerAlpha()
	{
		return array(
			array('asdavafaiwnoabwiubafpowf', TRUE),
			array('!aidhfawiodb', FALSE),
			array('51535oniubawdawd78', FALSE),
			array('!"£$(G$W£(HFW£F(HQ)"n', FALSE)
		);
	}
	
	/**
	 * Tests Validate::alpha()
	 * 
	 * Checks whether a string consists of alphabetical characters only.
	 *
	 * @test
	 * @group kohana.validation.helpers
	 * @dataProvider providerAlpha
	 * @param string  $string
	 * @param boolean $expected
	 */
	public function testAlpha($string, $expected)
	{
		$this->assertSame(
			$expected,
			Validate::alpha($string)
		);
	}

	/*
	 * Provides test data for testAlphaNumeric
	 */
	public function provideAlphaNumeric()
	{
		return array(
			array('abcd1234',  TRUE),
		    array('abcd',      TRUE),
		    array('1234',      TRUE),
		    array('abc123&^/-', FALSE)
		);
	}

	/**
	 * Tests Validate::alpha_numberic()
	 *
	 * Checks whether a string consists of alphabetical characters and numbers only.
	 *
	 * @test
	 * @group kohana.validation.helpers
	 * @dataProvider provideAlphaNumeric
	 * @param string  $input     The string to test
	 * @param boolean $expected  Is $input valid
	 */
	public function testAlphaNumeric($input, $expected)
	{
		$this->assertSame(
			$expected,
			Validate::alpha_numeric($input)
		);
	}

	/**
	 * Provides test data for testAlphaDash
	 */
	public function providerAlphaDash()
	{
		return array(
			array('abcdef',     TRUE),
		    array('12345',      TRUE),
		    array('abcd1234',   TRUE),
		    array('abcd1234-',  TRUE),
		    array('abc123&^/-', FALSE)
		);
	}

	/**
	 * Tests Validate::alpha_dash()
	 *
	 * Checks whether a string consists of alphabetical characters, numbers, underscores and dashes only.
	 *
	 * @test
	 * @group kohana.validation.helpers
	 * @dataProvider providerAlphaDash
	 * @param string  $input          The string to test
	 * @param boolean $contains_utf8  Does the string contain utf8 specific characters
	 * @param boolean $expected       Is $input valid?
	 */
	public function testAlphaDash($input, $expected, $contains_utf8 = FALSE)
	{
		if( ! $contains_utf8)
		{
			$this->assertSame(
				$expected,
				Validate::alpha_dash($input)
			);
		}		

		$this->assertSame(
			$expected,
			Validate::alpha_dash($input, TRUE)
		);
	}

	/**
	 * DataProvider for the valid::decimal() test
	 */
	public function providerDecimal()
	{
		return array(
			array('45.1664',  3,    NULL, FALSE),
			array('45.1664',  4,    NULL, TRUE),
			array('45.1664',  4,    2,    TRUE),
		);
	}

	/**
	 * Tests Validate::decimal()
	 *
	 * @test
	 * @group kohana.validation.helpers
	 * @dataProvider providerDecimal
	 * @param string  $decimal  The decimal to validate
	 * @param integer $places   The number of places to check to
	 * @param integer $digits   The number of digits preceding the point to check
	 * @param boolean $expected Whether $decimal conforms to $places AND $digits
	 */
	public function testDecimal($decimal, $places, $digits, $expected)
	{
		$this->assertSame(
			$expected,
			Validate::decimal($decimal, $places, $digits),
			'Decimal: "'.$decimal.'" to '.$places.' places and '.$digits.' digits (preceeding period)'
		);
	}

	/**
	 * Provides test data for testDigit
	 * @return array
	 */
	public function providerDigit()
	{
		return array(
			array('12345',    TRUE),
		    array('10.5',     FALSE),
		    array('abcde',    FALSE),
		    array('abcd1234', FALSE)
		);
	}

	/**
	 * Tests Validate::digit()
	 *
	 * @test
	 * @group kohana.validation.helpers
	 * @dataProvider providerDigit
	 * @param mixed   $input     Input to validate
	 * @param boolean $expected  Is $input valid
	 */
	public function testDigit($input, $expected, $contains_utf8 = FALSE)
	{
		if( ! $contains_utf8)
		{
			$this->assertSame(
				$expected,
				Validate::digit($input)
			);
		}

		$this->assertSame(
			$expected,
			Validate::digit($input, TRUE)
		);

	}

	/**
	 * DataProvider for the valid::color() test
	 */
	public function providerColor()
	{
		return array(
			array('#000000', TRUE),
			array('#GGGGGG', FALSE),
			array('#AbCdEf', TRUE),
			array('#000', TRUE),
			array('#abc', TRUE),
			array('#DEF', TRUE),
			array('000000', TRUE),
			array('GGGGGG', FALSE),
			array('AbCdEf', TRUE),
			array('000', TRUE),
			array('DEF', TRUE)
		);
	}

	/**
	 * Tests Validate::color()
	 *
	 * @test
	 * @group kohana.validation.helpers
	 * @dataProvider providerColor
	 * @param string  $color     The color to test
	 * @param boolean $expected  Is $color valid
	 */
	public function testColor($color, $expected)
	{
		$this->assertSame(
			$expected,
			Validate::color($color)
		);
	}

	/**
	 * Provides test data for testCreditCard()
	 */
	public function providerCreditCard()
	{
		return array(
			array('4222222222222',    'visa',       TRUE),
		    array('4012888888881881', 'visa',       TRUE),
		    array('4012888888881881', NULL,         TRUE),
		    array('4012888888881881', array('mastercard', 'visa'), TRUE),
		    array('4012888888881881', array('discover', 'mastercard'), FALSE),
		    array('4012888888881881', 'mastercard', FALSE),
		    array('5105105105105100', 'mastercard', TRUE),
		    array('6011111111111117', 'discover',   TRUE),
		    array('6011111111111117', 'visa',       FALSE)
		);
	}

	/**
	 * Tests Validate::credit_card()
	 *
	 * @test
	 * @group kohana.validation.helpers
	 * @dataProvider  providerCreditCard()
	 * @param string  $number   Credit card number
	 * @param string  $type	    Credit card type
	 * @param boolean $expected
	 */
	public function testCreditCard($number, $type, $expected)
	{
		$this->markTestSkipped('Missing credit card config file');

		$this->assertSame(
			$expected,
			Validate::credit_card($number, $type)
		);
	}


	/**
	 * Provides test data for testEmail()
	 *
	 * @return array
	 */
	function providerEmail()
	{
		return array(
			array('foo', TRUE,  FALSE),
			array('foo', FALSE, FALSE),

			// RFC is less strict than the normal regex, presumably to allow
			//  admin@localhost, therefore we IGNORE IT!!!
			array('foo@bar', FALSE, FALSE),
			array('foo@bar.com', FALSE, TRUE),
			array('foo@bar.sub.com', FALSE, TRUE),
			array('foo+asd@bar.sub.com', FALSE, TRUE),
			array('foo.asd@bar.sub.com', FALSE, TRUE),
		);
	}

	/**
	 * Tests Validate::email()
	 *
	 * Check an email address for correct format.
	 *
	 * @test
	 * @group kohana.validation.helpers
	 * @dataProvider providerEmail
	 * @param string  $email   Address to check
	 * @param boolean $strict  Use strict settings
	 * @param boolean $correct Is $email address valid?
	 */
	function testEmail($email, $strict, $correct)
	{
		$this->assertSame(
			$correct,
			Validate::email($email, $strict)
		);
	}

	/**
	 * Returns test data for testEmailDomain()
	 *
	 * @return array
	 */
	function providerEmailDomain()
	{
		return array(
			array('google.com', TRUE),
			// Don't anybody dare register this...
			array('DAWOMAWIDAIWNDAIWNHDAWIHDAIWHDAIWOHDAIOHDAIWHD.com', FALSE)
		);
	}

	/**
	 * Tests Validate::email_domain()
	 *
	 * Validate the domain of an email address by checking if the domain has a
	 * valid MX record.
	 *
	 * Test skips on windows
	 *
	 * @test
	 * @group kohana.validation.helpers
	 * @dataProvider providerEmailDomain
	 * @param string  $email   Email domain to check
	 * @param boolean $correct Is it correct?
	 */
	function testEmailDomain($email, $correct)
	{
		if(substr(PHP_OS, 0, 3) != 'win' OR version_compare(PHP_VERSION, '5.3.0', '>='))
		{
			$this->assertSame(
				$correct,
				Validate::email_domain($email),
				'Make sure you\'re connected to the internet'
			);
		}
		else
		{
			$this->markTestSkipped('checkdnsrr() was not added on windows until PHP 5.3');
		}
	}

	/**
	 * Provides data for testExactLength()
	 *
	 * @return array
	 */
	function providerExactLength()
	{
		return array(
			array('somestring', 10, TRUE),
			array('anotherstring', 13, TRUE),
		);
	}

	/**
	 *
	 * Tests Validate::exact_length()
	 *
	 * Checks that a field is exactly the right length.
	 *
	 * @test
	 * @group kohana.validation.helpers
	 * @dataProvider providerExactLength
	 * @param string  $string  The string to length check
	 * @param integer $length  The length of the string
	 * @param boolean $correct Is $length the actual length of the string?
	 * @return bool
	 */
	function testExactLength($string, $length, $correct)
	{
		return $this->assertSame(
			$correct,
			Validate::exact_length($string, $length),
			'Reported string length is not correct'
		);
	}

	/**
	 * Tests Validate::factory()
	 *
	 * Makes sure that the factory method returns an instance of Validate lib
	 * and that it uses the variables passed
	 *
	 * @test
	 */
	function testFactoryMethodReturnsInstanceWithValues()
	{
		$values = array(
			'this'			=> 'something else',
			'writing tests' => 'sucks',
			'why the hell'	=> 'amIDoingThis',
		);

		$instance = Validate::factory($values);

		$this->assertTrue($instance instanceof Validate);

		$this->assertSame(
			$values,
			$instance->as_array()
		);
	}

	/**
	 * DataProvider for the valid::ip() test
	 * @return array
	 */
	public function providerIp()
	{
		return array(
			array('75.125.175.50',   FALSE, TRUE),
		    array('127.0.0.1',       FALSE, TRUE),
		    array('256.257.258.259', FALSE, FALSE),
		    array('255.255.255.255', FALSE, FALSE),
		    array('192.168.0.1',     FALSE, FALSE),
		    array('192.168.0.1',     TRUE,  TRUE)
		);
	}

	/**
	 * Tests Validate::ip()
	 *
	 * @test
	 * @group kohana.validation.helpers
	 * @dataProvider  providerIp
	 * @param string  $input_ip
	 * @param boolean $allow_private
	 * @param boolean $expected_result
	 */
	public function testIp($input_ip, $allow_private, $expected_result)
	{
		$this->assertEquals(
			$expected_result,
			Validate::ip($input_ip, $allow_private)
		);
	}

	/**
	 * Returns test data for testMaxLength()
	 *
	 * @return array
	 */
	function providerMaxLength()
	{
		return array(
			// Border line
			array('some', 4, TRUE),
			// Exceeds
			array('KOHANARULLLES', 2, FALSE),
			// Under
			array('CakeSucks', 10, TRUE)
		);
	}

	/**
	 * Tests Validate::max_length()
	 *
	 * Checks that a field is short enough.
	 * 
	 * @test
	 * @group kohana.validation.helpers
	 * @dataProvider providerMaxLength
	 * @param string  $string    String to test
	 * @param integer $maxlength Max length for this string
	 * @param boolean $correct   Is $string <= $maxlength
	 */
	function testMaxLength($string, $maxlength, $correct)
	{
		 $this->assertSame(
			$correct,
			Validate::max_length($string, $maxlength)
		);
	}

	/**
	 * Returns test data for testMinLength()
	 *
	 * @return array
	 */
	function providerMinLength()
	{
		return array(
			array('This is obviously long enough', 10, TRUE),
			array('This is not', 101, FALSE),
			array('This is on the borderline', 25, TRUE)
		);
	}

	/**
	 * Tests Validate::min_length()
	 *
	 * Checks that a field is long enough.
	 *
	 * @test
	 * @group kohana.validation.helpers
	 * @dataProvider providerMinLength
	 * @param string  $string     String to compare
	 * @param integer $minlength  The minimum allowed length
	 * @param boolean $correct    Is $string 's length >= $minlength
	 */
	function testMinLength($string, $minlength, $correct)
	{
		$this->assertSame(
			$correct,
			Validate::min_length($string, $minlength)
		);
	}

	/**
	 * Returns test data for testNotEmpty()
	 *
	 * @return array
	 */
	function providerNotEmpty()
	{
		return array(
			array(array(),		FALSE),
			array(Null,			FALSE),
			array('',			FALSE),
			array(0,			FALSE),
			array(array(NULL),	TRUE),
			array('0',			TRUE),
			array('Something',	TRUE),
		);
	}

	/**
	 * Tests Validate::not_empty()
	 *
	 * Checks if a field is not empty.
	 *
	 * @test
	 * @group kohana.validation.helpers
	 * @dataProvider providerNotEmpty
	 * @param mixed   $value  Value to check
	 * @param boolean $empty  Is the value really empty?
	 */
	function testNotEmpty($value, $empty)
	{
		return $this->assertSame(
			$empty,
			Validate::not_empty($value)
		);
	}

	/**
	 * DataProvider for the Validate::numeric() test
	 */
	public function providerNumeric()
	{
		return array(
			array('12345', TRUE),
		    array('10.5',  TRUE),
		    array('-10.5', TRUE),
		    array('10.5a', FALSE)
		);
	}

	/**
	 * Tests Validate::numeric()
	 *
	 * @test
	 * @group kohana.validation.helpers
	 * @dataProvider providerNumeric
	 * @param string  $input     Input to test
	 * @param boolean $expected  Whether or not $input is numeric
	 */
	public function testNumeric($input, $expected)
	{
		$this->assertSame(
			$expected,
			Validate::numeric($input)
		);
	}

	/**
	 * Provides test data for testPhone()
	 * @return array
	 */
	public function providerPhone()
	{
		return array(
			array('0163634840',       NULL, TRUE),
		    array('+27173634840',     NULL, TRUE),
		    array('123578',           NULL, FALSE),
			// Some uk numbers
			array('01234456778',      NULL, TRUE),
			array('+0441234456778',   NULL, FALSE),
			// Google UK case you're interested
			array('+44 20-7031-3000', array(12), TRUE),
			// BT Corporate
			array('020 7356 5000',	  NULL, TRUE),
		);
	}

	/**
	 * Tests Validate::phone()
	 *
	 * @test
	 * @group kohana.validation.helpers
	 * @dataProvider  providerPhone
	 * @param string  $phone     Phone number to test
	 * @param boolean $expected  Is $phone valid
	 */
	public function testPhone($phone, $lengths, $expected)
	{
		$this->assertSame(
			$expected,
			Validate::phone($phone, $lengths)
		);
	}

	/**
	 * DataProvider for the valid::range() test
	 */
	public function providerRange()
	{
		return array(
			array(1,  0,  2, TRUE),
			array(-1, -5, 0, TRUE),
			array(-1, 0,  1, FALSE),
			array(1,  0,  0, FALSE),
			array(2147483647, 0, 200000000000000, TRUE),
			array(-2147483647, -2147483655, 2147483645, TRUE)
		);
	}

	/**
	 * Tests Validate::range()
	 *
	 * Tests if a number is within a range.
	 *
	 * @test
	 * @group kohana.validation.helpers
	 * @dataProvider providerRange
	 * @param integer $number    Number to test
	 * @param integer $min       Lower bound
	 * @param integer $max       Upper bound
	 * @param boolean $expected  Is Number within the bounds of $min && $max
	 */
	public function testRange($number, $min, $max, $expected)
	{
		$this->AssertSame(
			$expected,
			Validate::range($number, $min, $max)
		);
	}

	/**
	 * Provides test data for testUrl()
	 *
	 * @return array
	 */
	public function providerUrl()
	{
		return array(
			array('http://google.com', TRUE),
			array('http://localhost', TRUE),
			array('ftp://my.server.com', TRUE),
			array('http://ww£.gooogle.com', FALSE)
		);
	}

	/**
	 * Tests Validate::url()
	 *
	 * @test
	 * @group kohana.validation.helpers
	 * @dataProvider providerUrl
	 * @param string  $url       The url to test
	 * @param boolean $expected  Is it valid?
	 */
	public function testUrl($url, $expected)
	{
		$this->assertSame(
			$expected,
			Validate::url($url)
		);
	}

	
}