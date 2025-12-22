<?php include_once('includes/config.php'); ?>
<?php define("title", "Add Tool Archive | MQUAD"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>

<?php

if (isset($_REQUEST['submit'])) {
	$user_id = $_SESSION['user_id'];
	$client_id = $_SESSION['client_id'];
	$clientId = "C" . $client_id;
	$survey_name = $_REQUEST['survey_name'];
	// $published_date= $_REQUEST['published_date'];
	$client_name = $_REQUEST['client_name'];
	$category_id = $_REQUEST['category_id'];
	$category_id = implode(",", $category_id);
	$description = $_REQUEST['description'];
	$tool_access = $_REQUEST['tool_access'];
	$questionnaire_type = $_POST['questionnaire_type'];
	$from_date = $_POST['from_date'];
	$to_date = $_POST['to_date'];
	if ($questionnaire_type != "Link") {
		$filename = $_FILES['uploaded_questionnaire']['name'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		$filename = "" . $filename . "_" . date('his') . "." . $ext;
		$tempname = $_FILES['uploaded_questionnaire']['tmp_name'];
		$file_size = $_FILES['uploaded_questionnaire']['size'];
		$questionnaire_file = str_replace(" ", "_", $filename);
		$location = "upload_data_file/tools_archive_datafile/" . $clientId . "/";
		if (!file_exists($location)) {
			if (!mkdir($location, 0777, true)) {
				$error = 'Somthing Went wrong !!';
			} else {
				//echo "create";
				mkdir($location, 0777, true);
			}
		}
		move_uploaded_file($tempname, $location . $questionnaire_file);
	} else {
		$questionnaire_file = $_POST['questionnaire_link'];
	}

	if ($_SESSION['role_id'] == 1) {
		$tool_archive_status = 1;
	} else {
		$tool_archive_status = 0;
	}



	$insertcontribute = mysqli_query($conn, "insert into survey_bank SET survey_title='" . $survey_name . "',description='" . $description . "',category_id='" . $category_id . "',tool_access='" . $tool_access . "',client_name='" . $client_name . "',uploaded_questionnaire='" . $questionnaire_file . "',user_id='" . $user_id . "',tool_archive_status='" . $tool_archive_status . "',tool_study_year_from= '" . $from_date . "',tool_study_year_to='" . $to_date . "',questionnaire_type='" . $questionnaire_type . "'");



	//move_uploaded_file($tempname,$location."/".$questionnaire_file);

	if ($insertcontribute) {
		$_SESSION['status'] = "Tool has been successfully submitted";
		$_SESSION['status_code'] = "success";
		echo "<script>window.location.href='my_tool.php'</script>";
	} else {
		$_SESSION['status_error'] = "Something went wrong!!";
		$_SESSION['status_error_code'] = "error";
	}
}
?>
<style>
	.star {
		color: red;
	}
</style>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet">

<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">

				<ol class="breadcrumb">
					<li><i class="icon_documents_alt"></i>Tools Archive</li>
					<li><i class="fa fa-plus"></i>Add Tools</li>
				</ol>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<header class="panel-heading">Tools Archive </header>
					<div class="panel-body">
						<div class="form">
							<form class="form-horizontal" id="myForm" method="post" enctype="multipart/form-data">
								<div class="form-group ">
									<label for="cname" class="control-label col-lg-2">Name: <span class="star">*</span></label>
									<div class="col-lg-10">
										<input class="form-control" id="survey_name" name="survey_name" required type="text" />
									</div>
								</div>
								<div class="form-group ">
									<label class="form-label control-label col-lg-2" for="from_date">Year of Study: <span style="color:red; ">*</span></label>
									<div class="col-lg-10">
										<div class="row m-0">
											<div class="col-sm-6">
												<div class="form-group d-flex align-items-start ">
													<label class="form-label" for="from_date">From:</label>
													<input class="form-control" id="from_datepicker" required name="from_date" type="text" />

												</div>
											</div>
											<div class="col-sm-6">
												<div class="form-group d-flex align-items-start  ">
													<label class="form-label" for="to_date">To:</label>
													<input class="form-control" id="to_datepicker" required name="to_date" type="text" />
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="form-group ">
									<label for="cname" class="control-label col-lg-2">Thematic Area(s): <span class="star">*</span></label>
									<div class="col-lg-10">
										<div class="form-check">
											<select name="category_id[]" class="form-control select2" multiple required data-placeholder="Select Thematic Area(s)">
												<?php
												$categorysql = mysqli_query($conn, "SELECT category_id,category_name FROM categories where status='0'");
												while ($category = mysqli_fetch_array($categorysql)) { ?>

													<option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
												<?php  } ?>
											</select>
										</div>
									</div>
								</div>
								<div class="form-group ">
									<label for="cname" class="control-label col-lg-2">Institution/Organization:</span></label>
									<div class="col-lg-10">
										<input class="form-control" id="survey_name" name="client_name" type="text" />
									</div>
								</div>

								<div class="form-group ">
									<label for="cname" class="control-label col-lg-2">Tool Access: <span style="color:red; ">*</span></label>
									<div class="col-lg-10">
										<select class="form-control" name="tool_access" required>
											<option value="">Select Tool Type</option>
											<option value="Public">Public</option>
											<option value="Private">Private</option>
										</select>
									</div>
								</div>
								<div class="form-group ">
									<label for="cname" class="control-label col-lg-2">Description: <span class="star">*</span></label>
									<div class="col-lg-10">
										<textarea class="form-control" required="" placeholder="" name="description" required rows="5"></textarea>
									</div>
								</div>
								<div class="form-group">
									<label for="questionnaire_type" class="control-label col-lg-2">Questionnaire: <span style="color:red;">*</span></label>
									<div class="col-lg-10">
										<input type="radio" id="type1" required name="questionnaire_type" value="Link">
										<label for="type1"> Link</label>
										<input type="radio" id="type2" required name="questionnaire_type" value="File">
										<label for="type2"> File</label>
									</div>
								</div>

								<div id="questionnaire_link_field" style="display:none;">
									<div class="form-group">

										<label for="questionnaire_link" class="control-label col-lg-2">To Link: <span style="color:red;">*</span></label>
										<div class="col-lg-10">
											<input class="form-control" id="questionnaire_link" name="questionnaire_link" type="text" />
										</div>
									</div>
								</div>


								<div id="upload_questionnaire_field" style="display:none;">
									<div class="form-group">

										<label for="fileInput" class="control-label col-lg-2">Upload File: <span style="color:red;">*</span> </label>
										<div class="col-lg-10">
											<input class="form-control" id="fileInput" name="uploaded_questionnaire" type="file" accept=".pdf,.xls,.xlsx,.doc,.docx,.csv" title="Accepted file types: .pdf, .xls, .xlsx, .doc, .docx, .csv" />
											<span id="file_error" style="color:red;"></span>
										</div>
									</div>
								</div>


								<div class="form-group mt-xl">
									<div class="col-lg-offset-2 col-lg-10  text-right">
										<button class="btn btn-primary" type="submit" id="btnUpload" value="submit" name="submit">Submit</button>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>
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
<script>
	$(document).ready(function() {
		$("#fileInput").change(function() {
			var fileInput = $(this);
			var filePath = fileInput.val();
			var file_error = $("#file_error");
			var allowedExtensions = /(\.xlsx|\.xls|\.pdf|\.doc|\.docx|\.csv)$/i;

			if (!allowedExtensions.test(filePath)) {
				//file_error.html("Please upload a valid file (xlsx, xls, pdf, doc, docx, csv)");
				alert("Please upload a valid file (xlsx, xls, pdf, doc, docx, csv).");
				//return false;
				fileInput.val(''); // Clear the file input field
			}

		});
	});
</script>

<!-- Add Date picker -->

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

<!-- <script>
 $("#datepicker").datepicker({
    format: "yyyy",
    viewMode: "years", 
    minViewMode: "years",
    autoclose:true //to close picker once year is selected
});
</script> -->
<script>
	$(".multiple-select").select2({
		//$('.multiple-select').select2('update');
	});
</script>
<script>
	// function checkfunction() {
	// var checkBox = document.getElementById("check");
	// var text = document.getElementById("exampleModal");
	// if (checkBox.checked == true){
	// text.style.display = "block";
	// } else {
	// text.style.display = "none";
	// }
	// }
</script>
<script>
	function toggleQuestionnaireFields() {
		var linkField = document.getElementById('questionnaire_link_field');
		var fileField = document.getElementById('upload_questionnaire_field');
		var questionnaireLink = document.getElementById('questionnaire_link');
		var fileInput = document.getElementById('fileInput');

		if (document.getElementById('type1').checked) {
			linkField.style.display = 'block';
			fileField.style.display = 'none';
			questionnaireLink.setAttribute('required', 'required');
			fileInput.removeAttribute('required');
		} else if (document.getElementById('type2').checked) {
			linkField.style.display = 'none';
			fileField.style.display = 'block';
			questionnaireLink.removeAttribute('required');
			fileInput.setAttribute('required', 'required');
		}
	}

	document.addEventListener("DOMContentLoaded", function() {
		toggleQuestionnaireFields();

		document.getElementById('type1').addEventListener('change', toggleQuestionnaireFields);
		document.getElementById('type2').addEventListener('change', toggleQuestionnaireFields);
	});
</script>