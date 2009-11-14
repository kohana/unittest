<?php defined('SYSPATH') or die('No direct script access.') ?>
<style type="text/css">
	#header {
		background-color: #263038;
		color: #fff;
		padding: 20px;
		font-family: sans-serif;
	}
	#results {
		font-family: sans-serif;
	}
	#results > div {
		margin-top: 10px;
		padding: 20px;
	}
	#results > div ol li{
		font-size: 1.1em;
		margin-bottom: 15px;
		font-weight: bold;
	}
	h1, h2 {
		margin: 0 0 15px;
	}
	#header span {
		display: block;
		color: #83919C;
		margin: 5px 0 0 0;
	}
	#header span b {
		color: #ddd;
	}
	#header form {
		color: #000;
		float: right;
		background: #E5EFF8;
		border: 4px solid #4D6171;
		padding: 20px;
		font-size: 1.2em;
	}
	#header form label {
		display: block;
	}
	div.failures-list {
		background-color: #fed;
	}
	div.errors-list {
		background-color: #ffc;
	}
	
	span.test-case {
		color: #83919C;
	}
	span.test-name {
		color: #222;
	}
	span.test-data-set {
		display: block;
		color: #666;
		font-weight: normal;
	}
	span.test-message {
		display: block;
		color: #444;
	}
	div.big-message {
		font-size: 2em;
		text-align: center;
	}
	div.all-pass {
		background-color: #E0FFE0;
		border: 3px solid #b0FFb0;
	}
	div.no-tests {
		background-color: #FFFFE0;
		border: 3px solid #FFFFb0;
	}
	span.show {
		font-size: 0.7em;
		font-weight: normal;
		color:#4D6171;
	}
	.hidden {
		display: none;
	}
</style>
<script type="text/javascript">
document.write('<style type="text/css"> .collapsed { display: none; } </style>');
function toggle(type)
{
	var elem = document.getElementById(type+'-ol');
	var plus = document.getElementById(type+'-show');

	if (elem.style && elem.style['display'])
		// Only works with the "style" attr
		var disp = elem.style['display'];
	else if (elem.currentStyle)
		// For MSIE, naturally
		var disp = elem.currentStyle['display'];
	else if (window.getComputedStyle)
		// For most other browsers
		var disp = document.defaultView.getComputedStyle(elem, null).getPropertyValue('display');

	// Toggle the state of the "display" style
	elem.style.display = disp == 'block' ? 'none' : 'block';
	plus.innerHTML = disp == 'block' ? '[<?php echo __('show'); ?>]' : '[<?php echo __('hide'); ?>]';
	return false;
}
</script>
<div id="header">
	
	<?php echo Form::open();?>
	<?php echo Form::label('group', __('Switch Group')); ?>
	<?php echo Form::select('group', $groups, $group, array('id' => 'group'));?>
	<?php echo Form::submit('run', 'Run');?>
	<?php echo Form::close();?>
	
	<h1><?php echo  (is_null($group) ? __('All Groups') : __('Group').': ')?> <?php echo $group?></h1>
	<span class="time"><?php echo __('Time') ?>: <b><?php echo $time?></b></span>
	<span class="summary">
		<?php echo __('Tests') ?> <b><?php echo $totals['tests']?></b>, 
		<?php echo __('Assertions') ?> <b><?php echo $totals['assertions']?></b>, 
		<?php echo __('Failures') ?> <b><?php echo $totals['failures']?></b>, 
		<?php echo __('Skipped') ?> <b><?php echo $totals['skipped']?></b>, 
		<?php echo __('Errors') ?> <b><?php echo $totals['errors']?></b>.</span>
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
			endif; ?>
			<?php $hide = ($type === 'skipped' OR $type === 'incomplete'); ?>
			<div class="<?php echo $type?>-list">
				<h2 onclick="toggle('<?php echo $type?>');"><?php echo count($tests)?> 
					<?php echo __(ucfirst(Inflector::singular($type, count($tests))))?>
					<span id="<?php echo $type?>-show" class="show">[<?php echo $hide ? __('show') : __('hide'); ?>]</span></h2>
				<ol id="<?php echo $type?>-ol" class="<?php echo $hide ? 'hidden' : ''; ?>">
					<?php foreach ($tests as $result): ?>
						<li>
							<span class="test-case"><?php echo $result['class']?>::</span><span class="test-name"><?php echo $result['test']?></span>
							<?php if ($result['data_set']) : ?>
							<span class="test-data-set"> <?php echo __('with data set')?> <span><?php echo $result['data_set']?></span></span>
							<?php endif; ?>
							<span class="test-message"><?php echo htmlentities($result['message'])?></span>
						</li>
					<?php endforeach; ?>
				</ol>
			</div>
		<?php endforeach;?>
	<?php endif; ?>
</div>
