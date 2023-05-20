<?php

// Check for a defined constant or specific file inclusion
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

require_once __DIR__ . "/RestAPI.php";
require_once __DIR__ . "/../business-logic/BlogsServices.php";


class BlogsAPI extends RestAPI
{

    // Handles the request by calling the appropriate member function
    public function handleRequest()
    {

        // GET: /api/purchases
        if ($this->method == "GET" && $this->path_count == 2) {
            $this->getAll();
        }

        // GET: /api/purchases/{id}
        else if ($this->path_count == 3 && $this->method == "GET") {
            $this->getById($this->path_parts[2]);
        }

        // POST: /api/purchases
        else if ($this->path_count == 2 && $this->method == "POST") {
            $this->postOne();
        }

        // PUT: /api/purchases/{id}
        else if ($this->path_count == 3 && $this->method == "PUT") {
            $this->putOne($this->path_parts[2]);
        }

        // DELETE: /api/purchases/{id}
        else if ($this->path_count == 3 && $this->method == "DELETE") {
            $this->deleteOne($this->path_parts[2]);
        }

        // If none of our ifs are true, we should respond with "not found"
        else {
            $this->notFound();
        }
    }


    private function getAll()
    {
        $this->requireAuth();

        if ($this->user->user_role === "admin") {
            $purchases = BlogsServices::getAllBlogs();
        } else {
            $purchases = BlogsServices::getBlogsByUser($this->user->user_id);
        }


        $this->sendJson($purchases);
    }


    private function getById($id)
    {
        $this->requireAuth();

        $purchase = BlogsServices::getBlogById($id);

        if (!$purchase) {
            $this->notFound();
        }

        if ($this->user->user_role !== "admin" || $purchase->user_id !== $this->user->user_id) {
            $this->forbidden();
        }

        $this->sendJson($purchase);
    }


    private function postOne()
    {
        $this->requireAuth();

        $purchase = new BlogModel();

        $purchase->title = $this->body["title"];
        $purchase->content = $this->body["content"];
        $purchase->place_id = $this->body["place_id"];
        $purchase->blog_pic_url = $this->body["blog_pic_url"];


        // Admins can connect any user to the purchase
        if ($this->user->user_role === "admin") {
            $purchase->user_id = $this->body["user_id"];
        }

        // Regular users can only add purchases to themself
        else {
            $purchase->user_id = $this->user->user_id;
        }

        $success = BlogsServices::saveBlog($purchase);

        if ($success) {
            $this->created();
        } else {
            $this->error();
        }
    }


    private function putOne($id)
    {
        $this->requireAuth();

        $blog = new BlogModel();

        $blog->title = $this->body["title"];
        $blog->content = $this->body["content"];
        $blog->place_id = $this->body["place_id"];
        $blog->blog_pic_url = $this->body["blog_pic_url"];


        // Admins can connect any user to the purchase
        if ($this->user->user_role === "admin") {
            $blog->user_id = $this->body["user_id"];
        }

        // Regular users can only add purchases to themself
        else {
            $blog->user_id = $this->user->user_id;
        }

        $success = BlogsServices::updateBlogsById($id, $blog);

        if ($success) {
            $this->ok();
        } else {
            $this->error();
        }
    }

    // Deletes the purchase with the specified ID in the DB
    private function deleteOne($id)
    {
        // only admins can delete purchases
        $this->requireAuth(["admin"]);

        $blog = BlogsServices::getBlogById($id);

        if ($blog == null) {
            $this->notFound();
        }

        $success = BlogsServices::deleteBlogById($id);

        if ($success) {
            $this->noContent();
        } else {
            $this->error();
        }
    }
}
