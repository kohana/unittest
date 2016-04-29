<?php defined('SYSPATH') or die('No direct script access.');

/**
 * TestCase for testing in integration with Request and Response objects.
 *
 * This is inspired from the Play framework in which a test case provides
 * primitives to test Response objects.
 *
 * Testing a real application is feasible by self-requesting endpoints and
 * asserting pre-conditions and post-conditions.
 *
 * @package    Kohana/UnitTest
 * @author     Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com>
 * @copyright  (c) 2008-2009 Kohana Team
 * @license    http://kohanaphp.com/license
 */
abstract class Kohana_Unittest_Integration_TestCase extends Unittest_TestCase {

	/**
	 * Assert that a given Response status match the expected value.
	 *
	 * @param integer  $code     expected status code
	 * @param Response $response Response object
	 * @param string   $message  message displayed if the test fail
	 */
	public function assertStatus($code, Response $response, $message = NULL)
	{
		if ($message === NULL)
		{
			$message = $response->body();
		}

		$this->assertEquals($code, $response->status(), $message);
	}

	public function assertOk(Response $response, $message = NULL)
	{
		$this->assertStatus(200, $response, $message);
	}

	public function assertPermanentRedirection($location, Response $response, $message = NULL)
	{
		$this->assertStatus(301, $response, $message);
		$this->assertEquals($location, $response->headers('Location'));
	}

	public function assertTemporaryRedirection($location, Response $response, $message = NULL)
	{
		$this->assertStatus(302, $response, $message);
		$this->assertEquals($location, $response->headers('Location'));
	}

	public function assertUnauthorized(Response $response, $message = NULL)
	{
		$this->assertStatus(401, $response, $message);
	}

	public function assertForbidden(Response $response, $message = NULL)
	{
		$this->assertStatus(403, $response, $message);
	}

	public function assertNotFound(Response $response, $message = NULL)
	{
		$this->assertStatus(404, $response, $message);
	}

	public function assertServiceUnavailable(Response $response, $message = NULL)
	{
		$this->assertStatus(503, $response, $message);
	}

}
