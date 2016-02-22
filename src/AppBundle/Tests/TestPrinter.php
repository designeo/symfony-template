<?php

class ResultPrinterListener implements PHPUnit_Framework_TestListener {

    protected $suites = array();

    public function addError(PHPUnit_Framework_Test $test, Exception $e, $time) {}
    public function addFailure(PHPUnit_Framework_Test $test, PHPUnit_Framework_AssertionFailedError $e, $time) {}
    public function addIncompleteTest(PHPUnit_Framework_Test $test, Exception $e, $time) {}
    public function addSkippedTest(PHPUnit_Framework_Test $test, Exception $e, $time) {}
    public function startTest(PHPUnit_Framework_Test $test) {}
    public function endTest(PHPUnit_Framework_Test $test, $time) {}
    public function startTestSuite(PHPUnit_Framework_TestSuite $suite) {}
    public function addRiskyTest(PHPUnit_Framework_Test $test, Exception $e, $time) {}

    public function endTestSuite(PHPUnit_Framework_TestSuite $suite) {
        $this->suites = [$suite];

        $this->printResults([]);
    }

    public function printResults() {
        $tests = array();

        $classes = [];

        foreach($this->suites as $suite) {
            foreach($suite->tests() as $test) {
                if(!$test instanceOf PHPUnit_Framework_TestCase) {
                    continue;
                }

                $annotations = $test->getAnnotations();

                /** check if test class is usable for report */
                if (!isset($annotations["class"]["coversDefaultClass"])) {
                    continue;
                }

                /** if method does not cover anything skip */
                if (!isset($annotations["method"]["covers"])) {
                    continue;
                }

                # REGISTER CLASSES
                $testedClasses = $annotations["class"]["coversDefaultClass"]; // max one
                $testedMethods = $annotations["method"]["covers"];

                if (count($testedClasses) > 1) {
                    var_dump("!!!! covers more than one class !!!!");
                    var_dump($testedClasses);
                    continue;
                }

                $testedClass = $testedClasses[0];

                if (!array_key_exists($testedClass, $classes)) {
                    $classes[$testedClass] = [];
                }

                # REIGSTER METHODS
                foreach ($testedMethods as $testedMethod) {
                    if (!isset($classes[$testedClass][$testedMethod])) {
                        $classes[$testedClass][$testedMethod] = [];
                    }


                    $statusLine = "";

                    $status = $test->getStatus();
                    if($status == PHPUnit_Runner_BaseTestRunner::STATUS_FAILURE) {
                        $statusLine .= $this->outputFail();
                    } else if($status == PHPUnit_Runner_BaseTestRunner::STATUS_SKIPPED) {
                        $statusLine .= "S";
                    } else if($status == PHPUnit_Runner_BaseTestRunner::STATUS_INCOMPLETE) {
                        $statusLine .= "I";
                    } else if($status == PHPUnit_Runner_BaseTestRunner::STATUS_ERROR) {
                        $statusLine .= "\033[01;31mE\033[0m";
                    } else {
                        $statusLine .= $this->outputCheck();
                    }
                    $statusLine .= " - " . $test->getName();

                    $classes[$testedClass][$testedMethod][] = $statusLine;
                }
            }
        }
        foreach($classes as $className => $methods) {
            echo "\n\033[01;36m=== $className ===\033[0m\n";
            foreach($methods as $method => $testCaseStrings) {
                echo "\033[01;35m - $method\033[0m\n";
                foreach($testCaseStrings as $testCaseString) {
                    echo "  $testCaseString\n";
                }
            }

        }
    }

    protected function outputFail() {
        return "\033[01;31m".mb_convert_encoding("\x27\x16", 'UTF-8', 'UTF-16BE')."\033[0m";
    }

    protected function outputCheck() {
        return "\033[01;32m".mb_convert_encoding("\x27\x14", 'UTF-8', 'UTF-16BE')."\033[0m";
    }
}