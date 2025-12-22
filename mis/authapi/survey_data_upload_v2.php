<?php
include_once 'config.php';
require "vendor/autoload.php";
use \Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$secret_key = SECRETKEY;  //"YOUR_SECRET_KEY";
$jwt = null;
if($_SERVER['REQUEST_METHOD'] === "POST"){
	// ini_set('display_startup_errors', 1);
	// ini_set('display_errors', 1);
	// error_reporting(-1);
	
	$_REQUEST = json_decode( file_get_contents( 'php://input' ), true );
	$authHeader = $_SERVER['HTTP_AUTHORIZATION'];
	$tokenArr = explode(" ", $authHeader);
    $jwt = $tokenArr[1];
	if($jwt){
		try {
			$decoded = JWT::decode($jwt, $secret_key, array('HS256'));
			
			$qry = '';
			$arrResults = array();
			$full_json = json_encode($_REQUEST, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

			$i = 1;
		
			$arr['survey_id'] = $_REQUEST['survey_id'];
			$arr_ml['survey_id'] = $_REQUEST['survey_id'];
			$arr['user_id'] = $_REQUEST[ 'user_id' ];
			$arr_ml['user_id'] = $_REQUEST[ 'user_id' ];
			$arr['termination_reason'] = $_REQUEST['termination_reason'];
			$arr['reason_of_change'] = $_REQUEST['reason_of_change'];
			$arr['reason'] = $_REQUEST['reason'];
			$survey_id = $_REQUEST['survey_id'];
			$user_id = $_REQUEST['user_id'];
			$app_version = $_REQUEST['app_version'];
			$termination_reason=$_REQUEST['termination_reason'];
			$user_type = getid( "users", "role_id", "user_id", $user_id, $conn );
			$user_type_code = getid( "roles", "name", "id", $user_type, $conn );
			$client_id = getid( "users", "client_id", "user_id", $user_id, $conn );

			$cluster_no = $_REQUEST['cluster_no'];
			$selected_language = $_REQUEST['selected_language'];
			$status = $_REQUEST['status'];
			$end_date1 = $_REQUEST['date_time'];
			$GPS_lat = $_REQUEST['GPS_latitude_start'];
			$GPS_long = $_REQUEST['GPS_longitude_start'];
			$survey_status = $_REQUEST['survey_status'];
			$lat2 = $_REQUEST['GPS_latitude_mid'];
			$long2 = $_REQUEST['GPS_longitude_mid' ];
			$lat3 = $_REQUEST['GPS_latitude_end'];
			$long3 = $_REQUEST['GPS_longitude_end'];
			$arr['user_type'] = $user_type_code;
			$arr_ml['user_type'] = $user_type_code;
			$arr['app_version'] = $app_version;
			$arr_ml['app_version'] = $app_version;

			$arr['survey_status'] = getid( "survey_status_master", "name", "id", $survey_status, $conn );
			$arr_ml['survey_status'] = getid( "survey_status_master", "name", "id", $survey_status, $conn );
			$arr['gps_lat_start'] = $GPS_lat;
			$arr['gps_long_start'] = $GPS_long;
			$arr['gps_lat_mid'] = $lat2;
			$arr['gps_long_mid'] = $long2;
			$arr['gps_lat_end'] = $lat3;
			$arr['gps_long_end'] = $long3;
			//	$originalDate = "28/12/2020 00:10:19";
			$originalDate = str_replace( "/", "-", $end_date1 );
			$end_date = date( "Y-m-d h:i:s", strtotime( $originalDate ) );
			$arr['survey_edate'] = date( "d-m-Y", strtotime( $originalDate ) );
			$arr['survey_etime'] = date( "h:i:s", strtotime( $originalDate ) );
			$survey_name=$_REQUEST['survey_name'];
			$survey_name_id=$_REQUEST['survey_name_id'];
			

			/* $findsurvey=mysqli_query($conn,"select count(survey_id) as tot,survey_data_monitoring_id from survey_data_monitoring where survey_id='".$survey_id."' and user_id='".$user_id."'");
			$details_survey=mysqli_fetch_object($findsurvey);
			if($details_survey->tot>0)
			{
				$last_id1=$details_survey->survey_data_monitoring_id;
				$arrResults = array( "success" => "1", "message" => "Already Saved..", "survey_data_monitoring_id" => $last_id1);
			}
			else
			{ */ 
				/////////////////////////////////
				$hintques='';
				foreach( $_REQUEST[ 'hint_record' ] as $hintkey=>$hintvalue)
				{   
					$hintques.=$hintvalue['h_question_id'].",";
				}
				$hintques=substr($hintques,0,-1);
				/////////////////////////////////
				$error_record='';
				foreach( $_REQUEST[ 'error_record' ] as $error_recordkey=>$error_recordvalue)
				{
					$error_record.=$error_recordvalue['e_question_id'].",";
				}
				$error_record=substr($error_record,0,-1);

				$paradata_details=array();
				foreach ( $_REQUEST[ 'survey_data' ] as $key => $value ) {
					$question_id = $value['question_id'];
					$questions = getone_multi( 'questions', 'question_name', 'question_id', $question_id, 'status', '0', $conn );

					$question_field_name = $value[ 'field_name' ];
					$question_name = '';
					$option_id = $value['option_id'];
					$option_value = $value['option_value'];
					if ( $question_field_name == 'current_date' ) {
						$current_date1 = $value['option_value'];
						$st_date = str_replace( "/", "-", $current_date1 );
						$current_date = date( "Y-m-d h:i:s", strtotime( $st_date ) );
						$arr['current_sdate'] = date( "d-m-Y", strtotime( $st_date ) );
						$arr['current_sday'] = date( "D", strtotime( $st_date ) );
						$arr['current_stime'] = date( "h:i:s", strtotime( $st_date ) );
					}
					if($value[ 'duration' ]!='')
					{
						$paradata_details['field_name']=$value[ 'field_name' ];
						$paradata_details['duration']=$value[ 'duration' ];
						$paradata_details['gps']=str_replace(",","~",$value[ 'gps' ]);
						$paradata_details['timestamp']=$value[ 'timestamp' ];
						$paradata_details['keystrok']=$value[ 'key_stroke' ];
						$paradata[]=$paradata_details;
						$paradata_details=array();
					}
					$option_name = '';
					if ( $option_value != '' ) {
						$option_data = $option_value;
						$option_data_ml = $option_value;
						$option_data_coded = $option_value;
					} else {
						if ( strpos( $option_id, ',' ) == false ) {
							$option_data_coded = $option_id;
							$option_data = getone_multi( 'options', 'option_name', 'question_id', $question_id, 'option_sequence', $option_id, $conn );
							$option_data_ml = getone_multi_language( 'options_language', 'option_name', 'question_id', $question_id, 'option_sequence', $option_id, $selected_language, $conn );
						} else {
							$option_datamulti = '';
							$option_datamulti_ml = '';
							$optval = explode( ',', $option_id );
							foreach ( $optval as $key ) {
								$option_datamulti .= getone_multi( 'options', 'option_name', 'question_id', $question_id, 'option_sequence', $key, $conn ) . ", ";
								$option_datamulti_ml .= getone_multi_language( 'options_language', 'option_name', 'question_id', $question_id, 'option_sequence', $key,$selected_language, $conn ) . ", ";
							}
							$option_data = substr( trim( $option_datamulti ), 0, -1 );
							$option_data_ml = substr( trim( $option_datamulti_ml ), 0, -1 );
							$option_data_coded = $option_id;
						}
					}

					$arr[$question_field_name ] = $option_data;
					$arr_ml[$question_field_name ] = $option_data_ml;

					$arrCoded[$question_field_name ] = $option_data_coded;
				}
				$paradatas=json_encode($paradata, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
				// print_r($arr);
				// die;
				$json = json_encode($arr, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
				$json_ml = json_encode($arr_ml, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
				$jsonCoded = json_encode($arrCoded, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
				//$json = json_encode($arr);
				if ( $json != '' ) {
					/////// Survey data table/////////////////////
					$project_id = 1;

					$date = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
					$created_on =  $date->format('Y-m-d H:i:s');
					
					$findsurvey = mysqli_query($conn, "select count(survey_id) as tot,survey_data_monitoring_id from survey_data_monitoring where survey_id='" . $survey_id . "' and user_id='" . $user_id . "'");
					$details_survey = mysqli_fetch_object($findsurvey);
					if ($details_survey->tot > 0) {
						$inser_sql = "update survey_data_monitoring set survey_data_json='" . $json . "', survey_data_json_coded='" . $jsonCoded . "', full_json='" . $full_json . "' where survey_id='" . $survey_id . "' and user_id='" . $user_id . "'  ";
						$insertquery = mysqli_query($conn, $inser_sql);
						$last_id = $details_survey->survey_data_monitoring_id;
					}
					else{
						$inser_sql = "insert into survey_data_monitoring set survey_data_id='" . $insert_survey_data_id . "', survey_id='" . $survey_id . "',user_id='" . $user_id . "',survey_data_json='" . $json . "', survey_data_json_coded='".$jsonCoded."', full_json='" . $full_json . "',cluster_code='" . $cluster_no . "',survey_status='" . $survey_status . "',supervisor_id='" . $sup_id . "',user_type='" . $user_type . "',termination_reason='".$termination_reason."',survey_name='".$survey_name."', survey_name_id='".$survey_name_id."', client_id='".$client_id."', latitude='".$GPS_lat."', longitude='".$GPS_long."', created_on='".$created_on."', hint_record='".$hintques."', error_record='".$error_record."', para_data='".$paradatas."',language_id='".$selected_language."', survey_data_json_export='".$json_ml."' ";
						$insertquery = mysqli_query( $conn, $inser_sql );
						$last_id = mysqli_insert_id( $conn );
					}
					

					if ( $insertquery ) {
						$arrResults = array( "success" => "1", "message" => "Save Succssesful", "survey_data_monitoring_id" => $last_id );
					}else{
						$path = 'backups/backup'.date('Ym').'.txt';
						$myfile = fopen($path, "a") or die("Unable to open file..");
						$txt = $created_on."\n";
						$txt .= $inser_sql." "."\n\n";
						fwrite($myfile, $txt);
						fclose($myfile); 
					}
				}
			//}
			echo  json_encode($arrResults);
			
		}
		catch (Exception $e){
            http_response_code(401);
            echo json_encode(array(
                "message" => "Access denied.",
                "error" => $e->getMessage()
            ));
        }
		
	}
	else{
		echo json_encode(array(
			"status" => "3",
			"message" => "token are required"
		));
	}
	
	
	
}
else{
	echo json_encode(array(
        "status" => "2",
        "message" => "Bad Request. Please try again..!"
    ));
}
?>


