<?php
 include('includes/config.php');
 include('includes/functions.php');
 
 if(isset($_POST['email'])){
	$email=sanitizeInput($_POST['email'],$conn);
	$check_username = mysqli_query($conn,"SELECT username FROM users WHERE username='".$email."'"); 
    if (mysqli_num_rows($check_username)>0) {
        $result = array("status"=>2,"message"=>"Email is already registered");                                                                        
    } else {
		$getotp=(rand(0,1000000));
		$_SESSION['getotp']=$getotp;
		if(!empty($email)){
			
			$mailto=$email;
			$message_all = '';
			//$message_all= "Dear User,<br>";
			//$message_all.= "Thank you for registering on MQUAD.<br>Request you to kindly validate your account by entering the OTP shared through this email for completing the registration.Your OTP is ".$getotp;
			$message_all.= '<td width="100%" cellpadding="0" cellspacing="0" style="box-sizing: border-box; font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif"; background-color: #edf2f7; border-bottom: 1px solid #edf2f7; border-top: 1px solid #edf2f7; margin: 0; padding: 0; width: 100%">
								<table align="center" width="570" cellpadding="0" cellspacing="0" style="box-sizing: border-box; font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif"; background-color: #ffffff; border-color: #e8e5ef; border-radius: 2px; border-width: 1px; margin: 0 auto; padding: 0; width: 570px">
									<tbody>
										<tr>
											<td style="box-sizing: border-box; font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif; max-width: 100vw; padding: 32px">
											  <p style="box-sizing: border-box; font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left">Thank you for registering on MQUAD.<br>Request you to kindly validate your account by entering the OTP shared through this email for completing the registration.</p>
												<table style="font-family: Arial; text-align: left; font-size: 13px" cellpadding="0" cellspacing="0" width="520px">
													<thead>
														<tr>
															<th colspan="2" style="padding: 15px; background: #449a97; color: #fff; border: 1px solid #449a97">OTP Verification</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<th style="border: 1px solid #ccc; border-right: none; padding: 8px 15px">
																OTP :
															</th>
															<td style="border: 1px solid #ccc; border-left: none; padding: 8px 15px">
																' . $getotp . '
															</td>
														</tr>
													</tbody>
												</table>
												  <table align="center" width="100%" cellpadding="0" cellspacing="0" style="box-sizing: border-box; font-family: -apple-system,BlinkMacSystemFont,Roboto,Helvetica,Arial,sans-serif; margin: 30px auto; padding: 0; text-align: center; width: 100%">
													<tbody>
													  <tr>
														<td align="center" style="box-sizing: border-box; font-family: -apple-system,BlinkMacSystemFont,Roboto,Helvetica,Arial,sans-serif">
														  <table width="100%" border="0" cellpadding="0" cellspacing="0" style="box-sizing: border-box; font-family: -apple-system,BlinkMacSystemFont,Roboto,Helvetica,Arial,sans-serif">
															<tbody>
															  <tr>
																<td align="center" style="box-sizing: border-box; font-family: -apple-system,BlinkMacSystemFont,Roboto,Helvetica,Arial,sans-serif">
																  <table border="0" cellpadding="0" cellspacing="0" style="box-sizing: border-box; font-family: -apple-system,BlinkMacSystemFont">
																	  <tr>
																		<td style="box-sizing: border-box; font-family: -apple-system,BlinkMacSystemFont,Roboto,Helvetica,Arial,sans-serif">
																		</td>
																	  </tr>
																	</tbody>
																  </table>
																</td>
															  </tr>
															</tbody>
														  </table>
														</td>
													  </tr>
													</tbody>
												  </table>
												</tbody>
												</table>
											</td>	
										</tr>
									</tbody>  
								</table>	
							</td>';
			
			$txt = $message_all;
			$subject=base64_encode(" MQUAD Registration Verification.");
			$message=base64_encode($txt);
			
			$sendmail=send_mail_function($mailto,$message,$subject);
			
			if($sendmail['status']==1){
				
				$result = array("status"=>1,"message"=>"OTP send successfully");
				//echo "We have sent a One Time Password (OTP) to the Email ID. Please enter to complete the verification.";
			}
			 else {
				$result = array("status"=>0,"message"=>"OTP not send");
			} 
			
			
		}
	}
	echo json_encode($result);
 }
 
 if(isset($_POST['emailotp'])){
	$emailotp=sanitizeInput($_POST['emailotp'],$conn); 
	$_SESSION['verify']='verify';
	if($emailotp==$_SESSION['getotp']){
		$result = array("status"=>1,"verify"=>"verify","message"=>"OTP Match Successfully");
		
	}else{
		$result = array("status"=>0,"message"=>"OTP not Match");
	}
	echo json_encode($result); 
 }
 
 ?>
