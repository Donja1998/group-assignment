<?php

// Check for a defined constant or specific file inclusion
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

require_once __DIR__ . "/../business-logic/AuthService.php";
require_once __DIR__ . "/../business-logic/UsersService.php";

// Base class for all API classes to inherit from.
// Includes functions for sending response as JSON
// as well as parsing the request.

class RestAPI
{

    protected $path_parts, $path_count, $query_params, $method, $headers, $body;
    protected $user = false;

    // Gets data from the url and sets the protected properties
    // so that any class inheriting from this can read and handle
    // the request appropriately
    public function __construct($path_parts, $query_params)
    {

        // Set the other protected properties
        $this->path_parts = $this->removeEmptyStrings($path_parts);
        $this->method = $_SERVER["REQUEST_METHOD"];
        $this->headers = getallheaders();
        $this->query_params = $query_params;

        // Count the number of "parts" in the path
        // Example: "api/Purchases" is 2 parts and
        // "api/Purchases/5" is 3 parts
        $this->path_count = count($this->path_parts);

        $this->parseBody();
        $this->setUser();
    }

    // Sends the content of $response as JSON and ends execution
    // $status is the status code to send (200 is default and means "OK") 
    protected function sendJson($response, $status = 200)
    {
        http_response_code($status);

        header("Content-Type: application/json");

        echo json_encode($response);

        die();
    }


    // Preset response for OK-response (200)
    protected function ok(){
        $this->sendJson("OK");
    }

    // Preset response for no content (204)
    protected function noContent(){
        $this->sendJson("", 204);
    }

    // Preset response for if a resource is not found
    protected function notFound(){
        $this->sendJson("Not found", 404);
    }

    // Preset response for if a resource is created
    protected function created(){
        $this->sendJson("Created", 201);
    }

    // Preset response for general invalid request
    protected function invalidRequest(){
        $this->sendJson("Invalid request", 400);
    }

    // Preset response for unauthorized
    protected function unauthorized(){
        $this->sendJson("Unauthorized", 401);
    }

    // Preset response for unauthorized
    protected function forbidden(){
        $this->sendJson("Forbidden", 403);
    }

    // Preset response for general server error
    protected function error(){
        $this->sendJson("Error", 500);
    }


    protected function setUser(){
        if(!isset($this->headers["Authorization"])){
            return false;
        }

        $auth_header = $this->headers["Authorization"];
        $auth_parts = explode(" ", $auth_header);

        if(!isset($auth_parts[1])){
            return false;
        }

        $token = $auth_parts[1];

        $payload = AuthService::validateToken($token);

        if($payload === false || $payload->iss !== APPLICATION_NAME){
            return false;
        }

        $this->user = UsersService::getUserById($payload->user_id);
    }

    protected function requireAuth($authorized_roles = []){
    
        if($this->user === false){
            $this->unauthorized();
        }

        if(count($authorized_roles) > 0 && in_array($this->user->user_role, $authorized_roles) === false){
            $this->forbidden();
        }
    }

    // Parses the body as JSON so classes inheriting from this 
    // can access the body variables using $this->body
    private function parseBody()
    {
        $input = file_get_contents("php://input");

        if(strlen($input) > 0){
            $this->body = json_decode($input, true);
        }
        
    }

    private function removeEmptyStrings($arr) {
        return array_filter($arr, function($str) {
            return trim($str) !== '';
        });
    }
    
}
