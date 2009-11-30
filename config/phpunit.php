<?php


// We use 'use_' switches to make it easier to turn on/off certain settings
return array
(
	// This is the folder where we generate and zip all the reports for downloading
	// Needs to be readable and writable
	'temp_path'				=> APPPATH.'cache/phpunit/',

	// Should the code coverage checkbox be selected by default for the web ui
	'coverage_selected'		=> TRUE,

	// If you don't use a whitelist then by default everything that isn't blacklisted is included
	// If you do, then only whitelisted items are included
	'use_whitelist'		=> TRUE,

	'whitelist'	=>	array
					(
						// Should the app be whitelisted?
						// Useful if you just want to test your application
						'app'		=> TRUE,
						// Set to TRUE to include all modules, or use an array of module names
						// (the keys of the array passed to Kohana::modules() in the bootstrap)
						'modules'	=> TRUE,
						// If you don't want the kohana code coverage reports to pollute your app's
						// Then set this to true
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