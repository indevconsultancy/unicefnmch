<?php include_once('includes/config.php'); ?>
<?php define("title", "Validate Excel Form | MQUAD"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php include_once('ajax_validate_xlsform.php'); ?>
<?php 

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
?>
<!--main content start-->
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><i class="icon_documents_alt"></i>Form</li>
						<li class="breadcrumb-item active" aria-current="page"><i class="fa fa-file-excel-o" aria-hidden="true"></i>Validate Excel Form</li>
					</ol>
				</nav>
			</div>
		</div>
		<!-- page start-->
		<div class="row">
			<div class="col-lg-12">

				<section class="panel">
					<header class="panel-heading">Upload Form</header>
					<div class="panel-body">
						<?php
						
						$errors = [];

						if (isset($_POST['VerifySurvey'])) {
							if (isset($_FILES['structure']) && $_FILES['structure']['error'] == 0) {
								$file_name = $_FILES['structure']['name'];
								$tmp_name = $_FILES['structure']['tmp_name'];
								$errors = verifySurvey($conn,$file_name, $tmp_name);
							} else {
								$errors[] = "File upload error.";
							}
						} 
						?>
						<div class="form">
							<form class="form-validate form-horizontal" method="post" enctype="multipart/form-data">

								<div class="row">
									<label for="cname" class="col-lg-2 col-form-label text-end">Excel File: </label>
									<div class="col-lg-10">
										<input type="file" name="structure" accept=".xls,.xlsx" class="form-control" required />
									</div>
								</div>
								<hr>

								<div class="form-group">
									<div class="col-lg-offset-2 col-lg-12 text-end">
										<button class="btn btn-primary" type="submit" name="VerifySurvey" title="Verify XLSX FORM VALIDATION">Verify</button>
										 <?php
											if (!empty($errors)) {
												echo '<button class="btn btn-danger" type="button" data-bs-toggle="modal" data-bs-target="#xlsIssues" data-bs-backdrop="static" data-bs-keyboard="false" data-bs-whatever="@fat">Error Found</button>';
											} elseif (isset($_POST['VerifySurvey'])) {
												echo '<span class="btn btn-success">Verified<span>';
											}
											?>
									</div>
								</div>
							</form>
						</div>
					</div>
				</section>
			</div>
		</div>
		<!-- page end-->
	</section>
</section>




<div class="modal fade" id="xlsIssues" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" >
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" style="font-weight: 500 !important;"  >XLS Errors</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form action="" method="POST" enctype="multipart/form-data">
				<div class="modal-body"  style="max-height: 400px; overflow: auto;">
					<h4 style="color: red;">We have found some errors in xls file.</h4>
					
					<?php
                    foreach (array_unique($errors) as $error) {
                        echo "▶ " . $error . "<br>";
                    }
                    ?>
				</div> 
				<div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button> 
                </div>
			</form>
		</div>
	</div>
</div>
<!--main content end-->
<?php include_once('includes/footer.php'); ?>
<?php if (isset($_SESSION['status_error']) && $_SESSION['status_error'] != '') { ?>
	<script>
		swal.fire({
			title: "<?php echo $_SESSION['status_error']; ?>",
			icon: "<?php echo $_SESSION['status_error_code']; ?>",
			confirmButtonColor: '#449A97',
			confirmButtonText: 'Ok'
		});
	</script>
<?php unset($_SESSION['status_error']);
}  ?>