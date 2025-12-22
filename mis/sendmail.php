<?php
// echo phpinfo();
// die;
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

require("PHPMailer-master/PHPMailer-master/src/PHPMailer.php");   
require("PHPMailer-master/PHPMailer-master/src/SMTP.php");

$mail = new PHPMailer\PHPMailer\PHPMailer();
$mail->IsSMTP(); // enable SMTP


$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
$mail->SMTPAuth = true; // authentication enabled
$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
$mail->Host = "smtp.gmail.com";  
$mail->Port = 465; // or 587
$mail->IsHTML(true);
// $mail->Username = "info@mquad.org";
$mail->Username = "ss.snm1503@gmail.com";
// $mail->Password = "mdoasnhyshvkzkbo";
$mail->Password = "cusfliykujpayzel";
$mail->SetFrom("ss.snm1503@gmail.com");
$mail->Subject = "SSS Test Mail MQUAD";
$mail->Body = "This mail is only for testing.";
$mail->AddAddress("satyendrasinghbca777@gmail.com"); 
//$mail->AddAddress("satyendra.singh@indevconsultancy.com"); 

// $mail->AddAddress("khushboo@indevconsultancy.in"); 
if(!$mail->Send()) {
	echo "Mailer Error: " . $mail->ErrorInfo;
 } else {
	echo "Message has been sent";
 }
/*
$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
$mail->SMTPAuth = true; // authentication enabled
$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
$mail->Host = "smtp.gmail.com";  
$mail->Port = 465; // or 587
$mail->IsHTML(true);
$mail->Username = "ss.snm1503@gmail.com";
$mail->Password = "xkrgqfsykcoafpyg";
$mail->SetFrom("ss.snm1503@gmail.com");
$mail->Subject = "SSS Test Mail";
$mail->Body = "This mail is only for testing.";
$mail->AddAddress("khushboo@indevconsultancy.in"); 
//$mail->AddAddress("satyendra.singh@indevconsultancy.com"); 
 if(!$mail->Send()) {
	echo "Mailer Error: " . $mail->ErrorInfo;
 } else {
	echo "Message has been sent";
 }
*/ 
 ?>