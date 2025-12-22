<?php 
include('../includes/config.php');
include('functions.php');
var_dump($_REQUEST);
if(isset($_REQUEST['username']) && isset($_REQUEST['password']))
    {
		
    $error='';
	if(isset($_SESSION['locked'])){
		 $difference=time()-$_SESSION['locked'];
		// Check if 15 minutes have passed
		if ($difference > 900) { // 15 minutes in seconds
			unset($_SESSION['locked']);
			unset($_SESSION['login_attempts']);
			$_SESSION['login_attempts']=0;
			
		}
	}
	
	if($_SESSION['login_attempts']<3)
	{
		$error='';
		
	
	
 //$results=array();
		// echo "select user_id,status from users where username='".$_REQUEST['username']."'";
		// die();
		$userssquery=mysqli_query($conn,"select user_id,status from users where username='".$_REQUEST['username']."'");
		$getssusersql=mysqli_fetch_array($userssquery);
		$status=$getssusersql['status'];
		$captcha=$_REQUEST['captcha'];
		// echo "test".$_SESSION['CODE'];
		// die();
		 //$captcha='123';
		if($_SESSION['CODE']==$captcha){    
		//if($captcha=='123'){  
		    //$status=0;
			 $result=login($_REQUEST,$conn);
			// print_r($result);
			if($status==0){
				if($result['status']==1)
				{ 
			      if($result['roles']==4)
				  {
					 $results = array("restatus" => 1, "roles" => 4);
				  }
				  else {
				   $results = array("restatus" => 1, "roles" => 1);
				  }
				}
				else{
					$_SESSION['login_attempts'] += 1;
                    $error = "Please enter correct Username and Password. You have made ".$_SESSION['login_attempts']." unsuccessful attempt(s) out of 3 allowed attempts.";
				  $results = array("restatus" => 0, "roles" => 0, "error"=> $error);
				}
			}
			
			else{
				$error="Your account is inactive. Please contact your admin!!";
				$results = array("status" => 0, "roles" => 0, "error"=> $error);
			}
		}
			else
			{
				$error="Please check the captcha and try again!!";
				$results = array("status" => 0, "roles" => 0, "error"=> $error);
			}
        }
		else
			{
				$error="You have made ".$_SESSION['login_attempts']." unsuccessful attempt(s) out of 3 allowed attempts. Access has been blocked, please try after 15 Min";
				$results = array("status" => 0, "roles" => 0, "error"=> $error);
			}
		
		//echo "hello";
		echo json_encode($results);
		
     } 
/*
function get_enc_key($cid){

$headers = array(
    'Content-Type: application/json',
    'X-HAMC: '. API_KEY,
);
$apiUrl = API_URL . '/api/getenckey/' . $cid;

$ch = curl_init($apiUrl);

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);


if (curl_errno($ch)) {
    echo 'cURL error: ' . curl_error($ch);
}

curl_close($ch);

if ($response) {
    $responseData = json_decode($response, true);
    $_SESSION['enckey'] = $responseData['enckey'];
}
return true;
}

function cryptoJsAesDecrypt($passphrase, $jsonString){
    $jsondata = json_decode($jsonString, true);
    $salt = hex2bin($jsondata["s"]);
    $ct = base64_decode($jsondata["ct"]);
    $iv  = hex2bin($jsondata["iv"]);
    $concatedPassphrase = $passphrase.$salt;
    $md5 = array();
    $md5[0] = md5($concatedPassphrase, true);
    $result = $md5[0];
    for ($i = 1; $i < 3; $i++) {
        $md5[$i] = md5($md5[$i - 1].$concatedPassphrase, true);
        $result .= $md5[$i];
    }
    $key = substr($result, 0, 32);
    $data = openssl_decrypt($ct, 'aes-256-cbc', $key, true, $iv);
    return json_decode($data, true);
}

function cryptoJsAesEncrypt($passphrase, $value){
    $salt = openssl_random_pseudo_bytes(8);
    $salted = '';
    $dx = '';
    while (strlen($salted) < 48) {
        $dx = md5($dx.$passphrase.$salt, true);
        $salted .= $dx;
    }
    $key = substr($salted, 0, 32);
    $iv  = substr($salted, 32,16);
    $encrypted_data = openssl_encrypt(json_encode($value), 'aes-256-cbc', $key, true, $iv);
    $data = array("ct" => base64_encode($encrypted_data), "iv" => bin2hex($iv), "s" => bin2hex($salt));
    return json_encode($data);
}

function clear_malicious_data($user_data,$conn){
    $sanitize_input=trim($user_data);

    $sanitize_input=strip_tags($sanitize_input);

    $sanitize_input=mysqli_real_escape_string($conn,$sanitize_input);

    return $sanitize_input;

}

function clientStorage($conn,$clientId){

		$totalStoredData = 0;
		$getProjectSize = mysqli_query($conn, "select sum((row_size) / 1024 / 1024) AS `Size_MB` from ( select  SUM(char_length(project_id)+ char_length(project_name)+ char_length(client_id)+ COALESCE(char_length(description),0)+ char_length(status)+ char_length(created_at)) as row_size  from projects where client_id='".$clientId."'  ) AS projectSize ");
		$projectSize = mysqli_fetch_object($getProjectSize);
		 $totProjectSize = $projectSize->Size_MB;
		$totalStoredData+= $totProjectSize;
		
		///Create Form ////
		$getSurveySize = mysqli_query($conn, "select sum((row_size) / 1024 / 1024) AS `Size_MB`
						from (
						  select 
							SUM(char_length(id)+
							char_length(survey_name)+
							COALESCE(char_length(questinnour_file),0)+
							COALESCE(char_length(questionnaire_pdf),0)+
							char_length(created_at)+
							char_length(del_action)+
							char_length(status)+
							char_length(otp)+
							char_length(user_id)+
							char_length(client_id)+
							COALESCE(char_length(secret_key),0)+
							char_length(unique_id)+
							COALESCE(char_length(form_version),0)+
							char_length(category_id)+
							char_length(project_id))
						  as row_size 
						  from survey WHERE client_id='".$clientId."'  ) AS surveySize ");
		$surveySize = mysqli_fetch_object($getSurveySize);
		 $totSurveySize = $surveySize->Size_MB;
		 $totalStoredData+= $totSurveySize;
		
		//Create User///
		$getUserSize = mysqli_query($conn, "select sum((row_size) / 1024 / 1024) AS `Size_MB`
			from (
			  select 
				SUM(char_length(user_id)+
				char_length(name)+
				COALESCE(char_length(mobile),0)+
				char_length(username)+
				char_length(password)+
				char_length(orignal_password)+
				char_length(role_id)+
				COALESCE(char_length(user_code),0)+
				char_length(status)+
				COALESCE(char_length(email),0)+
				char_length(created_at)+
				COALESCE(char_length(firebase_token),0)+
				COALESCE(char_length(company_name),0)+
				char_length(client_id)+
				char_length(registered_as)+
				COALESCE(char_length(otp),0)+
				char_length(del_action)+
				char_length(device_id))
			  as row_size 
			  from users WHERE client_id='".$clientId."'  ) AS userSize; ");
		$userSize = mysqli_fetch_object($getUserSize);
		$totUserSize = $userSize->Size_MB;
		$totalStoredData+= $totUserSize;
		
		///Question Language///
		$getQLangSize = mysqli_query($conn, "select sum((row_size) / 1024 / 1024) AS `Size_MB`
			from (
			  select 
				SUM(char_length(question_lang_id )+
				char_length(language_id)+
				char_length(question_id)+
				COALESCE(char_length(question_name),0)+
				COALESCE(char_length(dictionary_label),0)+
				char_length(encrpt)+
				COALESCE(char_length(question_description),0)+
				COALESCE(char_length(questions_type_id),0)+
				COALESCE(char_length(question_input_type_id),0)+
				char_length(status)+
				char_length(prefill)+
				char_length(max_input)+
				char_length(screen_no)+
				char_length(group_id)+
				char_length(group_relation_id)+
				char_length(sequence_no)+
				char_length(field_name)+
				char_length(ref_table)+
				char_length(validation_id)+
				char_length(required)+
				char_length(title)+
				char_length(category_name)+
				char_length(survey_id)+
				char_length(input_field_type)+
				char_length(relevant)+
				char_length(constraints)+
				char_length(constraint_msg)+
				char_length(parameters)+
				char_length(read_only)+
				char_length(calculation)+
				COALESCE(char_length(repeat_count),0)+
				char_length(choice_filter)+
				char_length(appearance)+
				char_length(choice_relation)+
				COALESCE(char_length(default_response),0)+
				char_length(repeated)+
				char_length(normal_group_id)+
				COALESCE(char_length(paradata),0)+
				char_length(unique_id)+
				COALESCE(char_length(preserve),0)+
				char_length(lookups)+
				char_length(media_file))
			  as row_size 
			  from questions_language WHERE survey_id IN(SELECT id FROM survey WHERE client_id='".$clientId."' ) ) AS qSize; ");
		$qLSize = mysqli_fetch_object($getQLangSize);
		$totQuestionSize = $qLSize->Size_MB;
		$totalStoredData+= $totQuestionSize;
			
		//Options Language///
		$getOLangSize = mysqli_query($conn, "select sum((row_size) / 1024 / 1024) AS 'Size_MB'
			from (select 
				sum(char_length(options_language_id)+
				char_length(option_id)+
				char_length(question_id)+
				char_length(survey_id)+
				char_length(language_id)+
				COALESCE(char_length(option_name),0)+
				char_length(status)+
				COALESCE(char_length(option_sequence),0)+
				char_length(is_terminate)+
				COALESCE(char_length(option_type),0)+
				char_length(serial_no_for_app)+
				COALESCE(char_length(category_name),0)+
				COALESCE(char_length(choice_filter_parent),0)+
				COALESCE(char_length(likert_img),0)+
				COALESCE(char_length(media_file),0)+
				COALESCE(char_length(option_constraint),0)
				
				
				 )
			  as row_size 
			  from options_language  WHERE survey_id IN(SELECT id FROM survey WHERE client_id='".$clientId."' )  ) AS oldata; ");
		$oLSize = mysqli_fetch_object($getOLangSize);
		$totOptionSize = $oLSize->Size_MB;
		$totalStoredData+= $totOptionSize;
		
		///Survey Data Monitoring ////
		$getSurveydmSize = mysqli_query($conn, "select sum((row_size) / 1024 / 1024) AS 'Size_MB'
				from (select 
					sum(
					char_length(survey_data_monitoring_id)+
					COALESCE(char_length(survey_data_id),0)+
					COALESCE(char_length(survey_form_id),0)+
					char_length(survey_id)+
					char_length(user_id)+
					char_length(survey_data_json)+
					char_length(full_json)+
					char_length(survey_status)+
					char_length(user_type)+
					COALESCE(char_length(termination_reason),0)+
					char_length(created_on)+
					char_length(survey_name)+
					char_length(survey_name_id)+
					char_length(client_id)+
					char_length(latitude)+
					char_length(longitude)+
					COALESCE(char_length(para_data),0)+
					COALESCE(char_length(hint_record),0)+
					COALESCE(char_length(error_record),0)+
					char_length(language_id)+
					char_length(survey_data_json_export)+
					char_length(survey_data_json_coded) 
						
						
					 )
				  as row_size 
				  from survey_data_monitoring  WHERE client_id='".$clientId."' ) AS sdatam; ");
		$sdmSize = mysqli_fetch_object($getSurveydmSize);
		$totSurveydmSize = $sdmSize->Size_MB;
		$totalStoredData+= $totSurveydmSize;
		
		/////Media Lookups ////
		$directory = 'medialookups/C'.$clientId;
		$bytes = dirSize($directory);
		$sizeinMB = $bytes / 1048576; 
		$totalStoredData+= $sizeinMB;
		
		
		/////Tool Archive/////
		$directoryTA = 'upload_data_file/tools_archive_datafile/C'.$clientId;
		$bytesTA = dirSize($directoryTA);
		$sizeinMBTA = $bytesTA / 1048576; 
		$totalStoredData+= $sizeinMBTA;
		
		
		///Data Reposotory (Codebook)///
		$directoryDRCB = 'upload_data_file/upload_codebook/C'.$clientId;
		$bytesDACB = dirSize($directoryDRCB);
		$sizeinDACB = $bytesDACB / 1048576; 
		$totalStoredData+= $sizeinDACB;
		
		////Data Reposotory (Data Formate File)///
		$directoryDRDff = 'upload_data_file/upload_dataformate_file/C'.$clientId;
		$bytesDRDff = dirSize($directoryDRDff);
		$sizeinDRDff = $bytesDRDff / 1048576; 
		$totalStoredData+= $sizeinDRDff;
		
		///Update client storage///
		mysqli_query($conn,"update clients set datasize_mb='".$totalStoredData."' where id='".$clientId."' ");
		
	}
	
    function currentTimeStamp(){
		$date = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
 		return $date->format('Y-m-d H:i:s');
	}



function login($post,$conn)
{
    //session_regenerate_id(true);
    $message=array();
    $user_name=clear_malicious_data($post['username'],$conn);
    $password=$post['password'];
	
	$password = cryptoJsAesDecrypt(ENC_KEY, $password);
	$storedpass = hash('sha256',$password);
	
	
	//$password=encryptPassword($password); //create SHA256 function in function page
	
     $get_authentication_query="SELECT username,password,user_id, client_id, role_id,registered_as,login_date,name FROM users WHERE  binary username='".$user_name."' and  role_id in (1,2,3,4,5,6,7,8,9,10) and status='0'";
	
	$authentication_query=mysqli_query($conn,$get_authentication_query)or die(mysqli_error());
    if(mysqli_num_rows($authentication_query)>0){
      $login_data=mysqli_fetch_array($authentication_query,MYSQLI_ASSOC);
	  $full_name=$_SESSION['name']=$login_data['name'];
	  $user_id=$_SESSION['user_id']=$login_data['user_id'];
      $client_id=$_SESSION['client_id']=$login_data['client_id'];
	  $_SESSION['registered_as']=$login_data['registered_as'];
      $role_id = $_SESSION['role_id']=$login_data['role_id'];
      $_SESSION['username'] = $login_data['username'];
	  $hash_password=$login_data['password'];
	  $login_date=$login_data['login_date'];
	  $nowdate=date("Y-m-d");
	  $_SESSION['ISMEMORYFULL'] = false;
	  $_SESSION['SUBSCRIPTIONEXPIRED'] = false;
	  $_SESSION['BTNBLOCK'] = "";
      $_SESSION['subscription_scope'] = 1;
	   
	 
	  
	  if (hash_equals($storedpass,$hash_password)) {
		  
			$getControlRole = mysqli_query($conn,"SELECT GROUP_CONCAT(DISTINCT(role_id)) AS role_id FROM functional_role WHERE user_id='".$_SESSION['user_id']."' ");
			$controlRole = mysqli_fetch_object($getControlRole);
			$userControlRole =  $controlRole->role_id;
			$_SESSION['functional_role_id']=$controlRole->role_id;
			
			get_enc_key($client_id);
		
			$getPageButtons = mysqli_query($conn,"SELECT GROUP_CONCAT(page_button_id) AS page_button_id  FROM page_control  WHERE role_id IN($userControlRole) AND status='0' ");
			$pageButtons = mysqli_fetch_object($getPageButtons);
			$pagebuttonid = $pageButtons->page_button_id;
			$_SESSION['page_button_id']=$pageButtons->page_button_id;
			if($login_date!=$nowdate){
				$sqllogindate="update users set login_date='".$nowdate."' where username='".$user_name."' and user_id='".$login_data['user_id']."'";
				mysqli_query($conn,$sqllogindate);
				//clientStorage($conn,$client_id);
				clientStorage($conn,$client_id);
			}
			
			$getClientMemorySizes = mysqli_query($conn,"SELECT id,subscription, datasize_mb, allocatedsize_mb FROM clients WHERE id='".$client_id."' ");
			$clientMemorySizes = mysqli_fetch_object($getClientMemorySizes);
			$datasize_mb = $clientMemorySizes->datasize_mb;
			$allocatedsize_mb = $clientMemorySizes->allocatedsize_mb;
			$_SESSION['datasize_mb'] = $clientMemorySizes->datasize_mb;
			$_SESSION['allocatedsize_mb'] = $clientMemorySizes->allocatedsize_mb;
			$susbcription_scope=$clientMemorySizes->subscription;
			
			if($susbcription_scope==0)
			 {
				 $_SESSION['SUBSCRIPTIONEXPIRED']=true;
			$sqlSubscriptioncheck=mysqli_query($conn,"select * from pm_userSubscription_status where client_id='".$client_id."'");
			$dataCountSubscription=mysqli_num_rows($sqlSubscriptioncheck);
			if($dataCountSubscription>0)
			{
				$dataSubscriptionData=mysqli_fetch_object($sqlSubscriptioncheck);
				$subscription_status=$dataSubscriptionData->subscription_status;
				if($subscription_status=='Expired')
				{
					$_SESSION['SUBSCRIPTIONEXPIRED']=true;
				}
				else {
					$_SESSION['SUBSCRIPTIONEXPIRED']=false;
				}
			}
			 }
			 
			
			
			$date_time = currentTimeStamp();
			$device_name='Web';
			$getUserlog="insert into user_log set user_id='".$user_id."',client_id='".$client_id."',date_time='".$date_time."',device_name='".$device_name."'";
			$dataUserlog=mysqli_query($conn,$getUserlog);
			 $_SESSION['custom_session_time'] = time();
			if($datasize_mb>$allocatedsize_mb){ $_SESSION['ISMEMORYFULL'] = true; $_SESSION['BTNBLOCK'] = "disabled";  }
			$message=array("status"=>1,"message"=>'Successfully login',"login_status"=>$user_id,"roles"=>$role_id);  
		}else{
		  $message=array("status"=>0,"message"=>'Username and Password are incorrect');
		}
	 
    }
    else
    {
      $message=array("status"=>0,"message"=>'Username and Password are incorrect');
    }
    return $message;
	
}


*/



	 
	 
?>