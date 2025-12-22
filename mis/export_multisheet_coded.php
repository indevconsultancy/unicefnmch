<?php
session_start();
ob_start();
include('includes/config.php');
include('includes/functions.php');
include_once('create-questionnaire.php');
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
ini_set('memory_limit', '-1');
date_default_timezone_set('Asia/Kolkata');

// Include PHPExcel
include 'PHPExcel/Classes/PHPExcel.php';

// Function to generate column letters
for ($i = 'A'; $i <= 'Z'; $i++) {
	$setValuePosiotion[] = $i;
}
// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Satyendra")
	->setLastModifiedBy("Satyendra")
	->setTitle("Office 2007 XLSX MQUAD")
	->setSubject("Office 2007 XLSX MQUAD")
	->setDescription("MQUAD for Office 2007 XLSX, generated using PHP classes.")
	->setKeywords("office 2007 openxml php")
	->setCategory("MQUAD result file");

// Get survey data and headers
$surveyid = $_REQUEST['survey_id'];
$language_id = $_REQUEST['language_id'];
$identification_type = $_REQUEST['export_type'];

$getGroups = mysqli_query($conn, "SELECT id, group_name FROM questions_group WHERE survey_id='" . $surveyid . "' AND group_type='group' ");
$allGroups = mysqli_fetch_all($getGroups, MYSQLI_ASSOC);
//$group_names = $getGroup->group_name;

foreach ($allGroups as $allGroup) {
	$group_name = $allGroup['group_name'];
	$group_id = $allGroup['id'];
	$getGroupDatas = mysqli_query($conn, "select MAX(JSON_LENGTH(JSON_EXTRACT(full_json, '$." . $group_name . "'))) as groupData from survey_data_monitoring where survey_name_id='" . $surveyid . "'");
	$group_data = mysqli_fetch_object($getGroupDatas);
	$gdata[] = $group_data->groupData;
}
$getGroupDatasjs = mysqli_query($conn, "select full_json from survey_data_monitoring where survey_name_id='" . $surveyid . "' and survey_status!=7");
$fulljson_data = mysqli_fetch_object($getGroupDatasjs);

$DecryptedJson = $fulljson_data->full_json;
$full_json = json_decode($DecryptedJson);

$survey_data = $full_json->survey_data;

//Start Anonymous QUESTION FETCH work-08.10.2024
foreach ($survey_data as $key => $survey_datass) {
	$field_name = $survey_datass->field_name;
	$question_id = $survey_datass->question_id;

	if ($question_id == 1 && $field_name != '') {
		$anon_field[] = $field_name;
	}
}
//END Anonymous QUESTION FETCH

$getGroupsscreen = mysqli_query($conn, "SELECT id, group_name FROM questions_group WHERE survey_id='" . $surveyid . "' AND group_type='screen' ");
$allGroupsscreen = mysqli_fetch_all($getGroupsscreen, MYSQLI_ASSOC);
$scrn = $scrnid = [];
foreach ($allGroupsscreen as $gkey=>$allGroupsscreen1) {
	$gname = $allGroupsscreen1['group_name'];
	// $getFieldnames = mysqli_query($conn, "SELECT question_lang_id, question_name,dictionary_label, field_name,question_id, input_field_type, max_input, normal_group_id,repeat_count,repeated,default_response,group_id FROM questions_language WHERE language_id='1' AND question_name!='' AND survey_id='" . $surveyid . "' AND question_id< (SELECT question_id FROM questions_language WHERE language_id='1' AND field_name='" . $gname . "' AND survey_id='" . $surveyid . "') ORDER BY question_id DESC limit 1;");
	// $fnames = mysqli_fetch_object($getFieldnames);
	//$scrn[$fnames->field_name] = $gname;
	$scrn[$anon_field[$gkey]] = $gname;
}

$repeatedQuestions = array();

$getQuestions1 = mysqli_query($conn, "SELECT question_lang_id, question_name, encrpt, dictionary_label, field_name, question_id, input_field_type, max_input, normal_group_id, repeat_count, repeated, default_response, group_id FROM questions_language WHERE language_id='1' AND question_name!='' AND survey_id='$surveyid'");
$tRows = mysqli_num_rows($getQuestions1);
$getQuestions1_ss = mysqli_fetch_all($getQuestions1, MYSQLI_ASSOC);

$field_names[] = 'uniqueid';
$field_names[] = 'startDateTime';
$field_names[] = 'endDateTime';
$field_names[] = 'device_id';
$field_names[] = 'GPS';
$field_names[] = 'UserName';
$field_names[] = 'SurveyStatus';
$field_names[] = 'TerminationReason';

//$export_headers = [];
$msci_reports = [];
$repeatedQuestionsDo = [];
$grp = 0;
$grpkey = $notes = [];
$r1 = count($field_names) - 1;

foreach ($getQuestions1_ss as $questiondo) {
	$question = (object) $questiondo;
	$question_name = str_replace('"', '', $question->dictionary_label);
	$msci_reports[$question->field_name] = $question->encrpt;
	//  $export_headers[] = $question->field_name;
	$field_name = $question->field_name;
	$repeated = $question->repeated;
	$repeat_count = $question->repeat_count;
	$input_field_types[$question->field_name] = $question->input_field_type;
	$input_field_type = $question->input_field_type;
	$question_id = $question->question_id;
	$default_response = $question->default_response;
	$groupId = $question->group_id;

	if ($repeated != '' && $repeat_count == '') {
		$repeatedQuestionDo['f'] = $field_name;
		$repeatedQuestionDo['q'] = $question_name;
		$repeatedQuestionDo['intype'] = 'text'; //$input_field_type;


		$repeatedQuestionsDo[] = $repeatedQuestionDo;
		$repeatedQuestionDo = array();
	} else {
		//echo "<pre>";
		if (count($repeatedQuestionsDo) > 0) {
			$tf = count($field_names);
			$grpkey[] = $field_names[$tf - 1];
			for ($a = 1; $a <= $gdata[$grp]; $a++) {
				foreach ($repeatedQuestionsDo as $repeatedQuestionDo1) {
					$fname = $repeatedQuestionDo1['f'] . '_' . $a;
					$inputFieldType = $repeatedQuestionDo1['q'];
					$intype = $repeatedQuestionDo1['intype'];
					$field_names[] = $fname;
					$field_input_types[$fname] = $intype;
					$r1++;
				}
			}
			$grp++;
		}
		$repeatedQuestionsDo = array();


		$field_names[] = $question->field_name;
		$field_input_types[$question->field_name] = $question->input_field_type;
		$r1++;
	}
	if ($input_field_type == "note") {
		$notes[] = $r1;
	}
}
$groupRecords = [];
foreach ($grpkey as $k => $grpkey1) {
	$gname = $allGroups[$k]['group_name'];
	$g['group_name'] = $gname;
	$g['repeatedTime'] = $gdata[$k];
	$groupRecords[$grpkey1] = $g;
}
// echo "<pre>";	
// print_r($groupRecords);
// Fetch survey data
$getMondatas = mysqli_query($conn, "SELECT survey_data_monitoring_id, survey_id, survey_data_json, full_json, survey_status, users.name as full_name, termination_reason,survey_name  FROM survey_data_monitoring LEFT JOIN users ON users.user_id = survey_data_monitoring.user_id WHERE survey_name_id='$surveyid' AND survey_status != '7'");
$counter = 2;

while ($mdata = mysqli_fetch_object($getMondatas)) {
	// $fullData = $mcrypt->decrypt($mdata->full_json);
	$fullData = $mdata->full_json;
	$full_json = json_decode($fullData);
	$survey_name = $mdata->survey_name;
	$survey_data = $full_json->survey_data;
	$survey_id = $mdata->survey_id;
	$sequence_unique_id = $full_json->sequence_unique_id;
	$uniqueID = ($sequence_unique_id != '') ? $sequence_unique_id : $survey_id;

	$jsn_data['uniqueid'] = "'" . $uniqueID;
	$jsn_data['startDateTime'] = dateformate($full_json->start_date_time);
	$jsn_data['endDateTime'] = dateformate($full_json->end_date_time);
	$jsn_data['device_id'] = $full_json->device_id;
	$gps = $full_json->GPS_latitude_mid . ',' . $full_json->GPS_longitude_mid;
	if (empty($full_json->GPS_latitude_mid)) {
		$gps = $full_json->GPS_latitude_start . ',' . $full_json->GPS_longitude_start;
	}
	$jsn_data['GPS'] = $gps;
	$jsn_data['UserName'] = $mdata->full_name;
	$jsn_data['SurveyStatus'] = getSurveyStatus($mdata->survey_status);
	$jsn_data['TerminationReason'] = $mdata->termination_reason;

	foreach ($survey_data as $surveydata1) {
		$opt_value = $surveydata1->option_value;
		$opt_value = str_replace(array("-/", "/"), array("-", "-"), $opt_value);
		if ($surveydata1->option_id != "") {
			$opt_value = str_replace(",", "", $surveydata1->option_id);
		}
		if (strtolower($msci_reports[$surveydata1->field_name]) == "yes" && ($_SESSION['functional_role_id'] == 7 || $identification_type == 'deidentify')) {
			if ($input_field_types[$surveydata1->field_name] == 'text') {
				$optvalue = substr(str_pad($opt_value, 3, '*'), 0, 3);
				$opt_value = str_repeat("*", strlen($optvalue));
			} else {
				$opt_value = str_replace(array("-/", "/"), array("-", "-"), $opt_value);
				if ($surveydata1->option_id != "") {
					$opt_value = str_replace(",", "", $surveydata1->option_id);
				}
			}
		}
		$jsn_data[$surveydata1->field_name] = $opt_value;


		if (array_key_exists($surveydata1->field_name, $scrn)) {
			$groupName = $scrn[$surveydata1->field_name];
			$group_data = $full_json->$groupName;

			foreach ($group_data[0] as $groupdata) {
				$opt_value = $groupdata->option_value;
				$opt_value = str_replace(array("-/", "/"), array("-", "-"), $opt_value);
				if ($groupdata->option_id != "") {
					$opt_value = str_replace(",", "", $groupdata->option_id);
				}
				$fnm = $groupdata->field_name;

				$fnm1 = $groupdata->field_name;
				$jsn_data[$fnm] = $opt_value;

				////////////Group under question open in roster data/////////////
				if (array_key_exists($fnm1, $groupRecords)) {
					$groupName1 = $groupRecords[$fnm1]['group_name'];
					$group_data1 = $full_json->$groupName1;
					// echo "<pre>";
					// print_r($group_data1);
					$nofrepeated = $groupRecords[$fnm1]['repeatedTime'];
					for ($a = 1; $a <= $nofrepeated; $a++) {

						foreach ($group_data1[$a - 1] as $groupdata) {
							//echo $groupdata;
							$opt_value = $groupdata->option_value;
							$opt_value = str_replace(array("-/", "/"), array("-", "-"), $opt_value);
							if ($groupdata->option_id != "") {
								$opt_value = str_replace(",", "", $groupdata->option_id);
							}
							if (strtolower($msci_reports[$groupdata->field_name]) == "yes" && ($_SESSION['functional_role_id'] == 7 || $identification_type == 'deidentify')) {
								//$opt_value = str_repeat("X", strlen($opt_value));
								if ($input_field_types[$groupdata->field_name] == 'text') {
									$optvalue = substr(str_pad($opt_value, 3, '*'), 0, 3);
									$opt_value = str_repeat("*", strlen($optvalue));
								} else {
									$opt_value = str_replace(array("-/", "/"), array("-", "-"), $opt_value);
									if ($groupdata->option_id != "") {
										$opt_value = str_replace(",", "", $groupdata->option_id);
									}
								}
							}
							$fnm2 = $groupdata->field_name . '_' . $a;
							$jsn_data[$fnm2] = $opt_value;
						}
					}
				}
				/////////////////////////////////////////////				
			}
		}
		// echo "<pre>";
		// print_r($jsn_data);

		if (in_array($surveydata1->field_name, $grpkey)) {

			$groupName = $groupRecords[$surveydata1->field_name]['group_name']; //$allSingelGroup['group_name'];
			$group_data = $full_json->$groupName;
			$nofrepeated = $groupRecords[$surveydata1->field_name]['repeatedTime'];

			for ($a = 1; $a <= $nofrepeated; $a++) {
				foreach ($group_data[$a - 1] as $groupdata) {
					$opt_value = $groupdata->option_value;
					$opt_value = str_replace(array("-/", "/"), array("-", "-"), $opt_value);
					if ($groupdata->option_id != "") {
						$opt_value = str_replace(",", "", $groupdata->option_id);
					}
					if (strtolower($msci_reports[$groupdata->field_name]) == "yes" && ($_SESSION['functional_role_id'] == 7 || $identification_type == 'deidentify')) {
						//$opt_value = str_repeat("X", strlen($opt_value));
						if ($input_field_types[$groupdata->field_name] == 'text') {
							$optvalue = substr(str_pad($opt_value, 3, '*'), 0, 3);
							$opt_value = str_repeat("*", strlen($optvalue));
						} else {
							$opt_value = str_replace(array("-/", "/"), array("-", "-"), $opt_value);
							if ($groupdata->option_id != "") {
								$opt_value = str_replace(",", "", $groupdata->option_id);
							}
						}
					}
					$fnm = $groupdata->field_name . '_' . $a;
					$jsn_data[$fnm] = $opt_value;
				}
			}
		}
	}

	$jsn_data_arr[] = $jsn_data;
	$jsn_data = array();
}

$jsn_data_json = $jsn_data_arr;
// echo "<pre>";
// print_r($jsn_data_json);
// die();
$field_names = array_diff_key($field_names, array_flip($notes));

if (!class_exists('PHPExcel')) {
	require realpath(dirname(__FILE__)) . '/PHPExcel/Classes/PHPExcel.php';
}

$objPHPExcel = new PHPExcel();
$data = $jsn_data_json;

$cols = $field_names;
$dma = implode(',', $cols);
$cols = explode(',', $dma);

$objPHPExcel->setActiveSheetIndex(0);
$activeSheet = $objPHPExcel->getActiveSheet();

for ($i = 'A'; $i <= 'Z'; $i++) {
	$setValuePosiotion[] = $i;
}
$headerRow = 1;

foreach ($cols as $headKey => $colHeader) {
	$cell = $setValuePosiotion[$headKey] . $headerRow;
	$activeSheet->setCellValue($cell, strtolower($colHeader));
	// Apply bold styling
	$activeSheet->getStyle($cell)->getFont()->setBold(true);
}

foreach ($data as $i => $row) {

	foreach ($cols as $j => $col) {
		$activeSheet->setCellValue($setValuePosiotion[$j] . ($headerRow + 1 + $i), $row[$col]);
	}
}

$objPHPExcel->getActiveSheet()->setTitle('Survey-data');

$file_name = 'abc.xlsx';
ob_end_clean();
ob_clean();
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $file_name . '"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: cache, must-revalidate');
header('Pragma: public');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('uploaded_questionnaire/' . $file_name);
// Finalize Excel sheet
$file_name = "ExcelCoded-" . str_replace(" ", "_", $survey_name) . ".xlsx";

$path = base_url() . "/uploaded_questionnaire/abc.xlsx";
$resArr = array('status' => 200, 'path' => $path, 'fname' => $file_name);
echo json_encode($resArr);
