<?php include_once('includes/config.php'); ?>
<?php define("title", "Show Questions | MQUAD"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php
$user_id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id'];
$qryUser = '';

if ($user_id != '' and $role_id != '1') {
	$qryUser = " and (question_bank.user_id='" . $user_id . "' or question_bank.status_type='1')";
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
$query = "select question_bank_id, question_bank_name,field_name,question_type,categories.category_name from question_bank left join categories on question_bank.category_id=categories.category_id where question_bank.status='0' $qry order by question_bank.question_bank_id DESC";

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
</style>
<!--main content start-->
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">

				<ol class="breadcrumb">
					<li><i class="icon_documents_alt"></i>Question Bank</li>
					<li><i class="fa fa-list"></i>Show Questions </li>
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
																						} ?>><?= $category['category_name'] ?></option>
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
										$sub_themeQuery = mysqli_query($conn, "SELECT * FROM theme WHERE status='0' AND category_id='" . $_REQUEST['category_id'] . "' ");
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

									<option value="select_multiple" <?php if ($_REQUEST['question_type'] == 'select_multiple') {
																		echo "selected";
																	} ?>>Select Multiple</option>
									<option value="text" <?php if ($_REQUEST['question_type'] == 'text') {
																echo "selected";
															} ?>>Text</option>
									<option value="number" <?php if ($_REQUEST['question_type'] == 'number') {
																echo "selected";
															} ?>>Number</option>
									<option value="note" <?php if ($_REQUEST['question_type'] == 'note') {
																echo 'selected';
															} ?>>Note</option>
									<option value="date" <?php if ($_REQUEST['question_type'] == 'date') {
																echo "selected";
															} ?>>Date</option>
								</select>
							</div>
							<div class="form-group col-md-2" style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
								<input type="text" class="form-control" name="question_bank_name" id="question_bank_name" value="<?= @$_REQUEST['question_bank_name'] ?>" placeholder="Question Bank Name" title="Question or Keyword(s)" data-toggle="tooltip" data-placement="top"></input>
							</div>
							<div class="form-group col-md-2" style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
								<button type="submit" class="btn btn-primary width-md waves-effect waves-light form-control" id="btnsearch" disabled name="search">Search</button>
							</div>
							<div class="form-group col-md-2" style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
								<a href="question-bank-list.php" class="btn btn-primary width-md waves-effect waves-light form-control">Clear Filter</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<section class="panel">
			<header class="panel-heading">Number of Question(s): <?= $total_record ?>
			</header>
			<div class="table-responsive">
				<table class="table  table-striped">
					<thead>
						<tr>
							<th class="">S.No</th>
							<th style="width:30%;">Question</th>
							<th class="">Question Type</th>
							<?php if ($_SESSION['role_id'] == 1) { ?>
								<th class="">Added By</th>
							<?php  }  ?>
							<th class="">Thematic Area</th>
							<th class="">Source</th>
							<!-- <th style="width:8%;">Remark</th> -->
						</tr>
					</thead>
					<tbody>
						<?php
						$_SESSION['query'] = "select question_bank_id, question_bank_name,field_name,question_type,categories.category_name from question_bank left join categories on question_bank.category_id=categories.category_id where question_bank.status='0' $qry order by question_bank.question_bank_id ASC";
						$questionsql = "select question_bank_id,question_bank.user_id,users.name,field_name,question_type,status_type,target_group,data_source,question_bank_name,field_name,question_type,categories.category_name from question_bank left join categories on question_bank.category_id=categories.category_id  left join users on users.user_id=question_bank.user_id where question_bank.status='0' and status_type=1 $qryUser $qry order by question_bank.question_bank_id DESC limit $page,$per_page";
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
								<tr>
									<td><?= $sn++; ?></td>
									<td><?= $question['question_bank_name'] ?> <?= $qinfo; ?></td>
									<td><?= $question['question_type'] ?></td>
									<?php if ($_SESSION['role_id'] == 1) { ?>
										<td><?= $question['name'] ?> </td>
									<?php  }  ?>
									<td><?= $question['category_name'] ?></td>
									<td>
									<?= $question['data_source'] ?>
									</td>
									<!-- <td><?php //$question['target_group'] ?></td> -->
								</tr>
						<?php }
						} else {
							echo '<tr><td colspan="7" class="text-center" style="font-size: 25px;"  >Records Not Found !!</td></tr>';
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
}  ?>
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

</script>
<script>
	$(document).ready(function() {
		$('[data-toggle="tooltip"]').tooltip();
	});
</script>