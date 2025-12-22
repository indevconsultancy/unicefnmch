<?php session_start(); ?>
<?php include_once('includes/config.php'); ?>
<?php define("title", "Create New Password | MQUAD"); ?>
<?php include_once('includes/functions.php'); ?>
<?php
$new_password = $confirm_password = "";
$new_passwordErr = $confirm_passwordErr = "";
if (isset($_REQUEST['submit'])) {
	if (empty($_POST["new_password"])) {
		$new_passwordErr = "Please enter your password";
	} else {
		$new_password = test_input($_POST["new_password"]);
		if (strlen($_POST["new_password"]) <= 6) {
			$new_passwordErr = "Your Password Must Contain At Least 6 Characters!";
		} elseif (!preg_match("#[0-9]+#", $new_password)) {
			$new_passwordErr = "Your Password Must Contain At Least 1 Number!";
		} elseif (!preg_match("#[A-Z]+#", $new_password)) {
			$new_passwordErr = "Your Password Must Contain At Least 1 Capital Letter!";
		} elseif (!preg_match("#[a-z]+#", $new_password)) {
			$new_passwordErr = "Your Password Must Contain At Least 1 Lowercase Letter!";
		} elseif (!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST["new_password"])) {
			$new_passwordErr = "Your Password Must Contain At Least 1 Special Character !" . "<br>";
		}
	}
	if (empty($_POST["confirm_password"])) {
		$confirm_passwordErr = "Please enter your password";
	} else {
		$confirm_password = test_input($_POST["confirm_password"]);
		if (strlen($_POST["confirm_password"]) <= 6) {
			$confirm_passwordErr = "Your Password Must Contain At Least 6 Characters!";
		} elseif (!preg_match("#[0-9]+#", $confirm_password)) {
			$confirm_passwordErr = "Your Password Must Contain At Least 1 Number!";
		} elseif (!preg_match("#[A-Z]+#", $confirm_password)) {
			$confirm_passwordErr = "Your Password Must Contain At Least 1 Capital Letter!";
		} elseif (!preg_match("#[a-z]+#", $confirm_password)) {
			$confirm_passwordErr = "Your Password Must Contain At Least 1 Lowercase Letter!";
		} elseif (!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST["confirm_password"])) {
			$confirm_passwordErr = "Your Password Must Contain At Least 1 Special Character !" . "<br>";
		}
	}
}
function test_input($data)
{
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
?>
<?php
$username = $_SESSION['username'];
$role_id = $_SESSION['role_id'];
$error = '';
if (isset($_REQUEST['submit'])) {
	
	$new_pass = mysqli_real_escape_string($conn, $_POST['new_password']);
	$new_password = password_hash($new_pass, PASSWORD_DEFAULT);

	$confirm_pass = mysqli_real_escape_string($conn, $_POST['confirm_password']);
	$confirm_password = password_hash($confirm_pass, PASSWORD_DEFAULT);

	$userssquery = mysqli_query($conn, "select user_id,status from users where username='$username'");
	$getssusersql = mysqli_fetch_array($userssquery);
	$user_id = $getssusersql['user_id'];
	if ($new_pass == $confirm_pass) {
		$updateqry = mysqli_query($conn, "UPDATE users SET password ='" . $new_password . "' where user_id='" . $user_id . "'");
		if ($updateqry) {
			echo "<script>alert('Your password has been successfully created,Please login to same username and password !');</script>";
			echo "<script>location.href='index.php'</script>";
		} else {
			echo "<script>alert('Somthing went wrong!');</script>";
		}
	} else {
		echo "<script>alert('New password and confirm password are not match !');</script>";
		echo "<script>location.href='new_password.php'</script>";
	}
}
?>
<style>
	.error {
		color: red;
	}
</style>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Creative - Bootstrap 3 Responsive Admin Template">
	<meta name="author" content="GeeksLabs">
	<meta name="keyword" content="Creative, Dashboard, Admin, Template, Theme, Bootstrap, Responsive, Retina, Minimal">
	<link rel="shortcut icon" href="img/mquad-fav.png">
	<title>Create New Password | Mquad</title>
	<link href="css/bootstrapV5-3.min.css" rel="stylesheet">
	<link href="css/elegant-icons-style.css" rel="stylesheet" />
	<link href="css/font-awesome.css" rel="stylesheet" />
	<link href="css/style.css" rel="stylesheet">
	<link href="css/style-responsive.css" rel="stylesheet" />
	<style type="text/css">
	</style>
</head>

<body class="login-img3-body">
	<div class="container">
		<form class="login-form" action="" method="post">

			<div class="login-wrap-left">
				<div class="logo-wrap">
					<img src="img/mquad-logo.png" style="height:70px;" />
				</div>
				<h2>Create New Password</h2>
				<p>Please create your new password for future login</p>

			</div>

			<div class="login-wrap">
				<!--<h3>New Password</h3>-->
				<span>Please create your Password Must Contain At Least 6 Characters, 1 Capital Letter,1 Lowercase Letter,1 number and 1 special character.</span><br><br>
				<div class="row">
					<div class="col-md-12">
						<?php if ($error != '') { ?>
							<div class="alert alert-danger">
								<?= $error; ?>
							</div>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="input-group">
							<span class="input-group-addon"><i class="icon_key_alt"></i></span>
							<input type="password" class="form-control" id="npassword_input" name="new_password" placeholder="New Password" autofocus>
							<span class="input-group-addon float-right" style="  margin-right:20px;"> <a href="javascript:void(0)" onclick="myFunctionnew();"> <i class="fa fa-eye" aria-hidden="true"></i> </a> </span>
						</div>
						<span class="error"><?php echo $new_passwordErr; ?></span>

					</div>
					<div class="col-md-12">
						<div class="input-group">
							<span class="input-group-addon"><i class="icon_key_alt"></i></span>
							<input type="password" id="cpassword_input" class="form-control" name="confirm_password" placeholder="Confirm Password" autofocus>
							<span class="input-group-addon float-right" style="  margin-right:20px;"> <a href="javascript:void(0)" onclick="myFunctionconfirm();"> <i class="fa fa-eye" aria-hidden="true"></i> </a> </span>
						</div>
						<span class="error"><?php echo $confirm_passwordErr; ?></span>
					</div>
				</div>
				<br>
				<button class="btn btn-primary btn-lg btn-block" type="submit" name="submit">Submit</button>
				<!-- <button class="btn btn-info btn-lg btn-block" type="submit">Signup</button> -->
			</div>
		</form>
	</div>
	<script src="js/jquery-3.7.1.js"></script>
	<script src="js/bootstrap.bundleV5-3.min.js"></script>
</body>

</html>
<script>
	function myFunctionnew() {
		var x = document.getElementById("npassword_input");
		if (x.type === "new_password") {
			x.type = "text";
		} else {
			x.type = "new_password";
		}
	}

	function myFunctionconfirm() {
		var x = document.getElementById("cpassword_input");
		if (x.type === "confirm_password") {
			x.type = "text";
		} else {
			x.type = "confirm_password";
		}
	}
</script>