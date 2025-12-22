<?php include('includes/config.php'); ?>
<?php 

$conn1 = new mysqli("localhost", "root", "indev@123", "who_growth_db");

/*
$sqlqry=mysqli_query($conn,"select md.id,md.registration_id,md.admission_weight,md.admission_length,md.admission_head_circumference,reg.sex,DATEDIFF(md.date_of_admission,reg.baby_date_of_birth) AS age_months,reg.gestational_age_LBW from monitoring_data md,registration_form reg where md.registration_id=reg.id and md.type_of_monitoring!='Mother and Newborn at MNCU' and md.admission_weight>0 and md.growth_status=0 limit 0,50 ");

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

$weight = $data['admission_weight'];
$height = $data['admission_length']; // in cm
$head_circ = $data['admission_head_circumference']; // in cm
if($data['sex']=="Male") { $gender = 1; } else { $gender = 2; }

$age_months = $data['age_months']; // integer

$bmi = $weight / pow($height / 100, 2);



if ($lms = fetchLMS($conn1, "wtageinf", $age_months, $gender)) {
	if($weight>0)
	{
    $response['weight_for_age'] = round(calculateZScore($lms['l'], $lms['m'], $lms['s'], $weight), 2);
	//echo zToPercentile($response['weight_for_age']);
	$response['weight_percentile'] = zToPercentile($response['weight_for_age']);
	}
	else 
	{
		$response['weight_for_age'] ='NA';
		$response['weight_percentile'] ='NA';
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
		$response['length_for_age'] ='NA';
		$response['length_percentile'] ='NA';
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
		$response['head_circumference_for_age'] ='NA';
		$response['head_circ_percentile'] ='NA';
	}
}

// Weight-for-length/height
if($height>0 && $weight>0)
{
if($age_months<=730)
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
	$response['weight_for_length'] ='NA';
	$response['weight_for_length_percentile'] ='NA';
}
echo $data['id']."</br>";
mysqli_query($conn,"update monitoring_data set who_wtage='".$response['weight_for_age']."',who_lenage='".$response['length_for_age']."',who_wtlen='".$response['weight_for_length']."',who_head_circum='".$response['head_circumference_for_age']."',who_per_wtage='".$response['weight_percentile']."',who_per_lenage='".$response['length_percentile']."',who_per_wtlen='".$response['weight_for_length_percentile']."',who_head_circum_per='".$response['head_circ_percentile']."',growth_status=1 where id='".$data['id']."'");

}
*/

// Fenton Chart

$sqlqry=mysqli_query($conn,"select md.id,md.registration_id,md.admission_weight,md.admission_length,md.admission_head_circumference,reg.sex,DATEDIFF(md.date_of_admission,reg.baby_date_of_birth) AS age_months,reg.gestational_age_LBW from monitoring_data md,registration_form reg where md.registration_id=reg.id and md.type_of_monitoring!='Mother and Newborn at MNCU' and md.admission_weight>0 and reg.gestational_age_LBW>=22 and md.type_of_monitoring='Discharge Day' and md.growth_status_fenton=0 limit 0,50 ");

     // in cm

function getLMS($conn1, $sex, $ga, $param) {
	//echo "Gender".$sex."GA".$ga."Param".$param;
    $stmt = $conn1->prepare("SELECT l, m, s FROM fenton_growth WHERE sex=? AND ga_weeks=? AND parameter=?");
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

while($data=mysqli_fetch_array($sqlqry))
{
 //echo $data['sex'];
if($data['sex']=="Male") { $sex = 1; } else { $sex = 2; }         // 1=male, 2=female
//$ga = $data['gestational_age_LBW']; // gestational age in weeks
$ageinDays=round(($data['age_months']/7),0);
$ga = round(($data['gestational_age_LBW']+$ageinDays),0);
$weight = (float)$data['admission_weight']*1000;       // in grams
$length = $data['admission_length'];       // in cm
$head = $data['admission_head_circumference']; 

if ($lms = getLMS($conn1, $sex, $ga, 'weight')) {
	//echo $lms;
	if($weight>0)
{
    $weight_z = calculateZScore($lms['l'], $lms['m'], $lms['s'], $weight);
    $response['weight_for_age'] = round($weight_z, 2);
    $response['weight_percentile'] = zToPercentile($weight_z);

    if ($weight_z < -1.28) {
        $response['growth_classification'] = 'SGA';
    } elseif ($weight_z > 1.28) {
        $response['growth_classification'] = 'LGA';
    } else {
        $response['growth_classification'] = 'AGA';
    }
}
else {
	$response['weight_for_age'] = 'NA';
	$response['weight_percentile'] = 'NA';
	$response['growth_classification'] = 'NA';
}
}

// Length Z

if ($lms = getLMS($conn1, $sex, $ga, 'length')) {
	if($length>0)
   {
    $length_z = calculateZScore($lms['l'], $lms['m'], $lms['s'], $length);
    $response['length_for_age'] = round($length_z, 2);
    $response['length_percentile'] = zToPercentile($length_z);
   }
   else {
		$response['length_for_age'] = 'NA';
		$response['length_percentile'] = 'NA';
	}
}

// Head Circumference Z

if ($lms = getLMS($conn1, $sex, $ga, 'head_circ')) {
	if($head>0)
	{
		$head_z = calculateZScore($lms['l'], $lms['m'], $lms['s'], $head);
		$response['head_circumference_for_age'] = round($head_z, 2);
		$response['head_circ_percentile'] = zToPercentile($head_z);
	}
	else {
		$response['head_circumference_for_age'] = 'NA';
		$response['head_circ_percentile'] = 'NA';
	}

}
echo $data['id']."<br/>";
//echo "update monitoring_data set fenton_wtage='".$response['weight_for_age']."',fenton_per_wtage='".$response['weight_percentile']."',fenton_lenage='".$response['length_for_age']."',fenton_per_lenage='".$response['length_percentile']."',fenton_wtlen='".$response['weight_for_length']."',fenton_head_circum='".$response['head_circumference_for_age']."',fenton_head_circum_per='".$response['head_circ_percentile']."',fenton_growth_classification='".$response['growth_classification']."',growth_status_fenton=1 where id='".$data['id']."'";

mysqli_query($conn,"update monitoring_data set fenton_wtage='".$response['weight_for_age']."',fenton_per_wtage='".$response['weight_percentile']."',fenton_lenage='".$response['length_for_age']."',fenton_per_lenage='".$response['length_percentile']."',fenton_wtlen='".$response['weight_for_length']."',fenton_head_circum='".$response['head_circumference_for_age']."',fenton_head_circum_per='".$response['head_circ_percentile']."',fenton_growth_classification='".$response['growth_classification']."',growth_status_fenton=1 where id='".$data['id']."'");


}









?>