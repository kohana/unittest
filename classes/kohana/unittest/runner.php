<?php
/**
 * PHPUnit test runner for kohana
 *
 * @package    Kohana/Unittest
 * @author     Kohana Team
 * @author     BRMatt <matthew@sigswitch.com>
 * @author	   Paul Banks
 * @copyright  (c) 2008-2009 Kohana Team
 * @license    http://kohanaphp.com/license
 */
Class Kohana_Unittest_Runner implements PHPUnit_Framework_TestListener
{
	/**
	 * Results
	 * @var array
	 */
	protected $results = array(
		'errors'     => array(),
		'failures'   => array(),
		'skipped'    => array(),
		'incomplete' => array(),
	);

	/**
	 * Test result totals
	 * @var array
	 */
	protected $totals = array(
		'tests'      => 0,
		'passed'     => 0,
		'errors'     => 0,
		'failures'   => 0,
		'skipped'    => 0,
		'incomplete' => 0,
		'assertions' => 0,
	);

	/**
	 * Info about the current test running
	 * @var array
	 */
	protected $current = array();

	/**
	 * Time for tests to run (seconds)
	 * @var float
	 */
	protected $time = 0;

	/**
	 * Result collector
	 * @var PHPUnit_Framework_TestResult
	 */
	protected $result = NULL;

	/**
	 * the test suite to run
	 * @var PHPUnit_Framework_TestSuite
	 */
	protected $suite = NULL;

	/**
	 * Constructor
	 *
	 * @param PHPUnit_Framework_TestSuite $suite    The suite to test
	 * @param PHPUnit_Framework_TestResult $result  Optional result object to use
	 */
	function __construct(PHPUnit_Framework_TestSuite $suite, PHPUnit_Framework_TestResult $result = NULL)
	{
		if ($result === NULL)
		{
			$result = new PHPUnit_Framework_TestResult;
		}

		$result->addListener($this);

		$this->suite  = $suite;
		$this->result = $result;
	}

	/**
	 * Magic getter to allow access to member variables
	 *
	 * @param string $var Variable to get
	 * @return mixed
	 */
	function __get($var)
	{
		return $this->$var;
	}

	/**
	 * Calcualtes stats for each file covered by the code testing
	 *
	 * Each member of the returned array is formatted like so:
	 *
	 * <code>
	 * array(
	 *     'coverage'      => $coverage_percent_for_file,
	 *     'loc'           => $lines_of_code,
	 *     'locExecutable' => $lines_of_executable_code,
	 *     'locExecuted'   => $lines_of_code_executed
	 *   );
	 * </code>
	 *
	 * @return array Statistics for code coverage of each file
	 */
	public function calculate_cc()
	{
		if ($this->result->getCollectCodeCoverageInformation())
		{
			$coverage = $this->result->getCodeCoverageInformation();

			$coverage_summary = PHPUnit_Util_CodeCoverage::getSummary($coverage);

			$stats = array();

			foreach ($coverage_summary as $file => $_lines)
			{
				$stats[$file] = PHPUnit_Util_CodeCoverage::getStatistics($coverage_summary, $file);
			}

			return $stats;
		}

		return FALSE;
	}

	/**
	 * Calculates the percentage code coverage information
	 *
	 * @return boolean|float FALSE if cc is not enabled, float for coverage percent
	 */
	public function calculate_cc_percentage()
	{
		if ($stats = $this->calculate_cc())
		{
			$executable = 0;
			$executed   = 0;

			foreach ($stats as $stat)
			{
				$executable += $stat['locExecutable'];
				$executed   += $stat['locExecuted'];
			}

			return ($executable > 0) ? ($executed * 100 / $executable) : 100;
		}

		return FALSE;
	}

	/**
	 * Generate a report using the specified $temp_path
	 *
	 * @param array  $groups    Groups to test
	 * @param string $temp_path Temporary path to use while generating report
	 */
	public function generate_report(array $groups, $temp_path, $create_sub_dir = TRUE)
	{
		if ( ! is_writable($temp_path))
		{
			throw new Kohana_Exception('Temp path :path does not exist or is not writable by the webserver', array(':path' => $temp_path));
		}

		$folder_path = $temp_path;

		if ($create_sub_dir === TRUE)
		{
			// Icky, highly unlikely, but do it anyway
			// Basically adds "(n)" to the end of the filename until there's a free file
			$count = 0;
			do
			{
				$folder_name = date('Y-m-d_H:i:s')
					.(empty($groups) ? '' : ('['.implode(',', $groups).']'))
					.(($count > 0) ? ('('.$count.')') : '');
				++$count;
			}
			while (is_dir($folder_path.$folder_name));

			$folder_path .= $folder_name;

			mkdir($folder_path, 0777);
		}
		else
		{
			$folder_name = basename($folder_path);
		}

		$this->run($groups, TRUE);

		require_once 'PHPUnit/Runner/Version.php';
		require_once 'PHPUnit/Util/Report.php';

		PHPUnit_Util_Report::render($this->result, $folder_path);

		return array($folder_path, $folder_name);
	}

	/**
	 * Runs the test suite using the result specified in the constructor
	 *
	 * @param  array $groups       Optional array of groups to test
	 * @param  bool  $collect_cc   Optional, Should code coverage be collected?
	 * @return Kohana_PHPUnit      Instance of $this
	 */
	public function run(array $groups = array(), $collect_cc = FALSE)
	{
		if ($collect_cc AND ! extension_loaded('xdebug'))
		{
			throw new Kohana_Exception('Code coverage cannot be collected because the xdebug extension is not loaded');
		}

		$this->result->collectCodeCoverageInformation( (bool) $collect_cc);

		// Run the tests.
		$this->suite->run($this->result, FALSE, $groups);

		return $this;
	}

	public function addError(PHPUnit_Framework_Test $test, Exception $e, $time)
	{
		$this->totals['errors']++;
		$this->current['result'] = 'errors';
		$this->current['message'] = $test->getStatusMessage();

	}

	public function addFailure(PHPUnit_Framework_Test $test, PHPUnit_Framework_AssertionFailedError $e, $time)
	{
		$this->totals['failures']++;
		$this->current['result'] = 'failures';
		$this->current['message'] = $test->getStatusMessage();
	}

	public function addIncompleteTest(PHPUnit_Framework_Test $test, Exception $e, $time)
	{
		$this->totals['incomplete']++;
		$this->current['result'] = 'incomplete';
		$this->current['message'] = $test->getStatusMessage();
	}

	public function addSkippedTest(PHPUnit_Framework_Test $test, Exception $e, $time)
	{
		$this->totals['skipped']++;
		$this->current['result'] = 'skipped';
		$this->current['message'] = $test->getStatusMessage();
	}

	public function startTest(PHPUnit_Framework_Test $test)
	{
		$this->current['name'] = $test->getName(FALSE);
		$this->current['description'] = $test->toString();
		$this->current['result'] = 'passed';
	}

	public function endTest(PHPUnit_Framework_Test $test, $time)
	{
		// Add totals
		$this->totals['tests']++;
		$this->totals['assertions'] += $test->getNumAssertions();

		// Handle passed tests
		if ($this->current['result'] == 'passed')
		{
			// Add to total
			$this->totals['passed']++;
		}
		else
		{
			// Add to results
			$this->results[$this->current['result']][] = $this->current;
		}

		$this->current = array();

		$this->time += $time;
	}

	public function startTestSuite(PHPUnit_Framework_TestSuite $suite)
	{
	}

	public function endTestSuite(PHPUnit_Framework_TestSuite $suite)
	{
		// Parse test descriptions to make them look nicer
		foreach ($this->results as $case => $testresults)
		{
			foreach ($testresults as $type => $result)
			{
				preg_match('/^(?:([a-z0-9_]+?)::)?([a-z0-9_]+)(?: with data set (#\d+ \(.*?\)))?/i', $result['description'], $m);

				$this->results[$case][$type] += array(
					'class' => $m[1],
					'test' => $m[2],
					'data_set' => isset($m[3]) ? $m[3] : FALSE,
				);
			}
		}
	}
}
