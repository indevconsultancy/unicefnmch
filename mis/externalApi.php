<?php 
    header('Content-Type: application/json; charset=utf-8');
    header("Access-Control-Allow-Origin: *");
    //header("Access-Control-Allow-Methods: PUT, GET, POST, REQUEST,HEADER ");
	//$_REQUEST = json_decode(file_get_contents('php://input'), true);
	//print_r($_REQUEST);
	include('includes/config.php');
	include('includes/functions.php');
	// error_reporting(E_ALL);
	// ini_set('display_errors', 1);
	//include_once('mycrypt.php');
	include_once('api/mycrypt.php');
	
	if($_SESSION['enckey'] === 0){
		
		die("You are not authorized to see the survey");
	}
	
    $data = exportData($conn);
	echo json_encode($data);
    
	
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

	function findQuestion($array, $search){
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
		$opt_name = isset($array[$result[0]]['question_name']) ? $array[$result[0]]['question_name'] : "" ;
	}
	
	return $opt_name;
	
}
	function exportData($conn)
	{ 
	    $encSurveyId='';
		$type='';
		$language='';
		$language_id=0;
		$tokendata ='';
		$label='';
	    if(isset($_GET['s']) && $_GET['s']!='')
		{
		 $encSurveyId=$_GET['s'];
		}
		
		$headers = getallheaders();
		if(isset($headers["Authorization"]) && $headers["Authorization"]!='')
		{
		$tokendata = $headers["Authorization"] ?? "";
		}
        
		if(isset($_GET['lang']) && $_GET['lang']!='')
		{
		 $language=$_GET['lang'];
		 $language_id=getonefield($conn,'languages','language_id','language_code',$language);
		}
		if(isset($_GET['type']) && $_GET['type']!='')
		{
			$type=$_GET['type'];
		}
		if(isset($_GET['label']) && $_GET['label']!='')
		{
			$label=$_GET['label'];
		}
		
		
		//$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		    
		//$encSurveyId = str_replace("https://mquad.org/mis/externalApi", "", $actual_link);
        
		$surveyId = decrypt_url($encSurveyId);
        $getclient = mysqli_query($conn,"SELECT client_id FROM survey WHERE id='".$surveyId."'  ");
		$dataclientID=mysqli_fetch_object($getclient);
		get_enc_key($dataclientID->client_id);
		$mcrypt = new EncryptionUtils($_SESSION['enckey']);
		//$arrResults=array("success"=>500,"message"=>$type,"survey_id"=>$encSurveyId);
		//$username = $_SERVER['PHP_AUTH_USER'];
		//$password = $_SERVER['PHP_AUTH_PW'];
	    //$username= "user"; // isset($_REQUEST['username'])? $_REQUEST['username']:'';
        //$password= "password"; // isset($_REQUEST['password'])? $_REQUEST['password']:'';
		//$username=isset($_REQUEST['username'])? $_REQUEST['username']:'';
        //$password=isset($_REQUEST['password'])? $_REQUEST['password']:'';
		
	    if($tokendata!='' || $type!='')
	    { 
            $getOptions = mysqli_query($conn, "SELECT options_language_id, option_id, question_id, language_id, option_name, option_sequence FROM options_language WHERE survey_id='".$surveyId."' ");
			$allOptions = mysqli_fetch_all($getOptions, MYSQLI_ASSOC);
			
			$getQuestions = mysqli_query($conn, "SELECT question_lang_id, question_id, language_id, question_name FROM questions_language WHERE survey_id='".$surveyId."' ");
			$allQuestions = mysqli_fetch_all($getQuestions, MYSQLI_ASSOC);
			
            $getGroups = mysqli_query($conn,"SELECT id, group_name FROM `questions_group` WHERE survey_id='".$surveyId."' AND group_type='group' ORDER BY id ASC ");
            $allGroups = mysqli_fetch_all($getGroups, MYSQLI_ASSOC);
			
            $getAllDatas = mysqli_query($conn,"SELECT survey_data_monitoring_id, survey_id, full_json, survey_name, survey_name_id FROM survey_data_monitoring WHERE survey_name_id='".$surveyId."' order by survey_data_monitoring_id desc limit 0,2 ");
            $exportData = [];
            $exportGroups = [];
            $exportdd=[];
			
            while ($allData = mysqli_fetch_assoc($getAllDatas)) {
			// Decrypting full_json
			$full_jsons = $mcrypt->decrypt($allData['full_json']);
			// Decoding JSON
			$full_json = json_decode($full_jsons);
			unset($full_json->hint_record);
			unset($full_json->error_record);

			$survey_id = $full_json->survey_id;
			
			$survey_data = $full_json->survey_data;

			$exportData = [];
			$exportData['survey_id'] = $survey_id;
			$exportData['type']=$type;
			foreach ($survey_data as $sdataKey => $surveydata) {
				if($label!='')
				{
							$searchQqy = array("question_id"=>$question_id,"language_id"=>$language_id);
							//print_r($search);
							$questdata = findQuestion($allquestions, $searchQqy); //getone_multi('options', 'option_name', 'question_id', $question_id, 'option_sequence', $key, $conn);
							$questdata =str_replace("'","-",$questdata);
							$questdata =str_replace("\\","-",$questdata);
							$quest_data = $questdata;
                $field_name = $quest_data;
				}
				$field_name = $surveydata->field_name;
				$option_id = $surveydata->option_id;
				$question_id = $surveydata->question_id;
				$option_value = $surveydata->option_value;
				if($language_id==0)
				{
					if ($option_id != "") {
						$option_value = $option_id;
					}
					$exportData[$field_name] = $option_value;
				}
				else {
					
					$soption_id = $surveydata->option_id;
					$soption_value = $v->option_value;
					
					/////////// Option Convert ////////////////
					
					if ($soption_value != '') {
						$option_data = $soption_value;
					} 
				
					/////////// Option Convert ////////////////
					$option_name = '';
					if ($soption_value != '') {
						$option_data = $soption_value;
					} else {
						if (strpos($soption_id, ',') == false) { 
							$search = array("question_id"=>$question_id,"option_sequence"=>$soption_id, "language_id"=>$language_id);
							//print_r($search);
							$optdata = findData($allOptions, $search); //getone_multi('options', 'option_name', 'question_id', $question_id, 'option_sequence', $key, $conn);
							$optdata =str_replace("'","-",$optdata);
							$optdata =str_replace("\\","-",$optdata);
							$option_data = $optdata;
						} else {
							$option_datamulti = '';
							$optval = explode(',', $soption_id);
							foreach ($optval as $key) {
								
								$search = array("question_id"=>$question_id,"option_sequence"=>$key, "language_id"=>$language_id);
								$optdata1 = findData($allOptions, $search); //getone_multi('options', 'option_name', 'question_id', $question_id, 'option_sequence', $key, $conn);
								$optdata1 =str_replace("'","-",$optdata1);
								$optdata1 =str_replace("\\","-",$optdata1);
								$option_datamulti .= $optdata1 . ", ";
							}
							$option_data = substr(trim($option_datamulti), 0, -1);
						}
					}
				$exportData[$field_name] = $option_data;
				}
				
				}
			// Processing groups
			foreach ($allGroups as $allGroup) {
				$group_name = $allGroup['group_name'];
				$groupArr = $full_json->$group_name ?? [];
                $i=1;
				foreach ($groupArr as $groupDatas) {
					 $group_data['survey_id']=$survey_id;
					 $group_data['sequence']=$i;
					foreach ($groupDatas as $groupData) {
						$groupFieldName = $groupData->field_name;
						$groupquestion_id=$groupData->question_id;
						$groupOptionId = $groupData->option_id;
						$groupOptionValue = $groupData->option_value;
						
							if($language_id==0)
							{
								if ($groupOptionId != "") {
									$groupOptionValue = $groupOptionId;
								}
								$group_data[$groupFieldName] = $groupOptionValue;
							}
							else {
									$goption_id = $groupData->option_id;
									$goption_value = $groupData->option_value;
									
									/////////// Option Convert ////////////////
									$goption_name = '';
									if ($goption_value != '') {
										$goption_data = $goption_value;
									} 
									else {
										if (strpos($goption_id, ',') == false) {
											$gsearch = array("question_id"=>$groupquestion_id,"option_sequence"=>$goption_id, "language_id"=>$language_id);
											
											$goptdata = findData($allOptions, $gsearch); //getone_multi('options', 'option_name', 'question_id', $question_id, 'option_sequence', $key, $conn);
											$goptdata =str_replace("'","-",$goptdata);
											$goptdata =str_replace("\\","-",$goptdata);
											$goption_data = $goptdata;
										} else {
											$goption_datamulti = '';
											$goptval = explode(',', $goption_id);
											foreach ($goptval as $key) {
												
												$gsearch = array("question_id"=>$groupquestion_id,"option_sequence"=>$key, "language_id"=>$language_id);
												$goptdata1 = findData($gallOptions, $gsearch); //getone_multi('options', 'option_name', 'question_id', $question_id, 'option_sequence', $key, $conn);
												$goptdata1 =str_replace("'","-",$goptdata1);
												$goptdata1 =str_replace("\\","-",$goptdata1);
												$goption_datamulti .= $goptdata1 . ", ";
											}
											$goption_data = substr(trim($goption_datamulti), 0, -1);
										}
									}
								 $group_data[$groupFieldName] = $goption_data;
								}
						
					}
					$gArr[] = $group_data;
					$group_data = [];
					$i++;
					
					$output[$group_name]=$gArr;
					
				}
				$gArr = [];
				//$exportData[$group_name] = $gArr;
			
			}
			//$exportdd[] = $exportData;
			$output['data'][]=$exportData;
		}

            $final_data = $output;
            // $exportData['data'] = $final_data;
			//$arrResults=array("data"=>$final_data); 
			$arrResults=$final_data; 
		
		}
	    else
	    {
	        $arrResults=array("success"=>500,"message"=>"Username and password are required."); 
	    }
		
	    mysqli_close();
	  	return $arrResults;
	}
?>