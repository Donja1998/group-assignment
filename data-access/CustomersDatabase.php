<?php

// Check for a defined constant or specific file inclusion
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

// Use "require_once" to load the files needed for the class

require_once __DIR__ . "/Database.php";
require_once __DIR__ . "/../models/CustomerModel.php";

class CustomersDatabase extends Database
{
    private $table_name = "customers";
    private $id_name = "customer_id";

    // Get one customer by using the inherited function getOneRowByIdFromTable
    public function getOne($customer_id)
    {
        
        $result = $this->getOneRowByIdFromTable($this->table_name, $this->id_name, $customer_id);

        $customer = $result->fetch_object("CustomerModel");

        return $customer;
    }


    // Get all customers by using the inherited function getAllRowsFromTable
    public function getAll()
    {
         //roept get getAllRowsFromTable($table_name) op die alle rijen van tabel laad 
        //met behulp van while loop worden de rijen omgezet naar appsmodel door de functie fetchobject
       //$this is special PHP keyword ,  is used inside a class method to refer to the object instance on which the method is being called.
   
        $result = $this->getAllRowsFromTable($this->table_name);

        $customers = [];

        while($customer = $result->fetch_object("CustomerModel")){
            $customers[] = $customer;
        }

        return $customers;
    }

    // Create one by creating a query and using the inherited $this->conn 
    public function insert(CustomerModel $customer){
        $query = "INSERT INTO customers (first_name, last_name) VALUES (?, ?)";


         // bereidt een SQL-query voor om te worden uitgevoerd op de database. 
        //De $query parameter bevat de tekst van de query, die wordt voorbereid door 
        //de methode prepare() van het databaseverbinding-object ($this->conn).
         //prepare gebruikt set verklaringen van de databse api
        //uitslag wordt toegewezen aan $stmt
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("ss", $customer->first_name, $customer->last_name);

        $success = $stmt->execute();

        return $success;
    }

 // Update one by creating a query and using the inherited $this->conn 
 public function updateById($customer_id, CustomerModel $customer)
 {
     $query = "UPDATE customers SET first_name=?, last_name=? WHERE customer_id=?;";

     $stmt = $this->conn->prepare($query);

     $stmt->bind_param("ssi", $customer->first_name, $customer->last_name, $customer_id);

     $success = $stmt->execute();

     return $success;
 }

 // Delete one customer by using the inherited function deleteOneRowByIdFromTable
 public function deleteById($customer_id)
 {
     $success = $this->deleteOneRowByIdFromTable($this->table_name, $this->id_name, $customer_id);

     return $success;
 }
}