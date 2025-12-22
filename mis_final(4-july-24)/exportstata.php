<?php include_once('includes/config.php'); ?>
<?php
$surveyid = $_REQUEST['survey_id'];
$unique_id = getone($conn,"survey","unique_id","id",$surveyid);

ini_set('memory_limit','-1');
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
function getSurveyStatus($survey_status) {
    $surveystatus = [
        1 => 'Submitted',
        3 => 'Terminate',
        4 => 'Send for review',
        5 => 'Approved',
        6 => 'Re-submitted',
    ];

    return isset($surveystatus[$survey_status]) ? $surveystatus[$survey_status] : 'NA';
}
?>
<?php 
	/* date_default_timezone_set("Asia/Kolkata");
	echo "time-"; 
	echo date("Y-m-d H:i:s");
	echo "<br>"; */
	//CREATE DCT FILE 
	//echo "<pre>";
	$new_file_namedct = "stata/Exported.dct";
	$mynewfile_dct = fopen($new_file_namedct, "w");
	$text = "This contant write in the new file....";

	$new_textdct='infix dictionary using "Exported.dat" {
	1 lines'."\n";

	//$startposition = 1;
	$startposition = 182;
	$endposition = 1;
	
	$external_ques='     str    uniqueid    1:   1-15'."\n";
	$external_ques.='    str    startDateTime    1:   16-36'."\n";
	$external_ques.='    str    endDateTime    1:   37-57'."\n";
	$external_ques.='    str    device_id    1:   58-78'."\n";
	$external_ques.='    str    GPS    1:   79-109'."\n";
	$external_ques.='    str    UserName    1:   110-140'."\n";
	$external_ques.='    str    SurveyStatus    1:   141-161'."\n";
	$external_ques.='    str    TerminationReason    1:   161-181'."\n";
	
	$getGroups = mysqli_query($conn,"SELECT id, group_name FROM questions_group WHERE survey_id='".$surveyid."' AND group_type='group' ");
	$getGroup = mysqli_fetch_object($getGroups);
	$group_name = $getGroup->group_name;
	
	$getGroupDatas = mysqli_query($conn,"select MAX(JSON_LENGTH(JSON_EXTRACT(full_json, '$.".$group_name."'))) as groupData from survey_data_monitoring where survey_name_id='".$surveyid."' and survey_status!='7'");
	$group_data = mysqli_fetch_object($getGroupDatas);
	$gdata = $group_data->groupData;
	
	
	$repeatedQuestions = array();
	$getQuestions1 = mysqli_query($conn,"SELECT question_lang_id, question_name,dictionary_label, field_name,question_id, input_field_type, max_input, normal_group_id,repeat_count,repeated,default_response FROM questions_language WHERE language_id='1'  AND input_field_type!='note'  AND question_name!='' AND survey_id='".$surveyid."'");
	$tRows = mysqli_num_rows($getQuestions1);
	$r=0;
	$new_textdct.=$external_ques;
	// while($question = mysqli_fetch_object($getQuestions1)){
		$getQuestions1_ss = mysqli_fetch_all($getQuestions1,MYSQLI_ASSOC);
		//print_r($getQuestions1_ss);
		foreach($getQuestions1_ss as $question){
			$question = (object)$question;
		$r++;
		$question_name = $question->dictionary_label;
		$field_name = $question->field_name;
		$input_field_type = $question->input_field_type;
		$max_input = $question->max_input;
		$normal_group_id = $question->normal_group_id;
		$repeated = $question->repeated;
		$repeat_count = $question->repeat_count;
		$default_response = $question->default_response;
		
		// $field_names[] = $question->field_name;
		// $maxinputs[$question->field_name] = $question->max_input;
		
		$startposition = $startposition;
		$endposition = $startposition+$max_input;
		
		if($input_field_type=="select_one"  && $default_response!=""){
			$input_field_type="str";
		}
		
		if($input_field_type=="text"){
			$input_field_type="str";
		}else if($input_field_type=="date" || $input_field_type=="time"){
			$input_field_type="str";
		}else if($input_field_type=="select_one"){
			$input_field_type="long";
		}else if($input_field_type=="select_multiple"){
			$input_field_type="str";
		}else if($input_field_type=="number"){
			$input_field_type="long";
			if($max_input>8){ $input_field_type="str"; }
		}

		if($max_input<=1){
			$input_field_type="byte";
		}
		
		if($repeated!='' && $repeat_count==''){
			$repeatedQuestion['f'] = $field_name;
			$repeatedQuestion['t'] = $input_field_type;
			$repeatedQuestion['l'] = $max_input;
			$repeatedQuestions[] = $repeatedQuestion; 
		}
		else{
			$field_names[] = $question->field_name;
			$maxinputs[$question->field_name] = $question->max_input;
			$new_textdct.='    '.$input_field_type.'    '.$field_name.'    1:   '.$startposition.'-'.$endposition.'  '."\n";
			$startposition=$endposition+1;
		}
        
		$maxinputs['uniqueid'] = 15;
		$maxinputs['startDateTime'] = 20;
		$maxinputs['endDateTime'] = 20;
		$maxinputs['device_id'] = 20;
		$maxinputs['GPS'] = 30;
		$maxinputs['UserName'] = 30;
		$maxinputs['SurveyStatus'] = 20;
		$maxinputs['TerminationReason'] = 20;
		
		
		if((($repeated=='') && $repeat_count=='' && (count($repeatedQuestions)>0)) || ($tRows==$r) ){
			
			for($j=1; $j<=$gdata; $j++ ){
				foreach($repeatedQuestions as $repeatedQuestion1){
					$fieldName=$repeatedQuestion1['f'];
					$inputFieldType= $repeatedQuestion1['t'];
					$mlimit= $repeatedQuestion1['l'];
					$fname = $fieldName.'_'.$j;
					$field_names[] = $fname;
					$maxinputs[$fname] = $mlimit;
			
					$startposition = $startposition;
					$endposition = $startposition+$mlimit;
					$new_textdct.='    '.$inputFieldType.'    '.$fname.'    1:   '.$startposition.'-'.$endposition.'  '."\n";
					$startposition=$endposition+1;
				}
			}
			$repeatedQuestions;
		}
	}
	//echo "<pre>";
	$new_textdct.="\n".'}';
	//die;
	fwrite($mynewfile_dct, $new_textdct);
	fclose($mynewfile_dct);
?>


<?php
	
	// CREATE DO FILE
	//echo "<pre>";
	 $new_file_namedo = "stata/Exported.do";
	 $mynewfile_do = fopen($new_file_namedo, "w");
	//$text = "This contant write in the new file....";

	 $new_text_do='infix using "Exported.dct"'."\n\n";
	 
	 $new_text_do.='label variable uniqueid   "Unique Id"'."\n";
	 $new_text_do.='label variable startDateTime   "Start DateTime"'."\n";
	 $new_text_do.='label variable endDateTime   "End DateTime"'."\n";
	 $new_text_do.='label variable device_id   "Device Id"'."\n";
	 $new_text_do.='label variable GPS   "GPS"'."\n";
	 $new_text_do.='label variable UserName   "UserName"'."\n";
	 $new_text_do.='label variable SurveyStatus   "SurveyStatus"'."\n";
	 $new_text_do.='label variable TerminationReason   "TerminationReason"'."\n";
	 
	
	// $getGroups = mysqli_query($conn,"SELECT id, group_name FROM questions_group WHERE survey_id='".$surveyid."' AND group_type='group' ");
	// $getGroup = mysqli_fetch_object($getGroups);
	// $group_name = $getGroup->group_name;
	
	// $getGroupDatas = mysqli_query($conn,"select MAX(JSON_LENGTH(JSON_EXTRACT(full_json, '$.".$group_name."'))) as groupData from survey_data_monitoring where survey_name_id='".$surveyid."' and survey_status!=7 ");
	// $group_data = mysqli_fetch_object($getGroupDatas);
	// $gdata = $group_data->groupData;
	
	//$getQuestions = mysqli_query($conn,"SELECT question_lang_id, question_name,dictionary_label, field_name, input_field_type, max_input,question_id,normal_group_id,repeated FROM questions_language WHERE language_id='1' AND input_field_type!='note' AND question_name!='' AND survey_id='".$surveyid."'");

	$txt_mapping.='#delimit cr'."\n"; 
	//$tRows1 = mysqli_num_rows($getQuestions);
	$tRows1 = $tRows;
	$r1=0;
	
	//while($question = mysqli_fetch_object($getQuestions)){
		foreach($getQuestions1_ss as $questiondo){
			$question = (object)$questiondo;
		$r1++;
		$question_name = str_replace('"','',$question->dictionary_label);
		$field_name = $question->field_name;
		$input_field_type = $question->input_field_type;
		$question_id = $question->question_id;
		$normal_group_id = $question->normal_group_id;
		$repeated = $question->repeated;
		$repeat_count = $question->repeat_count;
		$default_response = $question->default_response;
		
		
		//$repeatedOptionsDo2=array();
		if($repeated!='' && $repeat_count=='' ){
			$repeatedQuestionDo['f'] = $field_name;
			$repeatedQuestionDo['q'] = $question_name;
			$repeatedQuestionDo['intype'] = 'text'; //$input_field_type;
			
			if($input_field_type=="select_one" && $default_response=='' ){
				$repeatedQuestionDo['intype'] = $input_field_type;
				$getOptions = mysqli_query($conn,"SELECT option_id, option_sequence, option_name FROM options_language WHERE status='0' AND language_id='1' AND survey_id='".$surveyid."' AND question_id='".$question_id."' ");
				
				while($options = mysqli_fetch_object($getOptions)){
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
		}else{
			if($input_field_type=="select_one"){
				
				$getOptions = mysqli_query($conn,"SELECT option_id, option_sequence, option_name FROM options_language WHERE status='0' AND language_id='1' AND survey_id='".$surveyid."' AND question_id='".$question_id."' ");
				$opt_txt.='label define '.strtoupper($field_name)."\n";
				
				while($options = mysqli_fetch_object($getOptions)){
					$option_sequence = $options->option_sequence;
					$option_name = trim($options->option_name);
					
					$opt_txt.='     '.$option_sequence.' "'.$option_name.'"'."\n";
				}
				$opt_txt.=';'."\n";

				$txt_mapping.='label values '.$field_name.'     '.strtoupper($field_name)."\n"; 
			}
			
			$new_text_do.='label variable '.$field_name.'   "'.$question_name.'"'."\n";
		}
		//echo "<pre>";
		//print_r($repeatedOptionsDo2);
		
		if((($repeated=='')  && $repeat_count=='' && (count($repeatedQuestionsDo)>0)) || ($tRows1==$r1) ){
			for($j1=1; $j1<=$gdata; $j1++ ){
				foreach($repeatedQuestionsDo as $repeatedQuestionDo1){
					$fieldName=$repeatedQuestionDo1['f'];
					$inputFieldType= $repeatedQuestionDo1['q'];
					$fname = $fieldName.'_'.$j1;
					$intype = $repeatedQuestionDo1['intype'];
					
					if($intype=="select_one"){
						$opt_txt.='label define '.strtoupper($fname)."\n";
						foreach($repeatedOptionsDo2[$fieldName] as $k=>$repeatedOptionDo){
							$oseq = $repeatedOptionDo['oseq'];
							$onm = $repeatedOptionDo['onm'];
							$opt_txt.='     '.$oseq.' "'.$onm.'"'."\n";
						}
						$opt_txt.=';'."\n";
						$txt_mapping.='label values '.$fname.'     '.strtoupper($fname)."\n"; 
					}
					$new_text_do.='label variable '.$fname.'   "'.$inputFieldType.'"'."\n";
				}
			}
		
		}
		
		
	}
	//echo "<pre>";
	$new_text_do;
	// SET OPTIONS
	$new_text_do.="\n";
	$new_text_do.='#delimit ;'."\n";
	$new_text_do.=$opt_txt."\n".$txt_mapping;
	//die;
	fwrite($mynewfile_do, $new_text_do);
	fclose($mynewfile_do);
?>


<?php
	
	// CREATE DAT FILE
	//echo "<pre>";
	$new_file_namedat = "stata/Exported.dat";
	$mynewfile_dat = fopen($new_file_namedat, "w");
	$text = "This contant write in the new file....";

	$get_expt_fields = mysqli_query($conn,"SELECT id,field_lable,msci_report FROM report_format WHERE survey_id='".$surveyid."' AND group_id='0' AND title!='' ORDER BY seq_no ASC");
    while($expt_fields = mysqli_fetch_object($get_expt_fields)){
        $msci_reports[$expt_fields->field_lable] = $expt_fields->msci_report;
    }

	$getMondatas = mysqli_query($conn,"SELECT survey_data_monitoring_id, survey_id, survey_data_json, full_json,survey_status,termination_reason,users.name as full_name FROM survey_data_monitoring left join users on users.user_id=survey_data_monitoring.user_id WHERE survey_name_id='".$surveyid."' and survey_status!='7'");
	while($mdata = mysqli_fetch_object($getMondatas)){
		// $survey_data_json = $mdata->survey_data_json;
		// $jsonArr = json_decode($survey_data_json);
		// $jsonArrs[]=$jsonArr;
		
		$mdata->full_json;
		$full_json = json_decode($mdata->full_json);
		$survey_data = $full_json->survey_data;
		$groupNameData = $full_json->$group_name;
		$k=0;
		if(count($groupNameData)>0){
			foreach($groupNameData as $groupNameDatag){
				
				$gdata=[];
				$k++;
				foreach($groupNameDatag as $groupNameDatag1){
					$seq="_".$k;
					//echo $groupNameDatag1->field_name.$seq;
					$gdata["field_name"] = $groupNameDatag1->field_name.$seq;
					$gdata["option_id"] = $groupNameDatag1->option_id;
					$gdata["option_value"] = $groupNameDatag1->option_value;
					$survey_data[] = (object)$gdata;
				}
				
			}
		} 
		
		$jsn_data['uniqueid'] = $mdata->survey_id;
		$jsn_data['startDateTime'] = $full_json->start_date_time;
		$jsn_data['endDateTime'] = $full_json->end_date_time;
		$jsn_data['device_id'] = $full_json->device_id;
		$jsn_data['GPS'] = substr($full_json->GPS_latitude_mid,0,10).','.substr($full_json->GPS_longitude_mid,0,10);
		$jsn_data['UserName'] = $mdata->full_name; 
		$jsn_data['SurveyStatus']=getSurveyStatus($mdata->survey_status);
		$jsn_data['TerminationReason']=$mdata->termination_reason;
		$opt_value='';
		foreach($survey_data as $surveydata1){
			$opt_value = $surveydata1->option_value;
			
			if($surveydata1->option_id!="" && $surveydata1->option_id!="0"){
				$opt_value = str_replace(",","",$surveydata1->option_id);
			}
			if(strtolower($msci_reports[$surveydata1->field_name])=="yes" && $_SESSION['functional_role_id']==7){
				$opt_value = str_repeat("X", strlen($opt_value));
			}
			if(in_array($surveydata1->field_name,$field_names)){
				$jsn_data[$surveydata1->field_name] = $opt_value;
			}
			
 		}
		$jsn_data_arr[] = $jsn_data;
		$jsn_data = array();
		
	}
	 
	
	//print_r($maxinputs);
	 //print_r($field_names);
	
	//$jsonArrs = $jsn_data_arr;
	
	//$new_text.='';
	foreach($jsn_data_arr as $jsn_data_arr_val1){
		foreach($jsn_data_arr_val1 as $k=>$jsn_data_arr_val){
			$sdata = $jsn_data_arr_val;//$jsonArrs[$i]->$field_name;
			$maxinput = $maxinputs[$k];
			$spc = $maxinput-strlen($sdata);
			$getspc = space($spc);
			$new_text.=$sdata.$getspc;
			//echo "<br>";
		}
		//echo "<br>";
		$new_text.="\n";
	}
	
	
	function space($num){
		$svar=' ';
		if($num>0){
			for($s=0;$s<$num; $s++){
				$svar.=' ';
			}
		}
		return $svar;
	}
	// echo "<pre>";
	// echo $new_text;
	// echo "sss-".date("Y-m-d H:i:s"); 
	
	fwrite($mynewfile_dat, $new_text);
	fclose($mynewfile_dat);
?>

<?php
// Create zip
function createZip($zip,$dir){
    if (is_dir($dir)){

        if ($dh = opendir($dir)){
            while (($file = readdir($dh)) !== false){
                
                // If file
                if (is_file($dir.$file)) {
                    if($file != '' && $file != '.' && $file != '..'){
                        
                        $zip->addFile($dir.$file);
					  
                    }
                }else{
                    // If directory
                    if(is_dir($dir.$file) ){

                        if($file != '' && $file != '.' && $file != '..'){

                            // Add empty directory
                             $zip->addEmptyDir($dir.$file);

                             $folder = $dir.$file.'/';
                           
                            // Read data of the folder
                            createZip($zip,$folder);
                        }
                    }
                    
                }
                    
            }
            closedir($dh);
        }
    }
}
//////////Create folder////////
	$client_id=$_SESSION['client_id'];
	$clientId="C".$client_id;
	$location="./stata_zip/".$clientId."/";
				
		if (!file_exists($location)) {
			if (!mkdir($location, 0777, true)) {
				$error='Somthing Went wrong !!';
			}else{
				//echo "create";
				mkdir($location, 0777, true);
			}
		}
	///////////////////
// CREATE ZIP FILE
$zip = new ZipArchive();

  $filename = "./stata_zip/".$clientId."/stata-".$unique_id.".zip";

if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
	exit("cannot open <$filename>\n");
	
}
$dir = 'stata/';
// Create zip
createZip($zip,$dir);
$zip->close();

// Create zip

?>


<?php
//DOWNLOAD ZIP
 // $filename1 = "stata_zip/stata-".$unique_id.".zip";
  $filename1 = "./stata_zip/".$clientId."/stata-".$unique_id.".zip";
 $filename = "My Zip File Download.zip";
	
if (file_exists($filename1)) {
	  //$url="https://mquad.org/mis/".$filename1;
	  //echo "<script>window.location.href='$url';</script>";
	  
	  $res = array("status"=>200,"message"=>"Stata file created","file_name"=>$filename1);
	  echo json_encode($res);
}
?>