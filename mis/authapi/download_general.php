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
				$table=isset($_REQUEST['table_name'])?$_REQUEST['table_name']:'';
				$user_id=isset($_REQUEST['user_id'])?$_REQUEST['user_id']:''; 
				if($_REQUEST['table_name']!='' )
				{
					if ($_REQUEST['table_name']=='states')
					{
						$Usersql = "SELECT `state_id`, `state_name`, `status`, `state_code` FROM `states`  where status='0'"; 
					}
					
				}
				else{
					echo json_encode(array("success"=>"0", "message"=>"table name are required."));
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
				$rows[] = $row;
			}
			echo  json_encode($rows);
			
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
