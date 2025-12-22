<?php
require 'include/config.php';


try {
    $input = json_decode(file_get_contents("php://input"), true);

    if (!isset($input['registration_id']) || !isset($input['user_id'])) {
        echo json_encode(["message" => "registration_id and user_id required", "status" => "0"]);
        exit;
    }
    $type_of_monitoring = mysqli_real_escape_string($conn, $input['type_of_monitoring'] ?? '');
    $user_id = mysqli_real_escape_string($conn, $input['user_id'] ?? '');
    $registration_id = mysqli_real_escape_string($conn, $input['registration_id'] ?? '');
    $date_of_admission = mysqli_real_escape_string($conn, $input['date_of_admission'] ?? '');
    $admission_weight = mysqli_real_escape_string($conn, $input['admission_weight'] ?? '');
    $admission_length = mysqli_real_escape_string($conn, $input['admission_length'] ?? '');
    $admission_head_circumference = mysqli_real_escape_string($conn, $input['admission_head_circumference'] ?? '');
    $type_of_feed = mysqli_real_escape_string($conn, $input['type_of_feed'] ?? '');
    $feedArray = array_map('trim', explode(',', $type_of_feed));
    $bst = (in_array("Breast Milk", $feedArray)) ? "Yes" : "No";
    $anml = (in_array("Animal Milk", $feedArray)) ? "Yes" : "No";
    $fmlu = (in_array("Formula Milk", $feedArray)) ? "Yes" : "No";
    $prtnl = (in_array("Parenteral", $feedArray)) ? "Yes" : "No";
    $oth = (in_array("Others", $feedArray)) ? "Yes" : "No";

    $other_feed = mysqli_real_escape_string($conn, $input['other_feed'] ?? '');
    $mode_of_feeding = mysqli_real_escape_string($conn, $input['mode_of_feeding'] ?? '');
    $growth_chart_used = mysqli_real_escape_string($conn, $input['growth_chart_used'] ?? '');

    $new_born_admitted = mysqli_real_escape_string($conn, $input['new_born_admitted'] ?? '');
    $number_of_hours_MNCU = mysqli_real_escape_string($conn, $input['number_of_hours_MNCU'] ?? '');
    $family_participatory_care = mysqli_real_escape_string($conn, $input['family_participatory_care'] ?? '');
    $developmental_supportive = mysqli_real_escape_string($conn, $input['developmental_supportive'] ?? '');
    $continuous_positive_airway_CPAC = mysqli_real_escape_string($conn, $input['continuous_positive_airway_CPAC'] ?? '');
    $mention_reason_not_using_CPAC = mysqli_real_escape_string($conn, $input['mention_reason_not_using_CPAC'] ?? '');
    $kmc_binders = mysqli_real_escape_string($conn, $input['kmc_binders'] ?? '');
    $is_ikmc_provided = mysqli_real_escape_string($conn, $input['is_ikmc_provided'] ?? '');
    $progress_of_child = mysqli_real_escape_string($conn, $input['progress_of_child'] ?? NULL);




    $insert_query = "INSERT INTO monitoring_data (type_of_monitoring, user_id, registration_id, date_of_admission, admission_weight, admission_length, admission_head_circumference, type_of_feed, other_feed,type_of_feed__Breastmilk,type_of_feed__Animal_Milk,type_of_feed__Formula_Milk,type_of_feed__Parenteral,type_of_feed__Other, mode_of_feeding, growth_chart_used,new_born_admitted,number_of_hours_MNCU,family_participatory_care,developmental_supportive,continuous_positive_airway_CPAC,mention_reason_not_using_CPAC,kmc_binders,progress_of_child,is_ikmc_provided) 
    VALUES ('$type_of_monitoring','$user_id','$registration_id','$date_of_admission','$admission_weight','$admission_length','$admission_head_circumference','$type_of_feed','$other_feed','$bst','$anml','$fmlu','$prtnl','$oth','$mode_of_feeding','$growth_chart_used','$new_born_admitted','$number_of_hours_MNCU','$family_participatory_care','$developmental_supportive','$continuous_positive_airway_CPAC','$mention_reason_not_using_CPAC','$kmc_binders','$progress_of_child','$is_ikmc_provided')";

    if (mysqli_query($conn, $insert_query)) {
        $id = mysqli_insert_id($conn);
        $monitoringStatusUpdate = "UPDATE registration_form SET monitoring_status = '$type_of_monitoring' WHERE id ='$registration_id'";
        mysqli_query($conn, $monitoringStatusUpdate);

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
