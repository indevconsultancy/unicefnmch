<?php include_once('../includes/config.php'); ?>
<?php
session_start();
$data=$_SESSION['data'];
ob_end_clean();
header('Content-Type: application/csv');
header('Content-Disposition: attachment; filename=survey_data.csv');
echo $data; unset($_SESSION["data"]); exit();
?>
