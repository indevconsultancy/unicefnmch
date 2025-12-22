<?php 
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require_once "api/mycrypt.php";
include_once('includes/config.php');
include_once('system_info.php');

if($_SESSION['enckey'] === 0){
    die("You are not authorized to see the survey");
}
$mcrypt = new EncryptionUtils($_SESSION['enckey']);

?>

<?php
	
	if(isset($_POST['formdata'])){
		date_default_timezone_set('Asia/Kolkata');
		$os_version = getOS(); // get system_info page 
		$device_name = getBrowser(); // get system_info page 
		$digits = 10;
		$unique_id = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);10;
		$formdatas = $_POST['formdata']; 
	
		$survey_id = $_POST['surveyId'];
		$language_id = $_POST['languageId'];
		$survey_name = $_POST['survey_name'];
		$allsGroupDatas = $_POST['allsGroupData'];
		$allsRepeatGroupDatas = $_POST['allsRepeatGroupDatas'];
		
		$GPS_latitude_start = $_POST['GPS_latitude_start'];
		$GPS_longitude_start = $_POST['GPS_longitude_start'];
		
		$start_date_time = $_POST['start_date_time'];
		$end_date_time  = date("Y-m-d H:i:s");
		
		$user_ids = getone($conn, "survey", "user_id", "id", $survey_id);
		$sdataArrf["client_id"] = getone($conn, "survey", "client_id", "id", $survey_id);
		$clientId=$_SESSION['client_id'] ? $_SESSION['client_id'] : $client_id; 
		$sdataArrf["user_id"] = $_SESSION['user_id'] ? $_SESSION['user_id'] : $user_ids;
		$sdataArrf["survey_name"]= $survey_name;
		$sdataArrf["selected_language"]= $language_id;
		$sdataArrf["survey_name_id"]=$survey_id;
		$sdataArrf["survey_id"]=$unique_id;
		$sdataArrf["app_version"]="web";
		$sdataArrf["survey_status"]="1";
		$sdataArrf["cluster_no"]="1234";
		$sdataArrf["GPS_latitude_start"]=$GPS_latitude_start;
		$sdataArrf["GPS_longitude_start"]=$GPS_longitude_start;
		//Add new field 10July2024
		$sdataArrf["send_survey_data"]='complete';
		$sdataArrf["start_date_time"]=$start_date_time;
		$sdataArrf["end_date_time"]=$end_date_time;
		$sdataArrf["os_version"]=$os_version;
		$sdataArrf["device_name"]=$device_name;
		$sdataArrf["device_id"]='Web';
		$sdataArrf["unique_id"]='';
		$sdataArrf["date_time"]=date("Y-m-d H:i:s");
		$sdataArrf["ip_address"]=$_SERVER['REMOTE_ADDR'];
		$sdataArrf["sequence_id"]=$unique_id;
		$sdataArrf["sequence_unique_id"]=$unique_id;
		
		$getQuestions = mysqli_query($conn,"SELECT question_id, field_name, input_field_type FROM questions_language WHERE survey_id='".$survey_id."' AND language_id='".$language_id."' AND input_field_type!='note'");
		while($question = mysqli_fetch_object($getQuestions)){
			$quesArr[$question->field_name] = $question->question_id;
			$quesTypes[$question->field_name] = $question->input_field_type;
		}
		
		foreach($formdatas as $formdata){
			$fname = $formdata['name'];
			$fvalue = $formdata['value'];

			$sdata["field_name"] = $fname;
			$sdata["question_id"] = $quesArr[$fname];
			$qType = $quesTypes[$fname];
			$option_id="";
			$option_value = "";
			if($qType=="select_one" || $qType=="select_multiple"){
				$option_id=$fvalue;
			}else{
				$option_value=$fvalue;
			}
			$sdata["option_id"] = $option_id;
			$sdata["option_value"] = $option_value;
			$sdataArr[]=$sdata;
			$sdata = array();
		}
		$sdataArrf["survey_data"]=$sdataArr;
	
		foreach ($allsRepeatGroupDatas as $rgKey => $allsRepeatGroupData) {

			//$sdataArrf[$rgKey]=$allsRepeatGroupData;
			foreach ($allsRepeatGroupData as $groupIndex => $repeatGroup) {
				$rgDatas = [];
				foreach ($repeatGroup as $rFieldkey => $allsRepGroupDataValue) {
					$rgData = [];
					$rgData["field_name"] = $rFieldkey;
					$rgData["question_id"] = $quesArr[$rFieldkey] ?? '';
					$rqType = $quesTypes[$rFieldkey] ?? 'text';
					$option_id = "";
					$option_value = "";
					if ($rqType == "select_one" || $rqType == "select_multiple") {
						$option_id = $allsRepGroupDataValue;
					} else {
						$option_value = $allsRepGroupDataValue;
					}
					$rgData["option_id"] = $option_id;
					$rgData["option_value"] = $option_value;
					$rgDatas[] = $rgData;
					$rgData=[];
				}
				$sdataArrf[$rgKey][$groupIndex]= $rgDatas;
				$rgDatas=[];
			}
		}

		foreach($allsGroupDatas as $dgKey=>$allsGroupData){
			foreach($allsGroupData as $sFieldkey=>$allsGroupDataValue){
				
				$ssgData["field_name"] = $sFieldkey;
				$ssgData["question_id"] = $quesArr[$sFieldkey];
				$qType = $quesTypes[$sFieldkey];
				$option_id="";
				$option_value = "";
				if($qType=="select_one" || $qType=="select_multiple"){
					$option_id=$allsGroupDataValue;
				}else{
					$option_value=$allsGroupDataValue;
				}
				$ssgData["option_id"] = $option_id;
				$ssgData["option_value"] = $option_value;
				$ssgDatas[] = $ssgData;
				$ssgData = [];
			}
			
			$sdataArrf[$dgKey][] = $ssgDatas;
			$ssgDatas = [];
		}
		// print_r($sdataArrf);
		// die();
		$postdatas = json_encode($sdataArrf);
		 $url = 'https://unicef.indevconsultancy.in/mis/api/survey_data_upload_v2_web.php';
		$postdatas = json_encode($sdataArrf);
		$ch = curl_init($url); 
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdatas);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		$result = curl_exec($ch);
		curl_close($ch);
		echo $result;  
		//echo json_encode($result);  
		//$resultData = array("status"=>1,"message"=>"Data save successfully","result"=>$result);
		//echo json_encode($result);
	}
	
?>
<?php
$rawData = file_get_contents('php://input');
$data = json_decode($rawData, true);
if (isset($data['fData']) && $data['fData']!='' && isset($data['partialData']) && $data['partialData'] == 'PARTIAL-DATA') {

	date_default_timezone_set('Asia/Kolkata');
	$os_version = getOS(); // get system_info page 
	$device_name = getBrowser(); // get system_info page 
	$digits = 10;
	$unique_id = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);10;
	$formdatas = $data['fData']; 

	$survey_id = $data['surveyId'];
	$language_id = $data['languageId'];
	$survey_name = $data['survey_name'];
	$sessionID = $data['sessionID'];
	
	$GPS_latitude_start = $data['GPS_latitude_start'];
	$GPS_longitude_start =$data['GPS_longitude_start'];
	$start_date_time =$data['start_date_time'];
	$end_date_time='';
	
	$allsGroupDatas = $data['allsGroupData'];
	$allsRepeatGroupDatas = $data['allsRepeatGroupDatas'];
	$user_ids = getone($conn, "survey", "user_id", "id", $survey_id);
	$sdataArrf["user_id"] = $_SESSION['user_id'] ? $_SESSION['user_id'] : $user_ids;
	$sdataArrf["survey_name"]= $survey_name;
	$sdataArrf["selected_language"]= $language_id;
	$sdataArrf["survey_name_id"]=$survey_id;
	$sdataArrf["survey_id"]=$unique_id;
	$sdataArrf["app_version"]="web";
	$sdataArrf["survey_status"]="1";
	$sdataArrf["cluster_no"]="1234";
	$sdataArrf["GPS_latitude_start"]=$GPS_latitude_start;
	$sdataArrf["GPS_longitude_start"]=$GPS_longitude_start;
	//Add new field 10July2024
	$sdataArrf["send_survey_data"]='Partial';
	$sdataArrf["start_date_time"]=$start_date_time;
	$sdataArrf["end_date_time"]=$end_date_time;
	$sdataArrf["os_version"]=$os_version;
	$sdataArrf["device_name"]=$device_name;
	$sdataArrf["device_id"]='Web';
	$sdataArrf["unique_id"]=$unique_id;
	$sdataArrf["date_time"]=date("Y-m-d H:i:s");
	$sdataArrf["ip_address"]=$_SERVER['REMOTE_ADDR'];
	$sdataArrf["sequence_id"]=$unique_id;
	$sdataArrf["sequence_unique_id"]=$unique_id;
	
	$getQuestions = mysqli_query($conn,"SELECT question_id, field_name, input_field_type FROM questions_language WHERE survey_id='".$survey_id."' AND language_id='".$language_id."' AND input_field_type!='note'");
	while($question = mysqli_fetch_object($getQuestions)){
		$quesArr[$question->field_name] = $question->question_id;
		$quesTypes[$question->field_name] = $question->input_field_type;
	}
	
	foreach($formdatas as $formdata){
		$fname = $formdata['name'];
		$fvalue = $formdata['value'];

		$sdata["field_name"] = $fname;
		$sdata["question_id"] = $quesArr[$fname];
		$qType = $quesTypes[$fname];
		$option_id="";
		$option_value = "";
		if($qType=="select_one" || $qType=="select_multiple"){
			$option_id=$fvalue;
		}else{
			$option_value=$fvalue;
		}
		$sdata["option_id"] = $option_id;
		$sdata["option_value"] = $option_value;
		$sdataArr[]=$sdata;
		$sdata = array();
	}
	$sdataArrf["survey_data"]=$sdataArr;
	//print_r($sdataArrf);
	
	foreach($allsGroupDatas as $dgKey=>$allsGroupData){
		foreach($allsGroupData as $sFieldkey=>$allsGroupDataValue){
			
			$ssgData["field_name"] = $sFieldkey;
			$ssgData["question_id"] = $quesArr[$sFieldkey];
			$qType = $quesTypes[$sFieldkey];
			$option_id="";
			$option_value = "";
			if($qType=="select_one" || $qType=="select_multiple"){
				$option_id=$allsGroupDataValue;
			}else{
				$option_value=$allsGroupDataValue;
			}
			$ssgData["option_id"] = $option_id;
			$ssgData["option_value"] = $option_value;
			$ssgDatas[] = $ssgData;
			$ssgData = [];
		}
		
		$sdataArrf[$dgKey][] = $ssgDatas;
		$ssgDatas = [];
	}
	foreach ($allsRepeatGroupDatas as $rgKey => $allsRepeatGroupData) {

			foreach ($allsRepeatGroupData as $groupIndex => $repeatGroup) {
				$rgDatas = [];
				foreach ($repeatGroup as $rFieldkey => $allsRepGroupDataValue) {
					$rgData = [];
					$rgData["field_name"] = $rFieldkey;
					$rgData["question_id"] = $quesArr[$rFieldkey] ?? '';
					$rqType = $quesTypes[$rFieldkey] ?? 'text';
					$option_id = "";
					$option_value = "";
					if ($rqType == "select_one" || $rqType == "select_multiple") {
						$option_id = $allsRepGroupDataValue;
					} else {
						$option_value = $allsRepGroupDataValue;
					}
					$rgData["option_id"] = $option_id;
					$rgData["option_value"] = $option_value;
					$rgDatas[] = $rgData;
					$rgData=[];
				}
				$sdataArrf[$rgKey][$groupIndex]= $rgDatas;
				$rgDatas=[];
			}
		}

		//print_r($sdataArrf);
	 $fullJson = json_encode($sdataArrf);
	
 	$sqlPartialSurvey=mysqli_query($conn,"SELECT partial_survey_data_id,full_json FROM partial_survey_data where session_id='".$sessionID."' and survey_name_id='".$survey_id."'");
	$partialData=mysqli_fetch_array($sqlPartialSurvey);
	
	$full_json = json_decode($partialData['full_json'], true); 
	
	if (!is_array($full_json) && empty($full_json)) {
		$sqlPartialSave=mysqli_query($conn,"INSERT into partial_survey_data set survey_id='".$unique_id."',survey_name_id='".$survey_id."',session_id='".$sessionID."', full_json='".$fullJson."'");
		//echo "save";
	}else{
		$sqlPartialSave=mysqli_query($conn,"UPDATE partial_survey_data set survey_id='".$unique_id."',survey_name_id='".$survey_id."', full_json='".$fullJson."' where session_id='".$sessionID."' and survey_name_id='".$survey_id."'");
		//echo "update";
	}
	
	if($sqlPartialSave){
		$resultData = array("status"=>2,"message"=>"Partial Data save successfully");
		echo json_encode($resultData); 
	}  
	
}		
?>