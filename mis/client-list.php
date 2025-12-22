<?php include_once('includes/config.php'); ?>
<?php define("title", "List Clients | MQUAD"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php
if ($_SESSION['role_id'] == 7) {
	if (!isset($_SERVER['HTTP_REFERER'])) {
		echo "<script>alert('Sorry, You Are Not Allowed to Access This Page');</script>";
		echo "<script>window.location.href='dashboard.php'</script>";
		exit;
	}
}
?>
<?php

$qry = '';
if (isset($_REQUEST['search'])) {
	if (isset($_REQUEST['id']) && $_REQUEST['id'] != '') {
		$qry .= " AND clients.role_id='" . $_REQUEST['id'] . "'";
	}
	if (isset($_REQUEST['name']) && $_REQUEST['name'] != '') {
		$qry .= " AND clients.name like '%" . $_REQUEST['name'] . "%'";
	}
}
?>
<?php

//pagination
$per_page = 10;

$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$page_url = "?";
$page_url = isset($_GET['id']) ? $page_url . "user_type_id=" . $_GET['id'] : $page_url;
$page_url = isset($_GET['name']) ? $page_url . "&name=" . $_GET['name'] : $page_url;
$page_url = isset($_GET['search']) ? $page_url . "&search=" . $_GET['search'] : $page_url;

$page = 0;
$current_page = 1;
if (isset($_GET['page'])) {
	$current_page = intval($_GET['page']);
	$page = ($current_page - 1) * $per_page;
}
$query = "select clients.id,clients.name,clients.email,roles.name as role_type_name,clients.status from clients INNER JOIN roles on clients.role_id=roles.id where clients.role_id!='1' AND clients.del_action='N' $qry order by clients.id DESC";
$get_query = mysqli_query($conn, $query);
$total_record = mysqli_num_rows($get_query);
$total_pages = ceil($total_record / $per_page);
?>
<?php
if (!empty($_REQUEST['delid'])) {
	$did = $_GET['delid'];
	$delsql = mysqli_query($conn, "UPDATE clients SET del_action='Y' where id='" . $did . "'");
	if ($delsql) {
		echo "<script>alert('Deleted Successfully...')</script>";
	}
	echo "<script>window.location.href='client-list.php'</script>";
}
?>
<style>
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        input:checked + .slider {
            background-color: #2196F3;
        }
        input:checked + .slider:before {
            transform: translateX(26px);
        }
    </style>
<!--main content start-->

<section id="main-content">
	<section class="wrapper">

		<div class="row">
			<div class="col-lg-12">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><i class="fa fa-user" aria-hidden="true"></i>Client Management</li>
						<li class="breadcrumb-item" aria-current="page"><i class="fa fa-list"></i>List Clients</li>
					</ol>
				</nav>
			</div>
		</div>
		<!-- page start-->
		<div class="row">
			<div class="col-sm-12">
				<div class="container-fluid1">
					<form class="form-inline" method="get" role="form">
						<div class="row filter_css clearfix g-2 mb-3">
							<div class="col-lg-5"">
								<select class="form-select" name="id" id="id">
									<option value="">Select Functional Type</option>
									<?php
									$roletype = mysqli_query($conn, "SELECT id,name FROM `roles` where id!='1' order by name ASC");
									while ($type = mysqli_fetch_array($roletype)) { ?>
										<option value="<?php echo $type['id'] ?>" <?php if ($type['id'] == $_REQUEST['id']) {
																						echo "selected";
																					} ?>><?php echo $type['name'] ?></option>
									<?php
									}

									?>
								</select>
							</div>
							<div class="form-group col-md-5"">
								<input type="text" class="form-control" name="name" placeholder="Name"></input>
							</div>
							<div class="form-group col-md-2"">
								<button type="submit" class="btn btn-primary width-md waves-effect waves-light form-control" name="search">Search</button>
							</div>
						</div>
					</form>
				</div>
				<section class="panel">
					<header class="panel-heading">Total Client: <?= $total_record ?>
					</header>
					<div class="table-responsive">
						<table class="table table-striped">
							<thead>
								<tr>
									<th class="">S.No</th>
									<th class="">Client name</th>
									<!--<th class="">Mobile</th>-->
									<th class="">Email</th>
									<!-- <th class="">Address </th>-->
									<th class="">Functional Type</th>
									<!-- <th class="">Profile Picture</th>-->
									<!-- <th class="">Status</th>-->
									<th class="">Subscription</th>
									<th class="">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$_SESSION['query'] = "select clients.id,clients.subscription,clients.name,clients.email,roles.name as role_type_name,clients.status from clients INNER JOIN roles on clients.role_id=roles.id where clients.role_id!='1' AND clients.del_action='N' order by clients.id DESC";
								$sql = "select clients.id,clients.name,clients.subscription,clients.email,roles.name as role_type_name,clients.status from clients INNER JOIN roles on clients.role_id=roles.id where clients.role_id!='1' AND clients.del_action='N' $qry order by clients.id DESC limit $page,$per_page";
								$getsql = mysqli_query($conn, $sql);
								$sn = 1 + $page;
								while ($user = mysqli_fetch_array($getsql)) { ?>
									<tr>
										<td><?= $sn++; ?></td>
										<td><?= ucfirst($user['name']) ?></td>
										<!--<td><?= $user['mobile'] ?></td>-->
										<td><?= $user['email'] ?></td>
										<!-- <td><?= $user['address'] ?></td>-->
										<td><?= $user['role_type_name'] ?></td>
										<!--<td><a href="#" class="popup-image"><img src="img/<?= $user['profile_img']; ?>"style="max-width: 50px; max-height: 50px; line-height: 20px;"></a></td>-->
										<!-- <td>
										<?php if ($user['status'] == 1) { ?>
                                            <span class="label label-danger">Rejected</span>
                                        <?php } else { ?>
										    <span class="label label-success">Approved</span>
                                        <?php } ?>
									 </td>-->
									    <td>
										<label class="switch">
											<input type="checkbox" class="toggle-status" data-id="<?= $user['id'] ?>" <?php if($user['subscription']=="0") { echo "checked"; }  ?>>
											<span class="slider"></span>
										</label>
										</td>
										<td>
											<a href="user-profile.php?cid=<?= $user['id']; ?>" class="btn btn-sm btn-primary"><i class="fa fa-eye" aria-hidden="true"></i></a>
											<a href="?delid=<?= $user['id']; ?>" onclick="return confirm('Do you want to delete ?');" ; class="btn btn-sm btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></a>
										</td>
									</tr>
								<?php
								}
								?>
							</tbody>
						</table>
					</div>
				</section>
			</div>
		</div>
		<div class="d-flex d-title-box-block align-items-center justify-content-between footer-paging">
			<div class="col-md-10">
				<div class="d-flex align-items-center" id="pagination">
					<?= paginate($per_page, $current_page, $total_record, $total_pages, $page_url); ?>
				</div>
			</div>
			<?php
			$_SESSION['header_column'] = "Name,email,Functional Type";
			$_SESSION['db_column'] = "name,email,role_type_name";
			?>
			<div class=" col-md-2 " style="margin-bottom: 0rem!important; padding-top: 5px">
				<a class="btn btn-success btn-sm waves-effect width-md waves-light " href="export/export.php">
					<i class="fa fa-file-excel-o" aria-hidden="true"></i> Export To CSV</a>
			</div>
		</div>


		<!-- page end-->
	</section>
</section>
<!--main content end-->

<?php include_once('includes/footer.php'); ?>
<script>
$(document).ready(function() {
    $('.toggle-status').on('change', function() {
        var itemId = $(this).data('id');
        var status = $(this).is(':checked') ? 1 : 0;

        $.ajax({
            url: 'ajax/get_user_ajax.php',
            type: 'POST',
            data: {
                id: itemId,
                status: status
            },
            success: function(response) {
                console.log('Status updated successfully');
            },
            error: function() {
                console.error('Failed to update status');
            }
        });
    });
});
</script>
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