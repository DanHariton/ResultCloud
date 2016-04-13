<?php
include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Library.utility.php');


// Get libraries
Library::using(Library::UTILITIES);
Library::using(Library::CORLY_SERVICE_SESSION);
Library::using(Library::EXTENTIONS_ANALYZERS);
Library::using(Library::CORLY_SERVICE_FACTORY, ['FactoryService.class.php']);
Library::using(Library::CORLY_SERVICE_FACTORY, ['FactoryDao.class.php']);

/**
 * IncludeService short summary.
 *
 * IncludeService description.
 *
 * @version 1.0
 * @author Filip
 */
class IncludeService
{
    // Types
    const TYPE_JAVASCRIPT = "text/javascript";
    
    /**
     * Load js components
     */
    public static function JsComponents()   {
        // Check corly installation
        if (!FactoryService::InstallationService()->CheckInstallation()->IsValid)
            return;

        // Get all components
        foreach (IncludeService::LoadComponents() as $component) {
            IncludeService::OutputJsComponent($component);
        }
        foreach (IncludeService::LoadAnalyzers() as $analyzer) {
            IncludeService::OutputJsAnalyzer($analyzer);
        }
    }
    
    /**
     * Load components
     * @return mixed
     */
    private static function LoadComponents()  {
        return FactoryService::ComponentService()->GetList()->ToList();
    }
    
    /**
     * Output js component
     * @param mixed $component 
     */
    private static function OutputJsComponent($component) {
        echo IncludeService::GetScriptElement(Library::VISUALIZATION_COMPONENTS . DIRECTORY_SEPARATOR . $component->Folder . DIRECTORY_SEPARATOR . 'frontend' . DIRECTORY_SEPARATOR . $component->Filename, IncludeService::TYPE_JAVASCRIPT);
    }

    /**
     * Load analyzers
     * @return mixed
     */
    private static function LoadAnalyzers() {
        return AnalyzeController::GetAnalyzersList()->ToList();
    }

    /**
     * Output js component
     * @param mixed $component 
     */
    private static function OutputJsAnalyzer($analyzer) {
        echo IncludeService::GetScriptElement(Library::EXTENTIONS_ANALYZERS . "/templates/" . $analyzer::ANALYZER_ID . DIRECTORY_SEPARATOR . $analyzer::JS_CONTROLLER, IncludeService::TYPE_JAVASCRIPT);
    }
    
    /**
     * Get script element as string
     * @param mixed $src 
     * @param mixed $type 
     * @return mixed
     */
    private static function GetScriptElement($src, $type)   {
        return '<script src="'.$src.'" type="'.$type.'"></script>';
    }
}
