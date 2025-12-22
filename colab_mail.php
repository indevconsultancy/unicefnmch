<?php
header('Access-Control-Allow-Origin: *');
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
$_REQUEST = json_decode(file_get_contents('php://input'),true);
$data = send_mail();
echo json_encode($data);
exit;
function send_mail()
{

	$mail_to=isset($_REQUEST['email_id'])? $_REQUEST['email_id']:'';
    $subject=isset($_REQUEST['subject'])? $_REQUEST['subject']:'';
    $message=isset($_REQUEST['message'])? $_REQUEST['message']:'';
 
	require("PHPMailerMaster/PHPMailer-master/src/PHPMailer.php");   
	require("PHPMailerMaster/PHPMailer-master/src/SMTP.php");

	$mail = new PHPMailer\PHPMailer\PHPMailer();
	$mail->IsSMTP(); // enable SMTP

	$mail->SMTPDebug = 0; // debugging: 1 = errors and messages, 2 = messages only
	$mail->SMTPAuth = true; // authentication enabled
	$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
	$mail->CharSet = 'UTF-8';
	$mail->Host = "smtp.mail.us-east-1.awsapps.com";  
	$mail->Port = 465; // or 587
	$mail->IsHTML(true);
	$mail->Username = "learningnetwork@ictforag.com";
	//$mail->Password = "qdhvobecuxegyxea";
	$mail->Password = "LyW5+m;8r]a#ev!H";
	$mail->SetFrom("learningnetwork@ictforag.com");
	$mail->Subject = $subject;
	$mail->Body = base64_decode($message);
	//$mail->AddAddress("satyendrasinghbca777@gmail.com"); 
	//$mail->AddAddress("satyendra.singh@indevconsultancy.com"); 

	$mail->AddAddress($mail_to); 
	 if($mail->send()){
			
			$arrResults = array('status' => 1, 'message' => 'Send Mail Successfully..');
		}
	   
		else{

	  
			$arrResults=array("success"=>"0","message"=>"Something went wrong..!!");
		}

		return $arrResults; 
	}

 ?>