<?php

// Check for a defined constant or specific file inclusion
if (!defined('MY_APP') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('This file cannot be accessed directly.');
}

require_once __DIR__ . "/../data-access/UsersDatabase.php";

// Class for handling authentication
// Including registering users, logging in, and updating password
class AuthService
{


    public static function registerUser(UserModel $user, $password)
    {
        $users_database = new UsersDatabase();

        $existing_user = $users_database->getByUsername($user->username);

        // Check if user exists
        if ($existing_user) {
            // Username exists
            return false;
        }

        // Hash the password securely
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Set the password hash in the user object
        $user->password_hash = $password_hash;

        // Insert the user into the database
        $success = $users_database->insert($user);

        return $success;
    }


    public static function authenticateUser($username, $test_password)
    {
        $users_database = new UsersDatabase();

        $user = $users_database->getByUsernameWithPassword($username);

        // Check if user exists
        if (!$user) {
            return false;
        }

        // Compare $user->password_hash and $test_password using password_verify()
        $password_matches = password_verify($test_password, $user->password_hash);

        if ($password_matches == false) {
            return false;
        }


        return $user;
    }


    public static function updatePassword($user_id, $password)
    {
        $users_database = new UsersDatabase();

        // Hash the password securely
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Insert the user into the database
        $success = $users_database->updatePasswordById($user_id, $password_hash);

        return $success;
    }

    public static function generateJsonWebToken(UserModel $user)
    {
        // Set the JWT header and payload with the user ID and username
        $header = json_encode([
            "alg" => "HS256",
            "typ" => "JWT"
        ]);

        $payload = json_encode([
            "user_id" => $user->user_id,
            "username" => $user->username,
            "user_role" => $user->user_role,
            "iss" => APPLICATION_NAME,
            "aud" => APPLICATION_NAME,
            "exp" => time() + 3600, // set to expire in 1 hour
            "iat" => time(),
            "nbf" => time()
        ]);

        // Encode Header to Base64Url String
        $encoded_header = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));

        // Encode Payload to Base64Url String
        $encoded_payload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

        // Create the JWT signature using the HMAC-SHA256 algorithm and a secret key
        $signature = hash_hmac("sha256", "$encoded_header.$encoded_payload", JWT_SECRET, true);

        // Encode Signature to Base64Url String
        $encoded_signature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        // Combine the encoded header, payload, and signature into a JWT token string
        $token = "$encoded_header.$encoded_payload.$encoded_signature";

        // Return the token
        return $token;
    }

    public static function validateToken($token)
    {
        // Split the token into header, payload, and signature strings
        list($encoded_header, $encoded_payload, $encoded_signature) = explode(".", $token);

        // Decode the header and payload from base64 strings to JSON objects
        $header = json_decode(base64_decode($encoded_header));
        $payload = json_decode(base64_decode($encoded_payload));


        // Verify that the JWT header specifies the expected algorithm and token type
        if ($header->alg !== "HS256" || $header->typ !== "JWT") {
            return false;
        }

        // Calculate the expected signature using the HMAC-SHA256 algorithm and the secret key
        $expected_signature = base64_encode(hash_hmac("sha256", "$encoded_header.$encoded_payload", JWT_SECRET, true));
        
        // Encode Signature to Base64Url String
        $expected_signature = str_replace(['+', '/', '='], ['-', '_', ''], $expected_signature);

        // Verify that the actual signature matches the expected signature
        if ($encoded_signature !== $expected_signature) {
            var_dump($encoded_signature, $expected_signature);
            return false;
        }

        // Verify that the token has not expired
        $expiration_time = $payload->exp;
        if (time() > $expiration_time) {
            return false;
        }

        // If all checks pass, return payload to indicate that the token is valid
        return $payload;
    }
}
