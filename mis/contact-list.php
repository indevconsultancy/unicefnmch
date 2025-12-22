<?php include_once('includes/config.php'); ?>
<?php define("title", "Contact List | MQUAD"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php

$qry = '';
if (isset($_REQUEST['search'])) {
	if (isset($_REQUEST['name']) && $_REQUEST['name'] != '') {
		$qry .= " AND contacts.name='" . $_REQUEST['name'] . "'";
	}
	if (isset($_REQUEST['email']) && $_REQUEST['email'] != '') {
		$qry .= " AND contacts.email like '%" . $_REQUEST['email'] . "%'";
	}
}
?>
<?php

//pagination
$per_page = 10;

$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$page_url = "?";
$page_url = isset($_GET['name']) ? $page_url . "&name=" . $_GET['name'] : $page_url;
$page_url = isset($_GET['email']) ? $page_url . "&email=" . $_GET['email'] : $page_url;
$page_url = isset($_GET['search']) ? $page_url . "&search=" . $_GET['search'] : $page_url;

$page = 0;
$current_page = 1;
if (isset($_GET['page'])) {
	$current_page = intval($_GET['page']);
	$page = ($current_page - 1) * $per_page;
}

$query = "SELECT `id`, `name`, `email`, `phone_number`, `subject`, `comments`, `created_at`, `status` FROM `contacts` WHERE  status=1 $qry";
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
</style>
<!--main content start-->
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><i class="icon_documents_alt"></i>Support Services</li>
						<li class="breadcrumb-item" aria-current="page"><i class="fa fa-list"></i>Contact List</li>
					</ol>
				</nav>
			</div>
		</div>
		<!-- page start-->
		<div class="row">
			<div class="col-sm-12">
				<div class="container-fluid1">
					<form class="form-inline" method="get" role="form">
						<div class="row filter_css clearfix g-2">
							<div class="col-md-5" >
								<input type="text" class="form-control" name="name" placeholder="Name"></input>
							</div>
							<div class="form-group col-md-5" >
								<input type="text" class="form-control" name="email" placeholder="Email"></input>
							</div>
							<div class="form-group col-md-2" >
								<button type="submit" class="btn btn-primary width-md waves-effect waves-light form-control" name="search">Search</button>
							</div>
						</div>
					</form>
				</div>
				<section class="panel mt-3">
					<header class="panel-heading">Total Record:(s) <?= $total_record ?>
					</header>
					<div class="table-responsive">
						<table class="table table-striped">
							<thead>
								<tr>
									<th class="">S.No</th>
									<th class="">Name</th>
									<th class="">Email</th>
									<th class="">Phone Number</th>
									<th class="">Subject</th>
									<th class="">Comments</th>
									<th class="">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$_SESSION['query'] = "SELECT  `name`, `email`, `phone_number`, `subject`, `comments`, `created_at`, `status` FROM `contacts` WHERE status=1";
								$sql = "SELECT `id`, `name`, `email`, `phone_number`, `subject`, `comments`, `created_at`, `status` FROM `contacts` WHERE status=1 $qry order by id DESC limit $page,$per_page";
								$getsql = mysqli_query($conn, $sql);
								$sn = 1 + $page;
								while ($user = mysqli_fetch_array($getsql)) { ?>
									<tr>
										<td><?= $sn++; ?></td>
										<td><?= ucfirst($user['name']) ?></td>
										<td><?= $user['email'] ?></td>
										<td><?= $user['phone_number'] ?></td>
										<td><?= $user['subject'] ?></td>
										<td><?= $user['comments'] ?></td>

										<td>
											<a href="view-contact.php?cid=<?= $user['id']; ?>" class="btn btn-sm btn-primary"><i class="fa fa-eye" aria-hidden="true"></i></a>

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
			$_SESSION['header_column'] = "Name,Email,Phone Number,Subject,Comments";
			$_SESSION['db_column'] = "name,email,phone_number,subject,comments";
			?>
			<div class=" col-md-2 text-end">
				<a class="btn btn-success btn-sm waves-effect width-md waves-light " href="export/export.php">
					<i class="fa fa-file-excel-o" aria-hidden="true"></i> Export To CSV</a>
			</div>
		</div>


		<!-- page end-->
	</section>
</section>
<!--main content end-->

<?php include_once('includes/footer.php'); ?>