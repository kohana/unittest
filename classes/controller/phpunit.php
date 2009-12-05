<?php defined('SYSPATH') or die ('No direct script access.');
/**
 * PHPUnit Kohana web based test runner
 * 
 * @author	Paul Banks
 * @package	Kohana PHPUnit
 */

class Controller_PHPUnit extends Controller_Template
{
	/**
	 * Test Suite
	 * @var PHPUnit_Framework_TestSuite
	 */
	protected $suite;

	/**
	 * Is the XDEBUG extension loaded?
	 * @var boolean
	 */
	protected $xdebug_loaded = FALSE;

	/**
	 * Template
	 * @var string
	 */
	public $template = 'phpunit/layout';

	/**
	 * Loads test suite
	 */
	public function before()
	{
		$this->suite = Kohana_Tests::suite();
		$this->xdebug_loaded = extension_loaded('xdebug');

		parent::before();

		$this->template->set_global('xdebug_enabled', $this->xdebug_loaded);
	}

	/**
	 * Handles index page for /phpunit/ and /phpunit/index/
	 */
	public function action_index()
	{
		$this->template->body = View::factory('phpunit/index')
			->set('groups', $this->get_groups_list())
			->set('report_formats', Kohana_PHPUnit::$report_formats);
	}

	/**
	 * Handles report generation
	 */
	public function action_report()
	{
		if( ! class_exists('Archive'))
		{
			throw new Kohana_Exception('The Archive module is needed to package the reports');
		}

		// We don't want to use the HTML layout, we're sending the user 100111011100110010101100
		$this->auto_render = FALSE;

		$config		= Kohana::config('phpunit');
		$temp_path	= rtrim($config->temp_path, '/').'/';
		$groups		= (array) Arr::get($_POST, 'group', array());

		$runner = new Kohana_PHPUnit($this->suite);

		list($report, $folder) = $runner->generate_report($groups, $temp_path, Arr::get($_GET, 'format', 'PHP_Util_Report'));

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
		if ( ! empty($_POST))
		{
			$uri = Route::get('phpunit')->uri(array('group'=> Arr::get($_POST, 'group'), 'action'=>'run'));

			if( ! empty($_POST['collect_cc']))
			{
				$uri .= url::query(array('cc' => '1'));
			}

			// Redirect to correct URL for group
			$this->request->redirect($uri);
		}

		$this->template->body = View::factory('phpunit/results');
		
		$group	= $this->request->param('group');
		$group	= ($group === NULL ? array() : (array) $group);

		// Only collect code coverage if xdebug is enabled and user asked for it
		$collect_cc	=	(! empty($_GET['cc'])) AND ((bool) $_GET['cc']);
		
		$runner = new Kohana_PHPUnit($this->suite);

		try
		{
			$runner->run($group, $collect_cc);
		}
		catch(Kohana_Exception $e)
		{
			// Code coverage is not allowed
			// TODO: Tell the user this?
			$runner->run($group);
		}
		
		// Show some results
		$this->template->body
			->set('group', $group)
			->set('groups', $this->get_groups_list())
			->set('time', $this->nice_time($runner->time))
			->set('report_uri', Route::get('phpunit')->uri(array_merge($this->request->param(), array('action' => 'report'))))
			->set('report_formats', Kohana_PHPUnit::$report_formats)
			->set('coverage', $runner->calculate_cc_percentage())
			->set('results', $runner->results)
			->set('totals', $runner->totals);
	}

	/**
	 * Get the list of groups from the test suite, sorted with 'All groups' prefixed
	 * @return array Array of groups in the test suite
	 */
	protected function get_groups_list()
	{
		// Make groups aray suitable for drop down
		$groups = $this->suite->getGroups();
		sort($groups);
		return array('' => 'All Groups') + array_combine($groups, $groups);	
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
			$parts[] = floor($time/DATE::MIN).'m';
			$time = $time % DATE::MIN;
		}
		
		if ($time > 0)
		{
			$parts[] = round($time, 1).'s';
		}
		
		return implode(' ', $parts);
	}
} // End Controller_PHPUnit