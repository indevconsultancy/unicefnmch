<?php include_once('includes/config.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('mycrypt.php'); ?>

<?php 
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
	
    <?php
    die("You are not authorized to see the survey");
}
$mcrypt = new EncryptionUtils($_SESSION['enckey']);
$client_id= $_SESSION['client_id'];

$id=$_REQUEST['id'];
$details_sql="SELECT full_json,survey_data_json, survey_name_id, survey_name, created_on  FROM survey_data_monitoring where survey_data_monitoring_id='".$id."'";
$details_query=mysqli_query($conn,$details_sql);
$details_data=mysqli_fetch_object($details_query);
$survey_id = $details_data->survey_name_id;
//echo $details_data->survey_data_json;
//encrypt data
echo $DecryptedJson = $mcrypt->decrypt($details_data->full_json);
//$datas = json_decode($DecryptedJson, true);
//print_r($datas);
?>