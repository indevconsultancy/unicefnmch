<?php include('includes/config.php');?>
<?php //include('includes/functions.php');?>


<?php

	$success='';
	$error='';
	// session_start();
	session_destroy();
	
	if(isset($_REQUEST['registration_submit'])){
		$email=mysqli_real_escape_string($conn,$_POST['email']);
		
		$name=mysqli_real_escape_string($conn,$_POST['name']);
		$registered_as=mysqli_real_escape_string($conn,$_POST['registered_as']);
		//$member_id =mysqli_real_escape_string($conn,$_POST['member_id']);
		$otp=$_POST['otp'];
		
		if($_SESSION['step']=='otp_step'){
			$insetssql="insert into clients set name='".$name."',email='".$email."',role_id='3',registered_as='".$registered_as."',membership_id='0'";
			$datasql=mysqli_query($conn,$insetssql);
			if($datasql!=''){
				//session_destroy();
				echo "<script>window.location.href='welcome.php'</script>";
			}
		}
		$getotp=(rand(0,1000000));
		if($_SESSION['getotp']==''){
			if(!empty($email)){
				
				$_SESSION['getotp']=$getotp;
				
				$_SESSION['email']=$email;
				/*$mailto=$email;
				$message_all = '';
				$message_all= "Dear User,<br>";
				$message_all.= "Account registration otp: ".$getotp;
				
				$txt = $message_all;
				$subject=base64_encode("OTP for MQUAD Registration");
				$message=base64_encode($txt);
				
				$sendmail=send_mail_function($mailto,$message,$subject);
				
				if($sendmail['status']==1){
					$success = "We have sent a One Time Password (OTP) to the Email ID. Please enter to complete the verification.";
				}
				 else {
					$error = "something went wrong!";
				} */
				$success = "We have sent a One Time Password (OTP) to the Email ID. Please enter to complete the verification.";
				
			}
		}else if($_SESSION['getotp']!=''){
			
			if($_SESSION['getotp']==$otp){
			 	
				$_SESSION['step']='otp_step';
				$success= "OTP verification successfully,Please fill basic details to complete the registration ";
				unset($_SESSION['getotp']);
				
				// echo $_SESSION['email']=$email;
				
			}else{
				$error="Please enter correct OTP!!";
			}
		}
	}	
   ?> 
<!DOCTYPE html>
<html lang="en">
   <!-- Added by HTTrack -->
   <meta http-equiv="content-type" content="text/html;charset=utf-8" />
   <!-- /Added by HTTrack -->
   <head>
      <link rel="icon" type="image/png" href="favicon.png">
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="description" content="Sign up for your 1-month FREE MQUAD account!  Several discounts may apply, feel free to contact MQUAD about small/large groups, eductional and Enterprise discounts." />
      <title>Signup | MQUAD</title>
      <link href="Content/CSS/bootstrap/bootstrap.css" rel="stylesheet" />
      <link href="Content/CSS/Site.css" rel="stylesheet" />
      <script src="Content/Scripts/modernizr-2.6.2.js"></script>
      <script async src="https://www.googletagmanager.com/gtag/js?id=UA-122580788-1"></script>
	  <!-- Cropper image CSS -->
      <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/cropper/2.3.4/cropper.min.css'>
      <!-- Cropper image JS -->
      <script src='https://cdnjs.cloudflare.com/ajax/libs/cropperjs/0.8.1/cropper.min.js'></script>
      <script>
         window.dataLayer = window.dataLayer || [];
         function gtag(){dataLayer.push(arguments);}
         gtag('js', new Date());
         
         gtag('config', 'UA-122580788-1');
      </script>
   </head>
   <style type="">
      .introPanel > div > div > .homeIntroBoxText > h3 {
      margin-top: 0;
      margin-bottom: 20px;
      }
      h3 {
      font-family: 'Poppins', sans-serif;
      color: white;
      margin: 0 0 26px;
      line-height: 1.2;
      }
      .ml-5{
      margin-top: 25px;
      margin-left:-26px;
      background-color:white;
      }
      .awesomePanel .homeAwesomeBox {
      min-height: auto;
      }
      .homeAwesomeBox > .div-box {
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
      margin-top:15px;
      }
      .card_title_sub{
      text-align: center;
      }
      .fa-fa-icon{
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
      .modal-title{
      color:#003b64;
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
      .modal-body input, .modal-body select, .modal-body textarea {
      max-width: 100%;
      }
      .modal-body .box {
      border: none !important;
      }	
      .col-form-label{
      font-weight: 300;
      }
 
      .box-option {
      padding: -6.5em;
      width: 100%;
      margin: 0.5em;
      }
      .box-2-option {
      padding: -2.5em;
      float:left;
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
      .options label, .options input {
      width: 5em;
      padding: 0.5em 1em;
      }
      #imgcode{
      width:97px;
      height:200px;
      }
      #statususer{
      color:red;
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
   .badge-primary{
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
#logo_error{
	color:red;
}
   </style>
    <?php include('includes/header.php');?>
   <body>
      <div class="signUpOuter">
         <div class="signUpMain">
            <div class="header">
               <div class="headerImage">
                  <img src="Content/Images/Signup/signup-circle.svg" alt="Signup For MQUAD" title="Sign Up With MQUAD!" />
               </div>
			
               <div class="headerTitle">
                  <h2>SIGN UP </h2>
               </div>
            <?php echo $_SESSION['getotp'];?>
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
				   <?php }else if($success!=''){ ?>
					   <div class="alert alert-success" role="alert">
					  <?=$success;?>
					  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					  </button>
					</div>
				   <?php } ?>
			   </div>
			</div>
			    <div class="signUpMainContents">
               <div class="signUpMainContent">
                  <div>
                     <div class="dedoooseSignupForm">
                        <form action="" method="post" novalidate>
                           <span class="rightWarning">Required *</span>
							<?php if($_SESSION['getotp']==''){ ?>
                           <div class="form-group	">
                              <label for="txtEmailAddress">Email ID <span style="color:red;">*</span></label>
                              <span class="formControlSpan"><input class="form-control" type="email" value="<?=@$_SESSION['email'];?>" <?php if($_SESSION['getotp']!=''){ echo "required";}else{ echo "required";} ?>  name="email" placeholder="Enter your Email ID"></span>
							  <p id="statususer"></p>
                           </div>
							<?php } ?>
							<div class="form-group" style="<?php if($_SESSION['getotp']!=''){ echo "display:block";}else{ echo "display:none";} ?>">
							  <label for="txtReferredBy">OTP Verification</label> <span style="color:red;">*</span>
							  <span class="formControlSpan"><input type="text" class="form-control" id="otp" name="otp" placeholder="Enter your OTP"></span>
							</div>
							<!--<div class="row" id="detail-reg">-->
							<?php if($_SESSION['step']=='otp_step'){ ?>
								<div class="form-group ">
									<label for="txtUsername">Registered As</label> <span style="color:red;">*</span>
									  <span class="formControlSpan">
									  <select class="form-control" name="registered_as" id="registed_as" placeholder="Registered As *" required>
										<option value="">Registered As </option>
										<option value="individual"<?php if(isset($_REQUEST['registered_as'])){ echo "selected";} ?>>Individual</option>
										<option value="organization"<?php if(isset($_REQUEST['registered_as'])){ echo "selected";} ?>>Organization</option>
									 </select>
								  </span>
							   </div>
							   <div class="form-group ">
								  <label for="txtReferredBy">Name</label> <span style="color:red;">*</span>
								  <span class="formControlSpan"><input type="text" class="form-control" id="form_fname" required name="name" placeholder="Enter your name"></span>
							   </div>
							   
								
								<!----------------logo upload------------------->
								<div class="form-group ">
									<input type="hidden" id="imgcode" name="imglogo"/>
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
							<?php } ?>
								<div class="row">
									<div class="col-md-7">
										<div class="form-group mb-3">
										<label for="txtEmailAddress">Enter Captcha <span style="color:red;">*</span></label>
											<input type="text" name="captcha" id="captcha" class="form-control" placeholder="Enter Captcha *" required="" autocomplete="off">
										</div>
									</div>
									<div class="col-md-3 mt-1 captcha-column">
										
										<img src="captcha.php" id="captch-img" width="100%">
									</div>
									<div class="col-md-2 mt-4" style="padding: 13px;">
										<label for="refresh"></label>
										<button type="button" class="site-button site-button-dark" onclick="document.getElementById('captch-img').src = 'captcha.php?' + Math.random(); document.getElementById('captcha').value = '';return false;"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</a></button>
									</div>
								</div>
								<!----------------End logo upload------------------->
								<div class="submitForm mb-2 hide">
								 <!-- <a  type="submit" id="register" name="submit_data" value="submit" data-content-piece="Signup Signup Form Button">Submit</a>-->
									<button type="submit" class="btn btn-primary " id="register" name="submit_data" style="float:right">Submit</button>
								</div>
							<!--</div>-->
							<div class="submitForm mb-2">
								<input type="submit" class="btn btn-primary " id="" name="registration_submit" style="float:right" value="Submit">
							</div>
                        </form>
                     </div>
                  </div>
                  
               </div>
            </div>
				
      <!---------------- End Signup modal---------------------- -->
 <?php include('includes/footer.php');?>
 <script>
	 function memRegister(val){
		$("#member_id").attr('value',val);
	 }
  </script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script>
	 $(document).ready(function(){
		$('#email').keyup(function(){
			var email=$(this).val();
			if(email.length >= 3){
			$.ajax({
				url:"get_user_ajax.php",
				method:"POST",
				data:{email:email},
				dataType:"text",
				success:function(html)
				{
					//alert(html);
					$('#statususer').html(html);
					
				}
			})
			}
		})
	 })
  </script> 
<script type="text/javascript">
//Name Validation
 $(function() {
	$("#fname_error_message").hide();
	   var error_fname = false;
	$("#form_fname").focusout(function(){
	   check_fname();
	});
	function check_fname() {
	   var pattern = /^[a-zA-Z ]*$/;
	   var fname = $("#form_fname").val();
	   if (pattern.test(fname) && fname !== '') {
		  $("#fname_error_message").hide();
			$('#register').attr("disabled",false);
		} else {
		  $("#fname_error_message").html("Should contain only Characters and white space allow");
		  $("#fname_error_message").show();
			$('#register').attr("disabled",true);
		  //$("#form_fname").css("border-bottom","2px solid #F90A0A");
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
		$('#register').attr("disabled",true);
		$("#logo_error").show();
	  } else {
		//alert("Is an image")
		$("#logo_error").hide();
		$('#register').attr("disabled",false);
	  }

	});
  });
 </script>

<script>
 <!------------start logo crop--------------->
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
	$("#imgcode").attr("value",imgSrc);
   cropped.src = imgSrc;
 
 });
</script>
 <!------------End logo upload--------------->
   </body>
</html>