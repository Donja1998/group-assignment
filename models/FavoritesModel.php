<?php

// Check for a defined constant or specific file inclusion
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

// Model class for customers-table in database

class FavoritesModel {
    public $favorite_id;
    public $user_id;
    public $blog_id;

    // constructor with  parameters to initialize object properties
    public function __construct($favorite_id = null, $user_id = null, $blog_id = null) {
        $this->favorite_id = $favorite_id;
        $this->user_id = $user_id;
        $this->blog_id = $blog_id;
    }} 