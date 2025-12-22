<?php
require 'include/config.php';


try {
    $input = json_decode(file_get_contents("php://input"), true);

    if (!isset($input['user_id']) || !isset($input['registration_id'])) {
        echo json_encode(["message" => "registration_id and user_id required", "status" => "0"]);
        exit;
    }

    $type_of_time = mysqli_real_escape_string($conn, $input['type_of_time'] ?? '');
    $user_id = mysqli_real_escape_string($conn, $input['user_id'] ?? '');
    $registration_id = mysqli_real_escape_string($conn, $input['registration_id'] ?? '');
    $schedule_follow_up_date = mysqli_real_escape_string($conn, $input['schedule_follow_up_date'] ?? '');
    $date_of_visit = mysqli_real_escape_string($conn, $input['date_of_visit'] ?? '');
    $baby_weight = mysqli_real_escape_string($conn, $input['baby_weight'] ?? '');
    $baby_length = mysqli_real_escape_string($conn, $input['baby_length'] ?? '');
    $baby_head_circumference = mysqli_real_escape_string($conn, $input['baby_head_circumference'] ?? '');
    $immunization_status = mysqli_real_escape_string($conn, $input['immunization_status'] ?? '');
    $type_of_feed = mysqli_real_escape_string($conn, $input['type_of_feed'] ?? '');
    $feedArray = array_map('trim', explode(',', $type_of_feed));
    $bst = (in_array("Breast Milk", $feedArray)) ? "Yes" : "No";
    $anml = (in_array("Animal Milk", $feedArray)) ? "Yes" : "No";
    $fmlu = (in_array("Formula Milk", $feedArray)) ? "Yes" : "No";
    $wtr = (in_array("Water", $feedArray)) ? "Yes" : "No";
    $oth = (in_array("Others", $feedArray)) ? "Yes" : "No";

    $times_baby_breastfed = mysqli_real_escape_string($conn, $input['times_baby_breastfed'] ?? '');
    $mode_of_feeding = mysqli_real_escape_string($conn, $input['mode_of_feeding'] ?? '');
    $check_health_examination = mysqli_real_escape_string($conn, $input['check_health_examination'] ?? '');
    $mention_identified_health = mysqli_real_escape_string($conn, $input['mention_identified_health'] ?? '');
    $anyone_counselled = mysqli_real_escape_string($conn, $input['anyone_counselled'] ?? '');
    $asha_visit_infant_after_birth = mysqli_real_escape_string($conn, $input['asha_visit_infant_after_birth'] ?? '');
    $how_many_times_asha_visit = mysqli_real_escape_string($conn, $input['how_many_times_asha_visit'] ?? '');
    $asha_check_following_infant = mysqli_real_escape_string($conn, $input['asha_check_following_infant'] ?? '');
    $asha_counsel_mother_family = mysqli_real_escape_string($conn, $input['asha_counsel_mother_family'] ?? '');
    $aww_visit_infant_after_birth = mysqli_real_escape_string($conn, $input['aww_visit_infant_after_birth'] ?? '');
    $how_many_times_aww_viited = mysqli_real_escape_string($conn, $input['how_many_times_aww_viited'] ?? '');
    $asha_visit_baby_from_sncu = mysqli_real_escape_string($conn, $input['asha_visit_baby_from_sncu'] ?? '');
    $how_many_times_asha_visited = mysqli_real_escape_string($conn, $input['how_many_times_asha_visited'] ?? '');
    $aww_visit_baby_from_sncu = mysqli_real_escape_string($conn, $input['aww_visit_baby_from_sncu'] ?? '');
    $how_many_times_aww_visited = mysqli_real_escape_string($conn, $input['how_many_times_aww_visited'] ?? '');
    $started_feeding_at_home = mysqli_real_escape_string($conn, $input['started_feeding_at_home'] ?? '');
    $offered_food_items = mysqli_real_escape_string($conn, $input['offered_food_items'] ?? '');
    $feedathomeArray = array_map('trim', explode(',', $offered_food_items));
    $crls = (in_array("Cereals/Tuber", $feedathomeArray)) ? "Yes" : "No";
    $nuts = (in_array("Legume & Nuts", $feedathomeArray)) ? "Yes" : "No";
    $vt_a = (in_array("Vitamin-A rich fruits and vegetables ( Red/ Yellow/Orange)", $feedathomeArray)) ? "Yes" : "No";
    $oth_frt = (in_array("Other fruits & Vegetables", $feedathomeArray)) ? "Yes" : "No";
    $mlk_pdct = (in_array("Milk & Milk products - Dahi or Lassi or Paneer)", $feedathomeArray)) ? "Yes" : "No";
    $egg = (in_array("Egg", $feedathomeArray)) ? "Yes" : "No";
    $meat = (in_array("Meat/ Poultry/ Fish", $feedathomeArray)) ? "Yes" : "No";
    $jnk_itm = (in_array("Junk items - Chips or Biscuits or chocolate etc", $feedathomeArray)) ? "Yes" : "No";

    $other_feed = mysqli_real_escape_string($conn, $input['other_feed'] ?? '');

    $insert_query = "INSERT INTO follow_up (type_of_time, user_id, registration_id, schedule_follow_up_date, date_of_visit, baby_weight, baby_length, baby_head_circumference, immunization_status, type_of_feed, other_feed,tof_Breast_milk,tof_Formula_milk,tof_Animal_milk,tof_Water,tof_Others, times_baby_breastfed, mode_of_feeding, check_health_examination, mention_identified_health, anyone_counselled, asha_visit_infant_after_birth, how_many_times_asha_visit, asha_check_following_infant, asha_counsel_mother_family, aww_visit_infant_after_birth, how_many_times_aww_viited, asha_visit_baby_from_sncu, how_many_times_asha_visited, aww_visit_baby_from_sncu, how_many_times_aww_visited,started_feeding_at_home,offered_food_items,cereals,legume_and_nuts,vitamin_a_fruits_and_vegetables,other_fruits_and_vegitables,milk_and_milk_product,egg,meat_or_poultry_or_fish,junk_items) VALUES ('$type_of_time','$user_id','$registration_id','$schedule_follow_up_date','$date_of_visit','$baby_weight','$baby_length','$baby_head_circumference','$immunization_status','$type_of_feed','$other_feed','$bst','$fmlu','$anml','$wtr','$oth','$times_baby_breastfed','$mode_of_feeding','$check_health_examination','$mention_identified_health','$anyone_counselled','$asha_visit_infant_after_birth','$how_many_times_asha_visit','$asha_check_following_infant','$asha_counsel_mother_family','$aww_visit_infant_after_birth','$how_many_times_aww_viited','$asha_visit_baby_from_sncu','$how_many_times_asha_visited','$aww_visit_baby_from_sncu','$how_many_times_aww_visited','$started_feeding_at_home','$offered_food_items','$crls','$nuts','$vt_a','$oth_frt','$mlk_pdct','$egg','$meat','$jnk_itm')";

    if (mysqli_query($conn, $insert_query)) {
        $id = mysqli_insert_id($conn);
        echo json_encode(["message" => "Monitoring Data submitted successfully", "status" => "1", "last_id" => $id]);
    } else {
        echo json_encode(["message" => "Failed to submit monitoring data", "status" => "0",]);
    }
} catch (Exception $e) {
    echo json_encode([
        "message" => "Invalid token",
        "status" => "0",
        "error" => $e->getMessage()
    ]);
}
