<?php
header("Content-Type: application/json");
$conn = new mysqli("localhost", "unicef_db", "unicef_dblean@!pA", "who_growth_db");

$data = json_decode(file_get_contents("php://input"), true);
$sex = $data['gender'];          // 1=male, 2=female
$ga = $data['gestational_age']; // gestational age in weeks
$weight = $data['weight'];       // in grams
$length = $data['length'];       // in cm
$head = $data['head_circ'];      // in cm

function getLMS($conn, $sex, $ga, $param) {
    $stmt = $conn->prepare("SELECT l, m, s FROM fenton_growth WHERE sex=? AND ga_weeks=? AND parameter=?");
    $stmt->bind_param("ids", $sex, $ga, $param);
    $stmt->execute();
    $stmt->bind_result($l, $m, $s);
    return $stmt->fetch() ? compact('l', 'm', 's') : null;
}

function calculateZScore($l, $m, $s, $x) {
    if ($l == 0) {
        return log($x / $m) / $s;
    } else {
        return ((pow(($x / $m), $l) - 1) / ($l * $s));
    }
}

function zToPercentile($z) {
    return round(100 * (0.5 * (1 + erf($z / sqrt(2)))), 0);
}

if (!function_exists('erf')) {
    function erf($x) {
        // Approximation of error function
        $a1 =  0.254829592;
        $a2 = -0.284496736;
        $a3 =  1.421413741;
        $a4 = -1.453152027;
        $a5 =  1.061405429;
        $p  =  0.3275911;

        $sign = $x < 0 ? -1 : 1;
        $x = abs($x);
        $t = 1.0 / (1.0 + $p * $x);
        $y = 1.0 - ((((( $a5 * $t + $a4 ) * $t) + $a3 ) * $t + $a2 ) * $t + $a1 ) * $t * exp(-$x * $x);

        return $sign * $y;
    }
}

$response = [];

// Weight Z and SGA/AGA/LGA Classification
if ($lms = getLMS($conn, $sex, $ga, 'weight')) {
    $weight_z = calculateZScore($lms['l'], $lms['m'], $lms['s'], $weight);
    $response['weight_zscore'] = round($weight_z, 2);
    $response['weight_percentile'] = zToPercentile($weight_z);

    if ($weight_z < -1.28) {
        $response['growth_classification'] = 'SGA';
    } elseif ($weight_z > 1.28) {
        $response['growth_classification'] = 'LGA';
    } else {
        $response['growth_classification'] = 'AGA';
    }
}

// Length Z
if ($lms = getLMS($conn, $sex, $ga, 'length')) {
    $length_z = calculateZScore($lms['l'], $lms['m'], $lms['s'], $length);
    $response['length_zscore'] = round($length_z, 2);
    $response['length_percentile'] = zToPercentile($length_z);
}

// Head Circumference Z
if ($lms = getLMS($conn, $sex, $ga, 'head_circ')) {
    $head_z = calculateZScore($lms['l'], $lms['m'], $lms['s'], $head);
    $response['head_circ_zscore'] = round($head_z, 2);
    $response['head_circ_percentile'] = zToPercentile($head_z);
}

echo json_encode($response);


