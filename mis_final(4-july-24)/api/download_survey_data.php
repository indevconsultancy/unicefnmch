<?php 
header('Access-Control-Allow-Origin: *');

require_once "config.php";
require_once "jwt.php";
require_once "mycrypt.php";
$headers = getallheaders();
$tokendata = $headers["X-Authorization"] ?? "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($tokendata) {
        // Extract the token from the Authorization header
        $token_array = explode(" ", $tokendata);
        if($token_array[0] == "Bearer"){
            
        
        $token = $token_array[1];
        
        // Verify and decode the JWT token
        $decodedToken = verifyToken($token, JWT_SECRET_KEY,$conn);
        
        if ($decodedToken) {
            $_REQUEST = json_decode(file_get_contents('php://input'),true);
			$data = Profile1($conn, $decodedToken->user_taken);
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





function Profile1($conn, $clientId = 1)
{

	$Usersql = 0;
	$target_file=0;
	$arrResults = array();	
	$qry=" where 1=1";
	
	$enc_key = get_encryption_key(SUPER_ADMIN_KEY, SUPER_ADMIN_URL, $clientId);
        
        if($enc_key === 0 ){
                return array("success" => "0", "message" => "Your client is not active");
                
        }
	$mcrypt = new EncryptionUtils($enc_key);
//	$mcrypt = new EncryptionUtils();
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
			if($mcrypt->isJson($row['survey_data_json'])){
				$row['survey_data_json'] = json_decode($row['survey_data_json']);
				$row['full_json'] = json_decode($row['full_json']);
			}else {
				$DecryptedJson = $mcrypt->decrypt($row['survey_data_json']);
				$DecryptedFullJson = $mcrypt->decrypt($row['full_json']);
				$row['survey_data_json'] = json_decode($DecryptedJson);
				$row['full_json'] = json_decode($DecryptedFullJson);

			}
			
			$rows[] = $row;
		}
	return $rows;	
}

?>