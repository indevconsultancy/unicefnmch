<?php 
include('../includes/config.php'); 
include('../mycrypt.php'); 

$mcrypt = new EncryptionUtils($_SESSION['enckey']);

if (isset($_POST['sdm_id']) && isset($_POST['key_value']) && isset($_POST['seq_no'])) {
    $sdm_id = $_POST['sdm_id'];
    $value = $_POST['key_value'];
    $seq_no = $_POST['seq_no'];
	
	$jsonsql = mysqli_query($conn,"select survey_data_monitoring_id,full_json from survey_data_monitoring where survey_data_monitoring_id = '".$sdm_id."'");
	$org_json = mysqli_fetch_object($jsonsql);
	$DecryptedJson = $mcrypt->decrypt($org_json->full_json);
	$full_json_de = json_decode($DecryptedJson);
	$full_json_en = json_encode($full_json_de);
	 
	 /* Review Temp Table Insert */
	 $tempsql = mysqli_query($conn,"select sdm_id from review_survey_data_monitoring where sdm_id = '".$sdm_id."'");
	 $tempdata = mysqli_fetch_object($tempsql);
	 if($tempdata->sdm_id == ''){
		$insertjson = mysqli_query($conn,"INSERT INTO review_survey_data_monitoring SET sdm_id = '".$sdm_id."',dc_full_json = '".$full_json_en."'");
		 echo "UPDATE review_survey_data_monitoring SET dc_full_json = json_set(dc_full_json, '$.survey_data[$seq_no].remarks','".$value."') WHERE id = '".$last_insert_id."'"; 
		$last_insert_id=mysqli_insert_id($conn);
		$update_json = mysqli_query($conn, "UPDATE review_survey_data_monitoring SET dc_full_json = json_set(dc_full_json, '$.survey_data[$seq_no].remarks','".$value."') WHERE id = '".$last_insert_id."'");
		
	 }else{
		 
		 echo "UPDATE review_survey_data_monitoring SET dc_full_json = json_set(dc_full_json, '$.survey_data[$seq_no].remarks','".$value."') WHERE id = '".$sdm_id."'"; 
		 $update_json = mysqli_query($conn, "UPDATE review_survey_data_monitoring SET dc_full_json = json_set(dc_full_json, '$.survey_data[$seq_no].remarks','".$value."') WHERE sdm_id = '".$sdm_id."'");
	 }
	 
	
	
}


?>
