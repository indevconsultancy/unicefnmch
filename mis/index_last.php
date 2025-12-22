<?php include_once('includes/config.php'); ?>
<?php //include_once('includes/functions.php'); ?>
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
  <title>Login | MQUAD</title>
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

.login-form a:hover{
	color: #34aadc;
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
				<span id="loginError"></span>
				
					<?php if($_SESSION['successerror']){ ?>
					<div class="alert alert-success alert-dismissible fade show" role="alert">
					<?=$_SESSION['successerror'];?>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
				   <?php } if($_SESSION['login_attempts']>=3){
						$error='';
				   ?>
					<p style="color:red;">You have completed 3 attempts. Your account has been blocked, please try again after 15 minutes</p>
					<?php } 
					if($error!=''){
					?>
					<div class="alert alert-danger alert-dismissible fade show" role="alert">
					<?=$error;?>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
					<?php } ?>
				</div>
			</div>
			<div class="input-group">
			  <span class="input-group-addon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
			  <input type="text" class="form-control" name="username" id="username" placeholder="Username" autofocus>
			</div>
			<div class="input-group">
			  <span class="input-group-addon"><i class="icon_key_alt"></i></span>
			  <input type="password" id="password" class="form-control" name="password" placeholder="Password">
			<span class="input-group-addon float-right"> <a href="javascript:void(0)" onclick="myFunction();"> <i class="fa fa-eye" aria-hidden="true"></i> </a> </span>
			</div>
			<div class="input-group">
				<p><img src="captcha.php" id="ref_captcha" width="160" height="40" border="0"/>
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
		
			<label class="checkbox d-block text-end">
				<u> <a href="forgot_password.php"> Forgot Password?</a></u> 
			</label>
			<?php 
			/* if($_SESSION['login_attempts']>=3){
				$_SESSION['locked']=time();
				echo "<p>Your account has been blocked, please try after 10 sec</p>";
			}else{ ?>
				<button class="btn btn-primary btn-lg btn-block" type="submit">Login</button>
			<?php	}  */?>
				<button class="btn btn-primary btn-lg btn-block w-100 userLogin" type="submit" <?php if($_SESSION['login_attempts']>=3){ $_SESSION['locked']=time(); echo "disabled"; }?>>Login</button> <br/>
				<p class="text-center mt-3" style="font-weight:700;color: #5f8899;">Don't have an account? <a href="../registration.php" style="margin-top:10px; text-align:center;  ">  Register Here </a></p>
		</div>
	</form>
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


				$(".userLogin").on("click", function(event){
        	    event.preventDefault();
				var CryptoJSAesJson = {
					stringify: function (cipherParams) {
						var j = {ct: cipherParams.ciphertext.toString(CryptoJS.enc.Base64)};
						if (cipherParams.iv) j.iv = cipherParams.iv.toString();
						if (cipherParams.salt) j.s = cipherParams.salt.toString();
						return JSON.stringify(j);
					},
					parse: function (jsonStr) {
						var j = JSON.parse(jsonStr);
						var cipherParams = CryptoJS.lib.CipherParams.create({ciphertext: CryptoJS.enc.Base64.parse(j.ct)});
						if (j.iv) cipherParams.iv = CryptoJS.enc.Hex.parse(j.iv)
						if (j.s) cipherParams.salt = CryptoJS.enc.Hex.parse(j.s)
						return cipherParams;
					}
				}
				
				$("#loginError").html('');
        	    let uname = $("#username").val();
        	    let upass = $("#password").val();
        	    let captcha = $("#captcha").val();
        	    if(uname!="" & upass!="" & captcha!=""){
        	        var encPass = upass;
					var encrypted = CryptoJS.AES.encrypt(JSON.stringify(encPass), "<?=ENC_KEY;?>", {format: CryptoJSAesJson}).toString();
        	        $.ajax({
            			url: "ajax/loginajax.php",
            			type: "post",
            			data: {username:uname,password:encrypted,captcha:captcha},
            			dataType:"json",
            			success: function (res) {
            				console.log(res);
							let result=res;
								if(result.restatus==1)
								{
									window.location.href='https://mquad.org/mis/dashboard_new.php';
								
								}
								else{
            				    $("#loginError").html('<div class="alert alert-danger alert-dismissible fade show">'+result.error+'</div>');
								}
            				
							
            				//captchaRefresh();
            			}
            	    });
        	    }else{
        	        $("#loginError").html('<div class="alert alert-danger alert-dismissible fade show">All fields are required.</div>');
        	    }
        	    
        	});
</script>
