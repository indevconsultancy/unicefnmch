<?php include_once('includes/config.php'); ?>
<?php define("title", "List Project | MQUAD"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>

<?php
$client_qry = "";
if ($_SESSION['role_id'] == '3') {
	$client_id = $_SESSION['client_id'];
	$client_qry = " and projects.client_id='" . $client_id . "' ";
}
?>
<?php
$qry = '';
if (isset($_REQUEST['search'])) {
	if (isset($_REQUEST['project_id']) && $_REQUEST['project_id'] != '') {
		$qry = " and projects.project_id = '" . $_REQUEST['project_id'] . "' ";
	}
}
?>
<?php
//pagination
$per_page = 10;
$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$page_url = "?";
$page_url = isset($_GET['project_id']) ? $page_url . "project_id=" . $_GET['project_id'] : $page_url;
$page_url = isset($_GET['search']) ? $page_url . "&search=" . $_GET['search'] : $page_url;
$page = 0;
$current_page = 1;
if (isset($_GET['page'])) {
	$current_page = intval($_GET['page']);
	$page = ($current_page - 1) * $per_page;
}
$query = "SELECT project_id,project_name,projects.client_id,clients.name FROM `projects` left join clients on clients.id=projects.client_id where projects.status='0' $qry $client_qry order by project_id DESC";
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
					<li><i class="icon_documents_alt"></i>Project Management</li>
					<li><i class="fa fa-list"></i>List Project</li>
				</ol>
			</div>
		</div>
		<!-- page start-->
		<div class="row">
			<div class="col-sm-12">
				<div class="container-fluid">
					<form class="form-inline" method="get" role="form">
						<div class="row filter_css clearfix">
							<div class="col-lg-8" style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
								<select class="form-control select2" name="project_id" id="project_id">
									<option value="">Select Project</option>
									<?php
									$projectsql = mysqli_query($conn, "SELECT project_id,project_name FROM `projects` where status='0' $client_qry");
									while ($rowProject = mysqli_fetch_array($projectsql)) { ?>
										<option value="<?= $rowProject['project_id'] ?>">
											<?= $rowProject['project_name'] ?>
										</option>
									<?php } ?>

								</select>
							</div>
							<div class="form-group col-md-2" style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
								<button type="submit" class="btn btn-primary width-md waves-effect waves-light form-control" id="btnsearch" disabled name="search">Search</button>
							</div>
							<div class="form-group col-md-2" style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
								<a href="project-list.php" class="btn btn-primary width-md waves-effect waves-light form-control">Clear
									Filter</a>
							</div>
						</div>
					</form>
				</div>
				<section class="panel">
					<header class="panel-heading">Total Project(s):
						<?= $total_record ?>
					</header>
					<div class=" table-responsive">
						<table class=" table table-striped  ">
							<thead>
								<tr>
									<th>S.No</th>
									<th>Project Name</th>
									<th>Client Name</th>
									<th style="width: 40%;">Description</th>
									<th>Created On</th>
									<th>No. of Forms</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$_SESSION['query'] = "SELECT project_id,project_name,projects.client_id,clients.name as client_name,projects.description,projects.created_at FROM `projects` left join clients on clients.id=projects.client_id where projects.status='0' $client_qry order by project_id DESC";
								$sqlProject = "SELECT project_id,project_name,projects.client_id,clients.name,projects.description,projects.created_at FROM `projects` left join clients on clients.id=projects.client_id where projects.status='0' $qry $client_qry order by project_id DESC limit $page,$per_page";
								$qryProject = mysqli_query($conn, $sqlProject);
								$sn = 1 + $page;
								if (mysqli_num_rows($qryProject) > 0) {
									while ($row = mysqli_fetch_array($qryProject)) {
								?>
										<tr>
											<td>
												<?= $sn++; ?>
											</td>
											<td>
												<?= $row['project_name']; ?>
											</td>
											<td>
												<?= $row['name']; ?>
											</td>
											<td>
												<?= $row['description']; ?>
											</td>
											<td>
												<?= date('d-M-Y H:i:s', strtotime($row['created_at'])); ?>
											</td>
											<?php $countForm = getcounts_multi($conn, 'survey', 'id', 'del_action', 'N', 'project_id', $row['project_id']); ?>
											<td class="text-center">
												<a href="survey-list.php?pid=<?= $row['project_id'] ?>" class="btn btn-primary">
													<?= $countForm ?>
												</a>
											</td>
											<td>
												<a href="add-project.php?eid=<?= $row['project_id'] ?>" class="btn btn-sm btn-success"><i class="fa fa-pencil" aria-hidden="true"></i></a>
											</td>
										</tr>
								<?php }
								} else {
									echo '<tr><td colspan="7" class="text-center" style="font-size: 25px;"  >Records Not Found !!</td></tr>';
								} ?>
							</tbody>
						</table>
					</div>
				</section>
				<div class="d-flex d-title-box-block align-items-center justify-content-between footer-paging">
					<div class="col-md-10">
						<div class="d-flex align-items-center justify-content-between" id="pagination">
							<?= paginate($per_page, $current_page, $total_record, $total_pages, $page_url); ?>
						</div>
					</div>
					<?php

				

					$_SESSION['file_name'] = 'Project-list.csv';
					$_SESSION['header_column'] = "Project Name,Client Name,Description,Created On";
					$_SESSION['db_column'] = "project_name,client_name,description,created_at";

					?>

					<div class=" col-md-2 export-csv" style="margin-bottom: 0rem!important; padding-top: 4px">
						<a class="btn btn-success btn-sm waves-effect width-md waves-light " href="export/export.php">
							<i class="fa fa-file-excel-o" aria-hidden="true"></i> Export To CSV</a>
					</div>
				</div>
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
} ?>
<script>
	$("#project_id").on("change", function() {
		if ($("#project_id").val() != '') {
			$('#btnsearch').prop('disabled', false);
		} else {
			$('#btnsearch').prop('disabled', true);
		}
	});
</script>