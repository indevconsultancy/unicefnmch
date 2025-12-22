<?php
// Session start and necessary includes
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

if (PHP_SAPI == 'cli') die('This example should only be run from a Web Browser');

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
function findData($array, $search)
{
	$result = array();
	foreach ($array as $key => $value) {
		foreach ($search as $k => $v) {
			if (!isset($value[$k]) || $value[$k] != $v) {
				continue 2;
			}
		}
		$result[] = $key;
	}
	//return $result;
	$opt_name = '';
	if (isset($result[0])) {
		$opt_name = isset($array[$result[0]]['option_name']) ? $array[$result[0]]['option_name'] : "";
	}
	return $opt_name;
}
// Get survey data and headers
$surveyid = $_REQUEST['survey_id'];
$language_id = $_REQUEST['language_id'];
$identification_type = $_REQUEST['identification_type'];
// temp DB created
	$SqlQ="SELECT full_json, user_id, survey_id,survey_status FROM survey_data_monitoring WHERE survey_name_id=".$surveyid." and survey_status!=7";

		$getSurveyData = mysqli_query($conn,$SqlQ);
		$dropSql = "DROP TEMPORARY TABLE IF EXISTS temp_full_table".$surveyid;
		mysqli_query($conn,$dropSql);
		$tempTableSql = "CREATE TEMPORARY TABLE IF NOT EXISTS temp_full_table".$surveyid." (
		survey_data_monitoring_id INT AUTO_INCREMENT PRIMARY KEY,
		full_json longtext,
		user_id INT,
		survey_id VARCHAR(55),
		survey_name_id VARCHAR(255),
		survey_status int(11)
	)";
	mysqli_query($conn,$tempTableSql);
	while($surData = mysqli_fetch_object($getSurveyData)){
		$full_json = $surData->full_json;
		$inser_sql = "INSERT INTO `temp_full_table".$surveyid."` set `full_json`='" . $full_json . "',`survey_id`='" . $surData->survey_id . "',`user_id`=" . $surData->user_id . ", `survey_name_id`='" . $surveyid ."',survey_status='".$surData->survey_status."'";
		$insertquery = mysqli_query($conn, $inser_sql);
	}
	
$getGroups = mysqli_query($conn,"SELECT id, group_name FROM questions_group WHERE survey_id='".$surveyid."' AND group_type='group' ");
	$allGroups = mysqli_fetch_all($getGroups, MYSQLI_ASSOC);
	//$group_names = $getGroup->group_name;
	
	foreach($allGroups as $allGroup){
		$group_name = $allGroup['group_name'];
		$group_id = $allGroup['id'];
		$getGroupDatas = mysqli_query($conn,"select MAX(JSON_LENGTH(JSON_EXTRACT(full_json, '$.".$group_name."'))) as groupData from temp_full_table".$surveyid." where survey_name_id='".$surveyid."'");
		$group_data = mysqli_fetch_object($getGroupDatas);
		$gdata[] = $group_data->groupData;
	}
	
	
	$getGroupsscreen = mysqli_query($conn,"SELECT id, group_name FROM questions_group WHERE survey_id='".$surveyid."' AND group_type='screen' ");
	$allGroupsscreen = mysqli_fetch_all($getGroupsscreen, MYSQLI_ASSOC);
	$scrn = $scrnid= [];
	foreach($allGroupsscreen as $allGroupsscreen1){
		$scrn[] = $allGroupsscreen1['group_name'];
		$gname = $allGroupsscreen1['group_name'];
		//$scrnid[] = $allGroupsscreen1['id'];
		
		//$getFieldnames = mysqli_query($conn,"SELECT question_lang_id, question_name,dictionary_label, field_name,question_id, input_field_type, max_input, normal_group_id,repeat_count,repeated,default_response,group_id FROM questions_language WHERE language_id='1' AND question_name!='' AND survey_id='".$surveyid."' AND question_id< (SELECT question_id FROM questions_language WHERE language_id='1' AND field_name='".$gname."' AND survey_id='".$surveyid."') ORDER BY question_id DESC limit 1;");
		//$fnames = mysqli_fetch_object($getFieldnames);
		//$scrn[$fnames->field_name] = $gname;
		
	} 
	// print_r($scrn);
	// die();
	$repeatedQuestions = array();
$getOptions = mysqli_query($conn, "SELECT options_language_id, option_id, question_id, language_id, option_name, option_sequence, category_name FROM options_language WHERE survey_id='" . $surveyid . "' and language_id='$language_id' ");
$allOptions = mysqli_fetch_all($getOptions, MYSQLI_ASSOC);

$getQuestions1 = mysqli_query($conn, "SELECT question_lang_id, question_name, encrpt, dictionary_label, field_name, question_id, input_field_type, max_input, normal_group_id, repeat_count, repeated, default_response, group_id FROM questions_language WHERE language_id='1' AND question_name!='' and questions_type_id not in(16,21,11,12,8,9) AND survey_id='$surveyid'");
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
	
	
$dataQuestions[] = 'uniqueid';
$dataQuestions[] = 'startDateTime';
$dataQuestions[] = 'endDateTime';
$dataQuestions[] = 'device_id';
$dataQuestions[] = 'GPS';
$dataQuestions[] = 'UserName';
$dataQuestions[] = 'SurveyStatus';
$dataQuestions[] = 'TerminationReason';
	
//$export_headers = [];
$msci_reports = [];
$repeatedQuestionsDo = [];
$grp=0;
$grpkey=$notes=[];
$r1=count($field_names)-1;
$multi_heading=[];	
foreach ($getQuestions1_ss as $questiondo) {
    $question = (object) $questiondo;
    $question_name = str_replace('"', '', $question->dictionary_label);
    $msci_reports[$question->field_name] = $question->encrpt;
  //  $export_headers[] = $question->field_name;
	$field_name = $question->field_name;
	$repeated = $question->repeated;
	$repeat_count = $question->repeat_count;
	$input_field_types[$question->field_name]=$question->input_field_type;
	$input_field_type = $question->input_field_type;
	$question_id = $question->question_id;
	$default_response = $question->default_response;
	$groupId = $question->group_id;
	
	
	
	if($repeated!='' && $repeat_count=='' ){
		$repeatedQuestionDo['f'] = $field_name;
		$repeatedQuestionDo['q'] = $question_name;
		$repeatedQuestionDo['intype'] = 'text'; //$input_field_type;
		
		
		$repeatedQuestionsDo[] = $repeatedQuestionDo;
		$repeatedQuestionDo = array();
		
	}else{
		//echo "<pre>";
		if(count($repeatedQuestionsDo)>0){
			$tf = count($field_names);
			$grpkey[] = $field_names[$tf-1];
			for($a=1; $a<=$gdata[$grp]; $a++){
				foreach($repeatedQuestionsDo as $repeatedQuestionDo1){
					$fname = $repeatedQuestionDo1['f'].'_'.$a;
					$fname1 = $repeatedQuestionDo1['q'].'_'.$a;
					$inputFieldType= $repeatedQuestionDo1['q'];
					$intype = $repeatedQuestionDo1['intype'];
					$field_names[] = $fname1;
					$field_input_types[$fname] = $intype;
					$r1++;
					
				}
			}
			$grp++;
		}
		$repeatedQuestionsDo = array();
		
		
		$field_names[] = $question_name;
		$field_input_types[$question->field_name] = $question->input_field_type;
		if($input_field_type=='select_multiple')
			{
				$sqlmultiques12=mysqli_query($conn, "select option_name,option_sequence,language_id from options_language where question_id='".$question_id."' and language_id='".$language_id."' order by options_language_id asc");
				while($datamulitiques12=mysqli_fetch_object($sqlmultiques12))
					{
						
						$opns=str_replace(', ', '-', $datamulitiques12->option_name);
						//$opns=$datamulitiques12->option_name;
						$field_names[]=$question_name.'/'.$opns;
					}
			}
		$r1++;
		
		
	}
	if($input_field_type=="note"){
		$notes[]=$r1;
	}
	
}
	$groupRecords = [];
	foreach($grpkey as $k=>$grpkey1){
		$gname = $allGroups[$k]['group_name'];
		$g['group_name'] = $gname;
		$g['repeatedTime'] = $gdata[$k];
		$groupRecords[$grpkey1] = $g;
	}

// Fetch survey data
$getMondatas = mysqli_query($conn, "SELECT survey_data_monitoring_id, survey_id, survey_data_json, full_json, survey_status, users.name as full_name, termination_reason,survey_name  FROM survey_data_monitoring LEFT JOIN users ON users.user_id = survey_data_monitoring.user_id WHERE survey_name_id='$surveyid' AND survey_status != '7'");
$counter = 2;

while ($mdata = mysqli_fetch_object($getMondatas)) {
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
	
	// echo "<pre>";
	// print_r($survey_data);
	// die();
    foreach ($survey_data as $surveydata1) {
        $opt_value = $surveydata1->option_value;
        $field_name = $surveydata1->field_name;
		$questionNames=getonefield($conn, 'questions', 'question_name', 'question_id', $surveydata1->question_id);
		$questionNames = str_replace('"', '', $questionNames);
        $opt_value = str_replace(array("-/", "/",", "), array("-", "-","-"), $opt_value);
        if ($surveydata1->option_id != "") {
			$option_id = str_replace(",", "", $surveydata1->option_id);
			// Split the option_id into multiple IDs if they are comma-separated
			$allSelChoices = explode(",", $surveydata1->option_id);
			$optionData = [];
			
			// Fetch option names for each option_id
			foreach ($allSelChoices as $allSelChoice) {
				$search = array("question_id" => $surveydata1->question_id, "option_sequence" => $allSelChoice, "language_id" => $language_id);
				$optdata = findData($allOptions, $search);
				if ($optdata) {
					$optionData[] = $optdata;
				}
			}
			
			
			// Combine option names into a comma-separated string
			$opt_value = implode(", ", $optionData);
			
			if($opt_value=='')
			{
				$opt_value=$surveydata1->option_id;
			}
			
		}
        if (strtolower($msci_reports[$surveydata1->field_name]) == "yes" && ($_SESSION['functional_role_id'] == 7 || $identification_type == 'deidentify')) {
            if($input_field_types[$surveydata1->field_name]=='text'){
				$optvalue = substr(str_pad($opt_value, 3, '*'), 0, 3);
				$opt_value = str_repeat("*", strlen($optvalue));
			}else{
				$opt_value = str_replace(array("-/", "/",", "), array("-", "-","-"), $opt_value);
				if ($surveydata1->option_id != "") {
					$opt_value = str_replace(",", "", $surveydata1->option_id);
				}
			}
			
        }
        $jsn_data[$questionNames] = $opt_value;
		
		if($input_field_types[$surveydata1->field_name]=='select_multiple'){
		
				$sqlmultiques=mysqli_query($conn, "select option_name,option_sequence,language_id from options_language where question_id='".$surveydata1->question_id."' and language_id='".$language_id."'");
				while($datamulitiques=mysqli_fetch_object($sqlmultiques))
				{
					$opt_value12='0';
					$opn=str_replace(', ', '-', $datamulitiques->option_name);
					//$opn=$datamulitiques->option_name;
					if(in_array($opn,$optionData))
					{
					$opt_value12='1';	
					}
					else{
						$opt_value12='0';	
					}
					
					$c11=$questionNames.'/'.$opn;
					$jsn_data[$c11] = $opt_value12;
					
				}
			
		}
		//echo $surveydata1->field_name;
		
		if (in_array($surveydata1->field_name, $grpkey)) {
			// Group data processing
			$groupName = $groupRecords[$surveydata1->field_name]['group_name'];
			$group_data = $full_json->$groupName;
			$nofrepeated = $groupRecords[$surveydata1->field_name]['repeatedTime'];

			for ($a = 1; $a <= $nofrepeated; $a++) {
				foreach ($group_data[$a - 1] as $groupdata) {
					$opt_value = $groupdata->option_value;
					$opt_value = str_replace(array("-/", "/",", "), array("-", "-","-"), $opt_value);

					if ($groupdata->option_id != "") {
						$allSelChoices = explode(",",$groupdata->option_id);
						$optionData = []; 

						// Fetch option names for each option_id
						foreach ($allSelChoices as $allSelChoice) {
							$search = array("question_id" => $groupdata->question_id, "option_sequence" => $allSelChoice, "language_id" =>$language_id);
							$optdata = findData($allOptions, $search);
							if ($optdata) {
								//$optdata=str_replace('/','-',$optdata);
								$optionData[] = $optdata;
							}
						}
						// Combine option names into a comma-separated string
						$opt_value = implode(", ", $optionData);
						
						if($opt_value=='')
							{
								$opt_value=$surveydata1->option_id;
							}
					}

					if (strtolower($msci_reports[$groupdata->field_name]) == "yes" && ($_SESSION['functional_role_id'] == 7 || $identification_type == 'deidentify')) {
						if($input_field_types[$groupdata->field_name]=='text'){
							$optvalue = substr(str_pad($opt_value, 3, '*'), 0, 3);
							$opt_value = str_repeat("*", strlen($optvalue));
						}else{
							//$opt_value = str_replace(array("-/", "/"), array("-", "-"), $opt_value);
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
	foreach($scrn as $srcn1){
			$groupName = $srcn1;
			$group_data = $full_json->$groupName;
			// echo "<pre>";
			 // print_r($scrn);
			foreach ($group_data[0] as $groupdata) {
				$opt_value = $groupdata->option_value;
				$grpqnName=getonefield($conn, 'questions', 'question_name', 'question_id', $groupdata->question_id);
				$grpqnName=str_replace('"', '', $grpqnName);
				$opt_value = str_replace(array("-/", "/",", "), array("-", "-","-"), $opt_value);

				if ($groupdata->option_id!= "") {
					$allSelChoices = explode(",", $groupdata->option_id);
					$optionData = [];
                    
					// Fetch option names for each option_id
					foreach ($allSelChoices as $allSelChoice) {
						$search = array("question_id" => $groupdata->question_id, "option_sequence" => $allSelChoice, "language_id" =>$language_id);
						$optdata = findData($allOptions, $search);
						if ($optdata) {
							//$optdata=str_replace('/','-',$optdata);
							$optionData[] = $optdata;
						}
					}
					// Combine option names into a comma-separated string
					
					$opt_value = implode(", ", $optionData);
					if($opt_value=='')
					{
						$opt_value=$groupdata->option_id;
					}
				}

				$fnm = $groupdata->field_name;
				$fnm1 = $groupdata->field_name;
				$jsn_data[$grpqnName] = $opt_value;
				//$jsn_data[$fnm] = $opt_value;
				$grpinptutt='';
				$grpinptutt=getonefield($conn, 'questions', 'input_field_type', 'question_id', $groupdata->question_id);
				
				if($grpinptutt=='select_multiple'){
					
							$sqlmultiques1=mysqli_query($conn, "select option_name,option_sequence,language_id from options_language where question_id='".$groupdata->question_id."' and language_id='".$language_id."'");
							while($datamulitiques1=mysqli_fetch_object($sqlmultiques1))
							{
								$opt_value23='0';
								//$multi_heading[$groupdata->question_id][]=$datamulitiques1->option_sequence;
								//$field_names[]=$datamulitiques1->option_name;
								$opn1=str_replace(', ','-',$datamulitiques1->option_name);
								if(in_array($opn1,$optionData))
								{
								$opt_value23='1';	
								}
								else{
									$opt_value23='0';	
								}
								//$jsn_data[$datamulitiques1->option_sequence] = $opt_value23;
								$opn1=str_replace(', ', '-', $datamulitiques1->option_name);
								$groupoptName=$grpqnName.'/'.$opn1;
								$jsn_data[$groupoptName] = $opt_value23;
							}
						
					} 
				////////////Group under question open in roster data/////////////
				// echo "<pre>";
				// print_r($groupRecords);
				if (array_key_exists($fnm1, $groupRecords)) {
					// Group data processing
					$groupName1 = $groupRecords[$fnm1]['group_name'];
					// echo "<pre>";
					// echo $fnm1;
					// print_r($groupRecords[$fnm1]['group_name']);
					$group_data2 = $full_json->$groupName1;
					
					$nofrepeated = $groupRecords[$fnm1]['repeatedTime'];
					
					for ($a = 1; $a <= $nofrepeated; $a++) {
						foreach ($group_data2[$a - 1] as $groupdata2) {
							//echo $groupdata2;
							$opt_value = $groupdata2->option_value;
							//$opt_value = str_replace(array("-/", "/"), array("-", "-"), $opt_value);

							if ($groupdata2->option_id != "") {
								$allSelChoices = explode(",", $groupdata2->option_id);
								$optionData = [];

								// Fetch option names for each option_id
								foreach ($allSelChoices as $allSelChoice) {
									$search = array("question_id" => $groupdata2->question_id, "option_sequence" => $allSelChoice, "language_id" =>$language_id);
									$optdata = findData($allOptions, $search);
									if ($optdata) {
										$optionData[] = $optdata;
									}
								}
								// Combine option names into a comma-separated string
								$opt_value = implode(", ", $optionData);
							}

							if (strtolower($msci_reports[$groupdata2->field_name]) == "yes" && ($_SESSION['functional_role_id'] == 7 || $identification_type == 'deidentify')) {
								 if($input_field_types[$groupdata2->field_name]=='text'){
									$optvalue = substr(str_pad($opt_value, 3, '*'), 0, 3);
									$opt_value = str_repeat("*", strlen($optvalue));
								}else{
									$opt_value = str_replace(array("-/", "/"), array("-", "-"), $opt_value);
									if ($groupdata2->option_id != "") {
										$opt_value = str_replace(",", "", $groupdata2->option_id);
									}
								}
							}

							$fnm2 = $groupdata2->field_name . '_' . $a;
							$jsn_data[$fnm2] = $opt_value;
						}
					}
				} 
				
			}
		}
	// echo "<pre>";
	// print_r($jsn_data);
	
	$jsn_data_arr[] = $jsn_data;
	$jsn_data = array();
	
}
 //die();
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
		$activeSheet->setCellValue($cell, $colHeader);
		// Apply bold styling
		$activeSheet->getStyle($cell)->getFont()->setBold(true);
	}
	// echo "<pre>";
	// print_r($data);
	// die();
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
$file_name = "ExcelData-".str_replace(" ","_",$survey_name).".xlsx";

$path = base_url()."/uploaded_questionnaire/abc.xlsx";
$resArr = array('status'=>200,'path'=>$path,'fname'=>$file_name);
echo json_encode($resArr);
?>
