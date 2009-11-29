<?php

// TODO: auto-detect this
$phpunit_path = DOCROOT.'../phpunit/';

return array
(
	// This is the folder where we generate and zip all the reports for downloading
	// Needs to be readable and writable
	'temp_path'				=> $phpunit_path.'temp',

);