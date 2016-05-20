<?php
include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Library.utility.php');

Library::using(Library::UTILITIES);

class UntestedAnalyzer
{
    const ANALYZER_ID = "UntestedAnalyzer";
    const JS_CONTROLLER = "untested_analyzer.js";
    private $is_interesting = false;
    public function analyze(SubmissionTSE $submission, LINQ $submissionList, $plugin)
    {
        $this->is_interesting = false;
        if ($plugin == "systemtap") {

            if ($submissionList->IsEmpty()) {
                return new ValidationResult(array());
            }
            $submission1 = $submissionList->Last();
            $submission2 = $submission;
            $res = new stdClass();
            $res->Categories = array();
            foreach ($submission1->GetCategories() as $category) {
                $category2 = $submission2->GetCategoryByName($category->GetName());
                if (is_null($category2)) {
                    continue;
                }
                foreach ($category->GetTestCases() as $testCase) {
                    $testCase2 = $category2->GetTestCaseByName($testCase->GetName());
                    if (is_null($testCase2)) {
                        continue;
                    }
                    error_log("//-----------------------------------------------work!");
                    foreach ($testCase->GetResults() as $result) {
                        
                        $result2 = $testCase2->GetResultByKey($result->GetKey());
                        if (is_null($result2)) {
                            continue;
                        }
                        error_log($result->GetValue()." ".$result2->GetValue());
                        if ($result->GetValue() != $result2->GetValue()) {
                            if ($result->GetValue() == "UNTESTED" &&
                                $result2->GetValue() != "UNTESTED") {
                                if (!isset($res->Categories[$category2->GetName()])) {
                                    $res->Categories[$category2->GetName()] = array();
                                }
                                if (!isset($res->Categories[$category2->GetName()][$testCase2->GetName()])) {
                                    $res->Categories[$category2->GetName()][$testCase2->GetName()] = array();
                                }
                                $res->Categories[$category2->GetName()][$testCase2->GetName()][$result->GetKey()] = $result2->GetValue();
                            }
                        }
                    }
                }
            }
            if (count($res->Categories)) {
                $this->is_interesting = true;
            }
            $validation = new ValidationResult(array(json_encode($res)));
            return $validation;
        }
    }

    public function isInteresting()
    {
        return $this->is_interesting;
    }

    public function Visualize(LINQ $data)
    {
        $visualize = array();
        foreach ($data->ToList() as $value) {
            $visualize[$value->GetSubmission()] = json_decode($value->GetResult());
        }
        return $visualize;
    }

    public function VisualizeSingle($data)
    {
        $visualize = null;
        if ($data) {
            $visualize = json_decode($data->GetResult());
        }
        return $visualize;
    } 
}
