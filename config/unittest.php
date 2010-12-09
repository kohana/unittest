<?php defined('SYSPATH') or die('No direct script access.');

return array(

	// The only environment in which the web runner is allowed to run
	// You can run tests from phpunit cli command regardless of this setting
	// This can also be set to an array for multiple environments
	'environment' => Kohana::DEVELOPMENT,

	// This is the folder where we generate and zip all the reports for downloading
	// Needs to be readable and writable
	'temp_path' => Kohana::$cache_dir.'/unittest',

	// Path from DOCROOT (i.e. http://yourdomain/) to the folder where HTML cc reports can be published.
	// If you'd prefer not to allow users to do this then simply set the value to FALSE.
	// Example value of 'cc_report_path' would allow devs to see report at http://yourdomain/report/
	'cc_report_path' => 'report',

	// If you don't use a whitelist then only files included during the request will be counted
	// If you do, then only whitelisted items will be counted
	'use_whitelist' => TRUE,

	// Items to whitelist, only used in cli
	// Web runner ui allows user to choose which items to whitelist
	'whitelist' => array(

		// Should the app be whitelisted?
		// Useful if you just want to test your application
		'app' => TRUE,

		// Set to array(TRUE) to include all modules, or use an array of module names
		// (the keys of the array passed to Kohana::modules() in the bootstrap)
		// Or set to FALSE to exclude all modules
		'modules' => array(TRUE),

		// If you don't want the Kohana code coverage reports to pollute your app's,
		// then set this to FALSE
		'system' => TRUE,
	),

	// Does what it says on the tin
	// Blacklisted files won't be included in code coverage reports
	// If you use a whitelist then the blacklist will be ignored
	'use_blacklist' => FALSE,

	// List of individual files/folders to blacklist
	'blacklist' => array(
	),

	// A database connection that can be used when testing
	// This doesn't overwrite anything, tests will have to use this value manually
	'db_connection' => 'unittest',
);
