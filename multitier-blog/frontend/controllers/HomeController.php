<?php

// Check for a defined constant or specific file inclusion
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

require_once __DIR__ . "/../ControllerBase.php";


class HomeController extends ControllerBase
{

    public function handleRequest($request_info)
    {
        if ($request_info == "not_found") {
            $this->notFound();
        } else {
            $this->viewPage("home");
        }
    }
}
