<?php
include_once('../includes/config.php'); ?>
<?php define("title", "Show Dataset(s) | MQUAD"); ?>
<?php include_once('../includes/header.php'); ?>
<?php include_once('../includes/left-sidebar.php'); ?>
<?php include_once('../includes/functions1.php'); ?>

<?php
// $username = $_SESSION['username'];
// $user_id = $_SESSION['user_id'];
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

$query = "SELECT sync_status,dataset_id, title, description, type_of_study_id, from_date,to_date, type_of_study_other, thematic_area_id, keywords_id, institution_id, authors_id, contact_person_name, contact_person_email, data_type FROM `project_datasets` where project_datasets.status='1' $qry  and project_datasets.dataset_status=1  ORDER BY `dataset_id` DESC";

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
					<li><i class="fa fa-bar-chart"></i></i>Show Dataset(s)</li>
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
										<a href="dataset-list.php" class="btn btn-primary width-md waves-effect waves-light form-control">Clear
											Filter</a>
									</div>
								</div>
							</form>
						</div>
						<div class="col-md-8">
							<!-- <form method="GET">
								<div class="search-widget input-group mb-xs" style="margin:15px!important;">
									<input id="inputDataverseSearch" class="form-control" aria-label="Search all data" placeholder="Title" name="title" value="<?= $_REQUEST['title'] ?>">

									<span class="input-group-btn">
										<button id="btnDataverseSearch" class="btn btn-secondary bootstrap-button-tooltip" name="searchdata" type="submit" data-original-title="Find"><span class="fa fa-search"></span></button>
									</span>

								</div>
							</form> -->
							<div class="row pl-0 pr-0">
								<div class="col-md-12">
									<?php


									$sqldata = "SELECT sync_status, dataset_id,user_id, title, description,dataset_status, from_date,to_date, type_of_study_id, type_of_study_other, thematic_area_id, keywords_id, institution_id, authors_id, contact_person_name, contact_person_email,related_publication, data_type ,study_types.study_name FROM `project_datasets`LEFT JOIN study_types on project_datasets.type_of_study_id=study_types.study_type_id  where project_datasets.status='1' $qry and project_datasets.dataset_status=1 ORDER BY `dataset_id` DESC limit $page,$per_page";
									$datasetdata = mysqli_query($conn, $sqldata);
									if (mysqli_num_rows($datasetdata) > 0) {
										while ($dataset_row = mysqli_fetch_array($datasetdata)) {
											$data_status = $dataset_row['dataset_status'];
											$sync_status = $dataset_row['sync_status'];

									?>
											<div class="survey-box">
												<div class="survey-container">
													<div class="survey-heading titled">
														<div class="col-md-9">
															<h4><?php echo $dataset_row['title']; ?>
														</div>

														</h4>
													</div>
												</div>
												<div class="survey-container">
													<div class="survey-heading">
														<p></i>Type of study:</p>
													</div>
													<div class="survey-data">
														<p><?php echo $dataset_row['study_name'] ?></p>
													</div>
												</div>
												<div class="survey-container">
													<div class="survey-heading">
														<p></i> Year of study:</p>
													</div>
													<div class="survey-data">
														<p>


															<?php
															$from_date = trim($dataset_row['from_date']);
															$to_date = trim($dataset_row['to_date']);
															if ($to_date === $from_date) { ?>
														<p class="text-center"><?php echo $from_date; ?></p>
													<?php } else { ?>
														<p class="text-center"><?php echo $from_date . "-" . $to_date; ?></p>
													<?php } ?>


													<!-- <?php echo $dataset_row['from_date'] . " - " . $dataset_row['to_date']; ?> -->
													</p>
													</div>
												</div>
												<div class="survey-container">
													<div class="survey-heading">
														<p></i> Thematic Area(s) :</p>
													</div>
													<div class="survey-data">
														<p>
															<?php $category_id = $dataset_row['thematic_area_id'];
															echo getcotegryname($conn, 'categories', 'category_id', $category_id, 'category_name')
															?>
														</p>
													</div>
												</div>
												<!-- <div class="survey-container">
													<div class="survey-heading">
														<p></i> Keyword(s):</p>
													</div>

													<div class="survey-data">

														<p>

															<?php
															$keywords_id = $dataset_row['keywords_id'];
															echo getKeyWordNames($conn, 'keywords', 'keywords_id', $keywords_id, 'keyword_name')
															?>
														</p>
													</div>
												</div> -->

												<!-- <div class="survey-container">
													<div class="survey-heading">
														<p><i class="icon"></i> Description:</p>
													</div>
													<div class="survey-data">
														<p><?php echo htmlspecialchars($dataset_row['description']); ?></p>
													</div>
												</div> -->

												<!-- <div class="survey-container">
													<div class="survey-heading">
														<p></i> Author(s) :</p>
													</div>
													<div class="survey-data">


														<p><?php
															$authors_id = $dataset_row['authors_id'];
															echo getKeyWordNames($conn, 'authors', 'authors_id', $authors_id, 'author_name')
															?>
														</p>


													</div>
												</div> -->
												<div class="survey-container">
													<div class="survey-heading">
														<p></i> Institution/Organization :</p>
													</div>
													<div class="survey-data">
														<p>
															<?php
															$institution_id = $dataset_row['institution_id'];
															echo getKeyWordNames($conn, 'institution', 'institution_id', $institution_id, 'institution_name')
															?>
													</div>
													</p>
												</div>

												<!-- <div class="survey-container">
													<div class="survey-heading">
														<p></i> Recent Publication(s) :</p>
													</div>
													<div class="survey-data">
														<p>
															<?php
															echo $dataset_row['related_publication']

															?>
													</div>
													</p>
												</div> -->
												<div class="survey-container">
													<div class="survey-heading">
														<p></i> Status: </p>
													</div>
													<div class="survey-data">
														<p>
															<?php if ($data_status == '1') { ?>
																<span class="label label-success">Approved</span>
															<?php } else if ($data_status == '2') { ?>
																<span class="label label-danger">Rejected</span>
															<?php } else { ?>
																<span class="label label-warning">Pending</span>
															<?php } ?>
														</p>
													</div>
												</div>

												<!-- <a href="add-datafile.php?dataset_id=<?= $dataset_row['dataset_id'] ?>" type="submit" class="btn btn-primary mt-1">Add Data</a> -->
												<a href="dataset-details.php?dataset_id=<?= $dataset_row['dataset_id'] ?>" type="submit" class="btn btn-primary mt-1">Metadata</a>

												<?php if ($_SESSION['role_id'] == '1') { ?>
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
												<?php } ?>


												<!-- <?php if ($dataset_row['user_id'] == $_SESSION['user_id']) { ?>
													<a href="javascript:void(0);" type="submit" data-id="<?= $dataset_row['dataset_id'] ?>" class="btn btn-danger delRepository">Delete</a>
												<?php } ?> -->
												<?php
												// echo$dataset_row['user_id'] ;
												// echo$_SESSION['user_id'] ;
												if ($dataset_row['user_id'] == $_SESSION['user_id']  && $sync_status == 0) { ?>

													<!-- <a href="javascript:void(0);" data-id="<?= htmlspecialchars($dataset_row['dataset_id'], ENT_QUOTES, 'UTF-8') ?>" class="btn btn-sync btn-4cd964">Sync to dataverse</a> -->

												<?php  } ?>

											</div>

										<?php }
									} else { ?>
										<div class="survey-data text-center">
											<h3>No record found !!</h3>
											<a class="badge badge-primary" href="survey_bank.php">Refresh</a> </td>
											</tr>
										</div>
									<?php } ?>
								</div>
							</div>

						</div>
						<div class="col-md-4">
							<div class="sticky-top">
								<div class="sidebar  ">
									<div class="side-heading">
										<h4>Thematic Area(s)</h4>
									</div>
									<form action="GET">
										<div class="side-data">
											<ul>

												<?php
												$categorysssql = "SELECT category_id,category_name FROM categories where status='0' order by category_name asc";
												$datassqry = mysqli_query($conn, $categorysssql);
												while ($datacategory = mysqli_fetch_array($datassqry)) {
													// echo"SELECT COUNT(dataset_id) as total FROM project_datasets where find_in_set('" . $datacategory['category_id'] . "',category_id) and project_datasets.status='1' $qryUser ";
													$sqlcatdata = "SELECT COUNT(dataset_id) as total FROM project_datasets where find_in_set('" . $datacategory['category_id'] . "',thematic_area_id) and project_datasets.status='1' and project_datasets.dataset_status='1' $qry";
													$sqlcategory = mysqli_query($conn, $sqlcatdata);
													while ($row = mysqli_fetch_array($sqlcategory)) {
												?>

														<li>
															<a href="dataset-list.php?category_id=<?= $datacategory['category_id'] ?>&published_date=&client_name=&searchdata="><span><?php echo $datacategory['category_name']; ?></span> <span class="badg"><?php echo $row['total']; ?></span></a>
														</li>
												<?php }
												} ?>
											</ul>
										</div>
									</form>
								</div>
								<!-- <div class="sidebar">
									<div class="side-heading">
										<h4>Study Year</h4>
									</div>
									<div class="side-data">
										<ul>
											<?php
											$publish_survey = mysqli_query($conn, "SELECT COUNT(data_repository_id) as count_publish_survey,data_study_year,data_repositroy.institution_name FROM `data_repositroy` where data_repositroy.status='1' $qry $qryUser group by data_study_year order by data_repository_id ASC");
											while ($publish_row = mysqli_fetch_array($publish_survey)) {
												//$published_date=$publish_row['data_study_year'];
												//$published_date= date("Y-m-d", strtotime($published_date));
											?>

												<li>
													<a href="data_bank.php?published_date=<?php echo $publish_row['data_study_year']; ?>&category_id=&client_name=&searchdata="><span><?php echo $publish_row['data_study_year']; ?></span> <span class="badg"><?php echo $publish_row['count_publish_survey']; ?></span></a>
												</li>
											<?php } ?>
										</ul>
									</div>
								</div> -->
								<!-- <div class="sidebar">
									<div class="side-heading">
										<h4>By Institution </h4>
									</div>
									<div class="side-data">
										<ul>
											<?php
											//echo "SELECT count(id) as clientwise_survey ,institution_name,year(published_date) as published_date,data_repositroy.category_id FROM `data_repositroy` where status='0' group by institution_name";
											$sqlclient = mysqli_query($conn, "SELECT count(data_repository_id) as clientwise_survey ,institution_name,data_study_year,data_repositroy.category_id FROM `data_repositroy` where data_repositroy.status='1' $qry  group by institution_name");
											while ($rowclient = mysqli_fetch_array($sqlclient)) {

											?>
												<li>
													<a href="data_bank.php?institution_name=<?= $rowclient['institution_name'] ?>&published_date=&category_id=&searchdata="><span><?php echo $rowclient['institution_name']; ?></span> <span class="badg"><?php echo $rowclient['clientwise_survey']; ?></span></a>
												</li>
											<?php } ?>
										</ul>
									</div>
								</div> -->
							</div>

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