<?php

function unittest_autoload($class)
{
	$file = str_replace('_', '/', $class);
	
	if($file = Kohana::find_file('tests', $file))
	{
		require_once $file;
	}
}

if(class_exists('PHPUnit_Util_Filter'))
{
    restore_exception_handler();
    restore_error_handler();
	
	spl_autoload_register('unittest_autoload');

	// Only supress request for CLI mode
	if (Kohana::$is_cli)
	{
		define('SUPPRESS_REQUEST', TRUE);
	}
}

// Make sure the PHPUnit classes are available
require_once "PHPUnit/Framework.php";

// Add route for web runner
Route::set('phpunit', 'phpunit(/<action>(/<group>))', array('group' => '[a-zA-Z0-9\.-_]+'))
	->defaults(array(
		'action' => 'index',
		'controller' => 'phpunit',
		'group' => NULL,
	));
