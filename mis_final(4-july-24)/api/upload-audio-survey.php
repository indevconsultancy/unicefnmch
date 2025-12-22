<?php 
	header('Access-Control-Allow-Origin: *');
	header('Content-Type: application/json; charset=utf-8');
	header("Access-Control-Allow-Methods: PUT, GET, POST");

	require_once "config.php";
	require_once "jwt.php";
	
	$headers = getallheaders();
	$tokendata = $headers["X-Authorization"] ?? "";
	
	if ($_SERVER["REQUEST_METHOD"] === "POST") {
		if ($tokendata) {
			// Extract the token from the Authorization header
			$token_array = explode(" ", $tokendata);
			if($token_array[0] == "Bearer"){
				
			
				$token = $token_array[1];
				
				// Verify and decode the JWT token
				$decodedToken = verifyToken($token, JWT_SECRET_KEY, $conn);
				
				if ($decodedToken) {
					$data = media_upload($conn);
					echo json_encode($data);
					exit;
				}
			} else {
				 $response = ["status"=>0, "message" => "Auth request is invalid"];
				echo json_encode($response);
				exit;
			}
		}
		
		http_response_code(401); // Unauthorized status code
		$response = ["status"=>0, "error" => "Invalid token"];
		echo json_encode($response);
		exit;
	} else {
		http_response_code(405); // Method Not Allowed
		$response = ["status"=>0, "message" => "The call method not allowed"];
		echo json_encode($response);
		exit;
	}
	


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
        $media_file=isset($_FILES['media_file'])? $_FILES['media_file']:'';
		
		//$uniqueid=$user_id."".$survey_name_id."_".$sequence_id;
		$uniqueid=$sequence_id;
		$date = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
 		$createdAt=$date->format('Y-m-d H:i:s');
		
		$filePath='';
		if($media_file!='' && $survey_name_id!='')
	    {
			$clientUniqueId="C".$client_id;
			$surveyUniqueId = $survey_name_id;
	     
			 $folder_path = "../media_survey/".$clientUniqueId."/".$surveyUniqueId."/" ;
			//array_map('unlink', glob("$folder_path/*.*"));
			if (!file_exists($folder_path)) {
				if (mkdir($folder_path, 0777, true)) {
					//echo "Folder created successfully!";
				} else {
					// $error = 'Failed to create folder!';
				}
			} else {
				//echo "Folder already exists!";
			}
				$file_name = $_FILES['media_file']['name'];
				$ext = pathinfo($file_name, PATHINFO_EXTENSION);
				$digits = 10;
				$unique_id = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);10;
				$date=$unique_id;//date("Ymd_his");
                $file_name=$uniqueid."_".$field_name.".".$ext;
				$temp_name = $_FILES['media_file']['tmp_name'];
				$location = $folder_path;
    		    $filePath= $location.$file_name;
				
    		     if(move_uploaded_file($temp_name , $filePath)) {
						$response = "success";
						$status=1;
						 $sqlUploadForm="insert into  survey_document set survey_id='".$survey_id."',survey_name_id='".$survey_name_id."',field_name='".$field_name."',client_id='".$client_id."',user_id='".$user_id."',survey_data_monitoring_id='".$survey_data_monitoring_id."',media_file='".$file_name."',created_at='".$createdAt."' ";
						mysqli_query($conn,$sqlUploadForm);
					}else{
						$response = "error";
					}
					$response="success";
				 if($status==1)
				{
				   $arrResults=array("success"=>"1","message"=>"Data successfuly submitted","name"=>$file_name, "file_status" =>$response); 
				}
				else
				{
					$arrResults=array("success"=>"0","message"=>"error", "file_status" =>$response); 
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