<?php
	require("PHPMailer-master/PHPMailer-master/src/PHPMailer.php");   
	require("PHPMailer-master/PHPMailer-master/src/SMTP.php");

function login($post,$conn)
{

    $message=array();
    $user_name=clear_malicious_data($post['username'],$conn);
    $password=clear_malicious_data($post['password'],$conn);
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
	  $_SESSION['BTNBLOCK'] = "";
	
	  if (password_verify($password, $hash_password)) {
		  
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
			 
			$getClientMemorySizes = mysqli_query($conn,"SELECT id, datasize_mb, allocatedsize_mb FROM clients WHERE id='".$client_id."' ");
			$clientMemorySizes = mysqli_fetch_object($getClientMemorySizes);
			$datasize_mb = $clientMemorySizes->datasize_mb;
			$allocatedsize_mb = $clientMemorySizes->allocatedsize_mb;
			$_SESSION['datasize_mb'] = $clientMemorySizes->datasize_mb;
			$_SESSION['allocatedsize_mb'] = $clientMemorySizes->allocatedsize_mb;
			
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
function dirSize($dir)
{
	$dirSize = 0;
	if(!is_dir($dir)){return false;};
	$files = scandir($dir);if(!$files){return false;}
	$files = array_diff($files, array('.','..'));

	foreach ($files as $file) {
		if(is_dir("$dir/$file")){
			 $dirSize += dirSize("$dir/$file");
		}else{
			$dirSize += filesize("$dir/$file");
		}
	}
	return $dirSize;
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
	function dateformate($date){
		$datereplace= str_replace(array("-/", "/"), array("-", "-"), $date);
		// $data= date('d-m-Y h:i:s',strtotime($datereplace));
		$data= date('d-m-Y H:i:s',strtotime($datereplace));
		return $data;
	}
	function dateformatetype($date){
		$datereplace= str_replace(array("-/", "/"), array("-", "-"), $date);
		// $data= date('d-m-Y h:i:s',strtotime($datereplace));
		$data= date('d-m-Y',strtotime($datereplace));
		return $data;
	}
function clear_malicious_data($user_data,$conn){
    $sanitize_input=trim($user_data);

    $sanitize_input=strip_tags($sanitize_input);

    $sanitize_input=mysqli_real_escape_string($conn,$sanitize_input);

    return $sanitize_input;

}
function send_mail_function($mailto,$message,$subject) {
		$arrResultss = array();
		
		$email = $mailto;
		$subject = base64_decode($subject);
		$message = base64_decode($message) . '<br><br>Warm Regards,<br> MQUAD Team <br><br>'; 
		// $subject = base64_decode($subject);
		// $message = base64_decode($message);
		if ( $email != '') {
			$txt = str_replace('\.','.',$message);
			$email_id_config="info@mquad.org";
			$mailhost="smtp.gmail.com";
			//$email_pass_config="qdhvobecuxegyxea";
			$email_pass_config="mdoasnhyshvkzkbo";
			$setfrom="info@mquad.org";
			$mailport=465;
			$smtpSecure="ssl";
			
			$mail = new PHPMailer\PHPMailer\PHPMailer();
			$mail->IsSMTP(); // enable SMTP
			$mail->SMTPDebug = 0; 
			$mail->SMTPAuth = true; 
		
			$mail->Host = $mailhost;  
			$mail->Port = $mailport;
			$mail->IsHTML(true);
			$mail->Username=$email_id_config;
			$mail->Password=$email_pass_config;
			$mail->SMTPSecure = $smtpSecure;
			$mail->SetFrom($setfrom,"MQUAD");
			$mail->AddAddress($email); 
			$mail->Subject = $subject;
			$mail->Body    = $txt;
			// print_r($mail->send());
			// die();
			$mailsendto =$mail->send();
			if($mailsendto) {
				$arrResultss=array("status"=>1,"message"=>"Success","msg"=>$txt);
			}
			else{
				$arrResultss=array("status"=>0,"message"=>"Failed");
			}
			return $arrResultss;
		}
		
	}
	
function getcount($conn, $tablename, $field, $qryfield, $value, $qryfield1, $value1) {
	$query = "SELECT  COUNT(DISTINCT($field)) as total FROM $tablename WHERE $qryfield='$value' AND $qryfield1='$value1'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_object($result);
    
    return $row->total;
}
function getcountrow($conn, $tablename, $field, $qryfield, $value) {
    //echo $query = "SELECT COUNT($field) as total FROM $tablename WHERE $qryfield='$value'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_object($result);
    
    return $row->total;
}
function Multicolumns($conn,$tablename,$fields,$qryfeild1,$value1,$qryfeild2,$value2,$qryfeild3,$value3)
{
	
	//echo "select $fields from $tablename where $qryfeild1='".$value1."' and $qryfeild2='".$value2."'and $qryfeild3='".$value3."'";
	$sn=mysqli_query($conn,"select $fields from $tablename where $qryfeild1='".$value1."' and $qryfeild2='".$value2."'and $qryfeild3='".$value3."'")or die(mysqli_error());
	$dn=mysqli_fetch_object($sn);
	return ($dn);
}
function encryptPassword($password){
	$salt = 'SaTyEnDRa_@_SaLT';
	return hash('sha256', $password . $salt);
}
//echo encryptPassword("Satyendra");
function uniqueId($name,$id){
	$name = trim($name);
	$str = substr($name, 0,1);
	$uid = str_pad($id,6,'0',STR_PAD_LEFT);
	return $uniquecode = "M" . $str . $uid;
}
 function paginate($item_per_page, $current_page, $total_records, $total_pages, $page_url)  {
        // echo $current_page."/".$total_pages;
      
        $pagination = '';
        
        $page_url.="&";
        
        if($total_pages > 0 && $current_page <= $total_pages) { //verify total pages and current page number
            $pagination .= '<h5 class="m-0">Total Pages: '.$current_page.' of '.$total_pages.'</h5>'.
            '<ul class="pagination m-0 ml-2">';
            
            $right_links    = $current_page + 3;
            $previous       = $current_page - 1; //previous link
            $next           = $current_page + 1; //next link
            $first_link     = true; //boolean var to decide our first link
            if($current_page > 1) {
                $previous_link = ($previous==0)?1:$previous;
                $pagination .= '<li class="page-item prev"><a class="page-link" href="'.$page_url.'page=1" title="First"><i class="fa fa-angle-double-left"></i></a></li>'; //first link
                $pagination .= '<li class="page-item" ><a class="page-link" href="'.$page_url.'page='.$previous_link.'" title="Previous"><i class="fa fa-angle-left"></i></a></li>'; //previous link
                for($i = ($current_page-2); $i < $current_page; $i++){ //Create left-hand side links
                    if($i > 0){
                        $pagination .= '<li class="page-item"><a class="page-link" href="'.$page_url.'page='.$i.'">'.$i.'</a></li>';
                    }
                }
                $first_link = false; //set first link to false
            }
            if($first_link){ //if current active page is first link
                $pagination .= '<li class="page-item active"><a class="page-link" href="'.$page_url.'page='.$current_page.'">'.$current_page.'</a></li>';
            } elseif($current_page == $total_pages){ //if it's the last active link
              $pagination .= '<li class="page-item last active"><a class="page-link" href="'.$page_url.'page='.$current_page.'">'.$current_page.'</a></li>';
            }else{ //regular current link
              $pagination .= '<li class="page-item active"><a class="page-link" href="'.$page_url.'page='.$current_page.'">'.$current_page.'</a></li>';
            }
            for($i = $current_page+1; $i < $right_links ; $i++){ //create right-hand side links
                if($i<=$total_pages){
                    $pagination .= '<li class="page-item"><a class="page-link" href="'.$page_url.'page='.$i.'">'.$i.'</a></li>';
                }
            }
            if($current_page < $total_pages){
                $next_link = ($i > $total_pages)? $total_pages : $i;
                $pagination .= '<li class="page-item"><a class="page-link" href="'.$page_url.'page='.$next_link.'" ><i class="fa fa-angle-right"></i></a></li>'; //next link
                $pagination .= '<li class="page-item next"><a class="page-link" href="'.$page_url.'page='.$total_pages.'" title="Last"><i class="fa fa-angle-double-right"></i></a></li>'; //last link
            }
            $pagination .= '</ul>';
        }
        return $pagination; 
        //return pagination links
    }
	///////////////////Notification send///////////////////////
	function sendNotification($firebase_token,$activitis,$message,$json)
    {
        $activitis = str_replace(" ","%20",$activitis);
        $message = str_replace(" ","%20",$message);
        $json = str_replace(" ","%20",$json);
        
       $path="https://mquad.org/firebase/?regId=$firebase_token&title=$activitis&message=$message&json=$json&push_type=individual";
        
        $send=file_get_contents($path);
        if($send)
        {
            return "success";
        }else{
            echo "failed";
        }
    }
function getSurveyStatus($survey_status) {
    $surveystatus = [
        1 => 'Submitted',
        3 => 'Terminated',
        4 => 'Send for review',
        5 => 'Approved',
        6 => 'Re-submitted',
        7 => 'Rejected',
    ];

    return isset($surveystatus[$survey_status]) ? $surveystatus[$survey_status] : '';
}	

function SpecialCharRemove($removedata)
{
  $remove = preg_replace('/[<>+?#\[\==^*&%",!(){}:\;\]]+/', '', $removedata);
  return $remove;
}
function getKeyWordName($conn, $keyWOrdID)
{
    $sqlKeyword = mysqli_query($conn, "SELECT GROUP_CONCAT(keyword_name) as keyname FROM keywords where status='1' and keywords_id in ($keyWOrdID)");
    $selkeydata = mysqli_fetch_array($sqlKeyword);
    return $selkeydata['keyname'];
}
function getKeyWordNames($conn, $tablename, $qryfield, $keyWordID, $fields)
{
    $tablename = mysqli_real_escape_string($conn, $tablename);
    $qryfield = mysqli_real_escape_string($conn, $qryfield);
    $keyWordID = mysqli_real_escape_string($conn, $keyWordID);
    $sqlKeyword = "SELECT GROUP_CONCAT($fields SEPARATOR ', ') as fields FROM $tablename WHERE status='1' AND $qryfield IN ($keyWordID)";
    $result = mysqli_query($conn, $sqlKeyword);
    $selkeydata = mysqli_fetch_array($result);
    return $selkeydata['fields'];
}

function getcotegryname($conn, $tablename, $qryfield, $cID, $fields)
{
    $tablename = mysqli_real_escape_string($conn, $tablename);
    $qryfield = mysqli_real_escape_string($conn, $qryfield);
    $cID = mysqli_real_escape_string($conn, $cID);
    $sqlKeyword = "SELECT GROUP_CONCAT($fields SEPARATOR ', ') as fields FROM $tablename WHERE  $qryfield IN ($cID)";
    $result = mysqli_query($conn, $sqlKeyword);
    $selkeydata = mysqli_fetch_array($result);
    return $selkeydata['fields'];
}
function getonefield($conn, $tablename, $field, $qryfeild, $value)
{
    $sn = mysqli_query($conn, "select  $field  as field from $tablename where $qryfeild='" . $value . "' ") or die(mysqli_error());
    $dn = mysqli_fetch_object($sn);
    return ($dn->field);
}

function pichartdesign($conn,$survey_id,$question_id)
{  
   $data='';

   $sqlquestion=mysqli_query($conn,"select question_name,field_name from questions where survey_id='".$survey_id."' and field_name='".$question_id."'");
   $dataquestion=mysqli_fetch_object($sqlquestion);  
   $fields='$.'.$dataquestion->field_name;
   $chartid=$dataquestion->field_name;
   $i=0;
	$sqlquestionqry=mysqli_query($conn,"select JSON_EXTRACT(survey_data_json, '".$fields."') as category ,count(*) as total from survey_data_monitoring where survey_name_id='".$survey_id."' group by category");
	while($rowdata=mysqli_fetch_object($sqlquestionqry))
	{
		if($i>0)
		{
			$data.=',';
		}
	$data.='{ 
        name: '.$rowdata->category.',
        y: '.$rowdata->total.'
      }';
	$i++; }
	$dataprint="<div id='".$chartid."' style='width:100%; height:400px;'></div>
	<script>
Highcharts.chart('".$chartid."', { colors: ['#29809b','#f39c12','#808080'],
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    legend: {
              itemStyle: {
                fontSize:'10px',
                 fontFamily: 'Muli, sans-serif',
                 color: '#333333'
              },
              itemHoverStyle: {
                 color: '#333333',
                 fontWeight: 'bold'
              },
              itemHiddenStyle: {
                 color: '#444'
              }

        },
    title: {
        text: '".$dataquestion->question_name."'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.y}</b>'
        // pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                distance: -30,
                format: '{point.y}'
            },
            showInLegend: true
        },
        
    },
    series: [{
        name: 'Total',
        colorByPoint: true,
        data: [".$data."]
    }]
});

</script>";

return($dataprint);
}

function barchartdesign($conn,$survey_id,$question_id)
{  
   $data='';
   $sqlquestion=mysqli_query($conn,"select question_name,field_name from questions where survey_id='".$survey_id."' and field_name='".$question_id."'");
   $dataquestion=mysqli_fetch_object($sqlquestion);  
   $fields='$.'.$dataquestion->field_name;
   $chartid=$dataquestion->field_name;
   $i=0;
	$sqlquestionqry=mysqli_query($conn,"select JSON_EXTRACT(survey_data_json, '".$fields."') as category ,count(*) as total from survey_data_monitoring where survey_name_id='".$survey_id."' group by category");
	while($rowdata=mysqli_fetch_object($sqlquestionqry))
	{
		if($i>0)
		{
			$data.=',';
		}
	$data.='{ 
        name: '.$rowdata->category.',
        y: '.$rowdata->total.'
      }';
	$i++; }
	$dataprint="<div id='bar".$chartid."' style='width:100%; height:400px;'></div>
	<script>
		Highcharts.chart('bar".$chartid."', {
    chart: {
        type: 'column'
    },
    title: {
        text: '".$dataquestion->question_name."'
    },
    subtitle: {
        text: ''
    },
    accessibility: {
        announceNewData: {
            enabled: true
        }
    },
    xAxis: {
        type: 'category'
    },
    yAxis: {
        title: {
            text: ''
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
			color:'#29809b',
            dataLabels: {
                enabled: true,
                format: '{point.y:.0f}'
            }
        }
    },
    tooltip: {
        headerFormat: '<span style=\"font-size:11px\">{series.name}</span><br>',
        pointFormat: '<span style=\"color:{point.color}\">{point.name}</span>: <b>{point.y:.0f}</b><br/>'
    },

    series: [
        {
            name: 'Total',
            data: [ ".$data." ]
        }
    ]
});

</script>";

return($dataprint);
}


function dynamicdrilldowndesign($conn,$survey_id,$question_id)
{  
   $data='';
   $sqlquestion=mysqli_query($conn,"select question_name,field_name from questions where survey_id='".$survey_id."' and field_name='".$question_id."'");
   $dataquestion=mysqli_fetch_object($sqlquestion);  
   $fields='$.'.$dataquestion->field_name;
   $chartid=$dataquestion->field_name;
   $i=0;
   $subdata='';
   $subsubdata.='';
	$sqlquestionqry=mysqli_query($conn,"select JSON_UNQUOTE(JSON_EXTRACT(survey_data_json, '".$fields."')) as category ,count(*) as total from survey_data_monitoring where survey_name_id='".$survey_id."' group by category");
	while($rowdata=mysqli_fetch_object($sqlquestionqry))
	{
				
		if($i>0)
		{
			$data.=',';
			$subsubdata.=',';
		}
	$data.='{ 
        name: "'.$rowdata->category.'",
        y: '.$rowdata->total.',
		drilldown: "'.$rowdata->category.'"
      }';
	$subsubdata.='{
		name: "'.$rowdata->category.'",
        id: "'.$rowdata->category.'",
        data: [';
	$i++;
	$n=0;
	//echo "select user_id as users, count(*) as total from survey_data_monitoring where survey_name_id='".$survey_id."' and JSON_UNQUOTE(JSON_EXTRACT(survey_data_json, '".$fields."'))='".$rowdata->category."' group by user_id";
	$subsqlquestionqry=mysqli_query($conn,"select user_id as users, count(*) as total from survey_data_monitoring where survey_name_id='".$survey_id."' and JSON_UNQUOTE(JSON_EXTRACT(survey_data_json, '".$fields."'))='".$rowdata->category."' group by user_id");
	while($subrowdata=mysqli_fetch_object($subsqlquestionqry))
	{
		$datauserids='user'.$subrowdata->users;
		$datausertotal=$subrowdata->total;
	if($n>0)
		{
			$subsubdata.=',';
		}
		$subsubdata.='[
            "'.$datauserids.'",
            '.$datausertotal.'
          ]';
	$i++;
	}
	$subsubdata.=']
	}';
	}
	
   
	$chartid="drill".$question_id;
	$dataprint='<div id="'.$chartid.'" style="width:100%; height:400px;"></div>
	<script>
Highcharts.chart("'.$chartid.'", {
  chart: {
    type: "column"
  },
  title: {
    text: "'.$dataquestion->question_name.'"
  },
  subtitle: {
    text: "Click the columns to view user wise: "
  },
  accessibility: {
    announceNewData: {
      enabled: true
    }
  },
  xAxis: {
    type: "category"
  },
  yAxis: {
    title: {
      text: "No. of Interview"
    }

  },
  legend: {
    enabled: false
  },
  plotOptions: {
    series: {
      borderWidth: 0,
      dataLabels: {
        enabled: true,
        format: "{point.y}"
      }
    }
  },

  

  series: [
    {
      name: "'.$dataquestion->question_name.'",
      colorByPoint: true,
      data: [
	    '.$data.'
      ]
    }
  ],
  drilldown: {
    series: [
     '.$subsubdata.'
    ]
  }
});
	</script>';

return($dataprint);
}


?>