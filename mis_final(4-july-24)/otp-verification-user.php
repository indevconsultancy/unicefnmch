<?php include_once('includes/config.php'); ?>
<?php define("title","OTP Verification | MQUAD");?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php
require("../PHPMailer-master/PHPMailer-master/src/PHPMailer.php");   
require("../PHPMailer-master/PHPMailer-master/src/SMTP.php"); 
?>
<?php 
if(isset($_POST['verify_otp'])){
	$otp=$_POST['otp'];
	$userid=$_REQUEST['uid'];
	
	$otpsql = "SELECT user_id,email,otp,name FROM users WHERE otp='$otp' and user_id='".$userid."'";
	$otpqry = mysqli_query($conn, $otpsql);
	$otpverify=mysqli_fetch_array($otpqry);
	$email=$otpverify['email'];
	$name=$otpverify['name'];
	$otpget= $otpverify['otp'];
	if($otpget==$otp){
		/* 
			$email="$email";
			$name="$name";
			$mail = new PHPMailer\PHPMailer\PHPMailer();
			$mail->IsSMTP(); // enable SMTP

			$mail->SMTPDebug = 0; // debugging: 1 = errors and messages, 2 = messages only
			$mail->SMTPAuth = true; // authentication enabled
			$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
			//$mail->Host = "smtp.gmail.com"; 
			$mail->Host = "72.125.168.184.host.secureserver.net";
			$mail->Port = 465; // or 587
			$mail->IsHTML(true);
			$mail->Username = "info@mquad.org";
			$mail->Password = "Xhv[wXnk~$(V";
			$mail->SetFrom("info@mquad.org");
			$mail->Subject = "Registration Complete: MQUAD";
			$mail->Body =  "Dear ".$name.",<br>Thank you for registering on MQUAD.";
			$mail->AddAddress($email,"$name"); 

			if(!$mail->Send()) {
				echo "Mailer Error: " . $mail->ErrorInfo;
			}else 
				{
					echo "<script> window.location.href='user-list.php';</script>";
				}
				 */
		}else{
			echo "<script>alert('Your otp is not correct !!')</script>";
		}
	}
	
?>
<!--main content start-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<section id="main-content">
   <section class="wrapper">
      <div class="row">
         <div class="col-lg-12">
            <ol class="breadcrumb">
               <li><i class="icon_documents_alt"></i>List User</li>
               <li><i class="fa fa-key" aria-hidden="true"></i>OTP Verification</li>
            </ol>
         </div>
      </div>
      <!-- page start-->    
      <div class="row">
         <div class="col-lg-12">
            
            <section class="panel">
               <header class="panel-heading">Authentication required</header>
               <div class="panel-body">
				 <p>An One Time Password (OTP) has been sent to the verified email address. Please enter it to validate this account</p>
					<div class="form">
                     <form class="form-validate form-horizontal" method="post" enctype="multipart/form-data">
                        <div class="form-group ">
							<label for="cname" class="control-label col-lg-2">Otp: <span style="color:red">*</span></label>
							<div class="col-lg-10">
								<input class="form-control" id="form_fname" name="otp" required type="text" />
							</div>
						</div> 
                        <div class="form-group">
                           <div class="col-lg-offset-2 col-lg-10 text-right">
                              <button class="btn btn-primary" type="submit" name="verify_otp">Verify</button>
                           </div>
                        </div>
                     </form>
					</div>
               </div>
            </section>
         </div>
      </div>
      <!-- page end-->
   </section>
</section>
<!--main content end-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js">

<?php include_once('includes/footer.php'); ?>