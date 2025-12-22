<?php include_once('includes/config.php'); ?>
<?php define("title","Forgot Password | MQUAD");?>
<?php include_once('includes/functions.php'); ?>


<?php
$successerror='';
$error='';
if(isset($_REQUEST['forgot_password']) ){
		$captcha=$_REQUEST['captcha'];
	if($_SESSION['CODE']==$captcha){ 
		$user_name=$_POST['username'];
		$getusername=mysqli_query($conn,"SELECT username,name,user_id from users where username='".$user_name."'");
		$countuser=mysqli_num_rows($getusername);
		$sqluser=mysqli_fetch_array($getusername);
		$username=$sqluser['username'];
		$user_id=$sqluser['user_id'];
		$name=$sqluser['name'];
		
		$sspass = str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ123456789");
		$passwd = substr($sspass, 0,8);
		$password=password_hash($passwd, PASSWORD_DEFAULT);
		if($countuser>0){
			
			$updateqry = mysqli_query($conn,"UPDATE users SET password='".$password."' WHERE  user_id='".$user_id."'");
				if($updateqry)
				{
					$mailto=$username;
					if($mailto!=''){
						$email=$username;
						$name=$name;
						$passwd=$passwd;
					
						$message_all = '';
						$message_all.='<td width="100%" cellpadding="0" cellspacing="0" style="box-sizing: border-box; font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif"; background-color: #edf2f7; border-bottom: 1px solid #edf2f7; border-top: 1px solid #edf2f7; margin: 0; padding: 0; width: 100%">
						<table align="center" width="570" cellpadding="0" cellspacing="0" style="box-sizing: border-box; font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif"; background-color: #ffffff; border-color: #e8e5ef; border-radius: 2px; border-width: 1px; margin: 0 auto; padding: 0; width: 570px">
							<tbody>
								<tr>
									<td style="box-sizing: border-box; font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif; max-width: 100vw; padding: 32px">
									  <h4 style="box-sizing: border-box; font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif; color: #3d4852; font-size: 18px; font-weight: bold; margin-top: 0; text-align: left">Dear '.$name.',</h4>
									  <p style="box-sizing: border-box; font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left">Login with the username and new password given below.</p>
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
						$subject=base64_encode("Password reset successful");
						$message=base64_encode($txt);
						$sendmail=send_mail_function($mailto,$message,$subject);
						if($sendmail['status']==1){
						$_SESSION['successerror']="Your password has ben successfully reset. Please check your registered Email ID for new Password";
						echo "<script>window.location.href='index.php'</script>";
						}
						 else {
							$error="Something went wrong!!";
						} 
					} else{
					$error="Please enter your Email ID!!";
				}
					
				} else{
					$error="Something went wrong!!";
				}
				
		}elseif($countuser==0 && $user_name!=$username){
			$error="Your account is not registered !!";
		}else{
			$error="Please enter your Email ID!!";
		}
	}else{
		$error="Please check the captcha and try again!!";
	}		
}	
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Creative - Bootstrap 3 Responsive Admin Template">
  <meta name="author" content="GeeksLabs">
  <meta name="keyword" content="Creative, Dashboard, Admin, Template, Theme, Bootstrap, Responsive, Retina, Minimal">
  <link rel="shortcut icon" href="img/mquad-fav.png">
  <title>Forgot Password | MQUAD</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/bootstrap-theme.css" rel="stylesheet">
  <link href="css/elegant-icons-style.css" rel="stylesheet" />
  <link href="css/font-awesome.css" rel="stylesheet" />
  <link href="css/style.css" rel="stylesheet">
  <link href="css/style-responsive.css" rel="stylesheet" />
<script src="js/jquery-1.8.3.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
</head>
<style>
.btn-sm{
	padding: 5px 10px !important;
	font-size: 12px !important;
	line-height: 1.5 !important;
	border-radius: 3px !important;
	margin-left: 70px;
}

</style>
<body class="login-img3-body">
  <div class="container">
    <form class="login-form" method="post" >
      <div class="login-wrap-left">
      	<div class="logo-wrap">
        	<img src="img/mquad-logo.png" style="height:70px;" />
        </div>
      	<h2>Forgot Password </h2>
        <p>If you did not request for password change, please proceed by clicking the Cancel button.</p>
		<a href="index.php" class="btn btn-primary btn-md" type="submit" style="color:white !important; background-color:#8cc63f;">Login</a>
      </div>
      <div class="login-wrap">
      	<h3>Reset Password</h3>
        
        <div class="row">
		  <div class="col-sm-12">
				<?php if($successerror!=''){ ?>
			   <div class="alert alert-success" role="alert">
				  <?=$successerror;?>
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				  </button>
				</div>
			   <?php } ?>
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
        <div class="input-group">
			<span class="input-group-addon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
          <input type="text" class="form-control" name="username" placeholder="Enter your Email ID" autofocus>
		</div>
		<div class="input-group">
			<p><img src="captcha.php" id="ref_captcha" width="160" height="40" border="1"/>
				<small style=><a href="#" style="color:white !important;" class="btn btn-primary btn-sm " onclick="
				  document.getElementById('ref_captcha').src = 'captcha.php?' + Math.random();
				  document.getElementById('captcha').value = '';
				  return false;"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</a>
				</small>
			</p>
		</div>	
		<div class="input-group">
			<span class="input-group-addon"></span>
			<input type="text" class="form-control" id="captcha" name="captcha"  placeholder="Enter Captcha">
		</div>
		<button class="btn btn-primary btn-lg btn-block" type="submit" name="forgot_password">Submit</button>
	</form>
  </div>
</body>
</html>
