<?php include_once('../includes/config.php'); ?>
<?php define("title", "View Dataset(s) | MQUAD"); ?>
<?php include_once('../includes/header.php'); ?>
<?php include_once('../includes/left-sidebar.php'); ?>
<?php include_once('../includes/functions1.php'); ?>

<?php
// echo "hello";
$datasetId = (int)$_GET['dataset_id'];
$queryDataset = "SELECT dataset_status,to_date,from_date,use_of_term,dataset_id, title, description, type_of_study_id,publication_date, type_of_study_other, thematic_area_id, keywords_id, institution_id, authors_id, contact_person_name, contact_person_email, data_type, related_publication, collaboration_id, project_id, user_id,study_types.study_name,project_datasets.related_publication,project_datasets.data_type,country_name, project_datasets.state_id,collaboration_id  FROM project_datasets  
left join study_types on study_types.study_type_id=project_datasets.type_of_study_id 
left JOIN country ON project_datasets.country_id = country.country_id 
left JOIN states ON project_datasets.state_id = states.state_id 
WHERE project_datasets.status = '1' and dataset_id='" . $datasetId . "' ";
$resultDataset = mysqli_query($conn, $queryDataset);
if ($resultDataset) {
	$dataset = mysqli_fetch_array($resultDataset);
	$dataset['user_id'];
	$state_id = $dataset['state_id'];
	$collaboration_id = $dataset['collaboration_id'];
	$sqlState = mysqli_query($conn, "select GROUP_CONCAT(state_name SEPARATOR ', ') as state_name from states where state_id in (" . $state_id . ") ");
	$stateResult = mysqli_fetch_array($sqlState);
}
?>
<?php



$getuser = mysqli_query($conn,  "SELECT user_id,sync_status,dataformat_fie,no_of_downloaded,dataformat_file_id,dataset_name,dataformat_name,dataformat_fie,dataformat_file_status,data_access,created_at FROM `project_dataformat_file` WHERE dataset_id=
'" . $datasetId . "' and status='1'");
$totaldata = mysqli_num_rows($getuser);
?>


<?php
//pagination
$per_page = 10;
$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$page_url = "?";
$page_url = isset($_GET['id']) ? $page_url . "id=" . $_GET['id'] : $page_url;

$page = 0;
$current_page = 1;
if (isset($_GET['page'])) {
	$current_page = intval($_GET['page']);
	$page = ($current_page - 1) * $per_page;
}
$query = "select survey_id,created_on,survey_name,survey_data_monitoring_id from survey_data_monitoring where user_id='$userid' $clnt order by survey_data_monitoring_id desc";
$get_query = mysqli_query($conn, $query);
$total_record = mysqli_num_rows($get_query);
$total_pages = ceil($total_record / $per_page);
?>
<!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet"> -->

<style>
	.cls {
		color: white;
	}

	.row {
		margin-top: 10px;
	}

	.pagination1,
	.pagination2 {
		display: -webkit-box;
		display: -ms-flexbox;
		display: flex;
		padding-left: 0;
		list-style: none;
		border-radius: 0.25rem;
		margin-top: 10px;
	}

	.pagination1>.active>a,
	.pagination>.active>span,
	.pagination>.active>a:hover,
	.pagination>.active>span:hover,
	.pagination>.active>a:focus,
	.pagination>.active>span:focus {
		z-index: 2;
		color: #ffffff;
		background-color: #8cc63f;
		border-color: #8cc63f;
		cursor: default;
	}

	.pagination2>.active>a,
	.pagination>.active>span,
	.pagination>.active>a:hover,
	.pagination>.active>span:hover,
	.pagination>.active>a:focus,
	.pagination>.active>span:focus {
		z-index: 2;
		color: #ffffff;
		background-color: #8cc63f;
		border-color: #8cc63f;
		cursor: default;
	}

	.pagination1>li>a,
	.pagination>li>span {
		position: relative;
		float: left;
		padding: 6px 12px;
		line-height: 1.428571429;
		text-decoration: none;
		background-color: #ffffff;
		border: 1px solid #dddddd;
		margin-left: -1px;
	}

	.pagination2>li>a,
	.pagination>li>span {
		position: relative;
		float: left;
		padding: 6px 12px;
		line-height: 1.428571429;
		text-decoration: none;
		background-color: #ffffff;
		border: 1px solid #dddddd;
		margin-left: -1px;
	}

	/* Custom CSS for hover and active tab colors */
	.nav-tabs .nav-link:hover {
		background-color: #f8f9fa;
	}

	.nav-tabs .nav-link.active {
		background-color: #394a59;
		color: white !important;
	}

	.card {
		margin-bottom: 20px;
		/* Adjust margin as needed */
	}

	.panel {
		border: 1px solid #ddd;
		/* Panel border style */
		border-radius: 4px;
		/* Panel border radius */
		padding: 15px;
		/* Panel padding */
		margin-bottom: 20px;
		/* Bottom margin for spacing */
	}
</style>
<!--main content start-->
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">
				<ol class="breadcrumb">
					<li><i class="fa fa-list"></i>List Dataset(s)</li> <!-- <a href="user-list.php"></a> -->
					<li><i class="fa fa-eye"></i></i>View Dataset(s)</li>
				</ol>
			</div>
		</div>
		<section class="section-box ">
			<div class="container">
				<div class="row g-2" style="padding-left: 10px;">
					<div class="col-sm-12 col-md-12 col-lg-12">
						<div class="collabration-card mb-10">

							<div class="col-lg-12">
								<div class="row">

									<h4 class="text-dark"><b><?= $dataset['title']; ?></b>
										<h4>
								</div>
							</div>
							<div class="content-single mt-10">

								<div class="row">
									<div class="col-sm-2">
										<p>Description:</p>
									</div>
									<div class="col-sm-10">
										<p> <?= $dataset['description']; ?></p>

									</div>

								</div>

								<div class="row">
									<div class="col-sm-2">
										<p style="    margin-top: 10px;
">Year of Study:</p>
									</div>
									<div class="col-sm-10">
										<p class="text-dark">
											<?php
											$from_date = trim($dataset['from_date']);
											$to_date = trim($dataset['to_date']);
											if ($to_date === $from_date) { ?>
										<p class=""><?php echo $from_date; ?></p>
									<?php } else { ?>
										<p class=""><?php echo $from_date . "-" . $to_date; ?></p>
									<?php } ?>

									</p>

									</div>
								</div>
								<div class="row">
									<div class="col-sm-2">
										<p>Type of Study:</p>
									</div>
									<div class=" col-sm-10">
										<p class="text-dark"> <?php $typeofstudy = $dataset['type_of_study_id'];
																echo getcotegryname($conn, 'categories', 'category_id', $typeofstudy, 'category_name')

																?>

										</p>
									</div>
								</div>

								<div class="row">
									<div class="col-sm-2">
										<p>Thematic Area(s):</p>
									</div>
									<div class=" col-sm-10">
										<p class="text-dark">
											<?php
											$category_id = $dataset['thematic_area_id'];
											echo getcotegryname($conn, 'categories', 'category_id', $category_id, 'category_name')
											?>
										</p>
									</div>
								</div>
								<!-- <div class="row">
									<div class="col-sm-2">
										<p>Keyword(s):</p>
									</div>
									<div class=" col-sm-10">
										<p class="text-dark">
											<?php $keywords_id = $dataset['keywords_id'];
											echo getKeyWordNames($conn, 'keywords', 'keywords_id', $keywords_id, 'keyword_name') ?>
										</p>
									</div>
								</div> -->


								<div class="row">
									<div class="col-sm-2">
										<p>Institution(s)/Company(s):</p>
									</div>
									<div class=" col-sm-10">

										<p class="text-dark">
											<?php $institution_id = $dataset['institution_id'];
											echo getKeyWordNames($conn, 'institution', 'institution_id', $institution_id, 'institution_name') ?>

										</p>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-2">
										<p>Data Type:</p>
									</div>
									<div class="col-sm-10">
										<p class="text-dark"> <?= $dataset['data_type']; ?></p>
									</div>
								</div>
								<!-- <div class="row">
									<div class="col-sm-2">
										<p>Author(s):</p>
									</div>
									<div class="col-sm-10">
										<p class="text-dark">
											<?php $authors_id = $dataset['authors_id'];
											echo getKeyWordNames($conn, 'authors', 'authors_id', $authors_id, 'author_name') ?>
										</p>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-2">
										<p>Contact Person Name:</p>
									</div>
									<div class="col-sm-10">
										<p class="text-dark"><?= $dataset['contact_person_name']; ?></p>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-2">
										<p>Contact Person Email:</p>
									</div>
									<div class="col-sm-10">
										<p class="text-dark"><?= $dataset['contact_person_email']; ?></p>

									</div>
								</div>

								<div class="row">
									<div class="col-sm-2">
										<p>Recent Publication(s):</p>
									</div>
									<div class="col-sm-10">
										<p> <?= $dataset['related_publication']; ?></p>

										</p>
									</div>

								</div> -->
								<!-- <div class="row">
	<div class="col-sm-2">
		<p>Published:</p>
	</div>
	<div class=" col-sm-10">
		<p>
			Not Yet Published </p>
	</div>
</div> -->
							</div>

						</div>
					</div>

					<div class=" mb-3">
						<div class="col-md-12 mt-15">
							<ul class="nav nav-tabs">
								<li class="active"><a data-toggle="tab" href="#home">File(s)</a></li>
								<li><a data-toggle="tab" href="#profile">Metadata</a></li>
								<li><a data-toggle="tab" href="#messages">Terms</a></li>
							</ul>

							<!-- Tab panes -->
							<div class="tab-content">
								<div id="home" class="tab-pane fade in active">


									<section class="panel" style="padding:15px;">
										<table class="table table-striped">
											<thead>
												<tr style="background: #394a59;">
													<th class="cls">S.No</th>
													<th class="cls">Title</th>
													<th class="cls">Data Access</th>
													<th class="cls">File Format</th>
													<th class="cls">Action </th>
												</tr>
											</thead>
											<?php

											$sn = 1 + $page;
											if (mysqli_num_rows($getuser) > 0) {
												while ($user = mysqli_fetch_array($getuser)) {
													$data_status = $user['dataformat_file_status'];

											?>
													<tbody>
														<tr class="content">
															<td><?= $sn++; ?></td>
															<td><?= $user['dataset_name'] ?></td>

															<td>
																<?php
																$check = $user['data_access'];
																if ($check == 'Public') {
																?>
																	<i class="fa fa-unlock" aria-hidden="true"></i>
																<?php } else { ?>
																	<i class="fa fa-lock" aria-hidden="true"></i>
																<?php } ?>
															</td>


															<td><?= $user['dataformat_name'] ?></td>

															<td>

																<?php
																$Dataid = "D" . $datasetId;
																$locationC = "../upload_data_file/dataset/" . $Dataid . "/";
																?>

																<a href="<?php echo $locationC . $user['dataformat_fie']; ?>" download=""><i class="fa fa-download" aria-hidden="true"></i></a>
																<?php if ($_SESSION['user_id'] == $user['user_id']) { ?>
																	<a href="" data-id="<?= $user['dataformat_file_id'] ?>" class="ml-3 delRepository"><i class="fa fa-trash" aria-hidden="true"></i></a>
																<?php } ?>
																<?php if ($user['sync_status'] == '0' && $dataset['dataset_status'] == 1 && $_SESSION['user_id'] == $user['user_id']) { ?>
																	<a href="javascript:void(0);" data-id="<?= htmlspecialchars($user['dataformat_file_id'], ENT_QUOTES, 'UTF-8') ?>" class="btn-sync" data-toggle="tooltip" data-placement="top" title="Sync to Dataverse">
																		<i class="fa fa-refresh" aria-hidden="true"></i>
																	</a>
																<?php } ?>



																<?php if ($_SESSION['role_id'] == '1') { ?>
																	<?php if ($data_status == '1') { ?>
																		<a type="submit" class="btn btn-success" disabled>Approved</a>
																	<?php } else if ($data_status == '0') { ?>
																		<a href="javascript:void();" type="submit" data-id="<?= $user['dataformat_file_id'] ?>" class="btn btn-success approveRepository">Approve</a>
																	<?php } ?>
																	<?php if ($data_status == '2') { ?>
																		<a type="submit" class="btn btn-danger" disabled>Rejected</a>
																		<a href="javascript:void();" type="submit" data-id="<?= $user['dataformat_file_id'] ?>" class="btn btn-success approveRepository">Approve</a>
																	<?php } else { ?>
																		<a href="javascript:void();" type="submit" data-id="<?= $user['dataformat_file_id'] ?>" class="btn btn-danger rejectRepository">Reject</a>
																	<?php } ?>
																<?php } ?>

															</td>

														</tr>
													</tbody>
												<?php
												}
											} else { ?>
												<tr>
													<td colspan="12" align="center">No record Found !! You haven't collected any data</td>
												</tr>
											<?php } ?>
										</table>
										<nav>
											<ul class="pagination1 justify-content-end pagination-sm mt-2">
											</ul>
										</nav>
									</section>
								</div>



								<div id="profile" class="tab-pane fade">
									<section class="panel" style="padding:15px;">

										<div class="col-lg-12 col-md-12 col-sm-12 col-12">
											<div class="content-single mt-10">

												<div class="row">
													<div class="col-sm-2">
														<p>Description:</p>
													</div>
													<div class="col-sm-10">
														<p> <?= $dataset['description']; ?></p>

													</div>

												</div>

												<div class="row">
													<div class="col-sm-2">
														<p>Year of Study:</p>
													</div>
													<div class=" col-sm-10">
														<p class="text-dark">

															<?php
															$from_date = trim($dataset['from_date']);
															$to_date = trim($dataset['to_date']);
															if ($to_date === $from_date) { ?>
														<p class=""><?php echo $from_date; ?></p>
													<?php } else { ?>
														<p class=""><?php echo $from_date . "-" . $to_date; ?></p>
													<?php } ?>

													</p>

													</div>
												</div>
												<div class="row">
													<div class="col-sm-2">
														<p>Type of Study:</p>
													</div>
													<div class=" col-sm-10">
														<p class="text-dark"> <?php $typeofstudy = $dataset['type_of_study_id'];
																				echo getcotegryname($conn, 'categories', 'category_id', $typeofstudy, 'category_name')

																				?>

														</p>
													</div>
												</div>

												<div class="row">
													<div class="col-sm-2">
														<p>Thematic Area(s):</p>
													</div>
													<div class=" col-sm-10">
														<p class="text-dark">
															<?php
															$category_id = $dataset['thematic_area_id'];
															echo getcotegryname($conn, 'categories', 'category_id', $category_id, 'category_name')
															?>
														</p>
													</div>
												</div>
												<div class="row">
													<div class="col-sm-2">
														<p>Keyword(s):</p>
													</div>
													<div class=" col-sm-10">
														<p class="text-dark">
															<?php $keywords_id = $dataset['keywords_id'];
															echo getKeyWordNames($conn, 'keywords', 'keywords_id', $keywords_id, 'keyword_name') ?>
														</p>
													</div>
												</div>


												<div class="row">
													<div class="col-sm-2">
														<p>Institution(s)/Company(s):</p>
													</div>
													<div class=" col-sm-10">

														<p class="text-dark">
															<?php $institution_id = $dataset['institution_id'];
															echo getKeyWordNames($conn, 'institution', 'institution_id', $institution_id, 'institution_name') ?>

														</p>
													</div>
												</div>
												<div class="row">
													<div class="col-sm-2">
														<p>Data Type:</p>
													</div>
													<div class="col-sm-10">
														<p class="text-dark"> <?= $dataset['data_type']; ?></p>
													</div>
												</div>
												<div class="row">
													<div class="col-sm-2">
														<p>Author(s):</p>
													</div>
													<div class="col-sm-10">
														<p class="text-dark">
															<?php $authors_id = $dataset['authors_id'];
															echo getKeyWordNames($conn, 'authors', 'authors_id', $authors_id, 'author_name') ?>
														</p>
													</div>
												</div>
												<div class="row">
													<div class="col-sm-2">
														<p>Contact Person Name:</p>
													</div>
													<div class="col-sm-10">
														<p class="text-dark"><?= $dataset['contact_person_name']; ?></p>
													</div>
												</div>
												<div class="row">
													<div class="col-sm-2">
														<p>Contact Person Email:</p>
													</div>
													<div class="col-sm-10">
														<p class="text-dark"><?= $dataset['contact_person_email']; ?></p>

													</div>
												</div>

												<div class="row">
													<div class="col-sm-2">
														<p>Recent Publication(s):</p>
													</div>
													<div class="col-sm-10">
														<p> <?= $dataset['related_publication']; ?></p>

														</p>
													</div>

												</div>
												<!-- <div class="row">
													<div class="col-sm-2">
														<p>Published:</p>
													</div>
													<div class=" col-sm-10">
														<p>
															Not Yet Published </p>
													</div>
												</div> -->
											</div>
										</div>
									</section>
								</div>
								<div id="messages" class="tab-pane fade">
									<section class="panel" style="padding:15px;">

										<?= $dataset['use_of_term'] ?>
									</section>

								</div>

							</div>


						</div>
					</div>
				</div>
			</div>
		</section>

	</section>
	</div>
	</div>
	<!--start-->

	<!-- <div class="col-md-9">
		<div class="profiles-data">
			<ul class="nav nav-tabs">
				<li class="active"><a data-toggle="tab" href="#home">Data</a></li>

			</ul>
			<div class="tab-content">
				<div id="home" class="tab-pane fade in active">
					<div class="table-responsive mb-0 mt-2" id="jar">
						<section class="panel" style="padding:15px;">
							<table class="table table-striped">
								<thead>
									<tr style="background: #394a59;">
										<th class="cls">S.No</th>
										<th class="cls">Title</th>
										<th class="cls">Data Access</th>
										<th class="cls">File Format</th>
										<th class="cls">Action </th>
									</tr>
								</thead>
								<?php

								$sn = 1 + $page;
								if (mysqli_num_rows($getuser) > 0) {
									while ($user = mysqli_fetch_array($getuser)) {
										$data_status = $user['dataformat_file_status'];

								?>
										<tbody>
											<tr class="content">
												<td><?= $sn++; ?></td>
												<td><?= $user['dataset_name'] ?></td>

												<td style='text-align:center;'>
													<?php
													$check = $user['data_access'];
													if ($check == 'Public') {
													?>
														<i class="fa fa-unlock" aria-hidden="true"></i>
													<?php } else { ?>
														<i class="fa fa-lock" aria-hidden="true"></i>
													<?php } ?>
												</td>


												<td><?= $user['dataformat_name'] ?></td>

												<td>

													<?php
													$Dataid = "D" . $datasetId;
													$locationC = "../upload_data_file/dataset/" . $Dataid . "/";
													?>
													<a href="<?php echo $locationC . $user['dataformat_fie']; ?>" download=""><i class="fa fa-download" aria-hidden="true"></i></a>

													<a href="" data-id="<?= $user['dataformat_file_id'] ?>" class="ml-3  delRepository"><i class="fa fa-trash" aria-hidden="true"></i>

													</a>

													<?php if ($user['sync_status'] == '0') { ?>
														<a href="javascript:void(0);" data-id="<?= htmlspecialchars($user['dataformat_file_id'], ENT_QUOTES, 'UTF-8') ?>" class="btn-sync" data-toggle="tooltip" data-placement="top" title="Sync to Dataverse">
															<i class="fa fa-refresh" aria-hidden="true"></i>
														</a>
													<?php } ?>


													<?php if ($_SESSION['role_id'] == '1') { ?>
														<?php if ($data_status == '1') { ?>
															<a type="submit" class="btn btn-success" disabled>Approved</a>
														<?php } else if ($data_status == '0') { ?>
															<a href="javascript:void();" type="submit" data-id="<?= $user['dataformat_file_id'] ?>" class="btn btn-success approveRepository">Approve</a>
														<?php } ?>
														<?php if ($data_status == '2') { ?>
															<a type="submit" class="btn btn-danger" disabled>Rejected</a>
															<a href="javascript:void();" type="submit" data-id="<?= $user['dataformat_file_id'] ?>" class="btn btn-success approveRepository">Approve</a>
														<?php } else { ?>
															<a href="javascript:void();" type="submit" data-id="<?= $user['dataformat_file_id'] ?>" class="btn btn-danger rejectRepository">Reject</a>
														<?php } ?>
													<?php } ?>

												</td>

											</tr>
										</tbody>
									<?php
									}
								} else { ?>
									<tr>
										<td colspan="12" align="center">No record Found !! You haven't collected any data</td>
									</tr>
								<?php } ?>
							</table>
							<nav>
								<ul class="pagination1 justify-content-end pagination-sm mt-2">
								</ul>
							</nav>
						</section>

					</div>
				</div>
			</div>
		</div>
	</div> -->
	</div>

	<!-- page end-->
</section>
</section>
<!--main content end-->
<?php include_once('../includes/footer.php'); ?>

<script>
	$(".delForm").on("click", function(e) {
		let surveyid = $(this).data("id");
		e.preventDefault();
		Swal.fire({
			title: 'Are you sure to Delete this Form?',
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
						surveyIdview: surveyid
					},
					success: function(res) {
						var ress = JSON.parse(res);
						if (ress.status == "1") {
							$("#sid-" + surveyid).hide();
							Swal.fire({
								title: 'Form deleted successfully',
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
	$('.approveRepository').on('click', function(e) {
		e.preventDefault();
		var approve_data_id = $(this).data('id');
		Swal.fire({
			title: 'Are you sure to approve this Data Repository?',
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
						approve_data: approve_data_id
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
							//window.location.reload();
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
				title: 'Are you sure to reject this Data Repository?',
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
							reject_data: data_reject_id
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
								//window.location.reload();
							}
						}
					})
				}
			});
		}) <
		script >
		// Returns an array of maxLength (or less) page numbers
		// where a 0 in the returned array denotes a gap in the series.
		// Parameters:
		//   totalPages:     total number of pages
		//   page:           current page
		//   maxLength:      maximum size of returned array
		function getPageList(totalPages, page, maxLength) {
			if (maxLength < 5) throw "maxLength must be at least 5";

			function range(start, end) {
				return Array.from(Array(end - start + 1), (_, i) => i + start);
			}

			var sideWidth = maxLength < 9 ? 1 : 2;
			var leftWidth = (maxLength - sideWidth * 2 - 3) >> 1;
			var rightWidth = (maxLength - sideWidth * 2 - 2) >> 1;
			if (totalPages <= maxLength) {
				// no breaks in list
				return range(1, totalPages);
			}
			if (page <= maxLength - sideWidth - 1 - rightWidth) {
				// no break on left of page
				return range(1, maxLength - sideWidth - 1)
					.concat([0])
					.concat(range(totalPages - sideWidth + 1, totalPages));
			}
			if (page >= totalPages - sideWidth - 1 - rightWidth) {
				// no break on right of page
				return range(1, sideWidth)
					.concat([0])
					.concat(
						range(totalPages - sideWidth - 1 - rightWidth - leftWidth, totalPages)
					);
			}
			// Breaks on both sides
			return range(1, sideWidth)
				.concat([0])
				.concat(range(page - leftWidth, page + rightWidth))
				.concat([0])
				.concat(range(totalPages - sideWidth + 1, totalPages));
		}

	$(function() {
		// Number of items and limits the number of items per page
		var numberOfItems = $("#jar .content").length;
		var limitPerPage = 10;
		// Total pages rounded upwards
		var totalPages = Math.ceil(numberOfItems / limitPerPage);
		// Number of buttons at the top, not counting prev/next,
		// but including the dotted buttons.
		// Must be at least 5:
		var paginationSize = 7;
		var currentPage;

		function showPage1(whichPage) {
			if (whichPage < 1 || whichPage > totalPages) return false;
			currentPage = whichPage;
			$("#jar .content")
				.hide()
				.slice((currentPage - 1) * limitPerPage, currentPage * limitPerPage)
				.show();
			// Replace the navigation items (not prev/next):
			$(".pagination1 li").slice(1, -1).remove();
			getPageList(totalPages, currentPage, paginationSize).forEach(item => {
				$("<li>")
					.addClass(
						"page-item " +
						(item ? "current-page " : "") +
						(item === currentPage ? "active " : "")
					)
					.append(
						$("<a>")
						.addClass("page-link")
						.attr({
							href: "javascript:void(0)"
						})
						.text(item || "...")
					)
					.insertBefore("#next-page");
			});
			return true;
		}

		// Include the prev/next buttons:
		$(".pagination1").append(
			$("<li>").addClass("page-item").attr({
				id: "previous-page"
			}).append(
				$("<a>")
				.addClass("page-link")
				.attr({
					href: "javascript:void(0)"
				})
				.text("Prev")
			),
			$("<li>").addClass("page-item").attr({
				id: "next-page"
			}).append(
				$("<a>")
				.addClass("page-link")
				.attr({
					href: "javascript:void(0)"
				})
				.text("Next")
			)
		);
		// Show the page links
		$("#jar").show();
		showPage1(1);

		// Use event delegation, as these items are recreated later
		$(
			document
		).on("click", ".pagination1 li.current-page:not(.active)", function() {
			return showPage1(+$(this).text());
		});
		$("#next-page").on("click", function() {
			return showPage1(currentPage + 1);
		});

		$("#previous-page").on("click", function() {
			return showPage1(currentPage - 1);
		});
		$(".pagination1").on("click", function() {
			$("html,body").animate({
				scrollTop: 0
			}, 0);
		});
	});
</script>
<!-- //2nd -->
<script>
	// Returns an array of maxLength (or less) page numbers
	// where a 0 in the returned array denotes a gap in the series.
	// Parameters:
	//   totalPages:     total number of pages
	//   page:           current page
	//   maxLength:      maximum size of returned array
	function getPageList1(totalPages, page, maxLength) {
		if (maxLength < 5) throw "maxLength must be at least 5";

		function range(start, end) {
			return Array.from(Array(end - start + 1), (_, i) => i + start);
		}

		var sideWidth = maxLength < 9 ? 1 : 2;
		var leftWidth = (maxLength - sideWidth * 2 - 3) >> 1;
		var rightWidth = (maxLength - sideWidth * 2 - 2) >> 1;
		if (totalPages <= maxLength) {
			// no breaks in list
			return range(1, totalPages);
		}
		if (page <= maxLength - sideWidth - 1 - rightWidth) {
			// no break on left of page
			return range(1, maxLength - sideWidth - 1)
				.concat([0])
				.concat(range(totalPages - sideWidth + 1, totalPages));
		}
		if (page >= totalPages - sideWidth - 1 - rightWidth) {
			// no break on right of page
			return range(1, sideWidth)
				.concat([0])
				.concat(
					range(totalPages - sideWidth - 1 - rightWidth - leftWidth, totalPages)
				);
		}
		// Breaks on both sides
		return range(1, sideWidth)
			.concat([0])
			.concat(range(page - leftWidth, page + rightWidth))
			.concat([0])
			.concat(range(totalPages - sideWidth + 1, totalPages));
	}

	$(function() {
		// Number of items and limits the number of items per page
		var numberOfItems = $("#jar2 .content2").length;
		var limitPerPage = 10;
		// Total pages rounded upwards
		var totalPages = Math.ceil(numberOfItems / limitPerPage);
		// Number of buttons at the top, not counting prev/next,
		// but including the dotted buttons.
		// Must be at least 5:
		var paginationSize = 7;
		var currentPage;

		function showPage(whichPage) {
			if (whichPage < 1 || whichPage > totalPages) return false;
			currentPage = whichPage;
			$("#jar2 .content2")
				.hide()
				.slice((currentPage - 1) * limitPerPage, currentPage * limitPerPage)
				.show();
			// Replace the navigation items (not prev/next):
			$(".pagination2 li").slice(1, -1).remove();
			getPageList1(totalPages, currentPage, paginationSize).forEach(item => {
				$("<li>")
					.addClass(
						"page-item " +
						(item ? "current-page " : "") +
						(item === currentPage ? "active " : "")
					)
					.append(
						$("<a>")
						.addClass("page-link1")
						.attr({
							href: "javascript:void(0)"
						})
						.text(item || "...")
					)
					.insertBefore("#next-page1");
			});
			return true;
		}

		// Include the prev/next buttons:
		$(".pagination2").append(
			$("<li>").addClass("page-item1").attr({
				id: "previous-page1"
			}).append(
				$("<a>")
				.addClass("page-link1")
				.attr({
					href: "javascript:void(0)"
				})
				.text("Prev")
			),
			$("<li>").addClass("page-item1").attr({
				id: "next-page1"
			}).append(
				$("<a>")
				.addClass("page-link1")
				.attr({
					href: "javascript:void(0)"
				})
				.text("Next")
			)
		);
		// Show the page links
		$("#jar2").show();
		showPage(1);

		// Use event delegation, as these items are recreated later
		$(
			document
		).on("click", ".pagination2 li.current-page:not(.active)", function() {
			return showPage(+$(this).text());
		});
		$("#next-page1").on("click", function() {
			return showPage(currentPage + 1);
		});

		$("#previous-page1").on("click", function() {
			return showPage(currentPage - 1);
		});
		$(".pagination2").on("click", function() {
			$("html,body").animate({
				scrollTop: 0
			}, 0);
		});
	});


	$('.delRepository').on('click', function(e) {
		e.preventDefault();
		var del_data_id = $(this).data('id');
		Swal.fire({
			title: 'Are you sure to delete this Data Repository?',
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
						dataid: del_data_id
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
							//window.location.reload();
						}
					}
				})
			}
		});
	})
</script>

<script>
	$('.btn-sync').on('click', function(e) {
		e.preventDefault();
		var sync_datafile_id = $(this).data('id');
		// alert(sync_datafile_id);
		Swal.fire({
			title: 'Do you want to sync your data to dataverse?',
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
						sync_datafile_id: sync_datafile_id
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
</script>