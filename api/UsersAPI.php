<?php

// Check for a defined constant or specific file inclusion
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

require_once __DIR__ . "/RestAPI.php";
require_once __DIR__ . "/../business-logic/CustomersService.php";

// Class for handling requests to "api/Customer"

// extend means that it creates a subclass or child that inherits properties and methods
class UsersAPI extends RestAPI
{

    // Handles the request by calling the appropriate member function
    public function handleRequest()
    {

        
        // If theres two parts in the path and the request method is GET 
        // it means that the client is requesting "api/Customers" and
        // we should respond by returning a list of all customers 
        if ($this->method == "GET" && $this->path_count == 2) {
            $this->getAll();
        } 

        // If there's three parts in the path and the request method is GET
        // it means that the client is requesting "api/Customers/{something}".
        // In our API the last part ({something}) should contain the ID of a 
        // customer and we should respond with the customer of that ID
        else if ($this->path_count == 3 && $this->method == "GET") {
            $this->getById($this->path_parts[2]);
        }

        // If theres two parts in the path and the request method is POST 
        // it means that the client is requesting "api/Customers" and we
        // should get ths contents of the body and create a customer.
        else if ($this->path_count == 2 && $this->method == "POST") {
            $this->postOne();
        } 

          // If theres two parts in the path and the request method is PUT 
        // it means that the client is requesting "api/Customers/{something}" and we
        // should get the contents of the body and update the customer.
        else if ($this->path_count == 3 && $this->method == "PUT") {
            $this->putOne($this->path_parts[2]);
        } 

        // If theres two parts in the path and the request method is DELETE 
        // it means that the client is requesting "api/Customers/{something}" and we
        // should get the ID from the URL and delete that customer.
        else if ($this->path_count == 3 && $this->method == "DELETE") {
            $this->deleteOne($this->path_parts[2]);
        } 
        
        // If none of our ifs are true, we should respond with "not found"
        else {
            $this->notFound();
        }
    }

    // Gets all customers and sends them to the client as JSON
    private function getAll()
    {
        $users = UsersService::getAllUsers();

        $this->sendJson($users);
    }

    // Gets one and sends it to the client as JSON
    private function getById($id)
    {
        $user = UsersService::getUserById($id);

        if ($user) {
            $this->sendJson($user);
        } else {
            $this->notFound();
        }
    }

    // Gets the contents of the body and saves it as a customer by 
    // inserting it in the database.
    //FOR POSTING 
    private function postOne()
    {
        //object oriented programming for assigning customermodel to customer
        // using word new -- making new object with its own set of values
        //by assigning it so customer -> create new reference that can be reused and passed around.
        $user = new UserModel();

        //$customer->customer_id = $this->body["customer_id"];
        $user->user_name = $this->body["user_name"];
        $user->user_password = $this->body["user_password"];
        $user->user_admin = $this->body["user_addmin"];


        //evoking statis method 'savecustomer' on customerservice' passing the 
        //customer object as a parameter and assigning the return value to variable $succes

        $success = UsersService::saveUser($user);

        if($success){
            $this->created();
            //you get comment 
        }
        else{
            $this->error();
        }
    }

    // Gets the contents of the body and updates the customer
    // by sending it to the DB
    //FOR PUTTING 

    private function putOne($id)
    {
        $user = new UserModel();

        $user->user_name = $this->body["user_name"];
        $user->user_password = $this->body["user_password"];
        $user->user_admin = $this->body["user_addmin"];

        $success = UsersService::updateUserById($id, $user);

        if($success){
            $this->ok();
        }
        else{
            $this->error();
        }
    }

    // Deletes the customer with the specified ID in the DB
    //FOR DELETING
    private function deleteOne($id)
    {       
 //finds user vanuit app service and assigned het aan $customer
        $user = UsersService::getUserById($id);


        if($user == null){ //als er geen user is gevonden die aansluit naar functie met notfound 

            $this->notFound();
        }
        //als er wel een user word gevonden dan word de functie deleteappbyid uit de appsservice geroepen om app te verwijderen

        $success = UsersService::deleteUserById($id);

        if($success){
            $this->noContent();// als het een succes is word de functie no content geroepen --> bevestiging.
        }
        else{
            $this->error();
        }
    }
}