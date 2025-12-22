<?php include('config.php') ?>
<?php
session_start();

$filename = $_REQUEST['filename'];

$today = date('dmyhis');
//$bid=$_SESSION['bid'];

$ssColumn = '';
$db_column = $_SESSION['db_column'];
$columnArr = explode(",", $db_column);

$sql = "SELECT reg.sex,FLOOR((reg.gestational_age_LBW * 7) + DATEDIFF(fol.date_of_visit, reg.baby_date_of_birth)) AS GA,fol.id,fol.baby_weight,fol.baby_length,fol.baby_head_circumference FROM follow_up AS fol JOIN registration_form AS reg ON fol.registration_id = reg.id WHERE fol.status='1' and fol.baby_weight > 0 and reg.gestational_age_LBW >= 24 and fol.growth_status_intergrowth = 0 $filter ORDER BY fol.id DESC";
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
