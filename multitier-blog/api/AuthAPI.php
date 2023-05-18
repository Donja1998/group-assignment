<?php

// Check for a defined constant or specific file inclusion
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

require_once __DIR__ . "/RestAPI.php";
require_once __DIR__ . "/../business-logic/AuthService.php";

// Class for handling requests to "api/auth"

class AuthAPI extends RestAPI
{

    // Handles the request by calling the appropriate member function
    public function handleRequest()
    {
        // GET: /api/auth/me
        if ($this->method == "GET" && $this->path_count == 3 && $this->path_parts[2] == "me") {
            $this->getUser();
        }

        // POST: /api/auth/register
        if ($this->method == "POST" && $this->path_count == 3 && $this->path_parts[2] == "register") {
            $this->registerUser();
        }

        // POST: /api/auth/login
        if ($this->method == "POST" && $this->path_count == 3 && $this->path_parts[2] == "login") {
            $this->login();
        }
        
        // If none of our ifs are true, we should respond with "not found"
        else {
            $this->notFound();
        }
    }

    
    private function getUser()
    {
        $this->requireAuth();

        $this->sendJson($this->user);
    }

    
    private function registerUser()
    {
        $user = new UserModel();

        $user->username = $this->body["username"];
        $user->user_role = "user"; // hard code all new users to regular "user" role
        $password = $this->body["password"];

        $success = AuthService::registerUser($user, $password);

        if($success){
            $this->created();
        }
        else{
            $this->invalidRequest();
        }
    }

    
    private function login()
    {
        $username = $this->body["username"];
        $test_password = $this->body["password"];

        $user = AuthService::authenticateUser($username, $test_password);

        if($user == false){
            $this->unauthorized();
        }
        
        $token = AuthService::generateJsonWebToken($user);

        $response = ["access_token" => $token];

        $this->sendJson($response);
    }

}
