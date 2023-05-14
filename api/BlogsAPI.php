<?php

// Check for a defined constant or specific file inclusion
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

require_once __DIR__ . "/RestAPI.php";
require_once __DIR__ . "/../business-logic/BlogsService.php";

// Class for handling requests to "api/app"

class BlogsAPI extends RestAPI
{

    // Handles the request by calling the appropriate member function
    public function handleRequest()
    {


        // If theres two parts in the path and the request method is GET 
        // it means that the client is requesting "api/Customers" and
        // we should respond by returning a list of all customers 
        if ($this->method == "GET" && $this->path_count == 2) {
            $this->getAllBlogs();
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
    private function getAllBlogs()
    {
        $blogs = BlogsService::getAllBlogs();


        $this->sendJson($blogs);
    }

    // Gets one and sends it to the client as JSON
    private function getById($id)
    {
        $this->requireAuth();

        $purchase = BlogsService::getPurchaseById($id);

        if (!$purchase) {
            $this->notFound();
        }

        if ($this->users->user_admin !== "admin" || $blog->user_id !== $this->user->user_id) {
            $this->forbidden();
        }

        $this->sendJson($blog);
    }

    // Gets the contents of the body and saves it as a customer by 
    // inserting it in the database.
    private function postOne()
    {
        $blog = new BlogsModel();

        // $app->app_id = $this->body["app_id"];
        $blog->blog_title = $this->body["blog_title"];
        $blog->blog_text = $this->body["blog_text"];
        $blog->latitude = $this->body["latitude"];
        $blog->longitude = $this->body["longitude"];
        $blog->place_id = $this->body["place_id"];

        // Admins can connect any user to the purchase
        if ($this->user->user_role === "user_admin") {
            $blog->user_id = $this->body["user_id"];
        }

        // Regular users can only add purchases to themself
        else {
            $blog->user_id = $this->user->user_id;
        }

        $success = BlogsService::saveBlog($blog);

        if ($success) {
            $this->created();
        } else {
            $this->error();
        }
    }


    // Gets the contents of the body and updates the customer
    // by sending it to the DB
    private function putOne($id)
    {

        $this->requireAuth();



        $blog = new BlogsModel();

        // $app->app_id = $this->body["app_id"];
        $blog->blog_title = $this->body["blog_title"];
        $blog->blog_text = $this->body["blog_text"];
        $blog->latitude = $this->body["latitude"];
        $blog->longitude = $this->body["longitude"];
        $blog->place_id = $this->body["place_id"];


        // Admins can connect any user to the purchase
        if($this->user->user_role === "user_admin"){
            $blog->user_id = $this->body["user_id"];
        }

        // Regular users can only add purchases to themself
        else{
            $blog->user_id = $this->user->user_id;
        }

        $success = BlogsService::updateBlogById($id, $blog);

        if ($success) {
            $this->ok();
        } else {
            $this->error();
        }
    }
    // Deletes the customer with the specified ID in the DB


    private function deleteOne($id)
    {

        // only admins can delete purchases
        $this->requireAuth(["user_admin"]);

        //finds app vanuit app service and assigned het aan $app
        $blog = BlogsService::getBlogById($id);

        //als er geen app is gevonden die aansluit naar functie met notfound 
        if ($blog == null) {
            $this->notFound();
        }

        //als er wel een app word gevonden dan word de functie deleteappbyid uit de appsservice geroepen om app te verwijderen
        $success = BlogsService::deleteBlogById($id);

        if ($success) {
            $this->noContent(); // als het een succes is word de functie no content geroepen --> bevestiging.
        } else {
            $this->error();
        }
    }
}
