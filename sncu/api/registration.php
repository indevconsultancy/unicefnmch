<?php
require 'include/config.php';

try {
    $input = json_decode(file_get_contents("php://input"), true);


    if (!isset($input['registration_code']) || !isset($input['user_id'])) {
        echo json_encode(["message" => "registration_code and user_id required", "status" => "0"]);
        exit;
    }

    $registration_year = mysqli_real_escape_string($conn, $input['registration_year'] ?? '');
    $registration_code = mysqli_real_escape_string($conn, $input['registration_code'] ?? '');
    $idddd = mysqli_real_escape_string($conn, $input['id'] ?? '');

    $district_id = mysqli_real_escape_string($conn, $input['district_id'] ?? '');
    $user_id = mysqli_real_escape_string($conn, $input['user_id'] ?? '');
    $sncu_id = mysqli_real_escape_string($conn, $input['sncu_id'] ?? '');
    $monitor_name = mysqli_real_escape_string($conn, $input['monitor_name'] ?? '');
    $monitor_institution = mysqli_real_escape_string($conn, $input['monitor_institution'] ?? '');
    $sncu_registration_serial_no = mysqli_real_escape_string($conn, $input['sncu_registration_serial_no'] ?? '');
    $unique_id_of_body = mysqli_real_escape_string($conn, $input['unique_id_of_body'] ?? '');
    $boby_name_optional = mysqli_real_escape_string($conn, $input['boby_name_optional'] ?? '');
    $boby_of_mothers_name = mysqli_real_escape_string($conn, $input['boby_of_mothers_name'] ?? '');
    $fathers_name = mysqli_real_escape_string($conn, $input['fathers_name'] ?? '');
    $phone_number_of_mother_father_caregivers = mysqli_real_escape_string($conn, $input['phone_number_of_mother_father_caregivers'] ?? '');
    $baby_date_of_birth = mysqli_real_escape_string($conn, $input['baby_date_of_birth'] ?? '');
    $sex = mysqli_real_escape_string($conn, $input['sex'] ?? '');
    $delivery_type = mysqli_real_escape_string($conn, $input['delivery_type'] ?? '');
    $birth_weight_kg = mysqli_real_escape_string($conn, $input['birth_weight_kg'] ?? '');
    $was_baby_lbw_at_time_of_birth_kg = mysqli_real_escape_string($conn, $input['was_baby_lbw_at_time_of_birth_kg'] ?? '');
    $growth_chart_used = mysqli_real_escape_string($conn, $input['growth_chart_used'] ?? '');
    $immunization_status = mysqli_real_escape_string($conn, $input['immunization_status'] ?? '');
    $means_for_verification_immunization = mysqli_real_escape_string($conn, $input['means_for_verification_immunization'] ?? '');
    $mothers_age_years = mysqli_real_escape_string($conn, $input['mothers_age_years'] ?? '');
    $mother_weight_kg = mysqli_real_escape_string($conn, $input['mother_weight_kg'] ?? '');
    $age_at_marrage_years = mysqli_real_escape_string($conn, $input['age_at_marrage_years'] ?? '');
    $reason_for_admission = mysqli_real_escape_string($conn, $input['reason_for_admission'] ?? '');
    $reasonArray = array_map('trim', explode(',', $reason_for_admission));
    $lbw = (in_array("LBW", $reasonArray)) ? "Yes" : "No";
    $preterm = (in_array("Preterm", $reasonArray)) ? "Yes" : "No";
    $oth = (in_array("Others", $reasonArray)) ? "Yes" : "No";

    $mention_other_reason = mysqli_real_escape_string($conn, $input['mention_other_reason'] ?? '');
    $other = mysqli_real_escape_string($conn, $input['other'] ?? '');
    $gestational_age_LBW = mysqli_real_escape_string($conn, $input['gestational_age_LBW'] ?? '');

    $sql = "SELECT id FROM registration_form WHERE id = '$idddd'";
    $result = mysqli_query($conn, $sql);


    $id = "";
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $id = $row['id'];
        $update_query = "UPDATE registration_form SET  registration_year = '$registration_year',user_id = '$user_id',district_id = '$district_id', sncu_id = '$sncu_id', monitor_name = '$monitor_name',monitor_institution = '$monitor_institution',sncu_registration_serial_no = '$sncu_registration_serial_no', unique_id_of_body = '$unique_id_of_body',boby_name_optional = '$boby_name_optional', boby_of_mothers_name = '$boby_of_mothers_name', fathers_name = '$fathers_name', phone_number_of_mother_father_caregivers = '$phone_number_of_mother_father_caregivers',baby_date_of_birth = '$baby_date_of_birth', sex = '$sex', delivery_type = '$delivery_type', birth_weight_kg = '$birth_weight_kg', was_baby_lbw_at_time_of_birth_kg = '$was_baby_lbw_at_time_of_birth_kg', growth_chart_used = '$growth_chart_used',immunization_status = '$immunization_status',means_for_verification_immunization = '$means_for_verification_immunization', mothers_age_years = '$mothers_age_years',mother_weight_kg = '$mother_weight_kg',age_at_marrage_years = '$age_at_marrage_years', reason_for_admission = '$reason_for_admission', mention_other_reason = '$mention_other_reason', other = '$other' ,gestational_age_LBW = '$gestational_age_LBW' WHERE id = '$id'";
        if (mysqli_query($conn, $update_query)) {
            echo json_encode(["message" => "Form update successfully", "status" => "1", "last_id" => $id]);
        } else {
            echo json_encode(["message" => "Failed to submit form", "status" => "0",]);
        }
    } else {
        $insert_query = "INSERT INTO registration_form(registration_year,registration_code,user_id, district_id, sncu_id, monitor_name, monitor_institution, sncu_registration_serial_no, unique_id_of_body, boby_name_optional, boby_of_mothers_name, fathers_name, phone_number_of_mother_father_caregivers, baby_date_of_birth, sex, delivery_type, birth_weight_kg, was_baby_lbw_at_time_of_birth_kg, growth_chart_used, immunization_status, means_for_verification_immunization, mothers_age_years, mother_weight_kg, age_at_marrage_years, reason_for_admission, mention_other_reason,other,reason_LBW,reason_Preterm,reason_Others,gestational_age_LBW) VALUES ('$registration_year','$registration_code','$user_id','$district_id','$sncu_id','$monitor_name','$monitor_institution','$sncu_registration_serial_no','$unique_id_of_body','$boby_name_optional','$boby_of_mothers_name','$fathers_name','$phone_number_of_mother_father_caregivers','$baby_date_of_birth','$sex','$delivery_type','$birth_weight_kg','$was_baby_lbw_at_time_of_birth_kg','$growth_chart_used','$immunization_status','$means_for_verification_immunization','$mothers_age_years','$mother_weight_kg','$age_at_marrage_years','$reason_for_admission','$mention_other_reason','$other','$lbw','$preterm','$oth','$gestational_age_LBW')";
        mysqli_query($conn, $insert_query);
        $id = mysqli_insert_id($conn);
        if (!empty($id)) {
            echo json_encode(["message" => "Form submitted successfully", "status" => "1", "last_id" => $id]);
        } else {
            echo json_encode(["message" => "Failed to submit form", "status" => "0",]);
        }
    }
} catch (Exception $e) {
    echo json_encode([
        "message" => "Invalid token",
        "status" => "0",
        "error" => $e->getMessage()
    ]);
}
