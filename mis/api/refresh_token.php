<?php

require_once "config.php";
require_once "jwt.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
     $data = json_decode(file_get_contents("php://input"), true);
    $refreshToken = $data["refreshToken"] ?? "";
 

    // Verify and decode the refresh token
    $decodedToken = verifyToken($refreshToken, REFRESH_TOKEN_SECRET_KEY, $conn);

    if ($decodedToken) {
        if ($decodedToken->exp < time()) {
            http_response_code(401); // Unauthorized status code

        deleteTokensFromDatabase($refreshToken, $conn);
            $response = ["status" => 0, "error" => "Refresh token has expired"];
            echo json_encode($response);
            exit;
        }

        $user_id = $decodedToken->user_id;
        $user_token = $decodedToken->user_taken;

        // Generate a new JWT token
        $jwtToken = generateToken($user_id, JWT_SECRET_KEY, $user_token);
        updateTokensToDatabase($user_id, $jwtToken, $refreshToken, $conn);
        $response = ["jwtToken" => $jwtToken];
        echo json_encode($response);
        exit;
    } else {
        http_response_code(401); // Unauthorized status code
        $response = ["status" => 0,"error" => "Invalid refresh token"];
        echo json_encode($response);
        exit;
    }
  } else {
	http_response_code(405); // Method Not Allowed
	$response = ["status"=>0, "message" => "The call method not allowed"];
	echo json_encode($response);
	exit;	
}