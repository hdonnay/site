<?php

/**
 * The main file for PHPUnit-WebReport.
 */

require_once 'Report.php';

/**
 * The dashboard for a PHPUnit test run.
 */
class PHPUnit_WebReport_Dashboard
{
	public $report;
	public $errors;
	
	/**
	 * Runs PHPUnit on the given directory.
	 */
	public static function run($testsDir, $logFile)
	{
		$phpUnitExe = self::getPhpUnitExe();
		$logFile = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $logFile);
		$command = $phpUnitExe . ' --log-junit "' . $logFile . '" "' . $testsDir . '"';
		$output = array();
		exec($command, $output);
		return $output;
	}
	
	protected static function findExe($exeName)
	{
		$envPaths = explode(PATH_SEPARATOR, getenv('PATH'));
		foreach ($envPaths as $p)
		{
			$tentative = rtrim($p, '/\\') . DIRECTORY_SEPARATOR . $exeName;
			if (file_exists($tentative))
				return $tentative;
		}
		return false;
	}
	
	protected static function getPhpUnitExe()
	{
		// Find 'phpunit' in the PATH.
		$phpUnitExe = self::findExe('phpunit');	// on Windows, this should be 'phpunit.bat' but since both exist,
												// and Windows runs .bat without extension, it's all good.
		if (!$phpUnitExe)
		{
			// Can't find phpunit. Use our own runner (which is a copy of phpunit's short code.).
			// ...but we still have to find the PHP executable.
			$phpExeName = 'php';
			if (strpos(PHP_OS, 'Windows') == 0)
				$phpExeName = 'php.exe';
				
			$phpExe = self::findExe($phpExeName);
			if (!$phpExe and isset($_SERVER['PHPRC']))
			{
				$tentative = $_SERVER['PHPRC'] . DIRECTORY_SEPARATOR . $phpExeName;
				if (file_exists($tentative))
					$phpExe = $tentative;
			}
			if (!$phpExe and strpos(PHP_OS, 'Windows') === false)
			{
				$tentative = '/usr/bin/php';
				if (file_exists($tentative))
					$phpExe = $tentative;
			}
			if (!$phpExe)
			{
				throw new Exception("Can't find the PHP executable anywhere!");
			}
			
			try
			{
				$runnerCode = '<?php'.PHP_EOL.
					'set_include_path("'.addcslashes(get_include_path(), '\\"').'");'.PHP_EOL.
<<<'EOD'
require_once 'PHP/CodeCoverage/Filter.php';
PHP_CodeCoverage_Filter::getInstance()->addFileToBlacklist(__FILE__, 'PHPUNIT');

if (strpos('/usr/bin/php', '@php_bin') === 0)
{
	set_include_path(dirname(__FILE__) . PATH_SEPARATOR . get_include_path());
}

require_once 'PHPUnit/Autoload.php';

define('PHPUnit_MAIN_METHOD', 'PHPUnit_TextUI_Command::main');

PHPUnit_TextUI_Command::main();
EOD;
				$phpUnitExe = tempnam(sys_get_temp_dir(), 'phpunit');
				file_put_contents($phpUnitExe, $runnerCode);
				$phpUnitExe = '"'.$phpExe .'" "'.$phpUnitExe.'"';
			}
			catch (Exception $e)
			{
				throw new Exception("Couldn't find PHPUnit executable ".PHPUNIT." and failed to create our own: ".$e->getMessage());
			}
		}
		return $phpUnitExe;
	}

	/**
	 * Creates a new instance of PHPUnit_WebReport_Dashboard.
	 */
	public function __construct($logFile, $format = 'xml')
	{
		$logFile = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $logFile);
		switch ($format)
		{
		case 'xml':
			$this->report = $this->parseXmlLog($logFile);
			break;
		default:
			throw new Exception('Not implemented.');
		}
	}
	
	/**
	 * Gets the CSS code for the report, for including in a page's <head> section.
	 */
	public function getReportCss()
	{
		return file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'phpunit_report.css');
	}
	
	/**
	 * Displays the test run report.
	 */
	public function display($headerLevel = 2)
	{
		if ($this->errors === null)
		{
			echo '<div class="test-report">';
			
			// Summary of the test run.
			echo '<div class="test-report-summary">';
			$this->displayStats($this->report);
			echo '</div>';
			
			// Details on test suites that failed.
			foreach ($this->report->testSuites as $testsuite)
			{
				$this->displayTestSuite($testsuite, true, $headerLevel);
			}
			echo '</div>';
		}
		else
		{
			echo '<div class="test-report">';
			echo '<h' . $headerLevel . '>Error reading PHPUnit log</h' . $headerLevel . '>';
			foreach ($this->errors as $error)
			{
				echo '<p>' . $error . '</p>';
			}
			echo '</div>';
		}
	}
	
	protected function displayStats($stats)
	{
		echo '<div class="stats';
		if ($stats->hasErrors() or $stats->hasFailures()) echo ' fail';
		else echo ' success';
		echo '">';
		$passCount = $stats->testCount() - $stats->errorCount() - $stats->failureCount();
		echo $passCount . '/' . $stats->testCount() . ' test cases complete: ' .
			 '<strong>' . $passCount . '</strong> passes, ' . 
			 '<strong>' . $stats->failureCount() . '</strong> fails and ' .
			 '<strong>' . $stats->errorCount() . '</strong> errors.';
		echo '</div>';
	}
	
	protected function displayTestSuite($testsuite, $skipSuccessful, $headerLevel)
	{
		if ($skipSuccessful and !$testsuite->hasErrors() and !$testsuite->hasFailures())
		{
			return;
		}
		
		echo '<div class="test-suite">';
		echo '<h' . $headerLevel . '>' . $testsuite->name . '</h' . $headerLevel . '>';
		
		if (!$skipSuccessful)
		{
			echo '<div class="test-suite-stats">';
			$this->displayStats($testsuite);
			echo '</div>';
		}
		
		foreach ($testsuite->testSuites as $subTestsuite)
		{
			$this->displayTestSuite($subTestsuite, $skipSuccessful, $headerLevel + 1);
		}
		
		foreach ($testsuite->testCases as $testcase)
		{
			if ($skipSuccessful and !$testcase->hasFailures() and !$testcase->hasErrors())
			{
				continue;
			}

			echo '<div class="test-case">';
			
			if ($testcase->hasErrors() or $testcase->hasFailures())
			{
				echo '<span class="fail">Fail</span>: ' . $testcase->name;
				if (count($testcase->failures) > 0)
				{
					echo '<div class="failures">';
					foreach ($testcase->failures as $failure)
					{
						echo '<pre>' . htmlentities($failure) . '</pre>';
					}
					echo '</div>';
				}
				if (count($testcase->errors) > 0)
				{
					echo '<div class="errors">';
					foreach ($testcase->errors as $error)
					{
						echo '<pre>' . htmlentities($error) . '</pre>';
					}
					echo '</div>';
				}
			}
			else
			{
				echo '<span class="success">Success</span>: ' . $testcase->name;
			}
			
			echo '</div>';
		}
		
		echo '</div>';
	}
	
	protected function parseXmlLog($logFile)
	{
		$report = new PHPUnit_WebReport_Report();
		
		libxml_use_internal_errors(true);
		$results = simplexml_load_file($logFile);
		if (!$results)
		{
			$this->errors = array();
			foreach (libxml_get_errors() as $error)
			{
				$this->errors[] = $error->message . " (in '" . $error->file . "', line " . $error->line . ", column " . $error->column . ")";
			}
			libxml_clear_errors();
		}
		else
		{
			foreach ($results->testsuite as $ts)
			{
				$report->testSuites[] = $this->parseXmlTestSuite($ts);
			}
		}
		
		return $report;
	}
	
	protected function parseXmlTestSuite($ts)
	{
		$testsuite = new PHPUnit_WebReport_TestSuite();
		$testsuite->name = $ts['name'];
		$testsuite->stats['tests'] = intval($ts['tests']);
		$testsuite->stats['assertions'] = intval($ts['assertions']);
		$testsuite->stats['failures'] = intval($ts['failures']);
		$testsuite->stats['errors'] = intval($ts['errors']);
		$testsuite->stats['time'] = floatval($ts['time']);
		
		foreach ($ts->testsuite as $subTs)
		{
			$testsuite->testSuites[] = $this->parseXmlTestSuite($subTs);
		}
			
		foreach ($ts->testcase as $tc)
		{
			$testcase = new PHPUnit_WebReport_TestCase();
			$testcase->name = $tc['name'];
			
			foreach ($tc->failure as $f)
			{
				$testcase->failures[] = strval($f);
			}
			foreach ($tc->error as $e)
			{
				$testcase->errors[] = strval($e);
			}
			
			$testsuite->testCases[] = $testcase;
		}
		
		return $testsuite;
	}
}
