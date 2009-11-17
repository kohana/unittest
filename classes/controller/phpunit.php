<?php defined('SYSPATH') or die ('No direct script access.');
/**
 * PHPUnit Kohana web based test runner
 * 
 * @author	Paul Banks
 * @package	Kohana PHPUnit
 */

class Controller_PHPUnit extends Controller implements PHPUnit_Framework_TestListener
{
	/**
	 * Test Suite
	 * @var PHPUnit_Framework_TestSuite
	 */
	protected $_suite;
	
	/**
	 * Results
	 * @var array
	 */
	protected $_results;
	
	/**
	 * Test result totals
	 * @var array
	 */
	protected $_totals;
	
	/**
	 * Info about the current test running
	 * @var array
	 */
	protected $_current;
	
	/**
	 * Time for tests to run (seconds)
	 * @var float
	 */
	protected $_time;
	
	public function before()
	{
		$this->_suite = Kohana_Tests::suite();
		
		$this->_results = array(
			'errors' => array(),
			'failures' => array(),
			'skipped' => array(),
			'incomplete' => array(),
		);		
		
		$this->_totals = array(
			'tests' => 0,
			'passed' => 0,
			'errors' => 0,
			'failures' => 0,
			'skipped' => 0,
			'incomplete' => 0,
			'assertions' => 0,
		);
		
		$this->_current = array();
		
		$this->_time = 0;
	}
	
	public function action_index()
	{
		$this->request->response = View::factory('phpunit/index')
			->set('groups', $this->_get_groups_list());
	}
	
	public function action_run()
	{
		if (isset($_POST['group']))
		{
			// Redirect to correct URL for group
			$this->request->redirect(Route::get('phpunit')->uri(array('group'=>$_POST['group'], 'action'=>'run')));
		}
		
		$group = $this->request->param('group');
		
		// Create a test result and attach a SimpleTestListener
		// object as an observer to it.
		$result = new PHPUnit_Framework_TestResult;
		$result->addListener($this);
		
		//Set groups
		if (is_null($group))
		{
			$use_group = array();
		}
		else
		{
			$use_group = array($group);
		}
		 
		// Run the tests.
		$this->_suite->run($result, FALSE, $use_group);	
		
		// Show some results
		$this->request->response = View::factory('phpunit/results')
			->set('group', $group)
			->set('groups', $this->_get_groups_list())
			->set('time', $this->_nice_time())
			->set('results', $this->_results)
			->set('totals', $this->_totals);		
	}
	
	protected function _get_groups_list()
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