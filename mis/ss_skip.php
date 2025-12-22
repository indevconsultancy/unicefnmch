<?php
include('includes/config.php');
$survey_id = base64_decode($_REQUEST['survey_id']);
$userID = $_SESSION['user_id'];

$sqlClient = mysqli_query($conn, "select id,survey_name,user_id,client_id from survey where id='" . $survey_id . "' and del_action='N'");
$dataqry = mysqli_fetch_array($sqlClient);
$client_id = $dataqry['client_id'];
$survey_name = $dataqry['survey_name'];

$secret = '6LdncVQqAAAAAP0q62aPxRs6GXQkh1Lbm_xOtSYM';
$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $_POST['g-recaptcha-response']);
$responseData = json_decode($verifyResponse);
if ($responseData->success) { ?>
	<?php //$response->login($_POST,$conn); 
	?>
<?php } ?>

<?php if ($_SESSION['user_id'] != "") {
	echo "<script>window.location.href='skip_userform_design.php?survey_id=" . base64_encode($survey_id) . "';</script>";
} else { ?>

	<!DOCTYPE html>
	<html lang="en">

	<head>

		<title>Web Survey | MQUAD</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<link rel="shortcut icon" href="img/mquad-fav.png"> 
		<link href="css/bootstrapV5-3.min.css" rel="stylesheet">
		<link href="css/elegant-icons-style.css" rel="stylesheet">
		<link href="css/font-awesome.min.css" rel="stylesheet">
		<link href="css/style.css" rel="stylesheet">
		<link href="css/style-responsive.css" rel="stylesheet">
		<script src="js/jquery.js"></script>
		<link href="css/select2.min.css" rel="stylesheet">
		<link href="css/select2-bootstrap.min.css" rel="stylesheet">
		<link href="<?= base_url(); ?>css/select2.min.css" rel="stylesheet">
		<style>
			body {
				/* background-image: url("img/form3.png");
				background-repeat: no-repeat;
				background-attachment: fixed;
				background-size: cover; */
				background: #fff;
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

			.panel-heading,
			.modal-header {
				background: #003b64;
				color: #ffffff;
				text-shadow: none;
			}


			.widget .widget-foot,
			.modal-footer {
				background: none !important;
				border: none !important;
			}

			.modal.show .modal-dialog{
				margin-top: 100px
			}
		</style>

	</head>

	<body class="modal-open">
		<div id="userModal" data-keyboard="false" data-bs-backdrop="static" class="modal show" tabindex="-1" role="dialog" style="display: block; padding-left: 0px;">
			<div class="modal-dialog" role="document">
				<div class="modal-content" style="background-color:white;">
					<div class="modal-header">
						<h4 class="modal-title fw-bold">Web Form</h4>
					</div>
					<div class="modal-body">
						<h4 class="fs-6 fw-bold text-dark">Please complete the reCaptcha verification before participating in the survey.</h4>
						<h4 class="fs-6 text-dark">Survey Name: <?= $survey_name ?></h4>

						<form action="" method="POST">
							<div class="input-group mt-4">
								<div class="g-recaptcha" id="recaptcha" data-sitekey="6LdncVQqAAAAALZHOplEb19MjchLsFbZvTBnNb9r" data-callback="verifyCaptcha" style="transform:scale(1);-webkit-transform:scale(.95);transform-origin:0 0;-webkit-transform-origin:0 0; text-align: center; display: block; margin: 0 auto;">
								</div>
								<span class="msg-error error" style="color:red;"></span>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" name="submit" value="submit" id="save_user" class="btn btn-primary">Submit</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Success Modal -->
		<div id="successModal" class="modal fade" tabindex="-1" role="dialog">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Success</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						Your operation was successful.
					</div>
					<div class="modal-footer">
						<button type="button" id="proceedButton" class="btn btn-primary">Proceed</button>
					</div>
				</div>
			</div>
		</div>

		<!--End add user Form-->

		<!-- jQuery --> 
		<!-- jQuery easing plugin -->
		<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js" type="text/javascript"></script>-->

		<script src="<?= base_url(); ?>js/select2.min.js"></script> 
		<script src="<?= base_url(); ?>js/jquery-3.7.1.js"></script>
		<script src="<?= base_url(); ?>js/bootstrap.bundleV5-3.min.js"></script>

		<script type="text/javascript">
			window.onload = function() {
				OpenBootstrapPopup();
			};

			function OpenBootstrapPopup() {
				$("#userModal").modal('show');
			}
		</script>
		<script src='https://www.google.com/recaptcha/api.js'></script>
		<!---------- validation for google captcha ---------->
		<script>
			$('#save_user').click(function() {
				var surveyId = "<?= base64_encode($survey_id); ?>";

				var $captcha = $('#recaptcha'),
					response = grecaptcha.getResponse();

				if (response.length === 0) {
					$('.msg-error').text("reCaptcha is mandatory");
					if (!$captcha.hasClass("error")) {
						$captcha.addClass("error");
					}
				} else {
					$('.msg-error').text('');
					$captcha.removeClass("error");
					// alert( 'reCAPTCHA marked' );
					window.location.href = "skip_userform_design.php?survey_id=" + surveyId;
					return true;
					/* var name= $('#name').val();
						var mobile= $('#mobile').val();
					
						var surveyId = "<?= $survey_id; ?>";
						var clientId = "<?= $client_id; ?>";
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
											 window.location.href="skip_userform_design.php?survey_id="+surveyId;
											 return true;
										}if(dataResult.status==0){
											
											window.location.href="skip_userform_design.php?survey_id="+surveyId;
											
										}
									
									}
								});
							}	
						}
						else{
							$("#error_message").html("All field are required, please fill all the field !");
							//alert('Please fill all the field !');
						} */

				}
			})
		</script>

		<!---------------Dublicate mobile no. Check--------------------------------->
		<script>
			/*   $(document).ready(function(){
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
  }) */
		</script>
		<!--------------------Data insert and form validation----------------------------------------->

		<!-------------------End form---------------------------------->
	</body>

	</html>
<?php } ?>