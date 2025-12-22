<?php
require 'include/config.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");


$data = json_decode(file_get_contents("php://input"));
if (!isset($data->email) || !isset($data->password)) {
    echo json_encode(["message" => "Email and password required", "status" => "0"]);
    exit;
}

$email = mysqli_real_escape_string($conn, $data->email);
$password = $data->password;
$query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    if (password_verify($password, $user['password'])) {
        unset($user['password']);
        unset($user['status']);
        $id = $user['id'];
        echo json_encode(["message" => "Login successful", "status" => "1", "data" => $user]);
    } else {
        echo json_encode(["message" => "Invalid password", "status" => "0"]);
    }
} else {
    echo json_encode(["message" => "User not found", "status" => "0"]);
}
