<?php include_once('includes/config.php'); ?>

<?php

	$getPatialData = mysqli_query($conn, "select survey_data_monitoring_id ,survey_name, full_json from survey_data_monitoring where survey_data_id='1' limit 10 ");
	while($patialData = mysqli_fetch_array($getPatialData)){
		$full_json = $patialData['full_json'];
		$survey_data_monitoring_id = $patialData['survey_data_monitoring_id'];
		
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://mquad.org/mis/api/sync_data_ss.php',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS =>$full_json,
		  CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json'
		  ),
		));

		$response = curl_exec($curl);
		curl_close($curl);
		$res = json_decode($response);
		if($res->success=="1"){
			$sync = mysqli_query($conn, "update survey_data_monitoring set survey_data_id='2' where survey_data_monitoring_id='".$survey_data_monitoring_id."' ");
			if($sync){
				echo "success"; 
			}
		}else{
			$sync = mysqli_query($conn, "update survey_data_monitoring set survey_data_id='3' where survey_data_monitoring_id='".$survey_data_monitoring_id."' ");
			if($sync){
				echo "failed"; 
			}
		}
	}

?>