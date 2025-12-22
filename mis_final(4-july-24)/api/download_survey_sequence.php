<?php

header('Access-Control-Allow-Origin: *');
require_once 'config.php';
require_once 'jwt.php';

$headers = getallheaders();
$tokendata = $headers['X-Authorization'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if ($tokendata) {
		$token_array = explode(" ", $tokendata);
		if($token_array[0] == 'Bearer'){
			$token = $token_array[1];
			$decodedToken = verifyToken($token, JWT_SECRET_KEY,$conn);
			if ($decodedToken) {
				$_REQUEST = json_decode(file_get_contents('php://input'), true);
				$arrResults = array();
				$user_id = $_REQUEST['user_id'];
				if($user_id!=''){
					$UserseqSql = "SELECT survey_name_id,user_id,sequence_id FROM `survey_data_sequence` WHERE user_id = '".$user_id."' AND (survey_name_id, sequence_id) IN ( SELECT survey_name_id, MAX(sequence_id) AS max_sequence_id FROM `survey_data_sequence` WHERE user_id = '".$user_id."' GROUP BY survey_name_id) ORDER BY sequence_id DESC";
					$return_arr = array();
					mysqli_set_charset($conn, 'utf8');
					$fetchData = mysqli_query($conn, $UserseqSql);
					
					$rows = array();
					while ($row = mysqli_fetch_array($fetchData, MYSQLI_ASSOC))
					{
						$rows[] = $row;
					}
					
					echo json_encode($rows);
					exit;
				}
				else{
					$rows = array("status"=>0,"message"=>"User Id are Required.");
					echo json_encode($rows);
					exit;
				}
			}
		}else{
			$response = ["status"=>0,"message" => "Auth request is invalid"];
			echo json_encode($response);
			exit;
		}
	}
	http_response_code(401); // Unauthorized status code
	$response = ["status"=>0,"error" => "Invalid token"];
	echo json_encode($response);
	exit;
} else {
	http_response_code(405); // Method Not Allowed
	$response = ["status"=>0, "message" => "The call method not allowed"];
	echo json_encode($response);
	exit;
}
