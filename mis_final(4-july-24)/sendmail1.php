<?php
include( 'PHPMailer/PHPMailer/PHPMailerAutoload.php' );

$arrResults = array();
//////////////////////
//   $first_name='Raju';
//   $middle_name='Kumar';
//   $last_name='Test';
//   $mobile_number='8467977713';
//   $email_id='kumarraju109@gmail.com';
//   $email=$email_id;

$email = 'kumarraju109@gmail.com';
$subject = 'Test Email';
$message = 'Test Mail <br><br>Warm Regards,<br> UAC Portal Administrator <br><br> <img src="https://portal.uacuae.com/images/logo.png" alt="logo" class="img-fluid"/>';
if ( $email != '' ) {
    //   $ss = str_shuffle( "123456789" );
    //   $otp = substr( $ss, 0, 5 );

    //   $name = $first_name;
    //   $event_all = '';
    //   $event_all = "Dear   " . $first_name ." ". $last_name . ",<br><br>";
    //   $event_all .= 'Thank you for registering on Skill Assessment Learning Platform. <br> <br>The current Email OTP for your profile is: ' . $otp . '<br><br>Thank you,<br> Team UAC <br><br> <img src="http://salp.indevconsultancy.in/assessement/prototype/images/logo.png" alt="logo" class="img-fluid"/>';
    //$event_all .= 'Your Email OTP for SALP Registration is: ' . $otp . '<br><br>Thank you, <br>Team SALP ';
    // $txt = $message;
    $message=urldecode($message);
    $txt = str_replace('\.','.',$message);
    ///////////////////////////
    // $email_id_config="indevjobs.org@gmail.com";
    $email_id_config="no-reply@barcindia.co.in";
    $mailhost="smtp.office365.com";
    $email_pass_config="h3DT*7=y";
    // $email_pass_config="1*c53@s$(~21";
    $setfrom="no-reply@barcindia.co.in";
    $mailport="587";
    $smtpSecure="ssl";
    date_default_timezone_set('Etc/UTC');
    $mail = new PHPMailer;                          // Passing `true` enables exceptions
    $mail->SMTPDebug = 0;                           // Enable verbose debug output
    // $mail->isSMTP();                             // Set mailer to use SMTP
    $mail->Debugoutput = 'html';
    $mail->Host = $mailhost;                        // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                         // Enable SMTP authentication
    $mail->Username = $email_id_config;             // SMTP username
    $mail->Password = $email_pass_config;           // SMTP password
    $mail->SMTPSecure = $smtpSecure;                // Enable TLS encryption, `ssl` also accepted
    $mail->Port = $mailport;                        // TCP port to connect to
    $mail->setFrom($setfrom,"BARC");
    $mail->addAddress($email); 
    $mail->isHTML(true);                            // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $txt;
    $mailsendto =$mail->send();
    // print_r($mail->ErrorInfo);
    // die;
    //////////////////////////////
    if ($mailsendto) {
        // echo "Success1";
        $arrResults=array("status"=>1,"message"=>"Success","msg"=>$txt,"mail"=>$mailsendto);
    }
    else {
        // echo "Failed";
        $arrResults=array("status"=>0,"message"=>"Failed","mail"=>$mailsendto);
    }
	echo json_encode($arrResults);
}
?>
<?php
/*$email = $_REQUEST['mail_to'];
// $subject = $_REQUEST['subject'];
// $message = $_REQUEST['message'];
// $mail_to = base64_decode($_REQUEST['mail_to']);
$subject = base64_decode($_REQUEST['subject']);
$message = base64_decode($_REQUEST['message']).'<br><br>Warm Regards,<br> UAC Portal Administrator <br><br> <img src="https://portal.uacuae.com/images/logo.png" alt="logo" class="img-fluid"/>';
include('PHPMailer/PHPMailer/PHPMailerAutoload.php');
//$email="satyendrasinghbca777@gmail.com";
if($email!=''){
   
   $txt = $message;//str_replace('\.','.',$message);
    //echo "<pre>";
    //   print_r($txt);
    //     die;
      
      
    ///////////////////////////
    $email_id_config="indevjobs.org@gmail.com";
    $mailhost="smtp.gmail.com";
    $email_pass_config="1*c53@s$(~21";
    $setfrom="admin@uacuae.com";
    $mailport="465";
    $smtpSecure="ssl";
    date_default_timezone_set('Etc/UTC');
    $mail = new PHPMailer;                         // Passing `true` enables exceptions
    $mail->SMTPDebug = 0;                                 // Enable verbose debug output
    //        $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Debugoutput = 'html';
    $mail->Host = $mailhost;                   // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = $email_id_config;             // SMTP username
    $mail->Password = $email_pass_config;                     // SMTP password
    $mail->SMTPSecure = $smtpSecure;                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = $mailport;                                     // TCP port to connect to
    $mail->setFrom($setfrom,"UAC");
    $mail->addAddress($email); 
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $subject;//"OTP for Skill Assessment Learning Platform Registration";
    $mail->Body    = $txt;
    $mailsendto =$mail->send();
    //////////////////////////////
    if($mailsendto) {
        //   echo "Success";
        $murl="https://portal.uacuae.com/forgot_pass.php?status=success";
        header("Location: $murl");
    }else{
        echo "failed";
    }
}  */ 
?>
