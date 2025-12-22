<?php include_once('includes/config.php'); ?>
<?php define("title","OTP Verification | MQUAD");?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php 
$survey_id=$_REQUEST['survey_id'];
$pages='';
  if($_GET['page']!=''){
	 $pages=$_GET['page'];
 }else{
	$pages='1'; 
 }
 
if(isset($_POST['verify_otp'])){
	$error='';
	 $otp=$_POST['otp'];
	 $sqlsuser=mysqli_query($conn,"SELECT users.user_id,users.email,users.name,survey.survey_name,survey.otp FROM `survey` inner join users on survey.user_id=users.user_id where survey.id='".$survey_id."' and survey.otp='".$otp."'");
		$qryusersurvey=mysqli_fetch_array($sqlsuser);
		$survey_name=$qryusersurvey['survey_name'];
		$user_id=$qryusersurvey['user_id'];
		$email=$qryusersurvey['email'];
		$name=$qryusersurvey['name'];
		$otp=$qryusersurvey['otp'];
		if($otp==$otp){
			
			$deleteformsql=mysqli_query($conn,"DELETE FROM survey WHERE id='".$survey_id."'");
			$deleteformsql=mysqli_query($conn,"DELETE FROM survey_data_monitoring WHERE survey_name_id='".$survey_id."'");
			$deleteformsql=mysqli_query($conn,"DELETE FROM assign_survey WHERE survey_id='".$survey_id."'");
			$deleteformsql=mysqli_query($conn,"DELETE FROM questions WHERE survey_id='".$survey_id."'");
			$deleteformsql=mysqli_query($conn,"DELETE FROM questions_language WHERE survey_id='".$survey_id."'");
			$deleteformsql=mysqli_query($conn,"DELETE FROM options WHERE survey_id='".$survey_id."'");
			$deleteformsql=mysqli_query($conn,"DELETE FROM options_language WHERE survey_id='".$survey_id."'");
			$deleteformsql=mysqli_query($conn,"DELETE FROM questions_group WHERE survey_id='".$survey_id."'");
			$deleteformsql=mysqli_query($conn,"DELETE FROM normal_groups WHERE survey_id='".$survey_id."'");
			$deleteformsql=mysqli_query($conn,"DELETE FROM report_format WHERE survey_id='".$survey_id."'");
			$deleteformsql=mysqli_query($conn,"DELETE FROM survey_loockups WHERE survey_id='".$survey_id."'");
			$deleteformsql=mysqli_query($conn,"DELETE FROM survey_document WHERE survey_name_id='".$survey_id."'");
			$deleteformsql=mysqli_query($conn,"DELETE FROM media_question_data WHERE survey_name_id='".$survey_id."'");
				
			if($deleteformsql){
				$mailto=$email;
				$name=$name;
				$survey_name=$survey_name;
				$message_all = '';
				$message_all = "Dear " . $name .",<br>";
				$message_all.= "Your Form <b>".$survey_name."</b> has been successfully removed from your account";
				
				$txt = $message_all;
				$subject=base64_encode("MQUAD Form Delete");
				$message=base64_encode($txt);
				$sendmail=send_mail_function($mailto,$message,$subject);
				if($sendmail['status']==1){
					$_SESSION['status'] = "Form has been deleted successfully";
					$_SESSION['status_code'] = "success";
				echo "<script> window.location.href='survey-list.php?&page=$pages';</script>";
				}
				 else {
					$_SESSION['status_error'] = "Something went wrong!!";
					$_SESSION['status_error_code'] = "warning";
				} 
			}
		}
		$_SESSION['status_error'] = "Please enter a valid OTP";
		$_SESSION['status_error_code'] = "warning";
	}
	
?>
<section id="main-content">
   <section class="wrapper">
      <div class="row">
         <div class="col-lg-12">
            <ol class="breadcrumb">
               <li><i class="icon_documents_alt"></i>List Form</li>
               <li><i class="fa fa-key" aria-hidden="true"></i>OTP Verification</li>
            </ol>
         </div>
      </div>
      <!-- page start-->    
      <div class="row">
         <div class="col-lg-12">
            
            <section class="panel">
               <header class="panel-heading">Authentication Required</header>
               <div class="panel-body">
				 <p>The One Time Password (OTP) has been sent to the verified email address. Please enter it to permanently remove the form and associated data from MQUAD server.</p>
					<div class="form">
                     <form class="form-validate form-horizontal" method="post" enctype="multipart/form-data">
                        <div class="form-group ">
							<label for="cname" class="control-label col-lg-2">OTP: <span style="color:red">*</span></label>
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
<?php include_once('includes/footer.php'); ?>
<?php if(isset($_SESSION['status_error']) && $_SESSION['status_error']!=''){ ?>
   <script>
		swal.fire({
			  title: "<?php echo $_SESSION['status_error'];?>",
			  icon:"<?php echo $_SESSION['status_error_code']; ?>",
			  confirmButtonColor: '#449A97',
			  confirmButtonText: 'Ok'
			});
	</script>
<?php unset($_SESSION['status_error']);}  ?>