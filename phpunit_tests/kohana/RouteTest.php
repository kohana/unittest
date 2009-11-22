<?php

/**
 * Description of RouteTest
 *
 * @group kohana
 * @author matt
 */
class Kohana_RouteTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Route::matches() should return false if the route doesn't match against a uri
	 *
	 * @test
	 */
	function testMatchesReturnsFalseOnFailure()
	{
		$route = new Route('projects/(<project_id>/(<controller>(/<action>(/<id>))))');

		$this->assertSame(FALSE, $route->matches('apple/pie'));
	}

	/**
	 * Route::matches() should return an array of parameters when a match is made
	 * An parameters that are not matched should not be present in the array of matches
	 * 
	 * @test
	 */
	function testMatchesReturnsArrayOfParametersOnSuccessfulMatch()
	{
		$route = new Route('(<controller>(/<action>(/<id>)))');
		$route->defaults(array(
			'controller' => 'welcome',
			'action'     => 'index'
		));

		$matches = $route->matches('welcome/index');

		$this->assertType('array', $matches);
		$this->assertArrayHasKey('controller', $matches);
		$this->assertArrayHasKey('action', $matches);
		$this->assertArrayNotHasKey('id', $matches);
		$this->assertSame(2, count($matches));
		$this->assertSame('welcome', $matches['controller']);
		$this->assertSame('index', $matches['action']);
	}
}