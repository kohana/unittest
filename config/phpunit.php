<?php

return array
(
	// The only enviroment in which the web runner is allowed to run
	// The CLI can be run reguardless
	'enviroment'		=> 'development',

	// This is the folder where we generate and zip all the reports for downloading
	// Needs to be readable and writable
	'temp_path'				=> Kohana::$cache_dir.'/phpunit',

	// If you don't use a whitelist then only files included during the request will be counted
	// If you do, then only whitelisted items will be counted
	'use_whitelist'		=> TRUE,

	// Items to whitelist, only used in cli
	// Web runner ui allows user to choose which items to whitelist
	'whitelist'	=>	array
					(
						// Should the app be whitelisted?
						// Useful if you just want to test your application
						'app'		=> TRUE,
						// Set to array(TRUE) to include all modules, or use an array of module names
						// (the keys of the array passed to Kohana::modules() in the bootstrap)
						// Or set to FALSE to exclude all modules
						'modules'	=> array(TRUE),
						// If you don't want the kohana code coverage reports to pollute your app's,
						// then set this to FALSE
						'system'	=> TRUE,
					),
	
	// Does what it says on the tin
	// Blacklisted files won't be included in code coverage reports
	'use_blacklist'		=> FALSE,

	'blacklist'	=>	array
					(
						// List of individual files / folders to blacklist
					),

);