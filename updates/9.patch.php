<?php

include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Library.utility.php');
Library::using(Library::CORLY_DBCREATE);
Library::using(Library::CORLY_DAO_UPDATE);
Library::using(Library::CORLY_DAO_IMPLEMENTATION_UPDATE);
Library::using(Library::UTILITIES);

/**
 * @version 1.0
 * @author Bohdan Iakymets
 */
class UpdatePatch_9
{
    public $Database;

    public function Update()
    {
        $driver = new UpdateDriver();
        $tTemplateSettings = new DbTable('User');

       // Set email property
        $pEmail = new DbProperty('Email');
        $pEmail->SetType(DbType::Varchar(127));
        $pEmail->NotNull();
        // Add Email to table
        $tUser->AddProperty($pEmail);

        $validation = $driver->Update(UpdateDriver::ADD_TO_TABLE, $tTemplateSettings);

        return $validation;
    }
}
