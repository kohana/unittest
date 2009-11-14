<?php defined('SYSPATH') or die('No direct script access.') ?>
<style type="text/css">
	#select {
		font-family: sans-serif;
		border: 2px solid black;
		padding: 30px;
		margin: 100px;
	}
	h1, h2 {
		margin-top: 0;
	}
	label {
		display: block;
		font-size: 1.2em;
		margin-bottom: 10px;
	}
	form {
		color: #000;
		background: #E5EFF8;
		border: 4px solid #4D6171;
		padding: 20px;
		font-size: 1.2em;
	}
	li {
		margin-bottom: 5px;
	}
</style>

<div id="select">
	<h1>PHPUnit for Kohana 3</h1>
	<?php echo Form::open(Route::get('phpunit')->uri(array('action' => 'run')));?>
	<?php echo Form::label('group', __('Run a test group')); ?>
	<?php echo Form::select('group', $groups, NULL, array('id' => 'group'));?>
	<?php echo Form::submit('run', 'Run');?>
	<?php echo Form::close();?>
	
	<h2>Useful links</h2>
	<ul>
		<li><a href="http://www.phpunit.de/manual/current/en/">PHPUnit Manual</a></li>
		<li><a href="http://github.com/banks/kohana-phpunit">Kohana PHPUnit Module README</a></li>
	</ul>
</div>