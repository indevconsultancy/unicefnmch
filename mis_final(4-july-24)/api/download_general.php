<?php 
header('Access-Control-Allow-Origin: *');

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
        $decodedToken = verifyToken($token, JWT_SECRET_KEY,$conn);
        
        if ($decodedToken) {
            $_REQUEST = json_decode(file_get_contents('php://input'),true);
			$data = Profile1($conn);
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

		


function Profile1($conn)
{

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
           if ($_REQUEST['table_name']=='districts')
	       {
	          $Usersql = "SELECT `district_id`, `district_name`, `state_id`, `status` FROM `districts` where status='0'"; 
	       }
           if ($_REQUEST['table_name']=='sub_districts' )
	       {
			   
	          $Usersql = "SELECT `sub_district_id`, `sub_district_name`,sub_district_code FROM `sub_districts` WHERE  status='0' and sub_district_id in (SELECT sub_district_id FROM `user_allocation` where user_id='".$user_id."')";
	       }
		   if ($_REQUEST['table_name']=='nccs_matrix')
	       {
	          $Usersql = "SELECT `id`, `durables_id`, `education_id`,`nccs_education_id`, `nccs_category` FROM `nccs_matrix` WHERE  status='0'"; 
	       }
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
	return $rows;	
}

?>