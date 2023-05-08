<?php

// Check for a defined constant or specific file inclusion
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

// Use "require_once" to load the files needed for the class

require_once __DIR__ . "/Database.php";
require_once __DIR__ . "/../models/AppsModel.php";

class appsDatabase extends Database
{
    private $table_name = "apps";
    private $id_name = "app_id";

    // Get one app by using the inherited function getOneRowByIdFromTable
    public function getOne($app_id)
    {
        $result = $this->getOneRowByIdFromTable($this->table_name, $this->id_name, $app_id);

        $apps = $result->fetch_object("AppsModel");

        return $apps;
    }


    // Get all apps by using the inherited function getAllRowsFromTable
   //Deze functie getAll() haalt alle rijen op uit een bepaalde database 
   //tabel (aangegeven door de variabele $this->table_name) en zet deze 
   //om naar een array van AppsModel objecten.
    public function getAll()
    {
        //roept get getAllRowsFromTable($table_name) op die alle rijen van tabel laad 
        //met behulp van while loop worden de rijen omgezet naar appsmodel door de functie fetchobject
       //$this is special PHP keyword ,  is used inside a class method to refer to the object instance on which the method is being called.
        $result = $this->getAllRowsFromTable($this->table_name);

        $apps = [];

        while($app = $result->fetch_object("AppsModel")){
            $apps[] = $app;
        }

        return $apps;
    }

    // Create one by creating a query and using the inherited $this->conn 
    public function insert(AppsModel $app){
        $query = "INSERT INTO apps (app_id, app_name, description, price) VALUES (?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("issi", $app->app_id, $app->app_name, $app->description, $app->price);

        $success = $stmt->execute();

        return $success;
    }

    // Update one by creating a query and using the inherited $this->conn 
    public function updateById($app_id, AppsModel $app)
    {
        $query = "UPDATE apps SET app_name=?, description=?, price=?  WHERE app_id=?;";


        // bereidt een SQL-query voor om te worden uitgevoerd op de database. 
        //De $query parameter bevat de tekst van de query, die wordt voorbereid door 
        //de methode prepare() van het databaseverbinding-object ($this->conn).
         //prepare gebruikt set verklaringen van de databse api
        //uitslag wordt toegewezen aan $stmt
        $stmt = $this->conn->prepare($query);


        $stmt->bind_param("ssii", $app->app_name, $app->description, $app->price, $app_id);

        $success = $stmt->execute();

        return $success;
    }

    // Delete one customer by using the inherited function deleteOneRowByIdFromTable
    public function deleteById($app_id)
    {
        $success = $this->deleteOneRowByIdFromTable($this->table_name, $this->id_name, $app_id);

        return $success;
    }

}
