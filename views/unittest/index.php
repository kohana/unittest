<?php defined('SYSPATH') or die('No direct script access.') ?>

<div id="select">
	<h1>PHPUnit for Kohana 3</h1>
	<div id="groups">
		<fieldset class="tests">
			<legend>Run Tests</legend>
			<?php echo Form::open($run_uri, array('method' => 'GET'));?>

			<?php echo Form::label('run_group', __('Run a test group')) ?>
			<?php echo Form::select('group', $groups, NULL, array('id' => 'run_group'));?>

			<?php if ($xdebug_enabled): ?>
				<?php echo Form::label('run_collect_cc', __('Calculate code coverage')) ?>
				<?php echo Form::checkbox('collect_cc', 1, TRUE, array('id' => 'run_collect_cc')) ?>

				<div depends_on="#run_collect_cc">
					<?php echo Form::label('run_use_whitelist', __('Use code coverage whitelist'));?>
					<?php echo Form::checkbox('use_whitelist', 1, TRUE, array('id' => 'run_use_whitelist')) ?>

					<div depends_on="#run_use_whitelist">
						<?php echo Form::label('run_whitelist', __('Only calculate coverage for files in selected modules')) ?>
						<?php echo Form::select('whitelist[]', $whitelistable_items, array(), array('id' => 'run_whitelist', 'multiple' => 'multiple')) ?>
					</div>

				</div>

			<?php endif ?>

			<?php echo Form::submit('submit', 'Run');?>
			<?php echo Form::close();?>
		</fieldset>

		<fieldset class="reports">
			<legend>Code Coverage Reports</legend>
			<?php if ( ! $xdebug_enabled): ?>
				<p><?php echo __('Xdebug needs to be installed to generate reports') ?></p>
			<?php else: ?>
				<?php echo Form::open($report_uri, array('method' => 'GET')) ?>

				<?php echo Form::label('cc_group', __('Generate report for')) ?>
				<?php echo Form::select('group', $groups, NULL, array('id' => 'cc_group'));?>

				<?php echo Form::label('report_archive', __('Download as archive?'));?>
				<?php echo Form::checkbox('archive', 1, FALSE, array('id' => 'report_archive')) ?>

				<?php echo Form::label('report_use_whitelist', __('Use code coverage whitelist'));?>
				<?php echo Form::checkbox('use_whitelist', 1, TRUE, array('id' => 'report_use_whitelist')) ?>

				<div depends_on="#report_use_whitelist">
					<?php echo Form::label('run_whitelist', __('Only calculate coverage for files in selected modules')) ?>
					<?php echo Form::select('whitelist[]', $whitelistable_items, array(), array('id' => 'run_whitelist', 'multiple' => 'multiple')) ?>
				</div>

				<?php echo Form::submit('submit', 'Run');?>
				<?php echo Form::close();?>
			<?php endif ?>
		</fieldset>
	</div>

	<h2>Useful links</h2>
	<ul>
		<li><a href="http://www.phpunit.de/manual/current/en/">PHPUnit Manual</a></li>
		<li><a href="http://github.com/kohana/unittest">Module README</a></li>
	</ul>
</div>
