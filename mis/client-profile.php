<?php include_once('includes/config.php'); ?>
<?php define("title", "User Profile | MQUAD"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php

$user_id = $_SESSION['user_id'];
$client_id = $_SESSION['client_id'];
?>
<?php
$sql = "select users.user_id,users.registered_as,users.name,users.email,users.mobile,roles.name as role_type_name from users left join roles on users.role_id=roles.id  where users.user_id='" . $user_id . "'";
$getsql = mysqli_query($conn, $sql);
$data = mysqli_fetch_array($getsql);

?>
<?php
// $getUser = mysqli_query($conn,"SELECT id,name,email,profile_img,address,mobile FROM clients WHERE id='$client_id'");
// $user = mysqli_fetch_array($getUser);

/*if(isset($_REQUEST['update_user'])){
		
		$id=$_REQUEST['id'];
		$name=$_REQUEST['name'];
		$email=$_REQUEST['email'];
		$update=mysqli_query($conn,"UPDATE clients SET name='".$name."',email='".$email."' WHERE clients.id='".$id."' ");
		if($update){
			echo "<script>alert('Profile update successfully');</script>";
		}
		  echo "<script>window.location.href='client-profile.php'</script>";
		
	}*/
?>
<?php
$user_id = $_SESSION['user_id'];
$client_id = $_SESSION['client_id'];
$client_qry = '';
$client_qry1 = '';
$userqry1 = '';

if ($_SESSION['functional_role_id'] != 3 && $_SESSION['functional_role_id'] != 1) {
	$userqry1 = " and assign_survey.user_id='" . $user_id . "' ";
	$client_qry = " and survey.id in(SELECT DISTINCT(survey_id) AS survey_id  FROM assign_survey WHERE user_id='" . $user_id . "' and status=0) ";
	$client_qry1 = " and survey_data_monitoring.survey_name_id in(SELECT DISTINCT(survey_id) AS survey_id  FROM assign_survey WHERE user_id='" . $user_id . "' and status=0) ";
}
if($_SESSION['functional_role_id']==9)
{
	$client_qry.=" and survey_data_monitoring.user_id='".$user_id."'";
	$client_qry1.=" and survey_data_monitoring.user_id='".$user_id."'";
}
?>
<?php
if (isset($_REQUEST['change_password'])) {
	$userId = $_SESSION['user_id'];
	$oldpass = sanitizeInput($_POST['oldpass'],$conn);
	$oldpassword = password_hash($oldpass, PASSWORD_DEFAULT);
	$newpass=sanitizeInput($_POST['newpassword'],$conn);
	$newpassword = password_hash($newpass, PASSWORD_DEFAULT);
	$confirmpass=sanitizeInput($_POST['conformpassword'],$conn);
	$conformpassword = password_hash($confirmpass, PASSWORD_DEFAULT);

	$queryselpass = mysqli_query($conn, "select user_id,password from users where user_id='" . $userId . "' ");
	$getpass = mysqli_fetch_array($queryselpass);

	$old_Pass = $getpass['password'];
	$error = '';
	$successerror = '';
	if (password_verify($oldpass, $old_Pass)) {
		//echo "password has been same";
		if ($newpass == $confirmpass) {
			$updatepassqry = mysqli_query($conn, "update users set password='" . $newpassword . "' where user_id='" . $userId . "'");

			$_SESSION['status'] = "Password has been successfully updated";
			$_SESSION['status_code'] = "success";
		} else {
			$_SESSION['status_error'] = "New and conform Password is not match!!";
			$_SESSION['status_error_code'] = "error";
		}
	} else {
		$_SESSION['status_error'] = "New and conform Password is not match!!";
		$_SESSION['status_error_code'] = "error";
	}
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

 
	.follow-info{
		padding-top: 0px !important;
	}


	.stat-num {
		font-size: 1.5rem;
	}

	.stat-name {
		font-size: 11px;
		color: #61b7b8;
		font-weight: 600;
		font-family: 'Lato';
	}

	.act-time .act-in .text {
		border: 0px solid #e3e6ed !important;
		padding: 10px;
		border-radius: 4px;
		-webkit-border-radius: 4px;
	}

	.client-tab,
	.client-tab li a {
		height: 35px;
	}

	.client-tab li a {
		line-height: 20px;
	}

	.panel-heading .nav>li>a {
		color: #fff !important;
	}

	.bio-graph-info h1 {
		font-size: 18px !important;
		font-weight: 700 !important;
	}
</style>
<!--main content start-->
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><i class="fa fa-home"></i>Home</li>
						<li class="breadcrumb-item active" aria-current="page"><i class="fa fa-users" aria-hidden="true"></i>User Profile</li>
					</ol>
				</nav>
			</div>
		</div>
		<!-- page start-->
		<div class="row">
			<!-- profile-widget -->
			<div class="col-lg-12">
				<div class="profile-widget profile-widget-info">
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-4 col-sm-12 follow-info">
								<p> Name: <?= ucfirst($data['name']) ?></p>
								<p> Email: <?= $data['email'] ?></p>
								<?php
								$functionalsql = "SELECT GROUP_CONCAT(roles.name) as role_type_name,roles.id as role_id FROM `functional_role` INNER join roles on functional_role.role_id=roles.id where functional_role.user_id='" . $data['user_id'] . "'";
								$getfunUsers = mysqli_query($conn, $functionalsql);
								$fundata = mysqli_fetch_array($getfunUsers);
								$role_id = $fundata['role_id'];
								?>
								<p> Role: <?= $fundata['role_type_name'] ?></p>
							</div>
							<?php if ($role_id == 3) { ?>
								<div class="col-lg-2 col-sm-8 follow-info weather-category">
									<?php
									$totalservey = mysqli_query($conn, "SELECT count(id) as total_survey FROM `survey` where client_id='$client_id' and del_action='N'");
									$data = mysqli_fetch_array($totalservey);
									?>
									<ul>
										<li class="active">
											<i class="fa fa-wpforms fa-2x" aria-hidden="true"></i><br> Total Form : <b><?= $data['total_survey']; ?></b>
										</li>
									</ul>
								</div>
								<div class="col-lg-2 col-sm-8 follow-info weather-category">
									<?php
									$publish = mysqli_query($conn, "SELECT count(id) as total_publish FROM `survey` where client_id='$client_id' and status='1' and del_action='N'");
									$data = mysqli_fetch_array($publish);
									?>
									<ul>
										<li class="active">
											<i class="fa fa-check-circle fa-2x" aria-hidden="true"></i><br> Publish : <b><?= $data['total_publish']; ?></b>
										</li>
									</ul>
								</div>
								<div class="col-lg-2 col-sm-8 follow-info weather-category">
									<?php
									$unpublish = mysqli_query($conn, "SELECT count(id) as total_unpublish FROM `survey` where client_id='$client_id' and status='0' and del_action='N'");
									$data = mysqli_fetch_array($unpublish);
									?>
									<ul>
										<li class="active">
											<i class="fa fa-times-circle fa-2x"></i><br> Unpublish : <b><?= $data['total_unpublish']; ?> <?php //$sssv=subscription_services($conn,$_SESSION['user_id']); print_r($sssv);
																																			?></b>
										</li>
									</ul>
								</div>
								<div class="col-lg-2 col-sm-8 follow-info weather-category">
									<?php
									$qrySubscription_status = mysqli_query($conn, "SELECT subscription_status from pm_userSubscription_status where client_id='$client_id'");
									$dataSubscription_status = mysqli_fetch_array($qrySubscription_status);
									$iconss = 'fa-times-circle';
									$textss = 'Inactive';
									if ($dataSubscription_status['subscription_status'] == 'Active') {
										$iconss = 'fa-check-circle';
										$textss = 'Active';
									} else {
										$textss = $dataSubscription_status['subscription_status'];
									}
									?>
									<ul>
										<li class="active">
											<i class="fa <?= $iconss ?> fa-2x"></i><br> Subcription : <b><?= $textss; ?> <?php //$sssv=subscription_services($conn,$_SESSION['user_id']); print_r($sssv);
																															?></b>
										</li>
									</ul>
								</div>
							<?php } ?>

							<?php if ($role_id != 3) { ?>
								<div class="col-lg-2 col-sm-8 follow-info weather-category">
									<?php
									//$assignForm=mysqli_query($conn," select COUNT(DISTINCT(assign_survey.survey_id)) as total_assign from assign_survey inner join survey on assign_survey.survey_id=survey.id where assign_survey.status='0'  and assign_survey.user_id='".$user_id."'");
									$assignForm = mysqli_query($conn, "select COUNT(DISTINCT(assign_survey.survey_id)) as total_assign from assign_survey inner join survey on assign_survey.survey_id=survey.id where assign_survey.status='0' $userqry1");

									$data = mysqli_fetch_array($assignForm);

									?>
									<ul>
										<li class="active">
											<i class="fa fa-wpforms fa-2x" aria-hidden="true"></i><br> Number of Assigned Forms: <b><?= $data['total_assign']; ?></b>
										</li>
									</ul>
								</div>
								<div class="col-lg-2 col-sm-8 follow-info weather-category">
									<?php
									$surveysql = mysqli_query($conn, "SELECT COUNT(survey_data_monitoring_id) AS total_monitoring FROM `survey_data_monitoring` left join survey on survey.id=survey_data_monitoring.survey_name_id left join users on users.user_id=survey_data_monitoring.user_id where users.del_action='N'  $client_qry1");
									$data = mysqli_fetch_array($surveysql);
									//echo $data['total_monitoring'];
									?>
									<ul>
										<li class="active">
											<i class="fa fa-list-alt fa-2x" ></i><br> Number of Entries Collected: <b><?= $data['total_monitoring']; ?> </b>
										</li>
									</ul>
								</div>
								<div class="col-lg-2 col-sm-8 follow-info weather-category">
									<?php
									//$surveysql=mysqli_query($conn,"SELECT COUNT(survey_data_monitoring_id) AS total_accept FROM `survey_data_monitoring` where survey_status in (1,6) and user_id='".$user_id."'");
									$surveyaccsql = mysqli_query($conn, "SELECT COUNT(survey_data_monitoring_id) AS total_accept FROM `survey_data_monitoring` left join survey on survey.id=survey_data_monitoring.survey_name_id where survey_data_monitoring.survey_status in (1,6) $client_qry1");
									$data = mysqli_fetch_array($surveyaccsql);

									//echo "SELECT COUNT(survey_data_monitoring_id) AS send_for_review FROM `survey_data_monitoring` left join survey on survey.id=survey_data_monitoring.survey_name_id where survey.status in (4) $client_qry1";
									$surveysendsql = mysqli_query($conn, "SELECT COUNT(survey_data_monitoring_id) AS send_for_review FROM `survey_data_monitoring` left join survey on survey.id=survey_data_monitoring.survey_name_id where survey_data_monitoring.survey_status in (4) $client_qry1");
									$datasend = mysqli_fetch_array($surveysendsql);

									$surveytersql = mysqli_query($conn, "SELECT COUNT(survey_data_monitoring_id) AS tot_terminated FROM `survey_data_monitoring` left join survey on survey.id=survey_data_monitoring.survey_name_id where survey_data_monitoring.survey_status in (3) $client_qry1");
									$datater = mysqli_fetch_array($surveytersql);
									?>
									<ul>
										<li class="active">
											<i class="fa fa-check-circle fa-2x" aria-hidden="true"></i><br>Number of Entries Verified: <b><?php echo '<span class="text-left tooltips" data-bs-placement="top" data-bs-toggle="tooltip" data-bs-html="true" title="Terminated: ' . $datater['tot_terminated'] . '<br>Send for review: ' . $datasend['send_for_review'] . '">' . $data['total_accept'] . '</span>'; ?></b>
										</li>
									</ul>
								</div>
								<div class="col-lg-2 col-sm-8 follow-info weather-category">
									<?php
									//$surveysql=mysqli_query($conn,"SELECT COUNT(survey_data_monitoring_id) AS total_reject FROM `survey_data_monitoring` where survey_status='4' and user_id='".$user_id."'");
									$surveyrejsql = mysqli_query($conn, "SELECT COUNT(survey_data_monitoring_id) AS total_reject FROM `survey_data_monitoring` left join survey on survey.id=survey_data_monitoring.survey_name_id where  survey_status='7' $client_qry1");
									$data = mysqli_fetch_array($surveyrejsql);

									?>
									<ul>
										<li class="active">
											<i class="fa fa-times-circle fa-2x"></i><br>Number of Entries Rejected: <b><?= $data['total_reject']; ?></b>
										</li>
									</ul>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row mt-2">
			<div class="col-lg-12">
				<section class="panel">
					<header class="panel-heading tab-bg-info">
						<ul class="nav nav-tabs client-tab">
							<?php if ($role_id == 3) { ?>
								<li class="nav-item">
									<a class="nav-link active" data-bs-toggle="tab" href="#profile">
										<!-- <i class="icon-user"></i> -->
										Profile
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-bs-toggle="tab" href="#change-password">
										<!-- <i class="icon-envelope"></i> -->
										Change Password
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-bs-toggle="tab" href="#recent-activity">
										<!-- <i class="icon-home"></i> -->
										Last Activity
									</a>
								</li>
							<?php } else { ?>
								<li class="nav-item">
									<a class="nav-link active" data-bs-toggle="tab" href="#change-password">
										<!-- <i class="icon-envelope"></i> -->
										Change Password
									</a>
								</li>
							<?php } ?>
						</ul>
					</header>

					<div class="panel-body">
						<div class="tab-content">
							<?php if ($role_id == 3) { ?>
								<div id="recent-activity" class="tab-pane">
									<div class="profile-activity">
										<div class="act-time">
											<div class="activity-body act-in">
												<div class="row">
													<span class="arrow"></span>
													<div class="col-lg-6">
														<div class="text">
															<?php
															//$sql=mysqli_query($conn,"SELECT clients.name,user_log.date_time,user_log.device_name FROM user_log left join clients on clients.id=user_log.client_id where user_log.client_id='91' order by user_log.date_time DESC LIMIT 0,1;");

															$sql = mysqli_query($conn, "SELECT users.name,user_log.date_time,user_log.device_name FROM user_log left join users on users.user_id=user_log.user_id where user_log.client_id='" . $client_id . "' and user_log.user_id!='" . $user_id . "' order by user_log.date_time DESC LIMIT 0,1");
															$getdata = mysqli_fetch_array($sql);
															?>
															<p class="attribution"><a href="#">User name : </a> <?= $getdata['name'] ?></p>
															<hr>
															<p class="attribution"><a href="#">Device name : </a> <?= $getdata['device_name'] ?></p>
															<hr>
															<p class="attribution"><a href="#">Last Login date :</a> <?php if ($getdata['date_time'] != "") {
																															echo date("d-M-Y", strtotime($getdata['date_time']));
																														} ?></p>
															<hr>

														</div>
													</div>
													<div class="col-lg-6">
														<div class="text">
															<?php
															$sqlclent = mysqli_query($conn, "SELECT clients.name,user_log.date_time,user_log.device_name FROM user_log left join clients on clients.id=user_log.client_id where user_log.client_id='" . $client_id . "' order by user_log.date_time DESC LIMIT 0,1;");

															$getclientdata = mysqli_fetch_array($sqlclent);
															?>
															<p class="attribution"><a href="#">Client name : </a> <?= $getclientdata['name'] ?></p>
															<hr>
															<p class="attribution"><a href="#">Device name : </a> <?= $getclientdata['device_name'] ?></p>
															<hr>
															<p class="attribution"><a href="#">Last Login date :</a> <?php if ($getclientdata['date_time'] != "") {
																															echo date("d-M-Y", strtotime($getclientdata['date_time']));
																														} ?></p>
															<hr>

														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<!-- profile -->

								<div id="profile" class="tab-pane active">
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

																			$surveysql = mysqli_query($conn, "SELECT COUNT(survey_data_monitoring_id) AS total_monitoring FROM `survey_data_monitoring` where client_id='$client_id'");
																			$data = mysqli_fetch_array($surveysql);
																			//echo $data['total_monitoring'];


																			?>
																			<li><span class="attribution" style="font-size:14px;">Number of Entries Collected: <?= $data['total_monitoring'] ?></span></li>
																			<?php
																			//$accsql=getcounts_multi($conn,'survey_data_monitoring','survey_data_monitoring_id','survey_status','1','client_id',$client_id);
																			$surveyssql = mysqli_query($conn, "SELECT COUNT(survey_data_monitoring_id) AS accsql FROM `survey_data_monitoring` where client_id='$client_id' and survey_status IN(1)");
																			$data = mysqli_fetch_array($surveyssql);
																			?>
																			<li><span class="attribution" style="font-size:14px;">Number of Entries Verified: <?= $data['accsql'] ?></span><br>
																			<li>
																				<?php
																				$resubmitted = getcounts_multi($conn, 'survey_data_monitoring', 'survey_data_monitoring_id', 'survey_status', '6', 'client_id', $client_id);
																				?>
																			<li><span class="attribution" style="font-size:14px;">Number of Entries Re-submitted: <?= $resubmitted ?></span></li>
																			<?php
																			$sendForInterview = getcounts_multi($conn, 'survey_data_monitoring', 'survey_data_monitoring_id', 'survey_status', '4', 'client_id', $client_id);
																			?>
																			<li><span class="attribution" style="font-size:14px;">Number of Entries Send for interview: <?= $sendForInterview ?></span></li>

																			<?php
																			$terminatesql = getcounts_multi($conn, 'survey_data_monitoring', 'survey_data_monitoring_id', 'survey_status', '3', 'client_id', $client_id);
																			?>
																			<li><span class="attribution" style="font-size:14px;">Number of Entries Terminated: <?= $terminatesql; ?></span>
																			</li>
																			<?php
																			$rejsql = getcounts_multi($conn, 'survey_data_monitoring', 'survey_data_monitoring_id', 'survey_status', '7', 'client_id', $client_id);

																			?>
																			<li><span class="attribution" style="font-size:14px;">Number of Entries Rejected: <?= $rejsql; ?></span></li>
																		</ul>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-lg-6">
													<center>
														<h1><b>Users</b></h1>
													</center></br>
													<div class="profile-card">
														<div class="stats">
															<div class="stat">
																<?php
																//echo "SELECT COUNT(user_id) FROM `users` where client_id='$client_id'";
																$totalsql = mysqli_query($conn, "SELECT COUNT(user_id) FROM `users` where client_id='" . $client_id . "' and del_action='N'");
																$total = mysqli_fetch_array($totalsql);
																//echo $total['COUNT(user_id)'];
																?>
																<span class="stat-num"><?= $total['COUNT(user_id)'] ?></span>
																<span class="stat-name">TOTAL USERS</span>
															</div>
															<div class="stat">
																<?php
																$activesql = mysqli_query($conn, "SELECT COUNT(user_id) FROM `users` where status='1' and client_id='" . $client_id . "' and del_action='N'");
																$active = mysqli_fetch_array($activesql);
																?>
																<span class="stat-num"><?= $active['COUNT(user_id)'] ?></span>
																<span class="stat-name">INACTIVE USERS</span>
															</div>
															<div class="stat">
																<?php
																$inactivesql = mysqli_query($conn, "SELECT COUNT(user_id) FROM `users` where status='0' and client_id='" . $client_id . "' and del_action='N'");
																$inactive = mysqli_fetch_array($inactivesql);
																?>
																<span class="stat-num"><?= $inactive['COUNT(user_id)'] ?></span>
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
								<div id="change-password" class="tab-pane">
									<section class="panel">
										<div class="panel-body bio-graph-info">
											<h1>Change Password </h1>
											<form class="form-horizontal" role="form" action="" method="POST">
												<input type="hidden" class="form-control" id="name" name="id" value="<?= $user['id'] ?>">
												<div class="row mb-2">
													<label class="col-lg-2 control-label">Old Password <span style="color:red;">*</span></label>
													<div class="col-lg-6">
														<input type="password" class="form-control" required id="oldpass" name="oldpass" />
													</div>
												</div>
												<div class="row mb-2">
													<label class="col-lg-2 control-label">New Password <span style="color:red;">*</span></label>
													<div class="col-lg-6">
														<input type="password" class="form-control" required id="newpassword" name="newpassword" />
													</div>
												</div>
												<div style="margin-top: 7px;" id="NewPasswordMatch"></div>
												<div class="row mb-2">
													<label class="col-lg-2 control-label">Confirm Password <span style="color:red;">*</span></label>
													<div class="col-lg-6">
														<input type="password" class="form-control" required id="conformpassword" name="conformpassword" />
													</div>
												</div>
												<div style="margin-top: 7px;" id="CheckPasswordMatch"></div>
												<div class="row mb-2">
													<div class="col-lg-offset-2  col-lg-8 text-end">
														<button type="submit" class="btn btn-primary" id="checked_pass" name="change_password">Submit</button>
													</div>
												</div>

											</form>
										</div>
									</section>
								</div>
							<?php } ?>
							<!-- edit-profile -->
							<!--<div id="edit-profile" class="tab-pane">
							<section class="panel">
								<div class="panel-body bio-graph-info">
									<h1>Update Profile </h1>
									<form class="form-horizontal" role="form" action="" method="POST">
										<input type="hidden" class="form-control" id="name" name="id" value="<?= $user['id'] ?>" >
											<div class="row mb-2">
												<label class="col-lg-2 control-label">Full name</label>
												<div class="col-lg-6">
													<input type="text" class="form-control" id="name" name="name" value="<?= $user['name'] ?>" >
												</div>
											</div>
										
										<div class="row mb-2">
											<label for="email" class="control-label col-lg-2">Email</label>
											<div class="col-lg-6">
												<input class="form-control " id="email" name="email" autocomplete="new-email" type="email" value="<?= $user['email'] ?>"/>
											</div>
										</div>
										
										<div class="row mb-2">
											<div class="col-lg-offset-2 col-lg-10">
												<button type="submit" class="btn btn-primary" name="update_user">Update</button>
											</div>
										 </div>
									</form>
								</div>
							</section>
						</div>-->
							<?php if ($role_id != 3) { ?>
								<!--change password-->
								<div id="change-password" class="tab-pane active">
									<section class="panel">
										<div class="panel-body bio-graph-info">
											<h1>Change Password </h1>
											<form class="form-horizontal" role="form" action="" method="POST">
												<input type="hidden" class="form-control" id="name" name="id" value="<?= $user['id'] ?>">
												<div class="row mb-2">
													<label class="col-lg-2 control-label">Old Password <span style="color:red;">*</span></label>
													<div class="col-lg-6">
														<input type="password" class="form-control" required id="old_password" name="oldpass" />
													</div>
												</div>
												<div class="row mb-2">
													<label class="col-lg-2 control-label">New Password <span style="color:red;">*</span></label>
													<div class="col-lg-6">
														<input type="password" class="form-control" required id="newpassword1" name="newpassword" />
													</div>
												</div>
												<div style="margin-top: 7px;" id="NewPasswordMatch"></div>
												<div class="row mb-2">
													<label class="col-lg-2 control-label">Confirm Password <span style="color:red;">*</span></label>
													<div class="col-lg-6">
														<input type="password" class="form-control" required id="conformpassword1" name="conformpassword" />
													</div>
												</div>
												<div style="margin-top: 7px;" id="CheckPasswordMatch1"></div>
												<div class="row mb-2 ">
													<div class="col-lg-offset-2 col-lg-6 text-end">
														<button type="submit" class="btn btn-primary" id="checked_pass1"  name="change_password">Submit</button>
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
<?php if (isset($_SESSION['status_error']) && $_SESSION['status_error'] != '') { ?>
	<script>
		swal.fire({
			title: "<?php echo $_SESSION['status_error']; ?>",
			icon: "<?php echo $_SESSION['status_error_code']; ?>",
			confirmButtonColor: '#449A97',
			confirmButtonText: 'Ok'
		});
	</script>
<?php unset($_SESSION['status_error']);
}  ?>

<?php if (isset($_SESSION['status']) && $_SESSION['status'] != '') { ?>
	<script>
		swal.fire({
			title: "<?php echo $_SESSION['status']; ?>",
			icon: "<?php echo $_SESSION['status_code']; ?>",
			confirmButtonColor: '#449A97',
			confirmButtonText: 'Ok'
		});
	</script>
<?php unset($_SESSION['status']);
}  ?>
<script>
	$(document).ready(function() {
		$("#conformpassword").on('keyup', function() {
			var newpassword = $("#newpassword").val();
			var conformpassword = $("#conformpassword").val();
			if (newpassword != conformpassword)
				$("#CheckPasswordMatch").html("Password does not match !").css("color", "red");
			else
				//$("#checked_pass").attr("disabled", false);
				$("#CheckPasswordMatch").html("Password match !").css("color", "green");
		});
	});
</script>