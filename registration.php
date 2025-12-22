<?php include('includes/config.php'); ?>
<?php include('includes/functions.php'); ?>

<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
	<link rel="icon" type="image/png" href="favicon.png">
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Sign up for your 1-month FREE MQUAD account!  Several discounts may apply, feel free to contact MQUAD about small/large groups, eductional and Enterprise discounts." />
	<title>Registration | MQUAD</title> 
	<link href="Content/CSS/bootstrap/bootstrapV5-3.min.css" rel="stylesheet" />
	<link href="Content/CSS/Site.css" rel="stylesheet" />
	<link href="style.css" rel="stylesheet" />
	<script src="Content/Scripts/modernizr-2.6.2.js"></script>
	<!-- Cropper image CSS -->
	<link rel='stylesheet' href='assets/css/cropper.min.css'>
	<!-- Cropper image JS -->
	<!-- Cropper image JS -->
	<script src='assets/js/cropper.min.js'></script>
</head>
<style>
	.introPanel>div>div>.homeIntroBoxText>h3 {
		margin-top: 0;
		margin-bottom: 20px;
	}

	h3 {
		font-family: 'Poppins', sans-serif;
		color: white;
		margin: 0 0 26px;
		line-height: 1.2;
	}

	.ml-5 {
		margin-top: 25px;
		margin-left: -26px;
		background-color: white;
	}

	.awesomePanel .homeAwesomeBox {
		min-height: auto;
	}

	.homeAwesomeBox>.div-box {
		background-color: #fff;
		text-align: left;
		padding: 36px 15px 36px 15px;
		border-style: solid;
		border-width: 0px 0px 0px 0px;
		box-shadow: 0px 0px 10px 0px #eee;
		position: relative;
		display: -webkit-box;
		display: -webkit-flex;
		display: -ms-flexbox;
		display: flex;
		-webkit-box-orient: vertical;
		-webkit-box-direction: normal;
		-webkit-flex-direction: column;
		-ms-flex-direction: column;
		flex-direction: column;
		width: 100%;
		transition: all .9s ease 0s;
		min-height: 407px;
		display: block;
		border-radius: 4px;
		overflow: hidden;

	}

	.awesomePanel .homeAwesomeBox {
		float: left;
		min-height: 1px;
		height: 343px;
	}

	.cta_btn {
		font-size: 17px;
		line-height: 26px;
		font-weight: 600;
		text-transform: capitalize;
		cursor: pointer;
		width: 100%;
		box-shadow: none;
		border: none;
		display: block;
		transition: all 0.4s;
		z-index: 1;
		padding: 15px 40px 15px 40px;
		text-align: center;
		background: rgb(139 198 63);
		color: white;
		text-decoration: none;
		transition: background .3s ease;
		margin-top: 15px;
	}

	.card_title_sub {
		text-align: center;
	}

	.fa-fa-icon {
		text-align: center;
	}

	.features.icon-item li .list-icon i {
		font-size: 13px;
		width: 1.25rem;
		width: 25px;
		height: 25px;
		background: #8bc63f;
		text-align: center;
		display: flex;
		align-items: center;
		justify-content: center;
		border-radius: 50%;
	}

	.modal-title {
		color: #003b64;
	}

	.homeAwesomeBox {
		margin-left: 3rem;
		padding: 2rem;
		min-width: 30%;
		padding-bottom: 5rem;
	}

	.modal-body {
		min-width: 100%;
	}

	.modal-body input,
	.modal-body select,
	.modal-body textarea {
		max-width: 100%;
	}

	.modal-body .box {
		border: none !important;
	}

	.col-form-label {
		font-weight: 300;
	}

	.box-option {
		padding: -6.5em;
		width: 100%;
		margin: 0.5em;
	}

	.box-2-option {
		padding: -2.5em;
		float: left;
		width: calc(100%/2 - 1em);
		display: inline-block !important;
	}

	.options label,
	.options input {
		width: 4em;
		padding: 0.5em 1em;
	}

	.hide {
		display: none;
	}

	img {
		max-width: 100%;
	}

	.options label,
	.options input {
		width: 5em;
		padding: 0.5em 1em;
	}

	#imgcode {
		width: 97px;
		height: 200px;
	}

	#statususer {
		color: red;
		font-size: 13px;
	}

	.awesomePanel .homeAwesomeTitle h2::after {
		background: #8cc63f;
		content: unset;
		display: none;
		height: 3px;
		left: calc(50% - 165px);
		bottom: 18px;
		min-width: 400px;
		margin: 0 auto;
		width: auto;
	}

	.badge-primary {
		background-color: #003b64;
	}

	h2 {
		font-size: 40px;
		line-height: 50px;
		font-weight: 700;
	}

	.icon-item li {
		display: flex;
		padding-bottom: 10px;
	}

	.icon-item li .list-text {
		align-self: center;
		padding-left: 5px;
	}

	#logo_error {
		color: red;
	}

	#otp_verified_msg {
		font-weight: bold;
		color: green;
	}

	#otp_verified_msg i {
		font-size: 20px !important;
		border: 1px solid green;
		border-radius: 50%;
		padding: 5px;
		margin: 10px 0px;
	}

	h4 {
		font-size: 20px;
	}

	#otp_verified_msg {
		font-weight: bold;
		color: green;
		margin-top: -20px;
	}

	#verify_otp {
		margin-bottom: 10px;
	}

	.input-group .form-control {
		position: relative;
		z-index: 2;
		float: left;
		min-width: 100%;
		margin-bottom: 0;
	}

	.input-group {
		position: relative;
		display: table;
		border-collapse: separate;
	}

	.signUpOuter .signUpMain .signUpMainContents .signUpMainContent .dedoooseSignupForm>form>div .formControlSpan {
		display: block;
		overflow: hidden;
	}

	.input-group-addon,
	.input-group-btn,
	.input-group .form-control {
		display: table-cell;
	}

	.input-group-btn {
		position: relative;
		font-size: 0;
		white-space: nowrap;
	}

	.input-group-addon,
	.input-group-btn,
	.input-group .form-control {
		display: table-cell;
	}

	.input-group-addon,
	.input-group-btn {
		width: 1%;
		white-space: nowrap;
		vertical-align: middle;
	}
	
	
</style>
<?php include('includes/header.php'); ?>

<body>
	<div class="signUpOuter">
		<div class="signUpMain">
			<div class="header">
				<div class="headerImage">
					<img src="Content/Images/Signup/signup-circle.svg" alt="Signup For MQUAD" title="Sign Up With MQUAD!" />
				</div>
				<div class="headerTitle">
					<h4>SIGN UP</h4>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<?php
					$error = '';
					if (isset($_REQUEST['submitData'])) {
						$name = sanitizeInput($_POST['name'],$conn);

						$email = sanitizeInput($_POST['email'],$conn);

						$registered_as = mysqli_real_escape_string($conn, $_POST['registered_as']);

						$sspass = str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ123456789");
						$passwd = substr($sspass, 0, 8);
						$password = password_hash($passwd, PASSWORD_DEFAULT);

						$captcha = $_REQUEST['captcha'];
						$scaptcha = $_SESSION['CODE'];
						$verify = $_SESSION['verify'];

						if ($scaptcha == $captcha && $verify == 'verify') {
							$_SESSION['CODE'] = '';
							$digits = 5;
							$unique_id = str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
							10;
							$imglogo = mysqli_real_escape_string($conn, $_POST['imglogo']);
							if ($imglogo != '') {
								$photo_name = "logo";
								$folder_path = "logo_image";
								$uniqueid = uniqid(rand(0 - 9, 10));
								$imgnamef = $photo_name . "_" . $uniqueid;
								$resimage = str_replace('data:image/png;base64,', '', $imglogo);
								$resimage = str_replace(' ', '+', $resimage);
								$resimage = base64_decode($resimage);
								$file = $folder_path . '/' . $imgnamef . '.png';
								$file_path = $imgnamef . '.png';
								$logoimgcode = "logo_image=" . "'$file_path'" . ",";
								$success = file_put_contents($file, $resimage);
							}

							$sqlusercheck = mysqli_query($conn, "SELECT username,email,password,role_id FROM `users` where email='" . $email . "'");
							if (mysqli_num_rows($sqlusercheck) > 0) {
								$error = 'Sorry username already registered !';
							} else {
								$mailto = $email;
								if ($mailto != '') {
									$name = $name;
									$message_all = '';
									$subject = base64_encode("MQUAD Login Details");
									$message_all .= '<td width="100%" cellpadding="0" cellspacing="0" style="box-sizing: border-box; font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif"; background-color: #edf2f7; border-bottom: 1px solid #edf2f7; border-top: 1px solid #edf2f7; margin: 0; padding: 0; width: 100%">
										<table align="center" width="570" cellpadding="0" cellspacing="0" style="box-sizing: border-box; font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif"; background-color: #ffffff; border-color: #e8e5ef; border-radius: 2px; border-width: 1px; margin: 0 auto; padding: 0; width: 570px">
											<tbody>
												<tr>
													<td style="box-sizing: border-box; font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif; max-width: 100vw; padding: 32px">
													  <h4 style="box-sizing: border-box; font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif; color: #3d4852; font-size: 18px; font-weight: bold; margin-top: 0; text-align: left">Dear ' . $name . ',</h4>
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
																		' . $email . '
																	</td>
																</tr>
																<tr>
																	<th style="border: 1px solid #ccc; border-right: none; padding: 8px 15px">
																		Password :
																	</th>
																	<td style="border: 1px solid #ccc; border-left: none; padding: 8px 15px">
																		' . $passwd . '
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
									$message_all = base64_encode($message_all);
									$sendmail = send_mail_function($mailto, $message_all, $subject);

									if ($sendmail['status'] == 1) {

										$sqlClent = "INSERT INTO clients SET $logoimgcode name='" . $name . "',email='" . $email . "',role_id='3',registered_as='" . $registered_as . "',membership_id='0',status='1'";
										$getsql = mysqli_query($conn, $sqlClent);
										if ($getsql) {
											$client_id = mysqli_insert_id($conn);
											$sql2 = "INSERT INTO users SET name='" . $name . "',email='" . $email . "',username='" . $email . "', password='" . $password . "',client_id='" . $client_id . "',role_id='3',user_code='" . $email . "',status='0',registered_as='" . $registered_as . "'";

											$getsql2 = mysqli_query($conn, $sql2);
											if ($getsql2) {
												$lastuserid = mysqli_insert_id($conn);
												$insertFunctional = "INSERT INTO functional_role SET user_id='" . $lastuserid . "', role_id='3' ";
												$resultdata = mysqli_query($conn, $insertFunctional);
												$resultdata = mysqli_query($conn, "INSERT INTO menu_role(menu_id,role_id,user_id,status) SELECT  menu_id,'3','" . $lastuserid . "','1' FROM menu_role WHERE role_id='1' ");

												$getmenus = mysqli_query($conn, "SELECT GROUP_CONCAT(menu_id) AS menu_id FROM `functional_control` WHERE role_id='3' AND status='0'");
												$menus = mysqli_fetch_object($getmenus);
												$allMenus = $menus->menu_id;
												$resultdata = mysqli_query($conn, "UPDATE menu_role SET status='0' WHERE user_id='" . $lastuserid . "' and menu_id IN($allMenus) ");

												if ($resultdata) {
													echo "<script>window.location.href='welcome.php'</script>";
												} else {
													$error = 'Something went wrong!';
												}
											} else {
												$error = 'Something went wrong!';
											}
										} else {
											$error = 'Something went wrong!';
										}
									} else {
										$error = 'Something went wrong!';
									}
								} else {
									$error = 'Email ID not Exit';
								}
							}
						} else {
							$error = 'Invalid Captcha!!';
						}
						$_SESSION['CODE'] = '';
					}
					?>


					<?php if ($error != '') { ?>
						<div class="alert alert-danger alert-dismissible fade show" role="alert">
							<?= $error; ?>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>

					<?php } ?>
				</div>
			</div>
			<?php //echo $_SESSION['otp']; 
			?>
			<div class="signUpMainContents">
				<div class="signUpMainContent">
					<div>
						<div class="dedoooseSignupForm">
							<form action="" method="post">
								<span class="rightWarning">Required *</span>
								<div class="form-group">
									<label for="txtUsername">Register As</label> <span style="color:red;">*</span>
									<span class="formControlSpan">
										<select class="form-control" name="registered_as" required id="registed_as" placeholder="Registered As *">
											<option value="">Register As </option>
											<option value="individual" <?php if (isset($_REQUEST['registered_as'])) {
																			echo "selected";
																		} ?>>Individual</option>
											<option value="organization" <?php if (isset($_REQUEST['registered_as'])) {
																				echo "selected";
																			} ?>>Organization</option>
										</select>
									</span>
								</div>
								<div class="form-group">
									<label for="txtReferredBy">Name</label> <span style="color:red;">*</span>
									<span class="formControlSpan"><input type="text" required class="form-control" id="form_fname" name="name" placeholder="Enter your name"></span>
									<span id="fname_error_message" style="color:red;"></span>
								</div>
								<div class="form-group">
									<label for="txtEmailAddress">Email ID <span style="color:red;">*</span></label>
									<span class="formControlSpan">
										<div class="input-group">
											<input class="form-control" required type="email" name="email" id="email" value="" placeholder="Enter your Email ID">
											<div class="input-group-btn">
												<button type="button" id="otpsend" class="btn btn-primary rounded-0">Send OTP</button>
											</div>
										</div>
									</span>
								</div>
								<p id="statususer"></p>
								<p id=""></p>
								<div class="form-group" id="email_otp">
									<label for="txtEmailAddress">Enter OTP <span style="color:red;">*</span></label>
									<span class="formControlSpan">
										<div class="input-group">
											<input class="form-control" required type="text" id="emailotp" placeholder="Enter Email OTP">
											<div class="input-group-btn">
												<button type="button" id="verifyotp" class="btn btn-primary" disabled value="OTP Send">Verify OTP</button>
											</div>
										</div>
									</span>
								</div>
								<div class="col-10" id="otp_verified_msg"></div>
								<div class="" id="verify_otp">
									<span style="color:green;">OTP send to the email ID</span>
								</div>
								
								<div class="form-group upload-logo" style="display:none">
									<input type="hidden" id="imgcode" name="imglogo" />
									<label for="message-text" class="col-form-label">Upload Logo</label>
									<main class="page">
										<input type="file" id="file-input" class="form-control file-input" accept=".jpeg,.jpg,.png" name="logo_image">
										<span id="logo_error"></span>
										<div class="row">
											<div class="box-2-option">
												<div class="result"></div>
											</div>
											<div class="box-2-option img-result hide">
												<img class="cropped" src="" alt="">
											</div>
										</div>
										<div class="box-option">
											<div class="options hide">
												<input type="hidden" class="img-w" value="200" min="200" max="200" />
												<button class="btn btn-primary save ">Crop</button>
											</div>
										</div>
									</main>
								</div>
								<div class="row enter-captcha" style="display:none">
									<div class="col-md-7">
										<div class="form-group mb-3">
											<label for="txtEmailAddress">Enter Captcha <span style="color:red;">*</span></label>
											<input type="text" name="captcha" required id="captcha" class="form-control" placeholder="Enter Captcha *" autocomplete="off">
										</div>
									</div>
									<div class="col-md-3 mt-1 captcha-column">
										<img src="captcha.php" id="captch-img" width="100%">
									</div>
									<div class="col-md-2 mt-4">
										<label for="refresh"></label>
										<button type="button" class="site-button site-button-dark border-0 px-3 py-1" onclick="document.getElementById('captch-img').src = 'captcha.php?' + Math.random(); document.getElementById('captcha').value = '';return false;"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</a></button>
									</div>
								</div>
								<!----------------End logo upload------------------->
								<div class="submitForm mb-2 submit-data" style="display:none">
									<button type="submit" class="btn btn-primary " disabled id="register" name="submitData" style="float:right">Submit</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!---------------- End Signup modal------------------------>
	<?php include('includes/footer.php'); ?>
	<script>
		function memRegister(val) {
			$("#member_id").attr('value', val);
		}
	</script>
	<script src="assets/js/jquery.min.js"></script>
	<script>
		$(document).ready(function() {
			$("#email_otp").hide();
			$("#verify_otp").hide();
			$("#otp_verified_msg").hide();

			$("#otpsend").on("click", function() {
				//alert('hhhhh');	
				var email = $("#email").val();
				//alert(email);
				if (email != '') {
					
					$.ajax({
						url: "ajax_registration.php",
						method: "POST",
						data: {
							email: email
						},
						success: function(data) {
							$('#otpsend').prop('disabled', true);
							$("#email_otp").show();
							$("#verify_otp").show();
							
							
							//console.log(res);

						}

					});

				} else {
					console.log('Email address are required.');
				}
			});

			$("#verifyotp").on("click", function() {
				var emailotp = $("#emailotp").val();
				if (emailotp != '') {
					$.ajax({
						url: 'ajax_registration.php',
						method: 'POST',
						data: {
							emailotp: emailotp
						},
						success: function(res) {
							var resdata = JSON.parse(res);
							if (resdata.status == "1") {
								$("#email_otp").hide();
								$("#statususer").hide();
								$("#verify_otp").hide();
								$("#otp_verified_msg").show();
								$("#otp_verified_msg").html('<i class="fa fa-check " style="font-size:48px;"></i> Email OTP Verified');
								$(".upload-logo").show();
								$(".enter-captcha").show();
								$(".submit-data").show();
							} else {
								$("#otp_verified_msg").show();
								$("#otp_verified_msg").html('<i class="fa fa-close" style="font-size:48px;color:red"></i> Invalid OTP');
							}

						}

					});
				} else {
					console.log('Please enter OTP from your email.');
				}


			})

		});
	</script>
	<script>
		$(document).ready(function() {
			$('#email').keyup(function() {
				var email = $(this).val();
				if (email.length >= 3) {
					$.ajax({
						url: "get_user_ajax.php",
						method: "POST",
						data: {
							email: email
						},
						dataType: "json", // Expect a JSON response
						success: function(resdata) {
							console.log(resdata);
							//$("#statususer").html(html);
							if (resdata.status == 0) {
								$("#statususer").html(resdata.message);
								$("#otpsend").prop('disabled', true); // Disable the button
							} else if(resdata.status == 1) {
								$("#statususer").html(resdata.message);
								 $("#otpsend").prop('disabled', false); // Enable the button
							}
						},
						error: function(xhr, status, error) {
							console.error("AJAX Error: ", status, error);
						}
						 
					});
				}
			});
		});

	</script>
	<script>
		// Disable the "Send OTP" button initially
		$("#otpsend").prop("disabled", true);

		// Check if the email input is not blank, enable the button
		$("#email").on("input", function() {
			var emailValue = $(this).val();
			if (emailValue.trim() !== "") {
				$("#otpsend").prop("disabled", false);
			} else {
				$("#otpsend").prop("disabled", true);
			}
		});

		// Add your OTP sending logic here when the button is clicked
		$("#otpsend").on("click", function() {
			// alert("OTP sent to: " + $("#email").val());
		});
	</script>

	<script>
		$(document).ready(function() {
			$('#emailotp').on('input', function() {
				var otpValue = $(this).val();
				$('#verifyotp').prop('disabled', otpValue === '');
			});
		});
	</script>
	<script type="text/javascript">
		$(function() {
			$("#fname_error_message").hide();
			var error_fname = false;

			$("#form_fname").focusout(function() {
				check_fname();
			});

			$("#emailotp").keyup(function() {
				check_fname();
			});

			function check_fname() {
				var pattern = /^[a-zA-Z ]*$/;
				var fname = $("#form_fname").val().trim();
				var emailotp = $("#emailotp").val();

				if (pattern.test(fname) && fname !== '') {
					$("#fname_error_message").hide();
					if (emailotp !== '') {
						$('#register').prop("disabled", false);
					} else {
						$('#register').prop("disabled", true);
					}
				} else {
					$("#fname_error_message").html("First name should contain only letters and white space.");
					$("#fname_error_message").show();
					$('#register').prop("disabled", true);
					error_fname = true;
				}
			}
		});
	</script>

	<script>
		//Logo validation
		$(document).ready(function() {
			$(document).on("change", ".file-input", function() {

				var myImg = this.files[0];
				var myImgType = myImg["type"];
				var validImgTypes = ["image/gif", "image/jpeg", "image/png"];

				if ($.inArray(myImgType, validImgTypes) < 0) {
					//alert("Not an image")
					$("#logo_error").html("Please select only jpeg,png and gif file");
					//$('#register').attr("disabled", true);
					$("#logo_error").show();
				} else {
					//alert("Is an image")
					$("#logo_error").hide();
					//$('#register').attr("disabled", false);
				}

			});
		});
	</script>

	<script>
		// vars
		let result = document.querySelector('.result'),
			img_result = document.querySelector('.img-result'),
			img_w = document.querySelector('.img-w'),
			img_h = document.querySelector('.img-h'),
			options = document.querySelector('.options'),
			save = document.querySelector('.save'),
			cropped = document.querySelector('.cropped'),
			//dwn = document.querySelector('.download'),
			upload = document.querySelector('#file-input'),
			cropper = '';

		// on change show image with crop options
		upload.addEventListener('change', e => {
			if (e.target.files.length) {
				// start file reader
				const reader = new FileReader();
				reader.onload = e => {
					if (e.target.result) {
						// create new image
						let img = document.createElement('img');
						img.id = 'image';
						img.src = e.target.result;
						// clean result before
						result.innerHTML = '';
						// append new image
						result.appendChild(img);
						// show save btn and options
						save.classList.remove('hide');
						options.classList.remove('hide');
						// init cropper
						cropper = new Cropper(img);
					}
				};
				reader.readAsDataURL(e.target.files[0]);
			}
		});

		// save on click
		save.addEventListener('click', e => {
			e.preventDefault();
			// get result to data uri
			let imgSrc = cropper.getCroppedCanvas({
				width: img_w.value // input value
			}).toDataURL();
			// remove hide class of img
			cropped.classList.remove('hide');
			img_result.classList.remove('hide');
			// show image cropped
			// console.log(imgSrc);
			$("#imgcode").attr("value", imgSrc);
			cropped.src = imgSrc;

		});
	</script>
	<!--Logo Validation-->
	<script>
		function fileValidation() {
			var fileInput =
				document.getElementById('file-input');

			var filePath = fileInput.value;

			// Allowing file type
			var allowedExtensions =
				/(\.jpeg|.jpg|.png|.gif)$/i;

			if (!allowedExtensions.exec(filePath)) {
				alert('Invalid file type');
				fileInput.value = '';
				return false;
			}
		}
	</script>
	<!------------End logo upload--------------->
</body>

</html>