<?php include('includes/config.php'); ?>
<?php 
$conn1 = new mysqli("localhost", "unicef_db", "unicef_dblean@!pA", "who_growth_db");

$sqlqry=mysqli_query($conn,"select md.id,md.registration_id,md.baby_weight,md.baby_length,md.baby_head_circumference,reg.sex,DATEDIFF(md.date_of_visit,reg.baby_date_of_birth) AS age_months,reg.gestational_age_LBW from follow_up md,registration_form reg where md.registration_id=reg.id and md.type_of_time!='' and md.baby_weight>0 and reg.gestational_age_LBW>=22 and md.growth_status_fenton=0 limit 0,100 ");

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
//echo $data['gestational_age_LBW'];
//echo $data['age_months'];
$ageinDays=round(($data['age_months']/7),0);
$ga = $data['gestational_age_LBW']+$ageinDays; // gestational age in weeks
$weight = (float)$data['baby_weight']*1000;       // in grams
$length = $data['baby_length'];       // in cm
$head = $data['baby_head_circumference']; 

if ($lms = getLMS($conn1, $sex, $ga, 'weight')) {
	//echo $lms;
	if($weight>0)
{
    $weight_z = calculateZScore($lms['l'], $lms['m'], $lms['s'], $weight);
    $response['weight_for_age'] = round($weight_z, 2);
    $response['weight_percentile'] = zToPercentile($weight_z);

    if ($response['weight_percentile'] < 10) {
        $response['growth_classification'] = 'SGA';
    } elseif ($response['weight_percentile'] <=90) {
        $response['growth_classification'] = 'AGA';
    } else {
        $response['growth_classification'] = 'LGA';
    }
}
else {
	$response['weight_for_age'] = '';
	$response['weight_percentile'] = '';
	$response['growth_classification'] = '';
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
		$response['length_for_age'] = '';
		$response['length_percentile'] = '';
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
		$response['head_circumference_for_age'] = '';
		$response['head_circ_percentile'] = '';
	}

}

//echo "update monitoring_data set fenton_wtage='".$response['weight_for_age']."',fenton_per_wtage='".$response['weight_percentile']."',fenton_lenage='".$response['length_for_age']."',fenton_per_lenage='".$response['length_percentile']."',fenton_wtlen='".$response['weight_for_length']."',fenton_head_circum='".$response['head_circumference_for_age']."',fenton_head_circum_per='".$response['head_circ_percentile']."',fenton_growth_classification='".$response['growth_classification']."',growth_status_fenton=1 where id='".$data['id']."'";
//echo $data['id']."<br/>";
//echo "update follow_up set fenton_wtage='".$response['weight_for_age']."',fenton_per_wtage='".$response['weight_percentile']."',fenton_lenage='".$response['length_for_age']."',fenton_per_lenage='".$response['length_percentile']."',fenton_wtlen='".$response['weight_for_length']."',fenton_head_circum='".$response['head_circumference_for_age']."',fenton_head_circum_per='".$response['head_circ_percentile']."',fenton_growth_classification='".$response['growth_classification']."',growth_status_fenton=1 where id='".$data['id']."'";
mysqli_query($conn,"update follow_up set fenton_wtage='".$response['weight_for_age']."',fenton_per_wtage='".$response['weight_percentile']."',fenton_lenage='".$response['length_for_age']."',fenton_per_lenage='".$response['length_percentile']."',fenton_wtlen='".$response['weight_for_length']."',fenton_head_circum='".$response['head_circumference_for_age']."',fenton_head_circum_per='".$response['head_circ_percentile']."',fenton_growth_classification='".$response['growth_classification']."',growth_status_fenton=1 where id='".$data['id']."'");

}
echo "Data Updated Successfully";

?>