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
	 * @covers Route
	 */
	function testMatchesReturnsArrayOfParametersOnSuccessfulMatch()
	{
		$route = new Route('(<controller>(/<action>(/<id>)))');
		
		$matches = $route->matches('welcome/index');

		$this->assertType('array', $matches);
		$this->assertArrayHasKey('controller', $matches);
		$this->assertArrayHasKey('action', $matches);
		$this->assertArrayNotHasKey('id', $matches);
		$this->assertSame(2, count($matches));
		$this->assertSame('welcome', $matches['controller']);
		$this->assertSame('index', $matches['action']);
	}

	/**
	 * Defaults specified with defaults() should be used if their values aren't
	 * present in the uri
	 *
	 * @test
	 * @covers Route
	 */
	function testDefaultsAreUsedIfParamsArentSpecified()
	{
		$route = new Route('(<controller>(/<action>(/<id>)))');
		$route->defaults(array('controller' => 'welcome', 'action' => 'index'));

		$matches = $route->matches('');

		$this->assertType('array', $matches);
		$this->assertArrayHasKey('controller', $matches);
		$this->assertArrayHasKey('action', $matches);
		$this->assertArrayNotHasKey('id', $matches);
		$this->assertSame(2, count($matches));
		$this->assertSame('welcome', $matches['controller']);
		$this->assertSame('index', $matches['action']);
	}

	/**
	 * This tests that routes with required parameters will not match uris without them present
	 * 
	 * @test
	 * @covers Route
	 */
	function testRequiredParametersAreNeeded()
	{
		$route = new Route('admin(/<controller>(/<action>(/<id>)))');

		$this->assertFalse($route->matches(''));

		$matches = $route->matches('admin');

		$this->assertType('array', $matches);

		$matches = $route->matches('admin/users/add');

		$this->assertType('array', $matches);
		$this->assertSame(2, count($matches));
		$this->assertArrayHasKey('controller', $matches);
		$this->assertArrayHasKey('action', $matches);
	}

	/**
	 * This tests the reverse routing returns the uri specified in the route
	 * if it's a static route
	 *
	 * A static route is a route without any parameters
	 *
	 * @test
	 * @covers Route::uri
	 */
	function testReverseRoutingReturnsRoutesURIIfRouteIsStatic()
	{
		$route = new Route('info/about_us');

		$this->assertSame('info/about_us', $route->uri(array('some' => 'random', 'params' => 'to confuse')));
	}
}