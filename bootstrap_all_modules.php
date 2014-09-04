<?php

include_once('bootstrap.php');

// Enable all modules we can find
$modules_iterator = new DirectoryIterator(MODPATH);

$modules = array();

foreach ($modules_iterator as $module)
{
	if ($module->isDir() AND ! $module->isDot())
	{
		$modules[$module->getFilename()] = MODPATH.$module->getFilename();
	}
}

// Add to modules, ensuring that the system 'module' comes at the very end
$modules = Kohana::modules() + $modules;
unset($modules['core']);
$modules['core'] = SYSPATH;
Kohana::modules($modules);
Kohana::init_modules();
unset ($modules_iterator, $modules, $module);
