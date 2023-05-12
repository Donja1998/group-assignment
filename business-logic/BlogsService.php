<?php

// Check for a defined constant or specific file inclusion
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

require_once __DIR__ . "/../data-access/AppsDatabase.php";

class BlogsService{

    // Get one customer by creating a database object 
    // from data-access layer and calling its getOne function.
    public static function getBlogById($id){
        $blogs_database = new BlogsDatabase();

        $blog = $blogs_database->getOne($id);

        // If you need to remove or hide data that shouldn't
        // be shown in the API response you can do that here
        // An example of data to hide is users password hash 
        // or other secret/sensitive data that shouldn't be 
        // exposed to users calling the API

        return $blog;
    }

    // Get all customers by creating a database object 
    // from data-access layer and calling its getAll function.
    public static function getAllBlogs(){
        $blogs_database = new BlogsDatabase();

        $blogs = $blogs_database->getAll();

        // If you need to remove or hide data that shouldn't
        // be shown in the API response you can do that here
        // An example of data to hide is users password hash 
        // or other secret/sensitive data that shouldn't be 
        // exposed to users calling the API

        return $blogs;
    }

    // Save a customer to the database by creating a database object 
    // from data-access layer and calling its insert function.
    public static function saveBlog(BlogsModel $blog){
        $blogs_database = new BlogsDatabase();

        // If you need to validate data or control what 
        // gets saved to the database you can do that here.
        // This makes sure all input from any presentation
        // layer will be validated and handled the same way.

        $success = $blogs_database->insert($blog);

        return $success;
    }




// Update the customer in the database by creating a database object 
    // from data-access layer and calling its update function.
    public static function updateBlogById($blog_id, BlogsModel $blog){
        $blogs_database = new BlogsDatabase();

        // If you need to validate data or control what 
        // gets saved to the database you can do that here.
        // This makes sure all input from any presentation
        // layer will be validated and handled the same way.

        $success = $blogs_database->updateById($blog_id, $blog);

        return $success;
    }

    // Delete the customer from the database by creating a database object 
    // from data-access layer and calling its delete function.
    public static function deleteBlogById($blog_id){
        $blogs_database = new BlogsDatabase();

        // If you need to validate data or control what 
        // gets deleted from the database you can do that here.
        // This makes sure all input from any presentation
        // layer will be validated and handled the same way.

        $success = $blogs_database->deleteById($blog_id);

        return $success;
    }
}