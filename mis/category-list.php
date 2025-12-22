<?php include_once('includes/config.php'); ?>
<?php define("title", "Thematic Area	 | MQUAD"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>


<?php
$qry = '';
if (isset($_REQUEST['search'])) {
	if (isset($_REQUEST['cname']) && $_REQUEST['cname'] != '') {
		$qry = " and category_name like '%" . $_REQUEST['cname'] . "%' ";
	}
}
?>

<?php

//pagination
$per_page = 10;
$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$page_url = "?";
$page_url = isset($_GET['id']) ? $page_url . "user_type_id=" . $_GET['id'] : $page_url;
$page_url = isset($_GET['cname']) ? $page_url . "&cname=" . $_GET['cname'] : $page_url;
$page_url = isset($_GET['search']) ? $page_url . "&search=" . $_GET['search'] : $page_url;

$page = 0;
$current_page = 1;
if (isset($_GET['page'])) {
	$current_page = intval($_GET['page']);
	$page = ($current_page - 1) * $per_page;
}
$query = "select category_id,category_name from categories where status='0' $qry ";
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
		color: #fff !important;
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
		<div class="add-button-bg">
			<a href="" class="btn btn-fixed-circle" title="Add Category" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-backdrop="static" data-bs-keyboard="false" data-bs-whatever="@fat" style="border-radius: 40px;"><i class="fa fa-plus"></i></a>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><i class="fa fa-cog" aria-hidden="true"></i>Setting</li>
						<li class="breadcrumb-item" aria-current="page"><i class="fa fa-list"></i>Thematic Area</li>
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
							<div class="col-lg-10" >
								<input type="text" class="form-control" name="cname" value="<?= @$_REQUEST['cname'] ?>" placeholder="Thematic Area	">
							</div>
							<div class="col-lg-2" >
								<button type="submit" class="btn btn-primary width-md waves-effect waves-light form-control" name="search">Search</button>
							</div>
						</div>
					</form>
				</div>
				<section class="panel mt-3">
					<header class="panel-heading">Total Thematic Area : <?= $total_record ?>
					</header>
					<div class="table-responsive">
						<table class="table table-striped">
							<thead>
								<tr>
									<th class="">S.No</th>
									<th class="">Thematic Area </th>
									<th class="">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$_SESSION['query'] = "select category_id,category_name from categories where status='0' $qry ";

								$sql = "select category_id,category_name from categories where status='0' $qry limit $page,$per_page";
								$getsql = mysqli_query($conn, $sql);
								$sn = 1 + $page;
								while ($user = mysqli_fetch_array($getsql)) {
									$category_id = $user['category_id'];  ?>
									<tr>
										<td><?= $sn++; ?></td>
										<td><?= ucfirst($user['category_name']) ?> <input type="hidden" value="<?= $user['category_name'] ?>" id="update<?= $category_id; ?>" /> </td>
										<td><a href="" class="btn-sm btn-primary" title="Add Category" onclick="return editCategory(<?= $category_id; ?>)" data-bs-toggle="modal" data-bs-target="#exampleModalEdit" data-bs-backdrop="static" data-bs-keyboard="false" data-bs-whatever="@fat"><i class="fa fa-pencil-square-o"></i></a> </td>

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
				<div class="d-flex align-items-center " id="pagination">
					<?= paginate($per_page, $current_page, $total_record, $total_pages, $page_url); ?>
				</div>
			</div>
			<?php
			$_SESSION['header_column'] = "Information Area";
			$_SESSION['db_column'] = "category_name";
			?>
			<div class=" col-md-2 ">
				<a class="btn btn-success btn-sm waves-effect width-md waves-light " href="export/export.php">
					<i class="fa fa-file-excel-o" aria-hidden="true"></i> Export To CSV</a>
			</div>

		</div>


		<!-- page end-->
	</section>
</section>
<!--main content end-->

<?php
if (isset($_POST['addcategory'])) {
	$category_name = $_POST['category_name'];
	$add_category = mysqli_query($conn, "INSERT INTO categories SET category_name='" . $category_name . "' ");
	if ($add_category) {
		$_SESSION['status'] = "add-category";
		echo "<script>window.location.href='category-list.php';</script>";
	}
}

if (isset($_POST['EditCategory'])) {
	$category_name = $_POST['category_name'];
	$category_id = $_POST['category_id'];
	$add_category = mysqli_query($conn, "Update categories SET category_name='" . $category_name . "' WHERE category_id='" . $category_id . "' ");
	if ($add_category) {
		$_SESSION['statuss'] = "Edit-category";
		echo "<script>window.location.href='category-list.php';</script>";
	}
}


?>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title fw-bold" id="exampleModalLabel" style="color:#394A59;">Category</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form action="" method="POST" enctype="multipart/form-data">
				<div class="modal-body">
					<div class="form-group">
						<input type="text" name="category_name" placeholder="Category Name" class="form-control" />
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary" name="addcategory" value="submit">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="exampleModalEdit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title fw-bold" id="exampleModalLabel" style="color:#394A59;">Edit Thematic Area</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form action="" method="POST" enctype="multipart/form-data">
				<div class="modal-body">
					<div class="form-group">
						<input type="hidden" name="category_id" id="ecateid" class="form-control" />
						<input type="text" name="category_name" id="ecate" placeholder="Category Name" class="form-control" />
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary" name="EditCategory" value="submit">Update</button>
				</div>
			</form>
		</div>
	</div>
</div>



<?php include_once('includes/footer.php'); ?>
<script>
	function editCategory(val) {
		//alert(val);
		var cate = $("#update" + val).val();
		$("#ecateid").attr("value", val);
		$("#ecate").attr("value", cate);
	}
</script>
<link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<?php
if ($_SESSION['status'] == 'add-category' && $_SESSION['status'] != '') { ?>
	<script>
		toastr.success('Category Added Successfully..!', 'Success Alert', {
			timeOut: 5000
		});
	</script>
<?php $_SESSION['status'] = '';
}
?>

<?php
if ($_SESSION['statuss'] == 'Edit-category' && $_SESSION['statuss'] != '') { ?>
	<script>
		toastr.success('Category Updated Successfully..!', 'Success Alert', {
			timeOut: 5000
		});
	</script>
<?php $_SESSION['statuss'] = '';
}
?>