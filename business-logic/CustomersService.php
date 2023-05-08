<?php

// Check for a defined constant or specific file inclusion
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

require_once __DIR__ . "/../data-access/CustomersDatabase.php";

class CustomersService{

    // Get one customer by creating a database object 
    // from data-access layer and calling its getOne function.
    public static function getCustomerById($id){
        $customers_database = new CustomersDatabase();

        $customer = $customers_database->getOne($id);

        // If you need to remove or hide data that shouldn't
        // be shown in the API response you can do that here
        // An example of data to hide is users password hash 
        // or other secret/sensitive data that shouldn't be 
        // exposed to users calling the API

        return $customer;
    }

    // Get all customers by creating a database object 
    // from data-access layer and calling its getAll function.
    public static function getAllCustomers(){
        $customers_database = new CustomersDatabase();

        $customers = $customers_database->getAll();

        // If you need to remove or hide data that shouldn't
        // be shown in the API response you can do that here
        // An example of data to hide is users password hash 
        // or other secret/sensitive data that shouldn't be 
        // exposed to users calling the API

        return $customers;
    }

    // Save a customer to the database by creating a database object 
    // from data-access layer and calling its insert function.
    public static function saveCustomer(CustomerModel $customer){
        $customers_database = new CustomersDatabase();

        // If you need to validate data or control what 
        // gets saved to the database you can do that here.
        // This makes sure all input from any presentation
        // layer will be validated and handled the same way.

        $success = $customers_database->insert($customer);

        return $success;
    }


// Update the customer in the database by creating a database object 
    // from data-access layer and calling its update function.
    public static function updateCustomerById($customer_id, CustomerModel $customer){
        $customers_database = new CustomersDatabase();

        // If you need to validate data or control what 
        // gets saved to the database you can do that here.
        // This makes sure all input from any presentation
        // layer will be validated and handled the same way.

        $success = $customers_database->updateById($customer_id, $customer);

        return $success;
    }

    // Delete the customer from the database by creating a database object 
    // from data-access layer and calling its delete function.
    public static function deleteCustomerById($customer_id){
        $customers_database = new CustomersDatabase();

        // If you need to validate data or control what 
        // gets deleted from the database you can do that here.
        // This makes sure all input from any presentation
        // layer will be validated and handled the same way.

        $success = $customers_database->deleteById($customer_id);

        return $success;
    }

    
}