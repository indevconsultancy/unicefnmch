<?php include_once('includes/config.php'); ?>
<?php define("title", "Add Repository | MQUAD"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php

if (isset($_REQUEST['submit'])) {
	$user_id = $_SESSION['user_id'];
	$client_id = $_SESSION['client_id'];
	$clientId = "C" . $client_id;
	$studyname = $_REQUEST['studyname'];
	$institution_name = $_REQUEST['institution_name'];
	$description = $_REQUEST['description'];
	$published_date = $_REQUEST['published_date'];
	$data_study_year = $_REQUEST['data_study_year'];
	$category_id = $_REQUEST['category_id'];
	$category_id = implode(",", $category_id);

	$data_name = $_REQUEST['data_name'];
	$data_access = $_REQUEST['data_access'];
	$author_name = $_REQUEST['author_name'];

	$data_format_name = $_REQUEST['data_format_name'];
	$data_formate = $_FILES['ch_for']['name'];
	$contact_email = $_REQUEST['contact_email'];
	if ($_SESSION['role_id'] == 1) {
		$data_repositroy_status = 1;
	} else {
		$data_repositroy_status = 0;
	}

	$insertdatarep = "insert into data_repositroy set  study_name='" . $studyname . "',institution_name='" . $institution_name . "',category_id='" . $category_id . "',description='" . $description . "',published_date='" . $published_date . "',data_study_year='" . $data_study_year . "',user_id='" . $user_id . "',data_repositroy_status='" . $data_repositroy_status . "' ";
	$datarepository = mysqli_query($conn, $insertdatarep);
	$last_data_repositroy_id = mysqli_insert_id($conn);

	foreach ($data_name as $data_key => $datanames) {
		$dataaccess = $data_access[$data_key];
		$authorname = $author_name[$data_key];

		$filename = $_FILES['upload_codebook']['name'][$data_key];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		$filename = "" . $datanames . "_" . $published_date . "." . $ext;
		$tempname = $_FILES['upload_codebook']['tmp_name'][$data_key];
		$file_size = $_FILES['upload_codebook']['size'][$data_key];
		$upload_codebook_file = str_replace(" ", "_", $filename);

		$location = "upload_data_file/upload_codebook/" . $clientId . "/";

		if (!file_exists($location)) {
			if (!mkdir($location, 0777, true)) {
				$error = 'Somthing Went wrong !!';
			} else {
				//echo "create";
				mkdir($location, 0777, true);
			}
		}

		$contactemail = $contact_email[$data_key];

		$insertdatarepother = "insert into data_repositroy_otherdata set data_name='" . $datanames . "',data_access='" . $dataaccess . "',upload_codebook='" . $upload_codebook_file . "',contact_email='" . $contactemail . "',author_name='" . $authorname . "',data_repository_id='" . $last_data_repositroy_id . "',user_id='" . $user_id . "' ";
		$datarep_other = mysqli_query($conn, $insertdatarepother);
		move_uploaded_file($tempname, $location . $upload_codebook_file);
		$last_data_repositroy_otherdata_id = mysqli_insert_id($conn);

		foreach ($data_format_name[$data_key] as $dataformate_key => $data_format_namess) {
			$data_formate = $_FILES['ch_for']['name'][$data_key];
			$data_formateArr = array_values(array_filter($data_formate));

			$tempname = $_FILES['ch_for']['tmp_name'][$data_key];
			$tempnameArr = array_values(array_filter($tempname));

			/* $data_formate=$_FILES['ch_for']['name'][$data_key][$dataformate_key];
							  $tempname = $_FILES['ch_for']['tmp_name'][$data_key][$dataformate_key]; */

			$data_formate = $data_formateArr[$dataformate_key];
			$tempname = $tempnameArr[$dataformate_key];

			//$location="upload_data_file/upload_dataformate_file/";

			$location = "upload_data_file/upload_dataformate_file/" . $clientId . "/";

			if (!file_exists($location)) {
				if (!mkdir($location, 0777, true)) {
					$error = 'Somthing Went wrong !!';
				} else {
					mkdir($location, 0777, true);
				}
			}

			$insertdatarepother = "insert into repository_dataformat set data_repository_id='" . $last_data_repositroy_id . "',data_repositroy_otherdata_id='" . $last_data_repositroy_otherdata_id . "', data_format_name='" . $data_format_namess . "',data_formate_file='" . $data_formate . "',user_id='" . $user_id . "' ";
			$datarep_other = mysqli_query($conn, $insertdatarepother);
			move_uploaded_file($tempname, $location . $data_formate);

			if ($insertdatarepother) {
				$_SESSION['status'] = "Your data has been successfully submitted";
				$_SESSION['status_code'] = "success";
				echo "<script>window.location.href='data_bank.php'</script>";
			} else {
				$_SESSION['status_error'] = "Something went wrong!!";
				$_SESSION['status_error_code'] = "error";
			}
		}
	}
}
?>
<style>
	.field_set {

		border: 1px groove #ddd !important;
		padding-top: 10px;
		padding-right: 5px;
		padding-bottom: 5px;
		margin: 0 0 1.5em 0 !important;
		-webkit-box-shadow: 0px 0px 0px 0px #000;
		box-shadow: 0px 0px 0px 0px #000;

		#more {
			display: none;
		}
	}

	@media (max-width: 768px) {
		.field_set {
			border: none !important;
		}
	}
</style>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet">

<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">

				<ol class="breadcrumb">
					<li><i class="icon_documents_alt"></i>Data Repository</li>
					<li><i class="fa fa-plus"></i>Add Dataset</li>
				</ol>
			</div>
		</div>
		<!-- page start-->

		<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<header class="panel-heading">Data Repository
					</header>
					<div class="panel-body">
						<div class="form">
							<form class="form-validate form-horizontal" id="myForm" method="post"
								enctype="multipart/form-data">
								<div class="form-group ">
									<label for="cname" class="control-label col-lg-2">Study Name: <span
											style="color:red;">*</span></label>
									<div class="col-lg-10">
										<input class="form-control" id="study_name" required name="studyname"
											type="text" />

									</div>
								</div>
								<div class="form-group ">
									<label for="cname" class="control-label col-lg-2">Year: <span
											style="color:red;">*</span></label>
									<div class="col-lg-10">
										<input class="form-control" id="datepicker" required name="data_study_year"
											type="text" />
									</div>
								</div>
								<div class="form-group ">
									<label for="cname" class="control-label col-lg-2">Institution: <span
											style="color:red;">*</span></label>
									<div class="col-lg-10">
										<input class="form-control" id="institution_name" required
											name="institution_name" type="text" />

									</div>
								</div>
								<div class="form-group ">
									<label for="cname" class="control-label col-lg-2">Thematic Area: <span
											style="color:red;">*</span></label>
									<div class="col-lg-10">
										<div class="form-check">
											<select name="category_id[]" class="form-control select2" multiple required
												data-placeholder="Select Thematic Area">
												<?php
												$categorysql = mysqli_query($conn, "SELECT category_id,category_name FROM categories where status='0'");
												while ($categoryform = mysqli_fetch_array($categorysql)) { ?>

													<option value="<?php echo $categoryform['category_id']; ?>">
														<?php echo $categoryform['category_name']; ?>
													</option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
								<fieldset class="field_set" id="more">
									<!--<legend>Data Repository data:</legend>-->
									<div class="form-group ">
										<label for="cname" class="control-label col-lg-2">Data Name: <span
												style="color:red;">*</span></label>
										<div class="col-lg-10">
											<input class="form-control" id="data_name" required name="data_name[]"
												type="text" />

										</div>
									</div>

									<div class="form-group ">
										<label for="cname" class="control-label col-lg-2">Data Access: <span
												style="color:red; ">*</span></label>
										<div class="col-lg-10">
											<select class="form-control" required name="data_access[]">
												<option value="">Select Data Type</option>
												<option value="Public">Public</option>
												<option value="Private">Private</option>
											</select>
										</div>
									</div>
									<div class="form-group ">
										<label for="cname" class="control-label col-lg-2">Data Format: </label>
										<div class="col-lg-10">
											<div class=" row ss">
												<div class="col-lg-2">
													<div class="form-check">
														<input type="checkbox" value="SAS" name="data_format_name[0][]">
														SAS
													</div>
												</div>
												<div class="col-lg-10">
													<input type="file" name="ch_for[0][]" accept=".sas"
														style="display:none" class="form-control ch_for ">
												</div>
											</div>
											<div class=" row ss">
												<div class="col-lg-2">
													<div class="form-check">
														<input type="checkbox" value="JSON"
															name="data_format_name[0][]"> JSON
													</div>
												</div>
												<div class="col-lg-10">
													<input type="file" name="ch_for[0][]" accept=".json"
														style="display:none" class="form-control ch_for ">
												</div>
											</div>
											<div class=" row ss">
												<div class="col-lg-2">
													<div class="form-check">
														<input type="checkbox" value="Excel"
															name="data_format_name[0][]"> Excel
													</div>
												</div>
												<div class="col-lg-10">
													<input type="file" name="ch_for[0][]" accept=".xlxs,.xls"
														style="display:none" class="form-control ch_for ">
												</div>
											</div>
											<div class=" row ss">
												<div class="col-lg-2">
													<div class="form-check">
														<input type="checkbox" value="SPSS"
															name="data_format_name[0][]"> SPSS
													</div>
												</div>
												<div class="col-lg-10">
													<input type="file" name="ch_for[0][]" accept=".spss"
														style="display:none" class="form-control ch_for ">
												</div>
											</div>
											<div class=" row ss">
												<div class="col-lg-2">
													<div class="form-check">
														<input type="checkbox" value="Stata"
															name="data_format_name[0][]"> Stata
													</div>
												</div>
												<div class="col-lg-10">
													<input type="file" name="ch_for[0][]" accept=".stata"
														style="display:none" class="form-control ch_for ">
												</div>
											</div>

										</div>
									</div>
									<div class="form-group ">
										<label for="cname" class="control-label col-lg-2">Upload Code Book: <span
												style="color:red;">*</span></label>
										<div class="col-lg-10">
											<input class="form-control codebookFile" required name="upload_codebook[]"
												accept=".pdf,.xls,.xlsx,.doc,.docx,.csv" type="File" />

										</div>
									</div>

									<div class="form-group ">
										<label for="cname" class="control-label col-lg-2">Email: <span
												style="color:red;">*</span></label>
										<div class="col-lg-10">
											<input class="form-control" required id="meta_data" name="contact_email[]"
												type="email" />

										</div>
									</div>
									<div class="form-group ">
										<label for="cname" class="control-label col-lg-2">Author: <span
												style="color:red;">*</span></label>
										<div class="col-lg-10">
											<input class="form-control" required id="author_name" name="author_name[]"
												type="text" />
										</div>
									</div>
								</fieldset>
								<div id='readId'></div>
								<button type="button" class="btn btn-primary btn-sm" onclick="myFunction(1)"
									style="float: right;">Add More</button></br>
								<div class="form-group" style="margin-top: 17px;">
									<label for="cname" class="control-label col-lg-2">Description: <span
											style="color:red;">*</span></label>
									<div class="col-lg-10">
										<textarea class="form-control" required placeholder="" name="description"
											rows="5"></textarea>
									</div>
								</div>
								<div class="form-group ">
									<label for="cname" class="control-label col-lg-2">Publication Date: <span
											style="color:red;">*</span></label>
									<div class="col-lg-10">
										<input class="form-control" id="datepicker" required
											max="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>"
											value="<?php echo date('Y-m-d'); ?>" name="published_date" type="date" />
									</div>
								</div>

								<div class="form-group">
									<div class="col-lg-offset-2 col-lg-10  text-right">
										<button class="btn btn-primary" type="submit" id="submit"
											name="submit">Submit</button>
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
<!--main content end-->
<?php include_once('includes/footer.php'); ?>

<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
} ?>
<script>
	$(document).ready(function () {
		$(".codebookFile").change(function () {
			var codebookFile = $(this);
			var filePath = codebookFile.val();
			var file_error = $("#file_error");
			var allowedExtensions = /(\.xlsx|\.xls|\.pdf|\.doc|\.docx|\.csv)$/i;

			if (!allowedExtensions.test(filePath)) {
				//file_error.html("Please upload a valid file (xlsx, xls, pdf, doc, docx, csv)");
				alert("Please upload a valid file (xlsx, xls, pdf, doc, docx, csv).");
				//return false;
				codebookFile.val(''); // Clear the file input field
			}

		});
	});
</script>
<script type="text/javascript">
	$(".multiple-select").select2({
		//maximumSelectionLength: 2
	});
	$("#datepicker").datepicker({
		format: "yyyy",
		viewMode: "years",
		minViewMode: "years",
		autoclose: true //to close picker once year is selected
	});
</script>
<script>
	$(document).ready(function () {
		$('.ss input:checkbox').on('click', function () {
			$(this).closest('.ss').find('.ch_for').toggle();
		})
	});


</script>
<script>
	var sss = 1;
	function myFunction(val) {
		var readmoreid = 1;
		//alert(sss);
		$.ajax({
			type: 'post',
			url: 'add_databank_ajax.php',
			data: { readmoreid: readmoreid, sss: sss },
			success: function (responsedata) {
				// alert(responsedata);
				$('#readId').append(responsedata);
			}
		});
		sss++;
	}

	$(document).on("click", ".remv", function () {
		$(this).parent().remove();
	});
</script>