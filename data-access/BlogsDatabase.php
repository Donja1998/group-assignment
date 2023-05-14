<?php

// Check for a defined constant or specific file inclusion
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

// Use "require_once" to load the files needed for the class

require_once __DIR__ . "/Database.php";
require_once __DIR__ . "/../models/BlogpostModel.php";

class blogsDatabase extends Database
{
    private $table_name = "blogs";
    private $id_name = "blog_id";

    // Get one app by using the inherited function getOneRowByIdFromTable
    public function getOne($blog_id)
    {
        $result = $this->getOneRowByIdFromTable($this->table_name, $this->id_name, $blog_id);

        $blogs = $result->fetch_object("BlogpostModel");

        return $blogs;
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

        $blogs = [];

        while($blog = $result->fetch_object("BlogpostModel")){
            $blogs[] = $blog;
        }

        return $blogs;
    }

    public function getByUserId($user_id)
    {
        $query = "SELECT * FROM blogs WHERE user_id = ?";

        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("i", $user_id);

        $stmt->execute();

        $result = $stmt->get_result();

        $blogs = [];

        while ($blogs = $result->fetch_object("BlogpostModel")) {
            $blogs[] = $blogs;
        }

        return $blogs;
    }

    // Create one by creating a query and using the inherited $this->conn 
    public function insert(BlogsModel $blog){
        $query = "INSERT INTO blogs (blog_title, blog_text, latitude, longitude, place_id, blog_id ) VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("ssddii", $blog->blog_title, $blog->blog_text, $blog->latitude, $blog->longitude,$blog->place_id, $blog->blog_id, );
      

        $success = $stmt->execute();

        return $success;
    }

    // Update one by creating a query and using the inherited $this->conn 
    public function updateById($blog_id, BlogsModel $blog)
    {
        $query = "UPDATE blogs SET blog_title=?, blog_text=?, latitude=?, longitude=?, place_id=?  WHERE blog_id=?;";


        // bereidt een SQL-query voor om te worden uitgevoerd op de database. 
        //De $query parameter bevat de tekst van de query, die wordt voorbereid door 
        //de methode prepare() van het databaseverbinding-object ($this->conn).
         //prepare gebruikt set verklaringen van de databse api
        //uitslag wordt toegewezen aan $stmt
        $stmt = $this->conn->prepare($query);


        $stmt->bind_param("ssddii", $blog->blog_title, $blog->blog_text, $blog->latitude, $blog->longitude,$blog->place_id, $blog_id);

        $success = $stmt->execute();

        return $success;
    }

    // Delete one customer by using the inherited function deleteOneRowByIdFromTable
    public function deleteById($blog_id)
    {
        $success = $this->deleteOneRowByIdFromTable($this->table_name, $this->id_name, $blog_id);

        return $success;
    }

}
