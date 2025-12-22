<?php include_once('includes/config.php'); ?>
<?php define("title", "List Users | MQUAD"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>


<?php
$client_qry = "";
if ($_SESSION['role_id'] == '3') {
	$client_id = $_SESSION['client_id'];
	$client_qry = " and users.client_id='" . $client_id . "' ";
}
?>
<?php
$page = $_GET['page'];
$pages = '';
if ($page != '') {
	$pages = $_GET['page'];
} else {
	$pages = 1;
}
?>
<?php
if ($_SESSION['role_id'] == 7) {
	if (!isset($_SERVER['HTTP_REFERER'])) {
		// redirect them to your desired location
		echo "<script>alert('Sorry, You Are Not Allowed to Access This Page');</script>";
		echo "<script>window.location.href='dashboard.php'</script>";
		exit;
	}
}
?>

<?php
$qry = '';
if (isset($_REQUEST['search'])) {
	if (isset($_REQUEST['name']) && ($_REQUEST['name'] != "")) {
		$qry .= " AND users.name like '%" . $_REQUEST['name'] . "%' ";
	}
	if (isset($_REQUEST['user_type_id']) && $_REQUEST['user_type_id'] != '') {
		$qry .= " AND users.role_id='" . $_REQUEST['user_type_id'] . "'";
	}
	if (isset($_REQUEST['email']) && $_REQUEST['email'] != '') {
		$qry .= " AND users.email='" . $_REQUEST['email'] . "'";
	}
	if (isset($_REQUEST['status']) && $_REQUEST['status'] != '') {
		$qry .= " AND users.status='" . $_REQUEST['status'] . "'";
	}
	/* if (isset($_REQUEST['fdate']) && isset($_REQUEST['tdate'])) {  
			$d1 = date('Y-m-d h:i:s', strtotime($_REQUEST['fdate']));
			$d1 = $_REQUEST['fdate'] . ' 00:00:00';
			$d2 = date('Y-m-d h:i:s', strtotime($_REQUEST['tdate']));
			$d2 = $_REQUEST['tdate'] . ' 23:59:00';
			if (!empty($_REQUEST['fdate']) && !empty($_REQUEST['tdate'])) {
				if ($qry != ' ') {
					$qry .= 'AND ';
				}
				$qry .= "users.created_at BETWEEN '" . $d1 . "' AND '" . $d2 . "'";
			} else {
				if (!empty($_REQUEST['fdate'])) {
					if ($qry != ' ') {
						$qry .= 'AND ';
					}
					$qry .= "users.created_at >= '" . $d1 . "'";
				}
				if (!empty($_REQUEST['tdate'])) {
					if ($qry != ' ') {
						$qry .= 'AND ';
					}
					$qry .= "users.created_at <= '" . $d2 . "'";
				}
			}
		} */
}

?>
<?php

//pagination
$per_page = 10;

$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$page_url = "?";
//$page_url = isset($_GET['page'])? $page_url."page=".$_GET['page']:$page_url;
$page_url = isset($_GET['name']) ? $page_url . "name=" . $_GET['name'] : $page_url;
$page_url = isset($_GET['user_type_id']) ? $page_url . "user_type_id=" . $_GET['user_type_id'] : $page_url;
$page_url = isset($_GET['email']) ? $page_url . "&email=" . $_GET['email'] : $page_url;
$page_url = isset($_GET['status']) ? $page_url . "&status=" . $_GET['status'] : $page_url;
$page_url = isset($_GET['search']) ? $page_url . "&search=" . $_GET['search'] : $page_url;

$page = 0;
$current_page = 1;
if (isset($_GET['page'])) {
	$current_page = intval($_GET['page']);
	$page = ($current_page - 1) * $per_page;
}
$query = "SELECT users.user_id,users.name,users.mobile,users.email,roles.name as user_type,users.status as user_status,users.created_at FROM users INNER join roles on users.role_id=roles.id where users.del_action='N' $client_qry $qry order by users.user_id DESC";


$get_query = mysqli_query($conn, $query);
$total_record = mysqli_num_rows($get_query);
$total_pages = ceil($total_record / $per_page);
?>
<?php
if (isset($_POST['setUserPermission'])) {
	$user_id = $_POST['user_id'];
	$survey_id = $_POST['survey_id'];
	$permissions = $_POST['permissions']; //arr
	if (count($permissions) > 0 && $user_id != "" && $survey_id != "") {

		$getPerms = mysqli_query($conn, "SELECT COUNT(id) as tot FROM formlabel_controls WHERE user_id='" . $user_id . "' AND survey_id='" . $survey_id . "' ");
		$getPermsData = mysqli_fetch_object($getPerms);
		if ($getPermsData->tot > 0) {
			$userPermissions = implode(",", $permissions);
			$setPermissions = mysqli_query($conn, "update formlabel_controls set page_button_id='" . $userPermissions . "' where user_id='" . $user_id . "' and survey_id='" . $survey_id . "' ");
			if ($setPermissions) {
				$_SESSION['status'] = "Permission Updated Successfully";
				$_SESSION['status_code'] = "success";
				//echo "<script window.location.href='user-list.php?page=$pages'></script>";
			} else {
				$_SESSION['status'] = "Something went wrong!!";
				$_SESSION['status_code'] = "warning";
			}
		} else {
			$userPermissions = implode(",", $permissions);
			$setPermissions = mysqli_query($conn, "insert into formlabel_controls set user_id='" . $user_id . "', survey_id='" . $survey_id . "', page_button_id='" . $userPermissions . "' ");
			if ($setPermissions) {
				$_SESSION['status'] = "Permission Set Successfully";
				$_SESSION['status_code'] = "success";
			} else {
				$_SESSION['status'] = "Something went wrong!!";
				$_SESSION['status_code'] = "warning";
			}
		}
	} else {
		$_SESSION['status'] = "All fields are required";
		$_SESSION['status_code'] = "warning";
	}
}
?>
<style>
	.panel-heading {
		background: #394a59;
		color: white;
		font-weight: unset;
	}

	.btn:not(:disabled):not(.disabled) {
		cursor: pointer;
	}

	.add-button-bg a {
		position: fixed;
		bottom: 54px;
		right: 50px;
		background: rgb(57, 74, 89);
		z-index: 99999;
		border-radius: 50%;
		width: 60px;
		height: 60px;
		color: #fff;
		line-height: 46px;
		font-size: 22px;
		transition: all .3s ease-in-out;
	}

	.add-button-bg a:hover {
		background: rgb(4 39 60);
		color: #ffffff;
		-webkit-transform: rotate(90deg);
		transform: rotate(90deg);
		box-shadow: 1px 1px 1px 17px rgb(255 192 192 / 28%);

	}

	.btn-success:hover,
	.btn-success:focus,
	.btn-success:active,
	.btn-success.active,
	.open .dropdown-toggle.btn-success {
		color: #ffffff;
		border-color: #003b64;
		background: #003b64;
	}

	.btn-danger:hover,
	.btn-danger:focus,
	.btn-danger:active,
	.btn-danger.danger,
	.open .dropdown-toggle.btn-danger {
		color: #ffffff;
		border-color: #003b64;
		background: #003b64;
	}

	.widget .padd,
	.modal-body {
		background-color: white;
		min-height: 93px;
	}

	.form-group1 {
		width: 20%;
	}

	.SumoSelect.open>.optWrapper {
		width: 500px;
	}

	.SumoSelect .select-all {
		padding: 1px 0 3px 35px;
	}

	.SumoSelect {
		width: 100% !important;
	}

	.SumoSelect .select-all {
		border-radius: 3px 3px 0 0;
		position: relative;
		border-bottom: 1px solid #ddd;
		background-color: #fff;
		padding: 1px 5px 2px 35px !important;
		height: 20px;
		cursor: pointer;
	}

	#main-content .wrapper .row {
		margin-bottom: 0px;
	}

	.panel {
		margin-bottom: 20px;
	}
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.sumoselect/3.0.2/sumoselect.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

<!--main content start-->
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">

				<ol class="breadcrumb">
					<li><i class="icon_documents_alt"></i>User Management</li>
					<li><i class="fa fa-list"></i>List Users</li>
				</ol>
			</div>
		</div>
		<!-- page start-->
		<div class="row">
			<div class="col-sm-12">
				<div class="container-fluid">
					<form class="form-inline" method="get" role="form">
						<div class="row filter_css clearfix">
							<div class="form-group col-lg-3" style="margin-bottom: 1rem!important;margin-top:-1rem!important;">
								<input type="text" class="form-control" name="name" value="<?= @$_REQUEST['name'] ?>" id="name" placeholder="Name">
							</div>
							<div class="form-group col-lg-3" style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
								<input type="email" class="form-control" name="email" value="<?= @$_REQUEST['email'] ?>" id="email" placeholder="Email ID"></input>
							</div>

							<div class="col-lg-2">
								<select class="form-control" id="user_status" name="status" style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
									<option value="">Status Type</option>
									<option value="0" <?php if ($_REQUEST['status'] == '0') {
															echo "selected";
														} ?>>Active</option>
									<option value="1" <?php if ($_REQUEST['status'] == '1') {
															echo "selected";
														} ?>>Inactive</option>
								</select>
							</div>
							<div class="form-group col-lg-2" style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
								<button type="submit" class="btn btn-primary width-md waves-effect waves-light form-control" id="btnsearch" name="search" disabled>Search</button>
							</div>
							<div class="form-group col-lg-2" style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
								<a href="user-list.php" class="btn btn-primary width-md waves-effect waves-light form-control">Clear Filter</a>
							</div>
						</div>
					</form>
				</div>
				<?php
				$sqlUser = mysqli_query($conn, "SELECT (SELECT COUNT(user_id) FROM users WHERE status = '0' AND del_action = 'N' $client_qry $qry) AS total_active_user,(SELECT COUNT(user_id) FROM users WHERE status = '1' AND del_action = 'N' $client_qry $qry) AS total_inactive_user");
				$userdata = mysqli_fetch_array($sqlUser);
				?>
				<section class="panel">
					<header class="panel-heading">Total Users: <?= $total_record ?> || Active: <?= $userdata['total_active_user'] ?> || Inactive: <?= $userdata['total_inactive_user'] ?>
						<!--<a href="javascript:" data-toggle="modal" data-target="#AssignUser" class="btn-sm btn-primary" style="float: right; margin-top: 4px;" data-backdrop="static" data-keyboard="false" data-whatever="@fat">Set Permission</a>-->
						<!--<a href="javascript:" data-toggle="modal" data-target="#AssignUser" class="btn btn-primary btn-sm" style="float: right; margin:1px;" >Set Permission</a>-->
						<a href="user_bulk_upload.php" class="btn btn-success btn-sm " style="float:right; margin:2px;">+ Upload User</a>
						<!--<a href="app_version.php" class="btn btn-primary btn-sm pull-right" style="float:right; margin:2px;">App Version</a>-->					
					
					</header>
					<div class="table-responsive">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>S.No</th>
									<th>Name</th>
									<!--<th class="">Mobile</th>-->
									<th>Email ID</th>
									<th width="18%">Functional Type</th>
									<th># of Form Assigned</th>
									<th>Created On</th>
									<th>Status</th>
									<th>Action</th>

								</tr>
							</thead>
							<tbody>
								<?php
								if ($total_record > 0) {

									$_SESSION['query'] = "SELECT users.user_id,users.name,users.mobile,users.email,users.username,users.status as user_status,users.created_at FROM users INNER join roles on users.role_id=roles.id where users.del_action='N' $client_qry $qry order by users.name ASC ";
									$sql = "SELECT users.user_id,users.name,users.mobile,users.email,roles.name as user_type,users.status as user_status,users.created_at FROM users INNER join roles on users.role_id=roles.id where users.del_action='N' $client_qry $qry order by users.user_id DESC limit $page,$per_page";

									$getUsers = mysqli_query($conn, $sql);
									$sn = 1 + $page;
									while ($user = mysqli_fetch_array($getUsers)) {
								?>
										<tr id="usid-<?= $user['user_id']; ?>">
											<td><?= $sn++; ?></td>
											<td><?= ucfirst($user['name']) ?></td>
											<!--<td><?= $user['mobile'] ?></td>-->
											<td><?= $user['email'] ?></td>
											<td>
												<?php
												$functionalsql = "SELECT GROUP_CONCAT(roles.name) as name FROM `functional_role` INNER join roles on functional_role.role_id=roles.id where functional_role.user_id='" . $user['user_id'] . "'";
												$getfunUsers = mysqli_query($conn, $functionalsql);
												$fundata = mysqli_fetch_array($getfunUsers);
												?>

												<?php echo $fundata['name']; ?>

											</td>
											<td class="text-center"><a href="view-user.php?id=<?= $user['user_id'] ?>">
													<?php echo getcount($conn, 'assign_survey', 'survey_id', 'status', '0', 'user_id', $user['user_id']); ?>
												</a>
											</td>
											<td><?= date('d-M-Y', strtotime($user['created_at'])); ?></td>
											<td>
												<?php
												if ($user['user_status'] == 1) {
												?>
													<a href="javascript:void(0);" data-id="<?= $user['user_id']; ?>" class="btn-sm btn-danger activeUser"><i class="fa fa-times-circle" style="font-size:15px"></i></a>
												<?php
												} else {
												?>
													<a href="javascript:void(0);" data-id="<?= $user['user_id']; ?>" class="btn-sm btn-success inactiveUser"><i class="fa fa-check-circle" aria-hidden="true" style="font-size:15px"></i></a>
												<?php
												}
												?>
											</td>
											<td>
												<div class="dropdown">
													<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="export-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
														Action
														<span class="caret"></span>
													</button>
													<ul class="dropdown-menu" aria-labelledby="export-btn">
														<li><a href="view-user.php?id=<?= $user['user_id']; ?>" class="">View</a></li>
														<li><a href="edit-user.php?id=<?= $user['user_id']; ?>&page=<?= $pages; ?>" class="">Edit</a></li>
														<li>
															<a href="javascript:void(0);" data-id="<?= $user['user_id']; ?>" class="resetuserid">Reset</a>
														</li>
														<?php
														if ($_SESSION['role_id'] == 1) { ?>
															<li>
																<a href="javascript:void(0);" data-id="<?= $user['user_id']; ?>" class="delUser">Delete</a>
															</li>
														<?php	}  ?>
													</ul>
												</div>
											</td>
										</tr>
								<?php }
								} else {
									echo '<tr><td colspan="12" class="text-center" style="font-size: 25px;"  >Records Not Found !!</td></tr>';
								} ?>
							</tbody>
						</table>
					</div>
				</section>
			</div>
		</div>
		<div class="d-flex d-title-box-block align-items-center justify-content-between footer-paging">
			<div class="col-md-10">
				<div class="d-flex align-items-center justify-content-between" id="pagination">
					<?= paginate($per_page, $current_page, $total_record, $total_pages, $page_url); ?>
				</div>
			</div>
			<?php
			$_SESSION['file_name'] = 'User-list.csv';
			$_SESSION['header_column'] = "Name,Mobile No,Username,Email Id,Created On";
			$_SESSION['db_column'] = "name,mobile,username,email,created_at";
			?>
			<div class=" col-md-2 export-csv" style="margin-bottom: 0rem!important; padding-top: 5px">
				<a class="btn btn-success btn-sm waves-effect width-md waves-light " href="export/export.php">
					<i class="fa fa-file-excel-o" aria-hidden="true"></i> Export To CSV</a>
			</div>
		</div>

		<!-- page end-->
	</section>
</section>

<!--SET PERMISSION-->
<!--<div class="modal fade" id="AssignUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title" id="exampleModalLabel">
					<span>Set User Permission</span></h3>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form action="" method="POST" enctype="multipart/form-data">
					<div class="modal-body">
						<div class="row">	
						<div class="col-lg-12">
							<label>Select User</label>
							<div class="form-group " >
							 
							 <select name="user_id" id="userid"  class="form-control" required style="margin-bottom: 10px" >
							  <option value="">Select User</option>
								 <?php
									/*$clnt='';
									if($_SESSION['role_id']=='3'){
										$clnt = " and users.client_id='".$_SESSION['client_id']."' ";
									}
								 $form_sql="SELECT DISTINCT(users.user_id),users.name,users.username FROM `functional_role` inner join users on users.user_id=functional_role.user_id where functional_role.role_id='9' $clnt order by users.name ASC";
								 $form_query=mysqli_query($conn,$form_sql);
								 if(mysqli_num_rows($form_query)>0)
								 {
									foreach($form_query as $rowform)
									{ ?>
										<option value="<?php echo $rowform['user_id'];?>"><?php echo $rowform['name']; ?></option>
									<?php
									}
								 }
								 else
								 {
									 echo "No Record Found";
								 }*/
									?>
							 </select>
							
							 
							 <select class="form-control" name="survey_id" id="surveyid" required>
								<option value="">Select Form</option>
								<?php
								/*$getSurveys = mysqli_query($conn,"SELECT id, survey_name FROM survey WHERE client_id='".$_SESSION['client_id']."' ");
									while($surveys = mysqli_fetch_object($getSurveys)){ ?>
										<option value="<?=$surveys->id?>"><?=$surveys->survey_name?></option>
									<?php	
									} */
								?>
							 </select>
							 </div>
							<label> PERMISSIONS </label>
							<div class="form-group">
								<ul class="tg-list">
									<?php //$getButtons = mysqli_query($conn,"SELECT page_button_id, page_name, activity_name FROM page_buttons WHERE page_name='survey-list.php' and status='0' "); while($buttons = mysqli_fetch_object($getButtons)){ 
									?>
										 <li class="tg-list-item">
											<h5><?= $buttons->activity_name ?></h5>
											<input class="tgl tgl-ios" id="inlineCheck<?= $buttons->page_button_id ?>" type="checkbox" name="permissions[]" value="<?= $buttons->page_button_id ?>"/>
											<label class="tgl-btn" for="inlineCheck<?= $buttons->page_button_id ?>"></label>
										  </li>
									<?php //$i ++;} 
									?>
								</ul>
							</div>
							<hr>
							<div class="text-right">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
						        <button type="submit" name="setUserPermission" class="btn btn-primary">Submit</button>
							</div>
							</div>
						  </div>
						</div>
					</div>
				</form>
			</div>
		</div>-->
</div>
<!--SET PERMISSION END-->
<!--main content end-->
<?php include_once('includes/footer.php'); ?>

<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.sumoselect/3.4.8/jquery.sumoselect.min.js"></script>
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
	$("#name,#user_status,#email").on("input", function() {

		if ($("#name").val() != '' || $("#email").val() != '' || $("#user_status").val() != '') {
			$('#btnsearch').prop('disabled', false);
		} else {
			$('#btnsearch').prop('disabled', true);
		}
	});
	$(function() {
		$('#toggle-active').bootstrapToggle({
			on: 'Active'
		});
	})
	$(function() {
		$('#toggle-inactive').bootstrapToggle({
			off: 'Inactive'
		});
	})

	function assignform(val) {
		$("#user_id").attr("value", val);
	}
	$(".multiple-select").select2({
		//maximumSelectionLength: 2
	});

	$('.check_multiselect').SumoSelect({
		selectAll: true,
		search: false,
	});
	$(".activeUser").on("click", function(e) {
		let activeuserid = $(this).data("id");
		//alert(activeuserid);
		e.preventDefault();
		Swal.fire({
			title: 'Are you sure to active this user?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#449A97',
			cancelButtonColor: '#449A97',
			confirmButtonText: 'Active'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: "ajax/get_ajax.php",
					type: "post",
					data: {
						activeuserid: activeuserid
					},
					success: function(res) {
						console.log(res);
						var ress = JSON.parse(res);
						if (ress.status == "1") {
							Swal.fire({
								title: 'Active successfully',
								icon: 'success',
								confirmButtonColor: '#449A97',
								confirmButtonText: 'Ok'
							}).then(() => {
                                 window.location.reload();
                            });
							//window.location.reload();
						}
					}
				})
			}
		});
	})
	$(".inactiveUser").on("click", function(e) {
		let inactive_userid = $(this).data("id");
		//alert(activeuserid);
		e.preventDefault();
		Swal.fire({
			title: 'Are you sure to Inactive this user?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#449A97',
			cancelButtonColor: '#449A97',
			confirmButtonText: 'Inactive'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: "ajax/get_ajax.php",
					type: "post",
					data: {
						inactive_userid: inactive_userid
					},
					success: function(res) {
						console.log(res);
						var ress = JSON.parse(res);
						if (ress.status == "1") {
							Swal.fire({
								title: 'Inactive successfully',
								icon: 'success',
								confirmButtonColor: '#449A97',
								confirmButtonText: 'Ok'
							}).then(() => {
                                window.location.reload();
                            });
							//window.location.reload();
						}
					}
				})
			}
		});
	})
	$(".delUser").on("click", function(e) {
		let deluserid = $(this).data("id");
		//alert(activeuserid);
		e.preventDefault();
		Swal.fire({
			title: 'Are you sure to Delete this user?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#449A97',
			cancelButtonColor: '#449A97',
			confirmButtonText: 'Active'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: "ajax/get_ajax.php",
					type: "post",
					data: {
						deluserid: deluserid
					},
					success: function(res) {
						console.log(res);
						var ress = JSON.parse(res);
						if (ress.status == "1") {
							$("#usid-" + deluserid).hide();
							Swal.fire({
								title: 'Delete successfully',
								icon: 'success',
								confirmButtonColor: '#449A97',
								confirmButtonText: 'Ok'
							})
							//window.location.reload();
						}
					}
				})
			}
		});
	})
	$(".resetuserid").on("click", function(e) {
		let resetid = $(this).data("id");
		e.preventDefault();
		Swal.fire({
			title: 'Are you sure to Reset this account?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#449A97',
			cancelButtonColor: '#449A97',
			confirmButtonText: 'Reset'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: "ajax/get_ajax.php",
					type: "post",
					data: {
						resetid: resetid
					},
					success: function(res) {
						console.log(res);
						var ress = JSON.parse(res);
						if (ress.status == "1") {
							Swal.fire({
								title: 'Reset successfully',
								icon: 'success',
								confirmButtonColor: '#449A97',
								confirmButtonText: 'Ok'
							})
							//window.location.reload();
						} else if (ress.status == "0") {
							Swal.fire({
								title: 'Something went wrong',
								icon: 'error',
								confirmButtonColor: '#449A97',
								confirmButtonText: 'Ok'
							})
							//window.location.reload();
						} else if (ress.status == "2") {
							Swal.fire({
								title: 'Notification not send',
								icon: 'error',
								confirmButtonColor: '#449A97',
								confirmButtonText: 'Ok'
							})
							//window.location.reload();
						}
					}
				})
			}
		});
	})

	$("#surveyid, #userid").on("change", function() {
		var surveyid = $("#surveyid").val();
		var userid = $("#userid").val();
		$(".tgl").each(function() {
			$(this).prop("checked", false);
		})
		//$(".tgl").attr("checked",false);
		if (surveyid != "" && userid != "") {
			$.ajax({
				type: "POST",
				url: "ajax_page.php",
				data: {
					surveyid: surveyid,
					userid: userid
				},
				success: function(res) {
					var result = JSON.parse(res);
					$.each(result, function(key, val) {
						$("#inlineCheck" + val).prop("checked", true);
					});
					//console.log(result);
				}
			});
		} else {
			console.log('Please select user name and form');
		}
	})
</script>
<script>
	$(document).ready(function() {
		$(window).on('scroll', function() {
			$('.dropdown').each(function() {
				var $dropdown = $(this);
				var $dropdownContent = $dropdown.find('.dropdown-menu');

				var dropdownTop = $dropdown.offset().top;
				var dropdownHeight = $dropdownContent.outerHeight();
				var viewportHeight = $(window).height();
				var scrollTop = $(window).scrollTop();

				var dropdownBottom = dropdownTop + dropdownHeight;

				// Check if dropdown will go beyond viewport
				if (dropdownBottom > viewportHeight + scrollTop) {
					$dropdownContent.addClass('dropdown-menu-right'); // Align to right if needed
					$dropdown.addClass('dropup'); // Optionally change to dropup for better visibility

					// Adjust dropdown position
					$dropdownContent.css('bottom', '100%');
				} else {
					$dropdownContent.removeClass('dropdown-menu-right'); // Remove alignment class
					$dropdown.removeClass('dropup'); // Remove dropup class

					// Reset dropdown position
					$dropdownContent.css('bottom', 'auto');
				}
			});
		});
	});
</script>