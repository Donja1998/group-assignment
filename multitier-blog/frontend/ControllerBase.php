<?php

// Check for a defined constant or specific file inclusion
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

class ControllerBase
{

    protected $path_parts, $path_count, $query_params, $method, $body, $model, $home;
    protected $user = false;

    public function __construct($path_parts, $query_params)
    {
        session_start();

        $this->model["error"] = false;

        $this->path_parts = $this->removeEmptyStrings($path_parts);
        $this->query_params = $query_params;
        $this->method = $_SERVER["REQUEST_METHOD"];

        $this->path_count = count($this->path_parts);

        $this->body = $_POST;
        $this->home = getHomePath();

        $this->setUser();
    }

    protected function viewPage($view, $status = 200)
    {
        http_response_code($status);

        header("Content-Type: text/html;charset=UTF-8");

        require __DIR__ . "/views/" . $view . ".php";

        die();
    }

    protected function notFound()
    {
        $this->viewPage("notFound", 404);
        die();
    }

    protected function unauthorized()
    {
        $this->viewPage("auth/unauthorized", 401);
        die();
    }

    protected function forbidden()
    {
        $this->viewPage("auth/unauthorized", 403);
        die();
    }

    protected function redirect($url)
    {
        header('Location: ' . $url);
        die();
    }

    protected function error()
    {
        echo "ERROR!!";
        debug_print_backtrace();
        die();
    }

    protected function setUser(){
        if(!isset($_SESSION["user"])){
            return false;
        }

        $this->user = UsersService::getUserById($_SESSION["user"]->user_id);
    }

    protected function requireAuth($authorized_roles = []){
    
        if($this->user === false){
            $this->unauthorized();
        }

        if(count($authorized_roles) > 0 && in_array($this->user->user_role, $authorized_roles) === false){
            $this->forbidden();
        }
    }

    private function removeEmptyStrings($arr)
    {
        return array_filter($arr, function ($str) {
            return trim($str) !== '';
        });
    }
}
