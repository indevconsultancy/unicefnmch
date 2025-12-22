<?php
include('includes/config.php');
/*
//////////////////////////////// asha_counsel_mother_family [Follow-UP]///////////////
$typeoffeed = ['Enquiry on whether breastfeeding is continued','Enquiry on frequency of breastfeeding','Enquiry on Exclusive breastfeeding','Enquiry on any challenges related to breastfeeding','Observation of positioning and attachment','None'];
$typeoffeed_column_name = ['enquiry_on_breastfeeding_is_continoued','enquiry_on_frequency_of_breastfeeding','enquiry_on_exclusive_breastfeeding','enquiry_on_anychallanges_breastfeeding','observation_of_positioning','counsel_none'];

$sqlqry = mysqli_query($conn, "SELECT id, asha_counsel_mother_family FROM follow_up WHERE asha_counsel_mother_family != ''");

while ($data = mysqli_fetch_object($sqlqry)) {
    $feedValues = array_map('trim', explode(',', $data->asha_counsel_mother_family));
    
    $updateFields = [];

    foreach ($typeoffeed as $index => $feedOption) {
        if (in_array($feedOption, $feedValues)) {
            $col = $typeoffeed_column_name[$index];
            $updateFields[] = "`$col` = 'Yes'";
        }
    }

    if (!empty($updateFields)) {
        $updateQuery = "UPDATE follow_up SET " . implode(', ', $updateFields) . " WHERE id = " . (int)$data->id;
        mysqli_query($conn, $updateQuery);
    }
}
*/
/////////////////////////////// asha_check_following_infant [Follow-UP]///////////////
/*
$typeoffeed = ['Weight','Temparature','Respiratory rate','Pus on umbilicus','Eyes','Urine frequency','None'];
$typeoffeed_column_name = ['check_Weight','check_Temparature','check_Respiratory_rate','check_Pus_on_umbilicus','check_Eyes','check_Urine_frequency','check_none'];

$sqlqry = mysqli_query($conn, "SELECT id, asha_check_following_infant FROM follow_up WHERE asha_check_following_infant != ''");

while ($data = mysqli_fetch_object($sqlqry)) {
    $feedValues = array_map('trim', explode(',', $data->asha_check_following_infant));
    
    $updateFields = [];

    foreach ($typeoffeed as $index => $feedOption) {
        if (in_array($feedOption, $feedValues)) {
            $col = $typeoffeed_column_name[$index];
            $updateFields[] = "`$col` = 'Yes'";
        }
    }

    if (!empty($updateFields)) {
        $updateQuery = "UPDATE follow_up SET " . implode(', ', $updateFields) . " WHERE id = " . (int)$data->id;
        mysqli_query($conn, $updateQuery);
    }
}
*/
/*
//////////////////////////////// Type of Feed [Follow-UP]///////////////

$typeoffeed = ['Breast Milk','Formula Milk','Animal Milk','Water','Parenteral','Others'];
$typeoffeed_column_name = ['tof_Breast_milk','tof_Formula_milk','tof_Animal_milk','tof_Water','tof_Others'];

$sqlqry = mysqli_query($conn, "SELECT id, type_of_feed FROM follow_up WHERE type_of_feed != ''");

while ($data = mysqli_fetch_object($sqlqry)) {
    $feedValues = array_map('trim', explode(',', $data->type_of_feed));
    
    $updateFields = [];

    foreach ($typeoffeed as $index => $feedOption) {
        if (in_array($feedOption, $feedValues)) {
            $col = $typeoffeed_column_name[$index];
            $updateFields[] = "`$col` = 'Yes'";
        }
    }

    if (!empty($updateFields)) {
        $updateQuery = "UPDATE follow_up SET " . implode(', ', $updateFields) . " WHERE id = " . (int)$data->id;
        mysqli_query($conn, $updateQuery);
    }
}
*/
//////////////////////////////// Type of Feed [Monitoring]///////////////

$typeoffeed = ['Breast Milk','Animal Milk','Formula Milk','Parenteral','Other'];
$typeoffeed_column_name = ['type_of_feed__Breastmilk','type_of_feed__Animal_Milk','type_of_feed__Formula_Milk','type_of_feed__Parenteral','type_of_feed__Other'];

$sqlqry = mysqli_query($conn, "SELECT id, type_of_feed FROM monitoring_data WHERE type_of_feed != ''");

while ($data = mysqli_fetch_object($sqlqry)) {
    $feedValues = array_map('trim', explode(',', $data->type_of_feed));
    
    $updateFields = [];

    foreach ($typeoffeed as $index => $feedOption) {
        if (in_array($feedOption, $feedValues)) {
            $col = $typeoffeed_column_name[$index];
            $updateFields[] = "`$col` = 'Yes'";
        }
    }

    if (!empty($updateFields)) {
        $updateQuery = "UPDATE monitoring_data SET " . implode(', ', $updateFields) . " WHERE id = " . (int)$data->id;
        mysqli_query($conn, $updateQuery);
    }
}
?>