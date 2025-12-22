<?php
function send_mail_function($mailto,$message,$subject) {
		$arrResultss = array();
		$email = $mailto;
	
		$subject = base64_decode($subject);
		$message = base64_decode($message);
		
		if ( $email != '') {
			$txt = str_replace('\.','.',$message);
			$email_id_config="info11@mquad.org";
			//$email_id_config="ss.snm1503@gmail.com";
			$mailhost="smtp.gmail.com";
			$email_pass_config="mdoasnhyshvkzkbo";
			//$email_pass_config="cusfliykujpayzel";
			$setfrom="info111@mquad.org";
			//$setfrom="ss.snm1503@gmail.com";
			$mailport=465;
			$smtpSecure="ssl";
			
			$mail = new PHPMailer\PHPMailer\PHPMailer();
			$mail->IsSMTP(); // enable SMTP
			$mail->SMTPDebug = 0;
			$mail->SMTPAuth = true; 
		
			$mail->Host = $mailhost;  
			$mail->Port = $mailport;
			$mail->IsHTML(true);
			$mail->Username=$email_id_config;
			$mail->Password=$email_pass_config;
			$mail->SMTPSecure = $smtpSecure;
			$mail->SetFrom($setfrom,"MQUAD");
			$mail->AddAddress($email); 
			$mail->Subject = $subject;
			$mail->Body    = $txt;
			$mailsendto =$mail->send();
			if($mailsendto) {
				$arrResultss=array("status"=>1,"message"=>"Success","msg"=>$txt);
			}
			else{
				$arrResultss=array("status"=>0,"message"=>"Failed");
			}
			return $arrResultss;
		}
		
	}
	
function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function sanitizeInput($data, $conn) {
    // Trim whitespace from the beginning and end
    $data = trim($data);

    // Remove backslashes if magic quotes are enabled
    if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
        $data = stripslashes($data);
    }

    // Remove all HTML tags
    $data = strip_tags($data);

    // Remove JavaScript-related patterns and common functions
    $patterns = array(
        '/<\s*script\b[^>]*>(.*?)<\/\s*script>/is',     // Remove <script> tags
        '/<\s*iframe\b[^>]*>(.*?)<\/\s*iframe>/is',     // Remove <iframe> tags
        '/<\s*style\b[^>]*>(.*?)<\/\s*style>/is',       // Remove <style> tags
        '/on[a-z]+\s*=\s*[^"\'\s]+/is',                 // Remove inline event handlers
        '/javascript:[^"\']*/is',                        // Remove javascript: protocols
        '/<\s*img\b[^>]*\s*onerror\s*=\s*[^>]*>/is',     // Remove onerror attributes in <img> tags
        '/<[^>]+(on\w+|style|javascript:)[^>]*>/is',    // Remove tags with dangerous attributes
        '/(?<![\w\d])\b(?:on\w+|javascript|data)\b[\w\d\s]*=[^\s"\'<>]+/is', // Remove more patterns
        '/\b(?:alert|confirm|prompt|eval|exec|document\.write|window\.open|location\.href|console\.log|setInterval|setTimeout)\s*\([^)]*\)/is' // Remove common JS functions
    );
    $data = preg_replace($patterns, '', $data);

    // Remove any remaining script-like patterns
     $data = preg_replace('/\b(?:alert|confirm|prompt|eval|exec|document\.write|window\.open|location\.href|console\.log|setInterval|setTimeout)\s*\([^)]*\)/is', '', $data);

    // Remove specific characters
    $data = str_replace(array('%', '&', '+', '='), '', $data);

    // Convert special characters to HTML entities to prevent XSS
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

    // Escape special characters for use in an SQL query
    $data = mysqli_real_escape_string($conn, $data);

    return $data;
}


?>	