<?php include_once('includes/config.php'); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php include_once('includes/functions.php'); ?>

<?php
if (isset($_REQUEST['submit'])) {

	$name = mysqli_real_escape_string($conn, sanitizeInput($_REQUEST['name']));
	$email = mysqli_real_escape_string($conn, sanitizeInput($_REQUEST['email']));
	// $password=MD5($_REQUEST['password']);
	$passwd = $_REQUEST['confpassword'];
	$confpassword = password_hash($passwd, PASSWORD_DEFAULT);
	$registered_as = $_REQUEST['registered_as'];
	//$membership_id=$_REQUEST['membership_id'];
	$sqluser = mysqli_query($conn, "SELECT username,password,role_id FROM `users` where username='" . $email . "'");
	if (mysqli_num_rows($sqluser) > 0) {
		echo "<script>alert('Sorry... username already registered !')</script>";
	} else {
		$sqlclient = "INSERT INTO clients(name, email,role_id,registered_as,membership_id,status) VALUES ('$name','$email','3','$registered_as','0','1')";
		$getsql = mysqli_query($conn, $sqlclient);

		if ($getsql) {
			$client_id = mysqli_insert_id($conn);
			$sql2 = "INSERT INTO `users`(name,username,email, password, client_id, role_id,user_code,status,registered_as) VALUES ('$name','$email','$email','$confpassword','$client_id','3','$email','0','$registered_as')";
			$getsql2 = mysqli_query($conn, $sql2);
			if ($getsql2) {
				$lastuserid = mysqli_insert_id($conn);
				$insertFunctional = "INSERT INTO functional_role SET user_id='" . $lastuserid . "', role_id='3' ";
				mysqli_query($conn, $insertFunctional);

				mysqli_query($conn, "INSERT INTO menu_role(menu_id,role_id,user_id,status) SELECT  menu_id,'3','" . $lastuserid . "','1' FROM menu_role WHERE role_id='1' ");

				$getmenus = mysqli_query($conn, "SELECT GROUP_CONCAT(menu_id) AS menu_id FROM `functional_control` WHERE role_id='3' AND status='0'");
				$menus = mysqli_fetch_object($getmenus);
				$allMenus = $menus->menu_id;

				$sqlUpdatemenu = mysqli_query($conn, "UPDATE menu_role SET status='0' WHERE user_id='" . $lastuserid . "' and menu_id IN($allMenus) ");
				if ($sqlUpdatemenu) {
					$_SESSION['status'] = "Registration successfully";
					$_SESSION['status_code'] = "success";
					echo "<script>window.location.href='client-list.php'</script>";
				}
			} else {
			}
			$_SESSION['status_error'] = "Something went wrong!!";
			$_SESSION['status_error_code'] = "error";
		} else {
			$_SESSION['status_error'] = "Something went wrong!!";
			$_SESSION['status_error_code'] = "error";
		}
	}
}
?>

<!--main content start-->
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12"> 
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><i class="fa fa-users" aria-hidden="true"></i>Client Management</li>
						<li class="breadcrumb-item" aria-current="page"><i class="fa fa-plus"></i>Add Client</li>
					</ol>
				</nav>
			</div>
		</div>
		<!-- page start-->

		<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<header class="panel-heading">
						Registration Form
					</header>
					<div class="panel-body">
						<div class="form">
							<form class="form-validate form-horizontal " id="registration_form" method="post" enctype="multipart/form-data">
								<div class="row mb-2">
									<label for="fullname" class="control-label col-lg-2">Register As<span class="required" style="color:red">*</span></label>
									<div class="col-lg-10">
										<select class="form-select" name="registered_as" required>
											<option value="">Select Register As</option>
											<option value="individual" <?php if (isset($_REQUEST['registered_as'])) {
																			echo "selected";
																		} ?>>Individual</option>
											<option value="organization" <?php if (isset($_REQUEST['registered_as'])) {
																				echo "selected";
																			} ?>>Organization</option>

										</select>
									</div>
								</div>
								<!--<div class="row mb-2">
								<label for="membership_id" class="control-label col-lg-2">Membership<span class="required" style="color:red">*</span></label>
									<div class="col-lg-10">
										<select class="form-control" name="membership_id" required>
											<option value="">Select Membership </option>
											<option value="1"<?php //if(isset($_REQUEST['membership_id'])){ echo "selected";} 
																?>>Basic</option>
											<option value="2"<?php //if(isset($_REQUEST['membership_id'])){ echo "selected";} 
																?>>Standard</option>
											<option value="3"<?php //if(isset($_REQUEST['membership_id'])){ echo "selected";} 
																?>>Advance</option>
											
										</select>
									</div>
								</div>-->
								<div class="row mb-2">
									<label for="fullname" class="control-label col-lg-2">Client name <span class="required" style="color:red">*</span></label>
									<div class="col-lg-10">
										<input class=" form-control" id="form_fname" name="name" type="text" value="<?php echo $name; ?>" />
										<span class="error_form" id="fname_error_message" style="color:red;"></span>
									</div>
								</div>
								<?php if ($_SESSION['role_id'] != '3' && $_SESSION['role_id'] != '1') { ?>
									<div class="row mb-2">
										<label for="fullname" class="control-label col-lg-2">Role Type<span class="required" style="color:red">*</span></label>
										<div class="col-lg-10">
											<select class="form-control" name="role_master_id" required>
												<option value="">Select Role</option>
												<?php
												$rolesql = mysqli_query($conn, "select id,name from roles where id='3'");
												while ($type = mysqli_fetch_array($rolesql)) { ?>
													<option value="<?= $type['id']; ?>" <?php if (isset($_REQUEST['role_master_id'])) {
																							echo "selected";
																						} ?>><?= $type['name']; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
								<?php } ?>

								<div class="row mb-2">
									<label for="Email" class="control-label col-lg-2">Email <span style="color:red">*</span></label>
									<div class="col-lg-10">
										<input class="form-control " id="email" name="email" type="text" value="<?php if (isset($_REQUEST['submit'])) {
																													echo $_REQUEST['email'];
																												} ?>" />
										<p id="statususer"></p>
									</div>
								</div>
								<div class="row mb-2">
									<label for="password" class="control-label col-lg-2">Password <span class="required" style="color:red">*</span></label>
									<div class="col-lg-10">
										<input class="form-control " id="passwords" name="password" type="password" autocomplete="new-password" value="<?php echo $password; ?>" />
									</div>
								</div>
								<div class="row mb-2">
									<label for="password" class="control-label col-lg-2">Confirm Password <span class="required" style="color:red">*</span></label>
									<div class="col-lg-10">
										<input class="form-control " id="ConfirmPassword" name="confpassword" type="password" autocomplete="new-password" value="<?php echo $password; ?>" />
										<span id="CheckPasswordMatch"></span>
									</div>
								</div>

								<div class="form-group">
									<div class="col-lg-offset-2 col-lg-12  text-end">
										<button class="btn btn-primary" type="submit" id="register" name="submit">submit</button>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
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
		$("#ConfirmPassword").on('keyup', function() {
			var password = $("#passwords").val();
			//alert(password);
			var confirmPassword = $("#ConfirmPassword").val();
			if (password != confirmPassword)
				$("#CheckPasswordMatch").html("Password does not match !").css("color", "red");
			else
				$("#CheckPasswordMatch").html("Password match !").css("color", "green");
		});
	});
</script>
<script>
	$(document).ready(function() {
		$('#email').keyup(function() {
			var email = $(this).val();
			if (email.length >= 3) {
				$.ajax({
					url: "get_user_ajax.php",
					method: "POST",
					data: {
						username: email
					},
					dataType: "text",
					success: function(html) {
						$('#statususer').html(html);
						//alert(html);

					}
				})
			}
		})
	})
</script>

<script type="text/javascript">
	$(function() {
		$("#fname_error_message").hide();
		var error_fname = false;
		$("#form_fname").focusout(function() {
			check_fname();
		});

		function check_fname() {
			var pattern = /^[a-zA-Z ]*$/;
			var fname = $("#form_fname").val();
			if (pattern.test(fname) && fname !== '') {
				$("#fname_error_message").hide();
				$('#register').attr("disabled", false);
			} else {
				$("#fname_error_message").html("Only alphabets and spaces are allowed");
				$("#fname_error_message").show();
				$('#register').attr("disabled", true);
				//$("#form_fname").css("border-bottom","2px solid #F90A0A");
				error_fname = true;
			}
		}

	});
</script>
<!--main content end-->
<?php include_once('includes/footer.php'); ?>