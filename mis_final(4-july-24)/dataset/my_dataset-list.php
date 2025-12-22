<?php
include_once('../includes/config.php'); ?>
<?php define("title", "My Dataset(s) | MQUAD"); ?>
<?php include_once('../includes/header.php'); ?>
<?php include_once('../includes/left-sidebar.php'); ?>
<?php include_once('../includes/functions1.php'); ?>

<?php
// $username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];
// $role_id = $_SESSION['role_id'];
// $qryUser = $qryUser1 = $qryaccess = '';

// if ($user_id != '' and $role_id != '1') {
// 	$qryUser = " and (data_repositroy.user_id='" . $user_id . "' or data_repositroy.data_repositroy_status='1') ";
// 	$qryUser1 = " and data_repositroy_otherdata.user_id='" . $user_id . "' ";
// }
// if ($user_id != '' and $role_id == '1') {

// 	$qryaccess = " and data_repositroy_otherdata.data_access='Public' ";
// }


?>

<?php
$qry = '';
$qrycat = '';
if (isset($_REQUEST['searchdata'])) {
	if (isset($_REQUEST['title']) && trim($_REQUEST['title'] != '')) {
		$qry .= "AND project_datasets.title like '%" . trim($_REQUEST['title']) . "%'";
	}
	if (isset($_REQUEST['category_id']) && $_REQUEST['category_id'] != '') {
		$qry .= " AND project_datasets.thematic_area_id=" . $_REQUEST['category_id'] . "";

		$qry .= " AND find_in_set('" . $_REQUEST['category_id'] . "',project_datasets.thematic_area_id)";
		$qrycat .= " AND find_in_set('" . $_REQUEST['category_id'] . "',categories.category_id)";
	}
	$from_date = isset($_REQUEST['from_date']) ? trim($_REQUEST['from_date']) : '';
	$to_date = isset($_REQUEST['to_date']) ? trim($_REQUEST['to_date']) : '';

	if ($from_date != '' && $to_date != '') {
		$from_date = mysqli_real_escape_string($conn, $from_date);
		$to_date = mysqli_real_escape_string($conn, $to_date);

		$qry .= " AND ((from_date <= '$to_date' AND to_date >= '$from_date') 
                OR (from_date >= '$from_date' AND from_date <= '$to_date')
                OR (to_date >= '$from_date' AND to_date <= '$to_date'))";
	} elseif ($from_date != '') {
		$from_date = mysqli_real_escape_string($conn, $from_date);
		$qry .= " AND (from_date >= '$from_date' OR to_date >= '$from_date')";
	} elseif ($to_date != '') {
		$to_date = mysqli_real_escape_string($conn, $to_date);
		$qry .= " AND (from_date <= '$to_date' OR to_date <= '$to_date')";
	}
}
?>
<?php
// pagination
$per_page = 10;
$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$page_url = "?";
$page_url = isset($_GET['tital']) ? $page_url . "tital=" . $_GET['tital'] : $page_url;
$page_url = isset($_GET['category_id']) ? $page_url . "&category_id=" . $_GET['category_id'] : $page_url;
$page_url = isset($_GET['published_date']) ? $page_url . "&published_date=" . $_GET['published_date'] : $page_url;
$page_url = isset($_GET['institution_name']) ? $page_url . "&institution_name=" . $_GET['institution_name'] : $page_url;
$page_url = isset($_GET['searchdata']) ? $page_url . "&search=" . $_GET['searchdata'] : $page_url;
$page = 0;
$current_page = 1;
if (isset($_GET['page'])) {
	$current_page = intval($_GET['page']);
	$page = ($current_page - 1) * $per_page;
}

$query = "SELECT sync_status,dataset_id, title, description, type_of_study_id, from_date,to_date, type_of_study_other, thematic_area_id, keywords_id, institution_id, authors_id, contact_person_name, contact_person_email, data_type FROM `project_datasets` where project_datasets.status='1' $qry and user_id='$user_id' ORDER BY `dataset_id` DESC";

$get_query = mysqli_query($conn, $query);
$total_record = mysqli_num_rows($get_query);
$total_pages = ceil($total_record / $per_page);
// 
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet">

<style>
	.panel-heading {
		background: #394a59;
		color: white;
		font-weight: unset;
	}

	.btn-4cd964 {
		background-color: #4cd964;
		color: white;
		padding: 5px 20px;
		text-decoration: none;
		border-radius: 4px;
		/* Add any other styling you need */
	}

	.btn-4cd964:hover {
		background-color: #45b757;
		/* Slightly darker shade for hover effect */
	}
</style>
<!--main content start-->
<section id="main-content">
	<div class="wrapper">
		<div class="row">
			<div class="col-lg-12">

				<ol class="breadcrumb">
					<li><i class="icon_documents_alt"></i>Dataset(s)</li>
					<li><i class="fa fa-bar-chart"></i></i>My Dataset(s)</li>
				</ol>
				<!--Start filter-->
				<div class="container-fluid">
				</div>
				<!-- Filter End-->
			</div>
		</div>
		<!-- page start-->
		<div class="row">
			<div class="col-sm-12">
				<div class="panel panel-default">
					<div class="row">
						<div class="col-md-12">
							<form method="GET" style="padding: 10px 0px;">
								<div class="row">
									<div class="col-sm-4">
										<div class="search-widget">
											<input id="inputDataverseSearch" class="form-control" aria-label="Search all data" placeholder="Title" name="title" value="" style="
    margin-left: 9px;
">
										</div>
									</div>
									<div class="col-sm-2">
										<input type="text" name="from_date" id="from_datepicker" value="<?= @$_REQUEST['from_date'] ?>" placeholder="From Date" class="form-control">
									</div>
									<div class="col-sm-2">
										<!-- <div class="input-group">
										<label class="input-group-btn form-control">
												To &nbsp;
											</label> -->
										<input type="text" name="to_date" id="to_datepicker" placeholder="To Date" value="<?= @$_REQUEST['to_date'] ?>" class="form-control">
										<!-- </div> -->
									</div>
									<div class="col-sm-2">
										<button type="submit" class="btn btn-primary width-md waves-effect waves-light form-control" id="btnsearch" name="searchdata" disabled>Search</button>
									</div>
									<div class="form-group col-sm-2">
										<a href="my_dataset-list.php" class="btn btn-primary width-md waves-effect waves-light form-control">Clear
											Filter</a>
									</div>
								</div>
							</form>
						</div>

						<div class="col-lg-12">
							<div class="panel">
								<header class="panel-heading d-flex justify-content-between align-items-center">
									<div>Number of My Dataset(s): <?= $total_record ?></div>
									<div class="row-bottom text-right">
										<form enctype="multipart/form-data" action="" method="post">

											<a class="btn btn-md btn-primary" href="add-dataset.php"><i class="fa fa-plus"></i> Add Dataset</a>
										</form>
									</div>
								</header>
								<div class="table-responsive">
									<table class="table table-striped">
										<thead>
											<tr>

												<th>S.No</th>
												<th>Title</th>
												<th>Type of study</th>
												<th>Year of study</th>
												<th>Thematic Area(s)</th>
												<th>Institution/Organization </th>

												<th>Status</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$sqltools = "SELECT sync_status, dataset_id,user_id, title, description,dataset_status, from_date,to_date, type_of_study_id, type_of_study_other, thematic_area_id, keywords_id, institution_id, authors_id, contact_person_name, contact_person_email,related_publication, data_type ,study_types.study_name FROM `project_datasets`LEFT JOIN study_types on project_datasets.type_of_study_id=study_types.study_type_id  where project_datasets.status='1' $qry and user_id='$user_id'  ORDER BY `dataset_id` DESC limit $page,$per_page";
											$getsql = mysqli_query($conn, $sqltools);
											$sn = 1 + $page;
											if (mysqli_num_rows($getsql) > 0) {
												while ($dataset_row = mysqli_fetch_array($getsql)) {
													$data_status = $dataset_row['dataset_status'];
													$sync_status = $dataset_row['sync_status'];


											?>
													<tr>
														<td>
															<?= $sn++; ?>
														</td>
														<td>
															<?= $dataset_row['title']; ?>
														</td>
														<td>
															<?php echo $dataset_row['study_name'] ?>
														</td>
														<td>
															<?php
															$from_date = trim($dataset_row['from_date']);
															$to_date = trim($dataset_row['to_date']);
															if ($to_date === $from_date) { ?>
																<p class="text-center"><?php echo $from_date; ?></p>
															<?php } else { ?>
																<p class="text-center"><?php echo $from_date . "-" . $to_date; ?></p>
															<?php } ?>
														</td>
														<td> <?php $category_id = $dataset_row['thematic_area_id'];
																echo getcotegryname($conn, 'categories', 'category_id', $category_id, 'category_name')
																?></td>
														<td><?php
															$institution_id = $dataset_row['institution_id'];
															echo getKeyWordNames($conn, 'institution', 'institution_id', $institution_id, 'institution_name')
															?></td>
														<td><?php if ($data_status == '1') { ?>
																<span class="label label-success">Approved</span>
															<?php } else if ($data_status == '2') { ?>
																<span class="label label-danger">Rejected</span>
															<?php } else { ?>
																<span class="label label-warning">Pending</span>
															<?php } ?>
														</td>


														<?php if ($_SESSION['role_id'] == '1') { ?>
															<td>
																<?php if ($data_status == '1') { ?>
																	<a type="submit" class="btn btn-success" disabled>Approved</a>
																<?php } else if ($data_status == '0') { ?>
																	<a href="javascript:void();" type="submit" data-id="<?= $dataset_row['dataset_id'] ?>" class="btn btn-success approveRepository">Approve</a>
																<?php } ?>
																<?php if ($data_status == '2') { ?>
																	<a type="submit" class="btn btn-danger" disabled>Rejected</a>
																	<a href="javascript:void();" type="submit" data-id="<?= $dataset_row['dataset_id'] ?>" class="btn btn-success approveRepository">Approve</a>
																<?php } else { ?>
																	<a href="javascript:void();" type="submit" data-id="<?= $dataset_row['dataset_id'] ?>" class="btn btn-danger rejectRepository">Reject</a>
																<?php } ?>
															</td>
														<?php } else { ?>
															<td>
																<a href="add-datafile.php?dataset_id=<?= $dataset_row['dataset_id'] ?>" class="btn-sm btn-primary" title="Edit  tool"><i class="fa fa-plus" aria-hidden="true"></i>
																	</i></a>
																<a href="dataset-details.php?dataset_id=<?= $dataset_row['dataset_id'] ?>" class="btn-sm btn-primary" title="Edit  tool"><i class="fa fa-eye" aria-hidden="true"></i>
																	</i></a>
																<a href="javascript:void(0);" ata-id="<?= $dataset_row['dataset_id'] ?>" class="btn-sm btn-primary delRepository "><i class="fa fa-trash"></i></a>
															</td>
														<?php } ?>


													</tr>
											<?php }
											} else {
												echo '<tr><td colspan="7" class="text-center" style="font-size: 25px;"  >Records Not Found !!<br/> <a class="badge badge-primary" href="my_tool.php">View All</a> </td></tr>';
											} ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="d-flex d-title-box-block align-items-center justify-content-between footer-paging">
					<div class="col-md-10">
						<div class="" id="pagination">
						</div>
					</div>
					<?php
					// $_SESSION['file_name'] = 'My-Tool.csv';
					// $_SESSION['header_column'] = "S.No,Name,From Date,To Date,Thematic Area";
					// $_SESSION['db_column'] = "id,survey_title,tool_study_year_from,tool_study_year_to,category_name";
					// 
					?>
					<!-- // <div class=" col-md-2 export-csv" style="margin-bottom: 0rem!important; padding-top: 5px">
					// 	<a class="btn btn-success btn-sm waves-effect width-md waves-light " href="export/export.php">
					// 		<i class="fa fa-file-excel-o" aria-hidden="true"></i> Export To CSV</a>
					// </div> -->

				</div>

			</div>

		</div>
	</div>
	</div>
	<!-- page end-->
	</div>
</section>
<!--main content end-->
<?php include_once('../includes/footer.php'); ?>
<!-- Optional JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>

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
	$('.btn-sync').on('click', function(e) {
		e.preventDefault();
		var sync_dataset_id = $(this).data('id');
		// alert(sync_dataset_id);
		Swal.fire({
			title: 'Do you want to sync your dataset to dataverse?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#449A97',
			cancelButtonColor: '#449A97',
			confirmButtonText: 'Yes'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: "../ajax/sync.php",
					type: "post",
					data: {
						sync_dataset_id: sync_dataset_id
					},
					success: function(res) {
						var ress = JSON.parse(res);
						if (ress.status == "1") {
							Swal.fire({
								title: 'Sync successfully',
								icon: 'success',
								confirmButtonColor: '#449A97',
								confirmButtonText: 'Ok'
							})
							window.location.reload();
						}
					}
				})
			}
		});

	})



	$('.approveRepository').on('click', function(e) {
		e.preventDefault();
		var approve_data_id = $(this).data('id');
		Swal.fire({
			title: 'Are you sure to approve this Dataset?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#449A97',
			cancelButtonColor: '#449A97',
			confirmButtonText: 'Approve'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: "../ajax/get_ajax.php",
					type: "post",
					data: {
						approve_datasetid: approve_data_id
					},
					success: function(res) {
						var ress = JSON.parse(res);
						if (ress.status == "1") {
							Swal.fire({
								title: 'Approve successfully',
								icon: 'success',
								confirmButtonColor: '#449A97',
								confirmButtonText: 'Ok'
							})
							window.location.reload();
						}
					}
				})
			}
		});

	})



	$(".rejectRepository").on("click", function(e) {
		let data_reject_id = $(this).data("id");
		e.preventDefault();
		Swal.fire({
			title: 'Are you sure to reject this Dataset?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#449A97',
			cancelButtonColor: '#449A97',
			confirmButtonText: 'Reject'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: "../ajax/get_ajax.php",
					type: "post",
					data: {
						reject_datsetid: data_reject_id
					},
					success: function(res) {
						var ress = JSON.parse(res);
						if (ress.status == "1") {
							Swal.fire({
								title: 'Rejected successfully',
								icon: 'success',
								confirmButtonColor: '#449A97',
								confirmButtonText: 'Ok'
							})
							window.location.reload();
						}
					}
				})
			}
		});
	})

	$('.delRepository').on('click', function(e) {
		e.preventDefault();
		var del_data_id = $(this).data('id');
		Swal.fire({
			title: 'Are you sure to delete this Dataset?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#449A97',
			cancelButtonColor: '#449A97',
			confirmButtonText: 'Delete'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: "../ajax/get_ajax.php",
					type: "post",
					data: {
						del_datasetid: del_data_id
					},
					success: function(res) {
						console.log(res);
						var ress = JSON.parse(res);
						if (ress.status == "1") {
							$("#data_id-" + del_data_id).hide();
							Swal.fire({
								title: 'Deleted successfully',
								icon: 'success',
								confirmButtonColor: '#449A97',
								confirmButtonText: 'Ok'
							})
							window.location.reload();
						}
					}
				})
			}
		});
	})
</script>
<script type="text/javascript">
	var currentYear = new Date().getFullYear();
	$("#from_datepicker").datepicker({
		format: "yyyy",
		viewMode: "years",
		minViewMode: "years",
		autoclose: true,
		endDate: new Date(currentYear, 11, 31)
	}).on("changeDate", function(selected) {
		var minDate = new Date(selected.date.valueOf());
		$("#to_datepicker").datepicker("setStartDate", minDate);
	});

	$("#to_datepicker").datepicker({
		format: "yyyy",
		viewMode: "years",
		minViewMode: "years",
		autoclose: true
	});

	$("#to_datepicker").on("changeDate", function() {
		var fromDate = $("#from_datepicker").datepicker("getDate");
		var toDate = $("#to_datepicker").datepicker("getDate");

		if (fromDate && toDate && toDate < fromDate) {
			$("#to_datepicker").datepicker("setDate", fromDate);
		}
	});
</script>
<script>
	$("#category_id,#inputDataverseSearch,#from_datepicker,#to_datepicker").on("change", function() {

		if ($("#category_id").val() != '' || $("#inputDataverseSearch").val() != '' || $("#from_datepicker").val() != '' || $("#to_datepicker").val() != '') {
			$('#btnsearch').prop('disabled', false);
		} else {
			$('#btnsearch').prop('disabled', true);
		}
	});
</script>