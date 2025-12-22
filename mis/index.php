<?php include_once('includes/config.php'); ?>
<?php include_once('includes/functions.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="" content="no-cache" />
	<meta http-equiv="Expires" content="-1" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Creative - Bootstrap 5 ">
	<meta name="author" content="GeeksLabs">
	<meta name="keyword" content="Creative, Dashboard, Admin, Template, Theme, Bootstrap, Responsive, Retina, Minimal">
	<link rel="shortcut icon" href="img/mquad-logo.png">
	<title>Login | AAKLAN | Dashboard</title>
	<link href="css/bootstrapV5-3.min.css" rel="stylesheet">
	<link href="css/elegant-icons-style.css" rel="stylesheet" />
	<link href="css/font-awesome.css" rel="stylesheet" />
	<link href="css/style.css" rel="stylesheet">
	<link href="css/style-responsive.css" rel="stylesheet" />
</head>
<!--<script type="text/javascript">
		function preventBack(){window.history.forward()}; 
		selTimeout("preventBack()", 0);
		window.onunload=function(){null;}
	</script>-->
<script type="text/javascript">
	window.history.forward();

	function noBack() {
		window.history.forward();
	}
</script>


<body class="login-img3-body" onLoad="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
	<?php

	$error = '';
	if (isset($_SESSION['locked'])) {
		$difference = time() - $_SESSION['locked'];
		// Check if 15 minutes have passed
		if ($difference > 900) { // 15 minutes in seconds
			unset($_SESSION['locked']);
			unset($_SESSION['login_attempts']);
		}
	}
	?>
	<div class="login-sec">
		<div class="row m-0">
			<div class="col-md-8 p-0">
				<div class="login-img position-relative w-100 vh-100 d-flex align-items-end">
					<div>
						<div class="text-white py-3 bg-primary p-2 login-box">
							<div class="d-flex align-items-center gap-5 mb-4">
								
								<div class="d-flex align-items-center gap-2">
									<img src="img/nmch-logo.png" width="80">
									<div>
										<span style="font-size: 17px;" class="fw-bold">
											नालंदा मेडिकल कॉलेज और अस्पताल
										</span> <br>
										<span style="font-family: Open Sans; font-size: 15px">
											Nalanda Medical College & Hospital
										</span>
									</div>
								</div>
								<img src="img/unicef-blue.jpg" width="150">

								<!-- <img src="img/nmch-logo-full.png" width="500"> -->
							</div>

							<div style="font-size: 15px;">
								<span style="font-family: Open Sans; font-size: 15px">Aakalan is an initiative of Nalanda Medical College and Hospital, supported by UNICEF, to strengthen data monitoring across nutrition themes. </span> <br/>
								<span style="font-family: Open Sans; font-size: 15px">It's a work in progress and will continuously adapt to programmatic needs. The initiative seeks to bridge the gap between data collected through supportive supervision, reported programmatic data, and analytical reports, facilitating program monitoring, review, evidence-based decision-making, and advocacy..</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4 d-flex align-items-center bg-white">
				<form action="" class="login-form w-100" method="post" name="myrecaptcha">
					<div class="login-wrap w-100">
						<h2 class="h2 fw-bold text-secondary mb-4 text-center" style="font-weight: bold !important;">AAKLAN Dashboard</h2>
						<!-- <h3 class="mb-4">Login</h3> -->
						<div class="row">
							<div class="col-sm-12">
								<span id="loginError"></span>

								<?php if ($_SESSION['successerror']) { ?>
									<div class="alert alert-success alert-dismissible fade show" role="alert">
										<?= $_SESSION['successerror']; ?>
										<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
									</div>
								<?php $_SESSION['successerror'] = '';
								}
								if ($_SESSION['login_attempts'] >= 3) {
									$error = '';
								?>
									<p style="color:red;">You have completed 3 attempts. Your account has been locked, Kindly try again after 15 minutes</p>
								<?php }
								if ($error != '') {
								?>
									<div class="alert alert-danger alert-dismissible fade show" role="alert">
										<?= $error; ?>
										<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
									</div>
								<?php } ?>
							</div>
						</div>
						<div class="input-group border rounded-1">
							<span class="input-group-addon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
							<input type="text" class="form-control" name="username" id="username" placeholder="Username" autofocus>
						</div>
						<div class="input-group border rounded-1">
							<span class="input-group-addon"><i class="icon_key_alt"></i></span>
							<input type="password" id="password" class="form-control" name="password" placeholder="Password">
							<span class="input-group-addon float-right"> <a href="javascript:void(0)" onclick="myFunction();"> <i class="fa fa-eye" aria-hidden="true"></i> </a> </span>
						</div>
						<div class="input-group border-0 gap-3">
							<div class="border rounded-1 d-flex">
								<img src="captcha.php" id="ref_captcha" width="160" height="40" border="0" />
								<span class="text-dark cursor-pointer bg-primary text-white p-3 d-flex align-items-center justify-content-center" onclick="
					  document.getElementById('ref_captcha').src = 'captcha.php?' + Math.random();
					  document.getElementById('captcha').value = '';
					  return false;"><i class="fa fa-refresh" aria-hidden="true"></i></span>
							</div>
							<input type="text" class="form-control border rounded-1" id="captcha" name="captcha" placeholder="Enter Captcha">
						</div>
						<!-- <div class="input-group border rounded-1">
							<span class="input-group-addon"></span>
							<input type="text" class="form-control" id="captcha" name="captcha" placeholder="Enter Captcha">
						</div> -->

						<label class="checkbox d-block text-end">
							<a href="forgot_password.php"> Forgot Password?</a>
						</label>

						<button
							class="btn btn-primary btn-lg btn-block w-100 userLogin"
							type="submit"
							onclick="this.disabled = true;"
							<?php if ($_SESSION['login_attempts'] >= 3) {
								$_SESSION['locked'] = time();
								echo 'disabled';
							} ?>>
							Login
						</button>
						<br />
						<!-- <p class="text-center mt-3" style="font-weight:700;color: #5f8899;">Don't have an account? <a href="../registration.php" style="margin-top:10px; text-align:center;  "> Register Here </a></p> -->
					</div>
				</form>
			</div>
		</div>
	</div>
	<script src="js/jquery-3.7.1.js"></script>
	<script src="js/bootstrap.bundleV5-3.min.js"></script>
	<script src="js/aes.js"></script>

</body>

</html>

<script>
	function myFunction() {
		var x = document.getElementById("password");
		if (x.type === "password") {
			x.type = "text";
		} else {
			x.type = "password";
		}
	}


	$(".userLogin").on("click", function(event) {
		event.preventDefault();
		/*
		var CryptoJSAesJson = {
			stringify: function(cipherParams) {
				var j = {
					ct: cipherParams.ciphertext.toString(CryptoJS.enc.Base64)
				};
				if (cipherParams.iv) j.iv = cipherParams.iv.toString();
				if (cipherParams.salt) j.s = cipherParams.salt.toString();
				return JSON.stringify(j);
			},
			parse: function(jsonStr) {
				var j = JSON.parse(jsonStr);
				var cipherParams = CryptoJS.lib.CipherParams.create({
					ciphertext: CryptoJS.enc.Base64.parse(j.ct)
				});
				if (j.iv) cipherParams.iv = CryptoJS.enc.Hex.parse(j.iv)
				if (j.s) cipherParams.salt = CryptoJS.enc.Hex.parse(j.s)
					console.log(cipherParams);
				return cipherParams;
			}
		}*/

		$("#loginError").html('');
		let uname = $("#username").val();
		let upass = $("#password").val();
		let captcha = $("#captcha").val();
		if (uname != "" & upass != "" & captcha != "") {
			var encPass = upass;
			var encrypted = upass;
			/*var encrypted = CryptoJS.AES.encrypt(JSON.stringify(encPass), "ENC_KEY", {
				format: CryptoJSAesJson
			}).toString();*/
			$.ajax({
				url: "ajax/loginajax1.php",
				type: "post",
				data: {
					username: uname,
					password: encrypted,
					captcha: captcha
				},
				dataType: "json",
				success: function(res) {
					console.log(res);
					let result = res;
					if (result.restatus == 1) {
						//console.log('redirect');
						window.location.href = 'https://unicef.indevconsultancy.in/mis/dashboard_new.php';

					} else {
						$('.userLogin').prop('disabled', false);

						$("#loginError").html('<div class="alert alert-danger alert-dismissible fade show">' + result.error + '</div>');
					}


					//captchaRefresh();
				}
			});
		} else {
			$('.userLogin').prop('disabled', false);

			$("#loginError").html('<div class="alert alert-danger alert-dismissible fade show">All fields are required.</div>');
		}
	});
</script>