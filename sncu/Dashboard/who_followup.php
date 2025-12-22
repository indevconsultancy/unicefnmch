<?php include('includes/config.php'); ?>
<?php 
$conn1 = new mysqli("localhost", "unicef_db", "unicef_dblean@!pA", "who_growth_db");


$sqlqry=mysqli_query($conn,"select md.id,md.registration_id,md.baby_weight,md.baby_length,md.baby_head_circumference,reg.sex,DATEDIFF(md.date_of_visit,reg.baby_date_of_birth) AS age_months,reg.gestational_age_LBW from follow_up md,registration_form reg where md.registration_id=reg.id and md.type_of_time!='' and md.baby_weight>0 and md.growth_status=0 limit 0,100");

function calculateZScore($l, $m, $s, $x) {
    return ($l == 0) ? log($x / $m) / $s : ((pow(($x / $m), $l) - 1) / ($l * $s));
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

// Fetch LMS from correct table
function fetchLMS($conn1, $table, $value, $sex, $key = 'age') {

	//echo "SELECT l, m, s FROM $table WHERE $key = ? AND sex = ?";
    $stmt = $conn1->prepare("SELECT l, m, s FROM $table WHERE $key = ? AND sex = ?");
    $stmt->bind_param("di", $value, $sex);
    $stmt->execute();
    $stmt->bind_result($l, $m, $s);
    return $stmt->fetch() ? compact('l', 'm', 's') : null;
}


while($data=mysqli_fetch_array($sqlqry))
{

$weight = $data['baby_weight'];
$height = $data['baby_length']; // in cm
$head_circ = $data['baby_head_circumference']; // in cm
if($data['sex']=="Male") { $gender = 1; } else { $gender = 2; }

$age_months = $data['age_months']; // integer

$bmi = $weight / pow($height / 100, 2);



// Weight-for-age
if ($lms = fetchLMS($conn1, "wtageinf", $age_months, $gender)) {
	if($weight>0)
	{
    $response['weight_for_age'] = round(calculateZScore($lms['l'], $lms['m'], $lms['s'], $weight), 2);
	//echo zToPercentile($response['weight_for_age']);
	$response['weight_percentile'] = zToPercentile($response['weight_for_age']);
	if ($response['weight_percentile'] < 10) {
			$response['growth_classification'] = 'SGA';
		} elseif ($response['weight_percentile'] <=90) {
			$response['growth_classification'] = 'AGA';
		} else {
			$response['growth_classification'] = 'LGA';
		}
	}
	else 
	{
		$response['weight_for_age'] ='';
		$response['weight_percentile'] ='';
	}
}

// Length/height-for-age
if ($lms = fetchLMS($conn1, "lenageinf", $age_months, $gender)) {
	if($height>0)
	{
    $response['length_for_age'] = round(calculateZScore($lms['l'], $lms['m'], $lms['s'], $height), 2);
	$response['length_percentile'] = zToPercentile($response['length_for_age']);
	}
	else{
		$response['length_for_age'] ='';
		$response['length_percentile'] ='';
	}
}

// Head circumference-for-age
if ($lms = fetchLMS($conn1, "hcageinf", $age_months, $gender)) {
	if($head_circ>20)
	{
    $response['head_circumference_for_age'] = round(calculateZScore($lms['l'], $lms['m'], $lms['s'], $head_circ), 2);
	$response['head_circ_percentile'] = zToPercentile($response['head_circumference_for_age']);
	}
	else {
		$response['head_circumference_for_age'] ='';
		$response['head_circ_percentile'] ='';
	}
}

// Weight-for-length/height
if($height>0 && $weight>0)
{
if($age_months>=0 && $age_months<=730)
{
if ($lms = fetchLMS($conn1, "wtleninf", $height, $gender, "length")) {
	
    $response['weight_for_length'] = round(calculateZScore($lms['l'], $lms['m'], $lms['s'], $weight), 2);
	$response['weight_for_length_percentile'] = zToPercentile($response['weight_for_length']);
}
}
else
{
	if ($lms = fetchLMS($conn1, "wtheightinf", $height, $gender, "length")) {
    $response['weight_for_length'] = round(calculateZScore($lms['l'], $lms['m'], $lms['s'], $weight), 2);
    $response['weight_for_length_percentile'] = zToPercentile($response['weight_for_length']);
}
}
}
else {
	$response['weight_for_length'] ='';
	$response['weight_for_length_percentile'] ='';
}
/*
// BMI-for-age (optional if you add this table later)
if ($lms = fetchLMS($conn, "wtstat", $age_months, $gender)) {
    $response['bmi_for_age'] = round(calculateZScore($lms['l'], $lms['m'], $lms['s'], $bmi), 2);
}
*/
//echo fetchLMS($conn, "wtageinf", $age_months, $gender);
//echo json_encode($response);


//echo $data['id']."</br>";
//echo "update follow_up set who_wtage='".$response['weight_for_age']."',who_lenage='".$response['length_for_age']."',who_wtlen='".$response['weight_for_length']."',who_head_circum='".$response['head_circumference_for_age']."',who_per_wtage='".$response['weight_percentile']."',who_per_lenage='".$response['length_percentile']."',who_per_wtlen='".$response['weight_for_length_percentile']."',who_head_circum_per='".$response['head_circ_percentile']."',growth_status=1 where id='".$data['id']."'";
mysqli_query($conn,"update follow_up set who_wtage='".$response['weight_for_age']."',who_lenage='".$response['length_for_age']."',who_wtlen='".$response['weight_for_length']."',who_head_circum='".$response['head_circumference_for_age']."',who_per_wtage='".$response['weight_percentile']."',who_per_lenage='".$response['length_percentile']."',who_per_wtlen='".$response['weight_for_length_percentile']."',who_head_circum_per='".$response['head_circ_percentile']."',who_classification='".$response['growth_classification']."',growth_status=1 where id='".$data['id']."'");
}
echo "Data Updated Successfully";

?>