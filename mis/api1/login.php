<?php
header('Access-Control-Allow-Origin: *');
header("Content-type: application/json; charset=utf-8");
include 'config.php';
require_once 'jwt.php';
require_once "mycrypt.php";
$_REQUEST = json_decode(file_get_contents('php://input'), true);
$data = login($conn);
echo json_encode($data);
exit;

function login($conn)
{
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
	$enc_key1 =$_ENV['CRYPT_KEY'] ;
	$mcrypt = new EncryptionUtils($enc_key1);
    $arrResults = array();
	 $_REQUEST['user_password'];
	// echo $passwordss=$mcrypt->encrypt('khushboo');
	// echo $password=$mcrypt->decrypt($passwordss);
	// die();
	$password=$mcrypt->decrypt($_REQUEST['user_password']);
    $user_name = mysqli_real_escape_string($conn, $_REQUEST['user_name']);
    $user_password = $password;
    $firebase_token = $_REQUEST['firebase_token'];
    $requested_device = $_REQUEST['device_id'];
    $device_name = $_REQUEST['device_name'];
    
    $date = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
    $date_time = $date->format('Y-m-d H:i:s');
    //$user_password= password_hash($password, );
    $sql = "SELECT `user_id`, `name`,`device_id`, `mobile`, `username`, `password`, `role_id`, `status`, `email`, `agency_code`, `created_at`, `client_id` FROM `users` WHERE username='" . $user_name . "' and status='0' limit 1 ";
//return array("request"=> $_REQUEST,"count"=>$count,"dpass"=>$user_password, "password" => password_verify($user_password, $data->password));
    $run = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($run);

    if ($count > 0) {

        $data = mysqli_fetch_object($run);
        
        if (password_verify($user_password, $data->password)) {
            $name = $data->name;
            $user_name = $data->username;
            $device_id = $data->device_id;
            $user_id = $data->user_id;
            $user_type_id = $data->role_id;
            $email = $data->email;
            $agency_code = $data->agency_code;
            $client_id = $data->client_id;
			$s3accessKey=base64_encode('AKIAZLY6QIYPRMM6SEOJ');
			$s3secretKey=base64_encode('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');
			
            $enc_key = get_encryption_key(SUPER_ADMIN_KEY, SUPER_ADMIN_URL, $client_id);
            if($enc_key === 0 ){
                $arrResults = array("success" => "0", "message" => "Your client is not active");
                return $arrResults;
            }

			/*add jwt auth key in response
			*/
			$jwtToken = generateToken($data->user_id, JWT_SECRET_KEY, $client_id);
			// Generate refresh token
			$refreshToken = generateRefreshToken($data->user_id, REFRESH_TOKEN_SECRET_KEY, $client_id);	
		

            $sqlclient = mysqli_query($conn, "SELECT id,logo_image FROM clients where id='" . $client_id . "' ");
            $dataClient = mysqli_fetch_object($sqlclient);
            $logo_image = $dataClient->logo_image;
            $url_logo = "https://mquad.org/logo_image/" . $logo_image . "";
			
			$totRecords=0;
			$getUserRecords = mysqli_query($conn, "SELECT COUNT(survey_data_monitoring_id) AS totRecords FROM survey_data_monitoring WHERE user_id='".$user_id."' ");
			$userRecods = mysqli_fetch_object($getUserRecords);
			$totRecords = $userRecods->totRecords;
			
            if ($device_id == '') {
                
		    saveTokensToDatabase($data->user_id, $jwtToken, $refreshToken, $conn);
                mysqli_query($conn, "UPDATE users SET firebase_token='" . $firebase_token . "', device_id='" . $requested_device . "' WHERE user_id='" . $user_id . "' ");

                $getUserlog = "insert into user_log set user_id='" . $user_id . "',date_time='" . $date_time . "',client_id='" . $client_id . "',device_name='" . $device_name . "'";
                $dataUserlog = mysqli_query($conn, $getUserlog);
				$enccode=array("user_id" => $user_id, "sequence_id"=>$totRecords,"s3accessKey" => $s3accessKey,"s3secretKey" => $s3secretKey, "auth_token" => $jwtToken, "refresh_token"=> $refreshToken, "name" => $name, "user_name" => $user_name, "user_type_id" => $user_type_id, "email" => $email, "agency_code" => $agency_code, "logo_image" => $url_logo, "client_id" => $client_id, "device_name" => $device_name, "enc_key" => $enc_key);
                $arrResults = array("success" => 1, "message" => 'Login Successful.',"encrypted_data" =>$mcrypt->encrypt(json_encode($enccode)) );
                
                //$arrResults = array("success" => 1, "message" => 'Login Successful.',"user_id" => $user_id, "sequence_id"=>$totRecords,"s3accessKey" => $s3accessKey,"s3secretKey" => $s3secretKey, "auth_token" => $jwtToken, "refresh_token"=> $refreshToken, "name" => $name, "user_name" => $user_name, "user_type_id" => $user_type_id, "email" => $email, "agency_code" => $agency_code, "logo_image" => $url_logo, "client_id" => $client_id, "device_name" => $device_name, "enc_key" => $enc_key);
            } elseif ($requested_device == $device_id) {

		    saveTokensToDatabase($data->user_id, $jwtToken, $refreshToken, $conn);
                $getUserlog = "insert into user_log set user_id='" . $user_id . "',date_time='" . $date_time . "',client_id='" . $client_id . "',device_name='" . $device_name . "'";
                $dataUserlog = mysqli_query($conn, $getUserlog);
                mysqli_query($conn, "UPDATE users SET firebase_token='" . $firebase_token . "' WHERE user_id='" . $user_id . "' ");
				$enccode=array("user_id" => $user_id, "sequence_id"=>$totRecords,"s3accessKey" => $s3accessKey,"s3secretKey" => $s3secretKey, "auth_token" => $jwtToken, "refresh_token"=> $refreshToken, "name" => $name, "user_name" => $user_name, "user_type_id" => $user_type_id, "email" => $email, "agency_code" => $agency_code, "logo_image" => $url_logo, "client_id" => $client_id, "device_name" => $device_name, "enc_key" => $enc_key);
                $arrResults = array("success" => 1, "message" => 'Login Successful.',"encrypted_data" =>$mcrypt->encrypt(json_encode($enccode)) );
                
				//$arrResults = array("encrypted_data" =>$mcrypt->encrypt($enccode));
               
            } else {
                $arrResults = array("success" => 3, "message" => 'Already Logged In');
                //$arrResults=array("success"=>"0", "message"=>"Invalid  username or password");
            }
        } else {
            $arrResults = array("success" => "0", "message" => "Invalid  username or password");
        }
    } else {
        $arrResults = array("success" => "0", "message" => "Invalid  username or password");
    }
  $_SESSION['login_attempts'] += 1;	
 }
 else
	{
		$error="You have made ".$_SESSION['login_attempts']." unsuccessful attempt(s) out of 3 allowed attempts. Access has been blocked, please try after 15 Min";
		$arrResults = array("success" => "0", "message" => $error);
	}
    return $arrResults;
}
