<?php

// Define global constant to prevent direct script loading 
define('MY_APP', true);


// Load the routers responsible for handling API requests
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/api/APIRouter.php";
require_once __DIR__ . "/frontend/FrontendRouter.php";

// Get URL path
// http://localhost/multitier-shop/[api/purchases/5]
// http://localhost/multitier-shop/[home/purchases] [PATH]
$path = $_GET["path"]; 

$path_parts = explode("/", $path);
$base_path = strtolower($path_parts[0]);
$query_params = $_GET;

// If the URL path starts with "api", load the API
if($base_path == "api"){

    // Handle requests using the API router
    $api = new APIRouter($path_parts, $query_params);
    $api->handleRequest();

}
// If the URL path starts with "home", load the frontend
else if($base_path == "home"){

    // Handle requests using the frontend router
    $frontend = new FrontendRouter($path_parts, $query_params);
    $frontend->handleRequest();

}
else{ // If URL path is not API, redirect to home
    header('Location: home');
    die();
}