<?php include_once('includes/config.php'); ?>
<?php include_once('mycrypt.php'); ?>

<?php

if ($_SESSION['enckey'] === 0) {
	die("You are not authorized to see the survey");
}
$mcrypt = new EncryptionUtils($_SESSION['enckey']);
	
	$survey_name_id=$_GET['survey_name_id'];
	//$details_sql="SELECT * FROM survey_data_monitoring where survey_data_monitoring_id='".$survey_name_id."'";
	$details_sql="SELECT GROUP_CONCAT(survey_data_monitoring_id) as survey_data_monitoring_id  FROM survey_data_monitoring WHERE survey_name_id = '".$survey_name_id."'";
	
	$details_query=mysqli_query($conn,$details_sql);
	$details_datas=mysqli_fetch_object($details_query);
	
	$survey_data_monitoring_id = $details_datas->survey_data_monitoring_id;

	$details_sqls="SELECT  *  FROM survey_data_monitoring WHERE survey_data_monitoring_id in ($survey_data_monitoring_id)";
	$details_querys=mysqli_query($conn,$details_sqls);
	while($details_data=mysqli_fetch_object($details_querys)){
	
		$client_id = $details_data->client_id;
		$survey_monitoring_id = $details_data->survey_data_monitoring_id;
		$survey_data_json = $details_data->survey_data_json;
		$full_json = $details_data->full_json;
		$survey_data_json_coded = $details_data->survey_data_json_coded;
		$survey_data_json_export = $details_data->survey_data_json_export;
		
		//encrypt data
		$survey_data_json_encrypt = $mcrypt->encrypt($survey_data_json);
		$full_json_encrypt = $mcrypt->encrypt($full_json);
		$para_data_encrypt= $mcrypt->encrypt($para_data);
		$survey_data_json_coded_encrypt = $mcrypt->encrypt($survey_data_json_coded);
		$survey_data_json_export_encrypt = $mcrypt->encrypt($survey_data_json_export);
		
		$sqlupdate="update survey_data_monitoring set survey_data_json='".$survey_data_json_encrypt."', full_json='".$full_json_encrypt."',survey_data_json_coded='".$survey_data_json_coded_encrypt."',survey_data_json_export='".$survey_data_json_export_encrypt."' where survey_data_monitoring_id='".$survey_monitoring_id."'";
		$sqldata=mysqli_query($conn,$sqlupdate);
	}
	if($sqldata){
		echo "Data Enctypted Successfully!!";
	}
	
?>