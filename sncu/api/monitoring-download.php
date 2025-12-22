<?php
require 'include/config.php';
header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"));
if (!isset($data->user_id) || empty($data->user_id)) {
    echo json_encode(["message" => "User ID is required", "status" => "0"]);
    exit;
}

$user_id = mysqli_real_escape_string($conn, $data->user_id);
$query = "SELECT id, type_of_monitoring, user_id, registration_id, date_of_admission, admission_weight, admission_length, admission_head_circumference, type_of_feed, other_feed, mode_of_feeding, growth_chart_used,new_born_admitted,number_of_hours_MNCU,family_participatory_care,developmental_supportive,continuous_positive_airway_CPAC,mention_reason_not_using_CPAC,kmc_binders,progress_of_child  FROM monitoring_data WHERE user_id='$user_id' AND status ='1'";

$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) > 0) {
    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    echo json_encode(["message" => "registration Download Successfully", "status" => "1", "data" => $data]);
} else {
    echo json_encode(["message" => "table not found", "status" => "0"]);
}
