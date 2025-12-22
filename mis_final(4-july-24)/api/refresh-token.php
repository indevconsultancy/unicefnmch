<?php

require_once 'config.php';
require_once 'jwt.php';

$headers = getallheaders();
$token = $headers['X-Authorization'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($token) {
        // Extract the token from the Authorization header
        $token_array = explode(" ", $token);
        if($token_array[0] == 'Bearer'){
            $decodedToken = verifyToken($token, REFRESH_TOKEN_SECRET_KEY, $conn);
            if ($decodedToken) {
                /*add jwt auth key in response
			*/
			$jwtToken = generateToken($data->user_id, JWT_SECRET_KEY);
			// Generate refresh token
			$refreshToken = generateRefreshToken($data->user_id, REFRESH_TOKEN_SECRET_KEY);	
		
            $response = ['auth_key' => 'Protected route accessed successfully'];
            echo json_encode($response);
            exit;
        }
        } else {
             $response = ['message' => 'Auth request is invalid'];
            echo json_encode($response);
            exit;
        }
    }
}