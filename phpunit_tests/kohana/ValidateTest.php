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
	 * @group kohana.validate.helpers
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
	 * @group kohana.validate.helpers
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
				Validate::email_domain($email)
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
	 * @group kohana.validate.helpers
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
	 * @group kohana.validate.helpers
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
	 * @group kohana.validate.helpers
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
	 * @group kohana.validate.helpers
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
	 * @group kohana.validate.helpers
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
	 * Provides test data for testPhone()
	 * @return array
	 */
	public function providerPhone()
	{
		return array(
			array('0163634840',   TRUE),
		    array('+27173634840', TRUE),
		    array('123578',       FALSE),
			// Some uk numbers
			array('01234456778',  TRUE),
			array('+0441234456778',  FALSE),
			// Google UK case you're interested
			array('+44 20-7031-3000', TRUE),
			// BT Corporate
			array('020 7356 5000',	  TRUE),
		);
	}

	/**
	 * Tests Validate::phone()
	 *
	 * @test
	 * @group kohana.validate.helpers
	 * @dataProvider  providerPhone
	 * @param string  $phone     Phone number to test
	 * @param boolean $expected  Is $phone valid
	 */
	public function testPhone($phone, $expected)
	{
		$this->assertSame(
			$expected,
			Validate::phone($phone)
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
}