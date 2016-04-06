<?php
session_start();

include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Library.utility.php');

// Include files
Library::using(Library::CORLY_SERVICE_FACTORY, ['FactoryService.class.php']);

/**
 * TemplateSettingsController short summary.
 *
 * TemplateSettingsController description.
 *
 * @version 1.0
 * @author Filip
 */
class TemplateSettingsController
{
    const GET = "GET";
    const SAVE = "SAVE";
    const GET_BY_USER = "GET_BY_USER";
    
    /**
     * Get settings for given project
     * @param mixed $project 
     * @return mixed
     */
    public function Get($project)  {
        return FactoryService::TemplateSettingsService()->GetProjectSettings($project);
    }

    public function GetByUser($user)  {
        return FactoryService::TemplateSettingsService()->GetUserSettings($user);
    }

    public function Save($templateSettings)  {
        return FactoryService::TemplateSettingsService()->SaveProjectSettings($templateSettings);
    }
}

// Extract json data
$rawData = file_get_contents('php://input');
$data = json_decode($rawData);

// Check GET requests
if (isset($_GET["method"]))	{
	// Init result
	$result = new stdClass();
	$TemplateSettingsController = new TemplateSettingsController();

	switch ($_GET["method"]) {
		case TemplateSettingsController::GET:
			$result = $TemplateSettingsController->Get($data);
			break;

        case TemplateSettingsController::SAVE:
            $result = $TemplateSettingsController->Save($data);
            break;

        case TemplateSettingsController::GET_BY_USER:
            $result = $TemplateSettingsController->GetByUser($data);
            break;

		default:
			$result = false;
			break;
	}

	// Return answer to client
	exit(json_encode($result));
}