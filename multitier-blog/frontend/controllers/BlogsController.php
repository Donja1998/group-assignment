<?php

// Check for a defined constant or specific file inclusion
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

require_once __DIR__ . "/../ControllerBase.php";
require_once __DIR__ . "/../../business-logic/BlogsServices.php";


class BlogsController extends ControllerBase
{

    public function handleRequest()
    {

        // Check for POST method before checking any of the GET-routes
        if ($this->method == "POST") {
            $this->handlePost();
        }


        // GET: /home/purchases
        if ($this->path_count == 2) {
            $this->showAll();
        }


        // GET: /home/purchases/new
        else if ($this->path_count == 3 && $this->path_parts[2] == "new") {
            $this->showNewBlogForm();
        }


        // GET: /home/purchases/{id}
        else if ($this->path_count == 3) {
            $this->showOne();
        }


        // GET: /home/purchases/{id}/edit
        else if ($this->path_count == 4 && $this->path_parts[3] == "edit") {
            $this->showEditForm();
        }

        // Show "404 not found" if the path is invalid
        else {
            $this->notFound();
        }
    }



    // Gets all purchases and shows them in the index view
    private function showAll()
    {
        $this->requireAuth();

        if ($this->user->user_role === "admin") {
            $blogs = BlogsServices::getAllBlogs();
        } else {
            $blogs = BlogsServices::getBlogsByUser($this->user->user_id);
        }

        // $this->model is used for sending data to the view
        $this->model = $blogs;

        $this->viewPage("blogs/index");
    }



    // Gets one purchase and shows the in the single view
    private function showOne()
    {
        // Get the purchase with the ID from the URL
        $blog = $this->getBlog();

        // $this->model is used for sending data to the view
        $this->model = $blog;

        // Shows the view file purchases/single.php
        $this->viewPage("blogs/single");
    }



    // Gets one and shows it in the edit view
    private function showEditForm()
    {
        // Get the purchase with the ID from the URL
        $blog = $this->getBlog();
    

        // $this->model is used for sending data to the view
        $this->model = $blog;

        // Shows the view file purchases/edit.php
        $this->viewPage("blogs/edit");
    }




    private function showNewBlogForm()
    {
        $this->requireAuth();

        // Shows the view file purchases/new.php
        $this->viewPage("blogs/new");
    }



    // Gets one purchase based on the id in the url
    private function getBlog()
    {
        $this->requireAuth();

        // Get the purchase with the specified ID
        $id = $this->path_parts[2];

        $blog = BlogsServices::getBlogById($id);

        if (!$blog) {
            $this->notFound();
        }

        if ($this->user->user_role !== "admin" && $blog->user_id !== $this->user->user_id) {
            $this->forbidden();
        }

        return $blog;
    }


    // handle all post requests for purchases in one place
    private function handlePost()
    {
        // POST: /home/purchases
        if ($this->path_count == 2) {
            $this->createBlog();
        }

        // POST: /home/purchase/{id}/edit
        else if ($this->path_count == 4 && $this->path_parts[3] == "edit") {
            $this->updateBlog();
        }

        // POST: /home/purchase/{id}/delete
        else if ($this->path_count == 4 && $this->path_parts[3] == "delete") {
            $this->deleteBlog();
        }

        // Show "404 not found" if the path is invalid
        else {
            $this->notFound();
        }
    }


    // Create a purchase with data from the URL and body
    private function createBlog()
    {
        $this->requireAuth();

        $blog = new BlogModel();

        // Get updated properties from the body
        $blog->title = $this->body["title"];
        $blog->content = $this->body["content"];
        $blog->place_id = $this -> body["place_id"];
        $blog->blog_pic_url = $this -> body["blog_pic_url"];



        // Admins can connect any user to the purchase
        if($this->user->user_role === "admin"){
            $blog->user_id = $this->body["user_id"];
        }

        // Regular users can only add purchases to themself
        else{
            $blog->user_id = $this->user->user_id;
        }

        // Save the purchase
        $success = BlogsServices::saveBlog($blog);

        // Redirect or show error based on response from business logic layer
        if ($success) {
            $this->redirect($this->home . "/blogs");
        } else {
            $this->error();
        }
    }

  // Update a purchase with data from the URL and body
  private function updateBlog()
  {
      //$this->requireAuth(["admin"]);

      $blog = new BlogModel();

      // Get ID from the URL
      $id = $this->path_parts[2];

      $existing_blog = BlogsServices::getBlogById($id);

      // Get updated properties from the body
      $blog->title = $this->body["title"];
      $blog->content = $this->body["content"];
      $blog->place_id = $this -> body["place_id"];
      $blog->user_id = $this -> body["user_id"];
      $blog->blog_pic_url = $this -> body["blog_pic_url"];
      //$blog->place_id = $existing_blog->place_id;

      $success = BlogsServices::updateBlogsById($id, $blog);

      // Redirect or show error based on response from business logic layer
      if ($success) {
          $this->redirect($this->home . "/blogs");
      } else {
          $this->error();
      }
  }

    // Delete a purchase with data from the URL
    private function deleteBlog()
    {
        $this->requireAuth(["admin"]);

        // Get ID from the URL
        $id = $this->path_parts[2];

        // Delete the purchase
        $success = BlogsServices::deleteBlogById($id);

        // Redirect or show error based on response from business logic layer
        if ($success) {
            $this->redirect($this->home . "/blogs");
        } else {
            $this->error();
        }
    }
}
