<?php defined('SYSPATH') or die ('No direct script access.');
/**
 * PHPUnit Kohana web based test runner
 *
 * @package	   Unittest
 * @author     Kohana Team
 * @author     BRMatt <matthew@sigswitch.com>
 * @author	   Paul Banks
 * @copyright  (c) 2008-2009 Kohana Team
 * @license    http://kohanaphp.com/license
 */

class Controller_UnitTest extends Controller_Template
{
	/**
	 * The uri by which the report uri will be executed
	 * @var string
	 */
	protected $report_uri = '';

	/**
	 * The uri by which the run action will be executed
	 * @var string
	 */
	protected $run_uri = '';

	/**
	 * Is the XDEBUG extension loaded?
	 * @var boolean
	 */
	protected $xdebug_loaded = FALSE;

	/**
	 * Template
	 * @var string
	 */
	public $template = 'unittest/layout';

	/**
	 * Loads test suite
	 */
	public function before()
	{
		parent::before();

		if( ! Kohana_Tests::enabled())
		{
			// Pretend this is a normal 404 error...
			$this->status = 404;

			throw new Kohana_Request_Exception('Unable to find a route to match the URI: :uri',
				array(':uri' => $this->request->uri));
		}

		// Prevent the whitelist from being autoloaded, but allow the blacklist
		// to be laoded
		Kohana_Tests::configure_enviroment(FALSE);

		$this->config = Kohana::config('unittest');

		// This just stops some very very long lines
		$route = Route::get('unittest');
		$this->report_uri	= $route->uri(array('action' => 'report'));
		$this->run_uri		= $route->uri(array('action' => 'run'));

		// Switch used to disable cc settings
		$this->xdebug_loaded = extension_loaded('xdebug');
		$this->template->set_global('xdebug_enabled', $this->xdebug_loaded);
	}

	/**
	 * Handles index page for /unittest/ and /unittest/index/
	 */
	public function action_index()
	{
		$this->template->body = View::factory('unittest/index')
			->set('run_uri', $this->run_uri)
			->set('report_uri', $this->report_uri)
			->set('whitelistable_items', $this->get_whitelistable_items())
			->set('groups', $this->get_groups_list(Kohana_Tests::suite()))
			->set('report_formats', Kohana_PHPUnit::$report_formats);
	}

	/**
	 * Handles report generation
	 */
	public function action_report()
	{
		// Fairly foolproof
		if( ! class_exists('Archive'))
		{
			throw new Kohana_Exception('The Archive module is needed to package the reports');
		}

		// We don't want to use the HTML layout, we're sending the user 100111011100110010101100
		$this->auto_render = FALSE;

		$suite			= Kohana_Tests::suite();
		$temp_path		= rtrim($this->config->temp_path, '/').'/';
		$group			= (array) Arr::get($_GET, 'group', array());
		$report_format	= Arr::get($_POST, 'format', 'PHP_Util_Report');

		// Stop unittest from interpretting "all groups" as "no groups"
		if(empty($group) OR empty($group[0]))
		{
			$group = array();
		}

		if(Arr::get($_GET, 'use_whitelist', FALSE))
		{
			$this->whitelist(Arr::get($_GET, 'whitelist', array()));
		}

		$runner = new Kohana_PHPUnit($suite);

		// $report is the actual directory of the report,
		// $folder is the name component of directory
		list($report, $folder) = $runner->generate_report($group, $temp_path, $report_format);

		$archive = Archive::factory('zip');

		// TODO: Include the test results?
		$archive->add($report, 'report', TRUE);

		$filename = $folder.'.zip';

		$archive->save($temp_path.$filename);

		// It'd be nice to clear up afterwards but by deleting the report dir we corrupt the archive
		// And once the archive has been sent to the user Request stops the script so we can't delete anything
		// It'll be up to the user to delete files periodically
		$this->request->send_file($temp_path.$filename, $filename);
	}

	/**
	 * Handles test running interface
	 */
	public function action_run()
	{
		$this->template->body = View::factory('unittest/results');

		// Get the test suite and work out which groups we're testing
		$suite	= Kohana_Tests::suite();
		$group	= (array) Arr::get($_GET, 'group', array());


		// Stop phpunit from interpretting "all groups" as "no groups"
		if(empty($group) OR empty($group[0]))
		{
			$group = array();
		}

		// Only collect code coverage if the user asked for it
		$collect_cc	=	(bool) Arr::get($_GET, 'collect_cc', FALSE);
		
		if($collect_cc AND Arr::get($_GET, 'use_whitelist', FALSE))
		{
			$whitelist = $this->whitelist(Arr::get($_GET, 'whitelist', array()));
		}

		$runner = new Kohana_PHPUnit($suite);

		try
		{
			$runner->run($group, $collect_cc);
			
			if($collect_cc)
			{
				$this->template->body->set('coverage', $runner->calculate_cc_percentage());
			}
			
			if(isset($whitelist))
			{
				$this->template->body->set('coverage_explanation', $this->nice_whitelist_explanation($whitelist));
			}
		}
		catch(Kohana_Exception $e)
		{
			// Code coverage is not allowed, possibly xdebug disabled?
			// TODO: Tell the user this?
			$runner->run($group);
		}

		// Show some results
		$this->template->body
			->set('results', $runner->results)
			->set('totals',  $runner->totals)
			->set('time',    $this->nice_time($runner->time))

			// Sets group to the currently selected group, or default all groups
			->set('group',  Arr::get($this->get_groups_list($suite), reset($group), 'All groups'))
			->set('groups', $this->get_groups_list($suite))

			->set('report_uri',     $this->report_uri.url::query())
			->set('report_formats', Kohana_PHPUnit::$report_formats)
			
			// Whitelist related stuff
			->set('whitelistable_items', $this->get_whitelistable_items())
			->set('whitelisted_items',   isset($whitelist) ? array_keys($whitelist) : array())
			->set('whitelist',           ! empty($whitelist));
	}

	/**
	 * Get the list of groups from the test suite, sorted with 'All groups' prefixed
	 * 
	 * @return array Array of groups in the test suite
	 */
	protected function get_groups_list($suite)
	{
		// Make groups aray suitable for drop down
		$groups = $suite->getGroups();
		sort($groups);
		return array('' => 'All Groups') + array_combine($groups, $groups);	
	}

	/**
	 * Gets a list of items that are whitelistable
	 *
	 * @return array
	 */
	protected function get_whitelistable_items()
	{
		static $whitelist;

		if(count($whitelist))
		{
			return $whitelist;
		}
		
		$whitelist = array();

		$whitelist['k_app'] = 'Application';

		$k_modules = array_keys(Kohana::modules());

		$whitelist += array_map('ucfirst', array_combine($k_modules, $k_modules));

		$whitelist['k_sys'] = 'Kohana Core';

		return $whitelist;
	}

	/**
	 * Whitelists a specified set of modules specified by the user
	 * 
	 * @param array $modules
	 */
	protected function whitelist(array $modules)
	{
		$k_modules = Kohana::modules();
		$whitelist = array();

		// Make sure our whitelist is valid
		foreach($modules as $item)
		{
			if(isset($k_modules[$item]))
			{
				$whitelist[$item] = $k_modules[$item];
			}
			else if($item === 'k_app')
			{
				$whitelist[$item] = APPPATH;
			}
			else if($item === 'k_sys')
			{
				$whitelist[$item] = SYSPATH;
			}
		}

		if(count($whitelist))
		{
			Kohana_Tests::whitelist($whitelist);
		}

		return $whitelist;
	}

	/**
	 * Prettifies the list of whitelisted modules
	 *
	 * @param array Array of whitelisted items
	 * @return string
	 */
	protected function nice_whitelist_explanation(array $whitelist)
	{
		$items = array_intersect_key($this->get_whitelistable_items(), $whitelist);

		return implode(', ', $items);
	}
	
	protected function nice_time($time)
	{		
		$parts = array();
		
		if ($time > DATE::DAY)
		{
			$parts[] = floor($time/DATE::DAY).'d';
			$time = $time % DATE::DAY;
		}
		
		if ($time > DATE::HOUR)
		{
			$parts[] = floor($time/DATE::HOUR).'h';
			$time = $time % DATE::HOUR;
		}
		
		if ($time > DATE::MINUTE)
		{
			$parts[] = floor($time/DATE::MINUTE).'m';
			$time = $time % DATE::MINUTE;
		}
		
		if ($time > 0)
		{
			$parts[] = round($time, 1).'s';
		}
		
		return implode(' ', $parts);
	}
} // End Controller_PHPUnit
