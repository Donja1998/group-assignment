<?php

// Check for a defined constant or specific file inclusion
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

require_once __DIR__ . "/../ControllerBase.php";
require_once __DIR__ . "/../../business-logic/BlogsService.php";


class BlogController extends ControllerBase
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
            $this->showNewPurchaseForm();
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

        if ($this->user->user_admin === "admin") {
            $blogs = BlogsService::getAllBlogs();
        } else {
            $blogs = BlogsService::getBlogsByUser($this->user->user_id);
        }

        // $this->model is used for sending data to the view
        $this->model = $blogs;

        $this->viewPage("blogs/index");
    }



    // Gets one purchase and shows the in the single view
    private function showOne()
    {
        // Get the purchase with the ID from the URL
        $blog = $this->getPurchase();

        // $this->model is used for sending data to the view
        $this->model = $blog;

        // Shows the view file purchases/single.php
        $this->viewPage("blogs/single");
    }



    // Gets one and shows it in the edit view
    private function showEditForm()
    {
        $this->requireAuth(["admin"]);

        // Get the purchase with the ID from the URL
        $blog = $this->getPurchase();

        // $this->model is used for sending data to the view
        $this->model = $blog;

        // Shows the view file purchases/edit.php
        $this->viewPage("blogs/edit");
    }




    private function showNewPurchaseForm()
    {
        $this->requireAuth();

        // Shows the view file purchases/new.php
        $this->viewPage("blogs/new");
    }



    // Gets one purchase based on the id in the url
    private function getPurchase()
    {
        $this->requireAuth();

        // Get the purchase with the specified ID
        $id = $this->path_parts[2];

        $purchase = BlogsService::getBlogsById($id);

        if (!$purchase) {
            $this->notFound();
        }

        if ($this->user->user_role !== "admin" && $purchase->user_id !== $this->user->user_id) {
            $this->forbidden();
        }

        return $purchase;
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

        $blog = new BlogsModel();

        // Get updated properties from the body
        $blog->blog_title = $this->body["blog_title"];
        $blog->blog_text = $this->body["blog_text"];
        $blog->latitude = $this->body["latitude"];
        $blog->latitude = $this->body["latitude"];
        $blog->place_id = $this->body["place_id"];



        // Admins can connect any user to the purchase
        if($this->user->user_role === "admin"){
            $blog->user_id = $this->body["user_id"];
        }

        // Regular users can only add purchases to themself
        else{
            $blog->user_id = $this->user->user_id;
        }

        // Save the purchase
        $success = BlogsService::saveBlog($blog);

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
        $this->requireAuth(["admin"]);

        $blog = new BlogsModel();

        // Get ID from the URL
        $id = $this->path_parts[2];

        $existing_blog = BlogsService::getBlogById($id);

        // Get updated properties from the body
        $blog->blog_title = $this->body["blog_title"];
        $blog->blog_text = $this->body["blog_text"];
        $blog->latitude = $this->body["latitude"];
        $blog->latitude = $this->body["latitude"];
        $blog->place_id = $this->body["place_id"];

        $success = BlogsService::updateBlogById($id, $blog);

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
        $success = BlogsService::deleteBlogById($id);

        // Redirect or show error based on response from business logic layer
        if ($success) {
            $this->redirect($this->home . "/blogs");
        } else {
            $this->error();
        }
    }
}