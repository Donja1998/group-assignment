<?php

// Check for a defined constant or specific file inclusion
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

require_once __DIR__ . "/../data-access/BlogsDatabase.php";

class BlogsServices{

    public static function getBlogById($id){
        $blogs_database = new BlogsDatabase();

        $blog = $blogs_database->getOne($id);

        return $blog;
    }
    

    public static function getAllBlogs(){
        $blogs_database = new BlogsDatabase();

        $blogs = $blogs_database->getAll();

        return $blogs;
    }
    

    public static function getBlogsByUser($user_id){
        $blogs_database = new BlogsDatabase();

        $blogs = $blogs_database->getByUserId($user_id);

        return $blogs;
    }

    
    public static function saveBlog(BlogModel $blog){
        $blogs_database = new BlogsDatabase();

        $success = $blogs_database->insert($blog);

        return $success;
    }

    
    public static function UpdateBlogsById($blog_id, BlogModel $blog){
        $blogs_database = new BlogsDatabase();

        $success = $blogs_database->updateById($blog_id, $blog);

        return $success;
    }

    
    public static function deleteBlogById($blog_id){
        $blogs_database = new BlogsDatabase();

        $success = $blogs_database->deleteById($blog_id);

        return $success;
    }
}

