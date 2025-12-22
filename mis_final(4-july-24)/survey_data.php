<?php include_once('includes/config.php'); ?>
<?php
	$sdm=$_REQUEST['sdm'];
	$sqlSurvey="SELECT survey_data_monitoring_id,full_json FROM survey_data_monitoring where survey_id='".$sdm."'";
	$qrySurvey=mysqli_query($conn,$sqlSurvey);
	$data=mysqli_fetch_array($qrySurvey);
	//echo "<pre>";
	echo $full_json=$data['full_json'];
?>