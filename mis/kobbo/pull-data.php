<?php
header('Access-Control-Allow-Origin: *');
header("Content-type: application/json; charset=utf-8");
$api_url = 'https://eu.kobotoolbox.org/api/v2/assets/amt67t3PhSs69DFpvRXqbF/data/?format=json';
$api_key = '7341c8d5e89637cf43252724a6f27b478c3a22a5';  // Replace with your Kobo API key

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => $api_url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => array(
        "Authorization: Token $api_key",
        "Content-Type: application/json"
    ),
));

$response = curl_exec($curl);
echo $response;
if (curl_errno($curl)) {
    echo 'Error: ' . curl_error($curl);
} else {
	//$result=$response
	/*
    $data = json_decode($response, true);
	$arr=array();
	$arr['user_id']=0;
	$arr['survey_id']=1;
	$arr['app_version']="Web";
	$arr['survey_status']=1;
	$arr['cluster_no']='';
	$arr['reason_of_change']='';
	$arr['census_district_code']=0;
	$arr['GPS_latitude_start']='';
	$arr['GPS_longitude_start']='';
	$arr['GPS_altitude_start']='';
	$arr['GPS_accuracy_start']='';
	$arr['is_partially']='No';
	$arr['screen_time_out']=0;
	$arr['screen_time_out_duration']="";
	print_r($data['results']);
	foreach($data['results'] as $key=>$value)
	{
		echo $key[0];
		//print_r($datas[0]);
		      
		
	}*/
	
   // print_r($data);  // This will print the form data
	
}

curl_close($curl);
?>

