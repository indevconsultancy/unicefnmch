<?php include('includes/config.php');
?>

<?php
require("PHPMailerMaster/PHPMailer-master/src/PHPMailer.php");
require("PHPMailerMaster/PHPMailer-master/src/SMTP.php");
?>

<?php

$error = '';
$successerror = '';
if (isset($_REQUEST['submit'])) {
	$name = mysqli_real_escape_string($conn, $_REQUEST['name']);
	$email = mysqli_real_escape_string($conn, $_REQUEST['email']);
	$phone_number = mysqli_real_escape_string($conn, $_REQUEST['phone_number']);
	$subject = mysqli_real_escape_string($conn, $_REQUEST['subject']);
	$comments = mysqli_real_escape_string($conn, $_REQUEST['comments']);
	$captcha = $_REQUEST['captcha'];
	$scaptcha = $_SESSION['CODE'];
	if ($scaptcha == $captcha) {
		$_SESSION['CODE'] = '';

		if ($email != '') {
			$email = $email;
			//die();
			$comments = $comments;
			$mail = new PHPMailer\PHPMailer\PHPMailer();
			$mail->IsSMTP(); // enable SMTP

			$mail->SMTPDebug = 0; // debugging: 1 = errors and messages, 2 = messages only
			$mail->SMTPAuth = true; // authentication enabled
			$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
			$mail->Host = "smtp.gmail.com";
			$mail->Port = 465; // or 587
			$mail->IsHTML(true);
			$mail->Username = "info@mquad.org";
			$mail->Password = "mdoasnhyshvkzkbo";
			$mail->SetFrom("info@mquad.org");
			$mail->Subject = "Contact US";
			$mail->Body = "Dear Team,<br>" . $comments . "";
			$mail->AddAddress("info@mquad.org", "Contact Us");

			if (!$mail->Send()) {
				echo "Mailer Error: " . $mail->ErrorInfo;
			} else {
				$insert = "INSERT INTO `contacts`( `name`, `email`, `phone_number`, `subject`, `comments`) VALUES ('$name','$email','$phone_number','$subject','$comments') ";
				$resultdata = mysqli_query($conn, $insert);
				if ($resultdata) {
					$successerror = "Your request has been successfully submitted";
					//echo "<script>window.location.href='contact.php'</script>";
				} else {
					$error = 'Something went wrong!';
				}
			}
		} else {
			$error = "Email not exit!!";
		}
	} else {
		$error = 'Invalid Captcha!!';
	}
	$_SESSION['CODE'] = '';
}

?>

<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
	<link rel="icon" type="image/png" href="favicon.png">
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Get in touch with  if you need support, on-on-one training or just want to leave feedback.  Contacting is just an email away." />
	<title>Contact - Home | MQUAD</title> 
	<link href="Content/CSS/bootstrap/bootstrapV5-3.min.css" rel="stylesheet" />
	<link href="Content/CSS/Site.css" rel="stylesheet" />
	<link rel="stylesheet" href="assets/font-awesome-4.7.0/css/font-awesome.min.css" />
	<script src="Content/Scripts/modernizr-2.6.2.js"></script>
</head>
<?php include('includes/header.php'); ?>

<body>

	<div class="contactPageOuter">
		<div class="contactPageMain">
			<div class="header">
				<div class="headerImage">
					<img src="Content/Images/about/contact/contact-circle.svg" alt="Prides itself on outstanding customer service." title="Get in Touch with" />
				</div>
				<div class="headerTitle">
					<h4>CONTACT</h4>
				</div>
				<div class="headerText">
					<p>Fill out the form below if you are looking for support, need one&#8212;on&#8212;one training, want to send feedback about, or looking to contact us about something else</p>
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<?php if ($successerror != '') { ?>
						<div class="alert alert-success alert-dismissible fade show" role="alert">
							<?= $successerror; ?>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
					<?php } ?>
					<?php if ($error != '') { ?>
						<div class="alert alert-danger alert-dismissible fade show" role="alert">
							<?= $error; ?>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
					<?php } ?>
				</div>

			</div>
			<div class="mainContent">
				<div class="dedoooseContactForm">
					<div>
						<form method="POST" action="">
							<div class="form-group">
								<label for="txtFirstName">Name <span style="color:red;">*</span></label>
								<input type="text" class="form-control" required name="name">
							</div>
							<div class="form-group">
								<label for="txtEmailAddress"><span class="required">Email ID</span> <span style="color:red;">*</span></label>
								<input type="text" class="form-control" required name="email">
							</div>
							<div class="form-group">
								<label for="txtPhoneNumber">Phone Number <span style="color:red;">*</span></label>
								<input type="number" class="form-control" required name="phone_number">
							</div>
							<div class="form-group">
								<label for="txtInstitution">Subject <span style="color:red;">*</span></label>
								<input type="text" class="form-control" required name="subject">
							</div>
							<div class="form-group">
								<label for="txtInstitution">Comments <span style="color:red;">*</span></label>
								<textarea type="text" class="form-control" required name="comments"></textarea>
							</div>
							<div class="row align-items-center">
								<div class="col-md-5 mt-1 captcha-column">
									<img src="captcha.php" id="captch-img" width="100%">
								</div>
								<div class="col-md-6">
									<label for="refresh"></label>
									<button type="button" class="site-button site-button-dark border-0" style="height: 35px;" onclick="document.getElementById('captch-img').src = 'captcha.php?' + Math.random(); document.getElementById('captcha').value = '';return false;"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</a></button>
								</div>
							</div>
							<div class="form-group mt-2">
								<label for="txtEmailAddress">Enter Captcha <span style="color:red;">*</span></label>
								<input type="text" name="captcha" required id="captcha" class="form-control" placeholder="Enter Captcha *" autocomplete="off">
							</div>


							<div class="form-group">
								<button type="submit" class="btn btn-primary" name="submit" style=float:left;>Submit</button>
							</div>
							<div id="divFormStatus" style="color: red;font-weight: bold"></div>
						</form>
					</div>
				</div>
				<div class="socialContent">
					<div>
						<div class="dedooseSupport">
							<h3>Support</h3>
							<p>E-Mail: <a href="mquad.io@gmail.com">info@mquad.org</a></p>
							<p>Phone No.: <span style="color: #8cc63f">+91 0120 4610 500/10</span></p>
							<p>Address: <span style="color: #8cc63f">19-A, Film City, Sector 16A, Noida, Uttar Pradesh 201301</span></p>
							<br />
						</div>
						<div class="dedooseSocial">
							<h3>Social Media</h3>
							<ul class="socialText">
								<li><a href="https://twitter.com/"><i class="fa fa-twitter" aria-hidden="true" style="color:#1da1f2; font-size:20px;"></i> Twitter</a></li>
								<li><a href="https://www.facebook.com/"><i class="fa fa-facebook-square" aria-hidden="true" style="color:#1877f2; font-size:20px;"></i> Facebook</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include('includes/footer.php'); ?>
</body>

</html>