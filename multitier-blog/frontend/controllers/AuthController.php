<?php

// Check for a defined constant or specific file inclusion
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

require_once __DIR__ . "/../ControllerBase.php";
require_once __DIR__ . "/../../business-logic/AuthService.php";

// Class for handling requests to "home/Auth"

class AuthController extends ControllerBase
{

    public function handleRequest()
    {

        // Check for POST method before checking any of the GET-routes
        if ($this->method == "POST") {
            $this->handlePost();
        }



        // GET: /home/auth/login
        if ($this->path_count == 3 && $this->path_parts[2] == "login") {
            $this->showLoginForm();
        }

        // GET: /home/auth/register
        if ($this->path_count == 3 && $this->path_parts[2] == "register") {
            $this->showRegisterForm();
        }

        // GET: /home/auth/profile
        if ($this->path_count == 3 && $this->path_parts[2] == "profile") {
            $this->showProfilePage();
        }

        // Show "404 not found" if the path is invalid
        else {
            $this->notFound();
        }
    }



    private function showLoginForm()
    {
        // Shows the view file auth/login.php
        $this->viewPage("auth/login");
    }

    private function showRegisterForm()
    {
        // Shows the view file auth/register.php
        $this->viewPage("auth/register");
    }

    private function showProfilePage()
    {
        // Shows the view file auth/register.php
        $this->viewPage("auth/profile");
    }


    // handle all post requests in one place
    private function handlePost()
    {
        // POST: /home/auth/login
        if ($this->path_count == 3 && $this->path_parts[2] == "login") {
            $this->loginUser();
        }

        // POST: /home/auth/register
        else if ($this->path_count == 3 && $this->path_parts[2] == "register") {
            $this->registerUser();
        }

        // POST: /home/auth/logout
        else if ($this->path_count == 3 && $this->path_parts[2] == "logout") {
            $this->logoutUser();
        }

        // POST: /home/auth/profile_pic
        else if ($this->path_count == 3 && $this->path_parts[2] == "profile_pic") {
            $this->addProfilePicture();
        }

        // Show "404 not found" if the path is invalid
        else {
            $this->notFound();
        }
    }


    private function loginUser()
    {
        $username = $this->body["username"];
        $test_password = $this->body["password"];

        $user = AuthService::authenticateUser($username, $test_password);

        if ($user === false) {
            $this->model["error"] = "Invalid credentials";
            $this->viewPage("auth/login");
        }

        $_SESSION["user"] = $user;

        $this->redirect($this->home . "/auth/profile");
    }


    private function registerUser()
    {
        $user = new UserModel();

        $user->username = $this->body["username"];
        $user->user_role = "user"; // hard code all new users to regular "user" role
        $password = $this->body["password"];
        $confirm_password = $this->body["confirm_password"];

        if ($password !== $confirm_password) {
            $this->model["error"] == "Passwords don't match";
            $this->viewPage("auth/register");
        }

        $existing_user = UsersService::getUserByUsername($user->username);

        if ($existing_user) {
            $this->model["error"] == "Username already in use";
            $this->viewPage("auth/register");
        }

        $success = AuthService::registerUser($user, $password);

        if ($success) {
            $this->redirect($this->home . "/auth/login");
        } else {
            $this->model["error"] == "Error registering user";
            $this->viewPage("auth/register");
        }
    }


    private function logoutUser()
    {
        session_destroy();

        $this->redirect($this->home . "/auth/login");
    }


    private function addProfilePicture()
    {
        $this->requireAuth();
        // Check if a file was uploaded
        if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {

            // Get the file name and extension
            $filename = $_FILES['profile_pic']['name'];
            $extension = pathinfo($filename, PATHINFO_EXTENSION);

            // Generate a unique file name
            $unique_filename = uniqid() . '.' . $extension;

            // Set the upload directory and file path
            $upload_directory = realpath(__DIR__ . "/../assets/img/profiles/");
            $file_path = "$upload_directory/$unique_filename";

            // Move the uploaded file to the upload directory
            $x = move_uploaded_file($_FILES['profile_pic']['tmp_name'], $file_path);

            // Get the URL path to the uploaded file
            $url_path = '/assets/img/profiles/' . $unique_filename;

            // You can now save the URL path to the database or use it in your application as needed
            $this->user->profile_pic_url = $url_path;

            UsersService::updateUser($this->user->user_id, $this->user);

            // Redirect to the profile page or display a success message
            $this->redirect($this->home . "/auth/profile");
            
        } else {
            $this->error();
        }
    }
}
