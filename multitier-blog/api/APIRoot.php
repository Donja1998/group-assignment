<?php

// Check for a defined constant or specific file inclusion
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

require_once __DIR__ . "/RestAPI.php";

// Class for handling requests to "api/"

class APIRoot extends RestAPI
{
    // Handles the request by calling the appropriate member function
    public function handleRequest()
    {
        $this->notFound();
    }
}
