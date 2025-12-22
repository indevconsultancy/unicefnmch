<?php 
    header('Content-Type: application/json; charset=utf-8');
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: PUT, GET, POST");
	
	include('includes/config.php');
	
	// error_reporting(E_ALL);
	// ini_set('display_errors', 1);
	//include_once('mycrypt.php');
	include_once('api/mycrypt.php');
	
	if($_SESSION['enckey'] === 0){
		die("You are not authorized to see the survey");
	}
		
    $data = exportData($conn);
	echo json_encode($data);

	function exportData($conn)
	{  $mcrypt = new EncryptionUtils($_SESSION['enckey']);
		$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		    
		$encSurveyId = str_replace("https://mquad.org/mis/externalApi", "", $actual_link);
        
		$surveyId = decrypt_url($encSurveyId);
		
	    $username= "user"; // isset($_REQUEST['username'])? $_REQUEST['username']:'';
        $password= "password"; // isset($_REQUEST['password'])? $_REQUEST['password']:'';
	    if($username!='' && $password!='')
	    {  
			
            $getGroups = mysqli_query($conn,"SELECT id, group_name FROM `questions_group` WHERE survey_id='".$surveyId."' AND group_type='group' ORDER BY id ASC ");
            $allGroups = mysqli_fetch_all($getGroups, MYSQLI_ASSOC);
            $getAllDatas = mysqli_query($conn,"SELECT survey_data_monitoring_id, survey_id, full_json, survey_name, survey_name_id FROM survey_data_monitoring WHERE survey_name_id='".$surveyId."'  ");
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
			foreach ($survey_data as $sdataKey => $surveydata) {
				$field_name = $surveydata->field_name;
				$option_id = $surveydata->option_id;
				$option_value = $surveydata->option_value;
				if ($option_id != "") {
					$option_value = $option_id;
				}
				$exportData[$field_name] = $option_value;
			}

			// Processing groups
			foreach ($allGroups as $allGroup) {
				$group_name = $allGroup['group_name'];
				$groupArr = $full_json->$group_name ?? [];

				foreach ($groupArr as $groupDatas) {
					foreach ($groupDatas as $groupData) {
						$groupFieldName = $groupData->field_name;
						$groupOptionId = $groupData->option_id;
						$groupOptionValue = $groupData->option_value;
						if ($groupOptionId != "") {
							$groupOptionValue = $groupOptionId;
						}
						$group_data[$groupFieldName] = $groupOptionValue;
					}
					$gArr[] = $group_data;
					$group_data = [];
				}
				$exportData[$group_name] = $gArr;
				$gArr = [];
			}
			$exportdd[] = $exportData;
		}

            $final_data = $exportdd;
            // $exportData['data'] = $final_data;
			$arrResults=array("data"=>$final_data); 
	    }
	    else
	    {
	        $arrResults=array("success"=>500,"message"=>"Username and password are required."); 
	    }
	    mysqli_close();
	  	return $arrResults;
	}
?>