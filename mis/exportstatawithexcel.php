<?php
include_once('includes/config.php');
include_once('includes/functions.php');
?>
<?php
header('Content-Type: text/csv; charset=UTF-8');
$time_diff = 0;

$starttime = strtotime(date("Y-m-d H:i:s"));

$identification_type = $_POST['export_types_data'];
include_once('create-questionnaire.php');
if (isset($_REQUEST['survey_id'])) {
	$surveyid = $_REQUEST['survey_id'];
	ini_set('memory_limit', '-1');

	// temp DB created
	$SqlQ = "SELECT full_json, user_id, survey_id,survey_status FROM survey_data_monitoring WHERE survey_name_id=" . $surveyid . " and survey_status!=7";

	$getSurveyData = mysqli_query($conn, $SqlQ);
	$dropSql = "DROP TEMPORARY TABLE IF EXISTS temp_full_table" . $surveyid;
	mysqli_query($conn, $dropSql);
	$tempTableSql = "CREATE TEMPORARY TABLE IF NOT EXISTS temp_full_table" . $surveyid . " (
		survey_data_monitoring_id INT AUTO_INCREMENT PRIMARY KEY,
		full_json longtext,
		user_id INT,
		survey_id VARCHAR(55),
		survey_name_id VARCHAR(255),
		survey_status int(11)
	)";
	mysqli_query($conn, $tempTableSql);
	while ($surData = mysqli_fetch_object($getSurveyData)) {
		//$full_json = $mcrypt->decrypt($surData->full_json);
		$full_json = $surData->full_json;
		$inser_sql = "INSERT INTO `temp_full_table" . $surveyid . "` set `full_json`='" . $full_json . "',`survey_id`='" . $surData->survey_id . "',`user_id`=" . $surData->user_id . ", `survey_name_id`='" . $surveyid . "',survey_status='" . $survey_status . "'";
		$insertquery = mysqli_query($conn, $inser_sql);
	}

	$survey_name = getone($conn, "survey", "survey_name", "id", $surveyid);
	$survey_name = str_replace(" ", "_", $survey_name);


	// CREATE DO FILE
	//echo "<pre>";
	$new_file_namedo = "stata/Exported.do";
	$mynewfile_do = fopen($new_file_namedo, "w");
	//$text = "This contant write in the new file....";

	// $new_text_do='infix using "Exported.dct"'."\n\n";
	//$new_text_do='import excel using "Exported.xlsx", firstrow'."\n\n";
	$new_text_do = 'import delimited using "data.csv",delimiter(comma) varnames(1) case(preserve) asdouble encoding(UTF-8) clear' . "\n\n";

	$new_text_do .= 'label variable uniqueid   "Unique Id"' . "\n";
	$new_text_do .= 'label variable startDateTime   "Start DateTime"' . "\n";
	$new_text_do .= 'label variable endDateTime   "End DateTime"' . "\n";
	$new_text_do .= 'label variable device_id   "Device Id"' . "\n";
	$new_text_do .= 'label variable GPS   "GPS"' . "\n";
	$new_text_do .= 'label variable UserName   "UserName"' . "\n";
	$new_text_do .= 'label variable SurveyStatus   "SurveyStatus"' . "\n";
	$new_text_do .= 'label variable TerminationReason   "TerminationReason"' . "\n";

	$txt_mapping .= '#delimit cr' . "\n";
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
	//	$scrn[$fnames->field_name] = $gname;
		$scrn[$anon_field[$gkey]] = $gname;
	}

	$repeatedQuestions = array();

	$getQuestions1 = mysqli_query($conn, "SELECT question_lang_id, question_name,encrpt,dictionary_label, field_name,question_id, input_field_type, max_input, normal_group_id,repeat_count,repeated,default_response,group_id FROM questions_language WHERE language_id='1'   AND question_name!='' AND survey_id='" . $surveyid . "'");
	$tRows = mysqli_num_rows($getQuestions1);
	$getQuestions1_ss = mysqli_fetch_all($getQuestions1, MYSQLI_ASSOC);
	$tRows1 = $tRows;


	$field_names[] = 'uniqueid';
	$field_names[] = 'startDateTime';
	$field_names[] = 'endDateTime';
	$field_names[] = 'device_id';
	$field_names[] = 'GPS';
	$field_names[] = 'UserName';
	$field_names[] = 'SurveyStatus';
	$field_names[] = 'TerminationReason';
	$field_input_types['uniqueid'] = 'text';
	$field_input_types['startDateTime'] = 'date';
	$field_input_types['endDateTime'] = 'date';
	$field_input_types['device_id'] = 'text';
	$field_input_types['GPS'] = 'text';
	$field_input_types['UserName'] = 'text';
	$field_input_types['SurveyStatus'] = 'text';
	$field_input_types['TerminationReason'] = 'text';

	$repeatedQuestionsDo = [];
	$grp = 0;
	$grpkey = $notes = [];
	$r1 = count($field_names) - 1;

	foreach ($getQuestions1_ss as $questiondo) {
		$question = (object)$questiondo;
		$question_name = str_replace('"', '', $question->dictionary_label);
		$msci_reports[$question->field_name] = $question->encrpt;
		$field_name = $question->field_name;
		$input_field_type = $question->input_field_type;
		$input_field_types[$question->field_name] = $question->input_field_type;
		$question_id = $question->question_id;
		$normal_group_id = $question->normal_group_id;
		$repeated = $question->repeated;
		$repeat_count = $question->repeat_count;
		$default_response = $question->default_response;
		$groupId = $question->group_id;

		if ($repeated != '' && $repeat_count == '') {
			$repeatedQuestionDo['f'] = $field_name;
			$repeatedQuestionDo['q'] = $question_name;
			$repeatedQuestionDo['intype'] = 'text'; //$input_field_type;

			if ($input_field_type == "select_one" && $default_response == '') {
				$repeatedQuestionDo['intype'] = $input_field_type;
				$getOptions = mysqli_query($conn, "SELECT option_id, option_sequence, option_name FROM options_language WHERE status='0' AND language_id='1' AND survey_id='" . $surveyid . "' AND question_id='" . $question_id . "' ");

				while ($options = mysqli_fetch_object($getOptions)) {
					$option_sequence = $options->option_sequence;
					$option_name = $options->option_name;
					$repeatedOptionDo['oseq'] = $option_sequence;
					$repeatedOptionDo['onm'] = trim($option_name);
					$repeatedOptionsDo[] = $repeatedOptionDo;
				}
				$repeatedOptionsDo2[$field_name] = $repeatedOptionsDo;
				$repeatedOptionsDo = [];
			}
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
						if ($intype == "select_one") {
							$opt_txt .= 'label define ' . strtoupper($fname) . "\n";
							foreach ($repeatedOptionsDo2[$repeatedQuestionDo1['f']] as $k => $repeatedOptionDo) {
								$oseq = $repeatedOptionDo['oseq'];
								$onm = $repeatedOptionDo['onm'];
								$opt_txt .= '     ' . $oseq . ' "' . $onm . '"' . "\n";
							}
							$opt_txt .= ';' . "\n";
							$txt_mapping .= 'label values ' . $fname . '     ' . strtoupper($fname) . "\n";
						}
						if ($intype != "note") {
							$new_text_do .= 'label variable ' . $fname . '   "' . $inputFieldType . '"' . "\n";
						}
					}
				}
				$grp++;
			}
			$repeatedQuestionsDo = array();


			$field_names[] = $question->field_name;
			$field_input_types[$question->field_name] = $question->input_field_type;
			$r1++;

			if ($input_field_type == "select_one") {

				$getOptions = mysqli_query($conn, "SELECT option_id, option_sequence, option_name FROM options_language WHERE status='0' AND language_id='1' AND survey_id='" . $surveyid . "' AND question_id='" . $question_id . "' ");
				$opt_txt .= 'label define ' . strtoupper($field_name) . "\n";

				while ($options = mysqli_fetch_object($getOptions)) {
					$option_sequence = $options->option_sequence;
					$option_name = trim($options->option_name);

					$opt_txt .= '     ' . $option_sequence . ' "' . $option_name . '"' . "\n";
				}
				$opt_txt .= ';' . "\n";

				$txt_mapping .= 'label values ' . $field_name . '     ' . strtoupper($field_name) . "\n";
			}
			if ($input_field_type != "note") {
				$new_text_do .= 'label variable ' . $field_name . '   "' . $question_name . '"' . "\n";
			}
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

	$new_text_do;
	// SET OPTIONS
	$new_text_do .= "\n";
	$new_text_do .= '#delimit ;' . "\n";
	$new_text_do .= $opt_txt . "\n" . $txt_mapping;
	// die;
	fwrite($mynewfile_do, $new_text_do);
	fclose($mynewfile_do);

	$getMondatas = mysqli_query($conn, "SELECT survey_data_monitoring_id, survey_id, survey_data_json, full_json,survey_status,users.name as full_name,termination_reason FROM survey_data_monitoring left join users on users.user_id=survey_data_monitoring.user_id WHERE survey_name_id='" . $surveyid . "' and survey_status!='7'");
	while ($mdata = mysqli_fetch_object($getMondatas)) {
		//$mdata->full_json;
		//$fullData = $mcrypt->decrypt($mdata->full_json);
		$fullData = $mdata->full_json;
		$full_json = json_decode($fullData);
		// echo "<pre>";
		// print_r($full_json);
		$survey_data = $full_json->survey_data;
		$survey_id = $mdata->survey_id;
		$sequence_unique_id = $full_json->sequence_unique_id;
		$uniqueID = ($sequence_unique_id != '') ? $sequence_unique_id : $survey_id;

		$jsn_data['uniqueid'] = $uniqueID;
		$jsn_data['startDateTime'] = dateformate($full_json->start_date_time);
		$jsn_data['endDateTime'] = dateformate($full_json->end_date_time);
		$jsn_data['device_id'] = $full_json->device_id;
		//$jsn_data['GPS'] = substr($full_json->GPS_latitude_mid,0,10).','.substr($full_json->GPS_longitude_mid,0,10);
		$gps = $full_json->GPS_latitude_mid . ',' . $full_json->GPS_longitude_mid;
		if (empty($full_json->GPS_latitude_mid)) {
			$gps = $full_json->GPS_latitude_start . ',' . $full_json->GPS_longitude_start;
		}
		$jsn_data['GPS'] = $gps;
		$jsn_data['UserName'] = $mdata->full_name;
		$jsn_data['SurveyStatus'] = getSurveyStatus($mdata->survey_status);
		$jsn_data['TerminationReason'] = $mdata->termination_reason;
		$opt_value = '';
		foreach ($survey_data as $surveydata1) {
			$opt_value = $surveydata1->option_value;
			$opt_value = str_replace(array("-/", "/"), array("-", "-"), $opt_value);
			if ($surveydata1->option_id != "") {
				$opt_value = str_replace(",", "", $surveydata1->option_id);
			}
			if (strtolower($msci_reports[$surveydata1->field_name]) == "yes" && ($_SESSION['functional_role_id'] == 7 || $identification_type == 'deidentify')) {
				//$opt_value = str_repeat("X", strlen($opt_value));
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
				//print_r($group_data);
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

						$nofrepeated = $groupRecords[$fnm1]['repeatedTime'];
						for ($a = 1; $a <= $nofrepeated; $a++) {

							foreach ($group_data1[$a - 1] as $groupdata) {
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

	$jsn_data_json = json_encode($jsn_data_arr);

	$field_names = array_diff_key($field_names, array_flip($notes));
	$ex = generateCSV($field_names, $jsn_data_json);
	//////////Create folder////////
	$client_id = $_SESSION['client_id'];
	$clientId = "C" . $client_id;
	$location = "./stata_zip/" . $clientId . "/";

	if (!file_exists($location)) {
		if (!mkdir($location, 0777, true)) {
			$error = 'Somthing Went wrong !!';
		} else {
			//echo "create";
			mkdir($location, 0777, true);
		}
	}
	///////////////////
	// CREATE ZIP FILE
	function zip_creation($source, $destination)
	{
		$dir = opendir($source);
		$result = ($dir === false ? false : true);

		if ($result !== false) {


			$rootPath = realpath($source);

			// Initialize archive object
			$zip = new ZipArchive();
			// $zipfilename = $destination.".zip";
			$zipfilename = $destination;
			$zip->open($zipfilename, ZipArchive::CREATE | ZipArchive::OVERWRITE);

			// Create recursive directory iterator
			/** @var SplFileInfo[] $files */
			$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootPath), RecursiveIteratorIterator::LEAVES_ONLY);

			foreach ($files as $name => $file) {
				// Skip directories (they would be added automatically)
				if (!$file->isDir()) {
					// Get real and relative path for current file
					$filePath = $file->getRealPath();
					$relativePath = substr($filePath, strlen($rootPath) + 1);

					// Add current file to archive
					$zip->addFile($filePath, $relativePath);
				}
			}

			// Zip archive will be created only after closing object
			$zip->close();

			return TRUE;
		} else {
			return FALSE;
		}
	}
	$dir = 'stata/';
	//$zip = new ZipArchive();
	$filename = "./stata_zip/" . $clientId . "/stata-" . $survey_name . ".zip";
	$source = $dir;
	$destination = $filename;
	$zipcreation = zip_creation($source, $destination);

	$filename1 = "./stata_zip/" . $clientId . "/stata-" . $survey_name . ".zip";
	$filename = "My Zip File Download.zip";
	$endtime = strtotime(date("Y-m-d H:i:s"));
	$time_diff = $endtime - $starttime;
	$ress = array("status" => 200, "message" => "Stata file created", "file_name" => $filename1, "time_diff" => $time_diff);
	echo json_encode($ress);
	//die;
}
?>