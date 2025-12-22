<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-type:application/json");
require_once "../config.php";


$headers = apache_request_headers();

$headers = array_change_key_case($headers,CASE_UPPER);

$clientHmac = $headers['X-HAMC'] ?? '';
 
if ($API_KEY !== $clientHmac) {

    http_response_code(401); 
    $response = ["status"=>401, "message" => "Not allowed!"];
    echo json_encode($response);
    exit;
    }
    
    if ($_SERVER["REQUEST_METHOD"] === "GET") {
       
            if(isset($_GET['uid']) && isset($_GET['status'])){
           $sqld= mysqli_query($conn, "UPDATE users SET status=" . $_GET['status'] ." WHERE user_id = ".$_GET['uid']);
            echo json_encode(array("message"=>"User updated", "sql" => $sqld));
            exit;
           }
           echo json_encode(array("message"=>"User couldn't updated"));
    }