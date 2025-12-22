<?php

require_once 'config.php';
require_once 'jwt.php';

$headers = getallheaders();
$token = $headers['X-Authorization'] ?? '';
$response = ['message' => $_SERVER];
            echo json_encode($response);
            exit;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($token) {
        // Extract the token from the Authorization header
        $token_array = explode(" ", $token);
        if($token_array[0] == 'Bearer'){
            
        
        $token = $token_array[1];
        
        // Verify and decode the JWT token
        $decodedToken = verifyToken($token, JWT_SECRET_KEY,$conn);
        
        if ($decodedToken) {
            // Access granted, perform the protected action
            $response = ['message' => 'Protected route accessed successfully'];
            echo json_encode($response);
            exit;
        }
        } else {
             $response = ['message' => 'Auth request is invalid'];
            echo json_encode($response);
            exit;
        }
    }
    
    http_response_code(401); // Unauthorized status code
    $response = ['error' => 'Invalid token'];
    echo json_encode($response);
    exit;
}
