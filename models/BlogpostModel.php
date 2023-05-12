<?php

// Check for a defined constant or specific file inclusion
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

// Model class for blogpost-table in database

class BlogsModel{
    public $blog_id;
    public $blog_title;
    public $blog_text; 
    public $latitude;    
    public $longitude; 
    public $place_id;
}

