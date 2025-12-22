<?php include_once('includes/config.php'); ?>
<?php define("title", "User Profile | MQUAD"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php
$getid = $_REQUEST['cid'];
if (isset($_REQUEST['cid']) && $_REQUEST['cid'] != '') {
	//echo "select clients.id,clients.name,clients.email,clients.mobile,clients.logo_image,clients.address,clients.mobile,roles.name as role_type_name,clients.membership_id,clients.registered_as from clients left join roles on clients.role_id=roles.id where clients.id='".$_REQUEST['cid']."'";
	$getsql = mysqli_query($conn, "select clients.id,clients.name,clients.email,clients.mobile,clients.logo_image,clients.address,clients.mobile,roles.name as role_type_name,clients.membership_id,clients.registered_as from clients left join roles on clients.role_id=roles.id where clients.id='" . $_REQUEST['cid'] . "'");
	$data = mysqli_fetch_array($getsql);
}

/*if(isset($_REQUEST['update_user'])){
		
		$id=$_REQUEST['id'];
		$name=$_REQUEST['name'];
		$email=$_REQUEST['email'];
		$update=mysqli_query($conn,"UPDATE clients SET name='".$name."',email='".$email."' WHERE clients.id='".$id."' ");
		if($update){
			echo "<script>alert('Profile update successfully');</script>";
		}
		  echo "<script>window.location.href='client-list.php'</script>";
		
	}*/
?>
<?php
if (isset($_REQUEST['change_password'])) {
	$userId = $_SESSION['user_id'];
	$oldpass = mysqli_real_escape_string($conn, $_POST['oldpass']);
	$oldpassword = password_hash($oldpass, PASSWORD_DEFAULT);
	$newpass = mysqli_real_escape_string($conn, $_POST['newpassword']);
	$newpassword = password_hash($newpass, PASSWORD_DEFAULT);
	$confirmpass = mysqli_real_escape_string($conn, $_POST['conformpassword']);
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
			$_SESSION['status'] = "Password updated successfully";
			$_SESSION['status_code'] = "success";
		} else {
			$_SESSION['status_error'] = "New and confirm Password is not match!!";
			$_SESSION['status_error_code'] = "error";
		}
	} else {
		$_SESSION['status_error'] = "Old Password Do Not Match!!";
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

		align-items: center;
	}

	.stat {
		display: flex;
		flex-direction: column;
		align-items: center;
		width: 32%;

	}

	.stat:nth-child(2) {
		border: 1px solid #adadad;
		border-top: 0;
		border-bottom: 0;

	}

	.stat-num {
		font-size: 2.0rem;
	}

	.stat-name {
		font-size: 1rem;
		color: #449a97;
	}

	.panel .panel-heading,
	.custom-panel .accordion-header {
		line-height: 25px;
		padding: 0;
	}
</style>
<!--main content start-->
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="row">
				<div class="col-sm-12 text-center">
					<?php if ($successerror != '') { ?>
						<div class="alert alert-success" role="alert">
							<?= $successerror; ?>
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
					<?php } ?>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12 text-center">
					<?php if ($error != '') { ?>
						<div class="alert alert-danger" role="alert">
							<?= $error; ?>
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
					<?php } ?>
				</div>
			</div>
			<div class="col-lg-12">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><i class="fa fa-list"></i>Client List</li>
						<li class="breadcrumb-item" aria-current="page"><i class="fa fa-users" aria-hidden="true"></i>Client Profile</li>
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
						<!--<div class="col-lg-2 col-sm-2">
						<h4><? php // echo $data['name']
							?></h4>
						<div class="follow-ava">
						<?php
						//if($data['profile_img']!=''){ 
						?>
								<img src="img/<?php //echo $data['profile_img']
												?>" alt="">
							<?php //} else{ 
							?>
								<img src="img/user1.png" alt="">
							<?php	//} 
							?>
							
						</div>
						<h6><?php //echo $data['role_type_name']
							?></h6>
						
					</div>-->
						<div class="row">
							<div class="col-lg-6 col-sm-6 follow-info py-0">
								<p>Email ID: <?= $data['email'] ?></p>
								<p>Registered As: <?= $data['registered_as'] ?></p>
								<p>Registered As: <?= $data['role_type_name'] ?></p>
								<p>Membership Plan:
									<?php
									if ($data['membership_id'] == '1') {
										echo "Basic";
									} else if ($data['membership_id'] == '2') {
										echo "Standard";
									} else if ($data['membership_id'] == '3') {
										echo "Advance";
									} else {
										echo "No Plan";
									}

									?>
								</p>

							</div>
							<div class="col-lg-2 col-sm-6 follow-info weather-category">
								<?php
								$totalservey = mysqli_query($conn, "SELECT count(id) FROM `survey` where client_id='" . $_REQUEST['cid'] . "'");
								$data = mysqli_fetch_array($totalservey);
								//echo $data['count(id)'];
								?>
								<ul>
									<li class="active">
										<i class="fa fa-comments fa-2x"> </i><br> Total Forms: <b><?= $data['count(id)']; ?></b>
									</li>
								</ul>
							</div>

							<div class="col-lg-2 col-sm-6 follow-info weather-category">

								<ul>
									<li class="active">
										<i class="fa fa-bell fa-2x"> </i><br> Published: <b><?= $data['count(id)']; ?></b>
									</li>
								</ul>
							</div>
							<div class="col-lg-2 col-sm-6 follow-info weather-category">
								<?php
								$unpublish = mysqli_query($conn, "SELECT count(id) FROM `survey` where client_id='" . $_REQUEST['cid'] . "' and status='0'");
								$data = mysqli_fetch_array($unpublish);
								//echo $data['count(id)'];
								?>
								<ul>
									<li class="active">
										<i class="fa fa-tachometer fa-2x"> </i><br> Unpublished: <b><?= $data['count(id)']; ?></b>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<header class="panel-heading tab-bg-info mt-3">
						<ul class="nav nav-tabs" id="myTab" role="tablist">
							<li class="nav-item" role="presentation">
								<a class="nav-link active" id="recent-activity-tab" data-bs-toggle="tab" href="#recent-activity" role="tab" aria-controls="recent-activity" aria-selected="true">

									Last Activity
								</a>
							</li>
							<li class="nav-item" role="presentation">
								<a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">

									Profile
								</a>
							</li>
							<!--<li class="nav-item" role="presentation">
								<a class="nav-link" id="edit-profile-tab" data-bs-toggle="tab" href="#edit-profile" role="tab" aria-controls="edit-profile" aria-selected="false">
									<i class="icon-envelope"></i>
									Edit Profile
								</a>
							</li>-->
							<li class="nav-item" role="presentation">
								<a class="nav-link" id="change-password-tab" data-bs-toggle="tab" href="#change-password" role="tab" aria-controls="change-password" aria-selected="false">

									Change Password
								</a>
							</li>
						</ul>
					</header>
					<div class="panel-body">
						<div class="tab-content" id="myTabContent">
							<div id="recent-activity" class="tab-pane active">
								<div class="profile-activity">
									<div class="act-time">
										<div class="activity-body act-in">
											<span class="arrow"></span>
											<div class="text">
												<?php
												$sql = mysqli_query($conn, "select survey_data_monitoring_id,survey_data_monitoring.created_on,survey_name,users.name from survey_data_monitoring INNER JOIN users on survey_data_monitoring.user_id=users.user_id where survey_data_monitoring.client_id='" . $_REQUEST['cid'] . "' order by survey_data_monitoring.created_on DESC LIMIT 0,1");
												$getdata = mysqli_fetch_array($sql);
												?>
												<p class="attribution">User name: </a> <?= $getdata['name'] ?></p>
												<hr>
												<p class="attribution">Last forms collected: </a> <?php if ($getdata['created_on'] != "") {
																										echo date("d-M-Y", strtotime($getdata['created_on']));
																									} ?></p>
												<hr>
												<p class="attribution">Form name: </a> <?= $getdata['survey_name'] ?></p>

											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- profile -->
							<div id="profile" class="tab-pane">
								<section class="panel">
									<div class="bio-graph-info">
										<div class="row">
											<div class="col-lg-6">
												<h1>Form Collected</h1>
												<div class="row">
													<div class="profile-activity">
														<div class="act-time">
															<div class="activity-body act-in">
																<span class="arrow"></span>
																<div class="text">
																	<?php
																	$surveysql = mysqli_query($conn, "SELECT COUNT(survey_data_monitoring_id) AS total_monitoring FROM `survey_data_monitoring` where client_id='" . $_REQUEST['cid'] . "'");
																	$data = mysqli_fetch_array($surveysql);
																	//echo $data['total_monitoring'];
																	?>
																	<p class="attribution" style="font-size:15px;"><a href="#">Total Form Collected: </a> <?= $data['total_monitoring'] ?></p>
																	<hr>
																	<?php
																	$accsql = mysqli_query($conn, "SELECT COUNT(survey_data_monitoring_id) AS total_acc FROM `survey_data_monitoring` where survey_status='5' and client_id='" . $_REQUEST['cid'] . "'");
																	$data = mysqli_fetch_array($accsql);
																	//echo $data['total_acc'];
																	?>
																	<p class="attribution" style="font-size:15px;"><a href="#">Accepted Form Collected: </a> <?= $data['total_acc'] ?></p>
																	<hr>
																	<?php
																	$rejsql = mysqli_query($conn, "SELECT COUNT(survey_data_monitoring_id) AS total_rej FROM `survey_data_monitoring` where survey_status='4' and client_id='" . $_REQUEST['cid'] . "'");
																	$data = mysqli_fetch_array($rejsql);
																	//echo $data['total_rej'];
																	?>
																	<p class="attribution" style="font-size:15px;"><a href="#">Rejected Form Collected: </a> <?= $data['total_rej'] ?></p>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="col-lg-6">
												<center>
													<h1>Users</h1>
												</center></br>
												<div class="profile-card">
													<div class="stats">
														<div class="stat">
															<?php
															$totalsql = mysqli_query($conn, "SELECT COUNT(user_id) as total_user FROM `users` where client_id='" . $_REQUEST['cid'] . "'");
															$total = mysqli_fetch_array($totalsql);
															//echo $total['total_user'];
															?>
															<span class="stat-num"><?= $total['total_user'] ?></span>
															<span class="stat-name">TOTAL USERS</span>
														</div>

														<div class="stat">
															<?php
															$activesql = mysqli_query($conn, "SELECT COUNT(user_id) as total_active FROM `users` where status='0' and client_id='" . $_REQUEST['cid'] . "'");
															$active = mysqli_fetch_array($activesql);
															//echo $total['total_active'];
															?>
															<span class="stat-num"><?= $active['total_active'] ?></span>
															<span class="stat-name">ACTIVE USERS</span>
														</div>

														<div class="stat">
															<?php
															$inactivesql = mysqli_query($conn, "SELECT COUNT(user_id) as total_inactive FROM `users` where status='1' and client_id='" . $_REQUEST['cid'] . "'");
															$inactive = mysqli_fetch_array($inactivesql);
															//echo $inactive['total_inactive'];
															?>
															<span class="stat-num"><?= $inactive['total_inactive'] ?></span>
															<span class="stat-name">INACTIVE USERS</span>
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
							<!-- edit-profile -->
							<?php
							$getsqlClient = mysqli_query($conn, "select clients.id as id,clients.name as name,clients.email,clients.mobile,clients.logo_image,clients.address,clients.mobile,roles.name as role_type_name,clients.membership_id,clients.registered_as from clients left join roles on clients.role_id=roles.id where clients.id='" . $_REQUEST['cid'] . "'");
							$dataClient = mysqli_fetch_array($getsqlClient);
							?>
							<!--<div id="edit-profile" class="tab-pane">
							<section class="panel">
								<div class="panel-body bio-graph-info">
									<h1>Update Profile </h1>
									<form class="form-horizontal" role="form" action="" method="POST">
										<input type="hidden" class="form-control" id="name" name="id" value="<?= $dataClient['id'] ?>" >
											<div class="row mb-2">
												<label class="col-lg-2 control-label">Full name</label>
												<div class="col-lg-6">
													<input type="text" class="form-control" id="name" name="name" value="<?= $dataClient['name'] ?>" >
												</div>
											</div>
										<div class="row mb-2">
											<label for="email" class="control-label col-lg-2">Email</label>
											<div class="col-lg-6">
												<input class="form-control " id="email" name="email" autocomplete="new-email" type="email" value="<?= $dataClient['email'] ?>"/>
											</div>
										</div>
										<div class="row mb-2">
											<div class="text-end">
												<button type="submit" class="btn btn-primary" name="update_user">Update</button>
											</div>
										 </div>
									</form>
								</div>
							</section>
							</div>-->

							<!--change password-->
							<div id="change-password" class="tab-pane">
								<section class="panel">
									<div class="bio-graph-info">
										<h1>Change Password </h1>
										<form class="form-horizontal" role="form" action="" method="POST">
											<input type="hidden" class="form-control" id="name" name="id" value="<?= $user['id'] ?>">
											<div class="row mb-2">
												<label class="col-lg-2 control-label">Old Password: <span style="color:red">*</span></label>
												<div class="col-lg-6">
													<input type="password" class="form-control" required id="old_password" name="oldpass" />
												</div>
											</div>
											<div class="row mb-2">
												<label class="col-lg-2 control-label">New Password: <span style="color:red">*</span></label>
												<div class="col-lg-6">
													<input type="password" class="form-control" required id="password" name="newpassword" />
												</div>
											</div>
											<div style="margin-top: 7px;" id="NewPasswordMatch"></div>
											<div class="row mb-2">
												<label class="col-lg-2 control-label">Confirm Password: <span style="color:red">*</span></label>
												<div class="col-lg-6">
													<input type="password" class="form-control" required id="confirm_password" name="conformpassword" />
												</div>
											</div>
											<div style="margin-top: 7px;" id="CheckPasswordMatch"></div>
											<div class="row mb-2">
												<div class="text-end">
													<button type="submit" id="checked_pass" class="btn btn-primary" name="change_password" disabled>Submit</button>
												</div>
											</div>

										</form>
									</div>
								</section>
							</div>
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
<script>
	$(document).ready(function() {

		$("#confirm_password").on('keyup', function() {
			var password = $("#password").val();
			var confirmPassword = $("#confirm_password").val();
			if (password != confirmPassword)
				$("#CheckPasswordMatch").html("Password does not match !").css("color", "red");
			else
				$("#checked_pass").attr("disabled", false);
			$("#CheckPasswordMatch").html("Password match !").css("color", "green");

		});
	});
</script>