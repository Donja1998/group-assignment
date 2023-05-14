<?php

// Check for a defined constant or specific file inclusion
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

require_once __DIR__ . "/../ControllerBase.php";

class ArticleController extends ControllerBase
{

    public function handleRequest()
    {
       $this->viewPage("articles");
    }
}