<?php
// Database connection (update with your DB credentials)
$mysqli = new mysqli("localhost", "root", "indev@123", "who_growth_db");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$data = json_decode(file_get_contents("php://input"), true);
$weight = $data['weight'];
$height = $data['height']; // in cm
$head_circ = $data['head_circ']; // in cm
$sex = $data['gender']; // 1 = male, 2 = female
$age_months = $data['age_months']; // integer

if (!$sex || !isset($age_months)) {
    die(json_encode(["error" => "Missing required parameters"]));
}

// Helper function to get LMS values for a given measure (weight, length, headcirc)
function getLMSValues($mysqli, $table, $sex, $age_months) {
    $age = round(floatval($age_months), 1);

    // Prepare and execute query (exact table and age column names may differ)
    $stmt = $mysqli->prepare("SELECT l, m, s, P3, P5, P10, P25, P50, P75, P90, P95, P97 FROM $table WHERE sex = ? AND age = ?");
    $stmt->bind_param("id", $sex, $age);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return null;
    }

    return $result->fetch_assoc();
}

// LMS fetch for weight-for-age
$wtLMS = getLMSValues($mysqli, 'wtageinf', $sex, $age_months);
// LMS fetch for length/height-for-age
$lnLMS = getLMSValues($mysqli, 'lenageinf', $sex, $age_months);
// LMS fetch for head circumference-for-age
$hcLMS = getLMSValues($mysqli, 'hcageinf', $sex, $age_months);

// Function to calculate Z-score using LMS formula
function calculateZScore($X, $L, $M, $S) {
    if ($L == 0) {
        return log($X / $M) / $S;
    }
    return (pow($X / $M, $L) - 1) / ($L * $S);
}

// Calculate Z-scores where data and measurement available
$weight_z = null; $length_z = null; $headcirc_z = null;

if ($weight && $wtLMS) {
    $weight_z = calculateZScore($weight, $wtLMS['l'], $wtLMS['m'], $wtLMS['s']);
}

if ($height && $lnLMS) {
    $length_z = calculateZScore($height, $lnLMS['l'], $lnLMS['m'], $lnLMS['s']);
}

if ($head_circ && $hcLMS) {
    $headcirc_z = calculateZScore($head_circ, $hcLMS['l'], $hcLMS['m'], $hcLMS['s']);
}

// Example insertion/updating UUDATA_zscore table (you may adjust column names accordingly)
// $stmt = $mysqli->prepare("INSERT INTO UUDATA_zscore (sex, age_months, weight_z, length_z, headcirc_z) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE weight_z=VALUES(weight_z), length_z=VALUES(length_z), headcirc_z=VALUES(headcirc_z)");
// $stmt->bind_param("idddd", $sex, $age_months, $weight_z, $length_z, $headcirc_z);
// $stmt->execute();

// Prepare reference percentile results for response
$response = [
    "weight_zscore" => $weight_z !== null ? round($weight_z, 2) : null,
    "weight_percentiles" => $wtLMS ? [
        "P3" => $wtLMS['P3'], "P5" => $wtLMS['P5'], "P10" => $wtLMS['P10'], "P25" => $wtLMS['P25'],
        "P50" => $wtLMS['P50'], "P75" => $wtLMS['P75'], "P90" => $wtLMS['P90'], "P95" => $wtLMS['P95'], "P97" => $wtLMS['P97']
    ] : null,
    "length_zscore" => $length_z !== null ? round($length_z, 2) : null,
    "length_percentiles" => $lnLMS ? [
        "P3" => $lnLMS['P3'], "P5" => $lnLMS['P5'], "P10" => $lnLMS['P10'], "P25" => $lnLMS['P25'],
        "P50" => $lnLMS['P50'], "P75" => $lnLMS['P75'], "P90" => $lnLMS['P90'], "P95" => $lnLMS['P95'], "P97" => $lnLMS['P97']
    ] : null,
    "headcirc_zscore" => $headcirc_z !== null ? round($headcirc_z, 2) : null,
    "headcirc_percentiles" => $hcLMS ? [
        "P3" => $hcLMS['P3'], "P5" => $hcLMS['P5'], "P10" => $hcLMS['P10'], "P25" => $hcLMS['P25'],
        "P50" => $hcLMS['P50'], "P75" => $hcLMS['P75'], "P90" => $hcLMS['P90'], "P95" => $hcLMS['P95'], "P97" => $hcLMS['P97']
    ] : null,
];

// Output JSON response
header('Content-Type: application/json');
echo json_encode($response);

?>