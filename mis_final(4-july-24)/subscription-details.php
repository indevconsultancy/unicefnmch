<?php include_once('includes/config.php'); ?>
 <?php define("title","User Profile | MQUAD");?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php

$user_id = $_SESSION['user_id'];
$client_id = $_SESSION['client_id'];
?>
<?php 
    $sql="select users.user_id,users.registered_as,users.name,users.email,users.mobile,roles.name as role_type_name from users left join roles on users.role_id=roles.id  where users.user_id='".$user_id."'";	
	$getsql=mysqli_query($conn,$sql);
	$data=mysqli_fetch_array($getsql);
?>

<?php 
$user_id = $_SESSION['user_id'];
$client_id = $_SESSION['client_id'];
	$client_qry='';
	$client_qry1='';
	$userqry1='';
	
	if($_SESSION['functional_role_id']!=3 && $_SESSION['functional_role_id']!=1){
		$userqry1=" and assign_survey.user_id='".$user_id."' ";
		$client_qry=" and survey.id in(SELECT DISTINCT(survey_id) AS survey_id  FROM assign_survey WHERE user_id='".$user_id."' and status=0) ";
		$client_qry1=" and survey_data_monitoring.survey_name_id in(SELECT DISTINCT(survey_id) AS survey_id  FROM assign_survey WHERE user_id='".$user_id."' and status=0) ";
	
	}
?>

<style type="text/css">
	.info-box i {
	display: block;
	height: 40px;
	font-size: 50px;
	line-height: 60px;
	width: 60px;
	float: left;
	text-align: center;
	margin-right: 15px;
	padding-right: 20px;
	color: rgba(255, 255, 255, 0.75);
	}
	.stats {
  height: 50%;
  background: #fff;
  display: flex;
/*   justify-content: center; */
  align-items: center;
}

.stat {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 32%;
/*   background: red; */
}

.stat:nth-child(2) {
  border: 1px solid #adadad;
  border-top: 0;
  border-bottom: 0;
/*   background: blue; */
}

.stat-num {
  font-size: 2.0rem;
}

.stat-name {
  font-size: 0.7em;
  color: #e07272;
}
.act-time .act-in .text {
    border: 0px solid #e3e6ed !important; 
    padding: 10px;
    border-radius: 4px;
    -webkit-border-radius: 4px;
}
</style>
<!--main content start-->
<section id="main-content">
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
			<ol class="breadcrumb">
				<li><i class="fa fa-home"></i>Home</li>
				<li><i class="fa fa-money" aria-hidden="true"></i>Subscription</li>
			</ol>
		</div>
	</div>
	<!-- page start-->
	<div class="row">
		<!-- profile-widget -->
		<div class="col-lg-12">
			<div class="profile-widget profile-widget-info">
				<div class="panel-body">
					
					<div class="col-lg-4 col-sm-4  follow-info weather-category">
						
						<?php 
						   $functionalsql="SELECT GROUP_CONCAT(roles.name) as role_type_name,roles.id as role_id FROM `functional_role` INNER join roles on functional_role.role_id=roles.id where functional_role.user_id='".$data['user_id']."'";
							$getfunUsers = mysqli_query($conn,$functionalsql);
							$fundata = mysqli_fetch_array($getfunUsers);
							$role_id=$fundata['role_id'];
							$targetedValue=array();
							$qrySubscription_status=mysqli_query($conn,"SELECT * from pm_userSubscription_status where client_id='$client_id'");
							$dataSubscription_status=mysqli_fetch_array($qrySubscription_status);
							$activesubscriptionID=$dataSubscription_status['activeSubscriptionID'];
							$qrySubscriptionDetails=mysqli_query($conn,"SELECT GROUP_CONCAT(componentValue) as serviceValues FROM pm_subscription_items where subscriptionID='".$activesubscriptionID."' and componentID in(3,4,5)");
							//echo "SELECT GROUP_CONCAT(componentValue) as serviceValues FROM pm_subscription_items where subscriptionID='".$dataSubscription_status['SubscriptionID']."' and componentID in(3,4,5)";
							$dataSubscriptionDetails=mysqli_fetch_array($qrySubscriptionDetails);
							$targetedValue=explode(",",$dataSubscriptionDetails['serviceValues']);
							
							
							$qrySubscriptionActive=mysqli_query($conn,"SELECT * FROM pm_subscriptions where subscriptionID='".$activesubscriptionID."' and status='0'");
							//echo "SELECT GROUP_CONCAT(componentValue) as serviceValues FROM pm_subscription_items where subscriptionID='".$dataSubscription_status['SubscriptionID']."' and componentID in(3,4,5)";
							$dataSubscriptionActive=mysqli_fetch_array($qrySubscriptionActive);
							
						?>
						<p> Package: <?=ucfirst($dataSubscriptionActive['SubscriptionName'])?></p>
						<p> Type: <?=$dataSubscriptionActive['SuscriptionType']?></p>
						<p> Expireation: <?=date('d-M-Y',strtotime($dataSubscription_status['SubscriptionExpireationDate']))?></p>
						<p> Current Price: <?=$dataSubscriptionActive['Price'];?>$ USD</p>
					</div>
					<?php if($role_id==3){?>
					<div class="col-lg-2 col-sm-8 follow-info weather-category">
						
						<ul>
							<li class="active">
								<i class="fa fa-wpforms fa-2x" aria-hidden="true"></i><br> Data Collection <br><b><?=$dataSubscription_status['forms_created'];?>/<?=$targetedValue[0];?></b>
							</li>
						</ul>
					</div>
					
					<div class="col-lg-2 col-sm-8 follow-info weather-category">
						
						<ul>
							<li class="active">
								<i class="fa fa-database fa-2x"></i><br> Storage  <br><b><?=round(($dataSubscription_status['storage_usage']/1024),2);?> /<?=$targetedValue[1];?> GB</b>
							</li>
						</ul>
					</div>
					<div class="col-lg-2 col-sm-8 follow-info weather-category">
						
						<ul>
							<li class="active">
								<i class="fa fa-dropbox fa-2x" aria-hidden="true"></i><br> Project Space <br> <b><?=$dataSubscription_status['project_space_created'];?>/<?=$targetedValue[2];?></b>
							</li>
						</ul>
					</div>
					<div class="col-lg-2 col-sm-8 follow-info weather-category">
						<?php
							
							$iconss='fa-times-circle';
							$textss='Inactive';
							if($dataSubscription_status['subscription_status']=='Active')
							{
								$iconss='fa-check-circle';
								$textss='Active';
							}
							else {
								$textss=$dataSubscription_status['subscription_status'];
							}
							?>
						<ul>
							<li class="active">
								<i class="fa <?=$iconss?> fa-2x"></i><br> Subcription <br> <b><?=$textss;?> <?php //$sssv=subscription_services($conn,$_SESSION['user_id']); print_r($sssv);?></b>
							</li>
						</ul>
					</div>
					<?php } ?>
					
					<?php if($role_id!=3) {?>
					<div class="col-lg-2 col-sm-8 follow-info weather-category">
						<?php
						//$assignForm=mysqli_query($conn," select COUNT(DISTINCT(assign_survey.survey_id)) as total_assign from assign_survey inner join survey on assign_survey.survey_id=survey.id where assign_survey.status='0'  and assign_survey.user_id='".$user_id."'");
							$assignForm=mysqli_query($conn,"select COUNT(DISTINCT(assign_survey.survey_id)) as total_assign from assign_survey inner join survey on assign_survey.survey_id=survey.id where assign_survey.status='0' $userqry1");
						
							$data=mysqli_fetch_array($assignForm);
							
							?>
						<ul>
							<li class="active">
								<i class="fa fa-wpforms fa-2x" aria-hidden="true"></i><br> Number of Assigned Forms: <b><?=$data['total_assign'];?></b>
							</li>
						</ul>
					</div>
					<div class="col-lg-2 col-sm-8 follow-info weather-category">
						<?php
							$surveysql=mysqli_query($conn,"SELECT COUNT(survey_data_monitoring_id) AS total_monitoring FROM `survey_data_monitoring` left join survey on survey.id=survey_data_monitoring.survey_name_id left join users on users.user_id=survey_data_monitoring.user_id where users.del_action='N'  $client_qry1");
							$data=mysqli_fetch_array($surveysql);
							//echo $data['total_monitoring'];
						?>
						<ul>
							<li class="active">
								<i class="icon_documents_alt" style="font-size: 2.5em;"></i><br> Number of Entries Collected: <b><?=$data['total_monitoring'];?> </b>
							</li>
						</ul>
					</div>
					<div class="col-lg-2 col-sm-8 follow-info weather-category">
						<?php
							//$surveysql=mysqli_query($conn,"SELECT COUNT(survey_data_monitoring_id) AS total_accept FROM `survey_data_monitoring` where survey_status in (1,6) and user_id='".$user_id."'");
							$surveyaccsql=mysqli_query($conn,"SELECT COUNT(survey_data_monitoring_id) AS total_accept FROM `survey_data_monitoring` left join survey on survey.id=survey_data_monitoring.survey_name_id where survey.status in (1,6) $client_qry1");
							$data=mysqli_fetch_array($surveyaccsql);
							
						?>
						<ul>
							<li class="active">
								<i class="fa fa-check-circle fa-2x" aria-hidden="true"></i><br>Number of Entries Verified: <b><?=$data['total_accept'];?></b>
							</li>
						</ul>
					</div>
					<div class="col-lg-2 col-sm-8 follow-info weather-category">
						<?php
							//$surveysql=mysqli_query($conn,"SELECT COUNT(survey_data_monitoring_id) AS total_reject FROM `survey_data_monitoring` where survey_status='4' and user_id='".$user_id."'");
							$surveyrejsql=mysqli_query($conn,"SELECT COUNT(survey_data_monitoring_id) AS total_reject FROM `survey_data_monitoring` left join survey on survey.id=survey_data_monitoring.survey_name_id where  survey_status='7' $client_qry1");
							$data=mysqli_fetch_array($surveyrejsql);
							
						?>
						<ul>
							<li class="active">
								<i class="fa fa-times-circle fa-2x"></i><br>Number of Entries Rejected: <b><?=$data['total_reject'];?></b>
							</li>
						</ul>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<section class="panel">
				<header class="panel-heading tab-bg-info">
					<ul class="nav nav-tabs">
					<?php if($role_id==3){?>
						<li class="active">
							<a data-toggle="tab" href="#subscription-history">
							<i class="icon-envelope"></i>
							Subscription History
							</a>
						</li>
						<li class="">
							<a data-toggle="tab" href="#profile">
							<i class="icon-user"></i>
							Usage History
							</a>
						</li>
						
						<?php } ?>
					</ul>
				</header>
				<div class="panel-body">
					<div class="tab-content">
					<?php if($role_id==3){?>
						<div id="subscription-history" class="tab-pane active">
							<section class="panel">
								<div class="table-responsive">
									<table class="table  table-striped">
										<thead>
											<tr>
												<th>Subscription Name</th>
												<th>Type</th>
												<th>Amount(in USD)</th>
												<th class="">Subscription Period</th>
												<th class="">Status</th>
												<th class="">Subscription Date</th>
												<th class="">Status</th>
											</tr>
										</thead>
										<tbody>
										<?php $sql_order=mysqli_query($conn,"select pm_payments.*,pm_subscriptions.SubscriptionName,pm_payments.client_id as client_id,pm_subscriptions.SuscriptionType,pm_subscriptions.Description,pm_usersubscriptions.Status as subsStatus,pm_usersubscriptions.StartDate,pm_usersubscriptions.Status,pm_usersubscriptions.EndDate from pm_payments,pm_subscriptions,pm_usersubscriptions where pm_payments.client_id='$client_id' and pm_usersubscriptions.PaymentID=pm_payments.PaymentID and pm_payments.subscriptionID=pm_subscriptions.subscriptionID and PaymentStatus='Completed' ORDER BY `pm_payments`.`PaymentID` DESC");
										      while($data_order=mysqli_fetch_object($sql_order))
											  { ?>
											<tr>
												<td><?=$data_order->SubscriptionName?></td>
												<td><?=$data_order->SuscriptionType?></td>
												<td><?=$data_order->AmountInUSD?></td>
												<td><?=date('d-M-Y',strtotime($data_order->StartDate))?> To <?=date('d-M-Y',strtotime($data_order->EndDate))?></td>
												<td><?=$data_order->subsStatus?></td>
												<td><?=$data_order->PaymentDate?></td>
												<td><?=$data_order->Status?></td>												
											</tr>
											  <?php } ?>		
										</tbody>
									</table>
								</div>
							</section>
						</div>
						
						<div id="profile" class="tab-pane">
							<section class="panel">
								<div class="panel-body bio-graph-info">
									<div class="row">
										<div class="col-lg-6">
											<h1><b>Form Monitoring</b></h1>
											<div class="row">
												<div class="profile-activity">
													<div class="act-time">
														<div class="activity-body act-in">
															<span class="arrow"></span>
															<div class="text">
															<ul>
															<style>
															  ul {
																list-style-type: none;
																padding: 0;
															  }
															  
															  li {
																margin-bottom: 0.5em;
															  }
															</style>

															<?php
																
																$surveysql=mysqli_query($conn,"SELECT COUNT(survey_data_monitoring_id) AS total_monitoring FROM `survey_data_monitoring` where client_id='$client_id'");
																$data=mysqli_fetch_array($surveysql);
																//echo $data['total_monitoring'];
																
																
															?>
															<li><span class="attribution" style="font-size:15px;">Number of Entries Collected: <?=$data['total_monitoring']?></span></li>
																<?php
																//$accsql=getcounts_multi($conn,'survey_data_monitoring','survey_data_monitoring_id','survey_status','1','client_id',$client_id);
																$surveyssql=mysqli_query($conn,"SELECT COUNT(survey_data_monitoring_id) AS accsql FROM `survey_data_monitoring` where client_id='$client_id' and survey_status IN(1,6)");
																$data=mysqli_fetch_array($surveyssql);
																?>
															<li><span class="attribution" style="font-size:15px;">Number of Entries Verified: <?=$data['accsql']?></span><br>
															<?php
																$sendForInterview=getcounts_multi($conn,'survey_data_monitoring','survey_data_monitoring_id','survey_status','4','client_id',$client_id);
															?>
															<li><span class="attribution" style="font-size:15px;">Number of Entries Send for interview: <?=$sendForInterview?></span></li>
															<?php
																$resubmitted=getcounts_multi($conn,'survey_data_monitoring','survey_data_monitoring_id','survey_status','6','client_id',$client_id);
															?>
															<li><span class="attribution" style="font-size:15px;">Number of Entries Re-submitted: <?=$resubmitted?></span></li>
															
															<?php
															$rejsql=getcounts_multi($conn,'survey_data_monitoring','survey_data_monitoring_id','survey_status','7','client_id',$client_id);
															
															?>
															<li><span class="attribution" style="font-size:15px;">Number of Entries Rejected: <?=$rejsql;?></span></li>
															<?php
															$terminatesql=getcounts_multi($conn,'survey_data_monitoring','survey_data_monitoring_id','survey_status','3','client_id',$client_id);
															?>
															<li><span class="attribution" style="font-size:15px;">Number of Entries Terminated: <?=$terminatesql;?></span></li>
															</ul>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-lg-6">
											<center><h1><b>Users</b></h1></center></br>
											<div class="profile-card">
												<div class="stats">
													<div class="stat">
													<?php
													   //echo "SELECT COUNT(user_id) FROM `users` where client_id='$client_id'";
														$totalsql=mysqli_query($conn,"SELECT COUNT(user_id) FROM `users` where client_id='".$client_id."' and del_action='N'");
														$total=mysqli_fetch_array($totalsql);
														//echo $total['COUNT(user_id)'];
													?>
													  <span class="stat-num"><?=$total['COUNT(user_id)']?></span>
													  <span class="stat-name">TOTAL USERS</span>
													</div>
													<div class="stat">
													<?php
														$activesql=mysqli_query($conn,"SELECT COUNT(user_id) FROM `users` where status='1' and client_id='".$client_id."' and del_action='N'");
														$active=mysqli_fetch_array($activesql);
														?>
													  <span class="stat-num"><?=$active['COUNT(user_id)']?></span>
													  <span class="stat-name">INACTIVE USERS</span>      
													</div>
													<div class="stat">
													<?php
														$inactivesql=mysqli_query($conn,"SELECT COUNT(user_id) FROM `users` where status='0' and client_id='".$client_id."' and del_action='N'");
														$inactive=mysqli_fetch_array($inactivesql);
														?>
													  <span class="stat-num"><?=$inactive['COUNT(user_id)']?></span>
													  <span class="stat-name">ACTIVE USERS</span>      
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</section>
							<section>
								<section>
									<div class="row">
									</div>
								</section>
							</div>
							<!--change password-->
						<?php } ?>
						<!-- edit-profile -->
						<!--<div id="edit-profile" class="tab-pane">
							<section class="panel">
								<div class="panel-body bio-graph-info">
									<h1>Update Profile </h1>
									<form class="form-horizontal" role="form" action="" method="POST">
										<input type="hidden" class="form-control" id="name" name="id" value="<?=$user['id']?>" >
											<div class="form-group">
												<label class="col-lg-2 control-label">Full name</label>
												<div class="col-lg-6">
													<input type="text" class="form-control" id="name" name="name" value="<?=$user['name']?>" >
												</div>
											</div>
										
										<div class="form-group">
											<label for="email" class="control-label col-lg-2">Email</label>
											<div class="col-lg-6">
												<input class="form-control " id="email" name="email" autocomplete="new-email" type="email" value="<?=$user['email']?>"/>
											</div>
										</div>
										
										<div class="form-group">
											<div class="col-lg-offset-2 col-lg-10">
												<button type="submit" class="btn btn-primary" name="update_user">Update</button>
											</div>
										 </div>
									</form>
								</div>
							</section>
						</div>-->
						<?php if($role_id!=3){?>
						<!--change password-->
						<div id="change-password" class="tab-pane active">
							<section class="panel">
								<div class="panel-body bio-graph-info">
									<h1>Change Password </h1>
									<form class="form-horizontal" role="form" action="" method="POST">
										<input type="hidden" class="form-control" id="name" name="id" value="<?=$user['id']?>" >
											<div class="form-group">
												<label class="col-lg-2 control-label">Old Password <span style="color:red;">*</span></label>
												<div class="col-lg-6">
													<input type="password" class="form-control" required id="old_password" name="oldpass" />
												</div>
											</div>
											<div class="form-group">
												<label class="col-lg-2 control-label">New Password <span style="color:red;">*</span></label>
												<div class="col-lg-6">
													<input type="password" class="form-control" required id="newpassword1" name="newpassword" />
												</div>
											</div>
											<div style="margin-top: 7px;" id="NewPasswordMatch"></div>
											<div class="form-group">
												<label class="col-lg-2 control-label">Confirm Password <span style="color:red;">*</span></label>
												<div class="col-lg-6">
													<input type="password" class="form-control" required id="conformpassword1" name="conformpassword" />
												</div>
											</div>
											<div style="margin-top: 7px;" id="CheckPasswordMatch1"></div>
										 <div class="form-group">
											<div class="col-lg-offset-2 col-lg-10">
												<button type="submit" class="btn btn-primary" id="checked_pass1" disabled name="change_password">Submit</button>
											</div>
										 </div>
										 
									</form>
								</div>
							</section>
						</div>
						<?php } ?>
					</div>
				</div>
			</section>
		</div>
	</div>
	<!-- page end-->
	</section>
</section>
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

<?php if(isset($_SESSION['status']) && $_SESSION['status']!=''){ ?>
<script>
	swal.fire({
		title: "<?php echo $_SESSION['status'];?>",
		icon:"<?php echo $_SESSION['status_code']; ?>",
		confirmButtonColor: '#449A97',
		confirmButtonText: 'Ok'
	});
</script>
<?php unset($_SESSION['status']);}  ?>
<script>
$(document).ready(function () {
   $("#conformpassword").on('keyup', function(){
    var newpassword = $("#newpassword").val();
    var conformpassword = $("#conformpassword").val();
    if (newpassword != conformpassword)
        $("#CheckPasswordMatch").html("Password does not match !").css("color","red");
    else
		 //$("#checked_pass").attr("disabled", false);
        $("#CheckPasswordMatch").html("Password match !").css("color","green");
   });
});
</script>
