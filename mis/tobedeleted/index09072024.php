<?php include_once('includes/config.php'); ?>
<?php include_once('includes/functions.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="" content="no-cache" />
  <meta http-equiv="Expires" content="-1" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Creative - Bootstrap 3 Responsive Admin Template">
  <meta name="author" content="GeeksLabs">
  <meta name="keyword" content="Creative, Dashboard, Admin, Template, Theme, Bootstrap, Responsive, Retina, Minimal">
  <link rel="shortcut icon" href="img/mquad-fav.png">
  <title>Login | MQUAD</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/bootstrap-theme.css" rel="stylesheet">
  <link href="css/elegant-icons-style.css" rel="stylesheet" />
  <link href="css/font-awesome.css" rel="stylesheet" />
  <link href="css/style.css" rel="stylesheet">
  <link href="css/style-responsive.css" rel="stylesheet" />
   <script src="js/jquery-1.8.3.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
</head>
<script type="text/javascript">
		function preventBack(){window.history.forward()}; 
		selTimeout("preventBack()", 0);
		window.onunload=function(){null;}
	</script>
<script type="text/javascript">
    window.history.forward();
    function noBack()
    {
        window.history.forward();
    }
</script>
<style>
.btn-sm{
	padding: 5px 10px !important;
	font-size: 12px !important;
	line-height: 1.5 !important;
	border-radius: 3px !important;
	margin-left: 70px;
}

</style>
<body class="login-img3-body" onLoad="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
  <div class="container">
    <?php 

    $error='';
	if(isset($_SESSION['locked'])){
		 $difference=time()-$_SESSION['locked'];
		// Check if 15 minutes have passed
		if ($difference > 900) { // 15 minutes in seconds
			unset($_SESSION['locked']);
			unset($_SESSION['login_attempts']);
			
		}
	}
    if(isset($_POST['username'])&& isset($_POST['password']))
    { 
		// echo "select user_id,status from users where username='".$_POST['username']."'";
		// die();
		$userssquery=mysqli_query($conn,"select user_id,status from users where username='".$_POST['username']."'");
		$getssusersql=mysqli_fetch_array($userssquery);
		$status=$getssusersql['status'];
		 $captcha=$_REQUEST['captcha'];
		 $captcha='123';
		//if($_SESSION['CODE']==$captcha){    
		if($captcha=='123'){  
			$result=login($_POST,$conn);
			if($status==0){
				if($result['status']==1)
				{ 
			      if($result['roles']==4)
				  {
				   echo "<script>location.href='https://mquad.org/sales/dashboard.php'</script>"; 
				  }
				  else {
				   echo "<script>location.href='dashboard_new.php'</script>";
				  }
				}
				else{
					$_SESSION['login_attempts'] += 1;
                    $error = "Please enter correct Username and Password.Your login attempt ".$_SESSION['login_attempts']." ended";
				}
			}
			
			else{
				$error="Your account is inactive.Please contact your admin!!";
			}
		}
		else
		{
			$error="Please check the captcha and try again!!";
		} 
     } 
    ?>
    <form action="" class="login-form" method="post" name="myrecaptcha" >
      <div class="login-wrap-left">
      	<div class="logo-wrap">
        	<img src="img/mquad-logo.png" style="height:70px;" />
        </div>
      	<h2>Welcome to MQUAD </h2>
        <p>Conduct surveys to collect quality data using simple mobile interface & export it to excel with ease. Store data in a reliable cloud infrastructure.</p>
	   </div>
		<div class="login-wrap">
			<h3>Login</h3>
			<div class="row">
				<div class="col-sm-12">
					<?php if($_SESSION['successerror']){ ?>
				   <div class="alert alert-success" role="alert">
					
					  <?=$_SESSION['successerror'];?>
					  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					  </button>
					</div>
				   <?php } if($_SESSION['login_attempts']>=3){
						$error='';
				   ?>
					<p style="color:red;">You have completed 3 attempts. Your account has been blocked, please try again after 15 minutes</p>
					<?php } 
					if($error!=''){
					?>
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
			  <input type="text" class="form-control" name="username" placeholder="Username" autofocus>
			</div>
			<div class="input-group">
			  <span class="input-group-addon"><i class="icon_key_alt"></i></span>
			  <input type="password" id="password_input" class="form-control" name="password" placeholder="Password">
			<span class="input-group-addon float-right" style="margin-top:-35px; margin-right:20px;"> <a href="javascript:void(0)" onclick="myFunction();"> <i class="fa fa-eye" aria-hidden="true"></i> </a> </span>
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
		
			<label class="checkbox">
				<span class="pull-right"><u> <a href="forgot_password.php"> Forgot Password?</a></u></span>
			</label>
			<?php 
			/* if($_SESSION['login_attempts']>=3){
				$_SESSION['locked']=time();
				echo "<p>Your account has been blocked, please try after 10 sec</p>";
			}else{ ?>
				<button class="btn btn-primary btn-lg btn-block" type="submit">Login</button>
			<?php	}  */?>
				<button class="btn btn-primary btn-lg btn-block" type="submit" <?php if($_SESSION['login_attempts']>=3){ $_SESSION['locked']=time(); echo "disabled"; }?>>Login</button> <br/>
				<p class="text-center" style="font-weight:700;color: #5f8899;">Don't have an account? <a href="../registration.php" style="margin-top:10px; text-align:center;color: #34aadc !important;">  Register Here </a></p>
		</div>
	</form>
  </div>
</body>
</html>
 
<script>
function myFunction() {
  var x = document.getElementById("password_input");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}
</script>
