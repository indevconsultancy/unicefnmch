<?php
include_once 'config.php';
require "vendor/autoload.php";
use \Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


$user_name = $password = $firebase_token = $requested_device = '';

if($_SERVER['REQUEST_METHOD'] === "POST"){
    $request = json_decode(file_get_contents("php://input"));
	
	$user_name = mysqli_real_escape_string($conn, $request->user_name);
	$user_password= mysqli_real_escape_string($conn, $request->user_password);
	$firebase_token = $request->firebase_token;
	$requested_device = $request->device_id;
	//$user_password = encryptPassword($password);
	$sql="SELECT `user_id`, `name`,`device_id`, `mobile`, `username`, `password`, `role_id`, `status`, `email`, `agency_code`, `created_at`, `client_id` FROM `users` WHERE username='".$user_name."'  and status='0'";
	$run=mysqli_query($conn,$sql);
	$count=mysqli_num_rows($run);
	
    if($count > 0){
		$data=mysqli_fetch_object($run);
		if(password_verify($user_password,$data->password)){
			$secret_key = SECRETKEY; //"YOUR_SECRET_KEY";
            $issuer_claim = "mquad.org"; // this can be the servername
            $audience_claim = "THE_AUDIENCE";
            $issuedat_claim = time(); // issued at
            $notbefore_claim = $issuedat_claim + 10; //not before in seconds
            $expire_claim = $issuedat_claim + 20*60; // expire time in seconds
            
		   $name=$data->name;
		   $user_name=$data->username;
		   $device_id=$data->device_id;
		   $user_id=$data->user_id;
		   $user_type_id=$data->role_id;
		   $email=$data->email; 
		   $agency_code=$data->agency_code; 
		   $client_id=$data->client_id;
			$token = array(
				"iss" => $issuer_claim,
				"aud" => $audience_claim,
				"iat" => $issuedat_claim,
				"nbf" => $notbefore_claim,
				"exp" => $expire_claim,
				"data" => array(
					"user_id" => $user_id,
					"name" => $name,
					"user_name" => $user_name,
					"device_id" => $device_id
				)
			);
			http_response_code(200);
			$jwt = JWT::encode($token, $secret_key);
			
			
			
			$sqlclient=mysqli_query($conn,"SELECT id,logo_image FROM clients where id='".$client_id."' ");
			$dataClient=mysqli_fetch_object($sqlclient);
			$logo_image=$dataClient->logo_image;
			$url_logo="https://mquad-stage11.indevconsultancy.in/logo_image/".$logo_image."";
			if($device_id=='')
			{
				mysqli_query($conn,"UPDATE users SET firebase_token='".$firebase_token."', device_id='".$requested_device."' WHERE user_id='".$user_id."' ");
				$arrResults = array("success" =>1, "message" => 'Login Successful.',"jwt" => $jwt,"user_id"=>$user_id,"name"=>$name,"user_name"=>$user_name,"user_type_id"=>$user_type_id,"email"=>$email,"agency_code"=>$agency_code,"logo_image"=>$url_logo,"client_id"=>$client_id);
			}
			elseif($requested_device==$device_id)
			{
				mysqli_query($conn,"UPDATE users SET firebase_token='".$firebase_token."' WHERE user_id='".$user_id."' ");
				$arrResults = array("success" =>1, "message" => 'Login Successful.',"jwt" => $jwt,"user_id"=>$user_id,"name"=>$name,"user_name"=>$user_name,"user_type_id"=>$user_type_id,"email"=>$email,"agency_code"=>$agency_code,"logo_image"=>$url_logo,"client_id"=>$client_id);
				
			}
			else{
				$arrResults = array("success" =>3, "message" => 'Already Logged In');
			}
			
            echo json_encode($arrResults);
		}
		else
		{
			http_response_code(401);
            echo json_encode(array("success"=>"0", "message" => "inviled username or password."));
		}
    }
	else
	{
		http_response_code(401);
        echo json_encode(array("success"=>"0", "message" => "inviled username or password."));
	}
} 
?>