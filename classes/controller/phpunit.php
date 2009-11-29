<?php defined('SYSPATH') or die ('No direct script access.');
/**
 * PHPUnit Kohana web based test runner
 * 
 * @author	Paul Banks
 * @package	Kohana PHPUnit
 */

class Controller_PHPUnit extends Controller_Template implements PHPUnit_Framework_TestListener
{
	/**
	 * Test Suite
	 * @var PHPUnit_Framework_TestSuite
	 */
	protected $_suite;


	protected $report_formats =	array
								(
									'PHPUnit_Util_Report'						=> 'HTML files (zipped)',
									'PHPUnit_Util_Log_CodeCoverage_XML_Clover'	=> 'Clover',
									'PHPUnit_Util_Log_CodeCoverage_XML_Source'	=> 'XML',
								);

	/**
	 * Results
	 * @var array
	 */
	protected $_results	=	array
							(
								'errors'		=> array(),
								'failures'		=> array(),
								'skipped'		=> array(),
								'incomplete'	=> array(),
							);
	
	/**
	 * Test result totals
	 * @var array
	 */
	protected $_totals = array
						(
							'tests'			=> 0,
							'passed'		=> 0,
							'errors'		=> 0,
							'failures'		=> 0,
							'skipped'		=> 0,
							'incomplete'	=> 0,
							'assertions'	=> 0,
						);
	
	/**
	 * Info about the current test running
	 * @var array
	 */
	protected $_current = array();
	
	/**
	 * Time for tests to run (seconds)
	 * @var float
	 */
	protected $_time = 0;

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
		$this->_suite = Kohana_Tests::suite();
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
			->set('groups', $this->get_groups_list());
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
		
		$group = $this->request->param('group');

		$use_group	=	($group === NULL ? array() : (array) $group);

		// Only collect code coverage if xdebug is enabled and user asked for it
		$collect_cc	=	(! empty($_GET['cc']) ? ((bool) $_GET['cc']) : FALSE)
						AND
						$this->xdebug_loaded;

		$result = $this->run($use_group, $collect_cc);

		if($result->getCollectCodeCoverageInformation())
		{
			$coverage = $result->getCodeCoverageInformation();

			$coverage_summary = PHPUnit_Util_CodeCoverage::getSummary($coverage);

			$executable = 0;
			$executed	= 0;

			foreach($coverage_summary as $file => $_lines)
			{
				$file_stats = PHPUnit_Util_CodeCoverage::getStatistics($coverage_summary, $file);
				$executable += $file_stats['locExecutable'];
				$executed   += $file_stats['locExecuted'];
			}

			$this->template->body->set('coverage', ($executed / $executable) * 100);
		}		
		
		// Show some results
		$this->template->body
			->set('group', $group)
			->set('groups', $this->get_groups_list())
			->set('time', $this->_nice_time())
			->set('report_uri', Route::get('phpunit')->uri(array_merge($this->request->param(), array('action' => 'report'))))
			->set('report_formats', $this->report_formats)
			->set('results', $this->_results)
			->set('totals', $this->_totals);		
	}

	/**
	 * Runs all tests in $groups
	 * 
	 * @param array $groups             Array of groups to test
	 * @param bool  $do_code_coverage   Should code coverage info be collected?
	 * @return PHPUnit_Framework_TestResult
	 */
	protected function run(array $groups = array(), $collect_code_coverage = FALSE)
	{
		// We attatch ourselves as an observer to collect stats and info about tests
		// see: add* start* end* methods
		$result = new PHPUnit_Framework_TestResult;
		$result->addListener($this);

		$result->collectCodeCoverageInformation((bool) $collect_code_coverage);

		// Run the tests.
		$this->_suite->run($result, FALSE, $groups);

		return $result;
	}

	/**
	 * Get the list of groups from the test suite, sorted with 'All groups' prefixed
	 * @return array Array of groups in the test suite
	 */
	protected function get_groups_list()
	{
		// Make groups aray suitable for drop down
		$groups = $this->_suite->getGroups();
		sort($groups);
		return array('' => 'All Groups') + array_combine($groups, $groups);	
	}
	
	public function addError(PHPUnit_Framework_Test $test, Exception $e, $time)
	{
		$this->_totals['errors']++;
		$this->_current['result'] = 'errors';
		$this->_current['message'] = $test->getStatusMessage();
		
	}
	
	public function addFailure(PHPUnit_Framework_Test $test, PHPUnit_Framework_AssertionFailedError $e, $time)
	{
		$this->_totals['failures']++;
		$this->_current['result'] = 'failures';
		$this->_current['message'] = $test->getStatusMessage();
	}
	
	public function addIncompleteTest(PHPUnit_Framework_Test $test, Exception $e, $time)
	{
		$this->_totals['incomplete']++;
		$this->_current['result'] = 'incomplete';
		$this->_current['message'] = $test->getStatusMessage();
	}
	
	public function addSkippedTest(PHPUnit_Framework_Test $test, Exception $e, $time)
	{
		$this->_totals['skipped']++;
		$this->_current['result'] = 'skipped';
		$this->_current['message'] = $test->getStatusMessage();
	}
	
	public function startTest(PHPUnit_Framework_Test $test)
	{
		$this->_current['name'] = $test->getName(FALSE);
		$this->_current['description'] = $test->toString();
		$this->_current['result'] = 'passed';
	}
	
	public function endTest(PHPUnit_Framework_Test $test, $time)
	{
		// Add totals
		$this->_totals['tests']++;
		$this->_totals['assertions'] += $test->getNumAssertions();
		
		// Handle passed tests
		if ($this->_current['result'] == 'passed')
		{
			// Add to total
			$this->_totals['passed']++;
		}
		else
		{
			// Add to results
			$this->_results[$this->_current['result']][] = $this->_current;
		}
		
		$this->_current = array();
		
		$this->_time += $time;
	}
	
	public function startTestSuite(PHPUnit_Framework_TestSuite $suite)
	{
	}
	
	public function endTestSuite(PHPUnit_Framework_TestSuite $suite)
	{
		// Parse test descriptions to make them look nicer
		foreach ($this->_results as $case => $test_results)
		{
			foreach ($test_results as $type => $result)
			{
				preg_match("/^(?:([a-z0-9_]+?)::)?([a-z0-9_]+)(?: with data set (#\d+ \(.*?\)))?/i", $result['description'], $m);
				
				$this->_results[$case][$type] += array(
					'class' => $m[1],
					'test' => $m[2],
					'data_set' => isset($m[3]) ? $m[3] : FALSE,
				);
			}
		}
	}
	
	protected function _nice_time()
	{
		$time = $this->_time;
		
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