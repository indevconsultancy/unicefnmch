<?php
require 'include/config.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->table_name)) {
    echo json_encode(["message" => "Table Name required", "status" => "0"]);
    exit;
}


$table_name = mysqli_real_escape_string($conn, $data->table_name);

$table_access = array("district_master","sncu_master");
if (in_array($table_name, $table_access)) {

    $query = "SELECT * FROM $table_name WHERE status = '1'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $data = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        echo json_encode(["message" => "Table Download Successfully", "status" => "1", "data" => $data]);
    } else {
        echo json_encode(["message" => "table not found", "status" => "0"]);
    }
} else {
    echo json_encode(["message" => "table access no", "status" => "0"]);
}
