<?php

// If we're on the CLI then PHPUnit will already be loaded
if(class_exists('PHPUnit_Util_Filter', FALSE))
{
	Kohana_Tests::configure_enviroment();

	// Stop kohana from processing the request
	define('SUPPRESS_REQUEST', TRUE);
}
else if(Kohana_Tests::enabled())
{
	// People shouldn't be running unit tests on their production server
	// so we assume that this /could/ be a web ui request on the dev server
	// and include phpunit so that modules can add specific files to the blacklist
	require_once 'PHPUnit/Framework.php';
}

Route::set('unittest', '(<controller>(/<action>(/<id>)))')
	->defaults(array(
		'action'     => 'index',
	));
