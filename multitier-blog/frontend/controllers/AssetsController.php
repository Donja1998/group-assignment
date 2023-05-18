<?php

// Check for a defined constant or specific file inclusion
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

require_once __DIR__ . "/../ControllerBase.php";

class AssetsController extends ControllerBase
{
    private $mime_types = [
        "css" => "text/css",
        "js" => "text/javascript",
        "csv" => "text/csv"
    ];

    public function handleRequest()
    {
        // get the path for the file that's being requested
        $file_path_parts = array_slice($this->path_parts, 1);
        $relative_file_path = implode("/", $file_path_parts);
        $file_path = __DIR__ . "/../" . $relative_file_path;

        // Get the real absolute path of the file and compare it to the 
        // absolute path for the assets directory 
        $real_assets_folder_path = realpath(__DIR__ . "/../assets");
        $real_file_path = realpath($file_path);
        $path_is_valid = strpos($real_file_path, $real_assets_folder_path) === 0;

        // Check that the file exists
        $file_exists = is_file($real_file_path);


        if ($path_is_valid && $file_exists) { // file path is valid

            // MIME type is a label used to identify the type of data in a file, based on its 
            // extension or content, to enable correct handling of the file by software programs
            $mime_type = mime_content_type($real_file_path);

            $file_extension = pathinfo($real_file_path, PATHINFO_EXTENSION);

            if(isset($this->mime_types[$file_extension])){
                $mime_type = $this->mime_types[$file_extension];
            }
            
            header('Content-Type: ' . $mime_type);

            // Send the file contents to the browser
            readfile($real_file_path);
        } else { // file path is invalid
            $this->notFound();
        }
    }
}
