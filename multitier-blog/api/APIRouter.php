<?php

// Check for a defined constant or specific file inclusion
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

require_once __DIR__ . "/BlogsAPI.php";
require_once __DIR__ . "/AuthAPI.php";
require_once __DIR__ . "/APIRoot.php";

// Class for routing all our API requests

class APIRouter
{

    private $path_parts, $query_params;
    private $routes = [];

    public function __construct($path_parts, $query_params)
    {
        // Available routes
        // Add to this if you need to add any route to the API
        $this->routes = [
            // Whenever someone calls "api/purchases" we 
            // will load the PurchasesAPI class
            "auth" => "AuthAPI",
            "purchases" => "PurchasesAPI",
            "root" => "APIRoot"
        ];

        $this->path_parts = $path_parts;
        $this->query_params = $query_params;
    }

    public function handleRequest()
    {

        $resource = "root";
        $route_class = $this->routes[$resource];

        // URL/api OR URL/api/12334
        if (count($this->path_parts) >= 2 && $this->path_parts[1] != "") {
            // Get the requested resource from the URL such as "Purchases" or "Users"
            $resource = strtolower($this->path_parts[1]);
        }

        // Check if route from URL exists
        if (isset($this->routes[$resource])) {
            // Get the class specified in the routes
            $route_class = $this->routes[$resource];
        }

        // Create a new object from the resource class
        $route_object = new $route_class($this->path_parts, $this->query_params);

        // Handle the request
        $route_object->handleRequest();
    }
}
