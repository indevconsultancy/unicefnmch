<?php include_once('includes/config.php'); ?>
<?php define("title", "Show Tool | MQUAD"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php

$username = mysqli_real_escape_string($conn, $_SESSION['username']);
$user_id = mysqli_real_escape_string($conn, $_SESSION['user_id']);
$role_id = mysqli_real_escape_string($conn, $_SESSION['role_id']);
$qryUser = '';

if ($user_id != '' and $role_id != '1') {
	$qryUser = " and (survey_bank.user_id='" . $user_id . "' or tool_archive_status='1')";
}
?>

<?php
$qry = '';
$qrycat = '';
if (isset($_REQUEST['searchdata'])) {
	if (isset($_REQUEST['survey_title']) && $_REQUEST['survey_title'] != '') {
		$qry .= " AND survey_bank.survey_title like '%" . $_REQUEST['survey_title'] . "%'";
	}
	if (isset($_REQUEST['category_id']) && $_REQUEST['category_id'] != '') {

		$qry .= "AND find_in_set('" . $_REQUEST['category_id'] . "',survey_bank.category_id)";
		$qrycat .= "AND find_in_set('" . $_REQUEST['category_id'] . "',categories.category_id)";
	}
	if (isset($_REQUEST['from_date']) && $_REQUEST['tool_study_year'] != '') {
		$qry .= "AND survey_bank.tool_study_year='" . $_REQUEST['tool_study_year'] . "'";
	}
	if (isset($_REQUEST['client_name']) && $_REQUEST['client_name'] != '') {
		$qry .= "AND survey_bank.client_name='" . $_REQUEST['client_name'] . "'";
	}
	if (isset($_REQUEST['from_date']) && $_REQUEST['from_date'] != '') {
		$qry = " and tool_study_year_from ='" . $_REQUEST['from_date'] . "' ";
	}
	if (isset($_REQUEST['to_date']) && $_REQUEST['to_date'] != '') {
		$qry = " and tool_study_year_to ='" . $_REQUEST['to_date'] . "' ";
	}
}
?>
<?php
$per_page = 10;

$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$page_url = "?";
$page_url = isset($_GET['survey_title']) ? $page_url . "&survey_title=" . $_GET['survey_title'] : $page_url;
$page_url = isset($_GET['category_id']) ? $page_url . "&category_id=" . $_GET['category_id'] : $page_url;
$page_url = isset($_GET['tool_study_year']) ? $page_url . "&tool_study_year=" . $_GET['tool_study_year'] : $page_url;
$page_url = isset($_GET['client_name']) ? $page_url . "&client_name=" . $_GET['client_name'] : $page_url;
$page_url = isset($_GET['search']) ? $page_url . "&search=" . $_GET['search'] : $page_url;

$page = 0;
$current_page = 1;
if (isset($_GET['page'])) {
	$current_page = intval($_GET['page']);
	$page = ($current_page - 1) * $per_page;
}
$query = "SELECT id,survey_bank.user_id,users.name,survey_title,questionnaire_type,description,client_name,survey_bank.category_id,survey_bank.published_date,tool_study_year_from,tool_study_year_to,survey_bank.source,survey_bank.uploaded_questionnaire,survey_bank.tool_archive_status,survey_bank.tool_access FROM `survey_bank` left join users on users.user_id=survey_bank.user_id where survey_bank.status='0' $qryUser $qry order by id DESC";

$get_query = mysqli_query($conn, $query);
$total_record = mysqli_num_rows($get_query);
$total_pages = ceil($total_record / $per_page);
?>

<style>
	#main-content .wrapper .row {
		margin-bottom: 0px;
	}

	.panel {
		margin-bottom: 20px;
	}

	.panel .panel-heading {
		margin-top: -10px;
	}
</style>
<link href="<?= base_url(); ?>assets/sweetalerts/sweetalert2.min.css" rel="stylesheet">
<script src="<?= base_url(); ?>assets/sweetalerts/sweetalert2.all.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet">
<!--main content start-->
<section id="main-content">
	<div class="wrapper">
		<div class="row">
			<div class="col-lg-12">
				<div class="row">
					<div class="col-sm-12 text-center">
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 text-center">
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
					</div>
				</div>
				<ol class="breadcrumb">
					<li><i class="icon_documents_alt"></i>Tools Archive</li>
					<li><i class="fa fa-list"></i></i>Show Tools</li>
				</ol>
				<div class="container-fluid">
				</div>
			</div>
		</div>
		<!-- page start-->
		<div class="row">
			<div class="col-sm-12">
				<div class="panel panel-default">
					<div class="row m-0">
						<div class="col-md-12">
							<form method="GET" style="padding: 10px 0px;">
								<div class="row">
									<div class="col-sm-4">
										<div class="search-widget">
											<input id="survey_title" class="form-control" type="text" name="survey_title" placeholder="Name	" title="Name" data-toggle="tooltip" data-placement="top" aria-label="Search all data" value="<?= $_REQUEST['survey_title'] ?>">
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
										<a href="survey_bank.php" class="btn btn-primary width-md waves-effect waves-light form-control">Clear
											Filter</a>
									</div>
								</div>
							</form>
						</div>
						<div class="col-md-8">
							<div class="row pl-0 pr-0">
								<div class="col-md-12">
									<?php
									$sqlcontribute = "SELECT id,survey_bank.user_id,questionnaire_type,users.client_id,users.name,survey_title,description,client_name,survey_bank.category_id,survey_bank.published_date,tool_study_year_from,tool_study_year_to,survey_bank.source,survey_bank.uploaded_questionnaire,survey_bank.tool_archive_status,survey_bank.tool_access FROM `survey_bank` left join users on users.user_id=survey_bank.user_id where survey_bank.status='0' $qryUser $qry order by id DESC limit $page,$per_page";
									$contribute_survey = mysqli_query($conn, $sqlcontribute);
									if (mysqli_num_rows($contribute_survey) > 0) {
										while ($contribute_row = mysqli_fetch_array($contribute_survey)) {
											$published_date = $contribute_row['published_date'];
											$published_year = date("d-m-Y", strtotime($published_date));
											$category_id = $contribute_row['category_id'];
											$tool_archive_status = $contribute_row['tool_archive_status'];
											$tool_access = $contribute_row['tool_access'];


									?>
											<?php
											$clientid = $contribute_row['client_id'];
											$cid = "C" . $clientid;

											?>
											<div class="survey-box" id="tools_id-<?= $contribute_row['id']; ?>">
												<div class="survey-container">
													<div class="survey-heading titled">
														<div class="col-md-8">
															<h4><?php echo $contribute_row['survey_title']; ?>
														</div>
														<div class="col-md-4">
															<span style="float: right;color:white;margin-top: 14px;" class=""><?= $tool_access; ?></span>
															<!-- <?php //if ($contribute_row['user_id'] == $user_id) { ?>
																<a type="submit" href="edit-survey-bank.php?eid=<?php //$contribute_row['id'] ?>" class="btn btn-success">Edit</a>
																<a href="javascript();" data-id="<?php //$contribute_row['id']; ?>" class="btn btn-danger deltools">Delete</a>
															<?php //} ?> -->
														</div>
														</h4>
													</div>
												</div>
												<div class="survey-container">
													<div class="survey-heading">
														<p><i class="fa fa-calendar"></i> Year of Study:</p>
													</div>
													<div class="survey-data">
														<p> <?= $contribute_row['tool_study_year_from'] . "-" . $contribute_row['tool_study_year_to'] ?>
														</p>

													</div>
												</div>

												<div class="survey-container">
													<div class="survey-heading">
														<p><i class="fa fa-list-alt"></i> Thematic Area(s):</p>
													</div>
													<div class="survey-data">
														<?php
														$sqlcdata = "SELECT GROUP_CONCAT(category_name) as category_name FROM `categories` WHERE `category_id` IN (" . $category_id . ") $qrycat";
														$sqlcates = mysqli_query($conn, $sqlcdata);
														$rows = mysqli_fetch_array($sqlcates);
														// while($rows=mysqli_fetch_array($sqlcates)){
														// $categories[]=$rows['category_name'];
														// }
														//print_r($categories);
														?>

														<!--<p><?php //echo implode(',',array_unique($categories));
																?></p>-->
														<p><?php echo $rows['category_name']; ?></p>
													</div>
												</div>
												<div class="survey-container">
													<div class="survey-heading">
														<p><i class="fa fa-building"></i> Institution/Organization:</p>
													</div>
													<div class="survey-data">
														<p><?php echo $contribute_row['client_name']; ?></p>
													</div>
												</div>
													<div class="survey-container">
														<div class="survey-heading">
															<p><i class="fa fa-download"></i> Questionnaire: </p>
														</div>
														<div class="survey-data">
														<?php if ($contribute_row['questionnaire_type'] == "File") { ?>
															<p><a href="upload_data_file/tools_archive_datafile/<?= $cid; ?>/<?php echo $contribute_row['uploaded_questionnaire']; ?>" target="_blank"><?php echo $contribute_row['uploaded_questionnaire']; ?></a></p>
														<?php } else { ?>
															<p><a href="<?php echo $contribute_row['uploaded_questionnaire']; ?>" target="_blank"><?php echo $contribute_row['uploaded_questionnaire']; ?></a></p>
														<?php }  ?>
														</div>
													</div>
												<div class="row">
													<div class="col-md-12" style="margin-top: 15px;">
														<div class="pull-right">
															<a href="view_tools.php?id=<?= $contribute_row['id']; ?>" class="label label-primary">View More</a>
														</div>
													</div>
												</div>

												<?php if ($_SESSION['role_id'] == 1 && $tool_access == 'Public') { ?>
													<?php if ($tool_archive_status == '1') { ?>
														<a type="submit" class="btn btn-success" disabled>Approved</a>
													<?php } else if ($tool_archive_status == '0') { ?>
														<a href="javascript:void(0);" data-id="<?= $contribute_row['id']; ?>" class="btn btn-success toolApprove">Approve</a>
													<?php } ?>

													<?php if ($tool_archive_status == '2') { ?>
														<a type="submit" class="btn btn-danger" disabled>Rejected</a>
														<a href="javascript:void(0);" data-id="<?= $contribute_row['id']; ?>" class="btn btn-success toolApprove">Approve</a>
													<?php } else { ?>
														<a href="javascript:void(0);" data-id="<?= $contribute_row['id']; ?>" class="btn btn-danger toolreject">Reject</a>
													<?php } ?>
												<?php } ?>
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
							<div class="panel">
								<div class="d-flex d-title-box-block align-items-center justify-content-between footer-paging">
									<div class="col-md-10">
										<div class="d-flex align-items-center justify-content-between" id="pagination">
											<?= paginate($per_page, $current_page, $total_record, $total_pages, $page_url); ?>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="sticky-top">
								<h5> <b>Total Tool(s): <?= $total_record ?></b></h5>
								<div class="sidebar  ">
									<div class="side-heading">
										<h4>Thematic Area(s)</h4>
									</div>
									<form action="GET">
										<div class="side-data">
											<ul>
												<?php
												$categorysssql = "SELECT category_id,category_name FROM categories where status='0'";
												$datassqry = mysqli_query($conn, $categorysssql);
												while ($datacategory = mysqli_fetch_array($datassqry)) {

													$sqlcatdata = "SELECT COUNT(id) as total FROM survey_bank where find_in_set('" . $datacategory['category_id'] . "',category_id) and survey_bank.status='0' $qryUser";
													$sqlcategory = mysqli_query($conn, $sqlcatdata);
													while ($row = mysqli_fetch_array($sqlcategory)) {
												?>

														<li>
															<a href="survey_bank.php?category_id=<?= $datacategory['category_id'] ?>&tool_study_year=&client_name=&searchdata="><span><?php echo $datacategory['category_name']; ?></span> <span class="badg"><?php echo $row['total']; ?></span></a>
														</li>
												<?php }
												} ?>
											</ul>
										</div>
									</form>
								</div>
								<!-- <div class="sidebar">
									<div class="side-heading">
										<h4>Publication Year</h4>
									</div>
									<div class="side-data">
										<ul>
											<?php
											// $publishyear_survey = mysqli_query($conn, "SELECT COUNT(survey_bank.id) as count_year_survey,tool_study_year,survey_bank.category_id,survey_bank.client_name FROM `survey_bank` where survey_bank.status='0' $qryUser $qry GROUP BY survey_bank.tool_study_year order by survey_bank.id ASC");
											// while ($publish_row = mysqli_fetch_array($publishyear_survey)) {

											?>
												<li>
													<a href="survey_bank.php?tool_study_year=<?php //echo $publish_row['tool_study_year']; 
																								?>&category_id=&client_name=&searchdata="><span><?php //echo $publish_row['tool_study_year']; 
																																				?></span> <span class="badg"><?php //echo $publish_row['count_year_survey']; 
																																												?></span></a>
												</li>
											<?php //} 
											?>
										</ul>
									</div>
								</div> -->
								<!-- <div class="sidebar">
									<div class="side-heading">
										<h4>By Institution</h4>
									</div>
									<div class="side-data">
										<ul>
											<?php
											// $sqlclient = mysqli_query($conn, "SELECT count(id) as clientwise_survey ,client_name,tool_study_year,survey_bank.category_id FROM `survey_bank` where status='0' $qryUser $qry group by client_name");
											// while ($rowclient = mysqli_fetch_array($sqlclient)) {
											?>
												<li>
													<a href="survey_bank.php?client_name=< $rowclient['client_name'] ?>&tool_study_year=&category_id=&searchdata="><span><?php //echo $rowclient['client_name']; 
																																											?></span> <span class="badg"><?php //echo $rowclient['clientwise_survey']; 
																																																			?></span></a>
												</li>
											<?php //} 
											?>
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
<?php include_once('includes/footer.php'); ?>
<!-- Optional JavaScript -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>

<script>
	$(".toolApprove").on("click", function(e) {
		let tool_approve_id = $(this).data("id");
		//alert(approve_id);
		e.preventDefault();
		Swal.fire({
			title: 'Are you sure to approve this tool archive?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#449A97',
			cancelButtonColor: '#449A97',
			confirmButtonText: 'Approve'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: "ajax/get_ajax.php",
					type: "post",
					data: {
						approve_id: tool_approve_id
					},
					success: function(res) {
						console.log(res);
						var ress = JSON.parse(res);
						if (ress.status == "1") {
							Swal.fire({
								title: 'Approve successfully',
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
	$(".toolreject").on("click", function(e) {
		let tool_reject_id = $(this).data("id");
		e.preventDefault();
		Swal.fire({
			title: 'Are you sure to reject this tool archive?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#449A97',
			cancelButtonColor: '#449A97',
			confirmButtonText: 'Reject'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: "ajax/get_ajax.php",
					type: "post",
					data: {
						reject_id: tool_reject_id
					},
					success: function(res) {
						console.log(res);
						var ress = JSON.parse(res);
						if (ress.status == "1") {
							Swal.fire({
								title: 'Rejected successfully',
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
	$(".deltools").on("click", function(e) {
		let tool_del_id = $(this).data("id");
		e.preventDefault();
		Swal.fire({
			title: 'Are you sure to delete this tool archive?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#449A97',
			cancelButtonColor: '#449A97',
			confirmButtonText: 'Delete'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: "ajax/get_ajax.php",
					type: "post",
					data: {
						tool_del_id: tool_del_id
					},
					success: function(res) {
						console.log(res);
						var ress = JSON.parse(res);
						if (ress.status == "1") {
							$("#tools_id-" + tool_del_id).hide();
							Swal.fire({
								title: 'Deleted successfully',
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
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>

<script>
    $("#category_id,#survey_title,#from_datepicker,#to_datepicker").on("change", function() {

        if ($("#category_id").val() != '' || $("#survey_title").val() != '' || $("#from_datepicker").val() != '' || $("#to_datepicker").val() != '') {
            $('#btnsearch').prop('disabled', false);
        } else {
            $('#btnsearch').prop('disabled', true);
        }
    });
</script>