<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
		<title>PHPUnit for Kohana</title>

		<style type="text/css">
			#select {
				font-family: sans-serif;
				border: 2px solid black;
				padding: 20px;
				margin: 40px 80px;
			}
			#select #groups {
				overflow: auto;
				margin-bottom: 25px;
			}
			#header {
				background-color: #263038;
				color: #fff;
				padding: 20px;
				font-family: sans-serif;
				overflow: auto;
			}
			#header span {
				display: block;
				color: #83919C;
				margin: 5px 0 0 0;
			}
			#header span b {
				color: #ddd;
			}
			#header span.code_coverage .excellent {
				color: #13CC1C;
			}
			#header span.code_coverage .ok {
				color: #448BFD;
			}
			#header span.code_coverage .terrible {
				color: #FF0A0A;
			}

			#header fieldset form label {
				display: block;
			}

			#header a {
				color: #4e7aa0;
			}

			li {
				margin-bottom: 5px;
			}

			fieldset {
				color: #000;
				background: #E5EFF8;
				border: 4px solid #4D6171;
				padding: 20px 20px 0px;
				font-size: 1.2em;
				width: 43%;
				display: block;
				-moz-border-radius: 2px;
			}

			fieldset legend {
				padding: 5px;
				-moz-border-radius: 2px;
				color: #FEFEFE;
				background: #4D6171;
			}
			form {
				display: inline;
			}
			fieldset form {
				display: block;
			}
			fieldset#results-options {
				width: 38%;
				float:right;
			}
			fieldset form label {
				display: block;
			}

			fieldset select {
				width: 45%;
			}

			fieldset#results-options form label {
				clear: left;
				float: left;
				width: 43%;
			}

			fieldset#results-options form input {
				display: block;
				float:left;
			}

			fieldset#results-options form input[type="submit"] {
				float: none;
				clear: both;
			}

			fieldset#results-options form select {
				float: left;
			}

			fieldset.tests {
				float: left;
			}

			fieldset.tests legend {
				background: #4D6171;

			}

			fieldset.reports {
				float: right;
				background: #FC817B;
				border-color: #D02820;
			}

			fieldset.reports legend {
				background: #D02820;
			}

			fieldset form input[type="submit"] {
				margin-top: 15px;
				display: block;
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
			div.failures-list {
				background-color: #ffcccc;
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
			span[title]{
				border-bottom: 1px dashed #83919C;
			}
		</style>

	</head>
	<body>
		<?php echo $body ?>

		<?php echo HTML::script('http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js') ?>
		<script type="text/javascript">
			$(document).ready(function(){

				// Using our own attribute is a little messy but gets the job done
				// this isn't very important code anyway...
				$('[depends_on]').each(function(){
					var dependent = $(this);
					var controller = $(dependent.attr('depends_on'));

					// We do this in the loop so that it can access dependent
					// Javascript scopes are slightly crazy
					var toggler = function(){
						if ($(this).attr('checked')){
							dependent.show();
						} else {
							dependent.hide();
						}
					}

					// Register the toggler
					controller.change(toggler);

					// And degrade nicely...
					if ( ! controller.attr('checked')){
						dependent.hide();
					}

				});

			});

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
				plus.innerHTML = disp == 'block' ? '[<?php echo __('show') ?>]' : '[<?php echo __('hide') ?>]';
				return false;
			}
		</script>

	</body>
</html>
