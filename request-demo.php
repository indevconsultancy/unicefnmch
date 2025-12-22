<?php include('includes/config.php');?>
<?php 
// require("PHPMailer-master//PHPMailer-master/src/PHPMailer.php");   
// require("PHPMailer-master/PHPMailer-master/src/SMTP.php");
 ?>
<?php
/*
$error='';
$successerror='';
if(isset($_REQUEST['submit'])) {
$first_name = $_REQUEST['first_name'];
$last_name = $_REQUEST['last_name'];
$company_name=$_REQUEST['company_name'];
$job_title=$_REQUEST['job_title'];
$business_email = $_REQUEST['business_email'];
  $insert="INSERT INTO request_demo set first_name='".$first_name."',business_email='".$business_email."',last_name='".$last_name."',company_name='".$company_name."',job_title='".$job_title."'"; 
	
	$query = mysqli_query($conn,$insert);
	if($query){
		$email = $business_email;//$_REQUEST['email'];
		
		$mail = new PHPMailer\PHPMailer\PHPMailer();
		$mail->IsSMTP(); // enable SMTP

		$mail->SMTPDebug = 0; // debugging: 1 = errors and messages, 2 = messages only
		$mail->SMTPAuth = true; // authentication enabled
		$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
		$mail->Host = "smtp.gmail.com";
		$mail->Port = 465; // or 587
		$mail->IsHTML(true);
		$mail->Username = "info@mquad.org";
		$mail->Password = "qdhvobecuxegyxea";  
		$mail->SetFrom($email);
		$mail->Subject = "Request Demo";
		$mail->Body = "Dear Support Team,<br>Please provide a demo on the MQUAD.";
		$mail->AddAddress('info@mquad.org',"Support Team"); 

			if(!$mail->Send()) {
				echo "Mailer Error: " . $mail->ErrorInfo;
			} else {
				$successerror='Your request has been successfully submitted';
				//echo "<script>window.location.href='index.php'</script>";
			} 
	  
	} else {
	  $error="Something went wrong !!";
	}
}
*/
?>

<!DOCTYPE html>
<html lang="en">
 
   <meta http-equiv="content-type" content="text/html;charset=utf-8" />
   <head>
       <link rel="icon" type="image/png" href="favicon.png">
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="description" content="Get in touch with  if you need support, on-on-one training or just want to leave feedback.  Contacting is just an email away." />
      <title>Request Demo</title>
      <link href="Content/CSS/bootstrap/bootstrap.css" rel="stylesheet" />
      <link href="Content/CSS/Site.css" rel="stylesheet" />
      <script src="Content/Scripts/modernizr-2.6.2.js"></script>
	   <style type="text/css">
	   
	       .PageOuter{
		   		background-color: #edf2f4;
			    margin-top: 102px;
				padding-top: 20px;
				padding-bottom: 20px;
		   }
		   .mt-2{
			   margin-top: 15px;
		   }
		   .mb-2{
			   margin-bottom: 15px;
		   }
		   .mt-0{
			   margin-top: 0px;
		   }
		   .mb-0{
			   margin-bottom: 0px;
		   }
		   ul.listing-list {
				padding-left: 18px;
			}
		   ul.listing-list li {
				margin-bottom: 12px;
			}
		   .form-conta{
			   padding: 15px;
			   background: #fff;
		   }
		   .matform fieldset {
			  margin: 0 0 3rem;
			  padding: 0;
			  border: none;
			}

			.matform .form-radio,
			.matform .form-group {
			  position: relative;
			  margin-top: 2.25rem;
			  margin-bottom: 2.25rem;
			}

			.matform .form-inline > .form-group,
			.matform .form-inline > .btn {
			  display: inline-block;
			  margin-bottom: 0;
			}

			.matform .form-help {
			  margin-top: 0.125rem;
			  margin-left: 0.125rem;
			  color: #b3b3b3;
			  font-size: 0.8rem;
			}
			.matform .checkbox .form-help, .matform .form-radio .form-help, .matform .form-group .form-help {
			  position: absolute;
			  width: 100%;
			}
			.matform .checkbox .form-help {
			  position: relative;
			  margin-bottom: 1rem;
			}
			.matform .form-radio .form-help {
			  padding-top: 0.25rem;
			  margin-top: -1rem;
			}

			.matform .form-group input {
			  height: 3.6rem;
			}
			.matform .form-group textarea {
			  resize: none;
			}
			.matform .form-group select {
			      width: 100%;
				 max-width: 100%;
				font-size: 1.6rem;
				height: 3.6rem;
				padding: 0.125rem 0 0.0625rem;
				background: none;
				border: none;
				line-height: 1.6;
				box-shadow: none;
			}
			.matform .form-group .control-label {
			  position: absolute;
			  top: 0.25rem;
			  pointer-events: none;
			  padding-left: 0.125rem;
			  z-index: 1;
			  color: #b3b3b3;
			  font-size: 1.5rem;
			  font-weight: normal;
			  -webkit-transition: all 0.28s ease;
			  transition: all 0.28s ease;
			}
			.matform .form-group .bar {
			  position: relative;
			  border-bottom: 0.0625rem solid #999;
			  display: block;
			}
			.matform .form-group .bar::before {
			  content: '';
			  height: 0.125rem;
			  width: 0;
			  left: 50%;
			  bottom: -0.0625rem;
			  position: absolute;
			  background: #337ab7;
			  -webkit-transition: left 0.28s ease, width 0.28s ease;
			  transition: left 0.28s ease, width 0.28s ease;
			  z-index: 2;
			}
			.matform .form-group input,
			.matform .form-group textarea {
			  display: block;
			  background: none;
			  padding: 0.125rem 0.125rem 0.0625rem;
			  font-size: 1.6rem;
			  border-width: 0;
			  border-color: transparent;
			  line-height: 1.9;
			  width: 100%;
				max-width: 100%;
			  color: transparent;
			  -webkit-transition: all 0.28s ease;
			  transition: all 0.28s ease;
			  box-shadow: none;
			}
			.matform .form-group input[type="file"] {
			  line-height: 1;
			}
			.matform .form-group input[type="file"] ~ .bar {
			  display: none;
			}
			.matform .form-group select,
			.matform .form-group input:focus,
			.matform .form-group input:valid,
			.matform .form-group input.form-file,
			.matform .form-group input.has-value,
			.matform .form-group textarea:focus,
			.matform .form-group textarea:valid,
			.matform .form-group textarea.form-file,
			.matform .form-group textarea.has-value {
			  color: #333;
			}
			.matform .form-group select ~ .control-label,
			.matform .form-group input:focus ~ .control-label,		
			.matform .form-group input:valid ~ .control-label,
			.matform .form-group input:active ~ .control-label,
			.matform .form-group input.form-file ~ .control-label,
			.matform .form-group input.has-value ~ .control-label,
			.matform .form-group textarea:focus ~ .control-label,
			.matform .form-group textarea:valid ~ .control-label,
			.matform .form-group textarea.form-file ~ .control-label,
			.matform .form-group textarea.has-value ~ .control-label {
			  font-size: 1.1rem;
			  color: gray;
			  top: -1rem;
			  left: 0;
			}
			.matform .form-group select:focus,
			.matform .form-group input:focus,
			.matform .form-group textarea:focus {
			  outline: none;
			}
			.matform .form-group select:focus ~ .control-label,
			.matform .form-group input:focus ~ .control-label,
			.matform .form-group textarea:focus ~ .control-label {
			  color: #337ab7;
			}
			.matform .form-group select:focus ~ .bar::before,
			.matform .form-group input:focus ~ .bar::before,
			.matform .form-group textarea:focus ~ .bar::before {
			  width: 100%;
			  left: 0;
			}
			.matform .checkbox label,
			.matform .form-radio label {
			  position: relative;
			  cursor: pointer;
			  padding-left: 3rem;
			  text-align: left;
			  color: #333;
			  display: block;
			}
			.matform .checkbox input,
			.matform .form-radio input {
			  width: auto;
			  opacity: 0.00000001;
			  position: absolute;
			  left: 0;
			}

			.matform .radio {
			  margin-bottom: 1rem;
			}
			.matform .radio .helper {
			  position: absolute;
			  top: -0.25rem;
			  left: -0.25rem;
			  cursor: pointer;
			  display: block;
			  font-size: 1rem;
			  -webkit-user-select: none;
				 -moz-user-select: none;
				  -ms-user-select: none;
					  user-select: none;
			  color: #999;
			}
			.matform .radio .helper::before,.matform .radio .helper::after {
			  content: '';
			  position: absolute;
			  left: 0;
			  top: 0;
			  margin: 0.25rem;
			  width: 1rem;
			  height: 1rem;
			  -webkit-transition: -webkit-transform 0.28s ease;
			  transition: -webkit-transform 0.28s ease;
			  transition: transform 0.28s ease;
			  transition: transform 0.28s ease, -webkit-transform 0.28s ease;
			  border-radius: 50%;
			  border: 0.125rem solid currentColor;
			}
			.matform .radio .helper::after {
			  -webkit-transform: scale(0);
					  transform: scale(0);
			  background-color: #337ab7;
			  border-color: #337ab7;
			}
			.matform .radio label:hover .helper {
			  color: #337ab7;
			}
			.matform .radio input:checked ~ .helper::after {
			  -webkit-transform: scale(0.5);
					  transform: scale(0.5);
			}
			.matform .radio input:checked ~ .helper::before {
			  color: #337ab7;
			}

			.matform .checkbox {
			  margin-top: 3rem;
			  margin-bottom: 1rem;
			}
			.checkbox .helper {
			  color: #999;
			  position: absolute;
			  top: 0;
			  left: 0;
			  width: 1.8rem;
			  height: 1.8rem;
			  z-index: 0;
			  border: 0.125rem solid currentColor;
			  border-radius: 0.0625rem;
			  -webkit-transition: border-color 0.28s ease;
			  transition: border-color 0.28s ease;
			}
			.matform .checkbox .helper::before, .matform .checkbox .helper::after {
			  position: absolute;
			  height: 0;
			  width: 0.2rem;
			  background-color: #337ab7;
			  display: block;
			  -webkit-transform-origin: left top;
					  transform-origin: left top;
			  border-radius: 0.25rem;
			  content: '';
			  -webkit-transition: opacity 0.28s ease, height 0s linear 0.28s;
			  transition: opacity 0.28s ease, height 0s linear 0.28s;
			  opacity: 0;
			}
			.matform .checkbox .helper::before {
			     top: 1.2rem;
    			left: 1rem;
			  -webkit-transform: rotate(-135deg);
					  transform: rotate(-135deg);
			  box-shadow: 0 0 0 0.0625rem #fff;
			}
			.matform .checkbox .helper::after {
			  top: 0.5rem;
    			left: 0.2rem;
			  -webkit-transform: rotate(-45deg);
					  transform: rotate(-45deg);
			}
			.matform .checkbox label:hover .helper {
			  color: #337ab7;
			}
			.matform .checkbox input:checked ~ .helper {
			  color: #337ab7;
			}
			.matform .checkbox input:checked ~ .helper::after, .matform .checkbox input:checked ~ .helper::before {
			  opacity: 1;
			  -webkit-transition: height 0.28s ease;
			  transition: height 0.28s ease;
			}
			.matform .checkbox input:checked ~ .helper::after {
			  height: 1rem;
			}
			.matform .checkbox input:checked ~ .helper::before {
			  height: 2rem;
			  -webkit-transition-delay: 0.28s;
					  transition-delay: 0.28s;
			}

			.matform .radio + .radio,
			.matform .checkbox + .checkbox {
			  margin-top: 1rem;
			}

			.matform .has-error .legend.legend, .has-error.form-group .control-label.control-label {
			  color: #d9534f;
			}
			.matform .has-error.form-group .form-help,
			.matform .has-error.form-group .helper, .matform .has-error.checkbox .form-help,
			.matform .has-error.checkbox .helper, .matform .has-error.radio .form-help,
			.matform .has-error.radio .helper, .matform .has-error.form-radio .form-help,
			.matform .has-error.form-radio .helper {
			  color: #d9534f;
			}
			.matform .has-error .bar::before {
			  background: #d9534f;
			  left: 0;
			  width: 100%;
			}

			.matform .button {
			 width: 100%;
			position: relative;
			background: #4f93ce;
			border: 1px solid #4f93ce;
			font-size: 1.7rem;
			color: #fff;
			margin: 3rem 0;
			padding: 1rem 3rem;
			cursor: pointer;
			-webkit-transition: background-color 0.28s ease, color 0.28s ease, box-shadow 0.28s ease;
			transition: background-color 0.28s ease, color 0.28s ease, box-shadow 0.28s ease;
			overflow: hidden;
			box-shadow: 0 2px 2px 0 rgb(0 0 0 / 14%), 0 3px 1px -2px rgb(0 0 0 / 20%), 0 1px 5px 0 rgb(0 0 0 / 12%);
			}
			.matform .button span {
			  color: #fff;
			  position: relative;
			  z-index: 1;
			}
			.matform .button::before {
			  content: '';
			  position: absolute;
			  background: #071017;
			  border: 50vh solid #1d4567;
			  width: 30vh;
			  height: 30vh;
			  border-radius: 50%;
			  display: block;
			  top: 50%;
			  left: 50%;
			  z-index: 0;
			  opacity: 1;
			  -webkit-transform: translate(-50%, -50%) scale(0);
					  transform: translate(-50%, -50%) scale(0);
			}
			.matform .button:hover {
			  color: #fff;
			  box-shadow: 0 6px 10px 0 rgba(0, 0, 0, 0.14), 0 1px 18px 0 rgba(0, 0, 0, 0.12), 0 3px 5px -1px rgba(0, 0, 0, 0.2);
			}
			.matform .button:active::before, .matform .button:focus::before {
			  -webkit-transition: opacity 0.28s ease 0.364s, -webkit-transform 1.12s ease;
			  transition: opacity 0.28s ease 0.364s, -webkit-transform 1.12s ease;
			  transition: transform 1.12s ease, opacity 0.28s ease 0.364s;
			  transition: transform 1.12s ease, opacity 0.28s ease 0.364s, -webkit-transform 1.12s ease;
			  -webkit-transform: translate(-50%, -50%) scale(1);
					  transform: translate(-50%, -50%) scale(1);
			  opacity: 0;
			}
			.matform .button:focus {
			  outline: none;
			}
			.matform > h2{
				font-weight:400;
			}
	   </style>
   </head>
   <?php include('includes/header.php');?>
   <body>
      <div class="PageOuter">
		<div class="container-fluid">
		  <div class="row">
			<div class="col-md-8">
			  <h2 class="mt-0">The most sophisticated online survey platform on the planet</h2>
				<p class="mt-2 mb-2">Get answers to your most important customer, brand, employee, and product questions with survey software that can handle everything from simple questionnaires to detailed research projects for the world’s biggest brands.</p>
				<ul class="mb-2 listing-list">
					<li>Design surveys with our intuitive drag-and-drop tool, 50+ survey templates, and 100+ question types</li>
					<li>Reach respondents wherever they are, with surveys on mobile devices, apps, websites, and more</li>
					<li>Integrate your surveys into your existing systems like Salesforce, Marketo, and Adobe</li>
				</ul>
				<img src="https://mquad.org/Content/Images/video.jpg" class="img-responsive"/>
		    </div>
			<div class="col-md-4">
			    <div class="form-conta">
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
					<form class="matform" action="" method="POST">
						<h2>Request Demo</h2>

						<div class="form-group">
						  <input type="text" name="first_name" required="required"/>
						  <label for="input"  class="control-label">First Name <sup style="color:red">*</sup></label><i class="bar"></i>
						</div>
						 <div class="form-group">
						  <input type="text" name="last_name" required="required"/>
						  <label for="input" class="control-label">Last Name <sup style="color:red">*</sup></label><i class="bar"></i>
						</div>
						
						<div class="form-group">
						  <input type="text" name="company_name" required="required"/>
						  <label for="input" class="control-label">Company Name <sup style="color:red">*</sup></label><i class="bar"></i>
						</div>
						
						
						<div class="form-group">
						  <input type="text" name="job_title" required="required"/>
						  <label for="input" class="control-label">Job Title <sup style="color:red">*</sup></label><i class="bar"></i>
						</div>
						<div class="form-group">
						  <input type="email" name="business_email" required="required"/>
						  <label for="input" class="control-label">Business Email ID<sup style="color:red">*</sup></label><i class="bar"></i>
						</div>
						<!-- <div class="form-group">
						  <input type="number" required="required"/>
						  <label for="input" class="control-label">Phone Number <sup>*</sup></label><i class="bar"></i>
						</div>
						 <div class="form-group">
						  <select>
							<option>India</option>
							<option>Hong Kong, China</option>
						  </select>
						  <label for="select" class="control-label">Country</label><i class="bar"></i>
						</div>-->

						<div class="checkbox">
						  <label>
							<input type="checkbox" checked="checked"/><i class="helper"></i> Agree to T & C
						  </label>
						</div>

						<button type="submit" class="button mt-3" data-testid="" name="submit" style="margin-top: 10px !important;">Submit</button>

					</form>
				</div>
		    </div>
		  </div>
	    </div>
        
    </div>
   <?php include('includes/footer.php');?>
   </body>
</html>