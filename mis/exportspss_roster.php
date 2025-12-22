<?php include_once('includes/config.php');
include_once('includes/functions.php');

ini_set('memory_limit', '-1');
mb_internal_encoding('UTF-8');
$starttime = strtotime(date("Y-m-d H:i:s"));

$surveyid = $_REQUEST['survey_id'];
$identification_type = $_REQUEST['spss_export_types'];
$survey_name = getone($conn, "survey", "survey_name", "id", $surveyid);
$survey_name = str_replace(" ", "_", $survey_name);
?>

<?php
//	echo "<pre>";
$new_file_namesps = "spss/Exported.sps";
$mynewfile_sps = fopen($new_file_namesps, "w");
//$text = "This contant write in the new file....";
$new_text = "*Before running the syntax, please replace XXX with the correct path where the .dat file is located." . "\n\n";
$new_text .= "DATA LIST FILE='XXX\Exported.dat' RECORDS=1" . "\n";

// temp DB created



$new_text .= '/' . "\n";
//$variale_lbl = '' . "\n" . 'VARIABLE LABELS' . "\n";
$variale_lbl = '' . "\n" . 'VARIABLE LABELS' . "";
//$opt_txt = '' . "\n" . 'VALUE LABELS' . "\n";
$opt_txt = '' . "\n" . 'VALUE LABELS' . "\n";

$startposition = 243;
$endposition = 1;

$external_ques = 'UNIQUE_ID    1-15   (A)' . "\n";
$external_ques .= 'STARTDATETIME    16-36   (A)' . "\n";
$external_ques .= 'ENDDATETIME    37-57   (A)' . "\n";
$external_ques .= 'DEVICE_ID    58-78   (A)' . "\n";
$external_ques .= 'GPS    79-129   (A)' . "\n";
$external_ques .= 'USERNAME    130-170   (A)' . "\n";
$external_ques .= 'SURVEY_STATUS     171-191   (A)' . "\n";
$external_ques .= 'TERMINATION_REASON    192-242   (A)' . "\n";

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
	// $getFieldnames = mysqli_query($conn, "SELECT question_lang_id, question_name,dictionary_label, field_name,question_id, input_field_type, max_input, normal_group_id,repeat_count,repeated,default_response,group_id FROM questions_language WHERE language_id='1' AND question_name!='' and input_field_type!='note' AND survey_id='" . $surveyid . "' AND question_id< (SELECT question_id FROM questions_language WHERE language_id='1' AND field_name='" . $gname . "' AND survey_id='" . $surveyid . "') ORDER BY question_id DESC limit 1;");
	// $fnames = mysqli_fetch_object($getFieldnames);
	// $scrn[$fnames->field_name] = $gname;
	$scrn[$anon_field[$gkey]] = $gname;
}

$repeatedQuestions = array();

$getQuestions1 = mysqli_query($conn, "SELECT question_lang_id, question_name,encrpt,dictionary_label, field_name,question_id, input_field_type, max_input, normal_group_id,repeat_count,repeated,default_response,group_id FROM questions_language WHERE language_id='1'   AND question_name!='' and input_field_type!='note' and question_id>1 AND survey_id='" . $surveyid . "'");
$tRows = mysqli_num_rows($getQuestions1);

$getQuestions1_ss = mysqli_fetch_all($getQuestions1, MYSQLI_ASSOC);
$tRows1 = $tRows;

$getQuestions12 = mysqli_query($conn, "SELECT GROUP_CONCAT(field_name) as notearray FROM questions_language WHERE language_id='1' and input_field_type='note' AND survey_id='" . $surveyid . "'");
$dataData12 = mysqli_fetch_object($getQuestions12);
$notearray = explode(',', $dataData12->notearray);

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

$new_text .= $external_ques;
$r = 0;

//$typesarray=('')
foreach ($getQuestions1_ss as $questiondo) {
	$question = (object)$questiondo;
	$question_name = str_replace('"', '', $question->dictionary_label);
	$msci_reports[$question->field_name] = $question->encrpt;
	$field_name = $question->field_name;
	$input_field_type = $question->input_field_type;
	$question_id = $question->question_id;
	$normal_group_id = $question->normal_group_id;
	$repeated = $question->repeated;

	$repeat_count = $question->repeat_count;
	$default_response = $question->default_response;
	$groupId = $question->group_id;



	$alpha = '';
	$defaultMaxlimit = 0;
	if ($input_field_type == 'text') {
		$alpha = '(A)';
	} else if ($input_field_type == 'date') {
		$alpha = '(A)';
		$defaultMaxlimit = 40;
	} else if ($input_field_type == 'select_multiple') {
		$alpha = '(A)';
	} else if ($input_field_type == 'picture') {
		$alpha = '(A)';
		$defaultMaxlimit = 100;
	} else if ($input_field_type == 'audio') {
		$alpha = '(A)';
		$defaultMaxlimit = 100;
	} else if ($input_field_type == 'video') {
		$alpha = '(A)';
		$defaultMaxlimit = 100;
	} else if ($input_field_type == 'gps-button') {
		$alpha = '(A)';
		$defaultMaxlimit = 100;
	}

	$max_input = $defaultMaxlimit > 0 ? $defaultMaxlimit : $question->max_input;
	$startposition = $startposition;
	$endposition = $startposition + $max_input;

	$maxinputs['uniqueid'] = 15;
	$maxinputs['startDateTime'] = 20;
	$maxinputs['endDateTime'] = 20;
	$maxinputs['device_id'] = 20;
	$maxinputs['GPS'] = 50;
	$maxinputs['UserName'] = 40;
	$maxinputs['SurveyStatus'] = 20;
	$maxinputs['TerminationReason'] = 50;
	$maxinputs[$field_name] = $max_input;
	/////////////////////////////// New /////////////////////////////////
	if ($repeated != '' && $repeat_count == '') {
		$repeatedQuestionDo['f'] = $field_name;
		$repeatedQuestionDo['q'] = $question_name;
		$repeatedQuestionDo['intype'] = $input_field_type;
		$repeatedQuestionDo['mx'] = $max_input;

		if ($input_field_type == "select_one" && $default_response == '') {
			$getOptions = mysqli_query($conn, "SELECT option_id, option_sequence, option_name FROM options_language WHERE status='0' AND language_id='1' AND survey_id='" . $surveyid . "' AND question_id='" . $question_id . "' group by option_sequence");

			while ($options = mysqli_fetch_object($getOptions)) {
				$option_sequence = $options->option_sequence;
				$option_name = $options->option_name;
				$option_name = preg_replace('/[^A-Za-z0-9\-. ]/', '', $option_name);
				$repeatedOptionDo['oseq'] = $option_sequence;
				$repeatedOptionDo['onm'] = $option_name;

				$repeatedOptionsDo[] = $repeatedOptionDo;
			}
			$repeatedOptionDo = [];
			$repeatedOptionsDo2[$field_name] = $repeatedOptionsDo;
			$repeatedOptionsDo = [];
		}
		$repeatedQuestionsDo[] = $repeatedQuestionDo;
		$repeatedQuestionDo = [];
	} else {
		if (count($repeatedQuestionsDo) > 0) {

			$tf = count($field_names);

			$grpkey[] = $field_names[$tf - 1];
			//echo "</pre>";
			//echo print_r($grpkey);
			for ($a = 1; $a <= $gdata[$grp]; $a++) {
				foreach ($repeatedQuestionsDo as $repeatedQuestionDo1) {
					//$intype='';
					$maxinputs1 = 0;
					$fname = $repeatedQuestionDo1['f'] . '_' . $a;
					$inputFieldType = $repeatedQuestionDo1['q'];
					$intype = $repeatedQuestionDo1['intype'];
					$field_names[] = $fname;
					//$maxinputs[$fname]=$repeatedQuestionDo1['mx'];
					//$maxinputs1 = $repeatedQuestionDo1['mx'];
					$maxinputs[$fname] = $defaultMaxlimit > 0 ? $defaultMaxlimit : $repeatedQuestionDo1['mx'];
					$maxinputs1 = $defaultMaxlimit > 0 ? $defaultMaxlimit : $repeatedQuestionDo1['mx'];
					//$maxinputs1 = $repeatedQuestionDo1['mx'];
					$field_input_types[$fname] = $intype;
					$r1++;
					//echo $fname.'<br/>';
					$alpha = '';
					if ($intype == 'text') {
						$alpha = '(A)';
					} else if ($intype == 'date') {
						$alpha = '(A)';
					} else if ($intype == 'select_multiple') {
						$alpha = '(A)';
					} else if ($intype == 'gps-button') {
						$alpha = '(A)';
					} else if ($intype == 'picture') {
						$alpha = '(A)';
					} else if ($intype == 'audio') {
						$alpha = '(A)';
					} else if ($intype == 'video') {
						$alpha = '(A)';
					}

					//echo $intype.'-'.$field_name."<br>";
					if ($field_input_types[$fname] == "select_one") {
						$opt_txt .= ' /' . strtoupper($fname) . "\n";
						foreach ($repeatedOptionsDo2[$repeatedQuestionDo1['f']] as $k => $repeatedOptionDo) {
							$oseq = $repeatedOptionDo['oseq'];
							$onm = $repeatedOptionDo['onm'];
							$onm = preg_replace('/[^A-Za-z0-9\-. ]/', '', $onm);
							$opt_txt .= '     ' . $oseq . ' "' . substr($onm, 0, 118) . '"' . "\n";
						}
						//echo $opt_txt;	
					}

					if ($intype == "note") {
						$notes[] = $r1;
					}

					//$startposition = $startposition;
					$endposition = $startposition + $maxinputs1;
					$startposition = $endposition + 1;
					//$endposition = $startposition + $max_input-1;

					/*else{
						$variale_lbl .= ' ' . strtoupper($fname) . '       "' . $inputFieldType . '"' . "\n";
					}*/
					//$variale_lbl .= ' /' . strtoupper($fname) . '       "' . $inputFieldType . '"' . "\n";


					if ($intype != "note") {
						$new_text .= strtoupper($fname) . '    ' . $startposition . '-' . $endposition . '   ' . $alpha . "\n";
						if ($r > 0) {
							$variale_lbl .= ' /' . strtoupper($fname) . '       "' . preg_replace('/[^A-Za-z0-9\-. ]/', '', substr($inputFieldType, 0, 240)) . '"' . "\n";
						} else {
							$variale_lbl .= ' ' . strtoupper($fname) . '       "' . preg_replace('/[^A-Za-z0-9\-. ]/', '', substr($inputFieldType, 0, 240)) . '"' . "\n";
						}
						$r++;
					}


					//$endposition = $endposition + 1;
				}
			}
			//die();

			$grp++;
		}
		$repeatedQuestionsDo = array();


		$field_names[] = $question->field_name;
		$field_input_types[$question->field_name] = $question->input_field_type;
		$r1++;


		if ($input_field_type == "select_one") {
			$getOptions = mysqli_query($conn, "SELECT option_id, option_sequence, option_name FROM options_language WHERE status='0' AND language_id='1' AND survey_id='" . $surveyid . "' AND question_id='" . $question_id . "' group by option_sequence");
			$opt_txt .= ' /' . strtoupper($field_name) . "\n";

			while ($options = mysqli_fetch_object($getOptions)) {
				$option_sequence = $options->option_sequence;
				$option_name = $options->option_name;
				$option_name = preg_replace('/[^A-Za-z0-9\-. ]/', '', $option_name);

				$opt_txt .= '     ' . $option_sequence . ' "' . substr($option_name, 0, 118) . '"' . "\n";
			}

			//$opt_txt.=';'."\n";

			//$txt_mapping.='label values '.$field_name.'     '.strtoupper($field_name)."\n"; 
		}


		if ($input_field_type != "note") {
			$new_text .= strtoupper($field_name) . '    ' . $startposition . '-' . $endposition . '   ' . $alpha . "\n";
			if ($r > 0) {
				$variale_lbl .= ' /' . strtoupper($field_name) . '       "' . preg_replace('/[^A-Za-z0-9\-. ]/', '', substr($question_name, 0, 240)) . '"' . "\n";
			} else {
				$variale_lbl .= ' ' . strtoupper($field_name) . '       "' . preg_replace('/[^A-Za-z0-9\-. ]/', '', substr($question_name, 0, 240)) . '"' . "\n";
			}
			$r++;
		}
		$startposition = $endposition + 1;
	}
	//
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
// die();
$new_text = substr($new_text, 0, -1);
$new_text .= ".\n";

$variale_lbl = substr($variale_lbl, 0, -1);
$variale_lbl .= ".";

$opt_txt = substr($opt_txt, 0, -1);
$opt_txt .= ".";

$new_text .= $variale_lbl . "\n" . $opt_txt . "\n" . "\nEXECUTE.";

fwrite($mynewfile_sps, $new_text);
fclose($mynewfile_sps);
?>

<?php
//CREATE DAT FILE
//echo "<pre>";
$new_file_name_dat = "spss/Exported.dat";
$mynewfile_dat = fopen($new_file_name_dat, "w");
$text = "This contant write in the new file....";

$startposition = 243;
$endposition = 1;

/////////////////////// New///////////////////////////

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


//////////////////////////// Old////////////////////////




/*
foreach ($jsn_data_arr as $jsn_data_arr_val1) {
	foreach ($jsn_data_arr_val1 as $k => $jsn_data_arr_val) {
		$sdata = $jsn_data_arr_val; //$jsonArrs[$i]->$field_name;
		$maxinput = $maxinputs[$k];
		$spc = $maxinput - strlen($sdata);
		$getspc = space($spc);
		$new_text_dat .= $sdata . $getspc;

	}
	$new_text_dat .= "\n";
}*/
$mm = 0;
foreach ($jsn_data_arr as $jsn_data_arr_val1) {

	foreach ($maxinputs as $k => $jsn_data_arr_val) {
		$sdata = $jsn_data_arr[$mm][$k]; //$jsonArrs[$i]->$field_name;
		$sdata = iconv('UTF-8', 'ASCII//TRANSLIT',  $sdata);
		$maxinput = $maxinputs[$k];

		$sdata = mb_convert_encoding($sdata, 'UTF-8', 'UTF-8');

		$spc = $maxinput - mb_strlen($sdata, 'UTF-8'); //mb_strlen($sdata);

		$getspc = space($spc);
		$new_text_dat .= $sdata . $getspc;
	}
	$new_text_dat .= "\n";
	$mm++;
}

function space($num)
{
	$svar = ' ';
	if ($num > 0) {
		for ($s = 0; $s < $num; $s++) {
			$svar .= ' ';
		}
	}
	return $svar;
}

fwrite($mynewfile_dat, $new_text_dat);
fclose($mynewfile_dat);
?>
 
<?php
// Create zip
function createZip($zip, $dir)
{
	if (is_dir($dir)) {

		if ($dh = opendir($dir)) {
			while (($file = readdir($dh)) !== false) {

				// If file
				if (is_file($dir . $file)) {
					if ($file != '' && $file != '.' && $file != '..') {

						$zip->addFile($dir . $file);
					}
				} else {
					// If directory
					if (is_dir($dir . $file)) {

						if ($file != '' && $file != '.' && $file != '..') {

							// Add empty directory
							$zip->addEmptyDir($dir . $file);

							$folder = $dir . $file . '/';

							// Read data of the folder
							createZip($zip, $folder);
						}
					}
				}
			}
			closedir($dh);
		}
	}
}
//////////Create folder////////
$client_id = $_SESSION['client_id'];
$clientId = "C" . $client_id;
$location = "./spss_zip/" . $clientId . "/";

if (!file_exists($location)) {
	if (!mkdir($location, 0777, true)) {
		//$error='Somthing Went wrong !!';
	} else {
		//echo "create";
		mkdir($location, 0777, true);
	}
}
///////////////////


$endtime = strtotime(date("Y-m-d H:i:s"));

$time_diff = $endtime - $starttime;
// CREATE ZIP FILE
$zip = new ZipArchive();
//$filename = "./spss_zip/spss-".$survey_name.".zip";
$filename = "spss_zip/" . $clientId . "/spss-" . $survey_name . ".zip";
if ($zip->open($filename, ZipArchive::CREATE) !== TRUE) {
	exit("cannot open <$filename>\n");
}
$dir = 'spss/';
// Create zip
createZip($zip, $dir);
$zip->close();
?>





<?php
//DOWNLOAD ZIP
$filename1 = "./spss_zip/" . $clientId . "/spss-" . $survey_name . ".zip";
if (file_exists($filename1)) {
	$result_data = array("status" => 200, "message" => "SPSS file created", "file_name" => $filename1, "time_diff" => $time_diff);
	echo json_encode($result_data);

	//unlink($filename1);

}

?>