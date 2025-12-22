<?php 
	header('Access-Control-Allow-Origin: *');
	header('Content-Type: application/json; charset=utf-8');
	header("Access-Control-Allow-Methods: PUT, GET, POST");
	include('config.php');
	$_REQUEST = json_decode(file_get_contents('php://input'),true);
	$data = media_upload($conn);
	
	echo json_encode($data);
	exit;
	function media_upload($conn)
	{
		$arrResults = array();
	    $survey_id = isset($_REQUEST['survey_id']) ? $_REQUEST['survey_id'] : '';
		$sequence_id = isset($_REQUEST['sequence_id']) ? $_REQUEST['sequence_id'] : '';
	    $survey_name_id=isset($_REQUEST['survey_name_id'])? $_REQUEST['survey_name_id']:'';
		$client_id=isset($_REQUEST['client_id'])? $_REQUEST['client_id']:'';
        $user_id=isset($_REQUEST['user_id'])? $_REQUEST['user_id']:'';
		$survey_data_monitoring_id=isset($_REQUEST['survey_data_monitoring_id'])? $_REQUEST['survey_data_monitoring_id']:'';
	    $field_name=isset($_REQUEST['field_name'])? $_REQUEST['field_name']:'';
	    $picture=isset($_REQUEST['picture'])? $_REQUEST['picture']:'';
       // $uniqueid=$user_id."".$survey_name_id."_".$sequence_id;
		$uniqueid=$sequence_id;
		$date = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
 		$createdAt=$date->format('Y-m-d H:i:s');
		
		$filePath='';
		if($picture!='' && $survey_name_id!=''){
			$clientUniqueId="C".$client_id;
			$surveyUniqueId = $survey_name_id;
	     
			 $folder_path = "../media_survey/".$clientUniqueId."/".$surveyUniqueId."/" ;
			//array_map('unlink', glob("$folder_path/*.*"));
			if (!file_exists($folder_path)) {
				if (!mkdir($folder_path, 0777, true)) {
					$error='Something Went wrong!!';
				}else{
					mkdir($folder_path, 0777, true);
				}
			}
			
			$file_name=$uniqueid."_".$field_name.".png";
			$filePath= $folder_path.$file_name;
			
			$picture = str_replace('data:image/png;base64,', '', $picture );
			$picture = str_replace(' ', '+', $picture );
			$picture = base64_decode($picture);  
			$res = file_put_contents($filePath, $picture);
			// print_r($res);
			// die();
			if($res) {
				$response = "success";
				$sqlUploadForm="insert into  media_question_data set survey_id='".$survey_id."',survey_name_id='".$survey_name_id."',field_name='".$field_name."',client_id='".$client_id."',user_id='".$user_id."',survey_data_monitoring_id='".$survey_data_monitoring_id."',media_file='".$file_name."',created_at='".$createdAt."' ";
				mysqli_query($conn,$sqlUploadForm);
				$arrResults=array("success"=>"1","message"=>"Data successfuly submitted","name"=>$file_name); 
			}else{
				$arrResults=array("success"=>"0","message"=>"error"); 
			}
			
		}
	    else
	    {
	        $arrResults=array("success"=>"0","message"=>"file name is emplty"); 
	    }
		mysqli_close($conn);
		return $arrResults;
	}
?>