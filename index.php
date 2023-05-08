<?php

// Define global constant to prevent direct script loading 
// is a safety feature. All files have a if statement about this myapp. so that if accidentally vardump somewhere it can not be accessed directly 
//so everything goes through this index file 
define('MY_APP', true); 

// Load the router responsible for handling API requests
require_once __DIR__ . "/api/APIRouter.php";

// Get URL path
$path = $_GET["path"];
$path_parts = explode("/", $path); //customers/2 =>array [customer, 2]
$base_path = strtolower($path_parts[0]); //lowercase

// If the URL path starts with "api", load the API - capital or not 
if($base_path == "api" && count($path_parts) > 1){
    $query_params = $_GET;

    // Handle requests using the API router
    $api = new APIRouter($path_parts, $query_params);
    $api->handleRequest();
}
else{ // If URL path is not API, respond with "not found"
    http_response_code(404);
    die("Page not found");
}