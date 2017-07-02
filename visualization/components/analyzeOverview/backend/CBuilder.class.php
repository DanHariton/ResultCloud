<?php

/**
 * @version 1.0
 * @author Bohdan Iakymets
 */
class CBuilder
{
    /**
     * Get analyzer data for vizualization
     * @param stdClass $data 
     * @return ValidationResult
     */
    public static function Get($data)    {
        $submission = $data->Submission;
        
        $vizualize = AnalyzeController::VisualizeBySubmission($submission);
        
        // return google chart object
        return new ValidationResult($vizualize);
    }
}
