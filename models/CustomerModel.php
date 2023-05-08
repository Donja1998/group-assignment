<?php

// Check for a defined constant or specific file inclusion
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

// Model class for customers-table in database

class CustomerModel{
    public $customer_id;
    public $first_name;
    public $last_name;    
}