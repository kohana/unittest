<?php

// If we're on the CLI then PHPUnit will already be loaded
if (class_exists('PHPUnit_Util_Filter', FALSE))
{
	Kohana_Tests::configure_environment();

	// Stop kohana from processing the request
	define('SUPPRESS_REQUEST', TRUE);
}
elseif (Kohana_Tests::enabled())
{
	// People shouldn't be running unit tests on their production server
	// so we assume that this _could_ be a web ui request on the dev server
	// and include phpunit so that modules realise that this could be a testing request
	require_once 'PHPUnit/Framework.php';
}

Route::set('unittest', 'unittest(/<action>)')
	->defaults(array(
		'controller' => 'unittest',
		'action'     => 'index',
	));
