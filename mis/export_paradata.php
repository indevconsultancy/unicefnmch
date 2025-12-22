<?php
include('includes/config.php');
include('includes/functions.php');
// require_once "api/mycrypt.php";
error_reporting(E_ALL);
error_reporting(1);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
ini_set('memory_limit', '-1');
date_default_timezone_set('Asia/Kolkata');

if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');


/* if($_SESSION['enckey'] === 0){
    die("You are not authorized to see the survey");
}

$mcrypt = new EncryptionUtils($_SESSION['enckey']); */
/** Include PHPExcel */

// include('PHPexcel/Classes/PHPExcel.php');
set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
include 'PHPExcel/Classes/PHPExcel.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Satyendra")
                             ->setLastModifiedBy("Satyendra")
                             ->setTitle("Office 2007 MQUAD")
                             ->setSubject("Office 2007 MQUAD")
                             ->setDescription("MQUAD")
                             ->setKeywords("office 2007 openxml php")
                             ->setCategory("Eligible MQUAD data");
//$index=1;
for($i="A";$i<="Z";$i++ )
{
    $setValuePosiotion[]=$i;
}
$survey_id_exported = $_REQUEST['survey_id'];
		
///////////////Roster Group Details///////////////////////
$getGroups = mysqli_query($conn,"SELECT id, group_name FROM questions_group WHERE survey_id='".$survey_id_exported."' AND group_type='group' ");
$allGroups = mysqli_fetch_all($getGroups, MYSQLI_ASSOC);

foreach($allGroups as $allGroup){
	$group_name = $allGroup['group_name'];
	$group_id = $allGroup['id'];
	
	$getFieldnames = mysqli_query($conn,"SELECT question_lang_id, question_name,dictionary_label, field_name,question_id, input_field_type, max_input, normal_group_id,repeat_count,repeated,default_response,group_id FROM questions_language WHERE language_id='1' AND question_name!='' AND survey_id='".$survey_id_exported."' AND question_id< (SELECT question_id FROM questions_language WHERE language_id='1' AND field_name='".$group_name."' AND survey_id='".$survey_id_exported."') ORDER BY question_id DESC limit 1");
	$rnames = mysqli_fetch_object($getFieldnames);
	$roster_gname[$rnames->field_name] = $group_name;
}
///////////////Roster Group Details///////////////////////
///////////////Group Details///////////////////////

$getGroupsscreen = mysqli_query($conn,"SELECT id, group_name FROM questions_group WHERE survey_id='".$survey_id_exported."' AND group_type='screen' ");
$allGroupsscreen = mysqli_fetch_all($getGroupsscreen, MYSQLI_ASSOC);
$scrn = $scrnid= [];
foreach($allGroupsscreen as $allGroupsscreen1){
	//$scrn[] = $allGroupsscreen1['group_name'];
	$gname = $allGroupsscreen1['group_name'];
	//$scrnid[] = $allGroupsscreen1['id'];
	
	$getFieldnames = mysqli_query($conn,"SELECT question_lang_id, question_name,dictionary_label, field_name,question_id, input_field_type, max_input, normal_group_id,repeat_count,repeated,default_response,group_id FROM questions_language WHERE language_id='1' AND question_name!='' AND survey_id='".$survey_id_exported."' AND question_id< (SELECT question_id FROM questions_language WHERE language_id='1' AND field_name='".$gname."' AND survey_id='".$survey_id_exported."') ORDER BY question_id DESC limit 1;");
	$fnames = mysqli_fetch_object($getFieldnames);
	$scrn[$fnames->field_name] = $gname;
	
} 
//print_r($scrn);
//die();
///////////////Group Details///////////////////////

////////////////////Srart Error record sheet/////////////////////////
$getclientId = mysqli_query($conn,"SELECT client_id, survey_name FROM survey_data_monitoring where survey_name_id='".$survey_id_exported."' and survey_status!=7 limit 1 ");
$clientId = mysqli_fetch_object($getclientId);
$client_id = $clientId->client_id;
$survey_name = $clientId->survey_name;

$user_codes = array();
$user_names = array();
$getUserData = mysqli_query($conn,"SELECT user_id,name,username FROM users WHERE client_id='".$client_id."' ");
while($userData = mysqli_fetch_object($getUserData)){
	 $user_codes[$userData->user_id] = $userData->username;
	 $user_names[$userData->user_id] = $userData->name;
}

//$objPHPExcel->createSheet(0);
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setCellValue("A1", "Question");
$objPHPExcel->getActiveSheet()->setCellValue("B1", "Error Count");

$objPHPExcel->getActiveSheet()->getStyle("A1:AAA1")->getFont()->setBold( true );
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(TRUE);
$data_array=[];

$get_sdmonerr = mysqli_query($conn,"SELECT survey_data_monitoring_id,survey_id,error_record  FROM survey_data_monitoring WHERE survey_name_id = '".$survey_id_exported."' and survey_status!=7 order by survey_data_monitoring_id asc");
while($sdmonerr = mysqli_fetch_object($get_sdmonerr)){
	
	if($sdmonerr->error_record!=''){
		$error_recordArr.=$sdmonerr->error_record.',';
		//$error_recordArr[]=$sdmonerr->error_record.',';
	}
	$surveyid = $sdmonerr->survey_id;
}
ksort($error_recordArr);

$errors=substr($error_recordArr,0,-1);
$error_array = explode(',',$errors);
if ($error_array[0]=='') {
  $data_errors=array(); 
}else{
	 $data_errors_arr=array_count_values($error_array); 
	ksort($data_errors_arr);
	$data_errors = $data_errors_arr;
	//print_r($data_errors);
} 

// $data_errors=array_count_values($error_array);
 $counter1=2;

$questions = [];

foreach($data_errors as $keyer => $ervalue) { 
    $erquestion = getone($conn, 'questions', 'field_name', 'question_id', $keyer);
    $questions[$keyer] = $erquestion;
}

// Step 2: Sort the $questions array by keys (question IDs)
//asort($questions);

$objPHPExcel->setActiveSheetIndex(0);

foreach($questions as $keyer => $erquestion) {
    $ervalue = $data_errors[$keyer]; // Retrieve the corresponding error value
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $counter1, $erquestion);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $counter1, $ervalue);
    $counter1++;
}

$objPHPExcel->getActiveSheet()->setTitle('Error Record');
$objPHPExcel->setActiveSheetIndex(0);
/*
===============================================
==============SHEET ONE ERROR RECORD COMPLETED==============
===============================================
*/
 $get_sdmon = mysqli_query($conn,"SELECT survey_data_monitoring_id, survey_id, survey_data_json, full_json, survey_status, users.name as full_name, termination_reason,survey_name  FROM survey_data_monitoring LEFT JOIN users ON users.user_id = survey_data_monitoring.user_id WHERE survey_name_id='".$survey_id_exported."' AND survey_status != '7' order by survey_data_monitoring_id desc");

	$counter11d=2;
	while ($durdata = mysqli_fetch_object($get_sdmon)) {
		//$fullData = $mcrypt->decrypt($durdata->full_json);
		$fullData = $durdata->full_json;
		$full_json = json_decode($fullData);
		
		$survey_name = $durdata->survey_name;
		$survey_data = $full_json->survey_data;
		$survey_id = $durdata->survey_id;
		$sequence_unique_id = $full_json->sequence_unique_id;
		$uniqueID = ($sequence_unique_id != '') ? $sequence_unique_id : $survey_id;
		$start_date_time = dateformate($full_json->start_date_time);
		$end_date_time = dateformate($full_json->end_date_time);
		$device_id = $full_json->device_id;
		$gps = $full_json->GPS_latitude_mid . ',' . $full_json->GPS_longitude_mid;
		if (empty($full_json->GPS_latitude_mid)) {
			$gps = $full_json->GPS_latitude_start . ',' . $full_json->GPS_longitude_start;
		}
		$gpsdata = $gps;
		$full_name = $durdata->full_name;
		$survey_status = getSurveyStatus($durdata->survey_status);
		$termination_reason = $durdata->termination_reason;
		
		foreach ($survey_data as $surveydata1) {
			if ($surveydata1->question_id != 1) {
				if ($surveydata1->option_id!='' || $surveydata1->option_value!='' || (($surveydata1->option_id != '' || $surveydata1->option_value != '') && $surveydata1->para_data != '') || ($surveydata1->option_id == '' && $surveydata1->option_value == '' && $surveydata1->para_data == '')) {
					$duration[] = $surveydata1->duration;
					$field_name[] = $surveydata1->field_name;
				} 
			}
			if(array_key_exists($surveydata1->field_name,$scrn)){
				$groupName = $scrn[$surveydata1->field_name];
				$group_data = $full_json->$groupName;
				foreach ($group_data[0] as $groupdata) {
					if ($groupdata->question_id != 1) {
						if($groupdata->option_id!='' || $groupdata->option_value!='' || (($groupdata->option_id!='' || $groupdata->option_value!='') && $groupdata->para_data!='') || ( $groupdata->option_id=='' && $groupdata->option_value=='' && $groupdata->para_data=='')){
							$duration[] = $groupdata->duration;
							$field_name[]= $groupdata->field_name;
						}
					}
				}
			}
			
			if(array_key_exists($surveydata1->field_name,$roster_gname)){
				$rgroupName = $roster_gname[$surveydata1->field_name];
				$rgroup_data = $full_json->$rgroupName;
				 $i=1;
				 foreach ($rgroup_data as $rgroupdata) {
					foreach($rgroupdata as $rkey=>$rgroupdatarr){
						if ($rgroupdatarr->question_id != 1) {
							if($rgroupdatarr->option_id!='' || $rgroupdatarr->option_value!='' || (($rgroupdatarr->option_id!='' || $rgroupdatarr->option_value!='') && $rgroupdatarr->para_data!='') || ( $rgroupdatarr->option_id=='' && $rgroupdatarr->option_value=='' && $rgroupdatarr->para_data=='')){
								$duration[] = $rgroupdatarr->duration;
								$field_name[]= $rgroupdatarr->field_name.'_'.$i;
							}	
						}	
						//$dataduration[]=$duration;
					}
				$i++;
				} 
			}	
		}
		$durdataArr = array_combine($field_name,$duration);
		
		// echo "<pre>";
		// print_r($durdataArr);
		// die();
		$sn=8;
		if($counter11d==2)
		{
			$objPHPExcel->createSheet(1);
			$objPHPExcel->setActiveSheetIndex(1);
			$objPHPExcel->getActiveSheet()->setCellValue("A1", "UniqueId");
			$objPHPExcel->getActiveSheet()->setCellValue("B1", "start_date_time");
			$objPHPExcel->getActiveSheet()->setCellValue("C1", "end_date_time");
			$objPHPExcel->getActiveSheet()->setCellValue("D1", "device_id");
			$objPHPExcel->getActiveSheet()->setCellValue("E1", "gps");
			$objPHPExcel->getActiveSheet()->setCellValue("F1", "username");
			$objPHPExcel->getActiveSheet()->setCellValue("G1", "surveystatus");
			$objPHPExcel->getActiveSheet()->setCellValue("H1", "terminationreason");
			
			foreach($durdataArr as $gdataKey=>$gdata){
				
				$objPHPExcel->getActiveSheet()->setCellValue($setValuePosiotion[$sn++]."1", $gdataKey);
			}
			
			$objPHPExcel->getActiveSheet()->getStyle("A1:AAA1")->getFont()->setBold( true );
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(TRUE);
		}
		
		$sn=8;
		$rowsc=0;
		foreach($durdataArr as $gdataKeyv=>$gdatav){
		//	echo $gdataKeyv;
			$objPHPExcel->setActiveSheetIndex(1);
			$objPHPExcel->getActiveSheet()->setCellValue("A".$counter11d, $uniqueID);
			$objPHPExcel->getActiveSheet()->setCellValue("B".$counter11d, $start_date_time);
			$objPHPExcel->getActiveSheet()->setCellValue("C".$counter11d, $end_date_time);
			$objPHPExcel->getActiveSheet()->setCellValue("D".$counter11d, $device_id);
			$objPHPExcel->getActiveSheet()->setCellValue("E".$counter11d, $gpsdata);
			$objPHPExcel->getActiveSheet()->setCellValue("F".$counter11d, $full_name);
			$objPHPExcel->getActiveSheet()->setCellValue("G".$counter11d, $survey_status);
			$objPHPExcel->getActiveSheet()->setCellValue("H".$counter11d, $termination_reason);
			$objPHPExcel->getActiveSheet()->setCellValue($setValuePosiotion[$sn++].$counter11d, $gdatav);
		
		}
		
		$counter11d++;
	}
	$duration=array();
	$field_name=array();
$objPHPExcel->getActiveSheet()->setTitle('Duration');
/*
===============================================
==============SHEET TWO DURATION COMPLETED========
===============================================
*/

$get_sdmontime = mysqli_query($conn,"SELECT survey_data_monitoring_id, survey_id, survey_data_json, full_json, survey_status, users.name as full_name, termination_reason,survey_name  FROM survey_data_monitoring LEFT JOIN users ON users.user_id = survey_data_monitoring.user_id WHERE survey_name_id='".$survey_id_exported."' AND survey_status != '7'");

	$countert=2;
	while ($timedata = mysqli_fetch_object($get_sdmontime)) {
		 $fullData = $timedata->full_json;
		 $full_json = json_decode($fullData);
		 
		$survey_name = $timedata->survey_name;
		$survey_data = $full_json->survey_data;
		$survey_id = $timedata->survey_id;
		$sequence_unique_id = $full_json->sequence_unique_id;
		$uniqueID = ($sequence_unique_id != '') ? $sequence_unique_id : $survey_id;
		
		$start_date_time = dateformate($full_json->start_date_time);
		$end_date_time = dateformate($full_json->end_date_time);
		$device_id = $full_json->device_id;
		$gps = $full_json->GPS_latitude_mid . ',' . $full_json->GPS_longitude_mid;
		if (empty($full_json->GPS_latitude_mid)) {
			$gps = $full_json->GPS_latitude_start . ',' . $full_json->GPS_longitude_start;
		}
		$gpsdata = $gps;
		$full_name = $timedata->full_name;
		$survey_status = getSurveyStatus($timedata->survey_status);
		$termination_reason = $timedata->termination_reason;
		
		foreach ($survey_data as $surveydata1) {
			if ($surveydata1->question_id != 1) {
				if((($surveydata1->option_id!='' || $surveydata1->option_value!='') && $surveydata1->para_data!='') || ( $surveydata1->option_id=='' && $surveydata1->option_value=='' && $surveydata1->para_data=='')){
					 $para_data[] = str_replace(array("-/", "/"), array("-", "-"), $surveydata1->para_data);
					 $field_nametime[] = $surveydata1->field_name;
				}	
			}	
				if(array_key_exists($surveydata1->field_name,$scrn)){
					$groupName = $scrn[$surveydata1->field_name];
					$group_data = $full_json->$groupName;
					foreach ($group_data[0] as $groupdata) {
						if ($groupdata->question_id != 1) {
							if((($groupdata->option_id!='' || $groupdata->option_value!='') && $groupdata->para_data!='') || ( $groupdata->option_id=='' && $groupdata->option_value=='' && $groupdata->para_data=='')){
								$para_data[] = str_replace(array("-/", "/"), array("-", "-"), $groupdata->para_data);
								$field_nametime[]= $groupdata->field_name;
							}
						}
					}
				}
				
				if(array_key_exists($surveydata1->field_name,$roster_gname)){
					$rgroupName = $roster_gname[$surveydata1->field_name];
					$rgroup_data = $full_json->$rgroupName;
					 $i=1;
					 foreach ($rgroup_data as $rgroupdata) {
						foreach($rgroupdata as $rkey=>$rgroupdatarr){
							if ($rgroupdatarr->question_id != 1) {
								if((($rgroupdatarr->option_id!='' || $rgroupdatarr->option_value!='') && $rgroupdatarr->para_data!='') || ( $rgroupdatarr->option_id=='' && $rgroupdatarr->option_value=='' && $rgroupdatarr->para_data=='')){
									$para_data[] =str_replace(array("-/", "/"), array("-", "-"), $rgroupdatarr->para_data);
									$field_nametime[]= $rgroupdatarr->field_name.'_'.$i;
								}	
							}	
						}
						$i++;
					} 
				}	
			 
		}
		
		$timedataArr = array_combine($field_nametime,$para_data);
		 
		 /* if (array_key_exists('submit', $timedataArr)) {
			unset($timedataArr['submit']);
		} */
		
		$tssn=8;
		if($countert==2)
		{
			$objPHPExcel->createSheet(2);
			$objPHPExcel->setActiveSheetIndex(2);
			$objPHPExcel->getActiveSheet()->setCellValue("A1", "UniqueId");
			$objPHPExcel->getActiveSheet()->setCellValue("B1", "start_date_time");
			$objPHPExcel->getActiveSheet()->setCellValue("C1", "end_date_time");
			$objPHPExcel->getActiveSheet()->setCellValue("D1", "device_id");
			$objPHPExcel->getActiveSheet()->setCellValue("E1", "gps");
			$objPHPExcel->getActiveSheet()->setCellValue("F1", "username");
			$objPHPExcel->getActiveSheet()->setCellValue("G1", "surveystatus");
			$objPHPExcel->getActiveSheet()->setCellValue("H1", "terminationreason");
			
			foreach($timedataArr as $tdataKey=>$tdata){
				
				$objPHPExcel->getActiveSheet()->setCellValue($setValuePosiotion[$tssn++]."1", $tdataKey);
			}
			
			$objPHPExcel->getActiveSheet()->getStyle("A1:AAA1")->getFont()->setBold( true );
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(TRUE);
		}
		
		$tssn=8;
		
		foreach($timedataArr as $tdataKeyv=>$tdatav){
			//echo $tdatav;
			$objPHPExcel->setActiveSheetIndex(2);
			$objPHPExcel->getActiveSheet()->setCellValue("A".$countert, $uniqueID);
			$objPHPExcel->getActiveSheet()->setCellValue("B".$countert, $start_date_time);
			$objPHPExcel->getActiveSheet()->setCellValue("C".$countert, $end_date_time);
			$objPHPExcel->getActiveSheet()->setCellValue("D".$countert, $device_id);
			$objPHPExcel->getActiveSheet()->setCellValue("E".$countert, $gpsdata);
			$objPHPExcel->getActiveSheet()->setCellValue("F".$countert, $full_name);
			$objPHPExcel->getActiveSheet()->setCellValue("G".$countert, $survey_status);
			$objPHPExcel->getActiveSheet()->setCellValue("H".$countert, $termination_reason);
			$objPHPExcel->getActiveSheet()->setCellValue($setValuePosiotion[$tssn++].$countert, $tdatav);
		}
		
		$countert++;
	}
$objPHPExcel->getActiveSheet()->setTitle('Timestamp');
/*
===============================================
==============SHEET THREE TIMESTAMP COMPLETED========
===============================================
*/
	
$get_sdmonkey = mysqli_query($conn,"SELECT survey_data_monitoring_id, survey_id, survey_data_json, full_json, survey_status, users.name as full_name, termination_reason,survey_name  FROM survey_data_monitoring LEFT JOIN users ON users.user_id = survey_data_monitoring.user_id WHERE survey_name_id='".$survey_id_exported."' AND survey_status != '7'");

	$counterk=2;
	while ($keydata = mysqli_fetch_object($get_sdmonkey)) {
		// $fullData = $mcrypt->decrypt($keydata->full_json);
		 $fullData = $keydata->full_json;
		 $full_json = json_decode($fullData);
		 
		$survey_name = $keydata->survey_name;
		$survey_data = $full_json->survey_data;
		$survey_id = $keydata->survey_id;
		$sequence_unique_id = $full_json->sequence_unique_id;
		$uniqueID = ($sequence_unique_id != '') ? $sequence_unique_id : $survey_id;
		
		$start_date_time = dateformate($full_json->start_date_time);
		$end_date_time = dateformate($full_json->end_date_time);
		$device_id = $full_json->device_id;
		$gps = $full_json->GPS_latitude_mid . ',' . $full_json->GPS_longitude_mid;
		if (empty($full_json->GPS_latitude_mid)) {
			$gps = $full_json->GPS_latitude_start . ',' . $full_json->GPS_longitude_start;
		}
		$gpsdata = $gps;
		$full_name = $keydata->full_name;
		$survey_status = getSurveyStatus($keydata->survey_status);
		$termination_reason = $keydata->termination_reason;
		
		foreach ($survey_data as $surveydata1) {
			if ($surveydata1->question_id != 1) {
				if((($surveydata1->option_id!='' || $surveydata1->option_value!='') && $surveydata1->para_data!='') || ( $surveydata1->option_id=='' && $surveydata1->option_value=='' && $surveydata1->para_data=='')){
				 $key_stroke[] = $surveydata1->key_stroke;
				 $key_field_name[] = $surveydata1->field_name;
				}
			}
			if(array_key_exists($surveydata1->field_name,$scrn)){
				$groupName = $scrn[$surveydata1->field_name];
				$group_data = $full_json->$groupName;
				foreach ($group_data[0] as $groupdata) {
					if ($groupdata->question_id != 1) {
						if((($groupdata->option_id!='' || $groupdata->option_value!='') && $groupdata->para_data!='') || ( $groupdata->option_id=='' && $groupdata->option_value=='' && $groupdata->para_data=='')){
							$key_stroke[] = $groupdata->key_stroke;
							$key_field_name[] = $groupdata->field_name;
						}
					}
				}
			}
			if(array_key_exists($surveydata1->field_name,$roster_gname)){
				$rgroupName = $roster_gname[$surveydata1->field_name];
				$rgroup_data = $full_json->$rgroupName;
				 $i=1;
				 foreach ($rgroup_data as $rgroupdata) {
					foreach($rgroupdata as $rkey=>$rgroupdatarr){
						if ($rgroupdatarr->question_id != 1) {
							if((($rgroupdatarr->option_id!='' || $rgroupdatarr->option_value!='') && $rgroupdatarr->para_data!='') || ( $rgroupdatarr->option_id=='' && $rgroupdatarr->option_value=='' && $rgroupdatarr->para_data=='')){
								$key_stroke[] = $rgroupdatarr->key_stroke;
								$key_field_name[]= $rgroupdatarr->field_name.'_'.$i;
							}	
						}	
					}
					$i++;
				} 
			}
			
		}
		
		$keydataArr = array_combine($key_field_name,$key_stroke);
		 
/* 		 if (array_key_exists('submit', $keydataArr)) {
			unset($keydataArr['submit']);
		} */
		
		$kssn=8;
		if($counterk==2)
		{
			$objPHPExcel->createSheet(3);
			$objPHPExcel->setActiveSheetIndex(3);
			$objPHPExcel->getActiveSheet()->setCellValue("A1", "UniqueId");
			$objPHPExcel->getActiveSheet()->setCellValue("B1", "start_date_time");
			$objPHPExcel->getActiveSheet()->setCellValue("C1", "end_date_time");
			$objPHPExcel->getActiveSheet()->setCellValue("D1", "device_id");
			$objPHPExcel->getActiveSheet()->setCellValue("E1", "gps");
			$objPHPExcel->getActiveSheet()->setCellValue("F1", "username");
			$objPHPExcel->getActiveSheet()->setCellValue("G1", "surveystatus");
			$objPHPExcel->getActiveSheet()->setCellValue("H1", "terminationreason");
			
			foreach($keydataArr as $kdataKey=>$kdata){
				
				$objPHPExcel->getActiveSheet()->setCellValue($setValuePosiotion[$kssn++]."1", $kdataKey);
			}
			
			$objPHPExcel->getActiveSheet()->getStyle("A1:AAA1")->getFont()->setBold( true );
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(TRUE);
		}
		
		$kssn=8;
		
		foreach($keydataArr as $kdataKeyv=>$kdatav){
			//echo $tdatav;
			$objPHPExcel->setActiveSheetIndex(3);
			$objPHPExcel->getActiveSheet()->setCellValue("A".$counterk, $uniqueID);
			$objPHPExcel->getActiveSheet()->setCellValue("B".$counterk, $start_date_time);
			$objPHPExcel->getActiveSheet()->setCellValue("C".$counterk, $end_date_time);
			$objPHPExcel->getActiveSheet()->setCellValue("D".$counterk, $device_id);
			$objPHPExcel->getActiveSheet()->setCellValue("E".$counterk, $gpsdata);
			$objPHPExcel->getActiveSheet()->setCellValue("F".$counterk, $full_name);
			$objPHPExcel->getActiveSheet()->setCellValue("G".$counterk, $survey_status);
			$objPHPExcel->getActiveSheet()->setCellValue("H".$counterk, $termination_reason);
			$objPHPExcel->getActiveSheet()->setCellValue($setValuePosiotion[$kssn++].$counterk, $kdatav);
		}
		
		$counterk++;
	}
$objPHPExcel->getActiveSheet()->setTitle('key_stroke');

/*
===============================================
==============SHEET FOUR KEYSTOKE COMPLETED========
===============================================
*/
	
$get_sdmongps = mysqli_query($conn,"SELECT survey_data_monitoring_id, survey_id, survey_data_json, full_json, survey_status, users.name as full_name, termination_reason,survey_name  FROM survey_data_monitoring LEFT JOIN users ON users.user_id = survey_data_monitoring.user_id WHERE survey_name_id='".$survey_id_exported."' AND survey_status != '7'");

	$counterg=2;
	while ($gpsdatas = mysqli_fetch_object($get_sdmongps)) {
		 //$fullData = $mcrypt->decrypt($gpsdatas->full_json);
		 $fullData = $gpsdatas->full_json;
		 $full_json = json_decode($fullData);
		 
		$survey_name = $gpsdatas->survey_name;
		$survey_data = $full_json->survey_data;
		$survey_id = $gpsdatas->survey_id;
		$sequence_unique_id = $full_json->sequence_unique_id;
		$uniqueID = ($sequence_unique_id != '') ? $sequence_unique_id : $survey_id;
		
		$start_date_time = dateformate($full_json->start_date_time);
		$end_date_time = dateformate($full_json->end_date_time);
		$device_id = $full_json->device_id;
		$gps = $full_json->GPS_latitude_mid . ',' . $full_json->GPS_longitude_mid;
		if (empty($full_json->GPS_latitude_mid)) {
			$gps = $full_json->GPS_latitude_start . ',' . $full_json->GPS_longitude_start;
		}
		$gpsdata = $gps;
		$full_name = $gpsdatas->full_name;
		$survey_status = getSurveyStatus($gpsdatas->survey_status);
		$termination_reason = $gpsdatas->termination_reason;
		
		foreach ($survey_data as $surveydata1) {
			if ($surveydata1->question_id != 1) {
				if((($surveydata1->option_id!='' || $surveydata1->option_value!='') && $surveydata1->para_data!='') || ( $surveydata1->option_id=='' && $surveydata1->option_value=='' && $surveydata1->para_data=='')){
				 $gps_data[] =$surveydata1->gps;
				 $gps_field_name[] = $surveydata1->field_name;
				} 
			} 
			if(array_key_exists($surveydata1->field_name,$scrn)){
				$groupName = $scrn[$surveydata1->field_name];
				$group_data = $full_json->$groupName;
				foreach ($group_data[0] as $groupdata) {
					if ($groupdata->question_id != 1) {
						if((($groupdata->option_id!='' || $groupdata->option_value!='') && $groupdata->para_data!='') || ( $groupdata->option_id=='' && $groupdata->option_value=='' && $groupdata->para_data=='')){
							$gps_data[] =$groupdata->gps;
							$gps_field_name[] = $groupdata->field_name;
						}
					}
				}
			}
			if(array_key_exists($surveydata1->field_name,$roster_gname)){
				$rgroupName = $roster_gname[$surveydata1->field_name];
				$rgroup_data = $full_json->$rgroupName;
				$i=1;
				 foreach ($rgroup_data as $rgroupdata) {
					foreach($rgroupdata as $rkey=>$rgroupdatarr){
						if ($rgroupdatarr->question_id != 1) {
							if((($rgroupdatarr->option_id!='' || $rgroupdatarr->option_value!='') && $rgroupdatarr->para_data!='') || ( $rgroupdatarr->option_id=='' && $rgroupdatarr->option_value=='' && $rgroupdatarr->para_data=='')){
								$gps_data[] = $rgroupdatarr->gps;
								$gps_field_name[]= $rgroupdatarr->field_name.'_'.$i;
							}	
						}	
					}
					$i++;
				} 
			}
			
		}
		$gpsdataArr = array_combine($gps_field_name,$gps_data);
		 
		 /* if (array_key_exists('submit', $gpsdataArr)) {
			unset($gpsdataArr['submit']);
		} */
		
		$gssn=8;
		if($counterg==2)
		{
			$objPHPExcel->createSheet(4);
			$objPHPExcel->setActiveSheetIndex(4);
			$objPHPExcel->getActiveSheet()->setCellValue("A1", "UniqueId");
			$objPHPExcel->getActiveSheet()->setCellValue("B1", "start_date_time");
			$objPHPExcel->getActiveSheet()->setCellValue("C1", "end_date_time");
			$objPHPExcel->getActiveSheet()->setCellValue("D1", "device_id");
			$objPHPExcel->getActiveSheet()->setCellValue("E1", "gps");
			$objPHPExcel->getActiveSheet()->setCellValue("F1", "username");
			$objPHPExcel->getActiveSheet()->setCellValue("G1", "surveystatus");
			$objPHPExcel->getActiveSheet()->setCellValue("H1", "terminationreason");
			
			foreach($gpsdataArr as $gdataKey=>$gata){
				
				$objPHPExcel->getActiveSheet()->setCellValue($setValuePosiotion[$gssn++]."1", $gdataKey);
			}
			
			$objPHPExcel->getActiveSheet()->getStyle("A1:AAA1")->getFont()->setBold( true );
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(TRUE);
		}
		
		$gssn=8;
		
		foreach($gpsdataArr as $gdataKeyv=>$gdatav){
			//echo $gdatav;
			$objPHPExcel->setActiveSheetIndex(4);
			$objPHPExcel->getActiveSheet()->setCellValue("A".$counterg, $uniqueID);
			$objPHPExcel->getActiveSheet()->setCellValue("B".$counterg, $start_date_time);
			$objPHPExcel->getActiveSheet()->setCellValue("C".$counterg, $end_date_time);
			$objPHPExcel->getActiveSheet()->setCellValue("D".$counterg, $device_id);
			$objPHPExcel->getActiveSheet()->setCellValue("E".$counterg, $gpsdata);
			$objPHPExcel->getActiveSheet()->setCellValue("F".$counterg, $full_name);
			$objPHPExcel->getActiveSheet()->setCellValue("G".$counterg, $survey_status);
			$objPHPExcel->getActiveSheet()->setCellValue("H".$counterg, $termination_reason);
			$objPHPExcel->getActiveSheet()->setCellValue($setValuePosiotion[$gssn++].$counterg, $gdatav);
		}
		
		$counterg++;
	}
	//die();
$objPHPExcel->getActiveSheet()->setTitle('GPS');
/*
===============================================
==============SHEET FIVE GPS COMPLETED========
===============================================
*/
$file_name = "Paradata_form-".str_replace(" ","_",$survey_name).".xlsx";

//SET SHEET ACTIVE DEFAULT
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel2007)
ob_end_clean();

/* header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//header('Content-Disposition: attachment;filename="'.$file_name.'" ');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');
// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
/*
$objWriter->save('php://output');
*/

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('uploaded_questionnaire/paradata.xlsx');
//header("Content-Type: application/vnd.ms-excel");

$path = base_url()."/uploaded_questionnaire/paradata.xlsx";
$resArr = array('status'=>20033,'path'=>$path,'fname'=>$file_name);
echo json_encode($resArr);
 
?>