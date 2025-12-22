<?php
header('Access-Control-Allow-Origin: *');
include('config.php');
require("PHPMailer-master/PHPMailer-master/src/PHPMailer.php");   
require("PHPMailer-master/PHPMailer-master/src/SMTP.php");
$_REQUEST = json_decode(file_get_contents('php://input'),true);
$data = forgotpassword($conn);
echo json_encode($data);
exit;

function forgotpassword($conn) 
{
	$message=array();
	$email = $_REQUEST['email'];

	$sql="select * from users where email='".$email."' and status='0' and del_action='N'";
	$sqlrun=mysqli_query($conn,$sql);
	$count=mysqli_num_rows($sqlrun);
	if($count>0)
	{
		$data=mysqli_fetch_object($sqlrun);
		$user_id=$data->user_id;
		$email=$data->email;
		$name=$data->name;
		$username=$data->username;
		
		$passvar= strtoupper(substr($name,0,2));
		$randnum=rand(10000,1000000);
		$orignal_password="$passvar"."$randnum";
		$password=password_hash($orignal_password, PASSWORD_DEFAULT);
	  
		if($email!='')
		{
			$passwd = "$passwd";
			$email="$email";
			$name="$name";
			$mail = new PHPMailer\PHPMailer\PHPMailer();
			$mail->IsSMTP(); // enable SMTP

			$mail->SMTPDebug = 0; // debugging: 1 = errors and messages, 2 = messages only
			$mail->SMTPAuth = true; // authentication enabled
			$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
			$mail->Host = "smtp.gmail.com";  
			$mail->Port = 465; // or 587
			$mail->IsHTML(true);
			$mail->Username = "info@mquad.org";
			$mail->Password = "yiqojdyfzjpfmyhx";
			$mail->SetFrom("info@mquad.org");
			$mail->Subject = "Password Reset";
			$mail->AddAddress($email,"$name");
			
			$mail->Body = "Dear ".$name.",<br>Your Username is: ".$username." and new password is: ".$orignal_password;
			
			if($mail->send()){
				$updatesql="update users set password='".$password."',orignal_password='".$orignal_password."' where user_id='".$user_id."'";
				$updatequery=mysqli_query($conn,$updatesql);	
				if($updatequery){
					$message = array('status' => 1, 'message' => ' Please check your registered Email ID for New Password');
				}else{
					$message = array('status' => 0, 'message' => 'Something went wrong!!');	
				}
			} else {
				 
				$message = array('status' => 0, 'message' => 'Mailer Error'. $mail->ErrorInfo);	
			}
		}
	}
	else
	{
	  $message=array("status"=>"0", "message"=>"Invalid username ");
	}
	return $message;
	mysqli_close($conn);
}

?>