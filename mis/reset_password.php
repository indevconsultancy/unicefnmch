<?php include_once('includes/config.php'); ?>
<?php define("title","Reset Password | MQUAD");?>
<?php include_once('includes/functions.php'); ?>

<?php
$timestamp =decrypt_url($_GET['timestamp']); ;
$current_time = time(); // Current time

$password = $conformpassword = "";
$new_passwordErr = $confirm_passwordErr = "";
$error = '';
$success = '';

// Function to sanitize input
function test_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if (isset($_POST['change_password'])) {
    
    if (empty($_POST["password"])) {
       $new_passwordErr = "Please enter your password";
	  
    } else {
        $password = test_input($_POST["password"]);
        if (strlen($password) <= 6) {
            $new_passwordErr = "Your Password Must Contain At Least 6 Characters!";
        } elseif (!preg_match("#[0-9]+#", $password)) {
            $new_passwordErr = "Your Password Must Contain At Least 1 Number!";
        } elseif (!preg_match("#[A-Z]+#", $password)) {
            $new_passwordErr = "Your Password Must Contain At Least 1 Capital Letter!";
        } elseif (!preg_match("#[a-z]+#", $password)) {
            $new_passwordErr = "Your Password Must Contain At Least 1 Lowercase Letter!";
        } elseif (!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $password)) {
            $new_passwordErr = "Your Password Must Contain At Least 1 Special Character!";
        }
    }

    // Validate confirm password
    if (empty($_POST["conformpassword"])) {
        $confirm_passwordErr = "Please enter your confirm password";
    } else {
        $conformpassword = test_input($_POST["conformpassword"]);
        if (strlen($conformpassword) <= 6) {
            $confirm_passwordErr = "Your Password Must Contain At Least 6 Characters!";
        } elseif (!preg_match("#[0-9]+#", $conformpassword)) {
            $confirm_passwordErr = "Your Password Must Contain At Least 1 Number!";
        } elseif (!preg_match("#[A-Z]+#", $conformpassword)) {
            $confirm_passwordErr = "Your Password Must Contain At Least 1 Capital Letter!";
        } elseif (!preg_match("#[a-z]+#", $conformpassword)) {
            $confirm_passwordErr = "Your Password Must Contain At Least 1 Lowercase Letter!";
        } elseif (!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $conformpassword)) {
            $confirm_passwordErr = "Your Password Must Contain At Least 1 Special Character!";
        }
    }

    if (empty($new_passwordErr) && empty($confirm_passwordErr)) {
        
        if ($_SESSION['CODE'] == $_POST['captcha']) {
			
			// $timestamp = $_GET['timestamp'];
			// $current_time = time(); // Current time
			
			 // Check if the link has expired (30 seconds expiration time)
			//if (($current_time - $timestamp) <= 30) {
				
				$uid = $_GET['uid'];
				$userId = decrypt_url($uid); // create function in config page
				$newpass = mysqli_real_escape_string($conn, $password);
				$confirmpass = mysqli_real_escape_string($conn, $conformpassword);

				// Check if new password and confirm password match
				if ($newpass !== $confirmpass) {
					$error = "New and confirm password do not match!";
				} else {
					$newpassword = password_hash($newpass, PASSWORD_DEFAULT);

					$getusername = mysqli_query($conn, "SELECT username, name FROM users WHERE user_id = '$userId'");
					if (mysqli_num_rows($getusername) > 0) {
						$sqluser = mysqli_fetch_assoc($getusername);
						$username = $sqluser['username'];
						$name = $sqluser['name'];

						$mailto = $username;
						if ($mailto != '') {
							$message_all = "Dear " . $name . ",<br>";
							$message_all .= "Your password has been successfully reset. Please use your new password to log in.";
							$txt = $message_all;
							$subject = base64_encode("Your password has been successfully reset");
							$message = base64_encode($txt);
							$sendmail = send_mail_function($mailto, $message, $subject);

							if ($sendmail['status'] == 1) {
								// Update password in database
								$updatepassqry = mysqli_query($conn, "UPDATE users SET password = '$newpassword' WHERE user_id = '$userId'");
								if ($updatepassqry) {
									$_SESSION['successerror'] = "Your password has been successfully created. Please log in with the same username and password.";
									echo "<script>window.location.href='index.php'</script>";
								} else {
									$error = "Password not updated. Please try again!";
								}
							} else {
								$error = "Something went wrong with sending the email!";
							}
						} else {
							$error = "Email ID does not exist!";
						}
					} else {
						$error = "Email ID not exit!";
					}
				}
			// }else {
				
				// $error="The reset link has expired. Please request a new password reset.";
			// }	
        } else {
            $error = "Please check the captcha and try again!";
        }
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
  <title>Reset Password | MQUAD</title>
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
.errormsg {
		color: red;
	}
	.error {
		color: red;
	}
</style>
<?php if (($current_time - $timestamp) <= 1800) {  //30 minutes valid
?>  
<body class="login-img3-body">

  <div class="container">
  
    <form class="login-form" method="post" >
      <div class="login-wrap-left">
      	<div class="logo-wrap">
			
        	<img src="img/mquad-logo.png" style="height:70px;" />
        </div>
      	<h2>Crete New Password </h2>
        <p>If you did not request for password change, please proceed by clicking the Cancel button.</p>
		<a href="index.php" class="btn btn-primary btn-md" type="submit" style="color:white !important; background-color:#8cc63f;">Cancel</a>
      </div>
      <div class="login-wrap">
      	<h3>Create new Password</h3>
        <span>Please create your Password Must Contain At Least 6 Characters, 1 Capital Letter,1 Lowercase Letter,1 number and 1 special character.</span><br><br>
        <div class="row">
			<div class="col-sm-12">
				
				<?php if ($error != '') { ?>
					<div class="alert alert-danger" role="alert">
						<?= $error; ?>
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
				<?php } ?>
			</div>
		</div>
        
		<div class="input-group">
			<span class="input-group-addon"><i class="icon_key_alt"></i></span>
			<input type="password" id="password" class="form-control" name="password" placeholder="Enter New Password" autocomplete="new-password" value="<?= htmlspecialchars(@$_POST['password']) ?>">
			<span class="input-group-addon float-right">
				<a href="javascript:void(0)" onclick="togglePasswordVisibility('password');">
					<i class="fa fa-eye" aria-hidden="true"></i>
				</a>
			</span>
		</div>
		<span class="errormsg"><?= htmlspecialchars($new_passwordErr) ?></span>
		<div class="input-group">
			<span class="input-group-addon"><i class="icon_key_alt"></i></span>
			<input type="password" id="conformpassword" class="form-control" name="conformpassword" placeholder="Enter Confirm Password" autocomplete="new-password" value="<?= htmlspecialchars(@$_POST['conformpassword']) ?>">
			<span class="input-group-addon float-right">
				<a href="javascript:void(0)" onclick="togglePasswordVisibility('conformpassword');">
					<i class="fa fa-eye" aria-hidden="true"></i>
				</a>
			</span>
		</div>
		<span class="errormsg"><?= htmlspecialchars($confirm_passwordErr) ?></span>
		
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
		<button class="btn btn-primary btn-lg btn-block" type="submit" name="change_password">Submit</button>
	</form>
 
  </div>
  
</body>
 
</html>
<?php } else { ?>
	 <div id="content" class="container py-5 container-limit--md reset-page">

<style>
.reset-card{
	    background: #fff;
		color: #000;
		font-size: 16px;
		padding:16px;
}
.reset-page{
	 idth: 100%;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}
.reset-card h1{
	    font-size: 36px;
    font-weight: 600 !IMPORTANT;
	margin-top: 5px;
}
.btn-outline-primary{
	    border: 2px solid #003b64 ;
    color: #003b64  !important;
    font-weight: 600;
}
.btn-outline-primary:hover{
	 background: #003b64;
	  color: #fff  !important;
}
</style>
	  <div class="card reset-card">
		<div class="p-5 container container-limit--sm">
		  <div id="forgottenPassword">
		  <img src="img/link-expire-icon.png" width="70">
			<h1>Link Expired</h1>
			<p>Your password reset link has expired, if you still wish to reset your forgotten password please go to the forgot password page</p>
			<a class="btn btn-outline-primary btn-lg" href="forgot_password.php">Forgot Password ?</a>
		  </div>
		</div>
	  </div>

</div>
 <?php } ?>
<script>
	function togglePasswordVisibility(id) {
    var input = document.getElementById(id);
    if (input.type === "password") {
        input.type = "text";
    } else {
        input.type = "password";
    }
}
</script>
