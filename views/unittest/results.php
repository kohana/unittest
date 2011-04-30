<?php defined('SYSPATH') or die('No direct script access.') ?>
<div id="header" class="results">
	<fieldset id="results-options">
		<legend>Options</legend>
		<?php echo Form::open($run_uri, array('method' => 'get'));?>
		<?php echo Form::label('group', __('Switch Group')) ?>
		<?php echo Form::select('group', $groups, $group, array('id' => 'group'));?>
		<?php if ($xdebug_enabled): ?>
			<?php echo Form::label('collect_cc', __('Collect Coverage')) ?>
			<?php echo Form::checkbox('collect_cc', 1, isset($coverage), array('id' => 'collect_cc')) ?>
			<div depends_on="#collect_cc">
				<?php echo Form::label('use_whitelist', __('Use whitelist'));?>
				<?php echo Form::checkbox('use_whitelist', 1, $whitelist, array('id' => 'use_whitelist')) ?>

				<div depends_on="#use_whitelist">
					<?php echo Form::label('whitelist', __('Whitelisted modules')) ?>
					<?php echo Form::select('whitelist[]', $whitelistable_items, $whitelisted_items, array('id' => 'whitelist', 'multiple' => 'multiple')) ?>
				</div>
			</div>
		<?php endif ?>
		<?php echo Form::submit('run', 'Run');?>
		<?php echo Form::close();?>
	</fieldset>
	<h1><?php echo is_null($group) ? __('All Groups') : (__('Group').': ') ?> <?php echo $group ?></h1>
	<span class="time"><?php echo __('Time') ?>: <b><?php echo $time?></b></span>
	<span class="summary">
		<?php echo __('Tests') ?> <b><?php echo $totals['tests']?></b>,
		<?php echo __('Assertions') ?> <b><?php echo $totals['assertions']?></b>,
		<?php echo __('Failures') ?> <b><?php echo $totals['failures']?></b>,
		<?php echo __('Skipped') ?> <b><?php echo $totals['skipped']?></b>,
		<?php echo __('Errors') ?> <b><?php echo $totals['errors']?></b>.
	</span>
	<?php if ($xdebug_enabled AND isset($coverage)): ?>
	<span class="code_coverage">
		<?php $level_class = ($coverage > 75) ? 'excellent' : (($coverage > 35) ? 'ok' : 'terrible') ?>
		<?php
			echo __('Tests covered :percent of the :codebase',
				array
				(
					':percent'  => '<b class="'.$level_class.'">'.num::format($coverage, 2).'%</b>',
					':codebase' => empty($coverage_explanation) ? 'codebase' : ('<span title="'.$coverage_explanation.'" style="display:inline;">modules</span>'),
				)
			);
		?>,
		<?php echo HTML::anchor($report_uri, 'View') ?>
		or
		<?php echo HTML::anchor($report_uri.'&archive=1', 'Download') ?>
		the report
	</span>
	<?php endif ?>
</div>

<div id="results">
	<?php if ($totals['tests'] == 0): ?>
		<div class="big-message no-tests">No tests in group</div>
	<?php elseif ($totals['tests'] === $totals['passed']): ?>
		<div class="big-message all-pass"><?php echo $totals['tests']?> Tests Passed</div>
	<?php else: ?>

		<?php foreach ($results as $type => $tests):?>
			<?php if (count($tests) < 1):
				continue;
			endif ?>
			<?php $hide = ($type === 'skipped' OR $type === 'incomplete') ?>
			<div class="<?php echo $type?>-list">
				<h2 onclick="toggle('<?php echo $type?>');"><?php echo count($tests)?>
					<?php echo __(ucfirst(Inflector::singular($type, count($tests))))?>
					<span id="<?php echo $type?>-show" class="show">[<?php echo $hide ? __('show') : __('hide') ?>]</span></h2>
				<ol id="<?php echo $type?>-ol" class="<?php echo $hide ? 'hidden' : '' ?>">
					<?php foreach ($tests as $result): ?>
						<li>
							<span class="test-case"><?php echo $result['class']?>::</span><span class="test-name"><?php echo $result['test']?></span>
							<?php if ($result['data_set']) : ?>
							<span class="test-data-set"> <?php echo __('with data set')?> <span><?php echo $result['data_set']?></span></span>
							<?php endif ?>
							<span class="test-message"><?php echo htmlentities($result['message'])?></span>
						</li>
					<?php endforeach ?>
				</ol>
			</div>
		<?php endforeach;?>
	<?php endif ?>
</div>
