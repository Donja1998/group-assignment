<?php

// Check for a defined constant or specific file inclusion
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

// Use "require_once" to load the files needed for the class

require_once __DIR__ . "/Database.php";
require_once __DIR__ . "/../models/UserModel.php";

class UsersDatabase extends Database
{
    private $table_name = "users";
    private $id_name = "user_id";



      // Get one user by using the inherited function getOneRowByIdFromTable
    // Never send the password hash unless needed for authentication
    public function getByUsername($user_name)
    {
        $user = $this->getByUsernameWithPassword($user_name);

        // Never send the password hash unless needed for authentication
        unset($user->user_password);

        // Return the UserModel object or null if no user was found
        return $user;
    }

 // Get one user by using the inherited function getOneRowByIdFromTable
    // Never send the password hash unless needed for authentication
    public function getByUsernameWithPassword($user_name)
    {
        // Define SQL query to retrieve user data by username
        $query = "SELECT * FROM users WHERE user_name = ?";

        // Prepare the query statement
        $stmt = $this->conn->prepare($query);

        // Bind the username parameter to the prepared statement
        $stmt->bind_param("s", $user_name);

        // Execute the query
        $stmt->execute();

        // Get the result of the query as a mysqli_result object
        $result = $stmt->get_result();

        // Fetch the user data as a UserModel object
        $user = $result->fetch_object("UserModel");

        // Return the UserModel object or null if no user was found
        return $user;
    }


    // Get one user by using the inherited function getOneRowByIdFromTable
    // Never send the password hash unless needed for authentication
    public function getByIdWithPassword($user_id)
    {
        $result = $this->getOneRowByIdFromTable($this->table_name, $this->id_name, $user_id);

        $user = $result->fetch_object("UserModel");

        return $user;
    }

    // Get one customer by using the inherited function getOneRowByIdFromTable
    public function getOne($user_id)
    {
        
        $result = $this->getOneRowByIdFromTable($this->table_name, $this->id_name, $user_id);

        $user = $result->fetch_object("UserModel");

         // Never send the password hash unless needed for authentication
         unset($user->user_password);

        return $user;
    }


    // Get all customers by using the inherited function getAllRowsFromTable
    public function getAll()
    {
         //roept get getAllRowsFromTable($table_name) op die alle rijen van tabel laad 
        //met behulp van while loop worden de rijen omgezet naar appsmodel door de functie fetchobject
       //$this is special PHP keyword ,  is used inside a class method to refer to the object instance on which the method is being called.
   
        $result = $this->getAllRowsFromTable($this->table_name);

        $user = [];

        while($user = $result->fetch_object("UserModel")){
            $users[] = $user;

              // Never send the password hash unless needed for authentication
              unset($user->user_password);
        }

        return $user;
    }

    // Create one by creating a query and using the inherited $this->conn 
    public function insert(UserModel $user){
        $query = "INSERT INTO users (user_id, user_name, user_password, user_admin) VALUES (?, ?, ?, ?)";

         // bereidt een SQL-query voor om te worden uitgevoerd op de database. 
        //De $query parameter bevat de tekst van de query, die wordt voorbereid door 
        //de methode prepare() van het databaseverbinding-object ($this->conn).
         //prepare gebruikt set verklaringen van de databse api
        //uitslag wordt toegewezen aan $stmt
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("ssi", $user->user_name, $user->user_password, $user->user_admin);

        $success = $stmt->execute();

        return $success;
    }

 // Update one by creating a query and using the inherited $this->conn 
 public function updateById($user_id, UserModel $user)
 {
     $query = "UPDATE users SET user_name=?, user_password=?, user_admin=? WHERE user_id=?;";

     $stmt = $this->conn->prepare($query);

     $stmt->bind_param("ssii", $user->user_name, $user->user_password, $user->user_admin, $user_id);

     $success = $stmt->execute();

     return $success;
 }


 // Update one by creating a query and using the inherited $this->conn 
 public function updatePasswordById($user_id, $user_password)
 {
     $query = "UPDATE users SET user_password=? WHERE user_id=?;";

     $stmt = $this->conn->prepare($query);

     $stmt->bind_param("si", $user_password, $user_id);

     $success = $stmt->execute();

     return $success;
 }

 // Delete one customer by using the inherited function deleteOneRowByIdFromTable
 public function deleteById($user_id)
 {
     $success = $this->deleteOneRowByIdFromTable($this->table_name, $this->id_name, $user_id);

     return $success;
 }
}