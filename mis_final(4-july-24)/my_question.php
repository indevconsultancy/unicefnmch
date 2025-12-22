<?php include_once('includes/config.php'); ?>
<?php define("title", "My Questions | MQUAD"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php
$user_id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id'];
$qryUser = '';
$qryUser2 = '';

if ($user_id != '' and $role_id != '1') {
	$qryUser = " and (question_bank.user_id='" . $user_id . "' or question_bank.status_type='1')";
}
if ($role_id == 3) {
	$qryUser2 = " and question_bank.user_id='" . $user_id . "'";
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
$qry = '';
if (isset($_REQUEST['search'])) {
	if (isset($_REQUEST['question_bank_name']) && $_REQUEST['question_bank_name'] != '') {
		$qry = " and 	question_bank_name like '%" . $_REQUEST['question_bank_name'] . "%' ";
	}
	if (isset($_REQUEST['category_id']) && $_REQUEST['category_id'] != '') {
		$qry = " and 	categories.category_id ='" . $_REQUEST['category_id'] . "' ";
	}
	if (isset($_REQUEST['question_type']) && $_REQUEST['question_type'] != '') {
		$qry = " and 	question_type ='" . $_REQUEST['question_type'] . "' ";
	}
	if (isset($_REQUEST['theme_name']) && $_REQUEST['theme_name'] != '') {
		$qry = " and 	theme_name like '%" . $_REQUEST['theme_name'] . "%' ";
	}
}

?>

<?php

//pagination
$per_page = 10;
$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$page_url = "?";
$page_url = isset($_GET['category_id']) ? $page_url . "category_id=" . $_GET['category_id'] : $page_url;
$page_url = isset($_GET['question_type']) ? $page_url . "&question_type=" . $_GET['question_type'] : $page_url;
$page_url = isset($_GET['search']) ? $page_url . "&search=" . $_GET['search'] : $page_url;

$page = 0;
$current_page = 1;
if (isset($_GET['page'])) {
	$current_page = intval($_GET['page']);
	$page = ($current_page - 1) * $per_page;
}
//$query="select question_bank_id, question_bank_name,field_name,question_type,categories.category_name from question_bank left join categories on question_bank.category_id=categories.category_id where question_bank.status='0' $qry order by question_bank.question_bank_id DESC";
$query = "select question_bank_id,question_bank.user_id,users.name,field_name,question_type,status_type,target_group,data_source,question_bank_name,field_name,question_type,categories.category_name from question_bank left join categories on question_bank.category_id=categories.category_id  left join users on users.user_id=question_bank.user_id where question_bank.status='0' $qryUser $qryUser2 $qry order by question_bank.question_bank_id DESC";

$get_query = mysqli_query($conn, $query);
$total_record = mysqli_num_rows($get_query);
$total_pages = ceil($total_record / $per_page);
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

	.panel {
		margin-bottom: 20px;
	}

	#main-content .wrapper .row {
		margin-bottom: 0px;
	}

	/* Flexbox styling for the header */
	.d-flex {
		display: flex;
	}

	.justify-content-between {
		justify-content: space-between;
	}

	.align-items-center {
		align-items: center;
	}

	.panel-heading {
		padding: 10px;
		background-color: #f5f5f5;
		border-bottom: 1px solid #ddd;
	}

	/* Button styling */
	.btn-md {
		padding: 5px 10px;
		font-size: 14px;
	}

	/* Tooltip styling */
	.tooltips {
		cursor: pointer;
	}

	.row-bottom.text-right {
		margin-top: 4px;
		margin-bottom: 4px;
	}

	/* Custom tooltip styling */
	/* .tooltips+.tooltip>.tooltip-inner {
		background-color: #000;
		color: #fff;
		text-align: justify;
	}

	.tooltips+.tooltip.top .tooltip-arrow {
		border-top-color: #000;
	} */
</style>
<!--main content start-->
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">
				<ol class="breadcrumb">
					<li><i class="icon_documents_alt"></i>Question Bank</li>
					<li><i class="fa fa-list"></i>My Questions </li>
				</ol>
			</div>
		</div>
		<!-- page start-->
		<div class="row">
			<div class="col-sm-12">
				<div class="container-fluid">
					<form class="form-inline" method="GET" role="form">
						<div class="row filter_css clearfix">
							<div class="form-group col-md-2" style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
								<select class="form-control" name="category_id" id="category_id">
									<option value="">Thematic Area</option>
									<?php
									$categoryType = mysqli_query($conn, "select category_id,category_name from categories where status='0' order by category_name asc");
									while ($category = mysqli_fetch_array($categoryType)) {
									?>
										<option value="<?= $category['category_id'] ?>" <?php if ($category['category_id'] == $_REQUEST['category_id']) {
																							echo "selected";
																						} ?>>
											<?= $category['category_name'] ?>
										</option>
									<?php
									}
									?>
								</select>
							</div>
							<div class="form-group col-md-2" style="margin-bottom: 1rem!important; margin-top: -1rem!important;">
								<?php
								?>
								<select class="form-control" name="sub_theme" id="sub_theme">
									<option value="">Sub Theme</option>
									<?php
									if (isset($_REQUEST['category_id'])) {
										$sub_themeQuery = mysqli_query($conn, "SELECT * FROM theme WHERE status='0' AND category_id='" . $_REQUEST['category_id'] . "' order by theme_name ASC ");
										while ($sub_theme = mysqli_fetch_array($sub_themeQuery)) {

									?>
											<option value="<?= $sub_theme['theme_id'] ?>" <?php if ($sub_theme['theme_id'] == $_REQUEST['sub_theme']) {
																								echo "selected";
																							} ?>>
												<?= $sub_theme['theme_name'] ?>
											</option>

									<?php
										}
									}
									?>

								</select>
							</div>
							<div class="form-group col-md-2" style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
								<select class="form-control" name="question_type" id="question_type">
									<option value="">Question Type</option>
									<option value="select_one" <?php if ($_REQUEST['question_type'] == 'select_one') {
																	echo "selected";
																} ?>>Select one</option>
									<!-- <option value="radio_button" <?php if ($_REQUEST['question_type'] == 'radio_button') {
																			echo "selected";
																		} ?>>Radio button</option> -->
									<option value="select_multiple" <?php if ($_REQUEST['question_type'] == 'select_multiple') {
																		echo "selected";
																	} ?>>Select
										Multiple</option>
									<option value="text" <?php if ($_REQUEST['question_type'] == 'text') {
																echo "selected";
															} ?>>Text</option>
									<option value="number" <?php if ($_REQUEST['question_type'] == 'number') {
																echo "selected";
															} ?>>Number</option>
									<!--<option value="note" <?php if ($_REQUEST['question_type'] == 'note') {
																echo 'selected';
															} ?>>Note</option>-->
									<option value="date" <?php if ($_REQUEST['question_type'] == 'date') {
																echo "selected";
															} ?>>Date</option>
								</select>
							</div>
							<div class="form-group col-md-2" style="margin-bottom: 1rem!important; margin-top: -1rem!important;">
								<input type="text" class="form-control" name="question_bank_name" id="question_bank_name" value="<?= @$_REQUEST['question_bank_name'] ?>" placeholder="Question Bank Name" title="Question or Keyword(s)" data-toggle="tooltip" data-placement="top">
							</div>
							<div class="form-group col-md-2" style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
								<button type="submit" class="btn btn-primary width-md waves-effect waves-light form-control" id="btnsearch" name="search" disabled>Search</button>
							</div>
							<div class="form-group col-md-2" style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
								<a href="my_question.php" class="btn btn-primary width-md waves-effect waves-light form-control">Clear
									Filter</a>
							</div>
						</div>
					</form>

				</div>

			</div>
		</div>
		<section class="panel">
			<header class="panel-heading d-flex justify-content-between align-items-center">
				<div>Number of Question(s): <?= $total_record ?></div>
				<div class="row-bottom text-right">
					<form enctype="multipart/form-data" action="" method="post">
						<?php //if ($_SESSION['role_id'] == 1) { ?>
							<a class="btn btn-md btn-primary" name="csv" href="upload_question_bank.php"><i class="fa fa-arrow-up" aria-hidden="true"></i> Upload Question Bank</a>
						<?php //} ?>
						<a class="btn btn-md btn-primary" href="add-question-bank.php"><i class="fa fa-plus"></i> Add Question</a>
					</form>
				</div>
			</header>
			<div class="table-responsive">
				<table class="table table-striped">
					<thead>
						<tr>
							<th class="">S.No</th>
							<th style="width:30%;">Question</th>
							<th class="">Question Type</th>
							<?php if ($_SESSION['role_id'] == 1) { ?>
								<th class="">Added By</th>
							<?php } ?>
							<th class="">Thematic Area</th>
							<th class="">Source</th>
							<!-- <th style="width:8%;">Remark</th> -->
							<th class="">Status</th>
							<th class="">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$_SESSION['query'] = "select question_bank_id,question_bank.user_id,users.name,field_name,question_type,status_type,target_group,data_source,question_bank_name,field_name,question_type,categories.category_name from question_bank left join categories on question_bank.category_id=categories.category_id  left join users on users.user_id=question_bank.user_id where question_bank.status='0' $qryUser $qryUser2 $qry order by question_bank.question_bank_id DESC";
						$questionsql = "select question_bank_id,question_bank.user_id,users.name,field_name,question_type,status_type,target_group,data_source,source_link,question_bank_name,field_name,question_type,categories.category_name from question_bank left join categories on question_bank.category_id=categories.category_id  left join users on users.user_id=question_bank.user_id where question_bank.status='0' $qryUser $qryUser2 $qry order by question_bank.question_bank_id DESC limit $page,$per_page";
						$getsql = mysqli_query($conn, $questionsql);
						$sn = 1 + $page;
						if (mysqli_num_rows($getsql) > 0) {
							while ($question = mysqli_fetch_array($getsql)) {
								$qinfo = '';
								$tooltip_data = '<table><tr><td><b>Label</b></td> <td><b>Value</b></td><tr>';
								if ($question['question_type'] == 'select_one' || $question['question_type'] == 'select_multiple') {
									$getqboptions = mysqli_query($conn, "SELECT id,question_option_name,option_value FROM question_bank_option WHERE question_bank_id='" . $question['question_bank_id'] . "' order by id ASC ");
									$qbopts = mysqli_fetch_all($getqboptions, MYSQLI_ASSOC);
									foreach ($qbopts as $qbopt) {
										$question_option_name = $qbopt['question_option_name'];
										$option_value = $qbopt['option_value'];
										$tooltip_data .= '<tr><td>' . $question_option_name . '</td><td>' . $option_value . '</td></tr>';
									}
									$tooltip_data .= '</table>';
									$qinfo = '<i class="fa fa-info-circle tooltips" data-placement="top" data-toggle="tooltip" data-original-title="' . $tooltip_data . '" data-html="true" style="background-color:#FFFFFF;"></i>';
								}

						?>
								<tr id="qid-<?= $question['question_bank_id']; ?>">
									<td>
										<?= $sn++; ?>
									</td>
									<td>
										<?= $question['question_bank_name'] ?>
										<?= $qinfo; ?>
									</td>
									<td>
										<?= $question['question_type'] ?>
									</td>
									<?php if ($_SESSION['role_id'] == 1) { ?>
										<td>
											<?= $question['name'] ?>
										</td>
									<?php } ?>
									<td>
										<?= $question['category_name'] ?>
									</td>
									<td>
									<a href="<?=$question['source_link']?>" target="_BLANK">
										<?= $question['data_source'] ?>
										<i class="fa fa-link" aria-hidden="true"></i>
									</a>
									</td>
									<!-- <td>
										<?= $question['target_group'] ?>
									</td> -->
									<?php if ($_SESSION['role_id'] == '3') { ?>
										<td>
											<?php
											if ($question['status_type'] == '0') { ?>
												<span class="label label-warning">Pending</span>

											<?php
											} else if ($question['status_type'] == '1') {
											?>
												<span class="label label-success">Approved</span>
											<?php
											} else if ($question['status_type'] == '2') {
											?>
												<span class="label label-danger">Rejected</span>
											<?php
											}

											?>
										</td>
									<?php } ?>

									<?php if ($_SESSION['role_id'] == '1') { ?>
										<td>
											<form method="post" action="">
												<?php
												if ($question['status_type'] == '0') { ?>
													<button class="btn btn-warning btn-sm" data-target="#Modalassignque<?= $question['question_bank_id'] ?>" onclick="assignque(<?= $question['question_bank_id']; ?>)" ; type="button" data-toggle="modal" data-keyboard="false" data-whatever="@fat">
														Pending</button>
												<?php
												} else if ($question['status_type'] == '1') {
												?>
													<button class="btn btn-success btn-sm" data-target="#Modalassignque<?= $question['question_bank_id'] ?>" onclick="assignque(<?= $question['question_bank_id']; ?>)" ; type="button" data-toggle="modal" data-keyboard="false" data-whatever="@fat">
														Approved</button>

												<?php
												} else if ($question['status_type'] == '2') {
												?>
													<button class="btn btn-danger btn-sm " data-target="#Modalassignque<?= $question['question_bank_id'] ?>" onclick="assignque(<?= $question['question_bank_id']; ?>)" ; type="button" data-toggle="modal" data-keyboard="false" data-whatever="@fat">
														Rejected</button>
												<?php
												}

												?>
											</form>
										</td>
									<?php } ?>
									<td>
										<?php
										if ($_SESSION['user_id'] == $question['user_id']) { ?>
											<a href="edit_question_bank.php?qid=<?= $question['question_bank_id']; ?>&page=<?= $pages; ?>" class="btn-sm btn-primary" title="Edit question"><i class="fa fa-pencil-square-o"></i></a>
										<?php
										}
										?>
										<?php if ($_SESSION['user_id'] == $question['user_id'] || $_SESSION['role_id'] == '1') { ?>
											<a href="javascript:void(0);" data-id="<?= $question['question_bank_id']; ?>" class="btn-sm btn-primary delQuestions "><i class="fa fa-trash"></i></a>
										<?php } else { ?>

										<?php } ?>
									</td>
									<div class="modal fade assignque_model" id="Modalassignque<?= $question['question_bank_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
										<div class="modal-dialog modal-dialog-centered" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h1 class="modal-title" id="exampleModalLabel">
														<span>Change question bank status</span>
													</h1>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<form action="" method="POST" enctype="multipart/form-data">
													<div class="modal-body" style="background-color:white;">

														<div class="profile-inner-bg mt-0 mb-3">
															<span style="font-weight: 400;">Do you want to approve or reject the
																question?</span>
														</div>
													</div>
													<div class="modal-footer">
														<form class="login-form" action="" method="POST">
															<input type="hidden" name="question_bank_id" id="question_bank_id" class="form-control" value="<?= $question['question_bank_id']; ?>" />
															<?php
															if ($question['status_type'] == '0') {
															?>
																<button type="submit" class="btn btn-md btn-success approveID" data-id="<?= $question['question_bank_id']; ?>" name="approve">Approve</button>
																<button type="submit" class="btn btn-md btn-danger rejectID" data-id="<?= $question['question_bank_id']; ?>" name="reject">Reject</button>
															<?php
															} else if ($question['status_type'] == '1') {
															?>
																<button type="submit" class="btn btn-md btn-danger rejectID" data-id="<?= $question['question_bank_id']; ?>" name="reject">Reject</button>
																<button type="button" class="btn btn-md btn-secondary" data-dismiss="modal">Close</button>
															<?php
															} else if ($question['status_type'] == '2') {
															?>
																<button type="submit" class="btn btn-md btn-success approveID" data-id="<?= $question['question_bank_id']; ?>" name="approve">Approve</button>
																<button type="button" class="btn btn-md btn-secondary" data-dismiss="modal">Close</button>
															<?php
															}
															?>
														</form>
													</div>
												</form>
											</div>
										</div>
									</div>
								</tr>
						<?php }
						} else {
							echo '<tr><td colspan="7" class="text-center" style="font-size: 25px;"  >Records Not Found !!<br/> <a class="badge badge-primary" href="my_question.php">View All</a> </td></tr>';
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
			$_SESSION['file_name'] = 'Question-bank.csv';
			$_SESSION['header_column'] = "S.No,Question,Question Type,Thematic Area";
			$_SESSION['db_column'] = "question_bank_id,question_bank_name,question_type,category_name";
			?>
			<div class=" col-md-2 export-csv" style="margin-bottom: 0rem!important; padding-top: 5px">
				<a class="btn btn-success btn-sm waves-effect width-md waves-light " href="export/export.php">
					<i class="fa fa-file-excel-o" aria-hidden="true"></i> Export To CSV</a>
			</div>

		</div>
		<!-- page end-->
	</section>
</section>
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
} ?>


<script>
	$("#category_id,#question_type,#question_bank_name").on("change", function() {

		if ($("#category_id").val() != '' || $("#question_type").val() != '' || $("#question_bank_name").val() != '') {
			$('#btnsearch').prop('disabled', false);
		} else {
			$('#btnsearch').prop('disabled', true);
		}
	});
</script>
<script>
	$(".delQuestions").on("click", function(e) {
		let questionid = $(this).data("id");
		e.preventDefault();
		Swal.fire({
			title: 'Are you sure to Delete this Question?',
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
						questionid: questionid
					},
					success: function(res) {
						var ress = JSON.parse(res);
						if (ress.status == "1") {
							$("#qid-" + questionid).hide();
							Swal.fire({
								title: 'Question deleted successfully',
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
	$(".approveID").on("click", function(e) {
		let approveid = $(this).data("id");
		e.preventDefault();
		$.ajax({
			url: "ajax/get_ajax.php",
			type: "post",
			data: {
				approveID: approveid
			},
			success: function(res) {
				var ress = JSON.parse(res);
				if (ress.status == "1") {
					Swal.fire({
						title: 'Approved successfully',
						icon: 'success',
						confirmButtonColor: '#449A97',
						confirmButtonText: 'Ok'
					})
					$(".assignque_model").modal('hide');
					window.location.reload();
				}
			}
		})
	})
	$(".rejectID").on("click", function(e) {
		let rejectID = $(this).data("id");
		e.preventDefault();
		$.ajax({
			url: "ajax/get_ajax.php",
			type: "post",
			data: {
				rejectID: rejectID
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
					$(".assignque_model").modal('hide');
					window.location.reload();
				}
			}
		})
	})
</script>

<script>
	$(document).ready(function() {
		$('#category_id').change(function() {
			var category_id = $(this).val();
			$.ajax({
				url: 'ajax/get_sub_themes_ajax.php',
				type: 'POST',
				data: {
					category_id: category_id
				},
				success: function(response) {
					$('#sub_theme').html(response);
				}
			});
		});
	});
</script>
<script>
	$(document).ready(function() {
		$('[data-toggle="tooltip"]').tooltip();
	});
</script>