<?php

require 'include/config.php';
header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"));

if (!isset($data->user_id) || empty($data->user_id)) {
    echo json_encode(["message" => "User ID is required", "status" => "0"]);
    exit;
}

$user_id = mysqli_real_escape_string($conn, $data->user_id);
$query = "SELECT id, type_of_time, user_id, registration_id, schedule_follow_up_date, date_of_visit, baby_weight, baby_length, baby_head_circumference, immunization_status, type_of_feed, other_feed, times_baby_breastfed, mode_of_feeding, check_health_examination, mention_identified_health, anyone_counselled, asha_visit_infant_after_birth, how_many_times_asha_visit, asha_check_following_infant, asha_counsel_mother_family, aww_visit_infant_after_birth, how_many_times_aww_viited, asha_visit_baby_from_sncu, how_many_times_asha_visited, aww_visit_baby_from_sncu, how_many_times_aww_visited,started_feeding_at_home,offered_food_items FROM follow_up WHERE  user_id='$user_id' AND status ='1'";

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
