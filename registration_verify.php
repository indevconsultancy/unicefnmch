<?php include_once('includes/config.php'); ?>
<?php //include_once('includes/functions.php'); ?>

<?php
/*
$email=$_SESSION['email'];
if(isset($_POST['submit'])){
	$otp=mysqli_real_escape_string($conn,$_POST['otp']);
	$otpsql = "SELECT user_id,email,otp,client_id,name FROM users WHERE otp='$otp'";
	$otpqry = mysqli_query($conn, $otpsql);
	$otpverify=mysqli_fetch_array($otpqry);
	$email=$otpverify['email'];
	$name=$otpverify['name'];
	 $otpget= $otpverify['otp'];
	if($otpget==$otp){
		
		$sspass = str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ123456789");
		$passwd = substr($sspass, 0,8);
		//$password=encryptPassword($passwd);  //Create SHA function in functions page
		$password=password_hash($passwd, PASSWORD_DEFAULT);
		
			$updateqry = mysqli_query($conn,"UPDATE clients SET status='1' WHERE id='".$otpverify['client_id']."' ");
			$updateqry = mysqli_query($conn,"UPDATE users SET password='".$password."',status='0' WHERE client_id='".$otpverify['client_id']."' AND user_id='".$otpverify['user_id']."'");
			if($updateqry)
			{
				$mailto = $email;
				if($mailto!=''){
					$passwd = $passwd;
					$name = $name;
					$message_all = '';
					//$message_all= "Dear " . $name .",<br>";
					$message_all.='<td width="100%" cellpadding="0" cellspacing="0" style="box-sizing: border-box; font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif"; background-color: #edf2f7; border-bottom: 1px solid #edf2f7; border-top: 1px solid #edf2f7; margin: 0; padding: 0; width: 100%">
						<table align="center" width="570" cellpadding="0" cellspacing="0" style="box-sizing: border-box; font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif"; background-color: #ffffff; border-color: #e8e5ef; border-radius: 2px; border-width: 1px; margin: 0 auto; padding: 0; width: 570px">
							<tbody>
								<tr>
									<td style="box-sizing: border-box; font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif; max-width: 100vw; padding: 32px">
									  <h4 style="box-sizing: border-box; font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif; color: #3d4852; font-size: 18px; font-weight: bold; margin-top: 0; text-align: left">Dear '.$name.',</h4>
									  <p style="box-sizing: border-box; font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left">Thank you for verification of your email. Welcome to MQUAD. <br>Login with the username and password given below.</p>
										<table style="font-family: Arial; text-align: left; font-size: 13px" cellpadding="0" cellspacing="0" width="520px">
											<thead>
												<tr>
													<th colspan="2" style="padding: 15px; background: #449a97; color: #fff; border: 1px solid #449a97">Your login credentials are as follows.</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<th style="border: 1px solid #ccc; border-right: none; border-bottom: none; padding: 8px 15px" width="80px">
														URL :
													</th>
													
													<td style="border: 1px solid #ccc; border-left: none; border-bottom: none; padding: 8px 15px">
														<a href="https://mquad.org/mis/index.php" target="_blank" rel="noreferrer">https://mquad.org/mis/index.php</a>
													</td>
												</tr>
												<tr>
													<th style="border: 1px solid #ccc; border-right: none; border-bottom: none; padding: 8px 15px" width="80px">
														Username :
													</th>
													<td style="border: 1px solid #ccc; border-left: none; border-bottom: none; padding: 8px 15px">
														'.$email.'
													</td>
												</tr>
												<tr>
													<th style="border: 1px solid #ccc; border-right: none; padding: 8px 15px">
														Password :
													</th>
													<td style="border: 1px solid #ccc; border-left: none; padding: 8px 15px">
														'.$passwd.'
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
					$subject=base64_encode("MQUAD Login Details");
					$message=base64_encode($txt);
					//$sendmail=send_mail_function($mailto,$message,$subject);
					if($sendmail['status']==1){
						echo "<script>window.location.href='welcome.php'</script>";
					}
					 else {
						$error='something went wrong!';
					} 
				}
				
			}else{
				$error='Something went wrong!';
			}
	}else{
		
		$error='Please enter correct OTP';
	}
}
*/
?>

<!DOCTYPE html>
<html lang="en">
   <meta http-equiv="content-type" content="text/html;charset=utf-8" />
   <head>
      <link rel="icon" type="image/png" href="../favicon.png">
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="description" content="Get in touch with MQUAD if you need support, on-on-one training or just want to leave feedback.  Contacting MQUAD is just an email away." />
      <title>Registration verify | MQUAD</title>
      <link href="Content/CSS/bootstrap/bootstrap.css" rel="stylesheet" />
      <link href="Content/CSS/Site.css" rel="stylesheet" />
      <script src="Content/Scripts/modernizr-2.6.2.js"></script>
      <script async src="https://www.googletagmanager.com/gtag/js?id=UA-122580788-1"></script>
      <script>
         window.dataLayer = window.dataLayer || [];
         function gtag(){dataLayer.push(arguments);}
         gtag('js', new Date());
         
         gtag('config', 'UA-122580788-1');
      </script>
   </head>
   <style>
   input, select, textarea {
    max-width: 100%;
	}
	.form-group {
    margin-bottom: 70px;
    margin-top: 15px;
}
   </style>
   <?php include('includes/header.php');?>
   <body>
     
      <div class="contactPageOuter">
         <div class="contactPageMain">
            <div class="header">
               <div class="headerImage">
                  <img src="Content/Images/otp.jpeg" alt="MQUAD prides itself on outstanding customer service." title="Get in Touch with MQUAD" />
               </div>
               <div class="headerTitle">
                  <h2>OTP Verification</h2>
				  <h3>Authentication Required</h3>
               </div>
               <div class="headerText">
                  <p>We have sent a One Time Password (OTP) to the Email ID. Please enter to complete the verification.</p>
               </div>
            </div>
			<div class="row">
			   <div class="col-sm-12">
				   <?php if($error!=''){ ?>
				   <div class="alert alert-danger" role="alert">
					  <?=$error;?>
					  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					  </button>
					</div>
				   <?php } ?>
			   </div>
			</div>	
            <div class="mainContent">
               <div>
					<div>
                     <form action="" id="myForm" method="POST" >
					 <div class="row">
					 	<div class="col-lg-6 col-md-12 col-sm-12 col-lg-offset-3">
							   <label for="txtOTP">Enter OTP</label>
							   <input type="number" class="form-control" name="otp" id="otp" placeholder="Enter OTP"/>
						</div>
						</div>
						<div class="row">						
						<div class="col-lg-6 mt-4 col-md-12 col-sm-12 col-lg-offset-3">
							<div class="form-group">
								<button type="submit" class="btn btn-primary btnShow pull-right" name="submit">Verify</button>
							</div>
						</div>
						</div>
						
                     </form>
                  </div>
               </div>
              
            </div>
         </div>
      </div>
   <?php include('includes/footer.php');?>
 
   </body>
  </html>