<?php
include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Library.utility.php');

// Get libraries
Library::using(Library::UTILITIES);
Library::using(Library::CORLY_ENTITIES);
Library::using(Library::CORLY_DBCREATE);
Library::using(Library::CORLY_SERVICE_FACTORY, ['FactoryService.class.php']);
Library::using(Library::CORLY_SERVICE_FACTORY, ['FactoryDao.class.php']);

/**
 * PluginManagementService short summary.
 *
 * PluginManagementService description.
 *
 * @version 1.0
 * @author Filip
 */
class PluginManagementService
{
    // Config file
    const CONFIG_FILE = "config.xml";
    const TEMPLATES_FILE = "templates.xml";
    
    // Property values
    const NAME = "name";
    const TYPE = "type";
    const VALUE = "value";
    const VARCHAR = "varchar";
    const DOUBLE = "double";
    const LONGTEXT = "longtext";
    
    // Component attributes
    const FOLDER = "folder";
    
    /**
     * Install plugin
     * @param mixed $plugin 
     * @return mixed
     */
    public function Install($plugin)    {
        $pluginTSE = new PluginTSE();
        $pluginTSE->MapObject($plugin);
        // Initialize validation
        $validation = new ValidationResult($pluginTSE);
        
        // Get plugin configuration
        $pluginConfiguration = $this->GetPluginConfiguration($pluginTSE->GetRoot());
        
        // Check for entity
        if (isset($pluginConfiguration->entity))    {
            $dbTable = $this->ParsePluginEntity($pluginConfiguration->entity);
            $this->CreatePluginTable($dbTable);
        }
        
        // Save plugin
        $pluginValidation = FactoryService::PluginService()->Save($pluginTSE->GetDbObject());
        
        // Return validation
        return $validation;
    }
    
    /**
     * Create table for plugin
     * @param DbTable $table 
     */
    private function CreatePluginTable(DbTable $table)    {
        // Load configuration
        $dbConfig = ConfigurationService::Database();
        // Create new database handler
        $mysqli = new mysqli($dbConfig->Data["hostname"], $dbConfig->Data["username"], $dbConfig->Data["password"], $dbConfig->Data["database"]);
        
        $statement = $mysqli->prepare($table->GetTableDefinition());
        $statement->execute();
    }
    
    
    /**
     * Parse plugin entity into database table
     * @param mixed $xmlEntity 
     * @return mixed
     */
    private function ParsePluginEntity($xmlEntity)    {
        // Create new table
        $dbTable = new DbTable((string)$xmlEntity[PluginManagementService::NAME]);
        
        // Iterate through properties
        foreach ($xmlEntity->property as $property) {
            // Create new property
            $dbProperty = new DbProperty((string)$property['name']);
            
            // Check property type
            switch((string)$property[PluginManagementService::TYPE])    {
                // Double
                case PluginManagementService::DOUBLE:
                    $dbProperty->SetType(DbType::Double());
                    break;
                    
                // Long text    
                case PluginManagementService::LONGTEXT:
                    $dbProperty->SetType(DbType::LongText());
                    break;
                    
                // Varchar
                case PluginManagementService::VARCHAR:
                    $dbProperty->SetType(DbType::Varchar((string)$property[PluginManagementService::VALUE]));
                    break;
            }
            
            // Add property to table
            $dbTable->AddProperty($dbProperty);
        }
        
        // Return result
        return $dbTable;
    }
    
    /**
     * Get all not installed plugins
     * @return mixed
     */
    public function GetNotInstalledPlugins()  {
        // Get plugin folders
        $pluginDirectoryNames = $this->GetPluginFolders();
        // Get installed plugins and their folders
        $lInstalledPlugins = new LINQ($this->GetInstalledPlugins());
        $installedPluginsDirNames = $lInstalledPlugins->Select('Root')->ToList();
        
        // Load configuration for each not installed plugin
        $uninstalledPlugins = array();
        foreach ($pluginDirectoryNames as $pluginDirname) {
            // If plugin is installed, go to another
            if (in_array($pluginDirname, $installedPluginsDirNames))
                continue;
            
            // Get plugin configuration
            $pluginConfiguration = $this->GetPluginConfiguration($pluginDirname);
            
            // Create plugin entity
            $plugin = new PluginTSE((string)$pluginConfiguration->base->name);
            // Set values
            $plugin->SetVersion((string)$pluginConfiguration->base->version);
            $plugin->SetAuthor((string)$pluginConfiguration->base->author);
            $plugin->SetAbout((string)$pluginConfiguration->base->about);
            $plugin->SetIdentifier((string)$pluginConfiguration->base->identifier);
            $plugin->SetRoot((string)$pluginConfiguration->base->root);
            
            // Add plugin to array
            $uninstalledPlugins[] = $plugin->ExportObject();
        }
        
        // Return result
        return $uninstalledPlugins;
    }
    
    /**
     * Get all folders from plugin folder
     * @return mixed
     */
    private function GetPluginFolders() {
        $pluginDirectoryNames = array();
        foreach (glob(Library::path(Library::PLUGINS, "*"), GLOB_ONLYDIR) as $folder)   {
            $pluginDirectoryNames[] = basename($folder);   
        }
        return $pluginDirectoryNames;
    }
    
    /**
     * Get all installed plugins
     * @return mixed
     */
    private function GetInstalledPlugins()   {
        return FactoryDao::PluginDao()->GetList()->ToList();
    }
    
    private function GetPluginConfiguration($basename)   {
        // Load XML configuration
        return simplexml_load_file(Library::path(Library::PLUGINS . DIRECTORY_SEPARATOR . $basename, self::CONFIG_FILE));
    }
}
