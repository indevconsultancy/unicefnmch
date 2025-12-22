<?php
header('Access-Control-Allow-Origin: *');
//header('Content-type: application/json; charset=UTF-8');
include('config.php');

require_once "jwt.php";
require_once "mycrypt.php";

$headers = getallheaders();
$tokendata = $headers["X-Authorization"] ?? "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($tokendata) {
        // Extract the token from the Authorization header
        $token_array = explode(" ", $tokendata);
        if($token_array[0] == "Bearer"){
            
        
        $token = $token_array[1];
        
        // Verify and decode the JWT token
        $decodedToken = verifyToken($token, JWT_SECRET_KEY,$conn);
        
        if ($decodedToken) {
            $_REQUEST = json_decode(file_get_contents('php://input'), true);
            
			$data = Profile1($conn , $decodedToken->user_taken);
			
			echo json_encode($data);
			exit;
        }
        } else {
             $response = ["status"=>0, "message" => "Auth request is invalid"];
            echo json_encode($response);
            exit;
        }
    }
    
    http_response_code(401); // Unauthorized status code
    $response = ["status"=>0, "error" => "Invalid token"];
    echo json_encode($response);
    
    exit;
} else {
    http_response_code(405); // Method Not Allowed
    $response = ["status"=>0, "message" => "The call method not allowed"];
    echo json_encode($response);
    exit;
}


function findData($array, $search){
	$result = array();
	foreach ($array as $key => $value){
	  foreach ($search as $k => $v){
		if (!isset($value[$k]) || $value[$k] != $v){
		  continue 2;
		}
	  }
	  $result[] = $key;
	}
	//return $result;
	$opt_name='';
	if(isset($result[0])){
		$opt_name = isset($array[$result[0]]['option_name']) ? $array[$result[0]]['option_name'] : "" ;
	}
	
	return $opt_name;
	
}

function Profile1($conn, $clientId = 1)
{
	$qry = '';
	$enc_key = get_encryption_key(SUPER_ADMIN_KEY, SUPER_ADMIN_URL, $clientId);
	if($enc_key === 0 ){
		return array("success" => "0", "message" => "Your client is not active");
	}
	$mcrypt = new EncryptionUtils($enc_key);
	$arrResults = array();
	//$full_json = json_encode($_REQUEST, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	
	$encrypted_incoming = json_encode($_REQUEST, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	$encrypted_array=array();
	$_REQUEST=array();
	$encrypted_array=json_decode($encrypted_incoming,true);
    if (array_key_exists("encrypted_data",$encrypted_array)){
        if($clientId === 0){
            return array("success" => "0", "message" => "Can't save the Data. Clinet is missing.");
        }
      $encrypted_array['encrypted_data'];
      $full_json = $mcrypt->decrypt($encrypted_array['encrypted_data']);
	  
      $full_json =str_replace("'","",$full_json);
	  $full_json =str_replace("\\","-",$full_json);
	  $_REQUEST=json_decode($full_json,true);
	  
    } else {
	  $full_json = json_encode($_REQUEST, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	  $full_json =str_replace("'","",$full_json);
	  $full_json =str_replace("\\","-",$full_json);
	  $_REQUEST=json_decode($full_json,true);
    }
	$full_json_encrypted = $mcrypt->encrypt($full_json);
	
	$i = 1;

	$arr['survey_id'] = $_REQUEST['survey_id'];
	$arr_ml['survey_id'] = $_REQUEST['survey_id'];
	$arr['sequence_unique_id'] = $_REQUEST['sequence_unique_id'];
	$arr['user_id'] = $_REQUEST['user_id'];
	$arr_ml['user_id'] = $_REQUEST['user_id'];
	$arr['termination_reason'] = $_REQUEST['termination_reason'];
	$arr['reason_of_change'] = $_REQUEST['reason_of_change'];
	$arr['description'] = $_REQUEST['description'];
	$arr['reason'] = $_REQUEST['reason'];
	$survey_id = $_REQUEST['survey_id'];
	$user_id = $_REQUEST['user_id'];
	$app_version = $_REQUEST['app_version'];
	$termination_reason = $_REQUEST['termination_reason'];
	$description = $_REQUEST['description'];
	$user_type = getid("users", "role_id", "user_id", $user_id, $conn);
	$user_type_code = getid("roles", "name", "id", $user_type, $conn);
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
	$sequence_id = $_REQUEST['sequence_id'];
	
	$arr['user_type'] = $user_type_code;
	$arr_ml['user_type'] = $user_type_code;
	$arr['app_version'] = $app_version;
	$arr_ml['app_version'] = $app_version;
	
	$sstatus = getid("survey_status_master", "name", "id", $survey_status, $conn);
	$arr['survey_status'] = $sstatus; //getid("survey_status_master", "name", "id", $survey_status, $conn);
	$arr_ml['survey_status'] = $sstatus;  //getid("survey_status_master", "name", "id", $survey_status, $conn);
	$arr['gps_lat_start'] = $GPS_lat;
	$arr['gps_long_start'] = $GPS_long;
	$arr['gps_lat_mid'] = $lat2;
	$arr['gps_long_mid'] = $long2;
	$arr['gps_lat_end'] = $lat3;
	$arr['gps_long_end'] = $long3;
	
	$arr['GPS_altitude_start'] = $GPS_altitude_start;
	$arr['GPS_accuracy_start'] = $GPS_accuracy_start;
	$arr['GPS_altitude_mid'] = $GPS_altitude_mid;
	$arr['GPS_accuracy_mid'] = $GPS_accuracy_mid;
	$arr['GPS_altitude_end'] = $GPS_altitude_end;
	$arr['GPS_accuracy_end'] = $GPS_accuracy_end;
	//	$originalDate = "28/12/2020 00:10:19";
	$originalDate = str_replace("/", "-", $end_date1);
	$end_date = date("Y-m-d h:i:s", strtotime($originalDate));
	$arr['survey_edate'] = date("d-m-Y", strtotime($originalDate));
	$arr['survey_etime'] = date("h:i:s", strtotime($originalDate));
	$survey_name = $_REQUEST['survey_name'];
	$survey_name_id = $_REQUEST['survey_name_id'];
	
	$getOptions = mysqli_query($conn, "SELECT options_language_id, option_id, question_id, language_id, option_name, option_sequence FROM options_language WHERE survey_id='".$survey_name_id."' ");
	$allOptions = mysqli_fetch_all($getOptions, MYSQLI_ASSOC);
	
	
	
	///////////////////////

	/* $findsurvey = mysqli_query($conn, "select count(survey_id) as tot,survey_data_monitoring_id from survey_data_monitoring where survey_id='" . $survey_id . "' and user_id='" . $user_id . "'");
	$details_survey = mysqli_fetch_object($findsurvey);
	if ($details_survey->tot > 0) {
		//$details_survey=mysqli_fetch_object($findsurvey);
		$last_id1 = $details_survey->survey_data_monitoring_id;
		$arrResults = array("success" => "1", "message" => "Already Saved..", "survey_data_monitoring_id" => $last_id1);
	} else { */
		/////////////////////////////////
		$hintques = '';
		foreach ($_REQUEST['hint_record'] as $hintkey => $hintvalue) {
			$hintques .= $hintvalue['h_question_id'] . ",";
		}
		$hintques = substr($hintques, 0, -1);
		/////////////////////////////////
		$error_record = '';
		foreach ($_REQUEST['error_record'] as $error_recordkey => $error_recordvalue) {
			$error_record .= $error_recordvalue['e_question_id'] . ",";
		}
		$error_record = substr($error_record, 0, -1);
		
		/// Group data in sert into survey_data_json 07-06-2023
		$getGroups = mysqli_query($conn,"SELECT question_id,field_name FROM `questions_language` WHERE survey_id='".$survey_name_id."' AND language_id='1' AND input_field_type='begin_group' AND repeated=''");
        if(mysqli_num_rows($getGroups)>0){
            while($grp = mysqli_fetch_object($getGroups)){
                $grp_name = $grp->field_name;
                $grpDatas = isset($_REQUEST[$grp_name]) ? $_REQUEST[$grp_name] : "";
				if(is_array($grpDatas)==1){
					foreach($grpDatas as $grpData){
						foreach($grpData as $grpData1){
							$_REQUEST['survey_data'][] = $grpData1;
						}
					}
				}                
            }
        } 
		/// Group data in sert into survey_data_json end

		$paradata_details = array();
		foreach ($_REQUEST['survey_data'] as $key => $value) {
			$question_id = $value['question_id'];
			//$questions = getone_multi('questions', 'question_name', 'question_id', $question_id, 'status', '0', $conn);

			$question_field_name = $value['field_name'];
			$question_name = '';
			$option_id = $value['option_id'];
			//$option_value = $value['option_value'];
			
			$option_value =str_replace("'","-",$value['option_value']);
			$option_value =str_replace("\\","-",$option_value);
	
			if ($question_field_name == 'current_date') {
				$current_date1 = $option_value;
				$st_date = str_replace("/", "-", $current_date1);
				$current_date = date("Y-m-d h:i:s", strtotime($st_date));
				$arr['current_sdate'] = date("d-m-Y", strtotime($st_date));
				$arr['current_sday'] = date("D", strtotime($st_date));
				$arr['current_stime'] = date("h:i:s", strtotime($st_date));
			}
			if ($value['duration'] != '') {
				$paradata_details['field_name'] = $value['field_name'];
				$paradata_details['duration'] = $value['duration'];
				$paradata_details['gps'] = str_replace(",", "~", $value['gps']);
				$paradata_details['timestamp'] = $value['timestamp'];
				$paradata_details['keystrok'] = $value['key_stroke'];
				$paradata[] = $paradata_details;
				$paradata_details = array();
			}
			$option_name = '';
			if ($option_value != '') {
				$option_data = $option_value;
				$option_data_ml = $option_value;
				$option_data_coded = $option_value;
			} else {
				if (strpos($option_id, ',') == false) {
					$option_data_coded = $option_id;
					
					$search = array("question_id"=>$question_id,"option_sequence"=>$option_id, "language_id"=>1);
					
					$optdata = findData($allOptions, $search); //getone_multi('options', 'option_name', 'question_id', $question_id, 'option_sequence', $key, $conn);
					$optdata =str_replace("'","-",$optdata);
					$optdata =str_replace("\\","-",$optdata);
					
					
					$search = array("question_id"=>$question_id,"option_sequence"=>$option_id, "language_id"=>$selected_language);
					$optdata_ml = findData($allOptions, $search);  //getone_multi_language('options_language', 'option_name', 'question_id', $question_id, 'option_sequence', $option_id, $selected_language, $conn);
					$optdata_ml =str_replace("'","-",$optdata_ml);
					$optdata_ml =str_replace("\\","-",$optdata_ml);
					
			
					$option_data = $optdata;
					$option_data_ml = $optdata_ml;
				} else {
					$option_datamulti = '';
					$option_datamulti_ml = '';
					$optval = explode(',', $option_id);
					foreach ($optval as $key) {
						
						$search = array("question_id"=>$question_id,"option_sequence"=>$key, "language_id"=>1);
						$optdata1 = findData($allOptions, $search); //getone_multi('options', 'option_name', 'question_id', $question_id, 'option_sequence', $key, $conn);
						$optdata1 =str_replace("'","-",$optdata1);
						$optdata1 =str_replace("\\","-",$optdata1);
						
						$search = array("question_id"=>$question_id,"option_sequence"=>$key, "language_id"=>$selected_language);
						$optdata2 = findData($allOptions, $search); //  getone_multi_language('options_language', 'option_name', 'question_id', $question_id, 'option_sequence', $key, $selected_language, $conn);
						$optdata2 =str_replace("'","-",$optdata2);
						$optdata2 =str_replace("\\","-",$optdata2);
						
						
						
						$option_datamulti .= $optdata1 . ", ";
						$option_datamulti_ml .=  $optdata2. ", ";
					}
					$option_data = substr(trim($option_datamulti), 0, -1);
					$option_data_ml = substr(trim($option_datamulti_ml), 0, -1);
					$option_data_coded = $option_id;
				}
			}

			$arr[$question_field_name] = $option_data;
			$arr_ml[$question_field_name] = $option_data_ml;

			$arrCoded[$question_field_name] = $option_data_coded;
		}
		$paradatas = json_encode($paradata, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		// print_r($arr);
		// die;
		$json = $mcrypt->encrypt(json_encode($arr, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		$json_ml = $mcrypt->encrypt(json_encode($arr_ml, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		$jsonCoded = $mcrypt->encrypt(json_encode($arrCoded, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		//$json = json_encode($arr);
		if ($json != '' && !empty($survey_id)) {
			/////// Survey data table/////////////////////
			$project_id = 1;

			$date = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
			$created_on = $date->format('Y-m-d H:i:s');
			
			$findsurvey = mysqli_query($conn, "select count(survey_id) as tot,survey_data_monitoring_id from survey_data_monitoring where survey_id='" . $survey_id . "' and user_id='" . $user_id . "'");
			$details_survey = mysqli_fetch_object($findsurvey);
			if ($details_survey->tot > 0) {
				
				$inser_sql = "update survey_data_monitoring set created_on='".$created_on."', survey_data_json='" . $json . "', survey_data_json_coded='" . $jsonCoded . "', full_json='" . $full_json_encrypted . "',survey_status='" . $survey_status . "', cluster_code='" . $cluster_no . "', user_type='" . $user_type . "', termination_reason='" . $termination_reason . "',description='".$description."', hint_record='" . $hintques . "', error_record='" . $error_record . "', para_data='" . $paradatas . "', survey_data_json_export='" . $json_ml . "'  where survey_id='" . $survey_id . "' and user_id='" . $user_id . "'  ";
				$insertquery = mysqli_query($conn, $inser_sql);
				$last_id = $details_survey->survey_data_monitoring_id;
				$bkpStatus=" Update survey_data_monitoring_id is ".$last_id." failed";
			}
			else{
			   $inser_sql = "insert into survey_data_monitoring set survey_id='" . $survey_id . "',user_id='" . $user_id . "',survey_data_json='" . $json . "', survey_data_json_coded='" . $jsonCoded . "', full_json='" . $full_json_encrypted . "',cluster_code='" . $cluster_no . "',survey_status='" . $survey_status . "',user_type='" . $user_type . "',termination_reason='" . $termination_reason . "',description='".$description."',survey_name='" . $survey_name . "', survey_name_id='" . $survey_name_id . "', client_id='" . $client_id . "', latitude='" . $GPS_lat . "', longitude='" . $GPS_long . "', created_on='" . $created_on . "', hint_record='" . $hintques . "', error_record='" . $error_record . "', para_data='" . $paradatas . "',language_id='" . $selected_language . "', survey_data_json_export='" . $json_ml . "' ";
				$insertquery = mysqli_query($conn, $inser_sql);
				$last_id = mysqli_insert_id($conn);
				$bkpStatus=" insert into survey_data_monitoring is failed";
			}
			

			if ($insertquery) {
				$sequenceSql=mysqli_query($conn,"insert into survey_data_sequence set sequence_id='".$sequence_id."',user_id='".$user_id."',survey_name_id='".$survey_name_id."'");
				$arrResults = array("success" => "1", "message" => "Save Succssesful", "survey_data_monitoring_id" => $last_id);
			}else{ 
				$arrResults = array("success" => "0", "message" => "failed");
				$path = 'backups/backup'.date('Ym').'.txt';
				$myfile = fopen($path, "a") or die("Unable to open file..");
				$txt = $created_on."   ".$bkpStatus."\n";
				$txt .= $full_json_encrypted." "."\n\n";
				fwrite($myfile, $txt);
				fclose($myfile);
			}
		}else{
			$arrResults = array("success" => "0", "message" => "failed");
		}
	//}
	return $arrResults;
}

?>