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
	$_REQUEST = json_decode(file_get_contents('php://input'),true);
	
	$authHeader = $_SERVER['HTTP_AUTHORIZATION'];
	$arr = explode(" ", $authHeader);
    $jwt = $arr[1];
	if($jwt){
		try {
			$decoded = JWT::decode($jwt, $secret_key, array('HS256'));
			
			$Usersql = 0;
			$target_file=0;
			$arrResults = array();	
			$qry=" where 1=1";
			if(isset($_REQUEST))
			{
				$survey_data_monitoring_id=isset($_REQUEST['survey_data_monitoring_id'])?$_REQUEST['survey_data_monitoring_id']:''; 
				if($_REQUEST['survey_data_monitoring_id']!='' )
				{
					$Usersql = "SELECT * FROM survey_data_monitoring  where survey_data_monitoring_id='".$survey_data_monitoring_id."' "; 
				}
			}
			else
			{
				$Usersql = "";
			}
			$return_arr = array();
			mysqli_set_charset($conn,'utf8'); 
			$fetch = mysqli_query($conn,$Usersql); 
			$rows = array();
			while ($row = mysqli_fetch_array($fetch, MYSQLI_ASSOC)) 
			{
				$row['survey_data_monitoring_id'] = $row['survey_data_monitoring_id'];
				$row['survey_id'] = $row['survey_id'];
				$row['user_id'] = $row['user_id'];
				$row['survey_data_json'] = json_decode($row['survey_data_json']);
				$row['full_json'] = json_decode($row['full_json']);
				$rows[] = $row;
			}
			echo json_encode($rows);
			
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