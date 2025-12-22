<?php
include('includes/config.php');
$survey_id=$_REQUEST['survey_id'];
 $userID=$_SESSION['user_id'];
 
$sqlClient=mysqli_query($conn,"select id,user_id,client_id from survey where id='".$survey_id."' and del_action='N'");
$dataqry=mysqli_fetch_array($sqlClient);
$client_id=$dataqry['client_id'];
//{ 

 $secret='6LfBM8QjAAAAAHtXsxMlZ3AQRzGUw0_vUM9gRaQI';
 $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $_POST['g-recaptcha-response']);
 $responseData = json_decode($verifyResponse);
 if($responseData->success){?>
  <?php //$response->login($_POST,$conn); ?> 
 <?php } ?>
 
 <?php if($_SESSION['user_id']!=""){ echo "<script>window.location.href='skip_userform_1.php?survey_id=".$survey_id."';</script>"; }else{ ?> 
	<?php //} ?> 
<!DOCTYPE html>
<html lang="en">

<head>
	
	<title>Web Form | MQUAD</title>
	<meta charset="utf-8" >
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" >
	<link rel="shortcut icon" href="img/mquad-fav.png">
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/bootstrap-theme.css" rel="stylesheet">
	<link href="css/elegant-icons-style.css" rel="stylesheet" >
	<link href="css/font-awesome.min.css" rel="stylesheet" >
	<link href="css/style.css" rel="stylesheet">
	<link href="css/style-responsive.css" rel="stylesheet" >
	<script src="js/jquery.js"></script>
	<link href="css/select2.min.css" rel="stylesheet" >
	<link href="css/select2-bootstrap.min.css" rel="stylesheet" >
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" >
	<style>
	body  {
		background-image: url("img/form3.png");
		background-repeat: no-repeat;
		background-attachment: fixed;
		background-size: cover;
		 
		}
		.thankyou-page ._header {
		padding: 45px 30px;
		padding: 46px;
		text-align: center;
		background: #003b64;
		}
		
		msg-error {
  color: red;
}
.g-recaptcha.error {
  padding: .2em;
  width: 19em;
}
	</style>
	 
	</head>

<body class="modal-open">
	 
	
	<div id="userModal" class="modal model-boxskip show" tabindex="-1" role="dialog" style="display: block; padding-left: 0px;">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="background-color:white;">
            <div class="modal-header">
                <h2 class="modal-title">User Form</h2>
            </div>
				<div class="modal-body">
				<h4><span style="margin-bottom:10px;">Please fill your basic details before participating in the survey !</span></h4>
				<span id="error_message" style="color:red;"></span>
				
				<form action="" method="POST" > 
				  <div class="form-group">
					<label for="recipient-name" class="control-label">Full Name <span style="color:red;">*</span></label>
					<input type="text" class="form-control" id="name" name="name" >
				  </div>
				  <div class="form-group">
					<label for="message-text" class="control-label">Mobile Number <span style="color:red;">*</span></label>
					<input type="number" class="form-control" id="mobile" name="mobile" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="10" required="">
					 <span class="error_form" id="mobile_error_message" style="color:red;"></span>
					 <p id="availability"></p> 
				  </div> 
				  <div class="input-group">
                <div class="g-recaptcha" id="recaptcha" data-sitekey="6LfBM8QjAAAAADeAAyJtPNdL1smF3FwvtFt295Oj" data-callback="verifyCaptcha" style="transform:scale(1);-webkit-transform:scale(.95);transform-origin:0 0;-webkit-transform-origin:0 0; text-align: center; display: block; margin: 0 auto;" >
		        </div>
				<span class="msg-error error" style="color:red;"></span>
                </div>
				</form></div>
				<div class="modal-footer">
					<button type="button" name="submit" value="submit" id="save_user" class="btn btn-primary">Submit</button>
				</div>
        </div>
    </div>
</div>

	<!--End add user Form-->

<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script>
<!-- jQuery easing plugin -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<script type="text/javascript">
    window.onload = function () {
        OpenBootstrapPopup();
    };
    function OpenBootstrapPopup() {
        $("#userModal").modal('show');
    }
</script>
<script src='https://www.google.com/recaptcha/api.js'></script>
<!---------- validation for google captcha ---------->
<script>
$( '#save_user' ).click(function(){
  var $captcha = $( '#recaptcha' ),
      response = grecaptcha.getResponse();
  
  if (response.length === 0) {
    $( '.msg-error').text( "reCaptcha is mandatory" );
    if( !$captcha.hasClass( "error" ) ){
      $captcha.addClass( "error" );
    }
  } else {
    $( '.msg-error' ).text('');
    $captcha.removeClass( "error" );
    // alert( 'reCAPTCHA marked' );
	var name= $('#name').val();
		var mobile= $('#mobile').val();
	
		var surveyId = "<?=$survey_id;?>";
		var clientId = "<?=$client_id;?>";
		if(name!="" && mobile!=""){
			if(mobile.length < 10 || mobile.length > 10){
				$("#mobile_error_message").html("Mobile No. is not valid, Please Enter 10 Digit Mobile No.");
				return false;
			}else{
				$.ajax({
					url:"ajax/skip_user_insert.php",
					method:"post",
					data:{
						nameuser:name,
						mobileuser:mobile,
						clientId:clientId
					},
					success:function (data){
						var dataResult = JSON.parse(data);
						if(dataResult.status==1){
							 window.location.href="skip_userform_1.php?survey_id="+surveyId;
							 return true;
						}if(dataResult.status==0){
							
							window.location.href="skip_userform_1.php?survey_id="+surveyId;
							// $("#error_message").html("Mobile No. is already registered!");
							//return false;
						}
					
					}
				});
			}	
		}
		else{
			$("#error_message").html("All field are required, please fill all the field !");
			//alert('Please fill all the field !');
		}

  }
})
</script>

<!---------------Dublicate mobile no. Check--------------------------------->
<script>
  $(document).ready(function(){
      $('#mobile').keyup(function(){
	  var mobile=$(this).val();
	  if(mobile.length >= 7){
		  $.ajax({
		  url:"ajax/get_user_skip.php",
		  method:"POST",
		  data:{mobile:mobile},
		  dataType:"text",
		  success:function(html)
		  {
		  $('#availability').html(html);
		 //  $('#save_user').attr("disabled",true);
		  }
		  })
        }
      })
  })
</script>
<!--------------------Data insert and form validation----------------------------------------->
 
 <!-------------------End form---------------------------------->
</body>
</html>
<?php } ?>
