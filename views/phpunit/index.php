<?php defined('SYSPATH') or die('No direct script access.') ?>

<div id="select">
	<h1>PHPUnit for Kohana 3</h1>
	<div id="groups">
		<fieldset class="tests">
			<legend>Run Tests</legend>
			<?php echo Form::open(Route::get('phpunit')->uri(array('action' => 'run')));?>
			<?php echo Form::label('run_group', __('Run a test group')); ?>
			<?php echo Form::select('group', $groups, NULL, array('id' => 'run_group'));?>
			<?php if($xdebug_enabled): ?>
				<?php echo Form::label('run_collect_cc', __('Calculate code coverage')); ?>
				<?php echo Form::checkbox('collect_cc', 1, TRUE, array('id' => 'run_collect_cc')); ?>
			<?php endif; ?>
			<?php echo Form::submit('submit', 'Run');?>
			<?php echo Form::close();?>
		</fieldset>
		<fieldset class="reports">
			<legend>Code Coverage Reports</legend>
			<?php if( ! $xdebug_enabled): ?>
				<p><?php echo __('Xdebug needs to be installed to generate reports'); ?></p>
			<?php else: ?>
				<?php echo Form::open(Route::get('phpunit')->uri(array('action' => 'report'))); ?>
				<?php echo Form::label('cc_group', __('Generate report for')); ?>
				<?php echo Form::select('group', $groups, NULL, array('id' => 'cc_group'));?>
				<?php echo Form::label('cc_format', __('Report format')); ?>
				<?php echo Form::select('format', $report_formats, array(), array('id' => 'cc_format')); ?>
				<?php echo Form::submit('submit', 'Run');?>
				<?php echo Form::close();?>
			<?php endif; ?>
		</fieldset>
	</div>
	
	<h2>Useful links</h2>
	<ul>
		<li><a href="http://www.phpunit.de/manual/current/en/">PHPUnit Manual</a></li>
		<li><a href="http://github.com/kohana/unittest">Kohana PHPUnit Module README</a></li>
	</ul>
</div>