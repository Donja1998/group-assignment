<?php

// Check for a defined constant or specific file inclusion
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

require_once __DIR__ . "/RestAPI.php";
require_once __DIR__ . "/../business-logic/FavoritesService.php";

// Class for handling requests to "api/app"

class FavoritesAPI extends RestAPI
{

    // Handles the request by calling the appropriate member function
    public function handleRequest()
    {

        
        // If theres two parts in the path and the request method is GET 
        // it means that the client is requesting "api/Customers" and
        // we should respond by returning a list of all customers 
        if ($this->method == "GET" && $this->path_count == 2) {
            $this->getAllFavorites();
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
    private function getAllFavorites()
    {
        $favorites = FavoritesService::getAllFavorites();
        

        $this->sendJson($favorites);
    }

    // Gets one and sends it to the client as JSON
    private function getById($id)
    {
        $favorite = FavoritesService::getFavoriteById($id);

        if ($favorite) {
            $this->sendJson($favorite);
        } else {
            $this->notFound();
        }
    }

    // Gets the contents of the body and saves it as a customer by 
    // inserting it in the database.
    private function postOne()
    {
        $favorite = new FavoritesModel();

       // $app->app_id = $this->body["app_id"];
        $favorite->user_id = $this->body["user_id"];
        $favorite->blog_id = $this->body["blog_id"];

        $success = FavoritesService::saveFavorite($favorite);

        if($success){
            $this->created();
        }
        else{
            $this->error();
        }
    }


    // Gets the contents of the body and updates the customer
    // by sending it to the DB
    private function putOne($id)
    {
        $favorite = new BlogsModel();

               // $app->app_id = $this->body["app_id"];
               $favorite->blog_title = $this->body["blog_title"];
               $favorite->blog_text = $this->body["blog_text"];
               $favorite->latitude = $this->body["latitude"];
               $favorite->longitude = $this->body["longitude"];
               $favorite->place_id = $this->body["place_id"];


       // AppsService is the class name, and updateAppsById is the static 
       //method being called. $id and $app are the arguments being passed to the method.
        //:: is a scope operator used to acces statis methods and properties.
        $success = FavoritesService::updateBlogById($id, $favorite);

        if($success){
            $this->ok();
        }
        else{
            $this->error();
        }
    }

    // Deletes the customer with the specified ID in the DB

    
    private function deleteOne($id)
    {
        //finds app vanuit app service and assigned het aan $app
        $favorite = FavoritesService::getFavoriteById($id);

        //als er geen app is gevonden die aansluit naar functie met notfound 
        if($favorite == null){
            $this->notFound();
        }

        //als er wel een app word gevonden dan word de functie deleteappbyid uit de appsservice geroepen om app te verwijderen
        $success = FavoritesService::deleteFavoriteById($id);

        if($success){
            $this->noContent(); // als het een succes is word de functie no content geroepen --> bevestiging.
        }
        else{
            $this->error();
        }
    }
}
