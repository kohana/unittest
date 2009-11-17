<?php

if( ! class_exists('Kohana'))
{
	die('Please include the kohana bootstrap file (see README.markdown)');
}

if($file = Kohana::find_file('classes', 'kohana/tests'))
{
	require_once $file;
}
else
{
	die('Could not include the test suite');
}