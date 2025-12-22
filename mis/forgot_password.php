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
		
			if($countuser>0){
				$mailto=$username;
				if($mailto!=''){
					$email=$username;
					$name=$name;
					$timestamp = time();
					$user_id=encrypt_url($user_id);  //function create i config page
					$timestamps=encrypt_url($timestamp);  //function create i config page
					
					
					$reset_url=base_url()."reset_password.php?uid=$user_id&timestamp=$timestamps";
				
					$message_all = "Dear " . $name . ",<br>";
					$message_all .= "We received a request to reset your password. Click the link below to create a new password:<br/>" . "Reset Link: <a href='" . $reset_url . "' target='_blank' rel='noreferrer'>" . $reset_url . "</a>";

					$txt = $message_all;
					$subject=base64_encode("Reset Your Password");
					$message=base64_encode($txt);
					$sendmail=send_mail_function($mailto,$message,$subject);
					if($sendmail['status']==1){
					$_SESSION['successerror']="Your request has been successfully submitted. Please check your registered email address for a link to create a new password.";
					echo "<script>window.location.href='index.php'</script>";
					}
					 else {
						$error="Something went wrong!!";
					} 
				} else{
				$error="Please enter your Email ID!!";
			}
					
		}elseif($countuser==0 && $user_name!=$username){
			$error="If you have an account, please check your email to reset your password.";
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
          <input type="text" class="form-control" name="username" placeholder="Enter your Email ID" value="<?=@$_POST['username']?>" autofocus>
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
