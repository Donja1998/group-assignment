<?php

// Check for a defined constant or specific file inclusion
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}


require_once __DIR__ . "/Database.php";
require_once __DIR__ . "/../models/BlogModel.php";

class BlogsDatabase extends Database
{
    private $table_name = "blogs";
    private $id_name = "blog_id";


    public function getOne($blog_id)
    {
        $result = $this->getOneRowByIdFromTable($this->table_name, $this->id_name, $blog_id);

        $blog = $result->fetch_object("BlogModel");

        return $blog;
    }



    public function getAll()
    {
        $result = $this->getAllRowsFromTable($this->table_name);

        $blogs = [];

        while ($blog = $result->fetch_object("BlogModel")) {
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

        while ($blog = $result->fetch_object("BlogModel")) {
            $blogs[] = $blog;
        }

        return $blogs;
    }



    public function insert(BlogModel $blog)
    {
        $query = "INSERT INTO blogs (title, content, place_id, user_id) VALUES (?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("sssi", $blog->title, $blog->content, $blog->place_id, $blog->user_id);

        $success = $stmt->execute();

        return $success;
    }


     
    public function updateById($blog_id, BlogModel $blog)
    {
        $query = "UPDATE blogs SET title=?, content=?, place_id=?, user_id=? WHERE blog_id=?;";

        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("sssii", $blog->title, $blog->content, $blog->place_id, $blog->user_id, $blog_id);

        $success = $stmt->execute();

        return $success;
    }

    public function deleteById($blog_id)
    {
        $success = $this->deleteOneRowByIdFromTable($this->table_name, $this->id_name, $blog_id);

        return $success;
    }
}
