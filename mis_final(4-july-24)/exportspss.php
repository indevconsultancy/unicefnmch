<?php include_once('includes/config.php');
include_once('includes/functions.php');
include_once('api/mycrypt.php');
?>

<?php
ini_set('memory_limit','-1');
$starttime = strtotime(date("Y-m-d H:i:s"));
?>
<?php
if ($_SESSION['enckey'] === 0) {
	die("You are not authorized to see the survey");
}
$mcrypt = new EncryptionUtils($_SESSION['enckey']);
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
$new_text = "*Before running the syntax, please replace XXX with the correct path where the .dat file is located.". "\n\n";
$new_text .= "DATA LIST FILE='XXX\Exported.dat' RECORDS=1" . "\n";

$getGroups = mysqli_query($conn, "SELECT id, group_name FROM questions_group WHERE survey_id='" . $surveyid . "' AND (group_type='group' OR group_type='screen')  ");
$getGroup = mysqli_fetch_object($getGroups);
$group_name = $getGroup->group_name;

$getGroupDatas = mysqli_query($conn, "select MAX(JSON_LENGTH(JSON_EXTRACT(full_json, '$." . $group_name . "'))) as groupData from survey_data_monitoring where survey_name_id='" . $surveyid . "' ");
$group_data = mysqli_fetch_object($getGroupDatas);
$gdata = $group_data->groupData;


$getQuestions = mysqli_query($conn, "SELECT question_lang_id, question_name, field_name, input_field_type, max_input,question_id,normal_group_id,repeated,dictionary_label FROM questions_language WHERE language_id='1' AND input_field_type!='note' AND question_name!='' AND survey_id='" . $surveyid . "'");

$new_text .= '/' . "\n";
//$variale_lbl = '' . "\n" . 'VARIABLE LABELS' . "\n";
$variale_lbl = '' . "\n" . 'VARIABLE LABELS' . "";
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

$tRows = mysqli_num_rows($getQuestions);
$r = 0;

$new_text .= $external_ques;
while ($question = mysqli_fetch_object($getQuestions)) {
	$r++;
	$question_name = str_replace('"','',$question->dictionary_label);
	$field_name = $question->field_name;
	$input_field_type = $question->input_field_type;
	$question_id = $question->question_id;
	$max_input = $question->max_input;

	$normal_group_id = $question->normal_group_id;
	$repeated = $question->repeated;

	$startposition = $startposition;
	$endposition = $startposition + $max_input;
	

	$alpha = '';
	if ($input_field_type == 'text') {
		$alpha = '(A)';
	}
	if ($input_field_type == 'date') {
		$alpha = '(A)';
	}
	if ($input_field_type == 'select_multiple') {
		$alpha = '(A)';
	}
	if ($repeated != '') {
		$repeatedQuestion['f'] = $field_name;
		$repeatedQuestion['q'] = $question_name;
		$repeatedQuestion['a'] = $alpha;
		$repeatedQuestion['intype'] = $input_field_type;
		$repeatedQuestions[] = $repeatedQuestion;
		if ($input_field_type == "select_one") {
			
			$getOptions1 = mysqli_query($conn, "SELECT option_id, option_sequence, option_name FROM options_language WHERE status='0' AND language_id='1' AND survey_id='" . $surveyid . "' AND question_id='" . $question_id . "' ");
			//$opt_txt.=' /'.strtoupper($field_name)."\n";

			while ($options1 = mysqli_fetch_object($getOptions1)) {
				$option_sequence = $options1->option_sequence;
				//$option_name = $options1->option_name;
				$option_name = str_replace('"','',$options1->option_name);
				
				$repeatedOption['oseq'] = $option_sequence;
				$repeatedOption['onm'] = $option_name;
				$repeatedOptions[] = $repeatedOption;
				//$opt_txt.='     '.$option_sequence.' "'.$option_name.'"'."\n";
			}
		}
	} else {
		$field_names[] = $question->field_name;
		$maxinputs[$question->field_name] = $question->max_input;

		if ($input_field_type == "select_one") {
			
			$getOptions = mysqli_query($conn, "SELECT option_id, option_sequence, option_name FROM options_language WHERE status='0' AND language_id='1' AND survey_id='" . $surveyid . "' AND question_id='" . $question_id . "' ");
			$opt_txt .= ' /' . strtoupper($field_name) . "\n";

			while ($options = mysqli_fetch_object($getOptions)) {
				$option_sequence = $options->option_sequence;
			//	$option_name = $options->option_name;
				$option_name = str_replace('’','',$options->option_name);
				$opt_txt .= '     ' . $option_sequence . ' "' . $option_name . '"' . "\n";
			}
		}
		
		$new_text .= strtoupper($field_name) . '    ' . $startposition . '-' . $endposition . '   ' . $alpha . "\n";
		 if($r!= 1){
			$variale_lbl .= ' /' . strtoupper($field_name) . '       "' . $question_name . '"' . "\n";
		}else{
			$variale_lbl .= ' ' . strtoupper($field_name) . '       "' . $question_name . '"' . "\n";
		} 
		//$variale_lbl .= ' /' . strtoupper($field_name) . '       "' . $question_name . '"' . "\n";
		$startposition = $endposition + 1;
	}
	//die();
	$maxinputs['uniqueid'] = 15;
	$maxinputs['startDateTime'] = 20;
	$maxinputs['endDateTime'] = 20;
	$maxinputs['device_id'] = 20;
	$maxinputs['GPS'] = 50;
	$maxinputs['UserName'] = 40;
	$maxinputs['SurveyStatus'] = 20;
	$maxinputs['TerminationReason'] = 50; 
	if (($repeated == '' && count($repeatedQuestions) > 0) || ($tRows == $r)) {
		for ($j = 1; $j <= $gdata; $j++) {
			foreach ($repeatedQuestions as $repeatedQuestion1) {
				$fieldName = $repeatedQuestion1['f'];
				$inputFieldType = $repeatedQuestion1['q'];
				$fname = $fieldName . '$' . $j;
				$field_names[] = $fname;
				$maxinputs[$fname] = $question->max_input;
				$alpha = $repeatedQuestion1['a'];
				$intype = $repeatedQuestion1['intype'];

				if ($intype == "select_one") {
					$opt_txt .= ' /' . strtoupper($fname) . "\n";
					foreach ($repeatedOptions as $repeatedOption) {
						$oseq = $repeatedOption['oseq'];
						$onm = $repeatedOption['onm'];
						$opt_txt .= '     ' . $oseq . ' "' . $onm . '"' . "\n";
					}
				}


				$startposition = $startposition;
				$endposition = $startposition + $max_input;
				//$new_textdct.='    '.$inputFieldType.'    '.$fname.'    1:   '.$startposition.'-'.$endposition.'  '."\n";
				$new_text .= strtoupper($fname) . '    ' . $startposition . '-' . $endposition . '   ' . $alpha . "\n";
				if($j != 1){
					$variale_lbl .= ' /' . strtoupper($fname) . '       "' . $inputFieldType . '"' . "\n";
				}else{
					$variale_lbl .= ' ' . strtoupper($fname) . '       "' . $inputFieldType . '"' . "\n";
				}
				//$variale_lbl .= ' /' . strtoupper($fname) . '       "' . $inputFieldType . '"' . "\n";
				$startposition = $endposition + 1;
			}
		}
		$repeatedQuestions;
	}
	//$new_text.=strtoupper($field_name).'    '.$startposition.'-'.$endposition.'   '.$alpha."\n";
	//$variale_lbl.=' /'.strtoupper($field_name).'       "'.$question_name.'"'."\n";
	//$startposition=$endposition+1;
	
	
}
//echo $new_text;
$new_text=substr($new_text,0,-1);
$new_text.=".\n";

$variale_lbl=substr($variale_lbl,0,-1);
$variale_lbl.=".";

$opt_txt=substr($opt_txt,0,-1);
$opt_txt.=".";

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

$getQuestions = mysqli_query($conn, "SELECT question_lang_id, question_name,encrpt,dictionary_label, field_name, input_field_type, max_input FROM questions_language WHERE language_id='1'  AND input_field_type!='note'  AND question_name!='' AND survey_id='" . $surveyid . "'");

while ($question = mysqli_fetch_object($getQuestions)) {
	$question->question_name;
	$question_name = str_replace('"','',$question->dictionary_label);
	$field_names[] = $question->field_name;
	$input_field_type = $question->input_field_type;
	$field_input_types[$question->field_name] = $question->input_field_type;
	$maxinputs[$question->field_name] = $question->max_input;
	$field_name = $question->field_name;
	$msci_reports[$question->field_name] = $question->encrpt;
	$startposition = $startposition;
	$endposition = $startposition + $max_input;
	$startposition = $endposition + 1;
}

$getMondatas = mysqli_query($conn, "SELECT survey_data_monitoring_id, survey_id, survey_data_json, full_json,survey_status,termination_reason,users.name as full_name FROM survey_data_monitoring left join users on users.user_id=survey_data_monitoring.user_id WHERE survey_name_id='" . $surveyid . "' and survey_status!='7'");
while ($mdata = mysqli_fetch_object($getMondatas)) {

	$FullData = $mcrypt->decrypt($mdata->full_json);

	$full_json = json_decode($FullData);
	$survey_data = $full_json->survey_data;
	$groupNameData = $full_json->$group_name;
	$survey_id=$mdata->survey_id;
	$sequence_unique_id=$full_json->sequence_unique_id;
	$uniqueID=($sequence_unique_id!='') ? $sequence_unique_id : $survey_id;
	$k = 0;
	foreach ($groupNameData as $groupNameDatag) {
		//print_r($groupNameDatag);
		$gdata = [];
		$k++;
		foreach ($groupNameDatag as $groupNameDatag1) {
			$seq = '$' . $k;
			$gdata["field_name"] = $groupNameDatag1->field_name . $seq;
			$gdata["option_id"] = $groupNameDatag1->option_id;
			$option_value=str_replace(array("-/", "/"), array("-", "-"),$groupNameDatag1->option_value);
			$gdata["option_value"] = $option_value;
			$survey_data[] = (object)$gdata;
		}
	}
	$jsn_data['uniqueid'] = $uniqueID;
	$jsn_data['startDateTime'] = dateformate($full_json->start_date_time);
	$jsn_data['endDateTime'] = dateformate($full_json->end_date_time);
	$jsn_data['device_id'] = $full_json->device_id;
	//$jsn_data['GPS'] = substr($full_json->GPS_latitude_mid, 0, 10) . ',' . substr($full_json->GPS_longitude_mid, 0, 10);
	
	if(empty($full_json->GPS_latitude_mid)){
		$jsn_data['GPS'] = $full_json->GPS_latitude_start.','.$full_json->GPS_longitude_start;
	}else{
		$jsn_data['GPS'] = $full_json->GPS_latitude_mid.','.$full_json->GPS_longitude_mid;
	}
	$jsn_data['UserName'] = $mdata->full_name;
	$jsn_data['SurveyStatus'] = getSurveyStatus($mdata->survey_status);
	$jsn_data['TerminationReason'] = $mdata->termination_reason; 

	$opt_value = '';
	foreach ($survey_data as $surveydata1) {
		
		$opt_value = $surveydata1->option_value;
		$opt_value=str_replace(array("-/", "/"), array("-", "-"),$opt_value);
		if ($surveydata1->option_id != "") {
			$opt_values = str_replace(",", "", $surveydata1->option_id);
			$opt_value = str_replace("’", "", $opt_values);
		}

		if (strtolower($msci_reports[$surveydata1->field_name]) == "yes" && ($_SESSION['functional_role_id'] == 7 || $identification_type == 'deidentify')) {
			$optvalue = substr(str_pad($opt_value, 3, '*'), 0, 3);
			$opt_value =str_repeat("*", strlen($optvalue));
			//$opt_value = str_repeat("X", strlen($opt_value));
		}
		/* if (in_array($surveydata1->field_name, $field_names)) {
			if($field_input_types[$surveydata1->field_name]==='date'){
				$opt_value = dateformatetype($opt_value); //function 
			}
			$jsn_data[$surveydata1->field_name] = $opt_value;
		} */
		$jsn_data[$surveydata1->field_name] = $opt_value;
	
	}

	$jsn_data_arr[] = $jsn_data;
	$jsn_data = array();
}


foreach ($jsn_data_arr as $jsn_data_arr_val1) {
	foreach ($jsn_data_arr_val1 as $k => $jsn_data_arr_val) {
		$sdata = $jsn_data_arr_val; //$jsonArrs[$i]->$field_name;
		$maxinput = $maxinputs[$k];
		$spc = $maxinput - strlen($sdata);
		$getspc = space($spc);
		$new_text_dat .= $sdata . $getspc;

	}
	$new_text_dat .= "\n";
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