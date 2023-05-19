<?php

// Check for a defined constant or specific file inclusion
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

// Model class for users-table in database

class BlogModel{
    public $blog_id;
    public $title;
    public $content;
    public $place_id;
    public $user_id;
    public $blog_pic_url;

}