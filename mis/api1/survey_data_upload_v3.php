<?php
header('Access-Control-Allow-Origin: *');
//header('Content-type: application/json; charset=UTF-8');
include('config.php');
$_REQUEST = json_decode(file_get_contents('php://input'), true);
$data = Profile1($conn);
echo json_encode($data);
exit;

function Profile1($conn)
{
	$qry = '';
	$arrResults = array();
	//$full_json = json_encode($_REQUEST, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	
	$full_json = json_encode($_REQUEST, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	$full_json =str_replace("'","",$full_json);
	$full_json =str_replace("\\","-",$full_json);
	$_REQUEST=array();
	$_REQUEST=json_decode($full_json,true);
  
	$i = 1;
	$survey_id = $_REQUEST['survey_id'];
	$user_id = $_REQUEST['user_id'];
	$app_version = $_REQUEST['app_version'];
	$termination_reason = $_REQUEST['termination_reason'];
	$client_id = getid("users", "client_id", "user_id", $user_id, $conn); 

	$cluster_no = $_REQUEST['cluster_no'];
	$selected_language = $_REQUEST['selected_language'];
	$status = $_REQUEST['status'];
	$end_date1 = $_REQUEST['date_time'];
	$GPS_lat = $_REQUEST['GPS_latitude_start'];
	$GPS_long = $_REQUEST['GPS_longitude_start'];
	$survey_status = $_REQUEST['survey_status'];
	$lat2 = $_REQUEST['GPS_latitude_mid'];
	$long2 = $_REQUEST['GPS_longitude_mid'];
	$lat3 = $_REQUEST['GPS_latitude_end'];
	$long3 = $_REQUEST['GPS_longitude_end'];
	
	$GPS_altitude_start = $_REQUEST['GPS_altitude_start'];
	$GPS_accuracy_start = $_REQUEST['GPS_accuracy_start'];
	
	$GPS_altitude_mid = $_REQUEST['GPS_altitude_mid'];
	$GPS_accuracy_mid = $_REQUEST['GPS_accuracy_mid'];
	
	$GPS_altitude_end = $_REQUEST['GPS_altitude_end'];
	$GPS_accuracy_end = $_REQUEST['GPS_accuracy_end'];
	
	$survey_name = $_REQUEST['survey_name'];
	$survey_name_id = $_REQUEST['survey_name_id'];
	
	
	if ($full_json != '') { 
		/////// Survey data table/////////////////////
		$project_id = 1;

		$date = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
		$created_on = $date->format('Y-m-d H:i:s');
		
		$findsurvey = mysqli_query($conn, "select count(survey_id) as tot, survey_data_monitoring_id from survey_data_monitoring where survey_id='" . $survey_id . "' and user_id='" . $user_id . "'");
		$details_survey = mysqli_fetch_object($findsurvey);
		if ($details_survey->tot > 0) {
			$inser_sql = "update survey_data_monitoring set full_json='" . $full_json . "' where survey_id='" . $survey_id . "' and user_id='" . $user_id . "'  ";
			$insertquery = mysqli_query($conn, $inser_sql);
			$last_id = $details_survey->survey_data_monitoring_id;
			$bkpStatus=" Update survey_data_monitoring_id is ".$last_id." failed";
		}
		else{
			// survey_data_id 1=> Partial sync, 2=> sync
			$inser_sql = "insert into survey_data_monitoring set survey_data_id='1', survey_id='" . $survey_id . "', survey_data_json='null', cluster_code='0', survey_data_json_export='null',  user_type='0', survey_data_json_coded='null', user_id='" . $user_id . "', full_json='" . $full_json . "',survey_status='" . $survey_status . "',survey_name='" . $survey_name . "', survey_name_id='" . $survey_name_id . "', client_id='" . $client_id . "', latitude='" . $GPS_lat . "', longitude='" . $GPS_long . "', created_on='" . $created_on . "',language_id='" . $selected_language . "' ";
			$insertquery = mysqli_query($conn, $inser_sql);
			$last_id = mysqli_insert_id($conn);
			$bkpStatus=" insert into survey_data_monitoring is failed";
		}
	
		if ($insertquery) {
			$arrResults = array("success" => "1", "message" => "Save Succssesful", "survey_data_monitoring_id" => $last_id);
		}else{ 
			//$arrResults = array("success" => "0", "message" => "failed");
			$path = 'backups/backup'.date('Ym').'.txt';
			$myfile = fopen($path, "a") or die("Unable to open file..");
			$txt = $created_on."   ".$bkpStatus."\n";
			$txt .= $full_json." "."\n\n";
			fwrite($myfile, $txt);
			fclose($myfile); 
		}
	}
	return $arrResults;
}

?>