<?php
header("Content-Type: application/json");
$conn = new mysqli("localhost", "root", "indev@123", "who_growth_db");

// Z-score calculation function
function calculateZScore($l, $m, $s, $x) {
    return ($l == 0) ? log($x / $m) / $s : ((pow(($x / $m), $l) - 1) / ($l * $s));
}

// Fetch LMS from correct table
function fetchLMS($conn, $table, $value, $sex, $key = 'age') {
	//echo "SELECT l, m, s FROM $table WHERE $key = ? AND sex = ?";
    $stmt = $conn->prepare("SELECT l, m, s FROM $table WHERE $key = ? AND sex = ?");
    $stmt->bind_param("di", $value, $sex);
    $stmt->execute();
    $stmt->bind_result($l, $m, $s);
    return $stmt->fetch() ? compact('l', 'm', 's') : null;
}

// Input
$data = json_decode(file_get_contents("php://input"), true);
$weight = $data['weight'];
$height = $data['height']; // in cm
$head_circ = $data['head_circ']; // in cm
$gender = $data['gender']; // 1 = male, 2 = female
$age_months = $data['age_months']; // integer

$bmi = $weight / pow($height / 100, 2);

$response = [];

// Weight-for-age
if ($lms = fetchLMS($conn, "wtageinf", $age_months, $gender)) {
    $response['weight_for_age'] = round(calculateZScore($lms['l'], $lms['m'], $lms['s'], $weight), 2);
}

// Length/height-for-age
if ($lms = fetchLMS($conn, "lenageinf", $age_months, $gender)) {
    $response['length_for_age'] = round(calculateZScore($lms['l'], $lms['m'], $lms['s'], $height), 2);
}

// Head circumference-for-age
if ($lms = fetchLMS($conn, "hcageinf", $age_months, $gender)) {
    $response['head_circumference_for_age'] = round(calculateZScore($lms['l'], $lms['m'], $lms['s'], $head_circ), 2);
}

// Weight-for-length/height
if($age_months<=730)
{
if ($lms = fetchLMS($conn, "wtleninf", $height, $gender, "length")) {
    $response['weight_for_length'] = round(calculateZScore($lms['l'], $lms['m'], $lms['s'], $weight), 2);
}
}
else
{
	if ($lms = fetchLMS($conn, "wtheightinf", $height, $gender, "length")) {
    $response['weight_for_length'] = round(calculateZScore($lms['l'], $lms['m'], $lms['s'], $weight), 2);
}
}
/*
// BMI-for-age (optional if you add this table later)
if ($lms = fetchLMS($conn, "wtstat", $age_months, $gender)) {
    $response['bmi_for_age'] = round(calculateZScore($lms['l'], $lms['m'], $lms['s'], $bmi), 2);
}
*/
//echo fetchLMS($conn, "wtageinf", $age_months, $gender);
echo json_encode($response);
?>