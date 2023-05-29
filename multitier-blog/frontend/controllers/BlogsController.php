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
        //$this->requireAuth();

        // Get the purchase with the specified ID
        $id = $this->path_parts[2];

        $blog = BlogsServices::getBlogById($id);

        if (!$blog) {
            $this->notFound();
        }

        //if ($this->user->user_role !== "admin" && $blog->user_id !== $this->user->user_id) {
           // $this->forbidden();
        //}

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
    $blog->title = isset($this->body["title"]) ? $this->body["title"] : '';
    $blog->content = isset($this->body["content"]) ? $this->body["content"] : '';
    $blog->place_id = isset($this->body["place_id"]) ? $this->body["place_id"] : '';

    // Check if the "content" value is empty
    if (empty($blog->content)) {
        // Handle the error, e.g., display an error message or redirect with an error flag
        $this->error();
        return;
    }

    // Check if a file was uploaded for blog_pic_url
    if (isset($_FILES['blog_pic_url']) && $_FILES['blog_pic_url']['error'] === UPLOAD_ERR_OK) {

        // Get the file name and extension
        $filename = $_FILES['blog_pic_url']['name'];
        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        // Generate a unique file name
        $unique_filename = uniqid() . '.' . $extension;

        // Set the upload directory and file path
        $upload_directory = realpath(__DIR__ . "/../assets/img/blogs/");
        $file_path = "$upload_directory/$unique_filename";

        // Move the uploaded file to the upload directory
        $x = move_uploaded_file($_FILES['blog_pic_url']['tmp_name'], $file_path);

        // Get the URL path to the uploaded file
        $url_path = '/assets/img/blogs/' . $unique_filename;

        // Set the blog_pic_url property
        $blog->blog_pic_url = $url_path;
    }

    // Admins can connect any user to the blog
    if ($this->user->user_role === "admin") {
        $blog->user_id = isset($this->body["user_id"]) ? $this->body["user_id"] : null;
    }
    // Regular users can only add blogs to themselves
    else {
        $blog->user_id = $this->user->user_id;
    }

    // Save the blog
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
      $blog = new BlogModel();
  
      // Get ID from the URL
      $id = $this->path_parts[2];
  
      $existing_blog = BlogsServices::getBlogById($id);
      $user_id = null; // Initialize the variable before the loop
  
      foreach ($_SESSION as $key => $value) {
          if ($value instanceof UserModel) {
              $user_id = $value->user_id;
              break; // Exit the loop since we found the user_id
          }
      }
  
      if ($existing_blog->user_id !== $user_id) {
          $this->requireAuth(["admin"]);
      } else {
  
          // Get updated properties from the body
          $blog->title = isset($this->body["title"]) ? $this->body["title"] : $existing_blog->title;
          $blog->content = isset($this->body["content"]) ? $this->body["content"] : $existing_blog->content;
          $blog->place_id = isset($this->body["place_id"]) ? $this->body["place_id"] : $existing_blog->place_id;
          $blog->user_id = isset($this->body["user_id"]) ? $this->body["user_id"] : $existing_blog->user_id;
  
          // Check if a file was uploaded for blog_pic_url
          if (isset($_FILES['blog_pic_url']) && $_FILES['blog_pic_url']['error'] === UPLOAD_ERR_OK) {
  
              // Get the file name and extension
              $filename = $_FILES['blog_pic_url']['name'];
              $extension = pathinfo($filename, PATHINFO_EXTENSION);
  
              // Generate a unique file name
              $unique_filename = uniqid() . '.' . $extension;
  
              // Set the upload directory and file path
              $upload_directory = realpath(__DIR__ . "/../assets/img/blogs/");
              $file_path = "$upload_directory/$unique_filename";
  
              // Move the uploaded file to the upload directory
              $x = move_uploaded_file($_FILES['blog_pic_url']['tmp_name'], $file_path);
  
              // Get the URL path to the uploaded file
              $url_path = '/assets/img/blogs/' . $unique_filename;
  
              // Set the blog_pic_url property
              $blog->blog_pic_url = $url_path;
          } else {
              // If no file was uploaded, retain the existing blog_pic_url value
              $blog->blog_pic_url = $existing_blog->blog_pic_url;
          }
  
          $success = BlogsServices::updateBlogsById($id, $blog);
  
          // Redirect or show error based on response from business logic layer
          if ($success) {
              $this->redirect($this->home . "/blogs");
          } else {
              $this->error();
          }
      }
  }
  

    // Delete a purchase with data from the URL
    private function deleteBlog()
    {
        // Get ID from the URL
        $id = $this->path_parts[2];
    
        $existing_blog = BlogsServices::getBlogById($id);
        $user_id = null; // Initialize the variable before the loop
    
        foreach ($_SESSION as $key => $value) {
            if ($value instanceof UserModel) {
                $user_id = $value->user_id;
                break; // Exit the loop since we found the user_id
            }
        }
    
        if ($existing_blog->user_id !== $user_id) {
            $this->requireAuth(["admin"]);
        }
    
        // Delete the blog
        $success = BlogsServices::deleteBlogById($id);
    
        // Redirect or show error based on response from the business logic layer
        if ($success) {
            $this->redirect($this->home . "/blogs");
        } else {
            $this->error();
        }
    }
}    
