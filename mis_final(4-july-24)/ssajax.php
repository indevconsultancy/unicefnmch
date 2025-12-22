<?php 
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require_once "api/mycrypt.php";
include_once('includes/config.php');

if($_SESSION['enckey'] === 0){
    die("You are not authorized to see the survey");
}
$mcrypt = new EncryptionUtils($_SESSION['enckey']);

?>

<?php
	
	if(isset($_POST['formdata'])){
		$formdatas = $_POST['formdata']; 
		$survey_id = $_POST['surveyId'];
		$language_id = $_POST['languageId'];
		$survey_name = $_POST['survey_name'];
		$allsGroupDatas = $_POST['allsGroupData'];
		$GPS_latitude_start = $_POST['GPS_latitude_start'];
		$GPS_longitude_start = $_POST['GPS_longitude_start'];
		
		$digits = 10;
		$unique_id = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);10;
		
		$sdataArrf["user_id"]= $_SESSION['user_id'];
		$sdataArrf["survey_name"]= $survey_name;
		$sdataArrf["selected_language"]= $language_id;
		$sdataArrf["survey_name_id"]=$survey_id;
		$sdataArrf["survey_id"]=$unique_id;
		$sdataArrf["app_version"]="web";
		$sdataArrf["survey_status"]="1";
		$sdataArrf["cluster_no"]="1234";
		$sdataArrf["GPS_latitude_start"]=$GPS_latitude_start;
		$sdataArrf["GPS_longitude_start"]=$GPS_longitude_start;
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
		$sdataArr=array();
		//print_r($sdataArrf["survey_data"]);
		
		
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
		//print_r($sdataArrf);
		//$url = 'https://mquad.org/mis/api/survey_data_upload_v2.php';
		 $url = 'https://mquad.org/mis/api/survey_data_upload_v2_web.php';
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
