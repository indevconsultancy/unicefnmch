<?php
header('Content-Type: application/json');
require_once "config.php";
require_once "jwt.php";

$headers = getallheaders();
$tokendata = $headers["X-Authorization"] ?? "";

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if ($tokendata) {
        // Extract the token from the Authorization header
        $token_array = explode(" ", $tokendata);
        if($token_array[0] == "Bearer"){
            
        $token = $token_array[1];
        
        // Verify and decode the JWT token
        $decodedToken = verifyToken($token, JWT_SECRET_KEY, $conn);
   
        if ($decodedToken) {
            $surveyid=mysqli_real_escape_string($conn, $_REQUEST['survey_id']);
            $path = "questionsjson/s".$surveyid.".json";
            $myfile = fopen($path, "r") or die("Unable to open file!");
            echo fread($myfile,filesize($path));
            fclose($myfile);
            exit;
        }
        } else {
             $response = ["status"=>0, "message" => "Auth request is invalid"];
            echo json_encode($response);
            exit;
        }
    }
    
    http_response_code(401); // Unauthorized status code
    $response = ["status"=>0, "error" => "Invalid token"];
    echo json_encode($response);
    exit;
} else {
    http_response_code(405); // Method Not Allowed
    $response = ["status"=>0, "message" => "The call method not allowed"];
    echo json_encode($response);
    exit;
}
?>