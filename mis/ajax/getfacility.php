<?php include('../includes/config.php'); ?>

<?php
function walker($conn, $tablename, $field, $qryfeild, $value)
{
    $sn = mysqli_query($conn, "select $field from $tablename where $qryfeild='" . $value . "'") or die(mysqli_error());
    $dn = mysqli_fetch_object($sn);
    return ($dn->$field);
}

if (isset($_POST['lookups_value']) && isset($_POST['survey_id'])) {
    $survey_id = $_POST['survey_id'];
    $lookups_value = $_POST['lookups_value'];
    $survey_lookup = walker($conn, 'survey_loockups', 'loockup_data', 'survey_id', $survey_id);

    $json = json_decode($survey_lookup);
    $json2 = $json->lookups;
    $json3 = $json2[0]->facility_ID;

    foreach ($json3 as $val) {
        if ($val->facility_ID == $lookups_value) {
            $result_arr = $val->data;

           
            header('Content-Type: application/json');
            echo json_encode($result_arr[0]);
            exit();
        }
    }
}


header('Content-Type: application/json');
echo json_encode([]);
exit();
?>
