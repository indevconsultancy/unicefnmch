<?php include_once('includes/config.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('mycrypt.php'); ?>
<?php 
$_SESSION['enckey'];

$user_id= $_SESSION['user_id'];
if($_SESSION['enckey'] === 0){ ?>
	<section id="main-content">
		<section class="wrapper">
			<div class="row">
			  <div class="col-lg-12">
				<div class="row">
					<div class="col-sm-12 text-center">
					   
					   <div class="alert alert-danger" role="alert">
						  You are not authorized to see the survey data
						  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						  </button>
						</div>
					</div>
				</div>
			  </div>
			</div>
		</section>
    </section>
	
<?php include_once('includes/footer.php'); ?>
    <?php
    die("You are not authorized to see the survey");
}
$mcrypt = new EncryptionUtils($_SESSION['enckey']);
$sid=$_GET['sid'];
$details_sql="SELECT survey_data_json,full_json FROM survey_data_monitoring  where survey_id='".$sid."'";
$details_query=mysqli_query($conn,$details_sql);
$details_data=mysqli_fetch_object($details_query);
 
 $DecryptedJson = $mcrypt->decrypt($details_data->full_json);
 $full_json = json_decode($DecryptedJson);

 echo $DecodeJson = json_encode($full_json);
 //print_r($full_json);
?>