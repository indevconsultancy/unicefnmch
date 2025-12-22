<?php include('config.php') ?>
<?php
session_start();

$filename = $_REQUEST['filename'];

$today = date('dmyhis');
//$bid=$_SESSION['bid'];

$ssColumn = '';
$db_column = $_SESSION['db_column'];
$columnArr = explode(",", $db_column);

$sql = "SELECT reg.monitor_name, reg.unique_id_of_body, reg.sncu_id, reg.boby_of_mothers_name, reg.sex, reg.gestational_age_LBW, DATEDIFF(md.date_of_admission, reg.baby_date_of_birth) AS age_in_days, md.id AS mon_id, md.type_of_monitoring, md.admission_weight, md.admission_length, md.admission_head_circumference, CASE WHEN md.type_of_monitoring = 'Date of Admission' THEN 'Admission' WHEN md.type_of_monitoring = 'Discharge Day' THEN 'Discharge' ELSE md.type_of_monitoring END AS mon_type, CASE WHEN md.type_of_monitoring = 'Date of Admission' THEN FLOOR(reg.gestational_age_LBW * 7) WHEN md.type_of_monitoring = 'Discharge Day' THEN FLOOR((reg.gestational_age_LBW * 7 + DATEDIFF(md.date_of_admission, reg.baby_date_of_birth))) ELSE NULL END AS GA FROM registration_form AS reg JOIN monitoring_data AS md ON reg.id = md.registration_id WHERE reg.status = '1' AND md.type_of_monitoring != 'Mother and Newborn at MNCU' AND md.admission_weight > 0 AND reg.gestational_age_LBW >= 24 AND md.growth_status_intergrowth = 0 ORDER BY reg.id DESC";
$head_column = $_SESSION['header_column'];

// header_column,db_column, and query are store in session from the list page 

$qry = mysqli_query($conn, $sql);
$data = $head_column . "\n";

while ($row = mysqli_fetch_array($qry)) {
	foreach ($columnArr as $vl) {
		// $row[$vl] = str_replace(",", "`", $row[$vl]);
		$data .= $row[$vl] . ",";
	}
	$data .= "\n";
}

header('Content-Type: application/csv');
echo header('Content-Disposition: attachment; filename=	' . $filename . '-' . $today . '".csv');

echo $data;
exit();
?>
