<?php

require 'include/config.php';
header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"));

if (!isset($data->user_id) || empty($data->user_id)) {
    echo json_encode(["message" => "User ID is required", "status" => "0"]);
    exit;
}

$user_id = mysqli_real_escape_string($conn, $data->user_id);
$query = "SELECT id, gestational_age_LBW, registration_year, registration_code, user_id, district_id, sncu_id, monitor_name, monitor_institution, sncu_registration_serial_no, unique_id_of_body, boby_name_optional, boby_of_mothers_name, fathers_name, phone_number_of_mother_father_caregivers, baby_date_of_birth, sex, delivery_type, birth_weight_kg, was_baby_lbw_at_time_of_birth_kg, growth_chart_used, immunization_status, means_for_verification_immunization, mothers_age_years, mother_weight_kg, age_at_marrage_years, reason_for_admission, mention_other_reason, other,monitoring_status, status FROM registration_form WHERE user_id='$user_id'";

$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) > 0) {
    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    echo json_encode(["message" => "registration Download Successfully", "status" => "1", "data" => $data]);
} else {
    echo json_encode(["message" => "No data found", "status" => "0"]);
}
