<?php

function getHomePath()
{
    // Get the requested URI from the server
    $string = $_SERVER["REQUEST_URI"];

    // Get the name of the home path from the URL parameter 'path'
    $home_path_name = explode("/", $_GET["path"])[0];

    // Split the requested URI into an array of parts
    $parts = explode('/', $string);

    // Find the index of the home path within the array of parts
    $home_index = array_search($home_path_name, $parts);

    // Concatenate the parts up to and including the home path to form the full home path
    $home_path = implode('/', array_slice($parts, 0, $home_index + 1));

    // Return the full home path
    return $home_path;
}

function getUser()
{
    if (!isset($_SESSION["user"])) {
        return false;
    }

    return UsersService::getUserById($_SESSION["user"]->user_id);
}
