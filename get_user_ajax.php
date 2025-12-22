<?php include_once('includes/config.php'); ?>
<?php
if (isset($_POST['email'])) { 
	$email_id=mysqli_real_escape_string($conn,$_POST['email']);
	if (!filter_var($email_id, FILTER_VALIDATE_EMAIL)) {
	  $result = array("status" => 0, "message" => "<div style='color: red;'>Invalid email address. Please verify your email address</div>");
	  echo json_encode($result);
	}else{
		$email=$email_id;
		$check_username = mysqli_query($conn,"SELECT username FROM users WHERE username='".$email."'"); 
		if (mysqli_num_rows($check_username)>0) {
		   // echo '<div style="color: #FF0001;"> <b>'.$email.'</b> is already registered! </div>';  
			$result = array("status" => 0, "message" => "<b>".$email."</b> is already registered!");	
		} else {
			$result = array("status" => 1, "message" => "<div style='color: green;'><b>".$email."</b> is available! <div style='color: green;'>Please click on <b> Send OTP </b>button to confirm your email and complete the registration process.</div>");
		   // echo '<div style="color: green;"> <b>'.$email.'</b> is available! </div><div style="color: green;">Please click on <b> Send OTP  </b>button next to the OTP field to confirm your email and complete the submission process. </div>'; 
		
		}
		echo json_encode($result);
	 }
}
?>